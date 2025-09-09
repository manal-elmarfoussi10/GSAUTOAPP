@extends('layout')

@section('title', $client->prenom . ' ' . $client->nom_assure . ' - Dossier')

@section('content')
<div class="container mx-auto px-4 py-8">

    @if(session('success'))
      <div class="mb-6 rounded-lg bg-green-50 text-green-800 px-4 py-2">
        {{ session('success') }}
      </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <div class="flex items-center">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $client->prenom }} {{ $client->nom_assure }}
                </h1>
                <a href="{{ route('clients.edit', $client->id) }}" 
                   class="ml-3 text-cyan-600 hover:text-cyan-800" 
                   title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" 
                         viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
            </div>
            <div class="mt-2">
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $client->plaque }}
                </span>
            </div>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('superadmin.clients.export.pdf', $client) }}"
               class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Télécharger le dossier
            </a>
        </div>
    </div>

    <!-- Statut -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Statut du dossier</h2>
                <div class="flex items-center mt-2">
                    <span class="bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $statutLabel }}
                    </span>
                    <span class="ml-3 text-sm text-gray-600">
                        Créé le: {{ $client->created_at?->format('d/m/Y') }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <span class="font-semibold">Statut interne :</span> 
                    {{ $client->statut_interne ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span class="text-sm">Modifier dossier</span>
        </a>

        <a href="{{ route('factures.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">Acquitter facture</span>
        </a>

        <a href="{{ route('sidexa.index', ['client_id' => $client->id]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-sm">Faire chiffrage</span>
        </a>
    </div>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Infos client -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Informations Client</h2>
                <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">Nom de l'assure</p><p class="font-medium">{{ $client->nom_assure }}</p></div>
                <div><p class="text-sm text-gray-500">Nom</p><p class="font-medium">{{ $client->prenom }}</p></div>
                <div><p class="text-sm text-gray-500">Adresse</p><p class="font-medium">{{ $client->adresse }}</p></div>
                <div><p class="text-sm text-gray-500">Email</p><p class="font-medium text-cyan-600"><a href="mailto:{{ $client->email }}">{{ $client->email }}</a></p></div>
                <div><p class="text-sm text-gray-500">Téléphone</p><p class="font-medium text-cyan-600"><a href="tel:{{ $client->telephone }}">{{ $client->telephone }}</a></p></div>
            </div>
        </div>

        <!-- Véhicule -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Véhicule</h2>
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">Immatriculation</p><p class="font-medium">{{ $client->plaque }}</p></div>
                <div><p class="text-sm text-gray-500">Kilométrage</p><p class="font-medium">{{ $client->kilometrage ?? '-' }} km</p></div>
                <div><p class="text-sm text-gray-500">Type de vitrage</p><p class="font-medium">{{ $client->type_vitrage ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Ancien modèle plaque</p><p class="font-medium">{{ $client->ancien_modele_plaque ?? '-' }}</p></div>
            </div>
        </div>

        <!-- Assurance -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Assurance</h2>
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <div><p class="text-sm text-gray-500">Nom</p><p class="font-medium">{{ $client->nom_assurance }}</p></div>
                <div><p class="text-sm text-gray-500">N° Police</p><p class="font-medium">{{ $client->numero_police }}</p></div>
                <div><p class="text-sm text-gray-500">N° Sinistre</p><p class="font-medium">{{ $client->numero_sinistre ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Autre assurance</p><p class="font-medium">{{ $client->autre_assurance ?? '-' }}</p></div>
            </div>
        </div>
    </div>

    <!-- Infos supplémentaires -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sinistre -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails du Sinistre</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-500">Date du sinistre</p><p class="font-medium">{{ $client->date_sinistre ? \Carbon\Carbon::parse($client->date_sinistre)->format('d/m/Y') : '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Date d'enregistrement</p><p class="font-medium">{{ $client->date_declaration ? \Carbon\Carbon::parse($client->date_declaration)->format('d/m/Y') : '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Raison</p><p class="font-medium">{{ $client->raison ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Réparation</p><p class="font-medium">{{ $client->reparation ? 'Oui' : 'Non' }}</p></div>
                <div><p class="text-sm text-gray-500">Connu par</p><p class="font-medium">{{ $client->connu_par ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Adresse de pose</p><p class="font-medium">{{ $client->adresse_pose ?? '-' }}</p></div>
                <div class="md:col-span-2"><p class="text-sm text-gray-500">Précisions</p><p class="font-medium">{{ $client->precision ?? '-' }}</p></div>
            </div>
        </div>

        <!-- Finance -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations Financières</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-500">Total Factures (HT)</p><p class="font-medium text-blue-600">{{ number_format($client->factures->sum('total_ht'), 2, ',', ' ') }} €</p></div>
                <div><p class="text-sm text-gray-500">Total Avoirs (HT)</p><p class="font-medium text-green-600">@php $totalAvoirs = 0; foreach ($client->factures as $facture) { $totalAvoirs += $facture->avoirs->sum('montant_ht'); } echo number_format($totalAvoirs, 2, ',', ' '); @endphp €</p></div>
                <div><p class="text-sm text-gray-500">Total Devis (HT)</p><p class="font-medium text-purple-600">{{ number_format($client->devis->sum('total_ht'), 2, ',', ' ') }} €</p></div>
                <div><p class="text-sm text-gray-500">Encaissé</p><p class="font-medium text-cyan-600">{{ $client->encaisse ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Cadeau</p><p class="font-medium">{{ $client->type_cadeau ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Référence interne</p><p class="font-medium">{{ $client->reference_interne ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Référence client</p><p class="font-medium">{{ $client->reference_client ?? '-' }}</p></div>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Documents</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $documents = [
                    'photo_vitrage' => 'Photo Vitrage',
                    'photo_carte_verte' => 'Carte Verte',
                    'photo_carte_grise' => 'Carte Grise',
                ];
                $hasDocuments = false;
            @endphp

            @foreach($documents as $field => $label)
                @if(!empty($client->$field))
                    @php $hasDocuments = true; @endphp
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 h-48 flex items-center justify-center">
                            @php $extension = pathinfo($client->$field, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($extension), ['pdf']))
                                <div class="text-center p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-700 truncate">{{ $label }}</p>
                                </div>
                            @else
                                <img src="{{ asset('/storage/app/public/' . $client->$field) }}" class="object-contain w-full h-full" alt="{{ $label }}">
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium text-gray-800">{{ $label }}</h3>
                            <div class="flex justify-between mt-2">
                                <a href="/storage/app/public/{{ $client->$field }}" target="_blank" class="text-cyan-600 hover:text-cyan-800 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Voir
                                </a>
                                <a href="/storage/app/public/{{ $client->$field }}" download class="text-gray-600 hover:text-gray-800 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(!$hasDocuments)
                <div class="col-span-3 text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-gray-500">Aucun document disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Historique du dossier</h2>
        <div class="relative pl-8 border-l-2 border-gray-200 space-y-6">
            <div class="relative">
                <div class="absolute -left-11 top-0 w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                    <div class="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <div class="pl-4">
                    <p class="font-medium text-gray-800">Dossier créé</p>
                    <p class="text-sm text-gray-500">{{ $client->created_at?->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-11 top-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                    <div class="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <div class="pl-4">
                    <p class="font-medium text-gray-800">Dossier envoyé à l'assurance</p>
                    <p class="text-sm text-gray-500">—</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-11 top-0 w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center">
                    <div class="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <div class="pl-4">
                    <p class="font-medium text-gray-800">En attente de validation</p>
                    <p class="text-sm text-gray-500">En cours...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .container { max-width: 1200px; }
    .bg-cyan-100 { background-color: #ecfeff; }
    .bg-cyan-600 { background-color: #0891b2; }
    .hover\:bg-cyan-700:hover { background-color: #0e7490; }
    .bg-blue-100 { background-color: #dbeafe; }
    .bg-green-100 { background-color: #dcfce7; }
    .bg-orange-100 { background-color: #ffedd5; }
</style>