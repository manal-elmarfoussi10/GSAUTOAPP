@extends('layout')

@section('content')
<div class="min-h-screen py-8 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
        <!-- Header with logo and title -->
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="bg-orange-500 p-3 rounded-xl shadow-md mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-center text-gray-800">
                    Modifier Dossier Client
                </h1>
                <p class="text-center text-gray-600 mt-2">
                    Mise à jour du dossier pour {{ $client->nom_assure }}
                </p>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-8 flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-8">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <strong class="font-medium">Erreurs :</strong>
                </div>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Card 1: Infos client -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="bg-gray-200 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Informations du Client</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Nom de l'assuré (ou Société)*</label>
                        <input name="nom_assure" value="{{ old('nom_assure', $client->nom_assure) }}" placeholder="Ex: Dupont SARL" required class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Prénom</label>
                        <input name="prenom" value="{{ old('prenom', $client->prenom) }}" placeholder="Ex: Jean" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Email</label>
                        <input name="email" value="{{ old('email', $client->email) }}" placeholder="Ex: contact@exemple.com" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Téléphone</label>
                        <input name="telephone" value="{{ old('telephone', $client->telephone) }}" placeholder="Ex: 06 12 34 56 78" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Adresse</label>
                        <input name="adresse" value="{{ old('adresse', $client->adresse) }}" placeholder="Ex: 123 Rue Principale" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Code postal</label>
                        <input name="code_postal" value="{{ old('code_postal', $client->code_postal) }}" placeholder="Ex: 75000" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Ville</label>
                        <input name="ville" value="{{ old('ville', $client->ville) }}" placeholder="Ex: Paris" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Plaque d'immatriculation</label>
                        <input name="plaque" value="{{ old('plaque', $client->plaque) }}" placeholder="Ex: AB-123-CD" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Assurance</label>
                        <select name="nom_assurance" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Choisir l'assurance</option>
                            <option value="AXA" {{ old('nom_assurance', $client->nom_assurance) == 'AXA' ? 'selected' : '' }}>AXA</option>
                            <option value="Allianz" {{ old('nom_assurance', $client->nom_assurance) == 'Allianz' ? 'selected' : '' }}>Allianz</option>
                            <option value="Groupama" {{ old('nom_assurance', $client->nom_assurance) == 'Groupama' ? 'selected' : '' }}>Groupama</option>
                            <option value="MAIF" {{ old('nom_assurance', $client->nom_assurance) == 'MAIF' ? 'selected' : '' }}>MAIF</option>
                            <option value="Matmut" {{ old('nom_assurance', $client->nom_assurance) == 'Matmut' ? 'selected' : '' }}>Matmut</option>
                            <option value="MACIF" {{ old('nom_assurance', $client->nom_assurance) == 'MACIF' ? 'selected' : '' }}>MACIF</option>
                            <option value="GMF" {{ old('nom_assurance', $client->nom_assurance) == 'GMF' ? 'selected' : '' }}>GMF</option>
                            <option value="Generali" {{ old('nom_assurance', $client->nom_assurance) == 'Generali' ? 'selected' : '' }}>Generali</option>
                            <option value="Crédit Agricole Assurances" {{ old('nom_assurance', $client->nom_assurance) == 'Crédit Agricole Assurances' ? 'selected' : '' }}>Crédit Agricole Assurances</option>
                            <option value="CNP Assurances" {{ old('nom_assurance', $client->nom_assurance) == 'CNP Assurances' ? 'selected' : '' }}>CNP Assurances</option>
                            <option value="BNP Paribas Cardif" {{ old('nom_assurance', $client->nom_assurance) == 'BNP Paribas Cardif' ? 'selected' : '' }}>BNP Paribas Cardif</option>
                            <option value="SMA" {{ old('nom_assurance', $client->nom_assurance) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="MMA" {{ old('nom_assurance', $client->nom_assurance) == 'MMA' ? 'selected' : '' }}>MMA</option>
                            <option value="AGPM" {{ old('nom_assurance', $client->nom_assurance) == 'AGPM' ? 'selected' : '' }}>AGPM</option>
                            <option value="Covéa" {{ old('nom_assurance', $client->nom_assurance) == 'Covéa' ? 'selected' : '' }}>Covéa</option>
                            <option value="Swiss Life" {{ old('nom_assurance', $client->nom_assurance) == 'Swiss Life' ? 'selected' : '' }}>Swiss Life</option>
                            <option value="Aviva" {{ old('nom_assurance', $client->nom_assurance) == 'Aviva' ? 'selected' : '' }}>Aviva</option>
                            <option value="AcommeAssure" {{ old('nom_assurance', $client->nom_assurance) == 'AcommeAssure' ? 'selected' : '' }}>AcommeAssure</option>
                            <option value="Luko" {{ old('nom_assurance', $client->nom_assurance) == 'Luko' ? 'selected' : '' }}>Luko</option>
                            <option value="Lemonade" {{ old('nom_assurance', $client->nom_assurance) == 'Lemonade' ? 'selected' : '' }}>Lemonade</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Si autre assurance</label>
                        <input name="autre_assurance" value="{{ old('autre_assurance', $client->autre_assurance) }}" placeholder="Précisez le nom" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center text-sm text-gray-700 font-medium">
                        <input type="checkbox" name="ancien_modele_plaque" value="1" class="form-checkbox h-5 w-5 text-orange-500 rounded mr-2" {{ old('ancien_modele_plaque', $client->ancien_modele_plaque) ? 'checked' : '' }}>
                        Ancien Modèle de plaque ?
                    </label>
                </div>
            </div>

            <!-- Card 2: Infos sinistre -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="bg-gray-200 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Informations du Sinistre</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium" title="Numéro figurant sur votre contrat d'assurance">Numéro de police</label>
                        <input name="numero_police" value="{{ old('numero_police', $client->numero_police) }}" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="N° Police d'assurance" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Date du sinistre</label>
                        <input type="date" name="date_sinistre" value="{{ old('date_sinistre', $client->date_sinistre ? \Carbon\Carbon::parse($client->date_sinistre)->format('Y-m-d') : '') }}" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Date de déclaration</label>
                        <input type="date" name="date_declaration" value="{{ old('date_declaration', $client->date_declaration ? \Carbon\Carbon::parse($client->date_declaration)->format('Y-m-d') : '') }}" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Raison du sinistre</label>
                        <input name="raison" value="{{ old('raison', $client->raison) }}" placeholder="Ex: Bris de glace" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type de vitrage</label>
                        <select name="type_vitrage" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Sélectionner le type</option>
                            <option value="Pare-brise" {{ old('type_vitrage', $client->type_vitrage) == 'Pare-brise' ? 'selected' : '' }}>Pare-brise</option>
                            <option value="Lunette arrière" {{ old('type_vitrage', $client->type_vitrage) == 'Lunette arrière' ? 'selected' : '' }}>Lunette arrière</option>
                            <option value="Vitre porte latérale avant - [Gauche]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre porte latérale avant - [Gauche]' ? 'selected' : '' }}>Vitre porte latérale avant - [Gauche]</option>
                            <option value="Vitre porte latérale avant - [Droite]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre porte latérale avant - [Droite]' ? 'selected' : '' }}>Vitre porte latérale avant - [Droite]</option>
                            <option value="Vitre porte latérale arrière - [Gauche]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre porte latérale arrière - [Gauche]' ? 'selected' : '' }}>Vitre porte latérale arrière - [Gauche]</option>
                            <option value="Vitre porte latérale arrière - [Droite]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre porte latérale arrière - [Droite]' ? 'selected' : '' }}>Vitre porte latérale arrière - [Droite]</option>
                            <option value="Pavillon panoramique" {{ old('type_vitrage', $client->type_vitrage) == 'Pavillon panoramique' ? 'selected' : '' }}>Pavillon panoramique</option>
                            <option value="Vitre triangulaire avant - [Gauche]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre triangulaire avant - [Gauche]' ? 'selected' : '' }}>Vitre triangulaire avant - [Gauche]</option>
                            <option value="Vitre triangulaire avant - [Droite]" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre triangulaire avant - [Droite]' ? 'selected' : '' }}>Vitre triangulaire avant - [Droite]</option>
                            <option value="Toit ouvrant arrière vitré (complet)" {{ old('type_vitrage', $client->type_vitrage) == 'Toit ouvrant arrière vitré (complet)' ? 'selected' : '' }}>Toit ouvrant arrière vitré (complet)</option>
                            <option value="Phare avant (complet) - [Gauche]" {{ old('type_vitrage', $client->type_vitrage) == 'Phare avant (complet) - [Gauche]' ? 'selected' : '' }}>Phare avant (complet) - [Gauche]</option>
                            <option value="Phare avant (complet) - [Droite]" {{ old('type_vitrage', $client->type_vitrage) == 'Phare avant (complet) - [Droite]' ? 'selected' : '' }}>Phare avant (complet) - [Droite]</option>
                            <option value="Vitre de custode" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre de custode' ? 'selected' : '' }}>Vitre de custode</option>
                            <option value="Vitre de toit ouvrant" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre de toit ouvrant' ? 'selected' : '' }}>Vitre de toit ouvrant</option>
                            <option value="Vitre de hayon" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre de hayon' ? 'selected' : '' }}>Vitre de hayon</option>
                            <option value="Vitre de portière" {{ old('type_vitrage', $client->type_vitrage) == 'Vitre de portière' ? 'selected' : '' }}>Vitre de portière</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type personnalisé</label>
                        <input name="type_vitrage_custom" value="{{ old('type_vitrage_custom', $client->type_vitrage_custom) }}" placeholder="Précisez le type" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2 font-medium">Professionnel</label>
                    <select name="professionnel" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Sélectionner...</option>
                        <option value="oui" {{ old('professionnel', $client->professionnel) == 'oui' ? 'selected' : '' }}>Oui</option>
                        <option value="non" {{ old('professionnel', $client->professionnel) == 'non' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
            </div>

            <!-- Card 3: Données facultatives -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="bg-gray-200 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Informations Complémentaires</h2>
                    <span class="ml-3 text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-full">Facultatif</span>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Est-ce une réparation ?</label>
                    <div class="flex items-center">
                        <label class="inline-flex items-center mr-6">
                            <input type="radio" name="reparation" value="1" class="form-radio h-4 w-4 text-orange-500 mr-2" {{ old('reparation', $client->reparation) == 1 ? 'checked' : '' }}>
                            <span>Oui</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="reparation" value="0" class="form-radio h-4 w-4 text-orange-500 mr-2" {{ old('reparation', $client->reparation) == 0 ? 'checked' : '' }}>
                            <span>Non</span>
                        </label>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Photo du vitrage -->
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Photo du vitrage</label>
                        <input type="file" name="photo_vitrage" class="form-input-file w-full" />
                        @if($client->photo_vitrage)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-1">Image actuelle:</p>
                                <img src="{{ Storage::url($client->photo_vitrage) }}" alt="Photo vitrage" class="w-full h-32 object-contain border rounded-lg">
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ Storage::url($client->photo_vitrage) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                                 
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Carte verte -->
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Carte verte</label>
                        <input type="file" name="photo_carte_verte" class="form-input-file w-full" />
                        @if($client->photo_carte_verte)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-1">Image actuelle:</p>
                                <img src="{{ Storage::url($client->photo_carte_verte) }}" alt="Carte verte" class="w-full h-32 object-contain border rounded-lg">
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ Storage::url($client->photo_carte_verte) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                              
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Carte grise -->
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Carte grise</label>
                        <input type="file" name="photo_carte_grise" class="form-input-file w-full" />
                        @if($client->photo_carte_grise)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-1">Image actuelle:</p>
                                <img src="{{ Storage::url($client->photo_carte_grise) }}" alt="Carte grise" class="w-full h-32 object-contain border rounded-lg">
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ Storage::url($client->photo_carte_grise) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                              
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type de cadeau</label>
                        <input name="type_cadeau" value="{{ old('type_cadeau', $client->type_cadeau) }}" placeholder="Ex: Carte cadeau" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Numéro de sinistre</label>
                        <input name="numero_sinistre" value="{{ old('numero_sinistre', $client->numero_sinistre) }}" placeholder="Ex: SIN-12345" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Kilométrage</label>
                        <input name="kilometrage" value="{{ old('kilometrage', $client->kilometrage) }}" placeholder="Ex: 45 000 km" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Agent/Commercial</label>
                        <input name="agent_commercial" value="{{ old('agent_commercial', $client->agent_commercial) }}" placeholder="Ex: Martin Dupont" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Connu par</label>
                        <input name="connu_par" value="{{ old('connu_par', $client->connu_par) }}" placeholder="Ex: Recommandation" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Adresse de pose</label>
                        <input name="adresse_pose" value="{{ old('adresse_pose', $client->adresse_pose) }}" placeholder="Si différent du domicile" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Référence interne</label>
                        <input name="reference_interne" value="{{ old('reference_interne', $client->reference_interne) }}" placeholder="Ex: REF-001" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Référence client</label>
                        <input name="reference_client" value="{{ old('reference_client', $client->reference_client) }}" placeholder="Ex: CLT-456" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Précision à apporter</label>
                    <textarea name="precision" rows="4" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Saisissez les détails supplémentaires ici...">{{ old('precision', $client->precision) }}</textarea>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center pt-6">
                <button type="submit" class="btn-submit bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg px-8 py-3 rounded-lg shadow transition duration-300 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Mettre à jour le dossier
                </button>
                <p class="text-sm text-gray-500 mt-4">
                    En soumettant ce formulaire, vous acceptez nos <a href="#" class="text-orange-500 hover:underline">conditions d'utilisation</a>.
                </p>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input {
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }
    
    .form-select {
        transition: all 0.3s ease;
    }
    
    .form-select:focus {
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }
    
    .form-input-file {
        @apply w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer;
    }
    
    .btn-submit {
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .form-checkbox {
        @apply rounded focus:ring-orange-500 h-5 w-5 text-orange-500 border-gray-300;
    }
    
    .form-radio {
        @apply rounded-full focus:ring-orange-500 h-4 w-4 text-orange-500 border-gray-300;
    }
</style>
@endsection