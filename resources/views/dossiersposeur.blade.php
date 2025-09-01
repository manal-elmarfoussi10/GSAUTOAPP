@extends('layout')

@section('content')
<div class="dossiers-container">
    <!-- En-tête avec titre et filtre -->
    <div class="dossiers-header">
        <div class="header-left">
            <h1 class="dossiers-title">
                <i class="fas fa-tools"></i>
                Mes Interventions
            </h1>
            <p class="dossiers-subtitle">Gérez vos dossiers clients et ajoutez des commentaires/photos</p>
        </div>

        <div class="header-actions">
            <div class="search-box">
                <input type="text" placeholder="Rechercher une intervention...">
                <i class="fas fa-search"></i>
            </div>
            <div class="filter-dropdown">
                <select>
                    <option>Toutes les interventions</option>
                    <option>À venir</option>
                    <option>Terminées</option>
                    <option>En attente</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Grille des interventions -->
    <div class="interventions-grid">
        @forelse ($interventions as $intervention)
        <div class="intervention-card">
            <!-- En-tête de la carte -->
            <div class="card-header">
                <div class="client-info">
                    <div class="client-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="client-name">{{ $intervention->client->nom_assure ?? 'Client non spécifié' }}</h3>
                        <p class="intervention-date">
                            <i class="far fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="status-badge completed">
                    <i class="fas fa-check"></i>
                    Terminée
                </div>
            </div>

            <!-- Détails de l'intervention -->
            <div class="card-details">
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $intervention->client->adresse ?? 'Adresse non spécifiée' }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>{{ $intervention->client->telephone ?? 'Téléphone non spécifié' }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Réf: INT-{{ $intervention->id }}</span>
                </div>
            </div>

            <!-- Commentaire existant -->
            <div class="existing-comment">
                <h4><i class="far fa-comment-dots"></i> Commentaire actuel</h4>
                <p>{{ $intervention->commentaire ?? 'Aucun commentaire pour cette intervention' }}</p>
            </div>

            <!-- Photo existante -->
            @if ($intervention->photo)
            <div class="existing-photo">
                <h4><i class="fas fa-camera"></i> Photo actuelle</h4>
                <div class="photo-container">
                    <img src="{{ asset('storage/' . $intervention->photo) }}"
                         alt="Photo de l'intervention"
                         class="intervention-photo"
                         onclick="openLightbox(this)">
                </div>
            </div>
            @endif

            <!-- Formulaire d'ajout -->
            <form action="{{ route('poseur.comment', $intervention->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="comment-form">
                @csrf

                <h4><i class="fas fa-plus-circle"></i> Ajouter des informations</h4>

                <div class="form-group">
                    <label for="commentaire-{{ $intervention->id }}">
                        <i class="far fa-comment"></i> Nouveau commentaire
                    </label>
                    <textarea name="commentaire"
                              id="commentaire-{{ $intervention->id }}"
                              class="form-textarea"
                              rows="3"
                              placeholder="Écrire un commentaire..."></textarea>
                </div>

                <div class="form-group">
                    <label for="photo-{{ $intervention->id }}">
                        <i class="fas fa-camera"></i> Joindre une photo
                    </label>
                    <div class="file-upload">
                        <label for="photo-{{ $intervention->id }}" class="upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Choisir un fichier
                        </label>
                        <input type="file"
                               name="photo"
                               id="photo-{{ $intervention->id }}"
                               class="file-input">
                        <span class="file-name">Aucun fichier sélectionné</span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </form>
        </div>
        @empty
        <div class="no-interventions">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZD0iTTUwIDBDMjIuNCAwIDAgMjIuNCAwIDUwczIyLjQgNTAgNTAgNTAgNTAtMjIuNCA1MC01MFM3Ny42IDAgNTAgMHptMCA5MGMtMjIuMSAwLTQwLTE3LjktNDAtNDBzMTcuOS00MCA0MC00MCA0MCAxNy45IDQwIDQwLTE3LjkgNDAtNDAgNDB6IiBmaWxsPSIjZmY0YjAwIi8+PHBhdGggZD0iTTYwIDMwSDQwdjIwaDIwVjMwek00MCA1MGgyMHYyMEg0MFY1MHoiIGZpbGw9IiNmZjRiMDAiLz48L3N2Zz4=" alt="Aucune intervention" class="empty-icon">
            <h3>Aucune intervention trouvée</h3>
            <p>Vous n'avez aucune intervention enregistrée pour le moment</p>
        </div>
        @endforelse
    </div>

    <!-- Lightbox pour les photos -->
    <div class="lightbox" id="lightbox">
        <span class="close-btn" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-content" id="lightbox-img">
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
        --success: #10B981;
    }

    .dossiers-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9fafb;
        min-height: 100vh;
    }

    /* En-tête */
    .dossiers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .dossiers-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .dossiers-title i {
        color: var(--primary);
    }

    .dossiers-subtitle {
        color: var(--gray);
        font-size: 1rem;
        margin-top: 5px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border-radius: 50px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 75, 0, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .filter-dropdown select {
        padding: 12px 40px 12px 20px;
        border-radius: 50px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        appearance: none;
        background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") no-repeat right 15px center;
        background-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-dropdown select:focus {
        outline: none;
        border-color: var(--primary);
    }

    /* Message de succès */
    .success-message {
        background: var(--success);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeIn 0.5s;
    }

    /* Grille des interventions */
    .interventions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
    }

    /* Carte d'intervention */
    .intervention-card {
        background: var(--white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }

    .intervention-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: linear-gradient(135deg, #fff 0%, var(--primary-light) 100%);
        border-bottom: 1px solid #eee;
    }

    .client-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .client-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .client-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .intervention-date {
        color: var(--gray);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-badge.completed {
        background: #ECFDF5;
        color: #10B981;
    }

    /* Détails */
    .card-details {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .detail-item i {
        color: var(--primary);
        width: 20px;
        text-align: center;
    }

    /* Commentaire existant */
    .existing-comment, .existing-photo {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .existing-comment h4, .existing-photo h4 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .existing-comment h4 i, .existing-photo h4 i {
        color: var(--primary);
    }

    .existing-comment p {
        color: var(--gray);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Photo existante */
    .photo-container {
        margin-top: 15px;
    }

    .intervention-photo {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .intervention-photo:hover {
        transform: scale(1.03);
    }

    /* Formulaire */
    .comment-form {
        padding: 20px;
    }

    .comment-form h4 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        margin-bottom: 15px;
        color: var(--dark);
    }

    .comment-form h4 i {
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--dark);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        resize: vertical;
        transition: all 0.3s;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 75, 0, 0.1);
    }

    .file-upload {
        display: flex;
        flex-direction: column;
    }

    .upload-btn {
        background: var(--primary-light);
        color: var(--primary);
        border: 1px dashed var(--primary);
        padding: 10px 15px;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .upload-btn:hover {
        background: rgba(255, 75, 0, 0.1);
    }

    .file-input {
        display: none;
    }

    .file-name {
        margin-top: 8px;
        font-size: 0.85rem;
        color: var(--gray);
        font-style: italic;
    }

    .submit-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
        width: 100%;
        justify-content: center;
    }

    .submit-btn:hover {
        background: #E04400;
    }

    /* Aucune intervention */
    .no-interventions {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .no-interventions img {
        width: 100px;
        height: 100px;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .no-interventions h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .no-interventions p {
        color: var(--gray);
        max-width: 500px;
        margin: 0 auto;
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }

    .lightbox-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 800px;
        animation: zoom 0.3s;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .close-btn:hover {
        color: var(--primary);
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoom {
        from { transform: scale(0.8); }
        to { transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .interventions-grid {
            grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .dossiers-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .search-box {
            width: 100%;
        }

        .interventions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes
        const cards = document.querySelectorAll('.intervention-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 150 * index);
        });

        // Gestion des noms de fichiers
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0] ? this.files[0].name : 'Aucun fichier sélectionné';
                this.parentElement.querySelector('.file-name').textContent = fileName;
            });
        });
    });

    // Lightbox pour les photos
    function openLightbox(element) {
        document.getElementById('lightbox').style.display = 'block';
        document.getElementById('lightbox-img').src = element.src;
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }

    // Fermer la lightbox en cliquant à l'extérieur
    window.onclick = function(event) {
        const lightbox = document.getElementById('lightbox');
        if (event.target === lightbox) {
            closeLightbox();
        }
    };
</script>
@endsection
