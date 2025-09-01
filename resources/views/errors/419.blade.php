@extends('layouts.guest')

@section('title', 'Session expirée')

@section('content')
<div class="text-center py-20">
    <h1 class="text-6xl font-bold text-orange-500 mb-4">419</h1>
    <h2 class="text-2xl text-gray-800 mb-4">Session expirée</h2>
    <p class="text-gray-600 mb-6">Votre session a expiré pour des raisons de sécurité. Veuillez vous reconnecter.</p>
    <a href="{{ route('login') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
        Se reconnecter
    </a>
</div>
@endsection
