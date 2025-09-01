@extends('layout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — Super Admin</title>

    {{-- Icons & libs --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <style>
        :root {
            --primary: #FF4B00;
            --primary-light: #ff7b40;
            --primary-extra-light: #fff1ec;
            --success: #10b981;
            --warning: #f59e0b;
        }
        .custom-dashboard-container { opacity:0; transform: translateY(20px); animation: fadeIn .8s ease forwards; }
        @keyframes fadeIn { to { opacity:1; transform: translateY(0); } }

        .custom-stat-card { transition: transform .3s ease, box-shadow .3s ease; border-left: 4px solid var(--primary); position: relative; overflow: hidden; cursor:pointer; }
        .custom-stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05); }
        .custom-stat-card::before { content:''; position:absolute; top:0; right:0; width:80px; height:80px; background:var(--primary-extra-light); border-radius:0 0 0 100%; z-index:0; transition: all .4s ease; }
        .custom-stat-card:hover::before { width:100%; height:100%; border-radius:1rem; }

        .custom-stat-icon { width:48px; height:48px; background:var(--primary-extra-light); border-radius:12px; display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:20px; transition: all .3s ease; z-index:1; }
        .custom-stat-card:hover .custom-stat-icon { transform: scale(1.1); background:var(--primary); color:#fff; }
        .custom-stat-card:hover .stat-value { color:var(--primary); }

        .custom-progress-bar { height:6px; background:#e2e8f0; border-radius:3px; overflow:hidden; margin-top:8px; }
        .custom-progress-fill { height:100%; border-radius:3px; background:var(--primary); transition: width 1s ease; }

        .custom-insurance-icon { width:32px; height:32px; background:var(--primary-extra-light); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--primary); transition: all .3s ease; }
        tr:hover .custom-insurance-icon { background:var(--primary); color:#fff; }

        .custom-loading-spinner { display:none; position:fixed; inset:0; background:rgba(255,255,255,.8); z-index:1000; justify-content:center; align-items:center; }
        .custom-spinner { width:50px; height:50px; border:5px solid var(--primary-extra-light); border-top:5px solid var(--primary); border-radius:50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen p-6">
    {{-- Loading overlay --}}
    <div class="custom-loading-spinner" id="loadingSpinner">
        <div class="custom-spinner"></div>
    </div>

    <div class="container mx-auto px-4 py-6 custom-dashboard-container">
        {{-- Header --}}
        <div class="flex justify-between items-center py-5 mb-6 border-b border-gray-300">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-3 transition-transform hover:scale-102">
                <i class="fas fa-chart-line"></i>
                Tableau de <span class="text-orange-500">Bord</span> — Super Admin
            </h1>
            <div class="flex gap-4">
                <button class="flex items-center gap-2 px-4 py-2 rounded-lg font-semibold border border-gray-300 text-gray-700 hover:bg-gray-100 hover:border-gray-500 transition-all shadow-sm" id="exportBtn">
                    <i class="fas fa-download"></i> Exporter PDF
                </button>
                <button class="flex items-center gap-2 px-4 py-2 rounded-lg font-semibold bg-orange-500 text-white hover:bg-orange-600 hover:-translate-y-0.5 transition-all shadow-sm hover:shadow-md" id="refreshBtn">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
            </div>
        </div>

        {{-- Stat Cards (we reuse your slots, with super-admin meaning) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card 1: Total unités (mapped to $totalHT) --}}
            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Unités totales</div>
                        <div class="text-2xl font-bold text-gray-800 stat-value">{{ number_format($totalHT ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i>
                            <span>{{ number_format(($nouveauxDossiers ?? 0), 0, ',', ' ') }}</span> nouvelles ce mois
                        </div>
                    </div>
                    <div class="custom-stat-icon"><i class="fas fa-layer-group"></i></div>
                </div>
            </div>

            {{-- Card 2: Total sociétés (mapped to $marge) --}}
            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Sociétés</div>
                        <div class="text-2xl font-bold text-gray-800 stat-value">{{ number_format($marge ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i>
                            <span>{{ number_format($depenses ?? 0, 0, ',', ' ') }}</span> nouvelles ce mois
                        </div>
                    </div>
                    <div class="custom-stat-icon"><i class="fas fa-building"></i></div>
                </div>
            </div>

            {{-- Card 3: Nouvelles sociétés ce mois (mapped to $depenses) --}}
            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Nouvelles sociétés</div>
                        <div class="text-2xl font-bold text-gray-800 stat-value">{{ number_format($depenses ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-gray-500 text-sm"><i class="fas fa-info-circle"></i> Depuis le 1er du mois</div>
                    </div>
                    <div class="custom-stat-icon"><i class="fas fa-user-plus"></i></div>
                </div>
            </div>

            {{-- Card 4: Faible solde (mapped to $dossiersActifs) --}}
            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Sociétés à faible solde</div>
                        <div class="text-2xl font-bold text-gray-800 stat-value">{{ number_format($dossiersActifs ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> <span>{{ number_format($nouveauxDossiers ?? 0, 0, ',', ' ') }}</span> ce mois
                        </div>
                    </div>
                    <div class="custom-stat-icon"><i class="fas fa-battery-quarter"></i></div>
                </div>
            </div>
        </div>

        {{-- Charts + Recent list --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Chart: New companies per month --}}
            <div class="bg-white rounded-xl p-6 shadow-md lg:col-span-2 transition-transform hover:-translate-y-1">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-gray-800">Nouvelles sociétés / mois</h2>
                    <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-calendar"></i> {{ now()->year }}
                    </button>
                </div>
                <div class="h-80 relative">
                    <canvas id="chartCompanies"></canvas>
                </div>
            </div>

            {{-- Recent companies --}}
            <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1">
                <div class="font-semibold mb-2">Nouvelles sociétés</div>
                <ul class="space-y-2 text-sm">
                    @forelse($recentCompanies ?? [] as $rc)
                        <li class="flex justify-between">
                            <span>{{ $rc->name }}</span>
                            <span class="text-gray-400">{{ $rc->created_human ?? '—' }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">Aucune donnée</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Chart: Top companies by units --}}
        <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1 mb-8">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">Top sociétés (unités)</h2>
                <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-trophy"></i> Top 8
                </button>
            </div>
            <div class="h-80 relative">
                <canvas id="chartTopUnits"></canvas>
            </div>
        </div>

        {{-- “Low units” table (reusing your table design) --}}
        <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1 mb-8">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">Sociétés à faible solde</h2>
                <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors" id="exportTableBtn">
                    <i class="fas fa-download"></i> Exporter Excel
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="lowUnitsTable">
                    <thead>
                        <tr>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Société</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Email</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Unités</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($statsParAssurance ?? []) as $c)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b border-gray-200 text-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="custom-insurance-icon"><i class="fas fa-building"></i></div>
                                        {{ $c->name }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 text-gray-700">{{ $c->email }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 font-semibold {{ ($c->units ?? 0) < 3 ? 'text-red-600' : 'text-amber-600' }}">
                                    {{ $c->units ?? 0 }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200">
                                    <a href="{{ route('superadmin.companies.edit', $c->id) }}" class="text-orange-600 hover:underline">Ajuster</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-4 text-gray-400" colspan="4">Aucune société à faible solde.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // PDF export of dashboard
        function exportToPDF() {
            const spinner = document.getElementById('loadingSpinner');
            spinner.style.display = 'flex';
            setTimeout(() => {
                html2canvas(document.querySelector('.container')).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('dashboard-superadmin-' + new Date().toISOString().slice(0,10) + '.pdf');
                    spinner.style.display = 'none';
                }).catch(() => spinner.style.display = 'none');
            }, 350);
        }

        // Excel export for the low-units table
        function exportTableToExcel() {
            const table = document.getElementById('lowUnitsTable');
            const wb = XLSX.utils.table_to_book(table, {sheet: 'Faible solde'});
            XLSX.writeFile(wb, 'low-units-' + new Date().toISOString().slice(0,10) + '.xlsx');
        }

        // Safe number format (if you later need it inside tooltips)
        function formatNumber(value) {
            return new Intl.NumberFormat('fr-FR').format(value ?? 0);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Animate progress bars (kept from your design)
            document.querySelectorAll('.custom-progress-fill').forEach(bar => {
                const w = bar.style.width; bar.style.width = '0%'; setTimeout(() => bar.style.width = w, 300);
            });

            // Buttons
            document.getElementById('refreshBtn').addEventListener('click', function () {
                const spinner = document.getElementById('loadingSpinner');
                spinner.style.display = 'flex';
                this.querySelector('i').classList.add('fa-spin');
                setTimeout(() => location.reload(), 500);
            });
            document.getElementById('exportBtn').addEventListener('click', exportToPDF);
            document.getElementById('exportTableBtn').addEventListener('click', exportTableToExcel);

            // === Charts ===
            // Line: New companies per month
            const companiesCtx = document.getElementById('chartCompanies').getContext('2d');
            new Chart(companiesCtx, {
                type: 'line',
                data: {
                    labels: @json($labels ?? []),
                    datasets: [{
                        label: 'Sociétés',
                        data: @json($data ?? []),
                        borderColor: '#FF4B00',
                        backgroundColor: 'rgba(255, 75, 0, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#FF4B00',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: .3
                    }]
                },
                options: {
                    responsive:true,
                    maintainAspectRatio:false,
                    plugins:{ legend:{ display:false } },
                    scales:{
                        y: { beginAtZero:true, grid: { color:'rgba(226,232,240,.5)' } },
                        x: { grid:{ display:false } }
                    }
                }
            });

            // Bar: Top companies by units
            const topUnitsCtx = document.getElementById('chartTopUnits').getContext('2d');
            new Chart(topUnitsCtx, {
                type: 'bar',
                data: {
                    labels: @json($dossiersLabels ?? []),
                    datasets: [{
                        label: 'Unités',
                        data: @json($dossiersData ?? []),
                        backgroundColor: 'rgba(255, 75, 0, 0.7)',
                        borderColor: 'rgba(255, 75, 0, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(255, 75, 0, 0.9)'
                    }]
                },
                options: {
                    responsive:true,
                    maintainAspectRatio:false,
                    plugins:{ legend:{ display:false } },
                    scales:{
                        y: { beginAtZero:true, grid:{ color:'rgba(226,232,240,.5)' } },
                        x: { grid:{ display:false } }
                    }
                }
            });
        });
    </script>
</body>
@endsection