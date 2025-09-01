@extends('layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-orange-500 text-white p-2 rounded-lg mr-3">
                        <i class="fas fa-user-edit"></i>
                    </span>
                    Modifier le poseur: {{ $poseur->nom }}
                </h1>
                <p class="text-gray-600 mt-2">Mettez à jour les informations de ce poseur</p>
            </div>
            <a href="{{ route('poseurs.index') }}" class="flex items-center text-orange-600 hover:text-orange-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Erreur(s) dans le formulaire</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form action="{{ route('poseurs.update', $poseur) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Main Information Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-orange-500 mr-2"></i>
                        Informations principales
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Nom complet <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="nom" value="{{ old('nom', $poseur->nom) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Téléphone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="telephone" value="{{ old('telephone', $poseur->telephone) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $poseur->email) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>

                     

                        <div class="flex items-center">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="hidden" name="actif" value="0">
                                    <input type="checkbox" name="actif" id="actif" value="1" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded" {{ old('actif', $poseur->actif) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="actif" class="font-medium text-gray-700">Poseur actif</label>
                                    <p class="text-gray-500">Décochez pour désactiver ce poseur</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                        Adresse
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Rue</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-road text-gray-400"></i>
                                </div>
                                <input type="text" name="rue" value="{{ old('rue', $poseur->rue) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Code postal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-mail-bulk text-gray-400"></i>
                                </div>
                                <input type="text" name="code_postal" value="{{ old('code_postal', $poseur->code_postal) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Ville</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-city text-gray-400"></i>
                                </div>
                                <input type="text" name="ville" value="{{ old('ville', $poseur->ville) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                        Informations supplémentaires
                    </h2>
                    
                    <div class="mb-6">
                        <label class="block mb-2 font-medium text-gray-700">Notes ou informations complémentaires</label>
                        <textarea name="info" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-500">{{ old('info', $poseur->info) }}</textarea>
                    </div>

               

                    <!-- Submit Button -->
                    <div class="text-right mt-6">
                        <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-medium flex items-center justify-center w-full md:w-auto">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background-color: #f9fafb;
    }
    
    .btn-primary {
        background: linear-gradient(to right, #f97316, #fb923c);
        transition: all 0.3s ease;
        border: none;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(to right, #ea580c, #f97316);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }
    
    input:focus, textarea:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }
    
    .form-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endsection