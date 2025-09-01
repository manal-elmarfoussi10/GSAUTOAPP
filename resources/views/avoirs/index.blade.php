@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mes Avoirs</h1>
            <p class="text-gray-600 mt-1">Liste des avoirs enregistrés dans le système</p>
        </div>
        <a href="{{ route('avoirs.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            Ajouter un Avoir
        </a>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Column Visibility -->
            <div class="relative">
                <button onclick="toggleAvoirColumnMenu()" class="flex items-center gap-2 border border-gray-200 bg-white px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Colonnes visibles
                </button>
                <div id="avoirColumnMenu" class="absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-lg z-50 p-4 hidden">
                    <h3 class="font-medium text-gray-800 mb-3">Afficher/Masquer</h3>
                    <ul class="space-y-2">
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-date" checked>
                                <span>Date</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-dossier" checked>
                                <span>Dossier</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-actions" checked>
                                <span>Actions</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-avoir" checked>
                                <span>Avoir</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-ht" checked>
                                <span>HT</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-ttc" checked>
                                <span>TTC</span>
                            </label>
                        </li>
                     
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-facture" checked>
                                <span>Facture associé</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-annee" checked>
                                <span>Année fiscale</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle-avoir rounded text-orange-500" data-column="col-rdv" checked>
                                <span>Date de RDV</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('avoirs.export.excel') }}" class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg text-sm bg-white text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('avoirs.export.pdf') }}" class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg text-sm bg-white text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Avoirs Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full table-auto min-w-[1200px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-date">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-dossier">Dossier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-actions">Actions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-avoir">Avoir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ht">HT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ttc">TTC</th>
                       
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-facture">Facture associé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-annee">Année fiscale</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-rdv">Date de RDV</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($avoirs as $avoir)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 col-date">{{ $avoir->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-dossier">
                            {{ $avoir->facture->client->nom_assure ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 space-y-1 col-actions">
                            <a href="{{ route('avoirs.edit', $avoir->id) }}">Modifier</a><br>
                            <form action="{{ route('avoirs.destroy', $avoir->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600">Supprimer</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-avoir">
                            <a href="{{ route('avoirs.pdf', $avoir->id) }}" class="bg-teal-100 text-teal-700 px-2 py-1 rounded">Télécharger</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-ht">{{ number_format($avoir->montant, 2) }} €</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-ttc">{{ number_format($avoir->montant, 2) }} €</td>
                       
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-facture">
                            @if($avoir->facture)
                                <a href="{{ route('factures.show', $avoir->facture->id) }}" class="text-blue-600 hover:underline">
                                    {{ $avoir->facture->numero ?? 'Facture #' . $avoir->facture->id }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-annee">{{ $avoir->created_at->year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-rdv">
                            {{ optional(optional($avoir->facture)->client->rdvs->first())->start_time ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleAvoirColumnMenu() {
        const menu = document.getElementById('avoirColumnMenu');
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const columnMenu = document.getElementById('avoirColumnMenu');
        const button = document.querySelector('button[onclick="toggleAvoirColumnMenu()"]');
        
        if (!columnMenu.contains(e.target) && !button.contains(e.target)) {
            columnMenu.classList.add('hidden');
        }
    });

    document.querySelectorAll('.column-toggle-avoir').forEach(toggle => {
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
        document.querySelectorAll('.column-toggle-avoir').forEach(toggle => {
            const columnClass = toggle.dataset.column;
            const isVisible = localStorage.getItem(columnClass) !== 'false';
            
            toggle.checked = isVisible;
            document.querySelectorAll(`.${columnClass}`).forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
        });
    });
</script>
@endsection