@extends('layout')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white border-r p-6">
        <a href="#" class="bg-orange-500 text-white px-4 py-2 mb-6 block rounded text-center hover:bg-orange-600">+ Ajouter un modèle</a>

        <div>
            <h2 class="font-semibold text-gray-800 mb-4">Mes emails</h2>
            <ul class="space-y-2">
                @foreach($templates as $template)
                    <li>
                        <a href="{{ route('email-templates.show', $template) }}" class="flex items-center justify-between text-sm px-3 py-2 rounded hover:bg-gray-100 {{ $activeTemplate->id === $template->id ? 'bg-gray-200 font-bold' : '' }}">
                            {{ $template->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Content -->
    <div class="w-3/4 p-10">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('email-templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nom :</label>
                <input type="text" name="name" value="{{ old('name', $activeTemplate->name ?? '') }}" class="w-full border rounded px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Objet :</label>
                <input type="text" name="subject" value="{{ old('subject', $activeTemplate->subject ?? '') }}" class="w-full border rounded px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Contenu :</label>
                <textarea name="body" rows="10" class="w-full border rounded px-4 py-2">{{ old('body', $activeTemplate->body ?? '') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Fichier :</label>
                <input type="file" name="file" class="block">
                <input type="text" name="file_name" placeholder="Nom du fichier" class="mt-2 w-full border rounded px-4 py-2" value="{{ old('file_name', $activeTemplate->file_name ?? '') }}">
                @if(!empty($activeTemplate->file_path))
                    <p class="text-sm mt-1">Fichier actuel: <a href="{{ Storage::url($activeTemplate->file_path) }}" target="_blank" class="text-blue-500 underline">Voir le fichier</a></p>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection