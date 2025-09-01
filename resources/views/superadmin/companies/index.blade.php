@extends('layout')
@section('title','Sociétés')

@section('content')
<div class="px-6 py-6">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border p-5 md:p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6 text-[#FF4B00]"></i>
                        <span>Liste des</span> <span class="text-[#FF4B00]">Sociétés</span>
                    </h1>
                    <p class="text-gray-500 mt-1">Gérez les sociétés et accédez rapidement aux actions.</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative">
                  
                    </div>

                    <a href="{{ route('superadmin.companies.create') }}"
                       class="btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Nouvelle société</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 sticky top-0">
                        <tr>
                            <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Nom</th>
                            <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Email</th>
                            <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Téléphone</th>
                            <th class="p-4 text-left font-semibold uppercase text-xs tracking-wider">Utilisateurs</th>
                            <th class="p-4 text-right font-semibold uppercase text-xs tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="companiesTbody" class="divide-y divide-gray-100">
                        @forelse ($companies as $company)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Nom -->
                                <td class="p-4">
                                    <a href="{{ route('superadmin.companies.show', $company) }}" class="flex items-center gap-3 group">
                                        <div class="h-10 w-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center font-semibold text-[#FF4B00]">
                                            {{ strtoupper(Str::of($company->name)->substr(0,1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 group-hover:text-[#FF4B00] transition-colors">
                                                {{ $company->name }}
                                            </div>
                                            @if($company->created_at)
                                                <div class="text-xs text-gray-400">Créée le {{ $company->created_at->format('d/m/Y') }}</div>
                                            @endif
                                        </div>
                                    </a>
                                </td>

                                <!-- Email -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                        <span>{{ $company->email ?: '—' }}</span>
                                    </div>
                                </td>

                                <!-- Téléphone -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                        <span>{{ $company->phone ?: '—' }}</span>
                                    </div>
                                </td>

                                <!-- Users -->
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                        <span class="font-medium">{{ $company->users()->count() }}</span>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('superadmin.companies.show', $company) }}"
                                           class="btn-icon text-green-600 hover:bg-green-50" title="Ouvrir">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('superadmin.companies.edit', $company) }}"
                                           class="btn-icon text-blue-600 hover:bg-blue-50" title="Éditer">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('superadmin.companies.destroy', $company) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer cette société ? Tous ses utilisateurs seront aussi supprimés.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon text-red-600 hover:bg-red-50" title="Supprimer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10">
                                    <div class="flex flex-col items-center justify-center text-center text-gray-500">
                                        <i data-lucide="buildings" class="w-10 h-10 mb-2 text-gray-300"></i>
                                        <p class="font-medium">Aucune société</p>
                                        <p class="text-sm">Créez votre première société pour commencer.</p>
                                        <a href="{{ route('superadmin.companies.create') }}" class="mt-3 btn-primary">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            <span>Nouvelle société</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($companies,'links'))
                <div class="px-4 py-3 bg-gray-50 border-t">
                    {{ $companies->links() }}
                </div>
            @endif
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
    .search-input{
        height:38px;border:1px solid #e5e7eb;border-radius:9999px;padding:0 12px;
        font-size:.9rem;transition:all .2s ease;background:#fff
    }
    .search-input:focus{outline:none;border-color:#FF6B00;box-shadow:0 0 0 3px rgba(255,107,0,.15)}
    .btn-icon{
        height:36px;width:36px;display:inline-flex;align-items:center;justify-content:center;
        border-radius:10px;border:1px solid #e5e7eb;transition:all .2s ease;background:#fff
    }
    .btn-icon:hover{transform:translateY(-1px)}
</style>

<script>
    // Simple in-page search (client-side)
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('searchInput');
        const rows  = Array.from(document.querySelectorAll('#companiesTbody tr'));

        if (!input) return;

        input.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            rows.forEach(row => {
                const txt = row.textContent.toLowerCase();
                row.style.display = txt.includes(q) ? '' : 'none';
            });
        });
    });
</script>
@endsection