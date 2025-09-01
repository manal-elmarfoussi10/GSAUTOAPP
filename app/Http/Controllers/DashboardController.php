<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Rdv;
use App\Models\Facture;

class DashboardController extends Controller
{
    public function index()
    {
        // Total HT
        $totalHT = Facture::sum('total_ht');

        // Marge
        $marge = 25000;

        // Dépenses 
        $depenses = 18500;

        // Dossiers actifs (clients)
        $dossiersActifs = Client::count();

        // Nouveaux dossiers ce mois-ci
        $nouveauxDossiers = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Statistiques mensuelles (CA)
        $chiffreAffaireParMois = Facture::selectRaw("MONTH(date_facture) as mois, SUM(total_ht) as total")
            ->groupBy(DB::raw("MONTH(date_facture)"))
            ->orderBy(DB::raw("MONTH(date_facture)"))
            ->get();

        $labels = [];
        $data = [];

        foreach ($chiffreAffaireParMois as $stat) {
            $labels[] = date('M', mktime(0, 0, 0, $stat->mois, 1));
            $data[] = $stat->total;
        }

        // Nombre de dossiers par mois
        $dossiersParMois = Client::selectRaw("MONTH(created_at) as mois, COUNT(*) as total")
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->orderBy(DB::raw("MONTH(created_at)"))
            ->get();

        $dossiersLabels = [];
        $dossiersData = [];

        foreach ($dossiersParMois as $stat) {
            $dossiersLabels[] = date('M', mktime(0, 0, 0, $stat->mois, 1));
            $dossiersData[] = $stat->total;
        }

        // Statistiques par assurance
        $statsParAssurance = Client::select(
                'nom_assurance',
                DB::raw('COUNT(*) as total_clients'),
                DB::raw('AVG(factures.total_ht) as panier_moyen'),
                DB::raw('SUM(factures.total_ht) as part_euro')
            )
            ->leftJoin('factures', 'factures.client_id', '=', 'clients.id')
            ->whereNotNull('nom_assurance')
            ->groupBy('nom_assurance')
            ->get();

        return view('dashboard', [
            'totalHT' => $totalHT,
            'marge' => $marge,
            'depenses' => $depenses,
            'dossiersActifs' => $dossiersActifs,
            'nouveauxDossiers' => $nouveauxDossiers,
            'labels' => $labels,
            'data' => $data,
            'dossiersLabels' => $dossiersLabels,
            'dossiersData' => $dossiersData,
            'statsParAssurance' => $statsParAssurance,
        ]);
    }
}
