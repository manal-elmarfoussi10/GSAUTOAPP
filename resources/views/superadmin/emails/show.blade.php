@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    {{-- flashes --}}
    @if(session('success'))
        <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-2">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded bg-red-50 text-red-700 px-4 py-2 space-y-1">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        {{-- Header --}}
        <div class="px-6 py-4 border-b">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">{{ $email->subject }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        De
                        <span class="font-medium">{{ $email->senderUser->name ?? 'Utilisateur' }}</span>
                        à
                        <span class="font-medium">
                            {{ optional($email->receiverUser)->name ?? 'Service Client' }}
                        </span>
                        · {{ optional($email->created_at)->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    {{-- Back to inbox --}}
                    <a href="{{ route('superadmin.emails.index') }}"
                       class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la boîte de réception
                    </a>

                    {{-- Open client dossier (only if email is linked to a client) --}}
                    @if($email->client_id)
                        <a href="{{ route('superadmin.clients.show', $email->client_id) }}#conversations"
                           class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-folder-open"></i>
                            Ouvrir le dossier client
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-6 space-y-8">
            {{-- Root message --}}
            <div class="text-gray-800 whitespace-pre-line">
                {!! nl2br(e($email->content)) !!}
            </div>

            @if($email->file_path)
                <div>
                    <a href="{{ asset('storage/'.$email->file_path) }}" target="_blank"
                       class="text-cyan-600 hover:text-cyan-800 inline-flex items-center">
                        <i class="fas fa-paperclip mr-2"></i>{{ $email->file_name ?? 'Pièce jointe' }}
                    </a>
                </div>
            @endif

            {{-- Thread replies --}}
            @forelse($email->replies as $reply)
                <div class="border-t pt-6">
                    <div class="text-sm text-gray-500 mb-1">
                        <span class="font-medium">{{ optional($reply->sender)->name ?? 'Utilisateur' }}</span>
                        à {{ optional($reply->receiver)->name ?? 'Service Client' }}
                        · {{ optional($reply->created_at)->format('d/m/Y H:i') }}
                    </div>

                    <div class="text-gray-800 whitespace-pre-line">
                        {!! nl2br(e($reply->content)) !!}
                    </div>

                    @if($reply->file_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/'.$reply->file_path) }}" target="_blank"
                               class="text-cyan-600 hover:text-cyan-800 inline-flex items-center">
                                <i class="fas fa-paperclip mr-2"></i>{{ $reply->file_name ?? 'Pièce jointe' }}
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="border-t pt-6 text-sm text-gray-500">
                    Aucune réponse pour le moment.
                </div>
            @endforelse

            {{-- Reply form --}}
            <form method="POST"
                  action="{{ route('superadmin.emails.reply', $email) }}"
                  enctype="multipart/form-data"
                  class="border-t pt-6">
                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-1">Répondre</label>
                <textarea name="content" rows="4"
                          class="w-full border rounded-md px-3 py-2 focus:ring-cyan-600 focus:border-cyan-600"
                          required></textarea>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe</label>
                    <input type="file" name="file" class="block w-full text-sm text-gray-600">
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                            class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection