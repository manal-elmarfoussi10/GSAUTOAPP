@extends('layout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord dynamique</title>
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

        .custom-dashboard-container {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .custom-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .custom-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: var(--primary-extra-light);
            border-radius: 0 0 0 100%;
            z-index: 0;
            transition: all 0.4s ease;
        }

        .custom-stat-card:hover::before {
            width: 100%;
            height: 100%;
            border-radius: 1rem;
        }

        .custom-stat-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-extra-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .custom-stat-card:hover .custom-stat-icon {
            transform: scale(1.1);
            background: var(--primary);
            color: white;
        }

        .custom-stat-card:hover .stat-value {
            color: var(--primary);
        }

        .custom-progress-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 8px;
        }

        .custom-progress-fill {
            height: 100%;
            border-radius: 3px;
            background: var(--primary);
            transition: width 1s ease;
        }

        .custom-insurance-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-extra-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        tr:hover .custom-insurance-icon {
            background: var(--primary);
            color: white;
        }

        .custom-loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .custom-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--primary-extra-light);
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen p-6">
    <div class="custom-loading-spinner" id="loadingSpinner">
        <div class="custom-spinner"></div>
    </div>

    <div class="container mx-auto px-4 py-6 custom-dashboard-container">
        <!-- Header -->
        <div class="flex justify-between items-center py-5 mb-6 border-b border-gray-300">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-3 transition-transform hover:scale-102">
                <i class="fas fa-chart-line"></i>
                Tableau de <span class="text-orange-500">Bord</span>
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">CA Annuel (HT)</div>
                        <div class="text-2xl font-bold text-gray-800" id="annualRevenue">{{ number_format($totalHT, 0, ',', ' ') }} €</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> <span id="revenueTrend">12.5%</span> vs année précédente
                        </div>
                    </div>
                    <div class="custom-stat-icon">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
            </div>

            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Marge (TTC)</div>
                        <div class="text-2xl font-bold text-gray-800" id="marginValue">{{ number_format($marge, 0, ',', ' ') }} €</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> <span id="marginTrend">8.2%</span> vs année précédente
                        </div>
                    </div>
                    <div class="custom-stat-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>

            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Dépenses</div>
                        <div class="text-2xl font-bold text-gray-800" id="expensesValue">{{ number_format($depenses, 0, ',', ' ') }} €</div>
                        <div class="text-gray-500 text-sm">
                            <i class="fas fa-info-circle"></i> Contrôlé dans le budget
                        </div>
                    </div>
                    <div class="custom-stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>

            <div class="custom-stat-card bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4 relative">
                    <div>
                        <div class="text-sm font-semibold text-gray-500 uppercase">Dossiers actifs</div>
                        <div class="text-2xl font-bold text-gray-800" id="activeFiles">{{ $dossiersActifs }}</div>
                        <div class="text-green-500 font-semibold text-sm flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> <span id="newFiles">{{ $nouveauxDossiers }}</span> nouveaux ce mois-ci
                        </div>
                    </div>
                    <div class="custom-stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-gray-800">Chiffre d'affaire (HT)</h2>
                    <div>
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-calendar"></i> <span id="currentYear">{{ now()->year }}</span>
                        </button>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="chartCa"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-gray-800">Nombre de dossiers</h2>
                    <div>
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-filter"></i> Tous
                        </button>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="chartDossiers"></canvas>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl p-6 shadow-md transition-transform hover:-translate-y-1 mb-8">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">Statistiques par Assurance</h2>
                <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg font-medium border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors" id="exportTableBtn">
                    <i class="fas fa-download"></i> Exporter Excel
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Assurance</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Part €</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Part %</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Panier moyen</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-700 uppercase text-xs tracking-wider border-b border-gray-300 bg-white sticky top-0">Évolution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPartEuro = $statsParAssurance->sum('part_euro');
                        @endphp

                        @foreach($statsParAssurance as $assurance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 border-b border-gray-200 text-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="custom-insurance-icon">
                                        @switch($assurance->nom_assurance)
                                            @case('MACIF') <i class="fas fa-building"></i> @break
                                            @case('MATMUT') <i class="fas fa-shield-alt"></i> @break
                                            @case('GROUPAMA') <i class="fas fa-umbrella"></i> @break
                                            @case('MMA') <i class="fas fa-home"></i> @break
                                            @case('AXA') <i class="fas fa-car"></i> @break
                                            @default <i class="fas fa-shield-alt"></i>
                                        @endswitch
                                    </div>
                                    {{ $assurance->nom_assurance }}
                                </div>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-gray-700">{{ number_format($assurance->part_euro ?? 0, 0, ',', ' ') }} €</td>
                            <td class="px-5 py-4 border-b border-gray-200 text-gray-700">
                                @php
                                    $partPercentage = $totalPartEuro > 0 ? ($assurance->part_euro / $totalPartEuro) * 100 : 0;
                                @endphp
                                <div>{{ number_format($partPercentage, 1) }}%</div>
                                <div class="custom-progress-bar">
                                    <div class="custom-progress-fill" style="width: {{ $partPercentage }}%"></div>
                                </div>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-gray-700">{{ number_format($assurance->panier_moyen ?? 0, 0, ',', ' ') }} €</td>
                            <td class="px-5 py-4 border-b border-gray-200 text-green-500 font-semibold">+{{ number_format(rand(5, 15)/10, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour exporter en PDF
        function exportToPDF() {
            const spinner = document.getElementById('loadingSpinner');
            spinner.style.display = 'flex';

            setTimeout(() => {
                // Utilisation de html2canvas pour capturer le dashboard
                html2canvas(document.querySelector('.container')).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('tableau-de-bord-' + new Date().toISOString().slice(0, 10) + '.pdf');

                    spinner.style.display = 'none';
                }).catch(error => {
                    console.error('Erreur lors de la génération du PDF:', error);
                    spinner.style.display = 'none';
                    alert('Une erreur est survenue lors de l\'export PDF');
                });
            }, 500);
        }

        // Fonction pour exporter le tableau en Excel
        function exportTableToExcel() {
            const table = document.getElementById('insuranceTable');
            const wb = XLSX.utils.book_new();

            // Préparer les données du tableau
            const data = [];
            const headers = [];

            // Récupérer les en-têtes
            table.querySelectorAll('th').forEach(th => {
                headers.push(th.innerText);
            });
            data.push(headers);

            // Récupérer les lignes de données
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach((td, index) => {
                    // Pour la colonne de pourcentage, prendre juste la valeur numérique
                    if (index === 2) {
                        const percentValue = td.querySelector('div:first-child').innerText;
                        row.push(percentValue.replace('%', ''));
                    } else {
                        row.push(td.innerText);
                    }
                });
                data.push(row);
            });

            // Créer la feuille Excel
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'Statistiques');

            // Exporter le fichier
            XLSX.writeFile(wb, 'statistiques-assurances-' + new Date().toISOString().slice(0, 10) + '.xlsx');
        }

        // Fonction utilitaire pour formater les montants
        function formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }


        document.addEventListener('DOMContentLoaded', function() {
            // CA Chart
            const caCtx = document.getElementById('chartCa').getContext('2d');
            const caChart = new Chart(caCtx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'CA (€)',
                        data: @json($data),
                        borderColor: '#FF4B00',
                        backgroundColor: 'rgba(255, 75, 0, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#FF4B00',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(45, 55, 72, 0.95)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            },
                            callbacks: {
                                label: function(context) {
                                    return `CA: ${formatCurrency(context.parsed.y)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(226, 232, 240, 0.5)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Dossiers Chart
            const dossiersCtx = document.getElementById('chartDossiers').getContext('2d');
            const dossiersChart = new Chart(dossiersCtx, {
                type: 'bar',
                data: {
                    labels: @json($dossiersLabels),
                    datasets: [{
                        label: 'Dossiers',
                        data: @json($dossiersData),
                        backgroundColor: 'rgba(255, 75, 0, 0.7)',
                        borderColor: 'rgba(255, 75, 0, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(255, 75, 0, 0.9)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(226, 232, 240, 0.5)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            
            document.querySelectorAll('.custom-progress-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });

            // Gestionnaire d'événements pour le bouton Actualiser
            document.getElementById('refreshBtn').addEventListener('click', function() {
                const spinner = document.getElementById('loadingSpinner');
                spinner.style.display = 'flex';
                this.querySelector('i').classList.add('fa-spin');

                setTimeout(() => {
                    location.reload();
                }, 500);
            });

            // Gestionnaire d'événements pour le bouton Exporter PDF
            document.getElementById('exportBtn').addEventListener('click', function() {
                // Animation de confirmation
                this.classList.add('bg-orange-500', 'text-white', 'border-transparent');
                setTimeout(() => {
                    this.classList.remove('bg-orange-500', 'text-white', 'border-transparent');
                }, 2000);

                exportToPDF();
            });

            // Gestionnaire d'événements pour le bouton Exporter Excel
            document.getElementById('exportTableBtn').addEventListener('click', function() {
                // Animation de confirmation
                this.classList.add('bg-orange-500', 'text-white', 'border-transparent');
                setTimeout(() => {
                    this.classList.remove('bg-orange-500', 'text-white', 'border-transparent');
                }, 2000);

                exportTableToExcel();
            });
        });
    </script>
</body>
</html>
@endsection
