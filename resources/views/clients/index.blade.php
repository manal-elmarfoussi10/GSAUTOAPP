@extends('layout')

@section('content')
<div class="flex-1 min-w-0"">
    <div class="max-w-7xl mx-auto">
        <!-- Header with stats -->
        <div class="bg-white rounded-2xl p-6 mb-6 shadow-md">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Gestion dossiers</h1>
                    <p class="text-gray-600 mt-1">Gérez efficacement vos dossiers clients bris de glace</p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('clients.create') }}" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Ajouter un dossier client
                    </a>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                <div class="stat-card bg-blue-50">
                    <div class="text-gray-600">Total Clients</div>
                    <div class="text-3xl font-bold text-blue-600">{{ count($clients) }}</div>
                    <div class="flex items-center mt-2 text-sm text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        +12% ce mois
                    </div>
                </div>
                
                <div class="stat-card bg-purple-50">
                    <div class="text-gray-600">Chiffre d'Affaires</div>
                    <div class="text-3xl font-bold text-purple-600">
                        @php
                            $totalCA = 0;
                            foreach ($clients as $client) {
                                $factureTotal = $client->factures->sum('total_ht') ?? 0;
                                $avoirTotal = 0;
                                foreach ($client->factures as $facture) {
                                    foreach ($facture->avoirs as $avoir) {
                                        $avoirTotal += $avoir->montant_ht ?? 0;
                                    }
                                }
                                $totalCA += $factureTotal - $avoirTotal;
                            }
                            echo number_format($totalCA, 2, ',', ' ') . ' €';
                        @endphp
                    </div>
                    <div class="mt-2 text-sm text-gray-600">Derniers 30 jours</div>
                </div>
                
                <div class="stat-card bg-green-50">
                    <div class="text-gray-600">Dossiers Terminés</div>
                    <div class="text-3xl font-bold text-green-600">
                        {{ $clients->where('statut_gg', 'Termine')->count() }}
                    </div>
                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ count($clients) ? round($clients->where('statut_gg', 'Termine')->count() / count($clients) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="stat-card bg-orange-50">
                    <div class="text-gray-600">En Attente</div>
                    <div class="text-3xl font-bold text-orange-600">
                        {{ $clients->where('statut_gg', '!=', 'Termine')->count() }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-orange-600">
                        <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                        Action nécessaire
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl p-6 mb-6 shadow-md">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher un client..." class="search-input w-full pl-10" id="searchInput">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <button onclick="toggleColumnFilters()" class="btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                        </svg>
                        Colonnes visibles
                    </button>
                    
                    <select class="form-select">
                        <option>Trier par: Date récente</option>
                        <option>Trier par: Ancien dossier</option>
                        <option>Trier par: Nom (A-Z)</option>
                        <option>Trier par: Nom (Z-A)</option>
                    </select>
                </div>
            </div>
            
            <!-- Column Visibility Filter -->
            <div id="columnFilters" class="flex flex-wrap gap-4 mt-4 p-4 bg-gray-50 rounded-lg hidden">
                @foreach ($columns as $colKey => $colLabel)
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-orange-500 rounded mr-2 toggle-col" data-col="col-{{ $colKey }}" checked>
                        <span class="text-gray-700">{{ $colLabel }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 text-left">
                        <tr>
                            @foreach ($columns as $colKey => $colLabel)
                                <th class="p-4 font-semibold uppercase text-xs col-{{ $colKey }}">{{ $colLabel }}</th>
                            @endforeach
                            <th class="p-4 font-semibold uppercase text-xs text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($clients as $client)
                            @php
                                $factureTotal = $client->factures->sum('total_ht') ?? 0;
                                $devisTotal = $client->devis->sum('total_ht') ?? 0;
                                $avoirTotal = 0;
                                foreach ($client->factures as $facture) {
                                    foreach ($facture->avoirs as $avoir) {
                                        $avoirTotal += $avoir->montant_ht ?? 0;
                                    }
                                }
                                $marge = $factureTotal - $avoirTotal;
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="p-4 col-date">
                                    <div class="text-gray-500 text-sm">{{ $client->created_at->format('d/m') }}</div>
                                    <div class="text-xs text-gray-400">{{ $client->created_at->format('Y') }}</div>
                                </td>
                                
                                <td class="p-4 font-medium col-dossier">
                                    <a href="{{ route('clients.show', $client->id) }}" class="hover:text-orange-500 transition-colors flex items-center">
                                        <div class="bg-gray-100 rounded-xl w-12 h-12 mr-3 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $client->nom_assure }} {{ $client->prenom }}</div>
                                            <div class="text-sm text-gray-500">{{ $client->modele_vehicule }} - {{ $client->immatriculation }}</div>
                                        </div>
                                    </a>
                                </td>
                                
                                <td class="p-4 col-statut">
                                    @if($client->statut_gg === 'Termine')
                                        <span class="status-badge bg-green-100 text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Terminé
                                        </span>
                                    @elseif($client->statut_gg === 'Signature')
                                        <span class="status-badge bg-purple-100 text-purple-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Signature
                                        </span>
                                    @elseif($client->statut_gg === 'Envoi Courrier')
                                        <span class="status-badge bg-orange-100 text-orange-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Envoi Courrier
                                        </span>
                                    @else
                                        <span class="status-badge bg-gray-100 text-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            En attente
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 col-assurance">
                                    <div class="font-medium">{{ $client->nom_assurance }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->numero_police }}</div>
                                </td>

                                <!-- Factures -->
                                <td class="p-4 col-facture">
                                    <div class="font-medium text-gray-800">{{ number_format($factureTotal, 2, ',', ' ') }} €</div>
                                </td>

                                <!-- Avoirs -->
                                <td class="p-4 col-avoir">
                                    <div class="text-gray-800">{{ number_format($avoirTotal, 2, ',', ' ') }} €</div>
                                </td>

                                <!-- Devis -->
                                <td class="p-4 col-devis">
                                    <div class="text-gray-800">{{ number_format($devisTotal, 2, ',', ' ') }} €</div>
                                </td>

                                <!-- Encaisse -->
                                <td class="p-4 col-encaisse">
                                    <div class="text-gray-800">{{ $client->encaisse ?? '-' }}</div>
                                </td>

                                <!-- Cadeau -->
                                <td class="p-4 col-cadeau">
                                    <div class="text-gray-800">{{ $client->type_cadeau ?? '-' }}</div>
                                </td>

                                <!-- Franchise -->
                                <td class="p-4 col-franchise">
                                    <div class="text-gray-800">{{ $client->franchise ?? '-' }}</div>
                                </td>

                                <!-- Poseur -->
                                <td class="p-4 col-poseur">
                                    <div class="text-gray-800">{{ $client->poseur ?? '-' }}</div>
                                </td>

                                <!-- Vitrage -->
                                <td class="p-4 col-vitrage">
                                    <div class="text-gray-800">{{ $client->type_vitrage ?? '-' }}</div>
                                </td>

                                <!-- Téléphone -->
                                <td class="p-4 col-phone">
                                    <div class="text-gray-800">{{ $client->telephone ?? '-' }}</div>
                                </td>

                                <!-- Marge -->
                                <td class="p-4 col-marge">
                                    <div class="font-medium text-gray-800">{{ number_format($marge, 2, ',', ' ') }} €</div>
                                </td>
                                
                                <!-- Actions -->
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn-icon text-blue-500 hover:bg-blue-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('clients.show', $client->id) }}" class="btn-icon text-green-500 hover:bg-green-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon text-red-500 hover:bg-red-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="text-sm text-gray-700 mb-4 md:mb-0">
                        Affichage de <span class="font-medium">1</span> à <span class="font-medium">{{ count($clients) }}</span> 
                        sur <span class="font-medium">{{ count($clients) }}</span> clients
                    </div>
                    
                    <div class="inline-flex mt-2 md:mt-0">
                        <button class="btn-pagination disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="btn-pagination bg-orange-500 text-white">1</button>
                        <button class="btn-pagination">2</button>
                        <button class="btn-pagination">3</button>
                        <button class="btn-pagination">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background: #FF6B00;
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-primary:hover {
        background: #D45A00;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 0, 0.4);
    }
    
    .stat-card {
        padding: 20px;
        border-radius: 12px;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .btn-secondary {
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #e2e8f0;
    }
    
    .form-select {
        background: #f1f5f9;
        color: #1e293b;
        border: none;
        padding: 10px 18px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input {
        width: 100%;
        padding: 10px 18px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #FF6B00;
        box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
    }
    
    .btn-icon {
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .btn-icon:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    
    .btn-pagination {
        height: 36px;
        width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        color: #4b5563;
        margin-right: 8px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-pagination:hover:not(.disabled) {
        background: #f1f5f9;
    }
    
    .btn-pagination.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
    function toggleColumnFilters() {
        const el = document.getElementById('columnFilters');
        el.classList.toggle('hidden');
    }

    document.querySelectorAll('.toggle-col').forEach(cb => {
        cb.addEventListener('change', function () {
            const colClass = this.dataset.col;
            document.querySelectorAll(`.${colClass}`).forEach(el => {
                el.style.display = this.checked ? '' : 'none';
            });
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection