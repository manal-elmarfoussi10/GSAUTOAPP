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
           

        {{-- Message si rien du tout --}}
        @if($clients->isEmpty() && $devis->isEmpty() && $factures->isEmpty())
            <div class="mt-8 text-center text-gray-500">
                Aucun résultat. Essaie un autre terme (immatriculation, client, dossier, référence…).
            </div>
        @endif
    @endif
</div>
@endsection

