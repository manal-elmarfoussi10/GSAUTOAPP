@extends('layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <!-- Modern Header -->
    <div class="max-w-7xl mx-auto mb-10">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-3">
                <h1 class="text-3xl md:text-4xl font-bold text-black tracking-tight">
                    <span class="text-black">
                        Calendrier des Interventions
                    </span>
                </h1>
                <p class="text-gray-600 max-w-2xl">
                    Gérez vos rendez-vous techniques avec précision et efficacité grâce à notre interface intuitive
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg blur-sm opacity-80 group-hover:opacity-100 transition-all duration-300"></div>
                    <button id="openModal" class="relative bg-white px-6 py-3 rounded-lg shadow-md flex items-center gap-2 text-gray-800 group-hover:text-orange-600 transition-colors">
                        <i class="fas fa-plus"></i>
                        Nouveau RDV
                    </button>
                </div>
                
               
            </div>
        </div>
    </div>

    <!-- Modern Stats Cards -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="bg-blue-50 p-3 rounded-xl">
                    <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">RDV aujourd'hui</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['today'] }}</h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">vs hier</span>
                <span class="text-xs font-medium text-green-500 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 12%
                </span>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="bg-orange-50 p-3 rounded-xl">
                    <i class="fas fa-calendar-week text-orange-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">RDV cette semaine</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['this_week'] }}</h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">vs semaine dernière</span>
                <span class="text-xs font-medium text-green-500 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 8%
                </span>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="bg-green-50 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">RDV complétés</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['completed'] }}</h3>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">taux de complétion</span>
                <span class="text-xs font-medium text-blue-500">94%</span>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="max-w-7xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div id="calendar" class="p-4"></div>
    </div>
</div>

