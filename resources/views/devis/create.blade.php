@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <style>
        input[type=number].no-spinners::-webkit-outer-spin-button,
        input[type=number].no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number].no-spinners {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Créer un nouveau devis</h1>
            <p class="text-gray-600 mt-1">Sélectionnez des produits, ajoutez-en, et choisissez le destinataire</p>
        </div>
        <a href="{{ route('devis.index') }}" class="flex items-center gap-2 text-orange-600 hover:text-orange-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <form action="{{ route('devis.store') }}" method="POST" id="devisForm" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="text-red-800 font-medium mb-2">Erreurs de validation :</h3>
            <ul class="list-disc pl-5 text-red-700">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Destinataire -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informations client</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Destinataire du devis</h2>

            <!-- Mode selector -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                <label class="border rounded-lg p-3 flex items-start gap-3 cursor-pointer has-[:checked]:border-orange-400">
                    <input type="radio" name="client_mode" value="existing" class="mt-1" {{ old('client_mode','existing')==='existing'?'checked':'' }}>
                    <div>
                        <div class="font-medium">Client existant</div>
                        <div class="text-sm text-gray-600">Rechercher dans vos clients</div>
                    </div>
                </label>



                <label class="border rounded-lg p-3 flex items-start gap-3 cursor-pointer has-[:checked]:border-orange-400">
                    <input type="radio" name="client_mode" value="prospect" class="mt-1" {{ old('client_mode')==='prospect'?'checked':'' }}>
                    <div>
                        <div class="font-medium">Prospect</div>
                        <div class="text-sm text-gray-600">Sans créer de fiche client</div>
                    </div>
                </label>
            </div>

            <!-- Existing client select -->
            <div id="section-existing" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Client</label>
                    <div class="relative">
                        <select id="client_id" name="client_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                            <option value="">-- Sélectionner un client --</option>
                            @foreach($clients as $client)
                              <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom_assure }} @if($client->plaque) — {{ $client->plaque }} @endif
                              </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Astuce : tapez pour filtrer (selon votre navigateur)</p>
                </div>
            </div>

            <!-- New client quick form -->
            <div id="section-new" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 hidden">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Nom complet *</label>
                    <input type="text" name="new_name" value="{{ old('new_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Ex: Jean Dupont">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Email</label>
                    <input type="email" name="new_email" value="{{ old('new_email') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="email@exemple.fr">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="new_phone" value="{{ old('new_phone') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="+33 ...">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Immatriculation</label>
                    <input type="text" name="new_immatriculation" value="{{ old('new_immatriculation') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="AA-123-BB">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium text-gray-700">Adresse</label>
                    <input type="text" name="new_address" value="{{ old('new_address') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Adresse complète (optionnel)">
                </div>
            </div>

            <!-- Prospect (no client) -->
            <div id="section-prospect" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 hidden">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Nom du prospect *</label>
                    <input type="text" name="prospect_name" value="{{ old('prospect_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Ex: Entreprise Martin">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Email</label>
                    <input type="email" name="prospect_email" value="{{ old('prospect_email') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="prospect_phone" value="{{ old('prospect_phone') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        <!-- Infos devis -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Dates</h2>

            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informations du devis</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Titre du devis</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Ex: Remplacement pare-brise">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Date du devis *</label>
                        <input type="date" name="date_devis" value="{{ old('date_devis', date('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Valide jusqu'au *</label>
                        <input type="date" name="date_validite" value="{{ old('date_validite', date('Y-m-d', strtotime('+30 days'))) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits / services -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Produits / Services</h2>
                <div class="flex gap-2">
                    <button type="button" id="addProductBtn" class="flex items-center gap-1 text-sm bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        Ajouter un produit
                    </button>
                    <button type="button" id="addFromCatalogBtn" class="flex items-center gap-1 text-sm bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        Catalogue
                    </button>
                </div>
            </div>

            <!-- Modal catalogue -->
            <div id="productCatalogModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[80vh] overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Sélectionner des produits</h3>
                        <button type="button" id="closeCatalogBtn" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-4 bg-gray-50 border-b">
                        <div class="flex gap-2">
                            <input type="text" id="catalogSearch" placeholder="Rechercher des produits..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2">
                            <button type="button" id="addSelectedBtn" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">Ajouter sélection</button>
                        </div>
                    </div>
                    <div class="overflow-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left"><input type="checkbox" id="selectAllProduits"></th>
                                    <th class="px-4 py-3 text-left">Produit</th>
                                    <th class="px-4 py-3 text-left">Description</th>
                                    <th class="px-4 py-3 text-left">Code</th>
                                    <th class="px-4 py-3 text-left">Prix HT</th>
                                    <th class="px-4 py-3 text-left">Taux TVA (%)</th>
                                </tr>
                            </thead>
                            <tbody id="catalogTable" class="divide-y divide-gray-200">
                                @foreach($produits as $produit)
                                <tr>
                                    <td class="px-4 py-3">
                                        <input type="checkbox" class="produit-checkbox" data-id="{{ $produit->id }}"
                                            data-name="{{ $produit->nom }}"
                                            data-description="{{ $produit->description }}"
                                            data-price="{{ $produit->prix_ht }}"
                                            data-tva="{{ $produit->taux_tva }}"> <!-- Changed to taux_tva -->
                                    </td>
                                    <td class="px-4 py-3 font-medium">{{ $produit->nom }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $produit->description }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $produit->code }}</td>
                                    <td class="px-4 py-3 font-medium">{{ number_format($produit->prix_ht, 2) }} €</td>
                                    <td class="px-4 py-3 font-medium">{{ number_format($produit->taux_tva, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tableau d’items -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium text-gray-600">Produit</th>
                            <th class="px-4 py-3 font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 font-medium text-gray-600">Quantité</th>
                            <th class="px-4 py-3 font-medium text-gray-600">Prix HT (€)</th>
                            <th class="px-4 py-3 font-medium text-gray-600">Taux TVA (%)</th>
                            <th class="px-4 py-3 font-medium text-gray-600">Remise (%)</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-right">Total HT</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable"></tbody>
                </table>
            </div>
        </div>

        <!-- Récap -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Récapitulatif</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-2">Total HT</h3>
                    <p class="text-2xl font-bold text-gray-800" id="total-ht">0.00 €</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-2">Total TVA</h3>
                    <p class="text-2xl font-bold text-gray-800" id="tva">0.00 €</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 bg-orange-50 border-orange-200">
                    <h3 class="font-medium text-orange-700 mb-2">Total TTC</h3>
                    <p class="text-2xl font-bold text-orange-700" id="total-ttc">0.00 €</p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('devis.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Annuler</a>
            <button type="submit" id="submitBtn" class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-lg transition-all shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Créer le devis
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Document loaded, initializing devis form');
        let rowCount = 0;

        // Add produit row
        function addProduitRow(produit = null) {
            try {
                console.log('Adding product row', produit);
                const rowId = rowCount++;
                const newRow = document.createElement('tr');
                newRow.className = 'border-b';

                // Set default values if produit is provided
                const defaultName = produit ? produit.name : '';
                const defaultDesc = produit ? produit.description : '';
                const defaultPrice = produit ? produit.price : '0';
                const defaultTva = produit ? produit.tva : '20.00';  // Default 20% VAT

                newRow.innerHTML = `
                    <td class="px-4 py-3">
                        <input type="text" name="items[${rowId}][produit]" value="${defaultName}" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Nom du produit" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="text" name="items[${rowId}][description]" value="${defaultDesc}" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Description">
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="items[${rowId}][quantite]" value="1" min="1" class="w-20 border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" required>
                    </td>
                    <td class="px-4 py-3">
                        <!-- FIX: Changed to prix_unitaire -->
                        <input type="number" step="0.01" name="items[${rowId}][prix_unitaire]" value="${defaultPrice}" min="0" class="w-24 border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" required>
                    </td>
                    <td class="px-4 py-3">
                        <!-- FIX: Changed to taux_tva -->
                        <input type="number" step="0.01" name="items[${rowId}][taux_tva]" value="${defaultTva}" min="0" class="w-20 border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" step="0.01" name="items[${rowId}][remise]" value="0" min="0" max="100" class="w-20 border border-gray-300 rounded px-3 py-2 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                    </td>
                    <td class="px-4 py-3 text-right font-medium">
                        <span class="total-cell">0.00 €</span>
                    </td>
                    <td class="px-4 py-3">
                        <button type="button" class="delete-row-btn text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </td>
                `;

                document.getElementById('itemsTable').appendChild(newRow);

                // Add event listeners to new inputs
                const inputs = newRow.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });

                // Add delete event
                newRow.querySelector('.delete-row-btn').addEventListener('click', function() {
                    console.log('Deleting row');
                    newRow.remove();
                    calculateTotals();
                });

                // Calculate totals for this new row
                calculateTotals();
            } catch (error) {
                console.error('Error adding product row:', error);
            }
        }

        // Add initial empty row
        addProduitRow();

        // Add produit button
        document.getElementById('addProductBtn').addEventListener('click', function() {
            addProduitRow();
        });

        // Add from catalog button
        document.getElementById('addFromCatalogBtn').addEventListener('click', function() {
            console.log('Opening product catalog');
            document.getElementById('productCatalogModal').classList.remove('hidden');
        });

        // Close catalog button
        document.getElementById('closeCatalogBtn').addEventListener('click', function() {
            console.log('Closing product catalog');
            document.getElementById('productCatalogModal').classList.add('hidden');
        });

        // Select all produits in catalog
        document.getElementById('selectAllProduits').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.produit-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });

        // Add selected produits from catalog
        document.getElementById('addSelectedBtn').addEventListener('click', function() {
            console.log('Adding selected products');
            const selectedProduits = document.querySelectorAll('.produit-checkbox:checked');

            if (selectedProduits.length === 0) {
                alert('Veuillez sélectionner au moins un produit');
                return;
            }

            selectedProduits.forEach(checkbox => {
                const produit = {
                    name: checkbox.getAttribute('data-name'),
                    description: checkbox.getAttribute('data-description'),
                    price: checkbox.getAttribute('data-price'),
                    tva: checkbox.getAttribute('data-tva')
                };
                addProduitRow(produit);
            });

            // Close modal and reset selection
            document.getElementById('productCatalogModal').classList.add('hidden');
            document.getElementById('selectAllProduits').checked = false;
            const checkboxes = document.querySelectorAll('.produit-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        // Catalog search functionality
        document.getElementById('catalogSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#catalogTable tr');

            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const code = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (name.includes(searchTerm) || description.includes(searchTerm) || code.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Calculate totals function
        function calculateTotals() {
            try {
                console.log('Calculating totals');
                let totalHT = 0;
                let totalTVA = 0;

                // Calculate each row total
                document.querySelectorAll('#itemsTable tr').forEach(row => {
                    const quantity = parseFloat(row.querySelector('input[name*="quantite"]').value) || 0;
                    const price = parseFloat(row.querySelector('input[name*="prix_unitaire"]').value) || 0;
                    const tauxTva = parseFloat(row.querySelector('input[name*="taux_tva"]').value) || 0;
                    const discount = parseFloat(row.querySelector('input[name*="remise"]').value) || 0;

                    // Calculate row total HT
                    let rowTotalHT = quantity * price;
                    if (discount > 0) {
                        rowTotalHT = rowTotalHT - (rowTotalHT * discount / 100);
                    }

                    // Calculate TVA for this row
                    let rowTVA = rowTotalHT * (tauxTva / 100);

                    // Update row total cell
                    row.querySelector('.total-cell').textContent = rowTotalHT.toFixed(2) + ' €';

                    totalHT += rowTotalHT;
                    totalTVA += rowTVA;
                });

                // Calculate TTC
                const totalTTC = totalHT + totalTVA;

                // Update summary
                document.getElementById('total-ht').textContent = totalHT.toFixed(2) + ' €';
                document.getElementById('tva').textContent = totalTVA.toFixed(2) + ' €';
                document.getElementById('total-ttc').textContent = totalTTC.toFixed(2) + ' €';
            } catch (error) {
                console.error('Error in calculateTotals:', error);
            }
        }

        // Add event listeners to existing inputs
        document.querySelectorAll('#itemsTable input').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });

        // Form submission handler
        document.getElementById('devisForm').addEventListener('submit', function(e) {
            console.log('Form submission initiated');

            // Validate at least one product row exists
            const productRows = document.querySelectorAll('#itemsTable tr');
            if (productRows.length === 0) {
                e.preventDefault();
                alert('Veuillez ajouter au moins un produit');
                return;
            }

            // Validate each product row
            let valid = true;
            productRows.forEach((row, index) => {
                const productName = row.querySelector('input[name*="produit"]').value;
                const quantity = row.querySelector('input[name*="quantite"]').value;
                const price = row.querySelector('input[name*="prix_unitaire"]').value;
                const tva = row.querySelector('input[name*="taux_tva"]').value;

                if (!productName || !quantity || !price || !tva) {
                    valid = false;
                    row.style.backgroundColor = '#fff0f0';
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs requis pour chaque produit');
            }
        });

        // Initial calculation
        calculateTotals();
        console.log('Devis form initialized successfully');
    });
</script>
@endsection
