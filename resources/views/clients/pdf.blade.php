<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dossier Client - {{ $client->prenom }} {{ $client->nom_assure }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header p { font-size: 14px; color: #666; }
        .section { margin-bottom: 15px; }
        .section-title { 
            background-color: #f0f0f0; 
            padding: 5px 10px; 
            font-weight: bold; 
            margin-bottom: 8px;
            border-left: 4px solid #0891b2;
        }
        .info-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 8px; 
        }
        .info-item { margin-bottom: 5px; }
        .label { font-weight: bold; color: #555; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $client->prenom }} {{ $client->nom_assure }}</h1>
        <p>Dossier créé le: {{ $client->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Client</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nom:</span> {{ $client->nom_assure }}
            </div>
            <div class="info-item">
                <span class="label">Prénom:</span> {{ $client->prenom }}
            </div>
            <div class="info-item">
                <span class="label">Email:</span> {{ $client->email }}
            </div>
            <div class="info-item">
                <span class="label">Téléphone:</span> {{ $client->telephone }}
            </div>
            <div class="info-item">
                <span class="label">Adresse:</span> {{ $client->adresse }}
            </div>
            <div class="info-item">
                <span class="label">Ville:</span> {{ $client->code_postal }} {{ $client->ville }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Véhicule</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Immatriculation:</span> {{ $client->plaque }}
            </div>
            <div class="info-item">
                <span class="label">Kilométrage:</span> {{ $client->kilometrage }} km
            </div>
            <div class="info-item">
                <span class="label">Type de vitrage:</span> {{ $client->type_vitrage }}
            </div>
            <div class="info-item">
                <span class="label">Ancien modèle:</span> {{ $client->ancien_modele_plaque }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assurance</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nom:</span> {{ $client->nom_assurance }}
            </div>
            <div class="info-item">
                <span class="label">N° Police:</span> {{ $client->numero_police }}
            </div>
            <div class="info-item">
                <span class="label">N° Sinistre:</span> {{ $client->numero_sinistre }}
            </div>
            <div class="info-item">
                <span class="label">Autre assurance:</span> {{ $client->autre_assurance }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails du Sinistre</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Date du sinistre:</span> 
                @if($client->date_sinistre)
                    {{ \Carbon\Carbon::parse($client->date_sinistre)->format('d/m/Y') }}
                @else
                    -
                @endif
            </div>
            <div class="info-item">
                <span class="label">Date déclaration:</span> 
                @if($client->date_declaration)
                    {{ \Carbon\Carbon::parse($client->date_declaration)->format('d/m/Y') }}
                @else
                    -
                @endif
            </div>
            <div class="info-item">
                <span class="label">Raison:</span> {{ $client->raison }}
            </div>
            <div class="info-item">
                <span class="label">Réparation:</span> {{ $client->reparation ? 'Oui' : 'Non' }}
            </div>
            <div class="info-item">
                <span class="label">Connu par:</span> {{ $client->connu_par }}
            </div>
            <div class="info-item">
                <span class="label">Adresse pose:</span> {{ $client->adresse_pose }}
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <span class="label">Précisions:</span> {{ $client->precision }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Statut</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Statut:</span> {{ $client->statut }}
            </div>
            <div class="info-item">
                <span class="label">Statut interne:</span> {{ $client->statut_interne }}
            </div>
        </div>
    </div>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y H:i') }} 
    </div>
</body>
</html>