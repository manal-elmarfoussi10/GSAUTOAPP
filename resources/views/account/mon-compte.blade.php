@extends('layout')

@section('title', 'Mon compte')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl px-8 py-10 mt-12 border border-gray-200">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 border-l-4 pl-4 border-[#FF4B00]">Mon compte</h1>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulaire mise à jour nom/email/photo -->
    <form method="POST" action="{{ route('mon-compte.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Photo de profil -->
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#FF4B00] shadow">
                <img src="{{ $user->photo ? asset('/storage/app/public/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=FF4B00&color=fff' }}"
                     alt="Photo de profil" class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col space-y-2">
                <label class="text-sm font-medium text-gray-700">Changer la photo</label>
                <input type="file" name="photo" accept="image/*"
                       class="block w-full text-sm text-gray-600 border border-gray-300 rounded px-3 py-1 bg-white">
                @error('photo')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Nom -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]" required>
            @error('name')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]" required>
            @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
                class="bg-[#FF4B00] hover:bg-[#e74400] text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            Enregistrer les modifications
        </button>
    </form>

    <!-- Bouton suppression photo  -->
    @if ($user->photo)
        <form method="POST" action="{{ route('mon-compte.photo.delete') }}" class="mt-4"
              onsubmit="return confirm('Supprimer la photo de profil ?');">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium underline">
                Supprimer la photo
            </button>
        </form>
    @endif

    <!-- Mot de passe -->
    <hr class="my-10 border-t-2 border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Changer le mot de passe</h2>

    @if (session('success_password'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded">
            {{ session('success_password') }}
        </div>
    @endif

    <form method="POST" action="{{ route('mon-compte.password') }}" class="space-y-6">
        @csrf

        @php
            $password_fields = [
                ['label' => 'Mot de passe actuel', 'name' => 'current_password'],
                ['label' => 'Nouveau mot de passe', 'name' => 'new_password'],
                ['label' => 'Confirmer le mot de passe', 'name' => 'new_password_confirmation']
            ];
        @endphp

        @foreach ($password_fields as $field)
        <div class="relative">
            <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
                {{ $field['label'] }}
            </label>
            <input type="password" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]"
                   required>
            <button type="button"
                    class="absolute right-3 top-[35px] text-gray-500 hover:text-[#FF4B00]"
                    onclick="togglePassword('{{ $field['name'] }}')">
                <i class="fas fa-eye"></i>
            </button>
            @error($field['name'])
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>
        @endforeach

        <button type="submit"
                class="bg-[#FF4B00] hover:bg-[#e74400] text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            Mettre à jour le mot de passe
        </button>
    </form>


    <!-- Suppression de compte -->
    <hr class="my-10 border-t-2 border-gray-100">
    <h2 class="text-xl font-bold text-red-600 mb-2">Supprimer mon compte</h2>
    <p class="text-sm text-gray-600 mb-4">Cette action est irréversible. Toutes vos données seront supprimées.</p>

    <form method="POST" action="{{ route('mon-compte.delete') }}"
          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            Supprimer mon compte
        </button>
    </form>
</div>

<!-- JS : afficher/masquer les mots de passe -->
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