<!-- FullCalendar CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600  px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Modifier RDV
                </h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-white/70 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Technicien</label>
                    <select name="poseur_id" id="edit-poseur-id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($poseurs as $poseur)
                            <option value="{{ $poseur->id }}">{{ $poseur->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" id="edit-client-id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(\App\Models\Client::all() as $client)
                            <option value="{{ $client->id }}">{{ $client->nom_assure }} ({{ $client->plaque }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Début</label>
                        <div class="relative">
                            <input type="datetime-local" name="start_time" id="edit-start-time" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fin</label>
                        <div class="relative">
                            <input type="datetime-local" name="end_time" id="edit-end-time" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-6 pt-3">
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="ga_gestion" id="edit-ga-gestion" class="sr-only">
                            <div class="block bg-gray-200 w-14 h-8 rounded-full transition-colors"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">GA GESTION</span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="indisponible_poseur" id="edit-indisponible" class="sr-only">
                            <div class="block bg-gray-200 w-14 h-8 rounded-full transition-colors"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Indisponible</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-between mt-8 pt-5 border-t border-gray-200">
                <button type="button" onclick="deleteRdv()" class="px-5 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl shadow flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl shadow flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Modal -->
<div id="rdvModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Nouveau RDV
                </h3>
                <button id="closeModal" class="text-white/70 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="addForm" action="{{ route('rdv.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Technicien</label>
                    <select name="poseur_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Sélectionner un technicien...</option>
                        @foreach($poseurs as $poseur)
                            <option value="{{ $poseur->id }}">{{ $poseur->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Sélectionner un client...</option>
                        @foreach(\App\Models\Client::all() as $client)
                            <option value="{{ $client->id }}">{{ $client->nom_assure }} ({{ $client->plaque }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-6">
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="indisponible_poseur" id="indisponible" class="sr-only">
                            <div class="block bg-gray-200 w-14 h-8 rounded-full transition-colors"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Indisponible</span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="ga_gestion" id="ga_gestion" class="sr-only">
                            <div class="block bg-gray-200 w-14 h-8 rounded-full transition-colors"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">GA GESTION</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Début</label>
                        <div class="relative">
                            <input type="datetime-local" name="start_time" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <i class="fas fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fin</label>
                        <div class="relative">
                            <input type="datetime-local" name="end_time" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <i class="fas fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200">
                <button type="submit" class="w-full px-5 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl shadow-lg hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <i class="fas fa-calendar-plus"></i>
                    Créer le RDV
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay listWeek'
            },
            events: {
                url: '{{ route("rdv.events") }}',
                method: 'GET',
                failure: function(error) {
                    showToast('Erreur lors du chargement des RDVs: ' + error.message, 'error');
                }
            },
            eventTimeFormat: { 
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;

                document.getElementById('edit-id').value = event.id;
                document.getElementById('edit-poseur-id').value = props.poseur_id;
                document.getElementById('edit-client-id').value = props.client_id || '';
                
                // FIX: Use ISO string directly for datetime-local inputs
                document.getElementById('edit-start-time').value = event.startStr.substring(0, 16);
                document.getElementById('edit-end-time').value = event.endStr.substring(0, 16);
                
                document.getElementById('edit-ga-gestion').checked = !!props.ga_gestion;
                document.getElementById('edit-indisponible').checked = !!props.indisponible_poseur;

                updateToggleSwitch('edit-ga-gestion');
                updateToggleSwitch('edit-indisponible');

                document.getElementById('editForm').action = `/rdv/${event.id}`;
                document.getElementById('editModal').classList.remove('hidden');
            },
            eventContent: function(arg) {
                const event = arg.event;
                const isGAGestion = event.extendedProps.ga_gestion;
                
                return {
                    html: `
                    <div class="flex flex-col">
                        <div class="font-semibold truncate text-sm">${event.title}</div>
                        <div class="text-xs opacity-90 mt-1 flex items-center justify-between">
                            <span>${arg.timeText}</span>
                            ${isGAGestion ? 
                                '<span class="bg-white/20 px-2 py-0.5 rounded-full text-[0.65rem]">GA</span>' : 
                                ''}
                        </div>
                    </div>
                    `
                };
            }
        });

        calendar.render();

        // Modal handlers
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.getElementById('closeModal');
        const rdvModal = document.getElementById('rdvModal');
        const editModal = document.getElementById('editModal');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => rdvModal.classList.remove('hidden'));
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => rdvModal.classList.add('hidden'));
        }

        // Form handlers
        const addForm = document.getElementById('addForm');
        const editForm = document.getElementById('editForm');

        if (addForm) {
            addForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await handleFormSubmit(this, 'POST');
            });
        }

        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await handleFormSubmit(this, 'PUT');
            });
        }

        // Unified form handler
        async function handleFormSubmit(form, method) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';

                // Convert form to JSON instead of FormData
                const formData = {
                    id: form.querySelector('input[name="id"]')?.value,
                    poseur_id: form.querySelector('select[name="poseur_id"]').value,
                    client_id: form.querySelector('select[name="client_id"]').value,
                    start_time: form.querySelector('input[name="start_time"]').value,
                    end_time: form.querySelector('input[name="end_time"]').value,
                    ga_gestion: form.querySelector('input[name="ga_gestion"]')?.checked || false,
                    indisponible_poseur: form.querySelector('input[name="indisponible_poseur"]')?.checked || false
                };

                const url = form.action;

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const contentType = response.headers.get('content-type');
                let data;
                
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    throw new Error(`Server returned: ${response.status} ${response.statusText}\n${text}`);
                }

                if (!response.ok) {
                    let errorMessage = data.message || 'Échec de la requête';
                    
                    if (data.errors) {
                        errorMessage = Object.entries(data.errors)
                            .map(([field, messages]) => `${messages.join(', ')}`)
                            .join('\n');
                    }
                    
                    throw new Error(errorMessage);
                }

                showToast(data.message || 'Opération réussie !', 'success');
                
                // Close modal and refresh
                if (form.id === 'addForm') {
                    rdvModal.classList.add('hidden');
                    form.reset();
                } else {
                    editModal.classList.add('hidden');
                }
                
                calendar.refetchEvents();

            } catch (error) {
                console.error('Detailed Error:', error);
                showToast(`Erreur: ${error.message}`, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        // Delete function
        window.deleteRdv = async function() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce RDV ?')) return;

            const id = document.getElementById('edit-id').value;
            const deleteBtn = document.querySelector('#editForm button[onclick="deleteRdv()"]');
            const originalText = deleteBtn.innerHTML;

            try {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

                const response = await fetch(`/rdv/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Échec de la suppression');
                }

                const data = await response.json();
                showToast(data.message || 'RDV supprimé avec succès', 'success');
                editModal.classList.add('hidden');
                calendar.refetchEvents();
            } catch (error) {
                console.error('Delete Error:', error);
                showToast(`Échec de la suppression: ${error.message}`, 'error');
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalText;
            }
        }

        // Initialize toggle switches
        document.querySelectorAll('input[type="checkbox"].sr-only').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateToggleSwitch(this.id);
            });
            // Initialize on load
            updateToggleSwitch(checkbox.id);
        });

        function updateToggleSwitch(id) {
            const checkbox = document.getElementById(id);
            if (!checkbox) return;
            
            const dot = checkbox.nextElementSibling?.nextElementSibling;
            const bg = checkbox.nextElementSibling;
            
            if (checkbox.checked) {
                dot.style.transform = 'translateX(100%)';
                bg.style.backgroundColor = '#F97316';
            } else {
                dot.style.transform = 'translateX(0)';
                bg.style.backgroundColor = '#E5E7EB';
            }
        }

        // Toast notifications
        function showToast(message, type = 'success') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white flex items-start max-w-md ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.style.zIndex = '10000';
            
            const messageLines = message.split('\n');
            
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mt-1 mr-3"></i>
                <div>
                    ${messageLines.map(line => `<div>${line}</div>`).join('')}
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });
</script>

<style>
    /* Calendar Styling */
    .fc .fc-button {
        background: white;
        color: #374151;
        border: 1px solid #D1D5DB;
        border-radius: 12px;
        padding: 8px 16px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .fc .fc-button:hover {
        background: #F9FAFB;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    
    .fc .fc-button-primary {
        background: #3B82F6;
        color: white;
        border-color: #3B82F6;
    }
    
    .fc .fc-button-primary:hover {
        background: #2563EB;
        border-color: #2563EB;
    }
    
    .fc .fc-button-active {
        background: #1D4ED8;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .fc .fc-daygrid-day-frame {
        transition: background 0.3s ease;
    }
    
    .fc .fc-day-today {
        background: rgba(59, 130, 246, 0.1);
    }
    
    .fc .fc-daygrid-day-number {
        color: #4B5563;
        font-weight: 500;
        padding: 8px;
    }
    
    .fc .fc-col-header-cell {
        background: #F9FAFB;
    }
    
    .fc .fc-col-header-cell-cushion {
        color: #4B5563;
        font-weight: 600;
        padding: 12px 0;
    }
    
    .fc-event {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 4px 8px;
    }
    
    .fc-event:hover {
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .fc .fc-daygrid-event-harness {
        margin-bottom: 8px;
    }
    
    .relative .dot {
        transition: transform 0.3s ease;
    }
    
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-frame {
        background: rgba(59, 130, 246, 0.1);
    }
    
    .fc .fc-day-today .fc-daygrid-day-number {
        color: #3B82F6;
        font-weight: 700;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Toggle switch styling */
    .relative .block {
        transition: background-color 0.3s ease;
    }
</style>
@endsection