@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error messages with improved styling -->
        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Header section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter un produit
                </h1>
                <p class="mt-1 text-sm text-gray-600">Remplissez les informations ci-dessous pour créer un nouveau produit</p>
            </div>
            <a href="{{ route('produits.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Form with card sections -->
        <form action="{{ route('produits.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Product information card -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        Informations du produit
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code produit</label>
                            <input type="text" name="code" value="{{ old('code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (HT) *</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" name="prix_ht" value="{{ old('prix_ht') }}" step="0.01" required class="block w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Montant TVA</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" name="montant_tva" value="{{ old('montant_tva') }}" step="0.01" class="block w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                            <select name="categorie" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                                <option value="">Sélectionner...</option>
                                <option value="Assurance" {{ old('categorie') == 'Assurance' ? 'selected' : '' }}>Assurance</option>
                                <option value="Partenaires" {{ old('categorie') == 'Partenaires' ? 'selected' : '' }}>Partenaires</option>
                                <option value="Avoirs Virements clients (709)" {{ old('categorie') == 'Avoirs Virements clients (709)' ? 'selected' : '' }}>Avoirs Virements clients (709 Rabais, remises et ristournes accordés par l'entreprise)</option>
                                <option value="Service (706)" {{ old('categorie') == 'Service (706)' ? 'selected' : '' }}>Service (706 Prestations de services)</option>
                                <option value="Autres charges sociales (647)" {{ old('categorie') == 'Autres charges sociales (647)' ? 'selected' : '' }}>Autres charges sociales (647)</option>
                                <option value="Charges de sécurité sociale et de prévoyance (645)" {{ old('categorie') == 'Charges de sécurité sociale et de prévoyance (645)' ? 'selected' : '' }}>Charges de sécurité sociale et de prévoyance (645)</option>
                                <option value="Cotisations à l'URSSAF (6451)" {{ old('categorie') == 'Cotisations à l\'URSSAF (6451)' ? 'selected' : '' }}>Cotisations à l'URSSAF (6451)</option>
                                <option value="Rémunération du personnel (641)" {{ old('categorie') == 'Rémunération du personnel (641)' ? 'selected' : '' }}>Rémunération du personnel (641)</option>
                                <option value="Note de frais (625)" {{ old('categorie') == 'Note de frais (625)' ? 'selected' : '' }}>Note de frais (625 Déplacements, missions et réceptions)</option>
                                <option value="Cadeaux à la clientèle (6234)" {{ old('categorie') == 'Cadeaux à la clientèle (6234)' ? 'selected' : '' }}>Cadeaux à la clientèle (6234)</option>
                                <option value="Honoraires (6226)" {{ old('categorie') == 'Honoraires (6226)' ? 'selected' : '' }}>Honoraires (6226)</option>
                                <option value="Autres (618)" {{ old('categorie') == 'Autres (618)' ? 'selected' : '' }}>Autres (618 Divers)</option>
                                <option value="Services bancaires et assimilés (627)" {{ old('categorie') == 'Services bancaires et assimilés (627)' ? 'selected' : '' }}>Services bancaires et assimilés (627)</option>
                                <option value="Frais postaux et de télécommunications (626)" {{ old('categorie') == 'Frais postaux et de télécommunications (626)' ? 'selected' : '' }}>Frais postaux et de télécommunications (626)</option>
                                <option value="Locations (613)" {{ old('categorie') == 'Locations (613)' ? 'selected' : '' }}>Locations (613)</option>
                                <option value="Sous-traitance générale (611)" {{ old('categorie') == 'Sous-traitance générale (611)' ? 'selected' : '' }}>Sous-traitance générale (611)</option>
                                <option value="Achats de marchandises (607)" {{ old('categorie') == 'Achats de marchandises (607)' ? 'selected' : '' }}>Achats de marchandises (607)</option>
                                <option value="Comptes transitoires ou d'attente (47)" {{ old('categorie') == 'Comptes transitoires ou d\'attente (47)' ? 'selected' : '' }}>Comptes transitoires ou d'attente (47)</option>
                                <option value="Rabais, remises, ristournes à accorder (4198)" {{ old('categorie') == 'Rabais, remises, ristournes à accorder (4198)' ? 'selected' : '' }}>Rabais, remises, ristournes à accorder et autres avoirs à établir (4198)</option>
                                <option value="Emprunts et dettes assimilées (16)" {{ old('categorie') == 'Emprunts et dettes assimilées (16)' ? 'selected' : '' }}>Emprunts et dettes assimilées (16)</option>
                                <option value="Main d'oeuvre (611)" {{ old('categorie') == 'Main d\'oeuvre (611)' ? 'selected' : '' }}>Main d'oeuvre (611 Sous-traitance générale)</option>
                              <option value="autres" {{ old('categorie') == 'autres' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>

                        <div class="flex items-center mt-2">
                            <input type="hidden" name="actif" value="0">
                            <div class="flex items-center h-5">
                                <input id="actif" name="actif" value="1" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded" {{ old('actif', true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="actif" class="font-medium text-gray-700">Produit actif</label>
                                <p class="text-gray-500">Décochez pour désactiver ce produit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 transform hover:-translate-y-0.5">
                    <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Enregistrer le produit
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    .hover\:-translate-y-0\.5:hover {
        transform: translateY(-0.125rem);
    }
    
    .transition {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .duration-150 {
        transition-duration: 150ms;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
    
    .focus\:ring-orange-500:focus {
        box-shadow: 0 0 0 3px rgba(255, 123, 0, 0.5);
    }
    
    .focus\:border-orange-500:focus {
        border-color: #ff7b00;
    }
    
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
</style>
@endsection