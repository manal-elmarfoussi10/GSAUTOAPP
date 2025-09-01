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
                    Nouveau Dossier Client
                </h1>
                <p class="text-center text-gray-600 mt-2">
                    Création d'un dossier pour la gestion bris de glace
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

        @if($errors->any()))
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
        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

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
                        <input name="nom_assure" placeholder="Ex: Dupont SARL" required class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Prénom</label>
                        <input name="prenom" placeholder="Ex: Jean" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Email</label>
                        <input name="email" placeholder="Ex: contact@exemple.com" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Téléphone</label>
                        <input name="telephone" placeholder="Ex: 06 12 34 56 78" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Adresse</label>
                        <input name="adresse" placeholder="Ex: 123 Rue Principale" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Code postal</label>
                        <input name="code_postal" placeholder="Ex: 75000" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Ville</label>
                        <input name="ville" placeholder="Ex: Paris" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Plaque d'immatriculation</label>
                        <input name="plaque" placeholder="Ex: AB-123-CD" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Assurance</label>
                        <select name="nom_assurance" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Choisir l'assurance</option>
                            <option value="autre">Autre</option>
                            <option value="AXA">AXA</option>
                            <option value="Allianz">Allianz</option>
                            <option value="Groupama">Groupama</option>
                            <option value="MAIF">MAIF</option>
                            <option value="Matmut">Matmut</option>
                            <option value="MACIF">MACIF</option>
                            <option value="GMF">GMF</option>
                            <option value="Generali">Generali</option>
                            <option value="Crédit Agricole Assurances">Crédit Agricole Assurances</option>
                            <option value="CNP Assurances">CNP Assurances</option>
                            <option value="BNP Paribas Cardif">BNP Paribas Cardif</option>
                            <option value="SMA">SMA</option>
                            <option value="MMA">MMA</option>
                            <option value="AGPM">AGPM</option>
                            <option value="Covéa">Covéa</option>
                            <option value="Swiss Life">Swiss Life</option>
                            <option value="Aviva">Aviva</option>
                            <option value="AcommeAssure">AcommeAssure</option>
                            <option value="Luko">Luko</option>
                            <option value="Lemonade">Lemonade</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Si autre assurance</label>
                        <input name="autre_assurance" placeholder="Précisez le nom" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center text-sm text-gray-700 font-medium">
                        <input type="checkbox" name="ancien_modele_plaque" class="form-checkbox h-5 w-5 text-orange-500 rounded mr-2">
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
                        <input name="numero_police" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="N° Police d'assurance" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Date du sinistre</label>
                        <input type="date" name="date_sinistre" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Date de déclaration</label>
                        <input type="date" name="date_declaration" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Raison du sinistre</label>
                        <input name="raison" placeholder="Ex: Bris de glace" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type de vitrage</label>
                        <select name="type_vitrage" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Sélectionner le type</option>
                            <option value="Pare-brise">Pare-brise</option>
                            <option value="Lunette arrière">Lunette arrière</option>
                            <option value="Vitre porte latérale avant - [Gauche]">Vitre porte latérale avant - [Gauche]</option>
                            <option value="Vitre porte latérale avant - [Droite]">Vitre porte latérale avant - [Droite]</option>
                            <option value="Vitre porte latérale arrière - [Gauche]">Vitre porte latérale arrière - [Gauche]</option>
                            <option value="Vitre porte latérale arrière - [Droite]">Vitre porte latérale arrière - [Droite]</option>
                            <option value="Pavillon panoramique">Pavillon panoramique</option>
                            <option value="Vitre triangulaire avant - [Gauche]">Vitre triangulaire avant - [Gauche]</option>
                            <option value="Vitre triangulaire avant - [Droite]">Vitre triangulaire avant - [Droite]</option>
                            <option value="Toit ouvrant arrière vitré (complet)">Toit ouvrant arrière vitré (complet)</option>
                            <option value="Phare avant (complet) - [Gauche]">Phare avant (complet) - [Gauche]</option>
                            <option value="Phare avant (complet) - [Droite]">Phare avant (complet) - [Droite]</option>
                            <option value="Vitre de custode">Vitre de custode</option>
                            <option value="Vitre de toit ouvrant">Vitre de toit ouvrant</option>
                            <option value="Vitre de hayon">Vitre de hayon</option>
                            <option value="Vitre de portière">Vitre de portière</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type personnalisé</label>
                        <input name="type_vitrage_custom" placeholder="Précisez le type" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2 font-medium">Professionnel</label>
                    <select name="professionnel" class="form-select w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Sélectionner...</option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
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
                            <input type="radio" name="reparation" value="1" class="form-radio h-4 w-4 text-orange-500 mr-2">
                            <span>Oui</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="reparation" value="0" class="form-radio h-4 w-4 text-orange-500 mr-2">
                            <span>Non</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Photo du vitrage</label>
                        <input type="file" name="photo_vitrage" class="form-input-file w-full" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Carte verte</label>
                        <input type="file" name="photo_carte_verte" class="form-input-file w-full" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Carte grise</label>
                        <input type="file" name="photo_carte_grise" class="form-input-file w-full" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Type de cadeau</label>
                        <input name="type_cadeau" placeholder="Ex: Carte cadeau" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Numéro de sinistre</label>
                        <input name="numero_sinistre" placeholder="Ex: SIN-12345" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Kilométrage</label>
                        <input name="kilometrage" placeholder="Ex: 45 000 km" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Agent/Commercial</label>
                        <input name="agent_commercial" placeholder="Ex: Martin Dupont" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Connu par</label>
                        <input name="connu_par" placeholder="Ex: Recommandation" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Adresse de pose</label>
                        <input name="adresse_pose" placeholder="Si différent du domicile" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Référence interne</label>
                        <input name="reference_interne" placeholder="Ex: REF-001" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Référence client</label>
                        <input name="reference_client" placeholder="Ex: CLT-456" class="form-input w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Précision à apporter</label>
                    <textarea name="precision" rows="4" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Saisissez les détails supplémentaires ici..."></textarea>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center pt-6">
                <button type="submit" class="btn-submit bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg px-8 py-3 rounded-lg shadow transition duration-300 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Créer le dossier
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