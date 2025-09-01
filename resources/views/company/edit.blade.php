@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-8 text-white">
            <div class="flex items-center">
                <div class="mr-4 bg-white/20 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Modifier mon entreprise</h1>
                    <p class="text-blue-100 mt-1">Mettez à jour les informations de votre entreprise</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- General Information -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informations générales</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom légal *</label>
                            <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom commercial</label>
                            <input type="text" name="commercial_name" value="{{ old('commercial_name', $company->commercial_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SIRET</label>
                            <input type="text" name="siret" value="{{ old('siret', $company->siret) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code APE</label>
                            <input type="text" name="ape" value="{{ old('ape', $company->ape) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <!-- New Legal Fields -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Forme juridique</label>
                            <input type="text" name="legal_form" value="{{ old('legal_form', $company->legal_form) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capital social (€)</label>
                            <input type="number" step="0.01" name="capital" value="{{ old('capital', $company->capital) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse siège social</label>
                            <input type="text" name="head_office_address" value="{{ old('head_office_address', $company->head_office_address) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RCS – Ville</label>
                            <div class="flex space-x-2">
                                <input type="text" name="rcs_number" placeholder="N° RCS" value="{{ old('rcs_number', $company->rcs_number) }}"
                                       class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <input type="text" name="rcs_city" placeholder="Ville" value="{{ old('rcs_city', $company->rcs_city) }}"
                                       class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code NAF</label>
                            <input type="text" name="naf_code" value="{{ old('naf_code', $company->naf_code) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <!-- End New Legal Fields -->
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Coordonnées</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $company->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <input type="text" name="address" value="{{ old('address', $company->address) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" name="city" value="{{ old('city', $company->city) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Information financières</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro TVA</label>
                            <input type="text" name="tva" value="{{ old('tva', $company->tva) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IBAN</label>
                            <input type="text" name="iban" value="{{ old('iban', $company->iban) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">BIC</label>
                            <input type="text" name="bic" value="{{ old('bic', $company->bic) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <!-- Financial Legal Additions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Régime TVA</label>
                            <input type="text" name="tva_regime" value="{{ old('tva_regime', $company->tva_regime) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Éco-contribution</label>
                            <input type="text" name="eco_contribution" value="{{ old('eco_contribution', $company->eco_contribution) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pénalités de retard</label>
                            <input type="text" name="penalty_rate" value="{{ old('penalty_rate', $company->penalty_rate) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                            <input type="text" name="methode_paiement" value="{{ old('methode_paiement', $company->methode_paiement) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>
                        <!-- End Financial Legal Additions -->
                    </div>

                    <br><br>
                <!-- Legal Extras -->
                <div class="border-b border-gray-200 pb-8">
                  <div class="flex items-center mb-6">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                      </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Informations légales</h2>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Assurance professionnelle</label>
                      <input type="text" name="professional_insurance" value="{{ old('professional_insurance', $company->professional_insurance) }}"
                             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Mandataire</label>
                      <input type="text" name="representative" value="{{ old('representative', $company->representative) }}"
                             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    </div>
                  </div>
                </div>
                </div>

                <!-- Preferences -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Préférences</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment nous avez-vous connu ?</label>
                            <input type="text" name="known_by" value="{{ old('known_by', $company->known_by) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Autorisation de contact</label>
                            <select name="contact_permission" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="oui" {{ old('contact_permission', $company->contact_permission) == 'oui' ? 'selected' : '' }}>Oui</option>
                                <option value="non" {{ old('contact_permission', $company->contact_permission) == 'non' ? 'selected' : '' }}>Non</option>
                                <option value="demander" {{ old('contact_permission', $company->contact_permission) == 'demander' ? 'selected' : '' }}>Me demander avant</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type de garage</label>
                            <select name="garage_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="fixe" {{ old('garage_type', $company->garage_type) == 'fixe' ? 'selected' : '' }}>Fixe</option>
                                <option value="mobile" {{ old('garage_type', $company->garage_type) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="les deux" {{ old('garage_type', $company->garage_type) == 'les deux' ? 'selected' : '' }}>Les deux</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div>
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Documents</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach([
                            'logo' => 'Logo de la société',
                            'id_photo_recto' => "Photo d'identité (Recto)",
                            'id_photo_verso' => "Photo d'identité (Verso)",
                            'kbis' => 'Document Kbis',
                            'rib' => 'Document RIB',
                            'tva_exemption_doc' => 'Document non assujetti TVA',
                            'invoice_terms_doc' => 'Conditions générales de vente'
                        ] as $field => $label)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>

                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <input type="file" name="{{ $field }}"
                                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>

                                    @if($company->$field)
                                        <a href="{{ asset('/storage/app/public/'.$company->$field) }}" target="_blank" class="ml-3 p-2 bg-green-50 rounded-lg text-green-700 hover:bg-green-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                @if($company->$field)
                                    <p class="mt-2 text-xs text-green-600 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Fichier existant - laisser vide pour conserver
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="mt-10 flex justify-center">
                <button type="submit" class="flex items-center bg-gradient-to-r from-orange-500 to-orange-600 hover:from-green-700 hover:to-green-800 text-white font-semibold px-8 py-3 rounded-lg transition-all shadow-md transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mettre à jour les informations
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }

    .hover\:-translate-y-0\.5:hover {
        transform: translateY(-0.125rem);
    }

    .transition {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    .duration-150 {
        transition-duration: 150ms;
    }

    .focus\:ring-blue-500:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }

    .focus\:border-blue-500:focus {
        border-color: #3b82f6;
    }

    .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endsection
