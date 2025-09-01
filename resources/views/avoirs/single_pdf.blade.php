<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avoir #{{ $avoir->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 20px 40px;
            background-color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .company-info {
            font-size: 12px;
            line-height: 1.5;
        }

        .devis-info {
            text-align: right;
        }

        .devis-info h2 {
            margin: 0;
            font-size: 20px;
            color: #0ea5e9;
        }

        .client-info {
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.5;
        }

        .prestations-title {
            font-weight: bold;
            margin: 20px 0 10px;
            text-transform: uppercase;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }

        table th, table td {
            border-bottom: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #e0f2fe;
            font-weight: bold;
            color: #0c4a6e;
        }

        .totals {
            width: 50%;
            margin-left: auto;
            margin-top: 20px;
        }

        .totals td {
            padding: 6px 8px;
        }

        .footer {
            font-size: 11px;
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            color: #64748b;
        }

        .signature {
            margin-top: 40px;
        }

        .signature div {
            width: 45%;
            display: inline-block;
            text-align: center;
            margin-top: 40px;
            border-top: 1px dashed #94a3b8;
            padding-top: 10px;
        }

        .bank-info {
            font-size: 11px;
            margin-top: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="company-info">
        @if($company)
            <strong>{{ $company->commercial_name ?? $company->name }}</strong><br>
            {{ $company->address }}<br>
            {{ $company->postal_code }} {{ $company->city }}<br>
            {{ $company->email }}<br>
            {{ $company->phone }}
        @endif
    </div>
    <div class="devis-info">
        <h2>AVOIR</h2>
        <p>#{{ $avoir->id }}</p>
        <p>{{ $company->city ?? '' }}, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
</div>

<div class="client-info">
    <strong>{{ $avoir->facture->client->prenom }} {{ $avoir->facture->client->nom_assure }}</strong><br>
    {{ $avoir->facture->client->adresse }}<br>
    {{ $avoir->facture->client->code_postal }} {{ $avoir->facture->client->ville }}
</div>

<div class="prestations-title">Détails liés à la facture #{{ $avoir->facture->id }}</div>
<table>
    <thead>
    <tr>
        <th>Description</th>
        <th>Prix unitaire</th>
        <th>Qté</th>
        <th>Montant HT</th>
    </tr>
    </thead>
    <tbody>
    @foreach($avoir->facture->items as $item)
        <tr>
            <td>{{ $item->produit }}</td>
            <td>{{ number_format($item->prix_unitaire, 2, ',', ' ') }} €</td>
            <td>{{ $item->quantite }}</td>
            <td>{{ number_format($item->total_ht, 2, ',', ' ') }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="totals">
    <tr>
        <td>Total HT Facturé</td>
        <td style="text-align:right;">{{ number_format($avoir->facture->total_ht, 2, ',', ' ') }} €</td>
    </tr>
    <tr>
        <td>Montant Avoir</td>
        <td style="text-align:right;"><strong>{{ number_format($avoir->montant, 2, ',', ' ') }} €</strong></td>
    </tr>
</table>

<div class="bank-info">
    <p>Modalités de remboursement : Par virement bancaire à l’ordre de {{ $company->commercial_name ?? $company->name }}</p>
    <p>IBAN : {{ $company->iban ?? '...' }}<br>
       BIC : {{ $company->bic ?? '...' }}</p>
</div>

<div class="signature">
    <div>Signature du client</div>
    <div>{{ $company->commercial_name ?? $company->name }}</div>
</div>

<div class="footer">
    {{ $company->commercial_name ?? $company->name }} — SIRET: {{ $company->siret }} — TVA: {{ $company->tva }}<br>
    Code APE: {{ $company->ape }} — RCS: {{ $company->rcs_number }} {{ $company->rcs_city }}
</div>

</body>
</html>