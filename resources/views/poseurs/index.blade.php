@extends('layout')

@section('content')
<div class="min-h-screen ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-orange-500 text-white p-2 rounded-lg mr-3">
                        <i class="fas fa-hard-hat"></i>
                    </span>
                    Gestion des Poseurs
                </h1>
                <p class="text-gray-600 mt-2">Liste complète de vos poseurs avec options de gestion</p>
            </div>
            <a href="{{ route('poseurs.create') }}" class="btn-primary text-white px-5 py-3 rounded-lg font-medium flex items-center justify-center w-full md:w-auto">
                <i class="fas fa-plus mr-2"></i> Ajouter un poseur
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="card bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Poseurs</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalPoseurs }}</h3>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <i class="fas fa-users text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="card bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Poseurs Actifs</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $activePoseurs }}</h3>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
           
            
            <div class=" ">
             
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <form method="GET" action="{{ route('poseurs.index') }}" class="w-full">
                <div class="relative search-box">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-0" 
                        placeholder="Rechercher par nom, email, téléphone...">
                </div>
            </form>
        </div>

        <!-- Poseurs Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poseur
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Localisation
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($poseurs as $poseur)
                        <tr class="hover:bg-orange-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-bold mr-3">
                                        {{ substr($poseur->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $poseur->nom }}</div>
                                        <div class="text-gray-500 text-sm">{{ $poseur->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">{{ $poseur->telephone }}</div>
                                <div class="text-gray-500 text-sm">{{ $poseur->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">{{ $poseur->ville }}</div>
                                <div class="text-gray-500 text-sm">{{ $poseur->code_postal }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($poseur->actif)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-circle text-[8px] mr-2"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-circle text-[8px] mr-2"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('poseurs.edit', $poseur) }}" class="text-blue-600 hover:text-blue-900 edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('poseurs.destroy', $poseur) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-lg shadow-sm">
            <div class="text-sm text-gray-700">
                Affichage de <span class="font-medium">{{ $poseurs->firstItem() }}</span> à <span class="font-medium">{{ $poseurs->lastItem() }}</span> sur <span class="font-medium">{{ $poseurs->total() }}</span> poseurs
            </div>
            <div class="flex space-x-2">
                @if ($poseurs->onFirstPage())
                    <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $poseurs->previousPageUrl() }}" class="px-3 py-1 rounded bg-orange-50 text-orange-600 hover:bg-orange-100">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @if ($poseurs->hasMorePages())
                    <a href="{{ $poseurs->nextPageUrl() }}" class="px-3 py-1 rounded bg-orange-50 text-orange-600 hover:bg-orange-100">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background-color: #f9fafb;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 14px;
        border: 1px solid #f0f0f0;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -10px rgba(249, 115, 22, 0.15);
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
    
    .search-box:focus-within {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }
    
    .edit-btn, .delete-btn {
        transition: all 0.2s ease;
        padding: 6px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    
    .edit-btn:hover {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .delete-btn:hover {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    table thead th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
    }
    
    table tbody tr:last-child td {
        border-bottom: none;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endsection