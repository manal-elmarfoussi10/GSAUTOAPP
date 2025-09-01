@extends('layout')

@section('content')
<div class="px-8 py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier la facture</h1>
        <a href="{{ route('factures.index') }}" class="text-orange-500 hover:underline">&larr; Retour</a>
    </div>

    <form action="{{ route('factures.update', $facture->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Client</label>
                <select name="client_id" class="w-full border border-gray-300 rounded px-4 py-2">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $facture->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->nom_assure }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Date de la facture</label>
                <input type="date" name="date_facture" value="{{ $facture->date_facture }}" class="w-full border border-gray-300 rounded px-4 py-2">
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Produits</h2>

        <table class="w-full text-sm border rounded shadow-sm mb-6">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Produit</th>
                    <th class="px-4 py-2">Quantité</th>
                    <th class="px-4 py-2">Prix unitaire</th>
                    <th class="px-4 py-2">Remise (%)</th>
                    <th class="px-4 py-2">Total HT</th>
                </tr>
            </thead>
            <tbody id="itemsTable">
                @foreach($facture->items as $index => $item)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        <!-- Changed from text input to product dropdown -->
                        <select name="items[{{ $index }}][produit]" class="w-full border border-gray-300 rounded px-2 py-1">
                            <option value="">Sélectionner un produit</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->nom }}" 
                                    {{ $item->produit == $produit->nom ? 'selected' : '' }}>
                                    {{ $produit->nom }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" name="items[{{ $index }}][quantite]" value="{{ $item->quantite }}" class="w-full border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01" name="items[{{ $index }}][prix_unitaire]" value="{{ $item->prix_unitaire }}" class="w-full border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" step="0.01" name="items[{{ $index }}][remise]" value="{{ $item->remise }}" class="w-full border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-4 py-2 text-right">
                        {{ number_format($item->total_ht, 2) }} €
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded shadow">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection