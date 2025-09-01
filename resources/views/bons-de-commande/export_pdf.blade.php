<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF - Bons de Commande</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f3f3; }
    </style>
</head>
<body>
    <h2>Bons de commande</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Client</th>
                <th>Fournisseur</th>
                <th>Titre</th>
                <th>Total HT</th>
                <th>TVA</th>
                <th>Total TTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bons as $bon)
            <tr>
                <td>{{ $bon->id }}</td>
                <td>{{ \Carbon\Carbon::parse($bon->date_commande)->format('d/m/Y') }}</td>
                <td>{{ $bon->client?->nom ?? '-' }}</td>
                <td>{{ $bon->fournisseur?->nom_societe ?? '-' }}</td>
                <td>{{ $bon->titre }}</td>
                <td>{{ number_format($bon->total_ht, 2, ',', ' ') }}</td>
                <td>{{ $bon->tva }}%</td>
                <td>{{ number_format($bon->total_ttc, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>