@extends('layout')

@section('content')
<div class="px-6 py-8">
    <h2 class="text-3xl font-semibold mb-4">Sidexa</h2>

    <a href="{{ route('sidexa.create') }}"
       class="inline-block mb-6 px-5 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
        Faire un chiffrage Sidexa
    </a>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Plaque</th>
                    <th class="px-4 py-3">Type de vitrage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sidexas as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $item->name }}</td>
                        <td class="px-4 py-2">{{ $item->plate }}</td>
                        <td class="px-4 py-2">{{ $item->glass_type }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                            Aucun chiffrage pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection