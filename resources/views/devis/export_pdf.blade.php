<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Devis - {{ $company->commercial_name }}</title>
    <style>
        /* Base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            background: #f9fafb;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Header */
        .header {
            background: #1e293b;
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .company-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .company-logo {
            height: 80px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
        }
        
        .company-text {
            flex: 1;
        }
        
        .company-text h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #f97316;
        }
        
        .company-details {
            font-size: 12px;
            opacity: 0.8;
            line-height: 1.6;
        }
        
        .report-info {
            text-align: right;
        }
        
        .report-title {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Table styles */
        .table-container {
            padding: 25px 30px;
            flex: 1;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background: #f8fafc;
        }
        
        th {
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        tbody tr:hover {
            background-color: #f8fafc;
        }
        
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        .currency {
            text-align: right;
            font-family: monospace;
        }
        
        /* Footer */
        .footer {
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            margin-top: auto;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .company-info-footer {
            text-align: left;
            flex: 1;
            min-width: 250px;
        }
        
        .page-info {
            flex: 1;
            min-width: 150px;
        }
        
        .document-info {
            flex: 1;
            min-width: 200px;
            text-align: right;
        }
        
        .footer-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 10px 0;
        }
        
        /* Utility classes */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-orange {
            color: #f97316;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .text-sm {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">

        
        <!-- Table -->
        <div class="table-container">
            <h2 class="mb-10">Détail des devis</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Référence</th>
                        <th>Client</th>
                        <th class="text-right">Total HT</th>
                        <th class="text-right">Total TVA</th>
                        <th class="text-right">Total TTC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devis as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->date_devis)->format('d/m/Y') }}</td>
                        <td>DEV-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $item->client->nom_assure ?? '-' }}</td>
                        <td class="currency">{{ number_format($item->total_ht, 2) }} €</td>
                        <td class="currency">{{ number_format($item->total_tva, 2) }} €</td>
                        <td class="currency text-orange">{{ number_format($item->total_ttc, 2) }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Totals -->
            <table class="mt-20">
                <tr>
                    <td class="text-right" style="width: 70%; padding-right: 15px; font-weight: 600;">Total Général HT:</td>
                    <td class="currency" style="font-weight: 600; border-top: 1px solid #e2e8f0;">{{ number_format($devis->sum('total_ht'), 2) }} €</td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-right: 15px; font-weight: 600;">Total TVA:</td>
                    <td class="currency" style="font-weight: 600;">{{ number_format($devis->sum('total_tva'), 2) }} €</td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-right: 15px; font-weight: 600;">Total Général TTC:</td>
                    <td class="currency text-orange" style="font-weight: 600; border-top: 1px solid #e2e8f0; font-size: 14px;">{{ number_format($devis->sum('total_ttc'), 2) }} €</td>
                </tr>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="company-info-footer">
                    <strong>{{ $company->commercial_name ?? $company->name }}</strong><br>
                    {{ $company->address }}, {{ $company->postal_code }} {{ $company->city }}<br>
                    Tél: {{ $company->phone }} | Email: {{ $company->email }}
                </div>
                
                <div class="page-info">
                    Page 1 sur 1<br>
                    <span class="text-sm">Document généré le {{ date('d/m/Y') }}</span>
                </div>
                
                <div class="document-info">
                    SIRET: {{ $company->siret }}<br>
                    TVA: {{ $company->tva }}<br>
                    APE: {{ $company->ape }}
                </div>
            </div>
            
            <div class="footer-divider"></div>
            
          
        </div>
    </div>
</body>
</html>