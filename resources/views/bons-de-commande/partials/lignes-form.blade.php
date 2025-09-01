<div class="mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-2">Lignes de commande</h3>

    <div class="w-full border border-gray-200 rounded overflow-hidden text-sm">
        <!-- Table Header -->
        <div class="grid grid-cols-8 bg-gray-100 text-left text-gray-700 px-3 py-2 font-medium">
            <div>#</div>
            <div>Ajouter au stock</div>
            <div>Produit</div>
            <div>Quantité</div>
            <div>Prix HT</div>
            <div>Remise (%)</div>
            <div>Total HT</div>
            <div class="text-center">Action</div>
        </div>

        <!-- Table Body -->
        <div id="ligne-produits">
            <div class="grid grid-cols-8 gap-2 px-3 py-2 border-t border-gray-200 ligne-row">
                <div>1</div>

                <div>
                    <select name="lignes[0][ajouter_au_stock]" class="w-full border rounded px-2 py-1">
                        <option value="0">NON</option>
                        <option value="1">OUI</option>
                    </select>
                </div>

                <div>
                    <select name="lignes[0][produit_id]" class="w-full border rounded px-2 py-1 produit-select">
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                        @endforeach
                        <option value="autre">Autre</option>
                    </select>
                    <input type="text" name="lignes[0][nom_produit]" class="w-full border rounded px-2 py-1 mt-2 nom-produit-input hidden" placeholder="Nom du nouveau produit">
                </div>

                <div>
                    <input type="number" name="lignes[0][quantite]" class="w-full border rounded px-2 py-1" value="1" min="1">
                </div>

                <div>
                    <input type="number" name="lignes[0][prix]" class="w-full border rounded px-2 py-1" step="0.01" value="0">
                </div>

                <div>
                    <input type="number" name="lignes[0][remise]" class="w-full border rounded px-2 py-1" step="0.01" value="0">
                </div>

                <div>
                    <input type="number" name="lignes[0][total_ht]" class="w-full border rounded px-2 py-1 bg-gray-100" value="0" readonly>
                </div>

                <div class="text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 font-semibold">×</button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-3 mt-4">
        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">+ Ajouter une ligne</button>
        <button type="button" class="bg-gray-100 hover:bg-gray-200 text-sm px-4 py-2 rounded">- Supprimer une ligne</button>
    </div>
</div>