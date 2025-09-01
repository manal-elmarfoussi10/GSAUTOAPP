@extends('layouts.guest')

@section('title', 'Créer un compte')

@section('content')
<div class="bg-white rounded-2xl shadow-lg px-8 py-10">
    <!-- Logo du projet en haut (optionnel) -->
    <div class="flex justify-center mb-8">
        <img src="{{ asset('images/GA GESTION LOGO.png') }}" alt="Logo Project France" class="h-16 w-auto" />
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Créer un compte</h2>
    <p class="text-gray-500 text-center mb-8">Inscrivez-vous pour nous rejoindre</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Nom -->
        <div class="mb-5">
            <label for="name" class="block mb-1 font-medium text-gray-700">Nom complet</label>
            <input id="name" type="text" name="name" required autofocus
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                placeholder="Votre nom complet" value="{{ old('name') }}" />
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block mb-1 font-medium text-gray-700">Adresse e-mail</label>
            <input id="email" type="email" name="email" required
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                placeholder="exemple@domaine.com" value="{{ old('email') }}" />
        </div>

        <!-- Mot de passe -->
        <div class="mb-5">
            <label for="password" class="block mb-1 font-medium text-gray-700">Mot de passe</label>
            <input id="password" type="password" name="password" required
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                placeholder="Mot de passe" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="mb-5">
            <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                placeholder="Confirmez le mot de passe" />
        </div>

        <button type="submit" class="w-full bg-black text-white py-2 rounded-lg text-lg font-bold hover:bg-green-700 transition">
            S'inscrire
        </button>
    </form>

    <div class="mt-8 text-center">
        <span class="text-gray-600 text-sm">Déjà inscrit ?</span>
        <a href="{{ route('login') }}" class="ml-1 text-blue-600 font-semibold hover:underline">
            Se connecter
        </a>
    </div>
</div>
@endsection