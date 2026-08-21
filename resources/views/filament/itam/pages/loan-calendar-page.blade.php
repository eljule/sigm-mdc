<x-filament-panels::page>
    <style>
        .calendar-control-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .dark .calendar-control-card {
            background-color: #0f172a;
            border-color: #1e293b;
        }
        .calendar-btn-primary {
            background-color: #2563eb;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s;
        }
        .calendar-btn-primary:hover {
            background-color: #1d4ed8;
            color: #ffffff !important;
        }
        .calendar-btn-secondary {
            background-color: #f1f5f9;
            color: #334155 !important;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #cbd5e1;
            transition: background-color 0.2s;
        }
        .calendar-btn-secondary:hover {
            background-color: #e2e8f0;
        }
        .dark .calendar-btn-secondary {
            background-color: #1e293b;
            color: #f8fafc !important;
            border-color: #334155;
        }
        .legend-card {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            background-color: #f8fafc;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .dark .legend-card {
            background-color: #0f172a;
            color: #cbd5e1;
            border-color: #1e293b;
        }

        /* FULLCALENDAR BASE & LIGHT MODE */
        .fc { font-family: inherit; }
        .fc-header-toolbar { flex-wrap: wrap; gap: 10px; margin-bottom: 20px !important; }
        .fc-button {
            background-color: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 6px 14px !important;
            box-shadow: none !important;
        }
        .fc-button:hover { background-color: #1e293b !important; border-color: #1e293b !important; }
        .fc-button-active { background-color: #2563eb !important; border-color: #2563eb !important; }
        .fc-prev-button, .fc-next-button { font-size: 14px !important; padding: 6px 12px !important; }
        .fc-event { cursor: pointer; padding: 3px 6px; border-radius: 4px; font-size: 12px; font-weight: 600; }

        .fc-toolbar-title { font-size: 1.35rem !important; font-weight: 700; color: #0f172a; }
        .fc-col-header-cell-cushion { color: #0f172a !important; font-weight: 700; text-decoration: none !important; }
        .fc-daygrid-day-number { color: #334155 !important; font-weight: 700; text-decoration: none !important; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #e2e8f0 !important; }

        /* FULLCALENDAR DARK MODE OVERRIDES */
        .dark .fc-toolbar-title { color: #f8fafc !important; }
        .dark .fc-col-header-cell-cushion { color: #f8fafc !important; text-decoration: none !important; }
        .dark .fc-daygrid-day-number { color: #f8fafc !important; text-decoration: none !important; }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #334155 !important; }
        .dark .fc-day-other .fc-daygrid-day-number { color: #64748b !important; }
        .dark .fc-daygrid-day { background-color: #0f172a !important; }
        .dark .fc-scrollgrid { background-color: #0f172a !important; border-color: #334155 !important; }
        .dark .fc-list { background-color: #0f172a !important; color: #f8fafc !important; }
        .dark .fc-list-day-cushion { background-color: #1e293b !important; color: #f8fafc !important; }
    </style>

    <div class="space-y-4">
        <!-- Barra de Controles y Filtros -->
        <div class="calendar-control-card">
            <div style="flex: 1; max-width: 450px;">
                <label for="asset-select" class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">
                    Filtrar por Equipo / Activo:
                </label>
                <select id="asset-select" wire:model.live="selectedAssetId" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm font-medium focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Todos los Equipos --</option>
                    @foreach($this->assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->computer_code ?? $asset->asset_code ?? "ID: {$asset->id}" }} - 
                            {{ $asset->category?->name ?? 'Equipo' }} 
                            ({{ $asset->model?->brand?->name }} {{ $asset->model?->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="{{ \App\Filament\Itam\Resources\AssetLoans\AssetLoanResource::getUrl('create') }}" class="calendar-btn-primary">
                    ➕ Nueva Reserva / Préstamo
                </a>

                <a href="{{ \App\Filament\Itam\Resources\AssetLoans\AssetLoanResource::getUrl('index') }}" class="calendar-btn-secondary">
                    📋 Ver Tabla
                </a>
            </div>
        </div>

        <!-- Leyenda de Colores de Estado -->
        <div class="legend-card">
            <span style="font-weight: 700;" class="text-slate-900 dark:text-slate-100">Leyenda de Estados:</span>
            <span style="display: inline-flex; align-items: center; gap: 4px;">🟡 Pendiente (Reserva)</span>
            <span style="display: inline-flex; align-items: center; gap: 4px;">🟢 En Préstamo Activo</span>
            <span style="display: inline-flex; align-items: center; gap: 4px;">⚪ Devuelto</span>
            <span style="display: inline-flex; align-items: center; gap: 4px;">🔴 Vencido (No Devuelto)</span>
        </div>

        <!-- Contenedor del Calendario -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm min-h-[650px]">
            <div id="calendar" wire:ignore></div>
        </div>
    </div>

    <!-- Script de FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initCalendar();
        });

        document.addEventListener('livewire:initialized', () => {
            initCalendar();
        });

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl || calendarEl.dataset.initialized) return;
            calendarEl.dataset.initialized = "true";

            let events = @json($this->events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 660,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    prev:     '◀',
                    next:     '▶',
                    today:    'Hoy',
                    month:    'Mes',
                    week:     'Semana',
                    day:      'Día',
                    list:     'Agenda'
                },
                events: events,
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.open(info.event.url, '_blank');
                    }
                }
            });

            calendar.render();

            Livewire.on('commit', () => {
                setTimeout(() => {
                    calendar.removeAllEvents();
                    calendar.addEventSource(@json($this->events));
                }, 100);
            });
        }
    </script>
</x-filament-panels::page>
