@extends('layout')

@section('content')
<div class="min-h-screen ">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-orange-500 text-white p-2 rounded-lg mr-3">
                        <i class="fas fa-edit"></i>
                    </span>
                    Modifier le produit: {{ $stock->reference }}
                </h1>
                <p class="text-gray-600 mt-2">Mettez à jour les informations de ce produit en stock</p>
            </div>
            <a href="{{ route('stocks.index') }}" class="flex items-center text-orange-600 hover:text-orange-800 font-medium">
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
            <form action="{{ route('stocks.update', $stock) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Product Information Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                        Informations sur le produit
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Produit <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-cube text-gray-400"></i>
                                </div>
                                <select name="produit_id" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 appearance-none" required>
                                    <option value="">-- Sélectionner un produit --</option>
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}" {{ $stock->produit_id == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Référence <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-barcode text-gray-400"></i>
                                </div>
                                <input type="text" name="reference" value="{{ old('reference', $stock->reference) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <select name="statut" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 appearance-none" required>
                                    @foreach([
                                        'À COMMANDER', 
                                        'COMMANDÉ', 
                                        'LIVRÉ', 
                                        'POSÉ', 
                                        'A RETOURNER', 
                                        'CASSÉ À LA LIVRAISON', 
                                        'CASSÉ POSÉ', 
                                        'RETOURNÉ', 
                                        'STOCKÉ', 
                                        'ATTENTE REMBOURSEMENT', 
                                        'REMBOURSÉ'
                                    ] as $status)
                                        <option value="{{ $status }}" {{ $stock->statut == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="date" value="{{ old('date', $stock->date) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier and Installer Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-truck text-orange-500 mr-2"></i>
                        Fournisseur et poseur
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Fournisseur <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <select name="fournisseur_id" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 appearance-none" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" {{ $stock->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                            {{ $fournisseur->nom_societe }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Poseur</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-hard-hat text-gray-400"></i>
                                </div>
                                <select name="poseur_id" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-200 focus:border-orange-500 appearance-none">
                                    <option value="">-- Sélectionner un poseur --</option>
                                    @foreach($poseurs as $poseur)
                                        <option value="{{ $poseur->id }}" {{ $stock->poseur_id == $poseur->id ? 'selected' : '' }}>
                                            {{ $poseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle text-orange-500 mr-1"></i> 
                            Tous les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires
                        </div>
                        <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-medium flex items-center justify-center w-full sm:w-auto">
                            <i class="fas fa-save mr-2"></i> Mettre à jour le produit
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
    
    select, input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }
    
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }
    
    .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, #fff7ed, #fffbeb, #f0fdfa);
    }
    
    .rounded-xl {
        border-radius: 1rem;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endsection