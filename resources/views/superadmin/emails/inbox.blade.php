@extends('layout')

@section('content')
<div class="flex h-screen bg-gray-50">
    {{-- Optional: reuse your sidebar, or make a slim one for superadmin --}}
    @includeIf('emails.partials.sidebar')

    <div class="flex-1 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Inbox (Super Admin)</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $emails->total() }} messages</p>
            </div>
            <div class="flex space-x-3">
                <form method="POST" action="{{ route('superadmin.emails.markAllRead') }}">
                    @csrf
                    <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 transition">
                        Marquer tout comme lu
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            @php
                $emailsWithUnreadReplies = $emails->getCollection()->filter(function($email) {
                    return ($email->replies->where('read', false)->count() ?? 0) > 0 || !$email->is_read;
                });
                // If nothing matched the filter above, just show the page collection
                $list = $emailsWithUnreadReplies->count() ? $emailsWithUnreadReplies : $emails->getCollection();
            @endphp

            @forelse($list as $email)
                <div class="group px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-all duration-200 flex items-start">
                    <div class="mr-4">
                        <form method="POST" action="{{ route('superadmin.emails.toggleImportant', $email->id) }}" onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" class="focus:outline-none text-xl h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200">
                                <i class="fas fa-star transition {{ $email->tag === 'important' ? 'text-yellow-400' : 'text-gray-300 group-hover:text-yellow-300' }}"></i>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('superadmin.emails.show', $email->id) }}" class="flex-1 min-w-0">
                        <div class="flex items-baseline">
                            <span class="font-semibold text-gray-800 truncate mr-3">{{ $email->senderUser->name ?? 'Utilisateur' }}</span>
                            <span class="text-sm text-gray-500 ml-2">à {{ $email->receiverUser->name ?? '—' }}</span>

                            @if($email->tag)
                                <span class="text-xs px-2 py-1 rounded-full font-medium text-white truncate ml-2"
                                      style="background-color: {{ $email->tag_color ?? '#999' }}">
                                    {{ ucfirst($email->tag) }}
                                </span>
                            @endif

                            @if($email->conversation_id)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">Thread</span>
                            @endif

                            @if(!$email->is_read)
                                <span class="ml-2 inline-flex items-center justify-center w-3 h-3 rounded-full bg-red-500" title="Non lu"></span>
                            @endif
                        </div>

                        <div class="mt-1 flex justify-between">
                            <div>
                                <span class="{{ !$email->is_read ? 'font-bold text-gray-900' : 'font-medium text-gray-800' }}">{{ $email->subject }}</span>
                                <span class="text-gray-500 text-sm ml-2">- {{ \Illuminate\Support\Str::limit(strip_tags($email->content), 70) }}</span>
                                @if($email->file_path)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/'.$email->file_path) }}" target="_blank" class="text-cyan-600 hover:text-cyan-800 text-sm flex items-center">
                                            <i class="fas fa-paperclip mr-1"></i> Télécharger la pièce jointe
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                {{ $email->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </a>

                    <div class="flex space-x-4 items-center text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity ml-4">
                        <form method="POST" action="{{ route('superadmin.emails.moveToTrash', $email->id) }}" onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200" title="Mettre à la corbeille">
                                <i class="fas fa-trash hover:text-red-500"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="inline-block p-4 bg-gray-100 rounded-full">
                        <i class="fas fa-inbox text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">Aucun message</h3>
                    <p class="mt-1 text-gray-500 max-w-md mx-auto">Les nouveaux messages apparaîtront ici.</p>
                </div>
            @endforelse
        </div>

        @if($emails->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $emails->links() }}
            </div>
        @endif
    </div>
</div>
@endsection