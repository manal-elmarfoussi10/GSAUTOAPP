@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-8 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Mon entreprise</h1>
                        <p class="text-orange-100 mt-1">Informations générales et documents</p>
                    </div>
                </div>

                @if($company)
                    <a href="{{ route('company.edit') }}" class="flex items-center bg-white text-orange-600 hover:bg-gray-50 font-semibold px-5 py-3 rounded-lg transition-all shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6">
            @if($company)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Company Info -->
                    <div class="space-y-8">
                        <!-- Company Identity -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Identité de l'entreprise</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Nom</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->name }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Nom commercial</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->commercial_name }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">SIRET</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->siret }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">APE</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->ape }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Forme juridique</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->legal_form }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Capital social</div>
                                    <div class="w-2/3 text-gray-800">{{ number_format($company->capital, 2, ',', ' ') }} €</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Adresse siège social</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->head_office_address }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">RCS – Ville</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->rcs_number }} – {{ $company->rcs_city }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Code NAF</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->naf_code }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Coordonnées</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Email</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->email }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Téléphone</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->phone }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Adresse</div>
                                    <div class="w-2/3 text-gray-800">
                                        {{ $company->address }}<br>
                                        {{ $company->postal_code }} {{ $company->city }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Préférences</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Connu par</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->known_by }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Autorisation de contact</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->contact_permission }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Type de garage</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->garage_type }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Financial & Documents -->
                    <div class="space-y-8">
                        <!-- Financial Information -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Information financières</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">TVA</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->tva }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">IBAN</div>
                                    <div class="w-2/3 text-gray-800 font-mono tracking-wide">{{ $company->iban }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">BIC</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->bic }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Régime TVA</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->tva_regime }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Éco-contribution</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->eco_contribution }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Pénalités de retard</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->penalty_rate }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Mode de paiement</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->methode_paiement }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations légales -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Informations légales</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Assurance professionnelle</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->professional_insurance }}</div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/3 text-gray-500 font-medium">Mandataire</div>
                                    <div class="w-2/3 text-gray-800">{{ $company->representative }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">Documents</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach([
                                    'logo' => 'Logo',
                                    'rib' => 'RIB',
                                    'kbis' => 'KBIS',
                                    'id_photo_recto' => "Photo d'identité (Recto)",
                                    'id_photo_verso' => "Photo d'identité (Verso)",
                                    'tva_exemption_doc' => 'Document exemption TVA',
                                    'invoice_terms_doc' => 'Conditions générales de vente'
                                ] as $field => $label)
                                    @if($company->$field)
                                        <a href="{{ asset('/storage/app/public/'.$company->$field) }}" target="_blank" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                            <div class="bg-orange-100 text-orange-600 p-2 rounded-lg mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-800">{{ $label }}</div>
                                                <div class="text-xs text-gray-500 mt-1">Voir le document</div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="flex items-center p-3 bg-gray-100 border border-gray-200 rounded-lg opacity-75">
                                            <div class="bg-gray-200 text-gray-500 p-2 rounded-lg mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-500">{{ $label }}</div>
                                                <div class="text-xs text-gray-400 mt-1">Non fourni</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-block p-4 bg-orange-50 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Aucune information enregistrée</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-8">Vous n'avez pas encore renseigné les informations de votre entreprise. Ajoutez-les pour compléter votre profil.</p>
                    <a href="{{ route('company.create') }}" class="inline-flex items-center bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ajouter mes informations
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
