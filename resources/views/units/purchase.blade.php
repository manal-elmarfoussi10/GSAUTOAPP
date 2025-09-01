@extends('layout')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow mt-10">
    <h2 class="text-orange-600 font-semibold text-sm border-b mb-4 pb-2">
        Achat d'unité pour traiter vos dossiers GG auto
    </h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="mb-4 text-gray-700 font-medium">
        Il vous reste <span class="text-blue-600 font-bold">{{ auth()->user()->units ?? 0 }}</span> unité(s) pour traiter vos dossiers.
    </p>

    <form action="{{ route('units.purchase') }}" method="POST" id="unitForm" enctype="multipart/form-data">
        @csrf

        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
        <input type="number" name="quantity" id="quantity" min="1" class="w-full border px-3 py-2 rounded mb-4" required>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
            <select name="payment_method" id="payment_method" class="w-full border px-3 py-2 rounded" required>
                <option value="stripe">Carte bancaire (Stripe)</option>
                <option value="virement">Virement bancaire</option>
            </select>
        </div>

        <div id="virement_fields" class="hidden">
            <div class="mb-2 text-sm text-gray-600">
                Veuillez effectuer le virement à :
                <ul class="mt-1 text-gray-800">
                    <li><strong>Nom :</strong> GG Auto SARL</li>
                    <li><strong>IBAN :</strong> FR76 1234 5678 9101 1121 3141 516</li>
                    <li><strong>BIC :</strong> AGRIFRPPXXX</li>
                </ul>
            </div>

            <label class="block text-sm font-medium text-gray-700 mt-3">Téléversez le reçu du virement :</label>
            <input type="file" name="virement_proof" class="mt-1 mb-4">
        </div>

        <div class="mb-2">
            <label class="text-sm text-gray-600">TVA</label>
            <div id="tvaDisplay" class="text-gray-800">0 €</div>
        </div>

        <div class="mb-4">
            <label class="text-sm text-gray-600">Total TTC</label>
            <div id="totalDisplay" class="text-gray-800 font-bold">0 €</div>
        </div>

        <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600">
            Acheter
        </button>
    </form>
</div>

<script>
    const quantityInput = document.getElementById('quantity');
    const tvaDisplay = document.getElementById('tvaDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const paymentMethodSelect = document.getElementById('payment_method');
    const virementFields = document.getElementById('virement_fields');

    const updateTotals = () => {
        const qty = parseFloat(quantityInput.value || 0);
        const pricePerUnit = 10;
        const tvaRate = 0.2;

        const subtotal = qty * pricePerUnit;
        const tva = subtotal * tvaRate;
        const total = subtotal + tva;

        tvaDisplay.textContent = `${tva.toFixed(2)} €`;
        totalDisplay.textContent = `${total.toFixed(2)} €`;
    };

    quantityInput.addEventListener('input', updateTotals);
    paymentMethodSelect.addEventListener('change', () => {
        if (paymentMethodSelect.value === 'virement') {
            virementFields.classList.remove('hidden');
        } else {
            virementFields.classList.add('hidden');
        }
    });

    updateTotals(); // Initial display
</script>
@endsection