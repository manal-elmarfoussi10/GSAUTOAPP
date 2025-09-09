@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Gestion des Factures</h1>
            <p class="text-gray-600 mt-1">Liste des factures enregistrées dans le système</p>
        </div>
        <a href="{{ route('factures.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>
            Ajouter une facture
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
                        @foreach([
                            'col-facture' => 'Facture',
                            'col-dossier' => 'Dossier',
                            'col-ht' => 'Montant HT',
                            'col-ttc' => 'Montant TTC',
                            'col-avoir' => 'Total Avoir',
                            'col-reste' => 'RESTE',
                            'col-status' => 'Statut',
                            'col-actions' => 'Actions'
                        ] as $column => $label)
                        <li>
                            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                                <input type="checkbox" class="column-toggle rounded text-orange-500" data-column="{{ $column }}" checked>
                                <span>{{ $label }}</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('factures.export.excel') }}" class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg text-sm bg-white text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('factures.export.pdf') }}" class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-lg text-sm bg-white text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Facture Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full table-auto min-w-[1000px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-facture">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-dossier">Dossier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ht">HT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-ttc">TTC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-avoir">Total Avoir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-reste">RESTE</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-status">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($factures as $facture)
                    @php
                        $paye   = $facture->paiements?->sum('montant') ?? 0;
                        $avoir  = $facture->avoirs?->sum('montant') ?? 0;
                        $reste  = $facture->total_ttc - $paye - $avoir;
                        $statusClass = $reste == 0 ? 'bg-green-100 text-green-800' : ($paye > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                        $statusText  = $reste == 0 ? 'Acquittée' : ($paye > 0 ? 'Partiellement payée' : 'Non acquittée');

                        // Name to display: client name OR prospect name from devis
                        $displayName = $facture->client->nom_assure
                                       ?? optional($facture->devis)->prospect_name
                                       ?? '-';
                        $subtitle    = $facture->client?->reference
                                       ?? (optional($facture->devis)->prospect_name ? 'Prospect' : '');
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap col-facture">
                            <a href="{{ route('factures.download.pdf', $facture->id) }}" class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium hover:bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                #{{ $facture->id }}
                            </a>
                        </td>

                        <!-- DOSSIER: show client name OR prospect name -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-dossier">
                            <div class="font-medium">{{ $displayName }}</div>
                            @if($subtitle)
                                <div class="text-xs text-gray-500 mt-1">{{ $subtitle }}</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-ht">{{ number_format($facture->total_ht, 2) }}€</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 col-ttc">{{ number_format($facture->total_ttc, 2) }}€</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 col-avoir">{{ number_format($avoir, 2) }}€</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 col-reste">{{ number_format($reste, 2) }}€</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm col-status">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                @if($reste == 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                @elseif($paye > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                @endif
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm col-actions">
                            <div class="sm:items-center gap-3">
                                @if ($reste > 0)
                                    <a href="{{ route('paiements.create', ['facture_id' => $facture->id]) }}" class="flex items-center gap-1 text-green-600 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                        </svg>
                                        Paiement
                                    </a>
                                    <a href="{{ route('avoirs.create.fromFacture', $facture->id) }}" class="flex items-center gap-1 text-purple-600 hover:text-purple-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Avoir
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-gray-100 p-5 rounded-full mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="mt-2 text-lg font-medium text-gray-700">Aucune facture trouvée</p>
                                <p class="mt-1 text-gray-500">Commencez par créer votre première facture</p>
                                <a href="{{ route('factures.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Créer une facture
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
        
        if (!columnMenu.contains(e.target) && !button.contains(e.target)) {
            columnMenu.classList.add('hidden');
        }
    });

    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const columnClass = this.dataset.column;
            const isVisible = this.checked;
            document.querySelectorAll(`.${columnClass}`).forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
            localStorage.setItem(columnClass, isVisible);
        });
    });

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
</script>
@endsection