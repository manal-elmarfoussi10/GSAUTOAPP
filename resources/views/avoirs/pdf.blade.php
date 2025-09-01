<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Avoirs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #ffffff;
            color: #222;
            font-family: Arial, sans-serif;
            padding: 25px;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 16px;
            margin-bottom: 28px;
        }
        .company-block {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .company-logo {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #FF6B00;
        }
        .company-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 1rem;
        }
        .company-info .company-name {
            font-weight: bold;
            font-size: 1.25rem;
            color: #FF6B00;
        }
        .company-info .company-address,
        .company-info .company-phone,
        .company-info .company-email {
            color: #444;
            font-size: 0.97rem;
        }
        .header-meta {
            text-align: right;
        }
        .header-meta .meta-title {
            color: #FF6B00;
            font-size: 1.3rem;
            font-weight: bold;
        }
        .header-meta .meta-date {
            font-size: 1rem;
            color: #888;
            margin-top: 2px;
        }
        .header-meta .meta-count {
            font-size: 1rem;
            color: #444;
            margin-top: 6px;
        }
        .report-title {
            text-align: center;
            font-size: 2.1rem;
            font-weight: bold;
            color: #FF6B00;
            margin: 0 0 26px 0;
            position: relative;
            letter-spacing: 1px;
        }
        .report-title:after {
            content: "";
            display: block;
            margin: 10px auto 0 auto;
            width: 80px;
            border-bottom: 3px solid #FF6B00;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
            font-size: 1rem;
        }
        .items-table th {
            background: #ffedd5;
            color: #FF6B00;
            font-weight: bold;
            padding: 13px 10px;
            border-bottom: 2px solid #FF6B00;
            text-align: left;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .items-table tr:nth-child(odd) {
            background: #fff;
        }
        .footer {
            text-align: center;
            color: #FF6B00;
            font-size: 1.05rem;
            margin-top: 38px;
            padding-top: 18px;
            border-top: 1px solid #e0e0e0;
        }
        /* Responsive */
        @media (max-width: 700px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
            }
            .header-meta {
                text-align: left;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="company-block">
            <div class="company-logo">
                <i class="fas fa-car"></i>
            </div>
          
        </div>
        <div class="header-meta">
            <div class="meta-date">{{ now()->format('d/m/Y H:i') }}</div>
            <div class="meta-count">Total avoirs : {{ count($avoirs) }}</div>
        </div>
    </div>
    <div class="report-title">Détails des Avoirs</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Facture ID</th>
                <th>Année fiscale</th>
                <th>Date de RDV</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($avoirs as $avoir)
                <tr>
                    <td>{{ $avoir->created_at->format('d/m/Y') }}</td>
                    <td>{{ $avoir->facture->client->nom_assure ?? '-' }}</td>
                    <td>{{ number_format($avoir->montant, 2) }} €</td>
                    <td>{{ $avoir->facture_id }}</td>
                    <td>{{ $avoir->created_at->format('Y') }}</td>
                    <td>{{ optional($avoir->facture->client->rdvs->first())->start_time ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        @php $user = auth()->user(); @endphp
        le {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html>
