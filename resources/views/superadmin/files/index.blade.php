@extends('layout')
@section('title','Fichiers & Exports')

@section('content')
<div class="px-6 py-6">
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border p-5 md:p-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="database" class="w-6 h-6 text-[#FF4B00]"></i>
            <span>Fichiers</span> <span class="text-[#FF4B00]">& Exports</span>
          </h1>
          <p class="text-gray-500 mt-1">Parcourez toutes les données multi-sociétés et exportez en CSV.</p>
        </div>

        <div class="flex items-center gap-3">
          <a href="{{ route('superadmin.files.export', request()->query()) }}" class="btn-primary">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>Exporter CSV</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-2xl shadow-sm border p-5 grid grid-cols-1 md:grid-cols-6 gap-3">
      <div class="md:col-span-2">
        <label class="text-xs text-gray-500">Type</label>
        <select name="type" class="border rounded-lg p-2 w-full">
          @php $t = $filters['type'] ?? 'clients'; @endphp
          @foreach([
            'clients'=>'Clients',
            'devis'=>'Devis',
            'factures'=>'Factures',
            'avoirs'=>'Avoirs',
            'paiements'=>'Paiements',
            'rdvs'=>'RDV',
            'expenses'=>'Dépenses',
            'bons_de_commande'=>'Bons de commandes',
            'fournisseurs'=>'Fournisseurs',
            'produits'=>'Produits',
            'poseurs'=>'Poseurs',
            'stocks'=>'Stocks',
          ] as $k=>$v)
            <option value="{{ $k }}" @selected($t===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div class="md:col-span-2">
        <label class="text-xs text-gray-500">Société</label>
        <select name="company_id" class="border rounded-lg p-2 w-full">
          <option value="">— Toutes —</option>
          @foreach($companies as $c)
            <option value="{{ $c->id }}" @selected((string)($filters['company_id'] ?? '') === (string)$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="text-xs text-gray-500">Du</label>
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="border rounded-lg p-2 w-full">
      </div>

      <div>
        <label class="text-xs text-gray-500">Au</label>
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="border rounded-lg p-2 w-full">
      </div>

      <div class="md:col-span-5">
        <label class="text-xs text-gray-500">Recherche</label>
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nom, email, téléphone, immatriculation, titre…"
               class="border rounded-lg p-2 w-full">
      </div>

      <div class="md:col-span-1 flex items-end">
        <button class="w-full px-4 py-2 bg-[#FF4B00] text-white rounded-xl">Filtrer</button>
      </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 sticky top-0">
            <tr>
              @foreach($columns as $label)
                <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">{{ $label }}</th>
              @endforeach

              {{-- Extra "Action" column only for Clients --}}
              @if(($filters['type'] ?? 'clients') === 'clients')
                <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Action</th>
              @endif
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            @php
              $isPaginator = $results instanceof \Illuminate\Pagination\LengthAwarePaginator;
              $rows = $isPaginator ? $results : collect($results);
              $currentType = $filters['type'] ?? 'clients';
            @endphp

            @forelse($rows as $row)
              <tr class="hover:bg-gray-50 transition-colors">
                @foreach(array_keys($columns) as $key)
                  <td class="p-4">
                    {{ app(\App\Http\Controllers\Superadmin\FilesController::class)
                        ->renderCell($currentType, $key, $row, true) }}
                  </td>
                @endforeach

                {{-- “Voir” button only for Clients --}}
                @if($currentType === 'clients')
                  <td class="p-4">
                    <a href="{{ route('superadmin.clients.show', $row->id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-cyan-600 text-white hover:bg-cyan-700">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.523 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10z" clip-rule="evenodd" />
                      </svg>
                      Voir
                    </a>
                  </td>
                @endif
              </tr>
            @empty
              <tr>
                <td class="p-8 text-center text-gray-500" colspan="{{ count($columns) + (($filters['type'] ?? 'clients') === 'clients' ? 1 : 0) }}">
                  Aucune donnée.
                </td>
              </tr>
            @endforelse
          </tbody>

          @if($isPaginator)
            <tfoot>
              <tr>
                <td class="px-4 py-3 bg-gray-50 border-t" colspan="{{ count($columns) + (($filters['type'] ?? 'clients') === 'clients' ? 1 : 0) }}">
                  {{ $results->links() }}
                </td>
              </tr>
            </tfoot>
          @endif
        </table>
      </div>
    </div>

  </div>
</div>

<style>
  .btn-primary{
    background:#FF6B00;color:#fff;font-weight:600;
    padding:10px 16px;border-radius:12px;display:inline-flex;gap:8px;align-items:center;
    box-shadow:0 4px 12px rgba(255,107,0,.25);transition:all .2s ease;border:none
  }
  .btn-primary:hover{background:#D45A00;transform:translateY(-1px)}
</style>
<script>lucide.createIcons();</script>
@endsection