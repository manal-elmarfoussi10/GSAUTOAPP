@extends('layout')

@section('content')
<div class="flex h-screen">
    {{-- Sidebar --}}
    @include('emails.partials.sidebar')

    {{-- Bin Emails Content --}}
    <div class="flex-1 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Corbeille</h1>
        </div>

        {{-- Search bar --}}
        <div class="mb-6">
            <input type="text" placeholder="Rechercher..." class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        {{-- Email list --}}
        <div class="bg-white rounded shadow overflow-hidden">
            @forelse($emails as $email)
                <div class="group flex items-center justify-between px-6 py-4 border-b hover:bg-gray-50 transition">

                    {{-- Left section: Star + Subject + Tag --}}
                    <div class="flex items-center gap-4">
                        {{-- Star --}}
                        <i class="fas fa-star text-xl text-gray-300 group-hover:text-yellow-400"></i>

                        {{-- Subject --}}
                        <span class="font-medium text-gray-800">{{ $email->subject }}</span>

                        {{-- Tag --}}
                        @if($email->tag)
                            <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                  style="background-color: {{ $email->label_color ?? '#eee' }}">
                                {{ ucfirst($email->tag) }}
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-4 items-center text-gray-500">
                        {{-- Restore --}}
                        <form method="POST" action="{{ route('emails.restore', $email->id) }}">
                            @csrf
                            <button type="submit" title="Restaurer">
                                <i class="fas fa-undo hover:text-blue-500"></i>
                            </button>
                        </form>

                        {{-- View --}}
                        <a href="{{ route('emails.show', $email->id) }}" title="Voir le mail">
                            <i class="fas fa-eye hover:text-green-500"></i>
                        </a>

                        {{-- Permanent Delete --}}
                        <form method="POST" action="{{ route('emails.destroy', $email->id) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet email ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer définitivement">
                                <i class="fas fa-trash-alt hover:text-red-600"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10">Aucun email dans la corbeille.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection