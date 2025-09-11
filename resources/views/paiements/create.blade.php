@extends('layout')

@section('content')
<div class="px-8 py-10">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Déclarer un paiement</h1>
    <a href="{{ route('factures.index') }}" class="text-orange-500 hover:underline">← Retour aux factures</a>
  </div>

  <form action="{{ route('paiements.store') }}" method="POST">
    @csrf
    <input type="hidden" name="facture_id" value="{{ $facture->id }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div>
        <label class="block mb-1 font-medium text-gray-700">Montant réglé</label>
        <input type="number" step="0.01" name="montant" class="w-full border-gray-300 rounded px-4 py-2" required>
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Mode de paiement</label>
        <input type="text" name="mode" class="w-full border-gray-300 rounded px-4 py-2">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Commentaire</label>
        <input type="text" name="commentaire" class="w-full border-gray-300 rounded px-4 py-2">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Date de paiement</label>
        <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" class="w-full border-gray-300 rounded px-4 py-2">
      </div>
    </div>

    <div class="text-right">
      <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded">
        Créer
      </button>
    </div>
  </form>
</div>
@endsection
