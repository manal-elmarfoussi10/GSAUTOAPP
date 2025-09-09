@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex items-start justify-between mb-8">
      <div class="flex items-center">
        <div class="bg-orange-50 p-3 rounded-xl mr-4">
          <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Modifier le produit</h1>
          <p class="text-sm text-gray-600 mt-1">Mettez à jour les informations du produit</p>
        </div>
      </div>

      <a href="{{ route('superadmin.products.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border text-gray-700 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19l-7-7 7-7" />
        </svg>
        Retour
      </a>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl border overflow-hidden">
      <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-orange-100 flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-800">
          {{ $product->nom }}
          <span class="text-gray-400 font-normal">— #{{ $product->id }}</span>
        </h2>

        @isset($allowDelete)
        @if($allowDelete)
          <form action="{{ route('superadmin.products.destroy', $product) }}" method="POST"
                onsubmit="return confirm('Supprimer définitivement ce produit ?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 border border-red-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Supprimer
            </button>
          </form>
        @endif
        @endisset
      </div>

      <form method="POST" action="{{ route('superadmin.products.update', $product) }}" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        @if(session('success'))
          <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-2">
            {{ session('success') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <div class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-0.5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Global toggle + optional company select -->
        @php
          $isGlobal = old('is_global', is_null($product->company_id)) ? true : false;
        @endphp
        <div class="grid grid-cols-1 gap-4">
          <label class="flex items-start gap-3 p-4 rounded-xl border bg-gray-50">
            <input type="checkbox" name="is_global" id="is_global" value="1" class="mt-1"
                   {{ $isGlobal ? 'checked' : '' }}>
            <span>
              <span class="font-medium text-gray-900">Produit global (toutes sociétés)</span>
              <span class="block text-sm text-gray-600">Visible pour toutes les sociétés. Décochez pour l’attribuer à une société précise.</span>
            </span>
          </label>

          {{-- Si vous souhaitez autoriser l’assignation à une société depuis le formulaire, décommentez :
          @if(isset($companies) && $companies->count())
            <div id="company_wrapper" class="{{ $isGlobal ? 'hidden' : '' }}">
              <label class="block text-sm font-medium text-gray-700 mb-1">Société</label>
              <select name="company_id" id="company_id" class="w-full border rounded-lg px-3 py-2">
                <option value="">— Sélectionner —</option>
                @foreach($companies as $c)
                  <option value="{{ $c->id }}"
                    @selected(old('company_id', $product->company_id)==$c->id)>{{ $c->name }}</option>
                @endforeach
              </select>
              <p class="text-xs text-gray-500 mt-1">Si vide et “global” coché, le produit restera global.</p>
            </div>
          @endif
          --}}
        </div>

        <!-- Basic fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $product->nom) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
            <input type="text" name="code" value="{{ old('code', $product->code) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea name="description" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                    placeholder="Détail du service / produit…">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Pricing -->
        @php
          // Si vous stockez aussi taux_tva en DB, remplacez old(...) ci-dessous par le champ du modèle
          $tauxTVAOld = old('taux_tva'); // fallback view-only
          $tauxTVAShow = !is_null($tauxTVAOld) ? $tauxTVAOld : ($product->taux_tva ?? null);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix HT (€) <span class="text-red-500">*</span></label>
            <input type="number" name="prix_ht" id="prix_ht" step="0.01" min="0" required
                   value="{{ old('prix_ht', $product->prix_ht) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">TVA (%)</label>
            <input type="number" name="taux_tva" id="taux_tva" step="0.01" min="0"
                   value="{{ $tauxTVAShow }}"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                   placeholder="Ex: 20">
            <p class="text-xs text-gray-500 mt-1">Optionnel — Si rempli, calcule automatiquement le montant TVA.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant TVA (€)</label>
            <input type="number" name="montant_tva" id="montant_tva" step="0.01" min="0"
                   value="{{ old('montant_tva', $product->montant_tva) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
            <p class="text-xs text-gray-500 mt-1">Peut être ajusté manuellement.</p>
          </div>
        </div>

        <!-- Category + active -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
            <input type="text" name="categorie" value="{{ old('categorie', $product->categorie) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                   placeholder="Ex: Pare-brise, Main d'œuvre, …">
          </div>
          <div class="flex items-center gap-3">
            <input id="actif" name="actif" type="checkbox" value="1" class="h-4 w-4"
                   {{ old('actif', (int)$product->actif) ? 'checked' : '' }}>
            <label for="actif" class="text-sm font-medium text-gray-700">Actif</label>
          </div>
        </div>

        <!-- Actions -->
        <div class="pt-2 flex items-center justify-end gap-3">
          <a href="{{ route('superadmin.products.index') }}"
             class="px-4 py-2 rounded-lg bg-white border text-gray-700 hover:bg-gray-50">Annuler</a>
          <button type="submit"
                  class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            Enregistrer
          </button>
        </div>
      </form>
    </div>

    <!-- Meta -->
    <div class="mt-6 text-xs text-gray-500">
      <p>Créé le {{ $product->created_at?->format('d/m/Y H:i') }} • Modifié le {{ $product->updated_at?->format('d/m/Y H:i') }}</p>
    </div>

  </div>
</div>

<script>
  // Toggle société wrapper if you enable it above
  document.getElementById('is_global')?.addEventListener('change', function () {
    const box = document.getElementById('company_wrapper');
    if (!box) return;
    this.checked ? box.classList.add('hidden') : box.classList.remove('hidden');
  });

  // TVA auto-calc
  const prixHtEl = document.getElementById('prix_ht');
  const tauxTvaEl = document.getElementById('taux_tva');
  const montantTvaEl = document.getElementById('montant_tva');

  function recalcTVA(){
    const ht = parseFloat(prixHtEl?.value || '0');
    const t  = parseFloat(tauxTvaEl?.value || '0');
    if (!isNaN(ht) && !isNaN(t)) {
      montantTvaEl.value = (ht * t / 100).toFixed(2);
    }
  }
  prixHtEl?.addEventListener('input', recalcTVA);
  tauxTvaEl?.addEventListener('input', recalcTVA);
</script>
@endsection