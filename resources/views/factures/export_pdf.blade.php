<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 30px;
            background-color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .logo {
            max-height: 60px;
        }

        .company-info {
            font-size: 13px;
            line-height: 1.4;
        }

        h1 {
            font-size: 20px;
            text-align: center;
            color: #0ea5e9;
            margin-bottom: 20px;
            border-top: 2px solid #0ea5e9;
            border-bottom: 2px solid #0ea5e9;
            padding: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
        }

        .footer {
            font-size: 11px;
            text-align: center;
            margin-top: 50px;
            color: #6b7280;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div>
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
        @endif
    </div>
    <div class="company-info">
        <strong>{{ $company->name }}</strong><br>
        {{ $company->address }}<br>
        {{ $company->phone }}<br>
        {{ $company->email }}
    </div>
    <div style="text-align: right; font-size: 12px;">
        Généré le {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<h1>Liste des Factures</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Date</th>
            <th>Total HT</th>
            <th>TVA</th>
            <th>Total TTC</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach($factures as $facture)
            <tr>
                <td>{{ $facture->numero }}</td>
                <td>{{ $facture->client->nom ?? $facture->client->prenom ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</td>
                <td>{{ number_format($facture->total_ht, 2, ',', ' ') }} €</td>
                <td>{{ number_format($facture->total_tva, 2, ',', ' ') }} €</td>
                <td>{{ number_format($facture->total_ttc, 2, ',', ' ') }} €</td>
                <td>{{ $facture->is_paid ? 'Payée' : 'Non payée' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    {{ $company->name }} — Généré automatiquement via la plateforme
</div>

</body>
</html>
