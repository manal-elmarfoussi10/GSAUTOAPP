<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientsController extends Controller
{
    /**
     * Show a client's dossier for support (superadmin + client_service).
     * Bypasses tenant/global scopes. Soft-deletes are included only if the model uses SoftDeletes.
     */
    public function show($id)
    {
        // Allow superadmin + client_service only
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, [User::ROLE_SUPERADMIN, User::ROLE_CLIENT_SERVICE], true),
            403
        );

        // Disable debugbar if present
        if (class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class)) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        // Defensive column list
        $candidateCols = [
            'id','company_id','prenom','nom_assure','plaque',
            'email','telephone','adresse','kilometrage','type_vitrage',
            'ancien_modele_plaque','nom_assurance','numero_police','numero_sinistre',
            'autre_assurance','date_sinistre','date_declaration','raison','reparation',
            'connu_par','adresse_pose','precision',
            'statut_signature','statut_verif_bdg','statut_envoi','statut_relance','statut_termine',
            'statut_interne',
            'encaisse','type_cadeau','reference_interne','reference_client',
            'created_at',
        ];
        $existingCols = Schema::getColumnListing('clients');
        $selectCols   = array_values(array_intersect($candidateCols, $existingCols));

        // Start query without tenant/global scopes
        $query = Client::query()->withoutGlobalScopes();

        // Include soft-deleted rows only if the model uses SoftDeletes
        if (in_array(SoftDeletes::class, class_uses_recursive(Client::class), true)) {
            $query->withTrashed();
        }

        $client = $query
            ->when($selectCols, fn ($q) => $q->select($selectCols))
            ->with([
                'factures'        => fn($q) => $q->latest()->limit(100),
                'factures.avoirs' => fn($q) => $q,
                'devis'           => fn($q) => $q->latest()->limit(100),
                'photos'          => fn($q) => $q->latest()->limit(20),
                'conversations' => function ($q) {
                    $q->with([
                        'creator:id,name',
                        'emails' => function ($q) {
                            $q->select(
                                    'id','thread_id','sender_id','receiver_id','content',
                                    'file_path','file_name','created_at'
                                )
                                ->latest()->limit(10)
                                ->with([
                                    'senderUser:id,name',
                                    'receiverUser:id,name',
                                    'replies' => function ($qr) {
                                        $qr->select(
                                            'id','email_id','sender_id','receiver_id','content',
                                            'file_path','file_name','created_at'
                                        )
                                        ->latest()->limit(10)
                                        ->with(['senderUser:id,name','receiverUser:id,name']);
                                    }
                                ]);
                        }
                    ])->latest();
                }
            ])
            ->findOrFail((int) $id);

        $statutLabel = $this->deriveStatutLabel($client);

        // Users for the “new conversation” select
        $companyIdForUsers = $client->company_id ?? auth()->user()->company_id;
        $users = User::query()
            ->when($companyIdForUsers, fn($q) => $q->where('company_id', $companyIdForUsers))
            ->select('id','name')
            ->orderBy('name')
            ->get();

        return view('superadmin.clients.show', compact('client','users','statutLabel'));
    }

    protected function deriveStatutLabel(Client $client): string
    {
        if ((int)($client->statut_termine   ?? 0) === 1) return 'Terminé';
        if ((int)($client->statut_relance   ?? 0) === 1) return 'Relancé';
        if ((int)($client->statut_envoi     ?? 0) === 1) return 'Envoyé';
        if ((int)($client->statut_verif_bdg ?? 0) === 1) return 'Vérification BDG';
        if ((int)($client->statut_signature ?? 0) === 1) return 'Signé';
        return 'En attente';
    }

    /**
     * Export dossier as PDF for support (superadmin + client_service).
     * Avoid implicit binding to prevent scope issues.
     */
    public function exportPdf($id)
    {
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, [User::ROLE_SUPERADMIN, User::ROLE_CLIENT_SERVICE], true),
            403
        );

        $query = Client::query()->withoutGlobalScopes();
        if (in_array(SoftDeletes::class, class_uses_recursive(Client::class), true)) {
            $query->withTrashed();
        }

        $client = $query->findOrFail((int) $id);
        $client->load(['factures.avoirs', 'factures', 'devis', 'photos']);

        config(['dompdf.public_path' => base_path('public')]);

        $pdf = Pdf::setOptions([
                'isRemoteEnabled' => true,
                'chroot'          => base_path('public'),
            ])
            ->loadView('clients.pdf', compact('client'));

        return $pdf->download('client_'.$client->id.'_'.now()->format('Ymd_His').'.pdf');
    }
}