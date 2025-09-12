<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Avoir;
use App\Models\Paiement;
use App\Models\Company;
use App\Models\Rdv;
use App\Models\Expense;
use App\Models\BonDeCommande;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Poseur;
use App\Models\Stock;

class FilesController extends Controller
{
    /** Build a base query. For support users (superadmin + client_service) we remove global scopes. */
    private function q(string $modelClass)
    {
        $user = auth()->user();
        $isSupport = $user && in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_CLIENT_SERVICE], true);
        return $isSupport ? $modelClass::query()->withoutGlobalScopes()
                          : $modelClass::query();
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && in_array($user->role, [User::ROLE_SUPERADMIN, User::ROLE_CLIENT_SERVICE], true), 403);

        $type = $request->input('type', 'clients');

        $companies = $this->q(Company::class)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        [$columns, $results] = $this->buildQuery($type, $request, true);

        return view('superadmin.files.index', [
            'filters'   => [
                'type'       => $type,
                'company_id' => $request->input('company_id'),
                'date_from'  => $request->input('date_from'),
                'date_to'    => $request->input('date_to'),
                'q'          => $request->input('q'),
            ],
            'columns'   => $columns,
            'results'   => $results,
            'companies' => $companies,
        ]);
    }

    public function export(Request $request)
    {
        // si tu veux autoriser le service client à exporter, ajoute ROLE_CLIENT_SERVICE ici
        abort_unless(auth()->user() && auth()->user()->role === User::ROLE_SUPERADMIN, 403);

        $type = $request->input('type', 'clients');
        [$columns, $rows] = $this->buildQuery($type, $request, false);

        $filename = 'export_'.$type.'_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($columns, $rows, $type) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));     // UTF-8 BOM
            fputcsv($out, array_values($columns), ';');       // header

            foreach ($rows as $row) {
                $line = [];
                foreach (array_keys($columns) as $key) {
                    $line[] = self::renderCell($type, $key, $row, true);
                }
                fputcsv($out, $line, ';');
            }
            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    /** Build columns + dataset for each type with filters. */
    private function buildQuery(string $type, Request $request, bool $paginate): array
    {
        $dateFrom   = $request->input('date_from');
        $dateTo     = $request->input('date_to');
        $companyId  = $request->input('company_id');
        $q          = trim((string) $request->input('q', ''));

        // choose existing product name column
        $productNameCol = Schema::hasColumn('produits','designation') ? 'designation'
                         : (Schema::hasColumn('produits','name') ? 'name'
                         : (Schema::hasColumn('produits','nom') ? 'nom' : null));

        switch ($type) {
            case 'devis': {
                $columns = [
                    'created_at' => 'Date',
                    'client'     => 'Client',
                    'titre'      => 'Titre',
                    'total_ht'   => 'Total HT',
                    'total_ttc'  => 'Total TTC',
                ];

                $query = $this->q(Devis::class)
                    ->with(['client' => function($q){
                        $q->select('id','nom_assure','prenom','email','telephone','company_id');
                    }])
                    ->latest('created_at');

                if ($companyId) {
                    $query->whereHas('client', function(Builder $c) use ($companyId) {
                        if (Schema::hasColumn('clients','company_id')) {
                            $c->where('company_id', $companyId);
                        }
                    });
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('titre','like',"%$q%")
                          ->orWhereHas('client', function(Builder $c) use ($q) {
                              $c->where('nom_assure','like',"%$q%")
                                ->orWhere('prenom','like',"%$q%")
                                ->orWhere('email','like',"%$q%")
                                ->orWhere('telephone','like',"%$q%");
                          });
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'factures': {
                $columns = [
                    'date_facture' => 'Date',
                    'client'       => 'Client',
                    'titre'        => 'Titre',
                    'total_ht'     => 'Total HT',
                    'total_ttc'    => 'Total TTC',
                    'is_paid'      => 'Payée',
                ];

                $query = $this->q(Facture::class)
                    ->with(['client' => function($q){
                        $q->select('id','nom_assure','prenom','email','telephone','company_id');
                    }])
                    ->latest('date_facture');

                if ($companyId) {
                    $query->whereHas('client', function(Builder $c) use ($companyId) {
                        if (Schema::hasColumn('clients','company_id')) {
                            $c->where('company_id', $companyId);
                        }
                    });
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'date_facture');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('titre','like',"%$q%")
                          ->orWhereHas('client', function(Builder $c) use ($q) {
                              $c->where('nom_assure','like',"%$q%")
                                ->orWhere('prenom','like',"%$q%")
                                ->orWhere('email','like',"%$q%")
                                ->orWhere('telephone','like',"%$q%");
                          });
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'avoirs': {
                $columns = [
                    'created_at' => 'Date',
                    'client'     => 'Client',
                    'facture'    => 'Facture',
                    'montant'    => 'Montant',
                ];

                $query = $this->q(Avoir::class)
                    ->with(['facture.client' => function($q){
                        $q->select('id','nom_assure','prenom','email','telephone','company_id');
                    }])
                    ->latest('created_at');

                if ($companyId) {
                    $query->whereHas('facture.client', function(Builder $c) use ($companyId) {
                        if (Schema::hasColumn('clients','company_id')) {
                            $c->where('company_id', $companyId);
                        }
                    });
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('montant','like',"%$q%")
                          ->orWhereHas('facture', function(Builder $f) use ($q) {
                              $f->where('titre','like',"%$q%");
                          })
                          ->orWhereHas('facture.client', function(Builder $c) use ($q) {
                              $c->where('nom_assure','like',"%$q%")
                                ->orWhere('prenom','like',"%$q%")
                                ->orWhere('email','like',"%$q%")
                                ->orWhere('telephone','like',"%$q%");
                          });
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'paiements': {
                $columns = [
                    'date_paiement' => 'Date',
                    'client'        => 'Client',
                    'facture'       => 'Facture',
                    'montant'       => 'Montant',
                    'methode'       => 'Méthode',
                ];

                $dateCol  = Schema::hasColumn('paiements','date') ? 'date'
                           : (Schema::hasColumn('paiements','date_paiement') ? 'date_paiement' : 'created_at');

                $query = $this->q(Paiement::class)
                    ->with(['facture.client' => function($q){
                        $q->select('id','nom_assure','prenom','email','telephone','company_id');
                    }])
                    ->orderBy($dateCol, 'desc');

                if ($companyId) {
                    $query->whereHas('facture.client', function(Builder $c) use ($companyId) {
                        if (Schema::hasColumn('clients','company_id')) {
                            $c->where('company_id', $companyId);
                        }
                    });
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, $dateCol);

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('methode','like',"%$q%")
                          ->orWhere('methode_paiement','like',"%$q%")
                          ->orWhere('montant','like',"%$q%")
                          ->orWhereHas('facture', function(Builder $f) use ($q) {
                              $f->where('titre','like',"%$q%");
                          })
                          ->orWhereHas('facture.client', function(Builder $c) use ($q) {
                              $c->where('nom_assure','like',"%$q%")
                                ->orWhere('prenom','like',"%$q%")
                                ->orWhere('email','like',"%$q%")
                                ->orWhere('telephone','like',"%$q%");
                          });
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'rdv':
            case 'rdvs': {
                $columns = [
                    'start_time' => 'Début',
                    'end_time'   => 'Fin',
                    'client'     => 'Client',
                    'poseur'     => 'Poseur',
                    'title'      => 'Titre',
                    'status'     => 'Statut',
                ];

                $query = $this->q(Rdv::class)
                    ->with([
                        'client:id,nom_assure,prenom,email,telephone,company_id',
                        'poseur:id,nom,email,telephone'
                    ])
                    ->orderBy('start_time','desc');

                if ($companyId) {
                    if (Schema::hasColumn('rdvs','company_id')) {
                        $query->where('company_id',$companyId);
                    } elseif (Schema::hasColumn('rdvs','client_id') && Schema::hasColumn('clients','company_id')) {
                        $query->whereHas('client', fn(Builder $c)=>$c->where('company_id',$companyId));
                    }
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'start_time');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('title','like',"%$q%")
                          ->orWhere('status','like',"%$q%")
                          ->orWhereHas('client', fn(Builder $c)=>$c
                              ->where('nom_assure','like',"%$q%")
                              ->orWhere('prenom','like',"%$q%")
                              ->orWhere('email','like',"%$q%")
                              ->orWhere('telephone','like',"%$q%"))
                          ->orWhereHas('poseur', fn(Builder $p)=>$p
                              ->where('nom','like',"%$q%")
                              ->orWhere('email','like',"%$q%")
                              ->orWhere('telephone','like',"%$q%"));
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'depenses':
            case 'expenses': {
                $columns = [
                    'date'          => 'Date',
                    'client'        => 'Client',
                    'fournisseur'   => 'Fournisseur',
                    'paid_status'   => 'Statut',
                    'ht_amount'     => 'HT',
                    'ttc_amount'    => 'TTC',
                ];

                $query = $this->q(Expense::class)
                    ->with([
                        'client:id,nom_assure,prenom,email,telephone,company_id',
                        'fournisseur:id,nom_societe,company_id'
                    ])
                    ->orderBy('date','desc');

                if ($companyId) {
                    if (Schema::hasColumn('expenses','company_id')) {
                        $query->where('company_id',$companyId);
                    } else {
                        $query->where(function(Builder $w) use ($companyId) {
                            $w->whereHas('client', fn(Builder $c)=>$c->where('company_id',$companyId))
                              ->orWhereHas('fournisseur', fn(Builder $f)=>$f->where('company_id',$companyId));
                        });
                    }
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'date');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('paid_status','like',"%$q%")
                          ->orWhere('description','like',"%$q%")
                          ->orWhereHas('client', fn(Builder $c)=>$c
                              ->where('nom_assure','like',"%$q%")
                              ->orWhere('prenom','like',"%$q%")
                              ->orWhere('email','like',"%$q%")
                              ->orWhere('telephone','like',"%$q%"))
                          ->orWhereHas('fournisseur', fn(Builder $f)=>$f
                              ->where('nom_societe','like',"%$q%"));
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'bons':
            case 'bons_de_commande':
            case 'purchase_orders': {
                $columns = [
                    'date'          => 'Date',
                    'numero'        => 'Numéro',
                    'fournisseur'   => 'Fournisseur',
                    'total_ht'      => 'Total HT',
                    'total_ttc'     => 'Total TTC',
                    'status'        => 'Statut',
                ];

                $dateCol = Schema::hasColumn('bon_de_commandes','date') ? 'date' : 'created_at';

                $query = $this->q(BonDeCommande::class)
                    ->with('fournisseur:id,nom_societe,company_id')
                    ->orderBy($dateCol,'desc');

                if ($companyId && Schema::hasColumn('bon_de_commandes','company_id')) {
                    $query->where('company_id',$companyId);
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, $dateCol);

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('numero','like',"%$q%")
                          ->orWhere('status','like',"%$q%")
                          ->orWhereHas('fournisseur', fn(Builder $f)=>$f->where('nom_societe','like',"%$q%"));
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'fournisseurs': {
                $columns = [
                    'created_at' => 'Date',
                    'nom_societe'=> 'Société',
                    'contact'    => 'Contact',
                ];

                $query = $this->q(Fournisseur::class)->orderBy('nom_societe');

                if ($companyId && Schema::hasColumn('fournisseurs','company_id')) {
                    $query->where('company_id',$companyId);
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('nom_societe','like',"%$q%")
                          ->orWhere('email','like',"%$q%")
                          ->orWhere('telephone','like',"%$q%");
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'produits': {
                $columns = [
                    'created_at' => 'Date',
                    'designation'=> 'Produit',
                    'reference'  => 'Référence',
                    'prix_ht'    => 'Prix HT',
                    'stock'      => 'Stock',
                ];

                $query = $this->q(Produit::class)->latest('created_at');

                if ($companyId && Schema::hasColumn('produits','company_id')) {
                    $query->where('company_id',$companyId);
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q, $productNameCol) {
                        if ($productNameCol) {
                            $b->where($productNameCol,'like',"%$q%");
                        }
                        $b->orWhere('reference','like',"%$q%");
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'poseurs': {
                $columns = [
                    'created_at' => 'Date',
                    'nom'        => 'Nom',
                    'contact'    => 'Contact',
                ];

                $query = $this->q(Poseur::class)->orderBy('nom');

                if ($companyId && Schema::hasColumn('poseurs','company_id')) {
                    $query->where('company_id',$companyId);
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('nom','like',"%$q%")
                          ->orWhere('email','like',"%$q%")
                          ->orWhere('telephone','like',"%$q%");
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'stocks': {
                $columns = [
                    'created_at' => 'Date',
                    'produit'    => 'Produit',
                    'reference'  => 'Référence',
                    'quantity'   => 'Quantité',
                    'location'   => 'Emplacement',
                ];

                $productSelect = array_values(array_filter([
                    'id',
                    $productNameCol,
                    Schema::hasColumn('produits','reference') ? 'reference' : null,
                    Schema::hasColumn('produits','company_id') ? 'company_id' : null,
                ]));

                $query = $this->q(Stock::class)
                    ->with(['produit' => function($q) use ($productSelect) {
                        if (!empty($productSelect)) {
                            $q->select($productSelect);
                        }
                    }])
                    ->latest('created_at');

                if ($companyId) {
                    if (Schema::hasColumn('stocks','company_id')) {
                        $query->where('company_id',$companyId);
                    } else {
                        $query->whereHas('produit', fn(Builder $p)=>$p->where('company_id',$companyId));
                    }
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q, $productNameCol) {
                        $b->where('location','like',"%$q%");
                        $b->orWhereHas('produit', function(Builder $p) use ($q, $productNameCol) {
                            if ($productNameCol) {
                                $p->where($productNameCol,'like',"%$q%");
                            }
                            $p->orWhere('reference','like',"%$q%");
                        });
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }

            case 'clients':
            default: {
                $columns = [
                    'created_at' => 'Date',
                    'client'     => 'Client',
                    'contact'    => 'Contact',
                    'vehicule'   => 'Véhicule',
                    'statut'     => 'Statut',
                ];

                $query = $this->q(Client::class)->latest('created_at');

                if ($companyId && Schema::hasColumn('clients','company_id')) {
                    $query->where('company_id', $companyId);
                }

                $this->applyDateRange($query, $dateFrom, $dateTo, 'created_at');

                if ($q !== '') {
                    $query->where(function(Builder $b) use ($q) {
                        $b->where('nom_assure','like',"%$q%")
                          ->orWhere('prenom','like',"%$q%")
                          ->orWhere('email','like',"%$q%")
                          ->orWhere('telephone','like',"%$q%")
                          ->orWhere('plaque','like',"%$q%")
                          ->orWhere('immatriculation','like',"%$q%");
                    });
                }

                $results = $paginate ? $query->paginate(25)->appends($request->query()) : $query->get();
                return [$columns, $results];
            }
        }
    }

    private function applyDateRange(Builder $query, ?string $from, ?string $to, string $column): void
    {
        if ($from) $query->whereDate($column, '>=', $from);
        if ($to)   $query->whereDate($column, '<=', $to);
    }

    private function filterByCompanyViaClient(Builder $query, ?string $companyId): Builder
    {
        if (!$companyId) return $query;

        return $query->whereHas('client', function(Builder $c) use ($companyId) {
            if (Schema::hasColumn('clients','company_id')) {
                $c->where('company_id', $companyId);
            }
        });
    }

    /** Render a single cell for HTML or CSV. */
    public static function renderCell(string $type, string $key, $row, bool $plain = false)
    {
        $money = fn($v) => number_format((float)($v ?? 0), 2, ',', ' ').' €';

        switch ($type) {
            case 'devis':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'client'     => trim(($row->client->nom_assure ?? '').' '.($row->client->prenom ?? '')),
                    'titre'      => $row->titre ?? '',
                    'total_ht'   => $money($row->total_ht),
                    'total_ttc'  => $money($row->total_ttc),
                    default      => '',
                };

            case 'factures':
                return match ($key) {
                    'date_facture' => $row->date_facture ? \Carbon\Carbon::parse($row->date_facture)->format('d/m/Y') : '',
                    'client'       => trim(($row->client->nom_assure ?? '').' '.($row->client->prenom ?? '')),
                    'titre'        => $row->titre ?? '',
                    'total_ht'     => $money($row->total_ht),
                    'total_ttc'    => $money($row->total_ttc),
                    'is_paid'      => $row->is_paid ? 'Oui' : 'Non',
                    default        => '',
                };

            case 'avoirs':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'client'     => trim(($row->facture->client->nom_assure ?? '').' '.($row->facture->client->prenom ?? '')),
                    'facture'    => $row->facture->titre ?? '',
                    'montant'    => $money($row->montant ?? $row->montant_ht ?? null),
                    default      => '',
                };

            case 'paiements': {
                $date = $row->date ?? ($row->date_paiement ?? $row->created_at);
                return match ($key) {
                    'date_paiement' => $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '',
                    'client'        => trim(($row->facture->client->nom_assure ?? '').' '.($row->facture->client->prenom ?? '')),
                    'facture'       => $row->facture->titre ?? '',
                    'montant'       => $money($row->montant ?? $row->amount ?? null),
                    'methode'       => $row->methode ?? $row->methode_paiement ?? '',
                    default         => '',
                };
            }

            case 'rdv':
            case 'rdvs':
                return match ($key) {
                    'start_time' => $row->start_time ? \Carbon\Carbon::parse($row->start_time)->format('d/m/Y H:i') : '',
                    'end_time'   => $row->end_time ? \Carbon\Carbon::parse($row->end_time)->format('d/m/Y H:i') : '',
                    'client'     => trim(($row->client->nom_assure ?? '').' '.($row->client->prenom ?? '')),
                    'poseur'     => (string)($row->poseur->nom ?? ''),
                    'title'      => $row->title ?? '',
                    'status'     => $row->status ?? '',
                    default      => '',
                };

            case 'depenses':
            case 'expenses':
                return match ($key) {
                    'date'        => $row->date ? \Carbon\Carbon::parse($row->date)->format('d/m/Y') : '',
                    'client'      => trim(($row->client->nom_assure ?? '').' '.($row->client->prenom ?? '')),
                    'fournisseur' => $row->fournisseur->nom_societe ?? '',
                    'paid_status' => $row->paid_status ?? '',
                    'ht_amount'   => $money($row->ht_amount),
                    'ttc_amount'  => $money($row->ttc_amount),
                    default       => '',
                };

            case 'bons':
            case 'bons_de_commande':
            case 'purchase_orders': {
                $date = $row->date ?? $row->created_at;
                return match ($key) {
                    'date'        => $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '',
                    'numero'      => $row->numero ?? '',
                    'fournisseur' => $row->fournisseur->nom_societe ?? '',
                    'total_ht'    => $money($row->total_ht),
                    'total_ttc'   => $money($row->total_ttc),
                    'status'      => $row->status ?? '',
                    default       => '',
                };
            }

            case 'fournisseurs':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'nom_societe'=> $row->nom_societe ?? '',
                    'contact'    => trim(($row->email ?? '').' / '.($row->telephone ?? '')),
                    default      => '',
                };

            case 'produits':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'designation'=> ($row->designation ?? $row->name ?? $row->nom ?? ''),
                    'reference'  => $row->reference ?? '',
                    'prix_ht'    => $money($row->prix_ht ?? $row->price_ht ?? $row->prix ?? null),
                    'stock'      => (string)($row->stock ?? $row->quantity ?? ''),
                    default      => '',
                };

            case 'stocks':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'produit'    => ($row->produit->designation ?? $row->produit->name ?? $row->produit->nom ?? ''),
                    'reference'  => $row->produit->reference ?? '',
                    'quantity'   => (string)($row->quantity ?? $row->qty ?? ''),
                    'location'   => $row->location ?? '',
                    default      => '',
                };

            case 'poseurs':
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'nom'        => (string)($row->nom ?? ''),
                    'contact'    => trim(($row->email ?? '').' / '.($row->telephone ?? '')),
                    default      => '',
                };

            case 'clients':
            default:
                return match ($key) {
                    'created_at' => optional($row->created_at)->format('d/m/Y H:i'),
                    'client'     => trim(($row->nom_assure ?? '').' '.($row->prenom ?? '')),
                    'contact'    => trim(($row->email ?? '').' / '.($row->telephone ?? '')),
                    'vehicule'   => ($row->plaque ?? $row->immatriculation ?? ''),
                    'statut'     => $row->statut ?? $row->statut_gg ?? '-',
                    default      => '',
                };
        }
    }
}