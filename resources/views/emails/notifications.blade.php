@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mes Notifications</h1>
             
            </div>
            <form method="POST" action="{{ route('emails.markAllRead') }}">
                @csrf
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-5 py-2.5 rounded-lg transition duration-300 ease-in-out flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Marquer toutes comme lues
                </button>
            </form>
        </div>

        <!-- Email cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($emails as $email)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <a href="{{ route('emails.show', $email->id) }}" class="block">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-base font-bold text-orange-700">
                                    {{ strtoupper(substr($email->sender_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex items-center">
                                    @if($email->attachments_count > 0)
                                    <div class="mr-2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($email->created_at)->format('d M H:i') }}</span>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ $email->subject }}
                                @if(!$email->is_read)
                                <span class="ml-2 bg-orange-500 text-white text-xs px-1.5 py-0.5 rounded-full align-middle">Nouveau</span>
                                @endif
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {!! strip_tags($email->content) !!}
                            </p>
                            
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-xs font-medium text-gray-700">{{ $email->sender_name ?? 'Utilisateur' }}</span>
                                <div class="flex space-x-2">
                                    @if($email->starred)
                                    <span class="text-orange-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </span>
                                    @endif
                                    @if($email->tag === 'important')
                                    <span class="text-orange-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                   
                </div>
            @empty
                <div class="col-span-4">
                    <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl border-2 border-dashed border-orange-200 p-10 text-center">
                        <div class="inline-block bg-orange-100 p-4 rounded-full mb-6">
                            <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune notification</h3>
                        <p class="text-gray-600 max-w-md mx-auto">Vous n'avez aucune notification non lue. Nous vous informerons quand de nouveaux messages arriveront.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($emails->hasPages())
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de 
                <span class="font-medium">{{ $emails->firstItem() }}</span>
                à 
                <span class="font-medium">{{ $emails->lastItem() }}</span>
                sur 
                <span class="font-medium">{{ $emails->total() }}</span>
                notifications
            </div>
            <div class="flex items-center space-x-2">
                @if ($emails->onFirstPage())
                    <span class="px-3 py-1.5 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                @else
                    <a href="{{ $emails->previousPageUrl() }}" class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif

                @foreach ($emails->getUrlRange(1, $emails->lastPage()) as $page => $url)
                    @if ($page == $emails->currentPage())
                        <span class="px-3 py-1.5 rounded-md bg-orange-500 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($emails->hasMorePages())
                    <a href="{{ $emails->nextPageUrl() }}" class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <span class="px-3 py-1.5 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .shadow-card {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .shadow-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>