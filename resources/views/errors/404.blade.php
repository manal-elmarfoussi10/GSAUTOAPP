@extends('layouts.guest')

@section('title', 'Page non trouvée')

@section('content')
<div class="text-center py-20">
    <h1 class="text-6xl font-bold text-red-600 mb-4">404</h1>
    <h2 class="text-2xl text-gray-800 mb-4">Oups ! Cette page n'existe pas.</h2>
    <p class="text-gray-600 mb-6">L'adresse saisie est introuvable ou a été supprimée.</p>
    <a href="{{ route('dashboard') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
        Retour à l'accueil
    </a>
</div>
@endsection
