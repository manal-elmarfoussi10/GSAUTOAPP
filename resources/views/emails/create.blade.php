@extends('layout')

@section('content')
<div class="flex h-screen bg-gray-50">
    {{-- Barre latérale gauche --}}
    @include('emails.partials.sidebar')

    <div class="flex-1 overflow-y-auto">
        {{-- En-tête --}}
        <div class="bg-white shadow-sm p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">Nouveau message</h1>

                <div class="space-x-2">
                    <a href="{{ route('emails.inbox') }}"
                       class="inline-flex items-center px-3 py-2 rounded-md border text-sm bg-white hover:bg-gray-50">
                        <i class="fas fa-inbox mr-2"></i> Boîte de réception
                    </a>

                    @if(request('client_id'))
                        <a href="{{ route('clients.show', (int) request('client_id')) }}"
                           class="inline-flex items-center px-3 py-2 rounded-md border text-sm bg-white hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i> Dossier client
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto py-6 px-4">
            @if ($errors->any())
                <div class="mb-4 rounded bg-red-50 text-red-700 px-4 py-3">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data"
                  class="bg-white rounded-2xl shadow border p-6 space-y-6">
                @csrf

                {{-- Destinataire --}}
                <div>
                    <label for="receiver_id" class="block text-sm font-semibold text-gray-800 mb-2">À :</label>
                    <div class="relative">
                        <select id="receiver_id" name="receiver_id" required
                                class="block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 h-12 pl-3 pr-10 text-gray-700">
                            <option value="" disabled selected>Sélectionner un destinataire</option>

                            {{-- Support GS Auto --}}
                            @isset($supportUsers)
                                @if($supportUsers->isNotEmpty())
                                    <optgroup label="GS Auto · Support">
                                        @foreach($supportUsers as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endisset

                            {{-- Entreprises + utilisateurs --}}
                            @foreach(($companies ?? collect()) as $company)
                                @if($company->users->isNotEmpty())
                                    <optgroup label="Entreprise : {{ $company->name }}">
                                        @foreach($company->users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        <span class="absolute right-3 top-3 text-gray-400 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    @if(request('client_id'))
                        <input type="hidden" name="client_id" value="{{ (int) request('client_id') }}">
                    @endif
                </div>

                {{-- Sujet --}}
                <div>
                    <label for="subject" class="block text-sm font-semibold text-gray-800 mb-2">Sujet :</label>
                    <div class="relative">
                        <input id="subject" name="subject" required
                               class="block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 h-12 pl-3 pr-10 text-gray-700"
                               placeholder="Objet de l’email" value="{{ old('subject') }}">
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-tag"></i>
                        </span>
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-2">Message :</label>
                    <textarea id="content" name="content" rows="10"
                              class="block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">{{ old('content') }}</textarea>
                </div>

                {{-- Pièce jointe --}}
                <div>
                    <label for="file" class="block text-sm font-semibold text-gray-800 mb-2">Pièce jointe :</label>
                    <input id="file" type="file" name="file"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md 
                                  file:border-0 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                {{-- Bouton envoyer --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-3 rounded-lg text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.25.1/full/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    CKEDITOR.replace('content', {
        height: 220,
        versionCheck: false,
        filebrowserUploadUrl: "{{ route('emails.upload') }}",
        filebrowserUploadMethod: 'form'
    });
});
</script>
@endsection