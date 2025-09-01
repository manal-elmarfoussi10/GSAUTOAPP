@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <!-- Error messages -->
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier le fournisseur
                </h1>
                <p class="mt-1 text-sm text-gray-600">Modifiez les informations de {{ $fournisseur->nom_societe }}</p>
            </div>
            <a href="{{ route('fournisseurs.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Form with card sections -->
        <form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations générales card -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Informations générales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la société *</label>
                            <input type="text" name="nom_societe" value="{{ old('nom_societe', $fournisseur->nom_societe) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $fournisseur->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                            <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                                <option value="">Sélectionner...</option>
                                @foreach([
                                    'Assurance',
                                    'Partenaires',
                                    'Avoirs Virements clients (709 Rabais, remises et ristournes accordés par l\'entreprise)',
                                    'Service (706 Prestations de services)',
                                    'Autres charges sociales (647)',
                                    'Charges de sécurité sociale et de prévoyance (645)',
                                    'Cotisations à l\'URSSAF (6451)',
                                    'Rémunération du personnel (641)',
                                    'Note de frais (625 Déplacements, missions et réceptions)',
                                    'Cadeaux à la clientèle (6234)',
                                    'Honoraires (6226)',
                                    'Autres (618 Divers)',
                                    'Services bancaires et assimilés (627)',
                                    'Frais postaux et de télécommunications (626)',
                                    'Locations (613)',
                                    'Sous-traitance générale (611)',
                                    'Achats de marchandises (607)',
                                    'Comptes transitoires ou d\'attente (47)',
                                    'Rabais, remises, ristournes à accorder et autres avoirs à établir (4198)',
                                    'Emprunts et dettes assimilées (16)',
                                    'Main d\'oeuvre (611 Sous-traitance générale)'
                                ] as $cat)
                                    <option value="{{ $cat }}" {{ $fournisseur->categorie === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adresse card -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Adresse
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'adresse</label>
                            <input type="text" name="adresse_nom" value="{{ old('adresse_nom', $fournisseur->adresse_nom) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rue</label>
                            <input type="text" name="adresse_rue" value="{{ old('adresse_rue', $fournisseur->adresse_rue) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                            <input type="text" name="adresse_cp" value="{{ old('adresse_cp', $fournisseur->adresse_cp) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" name="adresse_ville" value="{{ old('adresse_ville', $fournisseur->adresse_ville) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Types d'adresse</label>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="adresse_facturation" class="h-4 w-4 text-orange-600 rounded focus:ring-orange-500 border-gray-300" {{ $fournisseur->adresse_facturation ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Facturation</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="adresse_livraison" class="h-4 w-4 text-orange-600 rounded focus:ring-orange-500 border-gray-300" {{ $fournisseur->adresse_livraison ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Livraison</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="adresse_devis" class="h-4 w-4 text-orange-600 rounded focus:ring-orange-500 border-gray-300" {{ $fournisseur->adresse_devis ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Devis</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact card -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Contact
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du contact</label>
                            <input type="text" name="contact_nom" value="{{ old('contact_nom', $fournisseur->contact_nom) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email du contact</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $fournisseur->contact_email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone du contact</label>
                            <input type="text" name="contact_telephone" value="{{ old('contact_telephone', $fournisseur->contact_telephone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 transition duration-150">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 transform hover:-translate-y-0.5">
                    <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
    
    .transform {
        transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
    }
    
    .hover\:-translate-y-0\.5:hover {
        transform: translateY(-0.125rem);
    }
    
    .focus\:ring-orange-500:focus {
        box-shadow: 0 0 0 3px rgba(255, 123, 0, 0.5);
    }
    
    .focus\:border-orange-500:focus {
        border-color: #ff7b00;
    }
    
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
</style>
@endsection