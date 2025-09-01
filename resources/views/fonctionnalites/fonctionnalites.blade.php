@extends('layout')

@section('content')
<div class="container mx-auto px-6 py-20 space-y-28">

    <!-- Titre principal -->
    <div class="text-center">
        <h1 class="text-5xl font-extrabold text-[#FF4B00] tracking-tight mb-6">Fonctionnalités GAA Gestion</h1>
        <p class="text-gray-600 text-xl max-w-4xl mx-auto leading-relaxed">
            Une suite complète pour piloter votre activité, automatiser vos documents, et optimiser votre temps.
        </p>
    </div>

    <!-- Liste des fonctionnalités -->
    @php
        $features = [
            [
                'title' => 'Générateur de cession de créance',
                'image' => 'images/image1.png',
                'alt' => 'Générateur de créance',
                'text' => "Générez facilement vos cessions de créance, ordres de réparation et déclarations de bris de glace. Des modèles prêts à l’emploi, personnalisables et exportables.",
                'reverse' => false
            ],
            [
                'title' => 'Devis et Factures',
                'image' => 'images/image2.png',
                'alt' => 'Devis et factures',
                'text' => "Créez des devis en quelques clics et transformez-les en factures conformes à la législation. Suivi automatique de vos produits, prix et taux de TVA.",
                'reverse' => true
            ],
            [
                'title' => 'Tableau de bord',
                'image' => 'images/image3.png',
                'alt' => 'Tableau de bord',
                'text' => "Suivez en temps réel vos indicateurs clés : CA, paiements, marges et rendez-vous. Visualisation claire via des graphiques dynamiques.",
                'reverse' => false
            ],
            [
                'title' => 'Signature électronique',
                'image' => 'images/image4.png',
                'alt' => 'Signature électronique',
                'text' => "Faites signer tous vos documents à distance de manière sécurisée. Vos clients n'ont plus besoin de se déplacer.",
                'reverse' => true
            ],
            [
                'title' => 'Calendrier intelligent',
                'image' => 'images/image5.png',
                'alt' => 'Calendrier intelligent',
                'text' => "Planifiez efficacement vos rendez-vous avec une vue par technicien, couleur et calendrier mensuel ou hebdomadaire. Tout est synchronisé.",
                'reverse' => false
            ]
        ];
    @endphp

    @foreach ($features as $feature)
    <section class="bg-white rounded-3xl shadow-md hover:shadow-lg transition-shadow duration-300 p-12 md:p-16">
        <div class="flex flex-col md:flex-row {{ $feature['reverse'] ? 'md:flex-row-reverse' : '' }} items-center gap-14">

            <!-- Image -->
            <div class="md:w-1/2 flex justify-center">
                <img src="{{ asset($feature['image']) }}"
                     alt="{{ $feature['alt'] }}"
                     class="w-full max-w-[540px] rounded-2xl">
            </div>

            <!-- Texte -->
            <div class="md:w-1/2">
                <h2 class="text-4xl font-extrabold text-[#FF4B00] mb-6 leading-tight">{{ $feature['title'] }}</h2>
                <p class="text-gray-700 text-lg leading-relaxed">
                    {{ $feature['text'] }}
                </p>
            </div>
        </div>
    </section>
    @endforeach

</div>
@endsection
