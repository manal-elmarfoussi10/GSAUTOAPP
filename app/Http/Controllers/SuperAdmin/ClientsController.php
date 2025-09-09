<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientsController extends Controller
{
    public function show($id)
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        if (class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class)) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        // Pick only client columns that actually exist (defensive)
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

        $client = Client::query()
            ->when(!empty($selectCols), fn($q) => $q->select($selectCols))
            ->with([
                // Keep these relaxed to avoid “unknown column” surprises
                'factures'        => fn($q) => $q->latest()->limit(100),
                'factures.avoirs' => fn($q) => $q,     // no explicit select
                'devis'           => fn($q) => $q->latest()->limit(100),
                'photos'          => fn($q) => $q->latest()->limit(20),

                // ⬇️ DO NOT force a select list here (your table doesn’t have creator_id)
                'conversations' => function ($q) {
                    $q->with([
                        // This will simply return null if your relation/column doesn’t exist
                        'creator:id,name',
                        'emails' => function ($q) {
                            $q->select('id','thread_id','sender_id','receiver_id','content',
                                       'file_path','file_name','created_at')
                              ->latest()->limit(10)
                              ->with([
                                  'senderUser:id,name',
                                  'receiverUser:id,name',
                                  'replies' => function ($qr) {
                                      $qr->select('id','email_id','sender_id','receiver_id','content',
                                                  'file_path','file_name','created_at')
                                         ->latest()->limit(10)
                                         ->with(['senderUser:id,name','receiverUser:id,name']);
                                  }
                              ]);
                        }
                    ])->latest();
                }
            ])
            ->findOrFail($id);

        $statutLabel = $this->deriveStatutLabel($client);

        $companyIdForUsers = $client->company_id ?? auth()->user()->company_id;
        $users = User::where('company_id', $companyIdForUsers)->select('id','name')->get();

        // NOTE: view path now superadmin/clients/show.blade.php as in your tree
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

// app/Http/Controllers/Superadmin/ClientsController.php

public function exportPdf(Client $client)
{
    abort_unless(auth()->user()?->role === 'superadmin', 403);

    // Load only what the PDF needs
    $client->load(['factures.avoirs', 'factures', 'devis', 'photos']);

    // Make sure DomPDF knows where /public is and can load assets
    config([
        'dompdf.public_path' => base_path('public'),
    ]);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isRemoteEnabled' => true,           // allow absolute URLs (e.g. url('/storage/...'))
            'chroot'          => base_path('public'), // sandbox root for local files
        ])
        ->loadView('clients.pdf', compact('client')); // <-- file at resources/views/clients/pdf.blade.php

    $filename = 'client_' . $client->id . '_' . now()->format('Ymd_His') . '.pdf';
    return $pdf->download($filename);
}
}