<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export des Dépenses</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #FF6B35; margin-bottom: 5px; }
        .header p { color: #666; }
        .logo { text-align: center; margin-bottom: 15px; }
        .info { margin-bottom: 20px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background-color: #FF6B35; color: white; text-align: left; padding: 8px; }
        .table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #777; font-size: 12px; }
        .status-paid { color: #2A9D8F; }
        .status-pending { color: #E9C46A; }
        .status-unpaid { color: #E76F51; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; border-top: 2px solid #FF6B35; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Export des Dépenses</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="info">
        <p>Total des dépenses: {{ $expenses->count() }} | 
           Montant total TTC: {{ number_format($totalTtc, 2, ',', ' ') }} €</p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Réf. Client</th>
                <th>Client</th>
                <th>Fournisseur</th>
                <th>Statut</th>
                <th class="text-right">HT (€)</th>
                <th class="text-right">TTC (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->date->format('d/m/Y') }}</td>
                <td>#{{ $expense->client->reference_client }}</td>
                <td>{{ $expense->client->prenom }} {{ $expense->client->nom_assure }}</td>
                <td>{{ $expense->fournisseur->nom_societe }}</td>
                <td class="status-{{ $expense->paid_status }}">
                    @if($expense->paid_status == 'paid')
                        Payé
                    @elseif($expense->paid_status == 'pending')
                        En attente
                    @else
                        Non payé
                    @endif
                </td>
                <td class="text-right">{{ number_format($expense->ht_amount, 2, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($expense->ttc_amount, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total TTC:</td>
                <td colspan="2" class="text-right">
                    {{ number_format($totalTtc, 2, ',', ' ') }} €
                </td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} - Système de Gestion des Dépenses</p>
    </div>
</body>
</html>