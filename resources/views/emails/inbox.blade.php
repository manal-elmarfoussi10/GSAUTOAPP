@extends('layout')

@section('content')
<div class="flex h-screen bg-gray-50">
    {{-- Sidebar --}}
    @include('emails.partials.sidebar')

    {{-- Inbox Content --}}
    <div class="flex-1 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Inbox</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $emails->count() }} messages</p>
            </div>
            <div class="flex space-x-3">
              <button class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition">
                <a href="{{ route('emails.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i>New Email
                </a>
                </button>
                <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 transition">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>



        {{-- Email list with enhanced design --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            @php
                // Show emails with unread replies or not read by receiver
                $emailsWithUnreadReplies = $emails->filter(function($email) {
                    return $email->replies->where('read', false)->count() > 0 || !$email->is_read;
                });
            @endphp
            @forelse($emailsWithUnreadReplies as $email)
                <div class="group px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-all duration-200 flex items-start">
                    {{-- Star/Important toggle --}}
                    <div class="mr-4">
                        <form method="POST" action="{{ route('emails.toggleImportant', $email->id) }}"
                              onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" class="focus:outline-none text-xl h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200">
                                <i class="fas fa-star transition {{ $email->tag === 'important' ? 'text-yellow-400' : 'text-gray-300 group-hover:text-yellow-300' }}"></i>
                            </button>
                        </form>
                    </div>
                    {{-- Email content --}}
                    <a href="{{ route('emails.show', $email->id) }}" class="flex-1 min-w-0">
                        <div class="flex items-baseline">
                            <span class="font-semibold text-gray-800 truncate mr-3">{{ $email->senderUser->name }}</span>
                            <span class="text-sm text-gray-500 ml-2">to {{ $email->receiverUser->name }}</span>
                            @if($email->tag)
                                <span class="text-xs px-2 py-1 rounded-full font-medium text-white truncate"
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
                                        <a href="/storage/app/public/{{ $email->file_path}}" target="_blank" class="text-cyan-600 hover:text-cyan-800 text-sm flex items-center">
                                            <i class="fas fa-paperclip mr-1"></i> Download attachment
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                {{ $email->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </a>
                    {{-- Actions --}}
                    <div class="flex space-x-4 items-center text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity ml-4">
                        {{-- Move to trash --}}
                        <form method="POST" action="{{ route('emails.moveToTrash', $email->id) }}"
                              onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200" title="Move to trash">
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
                    <h3 class="mt-4 text-lg font-medium text-gray-700">No messages</h3>
                    <p class="mt-1 text-gray-500 max-w-md mx-auto">Your inbox is empty. New messages will appear here when you receive them.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($emails->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $emails->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Floating compose button for mobile --}}
<div class="fixed bottom-6 right-6 md:hidden">
    <button class="w-14 h-14 rounded-full bg-orange-500 text-white shadow-lg flex items-center justify-center hover:bg-orange-600">
        <i class="fas fa-pen text-xl"></i>
    </button>
</div>
@endsection
