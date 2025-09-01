<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $hasUnits = Schema::hasColumn('companies','units');

        // KPIs
        $stats = [
            'companies_total'     => Company::count(),
            'units_total'         => $hasUnits ? (int) Company::sum('units') : 0,
            'companies_new_month' => Company::where('created_at', '>=', now()->startOfMonth())->count(),
            'low_units_count'     => $hasUnits ? Company::where('units', '<', 5)->count() : 0,
        ];

        // Low units list
        $lowUnits = $hasUnits
            ? Company::select('id','name','email','units')
                ->where('units','<',5)
                ->orderBy('units')
                ->limit(10)
                ->get()
            : collect([]);

        // Recent companies (for the left list)
        $recentCompanies = Company::latest()
            ->limit(6)
            ->get(['id','name','email','units','created_at'])
            ->map(function ($c) {
                $c->created_human = $c->created_at ? $c->created_at->diffForHumans() : '—';
                return $c;
            });

        // Chart #1: companies created per month (last 12 months)
        $months = collect(range(0, 11))->map(fn ($i) => now()->startOfMonth()->subMonths($i))->reverse()->values();
        $companiesByMonth = $months->map(function ($m) {
            return [
                'label' => $m->format('M Y'),
                'value' => Company::whereBetween('created_at', [$m, $m->copy()->endOfMonth()])->count(),
            ];
        });

        $labelsCompanies = $companiesByMonth->pluck('label');
        $dataCompanies   = $companiesByMonth->pluck('value');

        // Chart #2: Top companies by units
        $top = $hasUnits
            ? Company::select('name','units')->orderByDesc('units')->limit(8)->get()
            : collect([]);

        $labelsTopUnits = $top->pluck('name');
        $dataTopUnits   = $top->pluck('units');

        // Page still expects these keys from your previous design; we map them:
        // (We won’t use money/dossiers; just fill meaningful values)
        $totalHT          = $stats['units_total']; // showing total units here to reuse your card
        $marge            = $stats['companies_total'];
        $depenses         = $stats['companies_new_month'];
        $dossiersActifs   = $stats['low_units_count'];
        $nouveauxDossiers = $stats['companies_new_month'];

        // Chart datasets mapped to your variables
        $labels        = $labelsCompanies; // for "CA" line chart (we repurpose title as "Nouvelles sociétés / mois")
        $data          = $dataCompanies;
        $dossiersLabels= $labelsTopUnits;  // for bar chart (Top sociétés par unités)
        $dossiersData  = $dataTopUnits;

        // For the table we’ll display low units; reuse $statsParAssurance var name to avoid editing your table scaffold
        $statsParAssurance = $lowUnits;

        return view('superadmin.dashboard', compact(
            // KPIs mapped to your original card placeholders
            'totalHT','marge','depenses','dossiersActifs','nouveauxDossiers',
            // charts
            'labels','data','dossiersLabels','dossiersData',
            // lists
            'recentCompanies',
            // reusing the table block name
            'statsParAssurance'
        ));
    }
}