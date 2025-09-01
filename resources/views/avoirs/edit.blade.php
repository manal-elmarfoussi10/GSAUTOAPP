@extends('layout')

@section('content')
<div class="px-8 py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier un avoir</h1>
        <a href="{{ route('avoirs.index') }}" class="text-orange-500 hover:underline">&larr; Retour</a>
    </div>

    <form action="{{ route('avoirs.update', $avoir->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Montant</label>
                <input type="number" step="0.01" name="montant" value="{{ $avoir->montant }}" class="w-full border border-gray-300 rounded px-4 py-2" required>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Associer à une facture</label>
                <select name="facture_id" class="w-full border border-gray-300 rounded px-4 py-2">
                    <option value="">Aucune</option>
                    @foreach ($factures as $facture)
                        <option value="{{ $facture->id }}" {{ $avoir->facture_id == $facture->id ? 'selected' : '' }}>
                            Facture #{{ $facture->id }} - {{ $facture->client->nom_assure ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded shadow">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection