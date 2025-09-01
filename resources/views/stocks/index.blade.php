@extends('layout')

@section('content')
<style>
    :root {
        --primary: #ff6d00;
        --primary-light: #ff9e40;
        --primary-dark: #c43c00;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border: #dee2e6;
        --success: #28a745;
        --danger: #dc3545;
        --info: #17a2b8;
    }
    
    .stock-container {
        padding: 2rem;
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    
    /* Header Styles */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: var(--primary);
        background: rgba(255, 109, 0, 0.1);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        gap: 0.5rem;
        border: none;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 6px rgba(255, 109, 0, 0.2);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(255, 109, 0, 0.3);
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .form-group {
        flex: 1;
        min-width: 250px;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(255, 109, 0, 0.15);
    }
    
    .export-actions {
        display: flex;
        gap: 0.75rem;
        margin-left: auto;
    }
    
    .btn-export {
        background: white;
        color: var(--gray);
        border: 1px solid var(--border);
        padding: 0.65rem 1rem;
        font-size: 0.85rem;
    }
    
    .btn-export.excel {
        color: var(--success);
        border-color: rgba(40, 167, 69, 0.3);
    }
    
    .btn-export.excel:hover {
        background: rgba(40, 167, 69, 0.05);
    }
    
    .btn-export.pdf {
        color: var(--danger);
        border-color: rgba(220, 53, 69, 0.3);
    }
    
    .btn-export.pdf:hover {
        background: rgba(220, 53, 69, 0.05);
    }
    
    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    
    .table th {
        background-color: #f8f9fa;
        color: var(--gray);
        font-weight: 600;
        text-align: left;
        padding: 1rem 1.25rem;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--border);
    }
    
    .table td {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
        color: var(--dark);
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(255, 109, 0, 0.03);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.8rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-commande { background: rgba(255, 193, 7, 0.15); color: #ff9800; }
    .status-livre { background: rgba(40, 167, 69, 0.15); color: #28a745; }
    .status-pose { background: rgba(0, 123, 255, 0.15); color: #007bff; }
    .status-retour { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
    .status-casse { background: rgba(220, 53, 69, 0.15); color: #dc3545; }
    .status-stock { background: rgba(23, 162, 184, 0.15); color: #17a2b8; }
    .status-attente { background: rgba(111, 66, 193, 0.15); color: #6f42c1; }
    
    .accord-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .accord-yes {
        background: rgba(40, 167, 69, 0.15);
        color: var(--success);
    }
    
    .accord-no {
        background: rgba(220, 53, 69, 0.15);
        color: var(--danger);
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 0.5rem;
        background: white;
        border: 1px solid var(--border);
        color: var(--gray);
        transition: all 0.3s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .btn-edit {
        color: var(--primary);
        border-color: rgba(255, 109, 0, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(255, 109, 0, 0.05);
    }
    
    .btn-delete {
        color: var(--danger);
        border-color: rgba(220, 53, 69, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.05);
    }
    
    /* Stats header */
    .stats-header {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-card {
        flex: 1;
        min-width: 200px;
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--primary);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0.5rem 0;
    }
    
    .stat-label {
        color: var(--gray);
        font-size: 0.9rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .export-actions {
            margin-left: 0;
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="stock-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-boxes"></i>
            Gestion des Stocks
        </h1>
        <a href="{{ route('stocks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            Ajouter un produit
        </a>
    </div>

    

    <div class="filter-section">
        <form method="GET" action="{{ route('stocks.index') }}" class="filter-form">
        
            
            <div class="form-group">
                <select name="statut" onchange="this.form.submit()" class="form-control">
                    <option value="">Tous les statuts</option>
                    @php
                        $statuts = [
                            'À COMMANDER',
                            'COMMANDÉ',
                            'LIVRÉ',
                            'POSÉ',
                            'A RETOURNER',
                            'CASSÉ À LA LIVRAISON',
                            'CASSÉ POSÉ',
                            'RETOURNÉ',
                            'STOCKÉ',
                            'ATTENTE REMBOURSEMENT',
                            'REMBOURSÉ'
                        ];
                    @endphp
                    @foreach($statuts as $statut)
                        <option value="{{ $statut }}" {{ request('statut') === $statut ? 'selected' : '' }}>
                            {{ $statut }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="export-actions">
                <a href="{{ route('stocks.export.excel') }}" class="btn btn-export excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>

            </div>
        </form>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Fournisseur</th>
                        <th>Statut</th>
                        <th>Poseur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                    <tr>
                        <td>
                            {{ $stock->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>{{ $stock->produit->nom ?? 'N/A' }}</td>
                        <td>{{ $stock->fournisseur->nom_societe ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClass = 'status-commande';
                                if (str_contains($stock->statut, 'LIVRÉ')) $statusClass = 'status-livre';
                                if (str_contains($stock->statut, 'POSÉ')) $statusClass = 'status-pose';
                                if (str_contains($stock->statut, 'RETOUR')) $statusClass = 'status-retour';
                                if (str_contains($stock->statut, 'CASSÉ')) $statusClass = 'status-casse';
                                if (str_contains($stock->statut, 'STOCK')) $statusClass = 'status-stock';
                                if (str_contains($stock->statut, 'ATTENTE')) $statusClass = 'status-attente';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $stock->statut }}</span>
                        </td>
                        <td>{{ $stock->poseur->nom ?? 'N/A' }}</td>
                  
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('stocks.edit', $stock) }}" class="btn-action btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('stocks.destroy', $stock) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Affichage de {{ $stocks->count() }} sur {{ $stocks->total() }} produits
        </div>
        <div class="flex space-x-2">
            @if ($stocks->previousPageUrl())
                <a href="{{ $stocks->previousPageUrl() }}" class="px-3 py-1 bg-white border rounded-md text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif
            
            @if ($stocks->nextPageUrl())
                <a href="{{ $stocks->nextPageUrl() }}" class="px-3 py-1 bg-white border rounded-md text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<script>
    // Simple confirmation for delete actions
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Add a subtle animation to status badges on hover
    document.querySelectorAll('.status-badge').forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
</script>
@endsection