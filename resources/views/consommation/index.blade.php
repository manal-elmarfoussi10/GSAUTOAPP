@extends('layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec résumé des jetons -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div>
            <h1 class="text-3xl font-bold">Ma consommation de jetons</h1>
            <p class="text-blue-100 mt-2">Suivez l'utilisation de vos jetons pour chaque action dans GA Gestion</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center bg-white/20 backdrop-blur-sm rounded-xl p-4">
            <div class="mr-4">
                <i class="fas fa-coins text-yellow-300 text-3xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-blue-100">Solde actuel</p>
                <p class="text-2xl font-bold">42 <span class="text-lg">jetons</span></p>
            </div>
        </div>
    </div>

    <!-- Cartes de résumé -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Jetons utilisés</h3>
                    <p class="text-2xl font-bold text-gray-800 mt-1">28</p>
                </div>
                <div class="bg-red-100 text-red-600 rounded-full p-3">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <span>Ce mois</span>
                    <span class="mx-2">•</span>
                    <span class="text-red-500 font-medium">-5 jetons</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Jetons restants</h3>
                    <p class="text-2xl font-bold text-gray-800 mt-1">14</p>
                </div>
                <div class="bg-green-100 text-green-600 rounded-full p-3">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <span>Renouvellement</span>
                    <span class="mx-2">•</span>
                    <span class="text-green-500 font-medium">15 jours</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Actions fréquentes</h3>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Factures</p>
                </div>
                <div class="bg-purple-100 text-purple-600 rounded-full p-3">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500">
                    <span>Dernière action</span>
                    <span class="mx-2">•</span>
                    <span class="font-medium">Hier</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau de consommation -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Historique des consommations</h2>
            <p class="text-gray-600 text-sm mt-1">Toutes les actions consommant des jetons dans les 30 derniers jours</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dossier
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jetons
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Ligne 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">11/06/2025</div>
                            <div class="text-sm text-gray-500">14:30</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-blue-600">THIERRY LOCHIN</div>
                            <div class="text-sm text-gray-500">RENAULT KADJAR - EG-844-MF</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-minus mr-1"></i> -1 jeton
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice text-purple-500 mr-2"></i>
                                Facture client
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Complété
                            </span>
                        </td>
                    </tr>
                    
                    <!-- Ligne 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">10/06/2025</div>
                            <div class="text-sm text-gray-500">11:15</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-blue-600">MARIE DUPONT</div>
                            <div class="text-sm text-gray-500">PEUGEOT 308 - AB-123-CD</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-minus mr-1"></i> -1 jeton
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-clipboard-list text-orange-500 mr-2"></i>
                                Devis
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        </td>
                    </tr>
                    
                    <!-- Ligne 3 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">09/06/2025</div>
                            <div class="text-sm text-gray-500">16:45</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-blue-600">JEAN MARTIN</div>
                            <div class="text-sm text-gray-500">CITROËN C4 - FG-789-HI</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-minus mr-1"></i> -2 jetons
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice-dollar text-blue-500 mr-2"></i>
                                Facture fournisseur
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Complété
                            </span>
                        </td>
                    </tr>
                    
                    <!-- Plus de lignes... -->
                    @for($i = 0; $i < 6; $i++)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">0{{ 8 - $i }}/06/2025</div>
                            <div class="text-sm text-gray-500">09:2{{$i}}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-blue-600">CLIENT {{$i+1}}</div>
                            <div class="text-sm text-gray-500">VÉHICULE - PL-{{$i+10}}0-AB</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-minus mr-1"></i> -1 jeton
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice text-purple-500 mr-2"></i>
                                Facture client
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Complété
                            </span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de <span class="font-medium">1</span> à <span class="font-medium">9</span> sur <span class="font-medium">42</span> actions
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-3 py-1 rounded-md bg-blue-600 text-white">1</button>
                <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">2</button>
                <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">3</button>
                <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Section d'analyse -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Répartition par type</h3>
                <div class="relative">
                    <select class="bg-gray-100 border-0 rounded-lg px-3 py-1 text-sm">
                        <option>30 derniers jours</option>
                        <option>7 derniers jours</option>
                        <option>Ce mois</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-center h-64">
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-pie text-4xl mb-3 text-blue-500"></i>
                    <p>Graphique de répartition</p>
                    <p class="text-sm mt-1">(Disponible dans la version complète)</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Consommation journalière</h3>
                <div class="relative">
                    <select class="bg-gray-100 border-0 rounded-lg px-3 py-1 text-sm">
                        <option>Juillet 2025</option>
                        <option>Juin 2025</option>
                        <option>Mai 2025</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-center h-64">
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-line text-4xl mb-3 text-green-500"></i>
                    <p>Graphique d'évolution</p>
                    <p class="text-sm mt-1">(Disponible dans la version complète)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }
    
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endsection