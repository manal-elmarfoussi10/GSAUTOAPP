<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Facture;

class DashboardController extends Controller
{
    public function index()
    {
        // Company context
        $companyId = auth()->user()->company_id;

        // Total HT (Factures)
        $totalHT = Facture::where('company_id', $companyId)->sum('total_ht');

        // Marge / Dépenses (placeholders — adapt if you track these per company)
        $marge    = 25000;
        $depenses = 18500;

        // Dossiers actifs (clients)
        $dossiersActifs = Client::where('company_id', $companyId)->count();

        // Nouveaux dossiers ce mois-ci
        $nouveauxDossiers = Client::where('company_id', $companyId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Chiffre d'affaires par mois
        $chiffreAffaireParMois = Facture::selectRaw('MONTH(date_facture) as mois, SUM(total_ht) as total')
            ->where('company_id', $companyId)
            ->whereNotNull('date_facture')
            ->groupByRaw('MONTH(date_facture)')
            ->orderByRaw('MONTH(date_facture)')
            ->get();

        $labels = [];
        $data   = [];
        foreach ($chiffreAffaireParMois as $stat) {
            $labels[] = date('M', mktime(0, 0, 0, $stat->mois, 1));
            $data[]   = $stat->total;
        }

        // Dossiers par mois
        $dossiersParMois = Client::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->where('company_id', $companyId)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        $dossiersLabels = [];
        $dossiersData   = [];
        foreach ($dossiersParMois as $stat) {
            $dossiersLabels[] = date('M', mktime(0, 0, 0, $stat->mois, 1));
            $dossiersData[]   = $stat->total;
        }

        // Statistiques par assurance (table prefixes to avoid ambiguity)
        $statsParAssurance = DB::table('clients')
            ->select(
                'clients.nom_assurance',
                DB::raw('COUNT(*) as total_clients'),
                DB::raw('AVG(factures.total_ht) as panier_moyen'),
                DB::raw('SUM(factures.total_ht) as part_euro')
            )
            ->leftJoin('factures', 'factures.client_id', '=', 'clients.id')
            ->whereNotNull('clients.nom_assurance')
            ->where('clients.company_id', $companyId)   // <-- disambiguated
            ->groupBy('clients.nom_assurance')
            ->get();

        return view('dashboard', [
            'totalHT'         => $totalHT,
            'marge'           => $marge,
            'depenses'        => $depenses,
            'dossiersActifs'  => $dossiersActifs,
            'nouveauxDossiers'=> $nouveauxDossiers,
            'labels'          => $labels,
            'data'            => $data,
            'dossiersLabels'  => $dossiersLabels,
            'dossiersData'    => $dossiersData,
            'statsParAssurance' => $statsParAssurance,
        ]);
    }
}