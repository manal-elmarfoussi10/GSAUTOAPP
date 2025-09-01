@extends('layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Client Header - Modified per request 1 -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ $client->prenom }} {{ $client->nom_assure }}
                    </h1>
                    <a href="{{ route('clients.edit', $client->id) }}" class="ml-3 text-cyan-600 hover:text-cyan-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
                <button class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <a href="{{ route('clients.export.pdf', $client) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Télécharger le dossier
                    </a>
                </button>
            </div>
        </div>

        <!-- Status Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-lg font-semibold text-gray-800">Statut du dossier</h2>
                    <div class="flex items-center mt-2">
                    <span class="bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $client->statut ?? 'En attente' }}
                    </span>
                        <span class="ml-3 text-sm text-gray-600">
                        Créé le: {{ $client->created_at->format('d/m/Y') }}
                    </span>
                    </div>
                </div>

                <!-- Statut Interne Form -->
                <form method="POST" action="{{ route('clients.statut_interne', $client->id) }}" class="w-full md:w-auto">
                    @csrf
                    <div class="flex flex-col md:flex-row items-start md:items-center">
                        <div class="mr-4 mb-2 md:mb-0">
                            <label for="statut_interne" class="block text-sm font-medium text-gray-700">Statut interne:</label>
                            <select name="statut_interne" id="statut_interne" class="mt-1 block w-full md:w-64 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                                <option value="">-- Aucun --</option>
                                <option value="En attente document" {{ $client->statut_interne === 'En attente document' ? 'selected' : '' }}>En attente document</option>
                                <option value="Faire devis" {{ $client->statut_interne === 'Faire devis' ? 'selected' : '' }}>Faire devis</option>
                                <option value="Fixer RDV" {{ $client->statut_interne === 'Fixer RDV' ? 'selected' : '' }}>Fixer RDV</option>
                                <option value="Faire commande" {{ $client->statut_interne === 'Faire commande' ? 'selected' : '' }}>Faire commande</option>
                                <option value="En attente de pose" {{ $client->statut_interne === 'En attente de pose' ? 'selected' : '' }}>En attente de pose</option>
                                <option value="Pose terminée" {{ $client->statut_interne === 'Pose terminée' ? 'selected' : '' }}>Pose terminée</option>
                                <option value="Annulée" {{ $client->statut_interne === 'Annulée' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md text-sm font-medium">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Actions - Modified with functional links -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            <!-- Modifier Dossier Button -->
            <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-sm">Modifier dossier</span>
            </a>

            <!-- Acquitter Facture Button -->
            <a href="{{ route('factures.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">Acquitter facture</span>
            </a>

            <!-- Faire Chiffrage Button -->
            <a href="{{ route('sidexa.index', ['client_id' => $client->id]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg flex flex-col items-center justify-center transition-all hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm">Faire chiffrage</span>
            </a>


        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Client Information - Modified per request 3 -->
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
                    <div>
                        <p class="text-sm text-gray-500">Nom de l'assure</p>
                        <p class="font-medium">{{ $client->nom_assure }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nom</p>
                        <p class="font-medium">{{ $client->prenom }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Adresse</p>
                        <p class="font-medium">{{ $client->adresse }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-cyan-600">
                            <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-medium text-cyan-600">
                            <a href="tel:{{ $client->telephone }}">{{ $client->telephone }}</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information - Modified per requests 4 and 5 -->
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
                    <div>
                        <p class="text-sm text-gray-500">Immatriculation</p>
                        <p class="font-medium">{{ $client->plaque }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kilométrage</p>
                        <p class="font-medium">{{ $client->kilometrage ?? '-' }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type de vitrage</p>
                        <p class="font-medium">{{ $client->type_vitrage ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ancien modèle plaque</p>
                        <p class="font-medium">{{ $client->ancien_modele_plaque ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Insurance Information -->
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
                    <div>
                        <p class="text-sm text-gray-500">Nom</p>
                        <p class="font-medium">{{ $client->nom_assurance }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">N° Police</p>
                        <p class="font-medium">{{ $client->numero_police }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">N° Sinistre</p>
                        <p class="font-medium">{{ $client->numero_sinistre ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Autre assurance</p>
                        <p class="font-medium">{{ $client->autre_assurance ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information - Modified per requests 6 and 11 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Accident Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails du Sinistre</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Date du sinistre</p>
                        <p class="font-medium">
                            @if($client->date_sinistre)
                                {{ \Carbon\Carbon::parse($client->date_sinistre)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date d'enregistrement</p>
                        <p class="font-medium">
                            @if($client->date_declaration)
                                {{ \Carbon\Carbon::parse($client->date_declaration)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Raison</p>
                        <p class="font-medium">{{ $client->raison ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Réparation</p>
                        <p class="font-medium">{{ $client->reparation ? 'Oui' : 'Non' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Connu par</p>
                        <p class="font-medium">{{ $client->connu_par ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Adresse de pose</p>
                        <p class="font-medium">{{ $client->adresse_pose ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Précisions</p>
                        <p class="font-medium">{{ $client->precision ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations Financières</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Factures (HT)</p>
                        <p class="font-medium text-blue-600">{{ number_format($client->factures->sum('total_ht'), 2, ',', ' ') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Avoirs (HT)</p>
                        <p class="font-medium text-green-600">
                            @php
                                $totalAvoirs = 0;
                                foreach ($client->factures as $facture) {
                                    $totalAvoirs += $facture->avoirs->sum('montant_ht');
                                }
                                echo number_format($totalAvoirs, 2, ',', ' ') . ' €';
                            @endphp
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Devis (HT)</p>
                        <p class="font-medium text-purple-600">{{ number_format($client->devis->sum('total_ht'), 2, ',', ' ') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Encaissé</p>
                        <p class="font-medium text-cyan-600">{{ $client->encaisse ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cadeau</p>
                        <p class="font-medium">{{ $client->type_cadeau ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Référence interne</p>
                        <p class="font-medium">{{ $client->reference_interne ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Référence client</p>
                        <p class="font-medium">{{ $client->reference_client ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section - Fixed version -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Documents</h2>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $documents = [
                        'photo_vitrage' => 'Photo Vitrage',
                        'photo_carte_verte' => 'Carte Verte',
                        'photo_carte_grise' => 'Carte Grise'
                    ];
                    $hasDocuments = false;
                @endphp

                @foreach($documents as $field => $label)
                    @if($client->$field)
                        @php $hasDocuments = true; @endphp
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                @php
                                    $extension = pathinfo($client->$field, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($extension, ['pdf', 'PDF']))
                                    <div class="text-center p-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-medium text-gray-700 truncate">{{ $label }}</p>
                                    </div>
                                @else
                                    <img src="{{asset('/storage/app/public/' . $client->$field)}}" class="object-contain w-full h-full">
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="font-medium text-gray-800">{{ $label }}</h3>
                                <div class="flex justify-between mt-2">

                                    <a  href="/storage/app/public/{{ $client->$field}}"  target="_blank" class="text-cyan-600 hover:text-cyan-800 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                                    <a href="/storage/app/public/{{ $client->$field}}" download class="text-gray-600 hover:text-gray-800 text-sm flex items-center">
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

        <!-- Conversation Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Conversations</h2>
                <button id="newConversationBtn" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Nouvelle Conversation
                </button>
            </div>

            <!-- New Conversation Form -->
            <div id="newConversationForm" class="hidden mb-8">
                <form method="POST" action="{{ route('clients.conversations.store', $client) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="receiver" class="block text-sm font-medium text-gray-700">Destinataire</label>
                        <select name="receiver" id="receiver" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Sujet</label>
                        <input type="text" name="subject" id="subject" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="content" id="content" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700">Fichier joint</label>
                        <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="cancelNewConversation" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            Annuler
                        </button>
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>

            <!-- List of Conversations -->
            <div class="space-y-6">
                @forelse ($client->conversations as $thread)
                    <div class="thread border rounded-lg p-4">
                        <div class="flex justify-between">
                            <h3 class="font-semibold">{{ $thread->subject }}</h3>
                            <span class="text-sm text-gray-500">
                        Started by {{ $thread->creator->name ?? 'Deleted User' }}
                        on {{ $thread->created_at->format('d/m/Y H:i') }}
                    </span>
                        </div>

                        <div class="mt-4">
                            @foreach ($thread->emails as $email)
                                @if($email->receiver_id == auth()->id() || $email->sender_id == auth()->id())
                                    <div class="email mb-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <span class="text-cyan-800 text-sm font-medium">
                                            {{ substr($email->senderUser->name, 0, 1) }}
                                        </span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium">{{ $email->senderUser->name }}</p>
                                                <p class="text-sm text-gray-500">
                                                    to {{ $email->receiverUser->name }} · {{ $email->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                <div class="mt-2 text-gray-700">
                                                    {!! nl2br(e($email->content)) !!}
                                                </div>
                                                @if($email->file_path)
                                                    <div class="mt-2">

                                                        <a href="/storage/app/public/{{ $email->file_path}}" target="_blank" class="text-cyan-600 hover:text-cyan-800 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            {{ $email->file_name }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Replies to this email -->
                                        <div class="replies pl-8 mt-4 space-y-4">
                                            @foreach ($email->replies as $reply)
                                                <div class="reply">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center">
                                                    <span class="text-cyan-800 text-sm font-medium">
                                                        @if($reply->sender)
                                                            {{ substr($reply->sender->name, 0, 2) }}
                                                        @endif
                                                    </span>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="font-medium">{{ $reply->sender->name ?? 'Utilisateur inconnu' }}</p>
                                                            <p class="text-sm text-gray-500">
                                                                to {{ $reply->receiverUser->name }} · {{ $reply->created_at->format('d/m/Y H:i') }}
                                                            </p>
                                                            <div class="mt-2 text-gray-700">
                                                                {!! nl2br(e($reply->content)) !!}
                                                            </div>
                                                            @if($reply->file_path)
                                                                <div class="mt-2">

                                                                    <a href="/storage/app/public/{{ $reply->file_path}}" target="_blank" class="text-cyan-600 hover:text-cyan-800 flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                        {{ $reply->file_name }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        @if($thread->emails->isNotEmpty())
                            <div id="messages-container">
                                <form method="POST" action="{{ route('conversations.reply', $thread->emails->first()->id) }}" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <!-- Add hidden email_id field -->
                                    <input type="hidden" name="email_id" value="{{ $thread->emails->first()->id }}">

                                    <div class="mb-4">
                                        <label for="content" class="block text-sm font-medium text-gray-700">Répondre</label>
                                        <textarea name="content" id="content" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="file" class="block text-sm font-medium text-gray-700">Fichier joint</label>
                                        <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                            Envoyer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Aucune conversation pour ce client.</p>
                @endforelse
            </div>
            <audio id="notificationSound" src="{{ asset('audio/notification.mp3') }}" preload="auto"></audio>
        </div>

        <script>
            const sound = document.getElementById('notificationSound');
            let lastCount = document.querySelectorAll('#messages-list .border').length;

            setInterval(() => {
                fetch("{{ route('conversations.fetch', $client->id) }}")
                    .then(r => r.text())
                    .then(html => {
                        // Parse the returned partial
                        const temp = document.createElement('div');
                        temp.innerHTML = html;
                        // Count new messages
                        const newCount = temp.querySelectorAll('div.border').length;
                        if (newCount > lastCount) sound.play();
                        lastCount = newCount;
                        // Replace only the messages list
                        document.getElementById('messages-list').innerHTML = html;
                    });
            }, 5000);
        </script>


        <script>
            // Toggle new conversation form
            document.getElementById('newConversationBtn').addEventListener('click', function() {
                document.getElementById('newConversationForm').classList.remove('hidden');
                this.classList.add('hidden');
            });

            document.getElementById('cancelNewConversation').addEventListener('click', function() {
                document.getElementById('newConversationForm').classList.add('hidden');
                document.getElementById('newConversationBtn').classList.remove('hidden');
            });
        </script>

        <!-- Timeline Section -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Historique du dossier</h2>
            <div class="relative pl-8 border-l-2 border-gray-200 space-y-6">
                <!-- Timeline Item -->
                <div class="relative">
                    <div class="absolute -left-11 top-0 w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                    <div class="pl-4">
                        <p class="font-medium text-gray-800">Dossier créé</p>
                        <p class="text-sm text-gray-500">{{ $client->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Timeline Item -->
                <div class="relative">
                    <div class="absolute -left-11 top-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                    <div class="pl-4">
                        <p class="font-medium text-gray-800">Dossier envoyé à l'assurance</p>
                        <p class="text-sm text-gray-500">19/07/2025 10:30</p>
                    </div>
                </div>

                <!-- Timeline Item -->
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
    .container {
        max-width: 1200px;
    }
    .bg-cyan-100 {
        background-color: #ecfeff;
    }
    .bg-cyan-600 {
        background-color: #0891b2;
    }
    .hover\:bg-cyan-700:hover {
        background-color: #0e7490;
    }
    .bg-blue-100 {
        background-color: #dbeafe;
    }
    .bg-green-100 {
        background-color: #dcfce7;
    }
    .bg-orange-100 {
        background-color: #ffedd5;
    }
</style>
