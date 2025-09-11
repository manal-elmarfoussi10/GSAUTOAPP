@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Titre --}}
    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight mb-6">
        Résultats pour “{{ $q }}”
    </h1>

    @if(!$q)
        <p class="text-gray-500">Saisis un mot-clé dans la barre de recherche en haut.</p>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===================== Clients ===================== --}}
            <div class="bg-white rounded-2xl shadow px-6 py-5">
                <h2 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user text-[#FF4B00]"></i>
                    Clients ({{ $clients->count() }})
                </h2>

                <ul class="divide-y divide-gray-100">
                    @forelse($clients as $c)
                        @php
                            $prenom = $c->prenom ?? '';
                            $nom    = $c->nom ?? $c->nom_assure ?? '';
                            $phone  = $c->telephone ?? '';
                            $mail   = $c->email ?? '';
                        @endphp
                        <li class="py-3">
                            <a href="{{ route('clients.show', $c->id) }}"
                               class="text-sm font-medium text-gray-900 hover:text-[#FF4B00]">
                                {{ trim($prenom.' '.$nom) ?: "Client #{$c->id}" }}
                            </a>
                            <div class="text-xs text-gray-500">
                                {{ $phone }} @if($phone && $mail) • @endif {{ $mail }}
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500">Aucun client</li>
                    @endforelse
                </ul>
            </div>

            {{-- ===================== Devis ===================== --}}
            <div class="bg-white rounded-2xl shadow px-6 py-5">
                <h2 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-[#FF4B00]"></i>
                    Devis ({{ $devis->count() }})
                </h2>

                <ul class="divide-y divide-gray-100">
                    @forelse($devis as $d)
                        @php
                            // Numéro de devis (fallbacks)
                            $num = $d->numero ?? $d->numero_devis ?? $d->reference ?? $d->ref ?? $d->id;

                            // "who" : immat / client / libellé de dossier joint (alias dossier_*)
                            $who = $d->immatriculation ?? $d->immat ?? $d->client_nom ?? $d->client_name ?? $d->nom_client ?? '';

                            if (!$who) {
                                foreach (['dossier_titre','dossier_nom','dossier_reference','dossier_numero','dossier_libelle'] as $cand) {
                                    if (!empty($d->$cand)) { $who = $d->$cand; break; }
                                }
                            }

                            $status = $d->status ?? $d->statut ?? '';
                        @endphp

                        <li class="py-3">
                            <a href="{{ route('devis.show', $d->id) }}"
                               class="text-sm font-medium text-gray-900 hover:text-[#FF4B00]">
                                #{{ $num }} @if($who) — {{ $who }} @endif
                            </a>
                            @if($status)
                                <div class="text-xs text-gray-500">{{ $status }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="py-3 text-gray-500">Aucun devis</li>
                    @endforelse
                </ul>
            </div>

            {{-- ===================== Factures ===================== --}}
            <div class="bg-white rounded-2xl shadow px-6 py-5">
                <h2 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-[#FF4B00]"></i>
                    Factures ({{ $factures->count() }})
                </h2>

                <ul class="divide-y divide-gray-100">
                    @forelse($factures as $f)
                        @php
                            $num = $f->numero ?? $f->numero_facture ?? $f->reference ?? $f->ref ?? $f->id;

                            $who = $f->immatriculation ?? $f->immat ?? $f->client_nom ?? $f->client_name ?? $f->nom_client ?? '';
                            if (!$who) {
                                foreach (['dossier_titre','dossier_nom','dossier_reference','dossier_numero','dossier_libelle'] as $cand) {
                                    if (!empty($f->$cand)) { $who = $f->$cand; break; }
                                }
                            }

                            $status = $f->status ?? $f->statut ?? '';
                            $total  = $f->total_ttc ?? $f->montant_ttc ?? null;
                        @endphp

                        <li class="py-3">
                            <a href="{{ route('factures.show', $f->id) }}"
                               class="text-sm font-medium text-gray-900 hover:text-[#FF4B00]">
                                #{{ $num }} @if($who) — {{ $who }} @endif
                            </a>
                            <div class="text-xs text-gray-500">
                                @if($status) {{ $status }} @endif
                                @if($status && $total) • @endif
                                @if($total !== null) {{ number_format($total, 0, ',', ' ') }} € @endif
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500">Aucune facture</li>
                    @endforelse
                </ul>
            </div>

        </div>

        {{-- Message si rien du tout --}}
        @if($clients->isEmpty() && $devis->isEmpty() && $factures->isEmpty())
            <div class="mt-8 text-center text-gray-500">
                Aucun résultat. Essaie un autre terme (immatriculation, client, dossier, référence…).
            </div>
        @endif
    @endif
</div>
@endsection

