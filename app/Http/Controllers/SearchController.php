<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $companyId = auth()->user()->company_id;

        if ($q === '') {
            return view('search.index', [
                'q' => '',
                'clients' => collect(),
                'devis' => collect(),
                'factures' => collect(),
            ]);
        }

        // -------- Clients (inchangé) --------
        $clientsQuery = Client::query()->where('company_id', $companyId);
        $clientsSelect = ['id'];
        $possibleClientCols = ['prenom','nom','nom_assure','telephone','email'];
        foreach ($possibleClientCols as $col) {
            if (Schema::hasColumn('clients', $col)) {
                $clientsSelect[] = $col;
                $clientsQuery->orWhere($col, 'like', "%{$q}%");
            }
        }
        $clients = $clientsQuery->orderBy('updated_at','desc')->limit(15)->get(array_unique($clientsSelect));

        /* ====================== DEVIS ======================
           -> company_id = X
           -> recherche texte uniquement sur `titre`           */
        $devis = Devis::query()
            ->where('company_id', $companyId)
            ->where('titre', 'like', "%{$q}%")
            ->orderByDesc('updated_at')
            ->limit(15)
            ->get(['id','client_id','titre','updated_at','company_id']);

        /* ==================== FACTURES =====================
           -> company_id = X
           -> recherche texte uniquement sur `titre`
           (si tu veux aussi par numero: ajoute orWhere('numero','like',...)) */
        $factures = Facture::query()
            ->where('company_id', $companyId)
            ->where('titre', 'like', "%{$q}%")
            ->orderByDesc('updated_at')
            ->limit(15)
            ->get(['id','client_id','numero','titre','updated_at','company_id']);

        return view('search.index', compact('q','clients','devis','factures'));
    }

    public function suggest(Request $request)
    {
        $q = trim($request->get('q', ''));
        if ($q === '') return response()->json([]);

        $companyId = auth()->user()->company_id;

        // -------- Clients (inchangé) --------
        $clientsQ = Client::query()->where('company_id', $companyId);
        if (Schema::hasColumn('clients','prenom')) $clientsQ->orWhere('prenom','like',"%{$q}%");
        if (Schema::hasColumn('clients','nom')) $clientsQ->orWhere('nom','like',"%{$q}%");
        if (Schema::hasColumn('clients','nom_assure')) $clientsQ->orWhere('nom_assure','like',"%{$q}%");
        $clients = $clientsQ->limit(5)->get()->map(function($c){
            $prenom = $c->prenom ?? '';
            $nom = $c->nom ?? $c->nom_assure ?? '';
            return [
                'type'=>'client',
                'label'=> trim($prenom.' '.$nom) ?: "Client #{$c->id}",
                'url'=> route('clients.show',$c->id),
                'icon'=> 'fa-user'
            ];
        });

        // -------- Devis (suggest) : company_id + titre --------
        $devis = Devis::query()
            ->where('company_id', $companyId)
            ->where('titre', 'like', "%{$q}%")
            ->limit(5)
            ->get(['id','titre'])
            ->map(function($d){
                return [
                    'type'=>'devis',
                    'label'=> $d->titre ?: "Devis #{$d->id}",
                    'url'=> route('devis.show',$d->id),
                    'icon'=> 'fa-file-invoice'
                ];
            });

        // -------- Factures (suggest) : company_id + titre --------
        $factures = Facture::query()
            ->where('company_id', $companyId)
            ->where('titre', 'like', "%{$q}%")
            ->limit(5)
            ->get(['id','numero','titre'])
            ->map(function($f){
                $label = $f->titre ?: ("Facture #".($f->numero ?: $f->id));
                return [
                    'type'=>'facture',
                    'label'=> $label,
                    'url'=> route('factures.show',$f->id),
                    'icon'=> 'fa-file-invoice-dollar'
                ];
            });

        return response()->json($clients->merge($devis)->merge($factures)->take(10)->values());
    }
}

