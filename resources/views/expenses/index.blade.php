@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Gestion des Dépenses</h1>
            <p class="text-gray-600 mt-1">Liste des dépenses enregistrées dans le système</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            Ajouter une dépense
        </a>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Column Visibility -->
            <div class="relative">
                <button onclick="toggleColumnMenu()" class="flex items-center gap-2 border border-gray-200 bg-white px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Colonnes visibles
                </button>
                <div id="columnMenu" class="absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-lg z-50 p-4 hidden">
                    <h3 class="font-medium text-gray-800 mb-3">Afficher/Masquer</h3>
                    <ul class="space-y-2">
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-date" checked>
                                <span>Date</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-dossier" checked>
                                <span>Dossier</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-fournisseur" checked>
                                <span>Fournisseur</span>
                            </label>
                        </li>
                     
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-ht" checked>
                                <span>Montant HT</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-ttc" checked>
                                <span>Montant TTC</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="col-actions" checked>
                                <span>Actions</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Export Buttons -->
<div class="flex flex-wrap gap-2">
    <a href="{{ route('expenses.export.excel') }}" 
       class="flex items-center gap-2 bg-white border border-green-500 text-green-600 hover:bg-green-50 px-4 py-2.5 rounded-lg transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        Export Excel
    </a>
    

</div>
            
            <!-- Search and Filter -->
            <div class="ml-auto flex flex-wrap gap-2">
               
               
            </div>
        </div>
    </div>

    <!-- Dépenses Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full table-auto min-w-[800px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-date">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-dossier">Dossier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fournisseur">Fournisseur</th>
                       
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ht">HT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ttc">TTC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap col-date">
                            <div class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-dossier">
                            <div class="font-medium">{{ $expense->client->prenom }} {{ $expense->client->nom_assure }}</div>
                            <div class="text-xs text-gray-500 mt-1">#{{ $expense->client->reference_client }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap col-fournisseur">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">{{ $expense->fournisseur->nom_societe }}</span>
                            </div>
                        </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-ht">
                            {{ number_format($expense->ht_amount, 2, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 col-ttc">
                            {{ number_format($expense->ttc_amount, 2, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-actions">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Modifier
                                </a>
                                
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1 text-red-600 hover:text-red-800 bg-transparent border-none p-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune dépense trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 flex flex-col sm:flex-row items-center justify-between border-t border-gray-100">
            <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                Affichage <span class="font-medium">{{ $expenses->firstItem() }}</span> à 
                <span class="font-medium">{{ $expenses->lastItem() }}</span> sur 
                <span class="font-medium">{{ $expenses->total() }}</span> résultats
            </div>
            <div class="flex space-x-1">
                @if ($expenses->onFirstPage())
                    <button class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <a href="{{ $expenses->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                @foreach ($expenses->getUrlRange(1, $expenses->lastPage()) as $page => $url)
                    @if ($page == $expenses->currentPage())
                        <span class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-orange-500 border border-orange-500 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($expenses->hasMorePages())
                    <a href="{{ $expenses->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <button class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function toggleColumnMenu() {
        const menu = document.getElementById('columnMenu');
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const columnMenu = document.getElementById('columnMenu');
        const button = document.querySelector('button[onclick="toggleColumnMenu()"]');
        
        if (columnMenu && button) {
            if (!columnMenu.contains(e.target) && !button.contains(e.target)) {
                columnMenu.classList.add('hidden');
            }
        }
    });

    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const columnClass = this.dataset.column;
            const isVisible = this.checked;
            
            document.querySelectorAll(`.${columnClass}`).forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
            
            // Store preference in localStorage
            localStorage.setItem(columnClass, isVisible);
        });
    });

    // Load column preferences
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            const columnClass = toggle.dataset.column;
            const isVisible = localStorage.getItem(columnClass) !== 'false';
            
            toggle.checked = isVisible;
            document.querySelectorAll(`.${columnClass}`).forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if(confirm('Voulez-vous vraiment supprimer cette dépense ?')) {
                this.submit();
            }
        });
    });
</script>
@endsection