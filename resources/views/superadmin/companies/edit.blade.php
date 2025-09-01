@extends('layout')
@section('title','Modifier la société')

@section('content')
<div class="px-6 py-6 max-w-5xl mx-auto space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800">
      Modifier <span class="text-[#FF4B00]">{{ $company->name }}</span>
    </h1>
    <p class="text-gray-500 text-sm">Mettez à jour les informations de la société.</p>
  </div>

  <!-- Form -->
  <form action="{{ route('superadmin.companies.update', $company) }}" method="POST"
        class="bg-white rounded-2xl shadow-sm border p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    @csrf
    @method('PUT')

    <!-- Nom -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Nom *</label>
      <input name="name" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]"
             value="{{ old('name',$company->name) }}" required>
      @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Email -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
      <input type="email" name="email" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]"
             value="{{ old('email',$company->email) }}">
      @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Téléphone -->
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">Téléphone</label>
      <input name="phone" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FF4B00] focus:border-[#FF4B00]"
             value="{{ old('phone',$company->phone) }}">
      @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Bouton -->
    <div class="md:col-span-3 flex justify-end">
      <button class="px-6 py-3 bg-[#FF4B00] text-white font-semibold rounded-full shadow hover:bg-[#e04300] transition">
        <i class="fas fa-save mr-2"></i> Enregistrer
      </button>
    </div>
  </form>
</div>
@endsection