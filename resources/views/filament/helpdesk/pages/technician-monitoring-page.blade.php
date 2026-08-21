<x-filament-panels::page>
    <style>
        .tech-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .tech-summary-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .dark .tech-summary-card {
            background-color: #0f172a;
            border-color: #1e293b;
        }
        .tech-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 20px;
        }
        .tech-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dark .tech-card {
            background-color: #0f172a;
            border-color: #1e293b;
        }

        /* BADGES EN LIGHT MODE */
        .status-badge-busy {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .status-badge-pending {
            background-color: #fefce8;
            color: #ca8a04;
            border: 1px solid #fef08a;
        }
        .status-badge-free {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* BADGES EN DARK MODE */
        .dark .status-badge-busy {
            background-color: #450a0a !important;
            color: #fca5a5 !important;
            border-color: #7f1d1d !important;
        }
        .dark .status-badge-pending {
            background-color: #422006 !important;
            color: #fde047 !important;
            border-color: #713f12 !important;
        }
        .dark .status-badge-free {
            background-color: #052e16 !important;
            color: #4ade80 !important;
            border-color: #14532d !important;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .ticket-item-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 10px;
        }
        .dark .ticket-item-box {
            background-color: #1e293b;
            border-color: #334155;
        }

        .free-message-box {
            border-left: 3px solid #22c55e;
            background-color: #f0fdf4;
        }
        .dark .free-message-box {
            border-left: 3px solid #4ade80;
            background-color: #052e16 !important;
        }
        .free-message-text {
            font-size: 12.5px;
            color: #166534;
            font-weight: 600;
        }
        .dark .free-message-text {
            color: #86efac !important;
        }
    </style>

    @php
        $techs = $this->techniciansData;
        $totalTechs = count($techs);
        $busyCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'busy'));
        $pendingCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'pending'));
        $freeCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'free'));
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen General -->
        <div class="tech-summary-grid">
            <div class="tech-summary-card">
                <div class="text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">
                    Personal Técnico Registrado
                </div>
                <div class="text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                    {{ $totalTechs }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #ef4444;">
                <div class="text-[11px] font-bold uppercase text-red-600 dark:text-red-400 mb-1">
                    🔴 En Atención Activa
                </div>
                <div class="text-2xl font-extrabold text-red-600 dark:text-red-400">
                    {{ $busyCount }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #eab308;">
                <div class="text-[11px] font-bold uppercase text-yellow-600 dark:text-yellow-400 mb-1">
                    🟡 Con Pendientes
                </div>
                <div class="text-2xl font-extrabold text-yellow-600 dark:text-yellow-400">
                    {{ $pendingCount }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #22c55e;">
                <div class="text-[11px] font-bold uppercase text-green-600 dark:text-green-400 mb-1">
                    🟢 Disponibles (Libres)
                </div>
                <div class="text-2xl font-extrabold text-green-600 dark:text-green-400">
                    {{ $freeCount }}
                </div>
            </div>
        </div>

        <!-- Grid de Estado por Técnico -->
        <div class="tech-card-grid">
            @foreach($techs as $item)
                <div class="tech-card">
                    <div>
                        <!-- Header del Técnico -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 m-0">
                                    👤 {{ $item['user']->name }}
                                </h3>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $item['user']->email ?? 'Sin correo' }}
                                    @if($item['user']->office)
                                        • <span class="font-medium text-slate-600 dark:text-slate-300">{{ $item['user']->office->acronym ?? $item['user']->office->name }}</span>
                                    @endif
                                </div>
                            </div>

                            <span class="status-pill {{ $item['statusClass'] }}">
                                {{ $item['statusLabel'] }}
                            </span>
                        </div>

                        <!-- Sección de Atención Activa (Si aplica) -->
                        @if($item['activeTickets']->count() > 0)
                            <div style="margin-top: 10px;">
                                <div class="text-[11px] font-bold uppercase text-red-600 dark:text-red-400">
                                    ⚡ Atención en Curso ({{ $item['activeTickets']->count() }})
                                </div>

                                @foreach($item['activeTickets'] as $t)
                                    <div class="ticket-item-box" style="border-left: 3px solid #ef4444;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong class="text-xs text-blue-600 dark:text-blue-400">{{ $t->ticket_code }}</strong>
                                            <span class="text-[11px] font-semibold text-red-600 dark:text-red-400">{{ strtoupper($t->priority ?? 'Media') }}</span>
                                        </div>
                                        <div class="text-xs font-semibold text-slate-900 dark:text-slate-100 mt-1">
                                            {{ $t->title }}
                                        </div>
                                        <div class="text-[11.5px] text-slate-500 dark:text-slate-400 mt-1">
                                            🏢 Oficina: <strong class="text-slate-700 dark:text-slate-200">{{ $t->office?->name ?? 'N/A' }}</strong><br>
                                            👤 Solicitante: <span class="text-slate-700 dark:text-slate-300">{{ $t->requester?->name ?? 'N/A' }}</span>
                                        </div>
                                        <div style="margin-top: 8px; text-align: right;">
                                            <a href="{{ route('fichas.ticket', ['id' => $t->id]) }}" target="_blank" 
                                               class="text-[11.5px] font-bold text-blue-600 dark:text-blue-400 underline">
                                                Ver Ficha de Ticket ↗
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Sección de Tickets Pendientes (Si aplica) -->
                        @if($item['pendingTickets']->count() > 0)
                            <div style="margin-top: 14px;">
                                <div class="text-[11px] font-bold uppercase text-yellow-600 dark:text-yellow-400">
                                    📌 Asignados Pendientes ({{ $item['pendingTickets']->count() }})
                                </div>
                                <ul class="mt-1.5 pl-4 text-xs text-slate-600 dark:text-slate-300 list-disc">
                                    @foreach($item['pendingTickets'] as $pt)
                                        <li class="mb-1">
                                            <strong class="text-blue-600 dark:text-blue-400">{{ $pt->ticket_code }}</strong> - {{ $pt->title }} ({{ $pt->office?->name ?? 'N/A' }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Mensaje si está disponible -->
                        @if($item['statusKey'] === 'free')
                            <div class="ticket-item-box free-message-box">
                                <div class="free-message-text">
                                    ✅ Técnico disponible para atención inmediata de nuevas incidencias.
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Métricas del Día -->
                    <div class="border-t border-slate-200 dark:border-slate-800 pt-3 mt-4 flex justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Resueltos hoy: <strong class="text-green-600 dark:text-green-400">{{ $item['resolvedToday'] }}</strong></span>
                        <span>Carga activa: <strong class="text-slate-900 dark:text-slate-100">{{ $item['totalActive'] }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
