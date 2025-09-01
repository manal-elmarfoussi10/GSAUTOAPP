@foreach ($lignes as $index => $ligne)
    <div class="product-line mb-4">
        <label class="block text-gray-700 font-medium mb-1">Produit</label>
        <select name="lignes[{{ $index }}][produit_id]" class="product-select border px-2 py-1 w-full rounded">
            <option value="">Sélectionnez un produit</option>
            <option value="autre" {{ $ligne->produit_id === 'autre' ? 'selected' : '' }}>Autre produit</option>
            @foreach($produits as $produit)
                <option value="{{ $produit->id }}" data-price="{{ $produit->prix }}"
                    {{ $ligne->produit_id == $produit->id ? 'selected' : '' }}>
                    {{ $produit->nom }} ({{ number_format($produit->prix,2,',',' ') }} €)
                </option>
            @endforeach
        </select>

        <div class="product-name-container mt-2 {{ $ligne->produit_id === 'autre' ? '' : 'hidden' }}">
            <label class="block text-gray-700 font-medium mb-1">Nom du produit</label>
            <input type="text" name="lignes[{{ $index }}][nom_produit]" value="{{ $ligne->nom_produit }}" class="border px-2 py-1 w-full rounded" />
        </div>

        <label class="block text-gray-700 font-medium mt-2 mb-1">Quantité</label>
        <input type="number" name="lignes[{{ $index }}][quantite]" value="{{ $ligne->quantite }}" class="quantity border px-2 py-1 w-full rounded" />

        <label class="block text-gray-700 font-medium mt-2 mb-1">Prix unitaire (€)</label>
        <input type="number" name="lignes[{{ $index }}][prix]" step="0.01" value="{{ $ligne->prix_unitaire }}" class="price border px-2 py-1 w-full rounded" />
    </div>
@endforeach