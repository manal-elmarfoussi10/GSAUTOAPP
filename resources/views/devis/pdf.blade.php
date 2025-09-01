<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: orange; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        .header { margin-bottom: 20px; }
        .right { text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h1>DEVIS {{ $devis->id }}</h1>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($devis->date_devis)->format('d/m/Y') }}</p>
    <p><strong>Client:</strong> {{ $devis->client->nom_assure }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total HT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($devis->items as $item)
            <tr>
                <td>{{ $item->produit }}</td>
                <td class="right">{{ number_format($item->prix_unitaire, 2) }} €</td>
                <td class="right">{{ $item->quantite }}</td>
                <td class="right">{{ number_format($item->total_ht, 2) }} €</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br><br>

<table>
    <tr>
        <th>Total HT</th>
        <td class="right">{{ number_format($devis->total_ht, 2) }} €</td>
    </tr>
    <tr>
        <th>TVA ({{ $devis->tva }}%)</th>
        <td class="right">{{ number_format($devis->total_tva, 2) }} €</td>
    </tr>
    <tr>
        <th>Total TTC</th>
        <td class="right">{{ number_format($devis->total_ttc, 2) }} €</td>
    </tr>
</table>

</body>
</html>