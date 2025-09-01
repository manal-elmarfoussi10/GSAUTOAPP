@extends('layouts.guest')

@section('title', 'Accès refusé')

@section('content')
<div class="text-center py-20">
    <h1 class="text-6xl font-bold text-yellow-500 mb-4">403</h1>
    <h2 class="text-2xl text-gray-800 mb-4">Accès non autorisé</h2>
    <p class="text-gray-600 mb-6">Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
    <a href="{{ route('dashboard') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
        Retour au tableau de bord
    </a>
</div>
@endsection
