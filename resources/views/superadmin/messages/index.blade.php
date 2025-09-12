@extends('layout')
@section('title', 'Messages')

@section('content')
<div class="px-6 py-6">
  <div class="max-w-7xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
      <div class="rounded-xl bg-green-50 text-green-800 px-4 py-2">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border p-5 md:p-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="inbox" class="w-6 h-6 text-[#FF4B00]"></i>
            <span>Messages</span>
          </h1>
          <p class="text-gray-500 mt-1">Messages envoyés par les sociétés via le formulaire de contact.</p>
        </div>
      </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border p-5 grid grid-cols-1 md:grid-cols-6 gap-3">
      <div class="md:col-span-3">
        <label class="text-xs text-gray-500">Recherche</label>
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nom, email, contenu…"
               class="border rounded-lg p-2 w-full">
      </div>

      @if($hasCompanyId)
        <div class="md:col-span-2">
          <label class="text-xs text-gray-500">Société</label>
          <select name="company_id" class="border rounded-lg p-2 w-full">
            <option value="">— Toutes —</option>
            @foreach($companies as $c)
              <option value="{{ $c->id }}" @selected((string)($filters['company_id'] ?? '') === (string)$c->id)>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>
      @endif

      <div class="{{ $hasCompanyId ? '' : 'md:col-span-2' }} md:col-span-1 flex items-end">
        <button class="w-full px-4 py-2 bg-[#FF4B00] text-white rounded-xl">Filtrer</button>
      </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 sticky top-0">
            <tr>
              <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Date</th>
              <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Expéditeur</th>
              <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Email</th>
              @if($hasCompanyId)
                <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Société</th>
              @endif
              <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Message</th>
              <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($contacts as $m)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="p-4 whitespace-nowrap text-gray-600">
                  {{ $m->created_at?->format('d/m/Y H:i') }}
                </td>
                <td class="p-4 whitespace-nowrap font-medium text-gray-900">
                  {{ $m->name }}
                </td>
                <td class="p-4 whitespace-nowrap text-cyan-700">
                  <a href="mailto:{{ $m->email }}">{{ $m->email }}</a>
                </td>
                @if($hasCompanyId)
                  <td class="p-4 whitespace-nowrap">
                    {{ $m->company->name ?? '—' }}
                  </td>
                @endif
                <td class="p-4">
                  <span class="text-gray-700">
                    {{ \Illuminate\Support\Str::limit($m->message, 90) }}
                  </span>
                </td>
                <td class="p-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <a href="{{ route('superadmin.messages.show', $m) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-cyan-600 text-white hover:bg-cyan-700">
                      <i data-lucide="eye" class="w-4 h-4"></i> Voir
                    </a>
                    <form action="{{ route('superadmin.messages.destroy', $m) }}" method="POST"
                          onsubmit="return confirm('Supprimer ce message ?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td class="p-8 text-center text-gray-500" colspan="{{ $hasCompanyId ? 6 : 5 }}">
                  Aucun message.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="px-4 py-3 bg-gray-50 border-t">
        {{ $contacts->links() }}
      </div>
    </div>

  </div>
</div>
<script>lucide.createIcons();</script>
@endsection