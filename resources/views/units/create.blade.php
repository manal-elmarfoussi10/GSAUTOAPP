@extends('layout')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow mt-10">
    <h2 class="text-orange-600 font-semibold text-sm border-b mb-4 pb-2">
        Achat d'unité pour traiter vos dossiers GG auto
    </h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <p class="mb-4 text-gray-700 font-medium">
        Il vous reste <span class="text-blue-600 font-bold">{{ auth()->user()->units ?? 0 }}</span> unité(s) pour traiter vos dossiers.
    </p>

    <form action="{{ route('units.purchase') }}" method="POST" id="unitForm">
        @csrf

        <label class="block text-sm font-medium text-gray-700 mb-1">Quantite</label>
        <input type="number" name="quantity" id="quantity" class="w-full border px-3 py-2 rounded mb-4" min="1" required>

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

    quantityInput.addEventListener('input', () => {
        const qty = parseFloat(quantityInput.value || 0);
        const pricePerUnit = 10;
        const tvaRate = 0.2;

        const subtotal = qty * pricePerUnit;
        const tva = subtotal * tvaRate;
        const total = subtotal + tva;

        tvaDisplay.textContent = `${tva.toFixed(2)} €`;
        totalDisplay.textContent = `${total.toFixed(2)} €`;
    });
</script>
@endsection