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
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 4px;">
                    Personal Técnico Registrado
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;" class="dark:text-slate-100">
                    {{ $totalTechs }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #ef4444;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #dc2626; margin-bottom: 4px;">
                    🔴 En Atención Activa
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #dc2626;">
                    {{ $busyCount }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #eab308;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #ca8a04; margin-bottom: 4px;">
                    🟡 Con Pendientes
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #ca8a04;">
                    {{ $pendingCount }}
                </div>
            </div>

            <div class="tech-summary-card" style="border-left: 4px solid #22c55e;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #16a34a; margin-bottom: 4px;">
                    🟢 Disponibles (Libres)
                </div>
                <div style="font-size: 24px; font-weight: 800; color: #16a34a;">
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
                                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;" class="dark:text-slate-100">
                                    👤 {{ $item['user']->name }}
                                </h3>
                                <div style="font-size: 12px; color: #64748b;">
                                    {{ $item['user']->email }}
                                </div>
                            </div>

                            <span class="status-pill {{ $item['statusClass'] }}">
                                {{ $item['statusLabel'] }}
                            </span>
                        </div>

                        <!-- Sección de Atención Activa (Si aplica) -->
                        @if($item['activeTickets']->count() > 0)
                            <div style="margin-top: 10px;">
                                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #dc2626;">
                                    ⚡ Atención en Curso ({{ $item['activeTickets']->count() }})
                                </div>

                                @foreach($item['activeTickets'] as $t)
                                    <div class="ticket-item-box" style="border-left: 3px solid #ef4444;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="font-size: 13px; color: #2563eb;">{{ $t->ticket_code }}</strong>
                                            <span style="font-size: 11px; font-weight: 600; color: #dc2626;">{{ strtoupper($t->priority ?? 'Media') }}</span>
                                        </div>
                                        <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-top: 2px;" class="dark:text-slate-200">
                                            {{ $t->title }}
                                        </div>
                                        <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">
                                            🏢 Oficina: <strong>{{ $t->office?->name ?? 'N/A' }}</strong><br>
                                            👤 Solicitante: {{ $t->requester?->name ?? 'N/A' }}
                                        </div>
                                        <div style="margin-top: 8px; text-align: right;">
                                            <a href="{{ route('fichas.ticket', ['id' => $t->id]) }}" target="_blank" 
                                               style="font-size: 11.5px; font-weight: 700; color: #2563eb; text-decoration: underline;">
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
                                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #ca8a04;">
                                    📌 Asignados Pendientes ({{ $item['pendingTickets']->count() }})
                                </div>
                                <ul style="margin-top: 6px; padding-left: 18px; font-size: 12px; color: #475569;" class="dark:text-slate-300">
                                    @foreach($item['pendingTickets'] as $pt)
                                        <li style="margin-bottom: 4px;">
                                            <strong style="color: #2563eb;">{{ $pt->ticket_code }}</strong> - {{ $pt->title }} ({{ $pt->office?->name ?? 'N/A' }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Mensaje si está disponible -->
                        @if($item['statusKey'] === 'free')
                            <div class="ticket-item-box" style="border-left: 3px solid #22c55e; background-color: #f0fdf4;">
                                <div style="font-size: 12.5px; color: #166534; font-weight: 600;">
                                    ✅ Técnico disponible para atención inmediata de nuevas incidencias.
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Métricas del Día -->
                    <div style="border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 16px; display: flex; justify-content: space-between; font-size: 12px; color: #64748b;" class="dark:border-slate-800">
                        <span>Resueltos hoy: <strong style="color: #16a34a;">{{ $item['resolvedToday'] }}</strong></span>
                        <span>Carga activa: <strong>{{ $item['totalActive'] }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
