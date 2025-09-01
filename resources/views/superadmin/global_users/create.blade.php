@extends('layout')
@section('title','Créer un utilisateur global')

@section('content')
<div class="px-6 py-6 max-w-5xl mx-auto space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">
      Créer un <span class="text-[#FF4B00]">utilisateur global</span>
    </h1>
    <p class="text-gray-500 text-sm">Ajoutez un nouvel utilisateur global au système.</p>
  </div>

  <!-- Form -->
  <form action="{{ route('superadmin.global-users.store') }}" method="POST"
        class="bg-white rounded-2xl shadow-sm border p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    @csrf

    <!-- Prénom -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Prénom *</label>
      <input name="first_name" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" 
             value="{{ old('first_name') }}" required>
      @error('first_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Nom -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Nom *</label>
      <input name="last_name" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" 
             value="{{ old('last_name') }}" required>
      @error('last_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Email -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Email *</label>
      <input type="email" name="email" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" 
             value="{{ old('email') }}" required>
      @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Role -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Rôle *</label>
      <select name="role" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" required>
        @foreach($roles as $value => $label)
          <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
        @endforeach
      </select>
      @error('role')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Password -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Mot de passe *</label>
      <input type="password" name="password" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" required>
      @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Confirm Password -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Confirmer *</label>
      <input type="password" name="password_confirmation" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]" required>
    </div>

    <!-- Active -->
    <div class="md:col-span-2">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" checked class="rounded text-[#FF4B00] focus:ring-[#FF4B00]">
        <span class="text-sm text-gray-700">Compte actif</span>
      </label>
    </div>

    <!-- Submit -->
    <div class="md:col-span-2 flex justify-end">
      <button class="px-6 py-3 bg-[#FF4B00] text-white font-semibold rounded-full shadow hover:bg-[#e04300] transition">
        <i class="fas fa-save mr-2"></i> Enregistrer
      </button>
    </div>
  </form>
</div>
@endsection