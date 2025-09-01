@extends('layouts.guest')

@section('title', 'Erreur serveur')

@section('content')
<div class="text-center py-20">
    <h1 class="text-6xl font-bold text-red-700 mb-4">500</h1>
    <h2 class="text-2xl text-gray-800 mb-4">Une erreur s'est produite</h2>
    <p class="text-gray-600 mb-6">Nous rencontrons un problème technique. L'équipe technique a été informée.</p>
    <a href="{{ route('dashboard') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
        Retour à l'accueil
    </a>
</div>
@endsection
