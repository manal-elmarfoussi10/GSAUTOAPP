<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Stocks</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Liste des stocks</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Fournisseur</th>
                <th>Statut</th>
                <th>Poseur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($stock->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $stock->produit->nom ?? '' }}</td>
                    <td>{{ $stock->fournisseur->nom_societe ?? '' }}</td>
                    <td>{{ $stock->statut }}</td>
                    <td>{{ $stock->poseur->nom ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>