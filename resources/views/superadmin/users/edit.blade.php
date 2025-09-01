@extends('layout')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="px-6 py-4 space-y-6">
    <h1 class="text-xl font-semibold">
        <span class="text-gray-800">Modifier</span>
        <span class="text-[#FF4B00]">{{ $user->name }}</span>
    </h1>

    <form action="{{ route('superadmin.companies.users.update', [$company->id, $user->id]) }}" 
          method="POST"
          class="bg-white rounded-2xl border p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <!-- First Name -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Prénom *</label>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                   class="w-full border rounded-lg p-2" required>
            @error('first_name') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>

        <!-- Last Name -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Nom *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                   class="w-full border rounded-lg p-2" required>
            @error('last_name') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded-lg p-2" required>
            @error('email') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>

        <!-- Role -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Rôle *</label>
            <select name="role" class="w-full border rounded-lg p-2" required>
                @foreach(\App\Models\User::roles() as $role => $label)
                    <option value="{{ $role }}" 
                        {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('role') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>

        <!-- Password (optional) -->
        <div class="md:col-span-2">
            <label class="block text-xs text-gray-500 mb-1">Mot de passe (laisser vide si inchangé)</label>
            <input type="password" name="password" class="w-full border rounded-lg p-2">
            @error('password') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>

        <!-- Active -->
        <div class="flex items-center gap-2 md:col-span-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Activer l’utilisateur</span>
        </div>

        <!-- Actions -->
        <div class="md:col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('superadmin.companies.show', $company->id) }}"
               class="px-4 py-2 bg-gray-200 rounded-xl">Annuler</a>
            <button type="submit"
                    class="px-4 py-2 bg-[#FF4B00] text-white rounded-xl">Enregistrer</button>
        </div>
    </form>
</div>
@endsection