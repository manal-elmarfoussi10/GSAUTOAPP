@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Créer une nouvelle dépense</h1>
            <p class="text-gray-600 mt-1">Remplissez le formulaire pour enregistrer une nouvelle dépense</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date de la dépense *</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500">
                        @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="paid_status" class="block text-sm font-medium text-gray-700 mb-1">Statut de paiement *</label>
                        <select name="paid_status" id="paid_status" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                            <option value="">Sélectionner un statut</option>
                            <option value="paid" {{ old('paid_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="pending" {{ old('paid_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="unpaid" {{ old('paid_status') == 'unpaid' ? 'selected' : '' }}>Non payé</option>
                        </select>
                        @error('paid_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client associé *</label>
                        <select name="client_id" id="client_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                            <option value="">Sélectionner un client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->prenom }} {{ $client->nom_assure }} (#{{ $client->reference_client }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fournisseur -->
                    <div>
                        <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur *</label>
                        <select name="fournisseur_id" id="fournisseur_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                            <option value="">Sélectionner un fournisseur</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->nom_societe }}
                                </option>
                            @endforeach
                        </select>
                        @error('fournisseur_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Montant HT -->
                    <div>
                        <label for="ht_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant HT (€) *</label>
                        <div class="relative">
                            <input type="number" name="ht_amount" id="ht_amount" step="0.01" min="0"
                                   value="{{ old('ht_amount') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-2.5 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="0,00">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                        </div>
                        @error('ht_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Taux TVA -->
                    <div>
                        <label for="tva_rate" class="block text-sm font-medium text-gray-700 mb-1">TVA (%)</label>
                        <select id="tva_rate"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500">
                            <option value="20" selected>20% (Standard)</option>
                            <option value="10">10%</option>
                            <option value="5.5">5.5%</option>
                            <option value="0">0% (Exonéré)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Utilisé pour calculer automatiquement le TTC.</p>
                    </div>

                    <!-- Montant TTC -->
                    <div>
                        <label for="ttc_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant TTC (€) *</label>
                        <div class="relative">
                            <input type="number" name="ttc_amount" id="ttc_amount" step="0.01" min="0"
                                   value="{{ old('ttc_amount') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-2.5 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="0,00">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                        </div>
                        @error('ttc_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Détails de la dépense...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                <div></div>
                <div class="flex space-x-3">
                    <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:opacity-90 transition-opacity shadow-md">
                        Enregistrer la dépense
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Dépenses récentes -->
    @if(isset($recentExpenses) && $recentExpenses->count())
    <div class="mt-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Dépenses récentes</h3>
            <a href="{{ route('expenses.index') }}" class="text-sm text-orange-500 hover:underline">Voir tout</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentExpenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $expense->date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $expense->client->prenom }} {{ $expense->client->nom_assure }}</div>
                                <div class="text-xs text-gray-500">#{{ $expense->client->reference_client }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">{{ $expense->fournisseur->nom_societe }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($expense->paid_status == 'paid')
                                  <span class="inline-flex items-center gap-1 bg-teal-100 px-3 py-1 rounded-full text-xs font-medium text-teal-800">Payé</span>
                                @elseif($expense->paid_status == 'pending')
                                  <span class="inline-flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full text-xs font-medium text-yellow-800">En attente</span>
                                @else
                                  <span class="inline-flex items-center gap-1 bg-red-100 px-3 py-1 rounded-full text-xs font-medium text-red-800">Non payé</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ number_format($expense->ttc_amount, 2, ',', ' ') }}€
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Auto-calc HT <-> TTC with TVA --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
      // CONFIG: selectors that exist on your page
      const SELECTORS = {
        lines:        '.js-line',             // each line wrapper (add this class on the line container)
        qty:          '.js-qty',              // quantity input inside a line
        price:        '.js-price',            // unit price input inside a line
        remise:       '.js-remise',           // discount % input inside a line
        lineTotalBox: '.js-line-total',       // element (input or span) showing line total
        totalHT:      '#total_ht',            // input for Total HT (€)
        tvaEuro:      '#tva_value',           // input for TVA (€)
        totalTTC:     '#total_ttc',           // input for Total TTC (€)
        tvaRate:      '#tva_rate'             // select for taux TVA (%)
      };
    
      // Fallbacks if you only have one line and no classes yet:
      const qtyFallback    = document.querySelector('#quantity, [name="quantity"]');
      const priceFallback  = document.querySelector('#unit_price, [name="unit_price"]');
      const remiseFallback = document.querySelector('#remise, [name="remise"]');
      const lineTotalBar   = document.querySelector('#line_total, .line-total');
    
      // Grab totals
      const totalHTInput  = document.querySelector(SELECTORS.totalHT);
      const tvaEuroInput  = document.querySelector(SELECTORS.tvaEuro);
      const totalTTCInput = document.querySelector(SELECTORS.totalTTC);
      const tvaRateSelect = document.querySelector(SELECTORS.tvaRate);
    
      // Helpers
      const toNum  = v => parseFloat((v ?? '').toString().replace(',', '.')) || 0;
      const fix2   = n => (Math.round(n * 100) / 100).toFixed(2);
    
      function computeLineTotal(qtyEl, priceEl, remiseEl) {
        const q   = toNum(qtyEl?.value);
        const pu  = toNum(priceEl?.value);
        const rem = toNum(remiseEl?.value);
        return q * pu * (1 - rem / 100);
      }
    
      function recalcAll() {
        const tvaRate = toNum(tvaRateSelect?.value);
    
        // If you have multiple lines with classes
        const lineNodes = document.querySelectorAll(SELECTORS.lines);
        let totalHT = 0;
    
        if (lineNodes.length > 0) {
          lineNodes.forEach(line => {
            const qty    = line.querySelector(SELECTORS.qty);
            const price  = line.querySelector(SELECTORS.price);
            const remise = line.querySelector(SELECTORS.remise);
            const box    = line.querySelector(SELECTORS.lineTotalBox);
            const lt     = computeLineTotal(qty, price, remise);
            totalHT     += lt;
            if (box) {
              if ('value' in box) box.value = fix2(lt);
              else box.textContent = fix2(lt);
            }
          });
        } else {
          // Single-line fallback (your current screenshot likely is this)
          const lt = computeLineTotal(qtyFallback, priceFallback, remiseFallback);
          totalHT  = lt;
          if (lineTotalBar) {
            if ('value' in lineTotalBar) lineTotalBar.value = fix2(lt);
            else lineTotalBar.textContent = fix2(lt);
          }
        }
    
        const tvaEuro = totalHT * (tvaRate / 100);
        const totalTTC = totalHT + tvaEuro;
    
        if (totalHTInput)  totalHTInput.value  = fix2(totalHT);
        if (tvaEuroInput)  tvaEuroInput.value  = fix2(tvaEuro);
        if (totalTTCInput) totalTTCInput.value = fix2(totalTTC);
      }
    
      // Hook inputs
      function bind(el) {
        if (!el) return;
        el.addEventListener('input', recalcAll);
        el.addEventListener('change', recalcAll);
      }
    
      // Bind multi-line fields if present
      document.querySelectorAll([
        SELECTORS.lines + ' ' + SELECTORS.qty,
        SELECTORS.lines + ' ' + SELECTORS.price,
        SELECTORS.lines + ' ' + SELECTORS.remise
      ].join(',')).forEach(bind);
    
      // Bind single-line fallbacks
      [qtyFallback, priceFallback, remiseFallback].forEach(bind);
    
      // Bind TVA rate
      bind(tvaRateSelect);
    
      // Initial calc
      recalcAll();
    });
    </script>
@endsection