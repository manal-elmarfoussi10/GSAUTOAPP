@extends('layout')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="flex h-screen bg-gray-50">
    {{-- Sidebar --}}
    @include('emails.partials.sidebar')

    {{-- Sent Emails Content --}}
    <div class="flex-1 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Sent</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $emails->count() }} messages</p>
        </div>

        {{-- Search/Compose --}}
        <div class="flex justify-between mb-6">
            <a href="{{ route('emails.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center">
                <i class="fas fa-plus mr-2"></i>New Email
            </a>
            <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        {{-- Email list --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            @forelse($emails as $email)
                <div class="group flex items-start px-6 py-4 border-b hover:bg-gray-50 transition">
                    {{-- Star toggle --}}
                    <div class="mr-4">
                        <form method="POST" action="{{ route('emails.toggleImportant', $email->id) }}" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="focus:outline-none text-xl h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-200">
                                <i class="fas fa-star transition {{ $email->tag === 'important' ? 'text-yellow-400' : 'text-gray-300 group-hover:text-yellow-300' }}"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Summary --}}
                    <a href="{{ route('emails.show', $email->id) }}" class="flex-1 min-w-0 text-gray-800">
                        <div class="flex justify-between items-baseline">
                            <div class="truncate">
                                <span class="font-semibold">{{ $email->senderUser->name }}</span>
                                <span class="text-sm text-gray-500 ml-2">to {{ $email->receiverUser->name }}</span>
                                @if($email->conversation_id)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-1 py-0.5 rounded ml-2">Thread</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 whitespace-nowrap ml-4">
                                {{ $email->created_at->format('h:i A') }}
                            </div>
                        </div>
                        <div class="mt-1">
                            <span class="font-medium">{{ $email->subject }}</span>
                            <span class="text-gray-500 text-sm ml-2">- {{ Str::limit(strip_tags($email->content), 70) }}</span>
                        </div>
                        @if($email->file_path)
                            <div class="mt-1">
                                <a href="/storage/app/public/{{ $email->file_path}}" target="_blank"
                                   class="flex items-center text-cyan-600 hover:text-cyan-800 text-sm">
                                    <i class="fas fa-paperclip mr-1"></i> Download attachment
                                </a>
                            </div>
                        @endif
                    </a>

                    {{-- Delete --}}
                    <div class="ml-4 opacity-0 group-hover:opacity-100 transition">
                        <form method="POST" action="{{ route('emails.moveToTrash', $email->id) }}" onclick="event.stopPropagation()">
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
                        <i class="fas fa-paper-plane text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">No sent messages</h3>
                    <p class="mt-1 text-gray-500">Your sent folder is empty.</p>
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
@endsection
