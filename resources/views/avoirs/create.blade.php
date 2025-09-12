@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Créer un avoir
            </h1>
            <a href="{{ route('avoirs.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-full flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
        </div>

        {{-- Body --}}
        <div class="p-6">

            {{-- Errors --}}
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

            {{-- Tip --}}
            <div class="mb-6 flex items-start gap-3">
                <div class="bg-orange-100 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-gray-600">
                    Créez un avoir pour rembourser un client ou ajuster le montant d’une facture existante.
                    L’avoir ne peut pas dépasser le <strong>reste dû</strong> de la facture.
                </p>
            </div>

            <form action="{{ route('avoirs.store') }}" method="POST" id="avoirForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Facture associée --}}
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Facture associée *</label>
                        <div class="relative">
                            <select
                                id="facture_id"
                                name="facture_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                required
                            >
                                @php
                                    $preselectedId = old('facture_id') ?? ($facture->id ?? null);
                                @endphp

                                @foreach ($factures as $f)
                                    @php
                                        // Compute remaining on the server and ship it as data-* for instant UX
                                        $paye  = (float) ($f->paiements->sum('montant') ?? 0);
                                        $avoir = (float) ($f->avoirs->sum('montant') ?? 0);
                                        $reste = round((float)$f->total_ttc - $paye - $avoir, 2);
                                        $labelClient = $f->client->nom_assure ?? 'Prospect';
                                    @endphp
                                    <option
                                        value="{{ $f->id }}"
                                        data-rest="{{ number_format($reste, 2, '.', '') }}"
                                        data-client="{{ $labelClient }}"
                                        {{ (string)$preselectedId === (string)$f->id ? 'selected' : '' }}
                                    >
                                        Facture #{{ $f->id }} — {{ $labelClient }} — TTC {{ number_format($f->total_ttc,2) }} €
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Live recap --}}
                        <div id="factureMeta" class="mt-3 grid grid-cols-1 gap-2">
                            <div class="text-sm text-gray-700">
                                Client / Dossier : <span id="metaClient" class="font-medium">—</span>
                            </div>
                            <div class="text-sm">
                                Reste dû : <span id="metaReste" class="font-semibold text-orange-600">0.00 €</span>
                            </div>
                        </div>
                    </div>

                    {{-- Montant + notes --}}
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Montant de l’avoir (€) *</label>
                        <input
                            id="montant"
                            type="number"
                            name="montant"
                            step="0.01"
                            min="0.01"
                            value="{{ old('montant') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Entrez le montant"
                            required
                        >

                        <label class="block mt-6 mb-2 font-medium text-gray-700">Notes (optionnel)</label>
                        <textarea
                            name="notes"
                            rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Ajoutez une description ou des notes..."
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="border-t border-gray-200 pt-6 mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Les champs marqués d’un astérisque (*) sont obligatoires
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('avoirs.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-500 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer l’avoir
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Help tiles --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-2">Qu’est-ce qu’un avoir ?</h3>
            <p class="text-gray-600 text-sm">Un avoir atteste d’une dette du vendeur envers l’acheteur (retour, erreur de facturation, geste commercial…)</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-2">Utilisation</h3>
            <p class="text-gray-600 text-sm">Les avoirs réduisent le reste dû d’une facture, peuvent être remboursés, ou imputés sur d’autres factures.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-2">Bon à savoir</h3>
            <ul class="text-gray-600 text-sm space-y-1 list-disc pl-4">
                <li>Conservés 10 ans</li>
                <li>Apparaissent dans l’historique financier</li>
                <li>Export PDF/Excel</li>
            </ul>
        </div>
    </div>
</div>

{{-- JS: keep montant <= reste & show meta --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('facture_id');
    const montant = document.getElementById('montant');
    const metaClient = document.getElementById('metaClient');
    const metaReste  = document.getElementById('metaReste');

    function applyMeta() {
        const opt = select.selectedOptions[0];
        if (!opt) return;

        const rest  = parseFloat(opt.dataset.rest || '0') || 0;
        const client = opt.dataset.client || 'Prospect';

        // Show meta
        metaClient.textContent = client;
        metaReste.textContent  = rest.toFixed(2) + ' €';

        // Lock max
        montant.max = rest.toFixed(2);

        // If old value is empty or above max, adjust the field placeholder/value
        if (!montant.value) {
            montant.placeholder = 'Max ' + rest.toFixed(2);
        } else if (parseFloat(montant.value) > rest) {
            montant.value = rest.toFixed(2);
        }
    }

    select.addEventListener('change', applyMeta);
    applyMeta(); // initial
});
</script>
@endsection