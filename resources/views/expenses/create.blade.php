@extends('layout')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Créer une nouvelle dépense</h1>
            <p class="text-gray-600 mt-1">Remplissez le formulaire pour enregistrer une nouvelle dépense</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-orange-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date Field -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date de la dépense *</label>
                        <div class="relative">
                            <input type="date" name="date" id="date" value="{{ old('date') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                              
                            </div>
                        </div>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status Field -->
                    <div>
                        <label for="paid_status" class="block text-sm font-medium text-gray-700 mb-1">Statut de paiement *</label>
                        <div class="relative">
                            <select name="paid_status" id="paid_status" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                                <option value="">Sélectionner un statut</option>
                                <option value="paid" {{ old('paid_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="pending" {{ old('paid_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="unpaid" {{ old('paid_status') == 'unpaid' ? 'selected' : '' }}>Non payé</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('paid_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Client Field -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client associé *</label>
                        <div class="relative">
                            <select name="client_id" id="client_id" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->prenom }} {{ $client->nom_assure }} (#{{ $client->reference_client }})
                                </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Supplier Field -->
                    <div>
                        <label for="fournisseur_id" class="block text-sm font-medium text-gray-700 mb-1">Fournisseur *</label>
                        <div class="relative">
                            <select name="fournisseur_id" id="fournisseur_id" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 appearance-none">
                                <option value="">Sélectionner un fournisseur</option>
                                @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->nom_societe }}
                                </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                                </svg>
                            </div>
                        </div>
                        @error('fournisseur_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- HT Amount -->
                    <div>
                        <label for="ht_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant HT (€) *</label>
                        <div class="relative">
                            <input type="number" name="ht_amount" id="ht_amount" step="0.01" min="0" 
                                   value="{{ old('ht_amount') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-2.5 focus:ring-orange-500 focus:border-orange-500" 
                                   placeholder="0,00">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                        </div>
                        @error('ht_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- TTC Amount -->
                    <div>
                        <label for="ttc_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant TTC (€) *</label>
                        <div class="relative">
                            <input type="number" name="ttc_amount" id="ttc_amount" step="0.01" min="0" 
                                   value="{{ old('ttc_amount') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 pl-10 py-2.5 focus:ring-orange-500 focus:border-orange-500" 
                                   placeholder="0,00">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                        </div>
                        @error('ttc_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Détails de la dépense...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
             
                </div>
            </div>
            
            <!-- Form Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                <div>
                
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:opacity-90 transition-opacity shadow-md">
                        Enregistrer la dépense
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Recent Expenses -->
    <div class="mt-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Dépenses récentes</h3>
            <a href="{{ route('expenses.index') }}" class="text-sm text-orange-500 hover:underline">Voir tout</a>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentExpenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $expense->date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $expense->client->prenom }} {{ $expense->client->nom_assure }}</div>
                                <div class="text-xs text-gray-500">#{{ $expense->client->reference_client }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">{{ $expense->fournisseur->nom_societe }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($expense->paid_status == 'paid')
                                <span class="inline-flex items-center gap-1 bg-teal-100 px-3 py-1 rounded-full text-xs font-medium text-teal-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Payé
                                </span>
                                @elseif($expense->paid_status == 'pending')
                                <span class="inline-flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full text-xs font-medium text-yellow-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-4a1 1 0 01-2 0v-2a1 1 0 012 0v2zm4-2a1 1 0 11-2 0v-6a1 1 0 012 0v6zm4-6a1 1 0 10-2 0v6a1 1 0 102 0V6zm4 8a1 1 0 100-2h-2a1 1 0 100 2h2z" clip-rule="evenodd" />
                                    </svg>
                                    En attente
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 bg-red-100 px-3 py-1 rounded-full text-xs font-medium text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Non payé
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ number_format($expense->ttc_amount, 2, ',', ' ') }}€
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // File upload preview
    document.getElementById('dropzone-file').addEventListener('change', function(e) {
        const files = e.target.files;
        const dropzoneContent = document.getElementById('dropzone-content');
        
        if (files.length > 0) {
            dropzoneContent.innerHTML = `
                <div class="p-4 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-gray-700">${files.length} fichier(s) sélectionné(s)</p>
                    <p class="text-xs text-gray-500 mt-1">Cliquez pour modifier</p>
                </div>
            `;
        }
    });

    // Clear form button
    document.querySelector('[aria-label="Supprimer le brouillon"]').addEventListener('click', function() {
        if(confirm('Voulez-vous vraiment effacer tous les champs du formulaire ?')) {
            document.querySelector('form').reset();
            document.getElementById('dropzone-content').innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Cliquez pour télécharger</span> ou glissez-déposez</p>
                <p class="text-xs text-gray-500">PDF, JPG, PNG (MAX. 5MB)</p>
            `;
        }
    });
</script>
@endsection