@extends('layout')

@section('content')
@php
    // Works with paginator or plain collection
    $isPaginator = $produits instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $list        = $isPaginator ? $produits->getCollection() : collect($produits);

    $total       = $list->count();
    $actifs      = $list->where('actif', true)->count();
    $avgPrixHt   = (float) $list->avg('prix_ht');
    $categories  = $list->pluck('categorie')->filter()->unique()->count();

    // Helper for % when montant_tva exists but taux_tva may be null
    $tvaPct = function ($p) {
        if (!is_null($p->taux_tva)) return (float) $p->taux_tva;
        if (!empty($p->prix_ht) && !empty($p->montant_tva)) {
            return round(($p->montant_tva / $p->prix_ht) * 100, 2);
        }
        return null;
    };
@endphp

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with icon and action button -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-orange-50 p-3 rounded-xl mr-4">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Produits (Superadmin)</h1>
                    <p class="text-sm text-gray-600 mt-1">Catalogue global visible par toutes les sociétés</p>
                </div>
            </div>

            <a href="{{ route('superadmin.products.create') }}"
               class="flex items-center justify-center bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-5 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter un produit
            </a>
        </div>

        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total produits</p>
                        <p class="text-2xl font-bold mt-1">{{ $total }}</p>
                    </div>
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Produits actifs</p>
                        <p class="text-2xl font-bold mt-1">{{ $actifs }}</p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Prix moyen HT</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($avgPrixHt, 2) }} €</p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Catégories</p>
                        <p class="text-2xl font-bold mt-1">{{ $categories }}</p>
                    </div>
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products table (desktop) -->
        <div class="hidden md:block bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-orange-100 flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-lg font-medium text-gray-800">Liste des produits</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix (HT)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TVA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($produits as $produit)
                            <tr class="hover:bg-orange-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $produit->nom }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $produit->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded inline-block">
                                        {{ $produit->code }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format((float)$produit->prix_ht, 2) }} €
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ number_format((float)$produit->montant_tva, 2) }} €</div>
                                    @php $pct = $tvaPct($produit); @endphp
                                    <div class="text-xs text-gray-500">{{ !is_null($pct) ? '(' . $pct . '%)' : '(—)' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $produit->categorie ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if (is_null($produit->company_id))
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                            Global
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Société
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($produit->actif)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 inline-flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 12 12"><circle cx="6" cy="6" r="6"/></svg>
                                            Actif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 inline-flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 12 12"><circle cx="6" cy="6" r="6"/></svg>
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('superadmin.products.edit', $produit) }}"
                                           class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-2 rounded-lg transition-colors"
                                           title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('superadmin.products.destroy', $produit) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Confirmer suppression ?')"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors"
                                                    title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Aucun produit.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (desktop) -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Total: <span class="font-medium">{{ $isPaginator ? $produits->total() : $total }}</span> produits
                    </div>
                    <div>
                        @if($isPaginator)
                            {{ $produits->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile cards -->
        <div class="md:hidden space-y-4">
            @forelse($produits as $produit)
                <div class="bg-white rounded-xl shadow border p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900">{{ $produit->nom }}</h3>
                                @if ($produit->actif)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactif</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $produit->description }}</p>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <div class="text-gray-500">Code</div>
                                    <div class="font-mono bg-gray-100 px-2 py-1 rounded inline-block">{{ $produit->code }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Catégorie</div>
                                    <div class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 inline-block">
                                        {{ $produit->categorie ?? '—' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Prix HT</div>
                                    <div class="font-medium">{{ number_format((float)$produit->prix_ht, 2) }} €</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">TVA</div>
                                    @php $pct = $tvaPct($produit); @endphp
                                    <div>{{ number_format((float)$produit->montant_tva, 2) }} € {{ !is_null($pct) ? '(' . $pct . '%)' : '' }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Origine</div>
                                    @if (is_null($produit->company_id))
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">Global</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Société</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-end space-x-2">
                                <a href="{{ route('superadmin.products.edit', $produit) }}"
                                   class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-2 rounded-lg transition-colors"
                                   title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('superadmin.products.destroy', $produit) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Confirmer suppression ?')"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">Aucun produit.</div>
            @endforelse

            @if($isPaginator)
                <div class="mt-4">{{ $produits->links() }}</div>
            @endif
        </div>

    </div>
</div>

<style>
    .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .hover\:-translate-y-1:hover { transform: translateY(-1px); }
    .transition-colors { transition-property: background-color,border-color,color,fill,stroke; transition-timing-function: cubic-bezier(.4,0,.2,1); }
    .duration-300 { transition-duration: 300ms; }
</style>
@endsection