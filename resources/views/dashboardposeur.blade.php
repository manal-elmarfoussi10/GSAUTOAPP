@extends('layout')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome">
        <h1>Bienvenue, {{ auth()->user()->first_name }}</h1>
        <p>Votre tableau de bord de poseur vitrage</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-euro-sign"></i>
                CA ANNUEL (HT)
            </div>
            <div class="stat-value">0 €</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                ↑ 12.5%
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-chart-line"></i>
                MARGE (TTC)
            </div>
            <div class="stat-value">25 000 €</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                ↑ 8.2%
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-wallet"></i>
                DÉPENSES
            </div>
            <div class="stat-value">18 500 €</div>
            <div class="stat-change">Dans budget</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                <i class="fas fa-folder-open"></i>
                DOSSIERS
            </div>
            <div class="stat-value">{{ $interventions->count() }}</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                ↑ {{ $interventions->count() }}
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="section-header">
        <i class="fas fa-bolt"></i>
        <h2>Accès rapide</h2>
    </div>

    <div class="actions-grid">
        <a href="{{ route('rdv.calendar') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Mon calendrier</h3>
            <p>Voir mes rendez-vous de pose</p>
            <div class="action-btn">
                <i class="fas fa-eye"></i>
                Consulter
            </div>
        </a>

        <a href="{{ route('poseur.dossiers') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h3>Mes interventions</h3>
            <p>Consulter mes dossiers</p>
            <div class="action-btn">
                <i class="fas fa-folder-open"></i>
                Voir
            </div>
        </a>
    </div>

    <!-- Interventions Section -->
    <div class="section-header">
        <i class="fas fa-clipboard-list"></i>
        <h2>Mes interventions à venir</h2>
    </div>

    <div class="interventions-container">
        <div class="interventions-header">
            <h3>
                <i class="fas fa-calendar-check"></i>
                Prochaines missions
            </h3>
        </div>

        <div class="interventions-list">
            @forelse($interventions as $intervention)
            <div class="intervention-card">
                <div class="intervention-date">
                    <div class="intervention-day">{{ \Carbon\Carbon::parse($intervention->date_heure_debut)->format('d') }}</div>
                    <div class="intervention-month">{{ \Carbon\Carbon::parse($intervention->date_heure_debut)->format('M') }}</div>
                    <div class="intervention-time">
                        {{ \Carbon\Carbon::parse($intervention->date_heure_debut)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($intervention->date_heure_fin)->format('H:i') }}
                    </div>
                </div>
                <div class="intervention-details">
                    <h4>{{ $intervention->client->nom_assure ?? 'N/A' }}</h4>
                    <div class="intervention-address">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $intervention->client->adresse ?? 'Adresse non spécifiée' }}
                    </div>
                    <div class="intervention-phone">
                        <i class="fas fa-phone-alt"></i>
                        {{ $intervention->client->telephone ?? 'Téléphone non spécifié' }}
                    </div>
                    <div class="intervention-comment">
                        {{ $intervention->commentaire ?? 'Aucun commentaire pour cette intervention' }}
                    </div>
                </div>
                <div class="intervention-actions">
                    <a href="{{ route('poseur.dossiers') }}?intervention_id={{ $intervention->id }}" class="action-button primary">
                        <i class="fas fa-info-circle"></i>
                        Détails
                    </a>
                    <a href="{{ route('poseur.dossiers') }}?intervention_id={{ $intervention->id }}#ajout-photo" class="action-button">
                        <i class="fas fa-camera"></i>
                        Photo
                    </a>
                </div>
            </div>
            @empty
            <div class="intervention-card">
                <div class="intervention-details">
                    <p class="no-interventions">Aucune intervention prévue pour le moment</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #FF4B00;
        --primary-light: #FFF2ED;
        --dark: #1F2937;
        --gray: #6B7280;
        --gray-light: #F3F4F6;
        --white: #FFFFFF;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
        background-color: #f9fafb;
    }

    /* Welcome Section */
    .welcome {
        margin-bottom: 20px;
        text-align: center;
    }

    .welcome h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary);
    }

    .welcome p {
        color: var(--gray);
        font-size: 0.95rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 3px solid var(--primary);
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .stat-title {
        color: var(--gray);
        font-size: 0.85rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 3px;
        color: var(--dark);
    }

    .stat-change {
        color: #10B981;
        font-weight: 500;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 25px 0 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-light);
    }

    .section-header h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Quick Actions */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .action-card {
        background: var(--white);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.2s;
        border: 1px solid var(--gray-light);
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(255, 75, 0, 0.1);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .action-icon i {
        color: var(--primary);
        font-size: 1.5rem;
    }

    .action-card h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .action-card p {
        color: var(--gray);
        margin-bottom: 15px;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .action-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        font-size: 0.9rem;
    }

    /* Interventions */
    .interventions-container {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-top: 10px;
    }

    .interventions-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--gray-light);
        background: var(--primary-light);
    }

    .interventions-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--dark);
    }

    .interventions-list {
        padding: 0 20px 20px;
    }

    .intervention-card {
        display: flex;
        padding: 20px 0;
        border-bottom: 1px solid var(--gray-light);
        transition: all 0.2s;
    }

    .intervention-card:last-child {
        border-bottom: none;
    }

    .intervention-card:hover {
        background: var(--primary-light);
    }

    .intervention-date {
        min-width: 90px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255, 75, 0, 0.1);
        border-radius: 8px;
        padding: 10px;
        margin-right: 15px;
    }

    .intervention-day {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }

    .intervention-month {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--dark);
        font-weight: 600;
        margin: 3px 0;
    }

    .intervention-time {
        font-size: 0.85rem;
        color: var(--gray);
        font-weight: 500;
    }

    .intervention-details {
        flex-grow: 1;
        padding-right: 15px;
    }

    .intervention-details h4 {
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: var(--dark);
    }

    .intervention-address,
    .intervention-phone {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        margin-bottom: 6px;
        font-size: 0.9rem;
    }

    .intervention-comment {
        background: rgba(255, 255, 255, 0.7);
        padding: 10px;
        border-radius: 8px;
        border-left: 3px solid var(--gray-light);
        font-size: 0.9rem;
        color: var(--dark);
        margin-top: 10px;
    }

    .intervention-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 120px;
    }

    .action-button {
        background: none;
        border: 1px solid var(--gray-light);
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        color: inherit;
        font-size: 0.85rem;
    }

    .action-button.primary {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .action-button:hover {
        background: var(--primary-light);
        border-color: var(--primary);
    }

    .action-button.primary:hover {
        background: #E04400;
        border-color: #E04400;
    }

    .no-interventions {
        text-align: center;
        color: var(--gray);
        padding: 20px 0;
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .intervention-card {
            flex-direction: column;
        }

        .intervention-date {
            flex-direction: row;
            justify-content: flex-start;
            gap: 15px;
            margin-right: 0;
            margin-bottom: 15px;
            min-width: auto;
            width: 100%;
        }

        .intervention-actions {
            flex-direction: row;
            flex-wrap: wrap;
            margin-top: 15px;
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .dashboard-container {
            padding: 10px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-value {
            font-size: 1.3rem;
        }

        .action-card {
            padding: 15px;
        }

        .intervention-date {
            padding: 8px;
        }
    }
</style>

<script>
    // Animation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes
        const cards = document.querySelectorAll('.stat-card, .action-card, .intervention-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(15px)';
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
</script>
@endsection
