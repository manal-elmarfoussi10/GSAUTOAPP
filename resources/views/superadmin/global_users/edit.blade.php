@extends('layout')
@section('title','Modifier un utilisateur global')

@section('content')
<div class="px-6 py-6">
  <div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
      <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
        <i data-lucide="user-cog" class="w-7 h-7 text-[#FF4B00]"></i>
        
        <span class="text-[#FF4B00]">{{ $user->name }}</span>
      </h1>
      <p class="text-gray-500 mt-2">Mettez à jour les informations de l’utilisateur global.</p>
    </div>

    <!-- Form -->
    <form action="{{ route('superadmin.global-users.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-sm border p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      @csrf 
      @method('PUT')

      <!-- Prénom -->
      <div class="flex flex-col">
        <label for="first_name" class="mb-2 font-medium text-gray-700">Prénom *</label>
        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
        @error('first_name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <!-- Nom -->
      <div class="flex flex-col">
        <label for="last_name" class="mb-2 font-medium text-gray-700">Nom *</label>
        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
        @error('last_name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <!-- Email -->
      <div class="flex flex-col">
        <label for="email" class="mb-2 font-medium text-gray-700">Email *</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
        @error('email')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <!-- Role -->
      <div class="flex flex-col">
        <label for="role" class="mb-2 font-medium text-gray-700">Rôle *</label>
        <select id="role" name="role" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
          @foreach($roles as $value => $label)
            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('role')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <!-- Password -->
      <div class="flex flex-col">
        <label for="password" class="mb-2 font-medium text-gray-700">Mot de passe</label>
        <input id="password" type="password" name="password" placeholder="Laisser vide pour ne pas changer" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
        @error('password')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
      </div>

      <!-- Confirmation -->
      <div class="flex flex-col">
        <label for="password_confirmation" class="mb-2 font-medium text-gray-700">Confirmation</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
      </div>

      <!-- Active -->
      <div class="flex items-center md:col-span-3">
        <label class="inline-flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]">
          <span class="text-gray-700 font-medium select-none">Compte actif</span>
        </label>
      </div>

      <!-- Submit -->
      <div class="md:col-span-3 flex justify-end">
        <button type="submit" class="bg-[#FF6B00] hover:bg-[#D45A00] text-white font-semibold rounded-full px-6 py-3 shadow-md flex items-center gap-2 transition-colors duration-200">
          <i data-lucide="save" class="w-5 h-5"></i>
          <span>Enregistrer</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection