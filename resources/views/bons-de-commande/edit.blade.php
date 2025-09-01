@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b-2 border-orange-500 pb-2">Modifier le bon de commande</h1>
    
    <form action="{{ route('bons-de-commande.update', $bon) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-lg" id="order-form">
        @csrf
        @method('PUT')
        
        <!-- Client & Supplier -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="mb-6">
                <label for="client_id" class="block text-gray-700 font-bold mb-3 flex items-center">
                    <i class="fas fa-folder-open text-orange-500 mr-2"></i>Dossier
                </label>
                <select name="client_id" id="client_id" class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    <option value="">Sélectionnez un client</option>
                    @foreach($clients as $clientOption)
                        <option value="{{ $clientOption->id }}" {{ $bon->client_id==$clientOption->id ? 'selected':'' }}>
                            {{ $clientOption->nom_assure }} {{ $clientOption->prenom }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="fournisseur_id" class="block text-gray-700 font-bold mb-3 flex items-center">
                    <i class="fas fa-truck text-orange-500 mr-2"></i>Fournisseur *
                </label>
                <select name="fournisseur_id" id="fournisseur_id" required class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    <option value="">Sélectionnez un fournisseur</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ $bon->fournisseur_id==$fournisseur->id ? 'selected':'' }}>
                            {{ $fournisseur->nom_societe }}
                        </option>
                    @endforeach
                </select>
                @error('fournisseur_id')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <!-- Title & Date -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="mb-6">
                <label for="titre" class="block text-gray-700 font-bold mb-3">Titre</label>
                <input type="text" name="titre" id="titre" value="{{ old('titre',$bon->titre) }}" class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                @error('titre')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="date_commande" class="block text-gray-700 font-bold mb-3">Date de commande *</label>
                <input type="date" name="date_commande" id="date_commande" value="{{ old('date_commande',\Carbon\Carbon::parse($bon->date_commande)->format('Y-m-d')) }}" required class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                @error('date_commande')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <!-- Product Lines -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-6 border-b border-orange-300 pb-2 flex items-center">
                <i class="fas fa-list-ul text-orange-500 mr-3"></i>Lignes de commande
            </h2>
            <div id="product-lines" class="space-y-5">
                @foreach($bon->lignes as $index => $ligne)
                <div class="product-line grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-5 bg-orange-50 rounded-xl border border-orange-100">
                    <div class="md:col-span-5">
                        <label class="block text-gray-700 mb-2">Produit</label>
                        <select name="lignes[{{ $index }}][produit_id]" class="product-select w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                            <option value="">Sélectionnez un produit</option>
                            <option value="autre" {{ $ligne->produit_id=='autre'?'selected':'' }}>Autre produit</option>
                            @foreach($produits as $produitOption)
                            <option value="{{ $produitOption->id }}" data-price="{{ $produitOption->prix_ht }}"
                                {{ $ligne->produit_id==$produitOption->id?'selected':'' }}>
                                {{ $produitOption->nom }} ({{ number_format($produitOption->prix_ht,2,',',' ') }} €)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-5 {{ $ligne->produit_id!='autre'?'hidden':'' }} product-name-container">
                        <label class="block text-gray-700 mb-2">Nom du produit</label>
                        <input type="text" name="lignes[{{ $index }}][nom_produit]" value="{{ $ligne->produit_id=='autre'?$ligne->produit->nom : '' }}" class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Quantité</label>
                        <input type="number" name="lignes[{{ $index }}][quantite]" min="1" value="{{ $ligne->quantite }}" class="quantity w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-gray-700 mb-2">Prix unitaire (€)</label>
                        <input type="number" name="lignes[{{ $index }}][prix]" step="0.01" min="0" value="{{ $ligne->prix_unitaire }}" class="price w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-gray-700 mb-2">Remise (%)</label>
                        <input type="number" name="lignes[{{ $index }}][remise]" step="0.01" min="0" max="100" value="{{ $ligne->remise }}" class="discount w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
                    </div>
                    <div class="md:col-span-3 flex items-center">
                        <input type="checkbox" name="lignes[{{ $index }}][ajouter_au_stock]" value="1" class="mr-3 h-5 w-5 text-orange-500" {{ $ligne->ajouter_au_stock?'checked':'' }}>
                        <label class="text-gray-700 font-medium">Ajouter au stock</label>
                    </div>
                    <div class="md:col-span-1">
                        <button type="button" class="remove-line bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition flex items-center justify-center">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="md:col-span-12 mt-4">
                        <div class="flex justify-between p-3 bg-orange-100 rounded-lg">
                            <span class="font-bold text-orange-700">Total ligne:</span>
                            <span class="line-total font-bold text-orange-700">0.00 €</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-line" class="mt-6 bg-orange-500 text-white px-5 py-3 rounded-lg hover:bg-orange-600 transition flex items-center">
                <i class="fas fa-plus-circle mr-3"></i> Ajouter une ligne
            </button>
        </div>
        
        <!-- Totals -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-orange-50 p-6 rounded-xl border border-orange-100">
            <div>
                <label class="block text-gray-700 mb-2">Total HT (€)</label>
                <input type="text" name="total_ht" id="total_ht" readonly value="{{ $bon->total_ht }}" class="w-full px-4 py-3 border rounded bg-gray-100 text-right">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">TVA (%)</label>
                <input type="number" name="tva" id="tva" value="{{ $bon->tva }}" min="0" max="100" step="0.01" class="w-full px-4 py-3 border rounded text-right">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Total TTC (€)</label>
                <input type="text" name="total_ttc" id="total_ttc" readonly value="{{ $bon->total_ttc }}" class="w-full px-4 py-3 border rounded bg-gray-100 text-right">
            </div>
        </div>

        <!-- File upload -->
        <div class="mb-8">
            <label for="fichier" class="block text-gray-700 font-bold mb-3">Fichier</label>
            <input type="file" name="fichier" id="fichier" class="w-full px-4 py-3 border rounded focus:ring-orange-300 focus:border-orange-300">
            @if($bon->fichier)
                <p class="text-sm text-gray-500 mt-1">
                    Fichier actuel:
                    <a href="{{ Storage::url($bon->fichier) }}" target="_blank" class="text-blue-500 underline">Voir le fichier</a>
                </p>
            @endif
        </div>

        <div class="text-right">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-bold transition">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function attachEvents(row) {
        // Product select: update price and show/hide custom name
        const productSelect = row.querySelector('.product-select');
        if (productSelect) {
            productSelect.addEventListener('change', function () {
                const selected = productSelect.options[productSelect.selectedIndex];
                const price = selected.getAttribute('data-price');
                const priceInput = row.querySelector('.price');
                if (productSelect.value && productSelect.value !== 'autre' && priceInput) {
                    priceInput.value = price ? parseFloat(price).toFixed(2) : '';
                    row.querySelector('.product-name-container')?.classList.add('hidden');
                } else if (productSelect.value === 'autre') {
                    row.querySelector('.product-name-container')?.classList.remove('hidden');
                    if (priceInput) priceInput.value = '';
                } else {
                    row.querySelector('.product-name-container')?.classList.add('hidden');
                    if (priceInput) priceInput.value = '';
                }
                calculateLineTotal(row);
                calculateOrderTotals();
            });
        }
        // Quantity/price/discount input listeners
        ['.quantity', '.price', '.discount'].forEach(selector => {
            const input = row.querySelector(selector);
            if (input) {
                input.addEventListener('input', function () {
                    calculateLineTotal(row);
                    calculateOrderTotals();
                });
            }
        });
        // Remove line button
        const removeBtn = row.querySelector('.remove-line');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (document.querySelectorAll('.product-line').length > 1) {
                    row.remove();
                    calculateOrderTotals();
                }
            });
        }
    }

    function calculateLineTotal(row) {
        const qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
        const price = parseFloat(row.querySelector('.price')?.value) || 0;
        const discount = parseFloat(row.querySelector('.discount')?.value) || 0;
        let total = qty * price;
        if (discount > 0) {
            total = total * (1 - discount / 100);
        }
        const totalSpan = row.querySelector('.line-total');
        if (totalSpan) totalSpan.textContent = total.toFixed(2) + ' €';
        row.setAttribute('data-line-total', total);
    }

    function calculateOrderTotals() {
        let totalHT = 0;
        document.querySelectorAll('.product-line').forEach(row => {
            const lineTotal = parseFloat(row.getAttribute('data-line-total')) || 0;
            totalHT += lineTotal;
        });
        document.getElementById('total_ht').value = totalHT.toFixed(2);
        const tva = parseFloat(document.getElementById('tva')?.value) || 0;
        const totalTTC = totalHT * (1 + tva / 100);
        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
    }

    // Initial attach for all rows and calculate
    document.querySelectorAll('.product-line').forEach(row => {
        attachEvents(row);
        calculateLineTotal(row);
    });
    calculateOrderTotals();

    // Add line logic
    const addLineBtn = document.getElementById('add-line');
    if (addLineBtn) {
        addLineBtn.addEventListener('click', function () {
            const linesContainer = document.getElementById('product-lines');
            const rows = linesContainer.querySelectorAll('.product-line');
            if (!rows.length) return;
            const firstRow = rows[0];
            const newRow = firstRow.cloneNode(true);
            // Update indexes in name attributes
            const newIndex = rows.length;
            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                }
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.type === 'number' || input.type === 'text') {
                    if (input.classList.contains('quantity')) input.value = 1;
                    else input.value = '';
                }
            });
            // Hide custom name, reset line total
            newRow.querySelector('.product-name-container')?.classList.add('hidden');
            const totalSpan = newRow.querySelector('.line-total');
            if (totalSpan) totalSpan.textContent = '0.00 €';
            newRow.setAttribute('data-line-total', 0);
            linesContainer.appendChild(newRow);
            attachEvents(newRow);
            calculateLineTotal(newRow);
            calculateOrderTotals();
        });
    }

    // TVA recalc
    const tvaInput = document.getElementById('tva');
    if (tvaInput) {
        tvaInput.addEventListener('input', function () {
            calculateOrderTotals();
        });
    }
});
</script>
@endsection