@extends('layout')
@section('title','Ajouter un utilisateur')

@section('content')
<div class="px-6 py-4">
  <h1 class="text-xl font-semibold mb-4">
    <span class="text-gray-800">Ajouter un utilisateur —</span>
    <span class="text-[#FF4B00]">{{ $company->name }}</span>
  </h1>

  <form action="{{ route('superadmin.companies.users.store', $company) }}" method="POST"
        class="bg-white rounded-2xl border p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf
    <div>
      <label class="block text-xs text-gray-500 mb-1">Prénom *</label>
      <input name="first_name" class="w-full border rounded-lg p-2" value="{{ old('first_name') }}">
      @error('first_name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Nom *</label>
      <input name="last_name" class="w-full border rounded-lg p-2" value="{{ old('last_name') }}">
      @error('last_name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Email *</label>
      <input type="email" name="email" class="w-full border rounded-lg p-2" value="{{ old('email') }}">
      @error('email')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Rôle *</label>
      <select name="role" class="w-full border rounded-lg p-2">
        @foreach($roles as $value => $label)
          <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
      </select>
      @error('role')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Mot de passe *</label>
      <input type="password" name="password" class="w-full border rounded-lg p-2">
      @error('password')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Confirmer *</label>
      <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2">
    </div>
    <label class="inline-flex items-center gap-2 mt-2">
      <input type="checkbox" name="is_active" value="1" checked>
      <span class="text-sm">Activer</span>
    </label>

    <div class="md:col-span-2 flex justify-end mt-2">
      <button class="px-4 py-2 bg-[#FF4B00] text-white rounded-xl">Enregistrer</button>
    </div>
  </form>
</div>
@endsection