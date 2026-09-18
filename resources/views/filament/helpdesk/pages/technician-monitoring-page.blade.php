<x-filament-panels::page>
    <div wire:poll.20s class="mon-page">
        <style>
            /* ==========================================================================
               ESTILOS DEDICADOS Y AUTOCONTENIDOS - CENTRO DE MONITOREO TÉCNICO
               ========================================================================== */

            /* SUBTÍTULO DE CABECERA AJUSTADO */
            .fi-header-subheading {
                font-size: 13px !important;
                line-height: 1.45 !important;
                color: #64748b !important;
                font-weight: 500 !important;
                margin-top: 4px !important;
                letter-spacing: normal !important;
            }
            .dark .fi-header-subheading {
                color: #94a3b8 !important;
            }

            /* RESET TOTAL Y PROTECCIÓN DE SVGS: NINGÚN SVG CRECE DESCONTROLADO */
            .mon-page svg {
                display: inline-block !important;
                vertical-align: middle !important;
                flex-shrink: 0 !important;
            }
            .mon-icon-xs { width: 12px !important; height: 12px !important; min-width: 12px !important; max-width: 12px !important; }
            .mon-icon-sm { width: 15px !important; height: 15px !important; min-width: 15px !important; max-width: 15px !important; }
            .mon-icon-md { width: 18px !important; height: 18px !important; min-width: 18px !important; max-width: 18px !important; }
            .mon-icon-lg { width: 22px !important; height: 22px !important; min-width: 22px !important; max-width: 22px !important; }
            .mon-icon-xl { width: 26px !important; height: 26px !important; min-width: 26px !important; max-width: 26px !important; }

            /* ANIMACIONES Y MICRO-INTERACCIONES */
            @keyframes soft-pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.4; transform: scale(1.2); }
            }
            .mon-pulse-red {
                animation: soft-pulse 1.8s infinite ease-in-out;
            }
            .mon-pulse-green {
                animation: soft-pulse 2.2s infinite ease-in-out;
            }

            /* SISTEMA FLEXBOX AUTOCONTENIDO */
            .mon-flex { display: flex !important; }
            .mon-inline-flex { display: inline-flex !important; }
            .mon-flex-col { flex-direction: column !important; }
            .mon-flex-row { flex-direction: row !important; }
            .mon-flex-wrap { flex-wrap: wrap !important; }
            .mon-items-center { align-items: center !important; }
            .mon-items-start { align-items: flex-start !important; }
            .mon-items-end { align-items: flex-end !important; }
            .mon-justify-between { justify-content: space-between !important; }
            .mon-justify-center { justify-content: center !important; }
            .mon-justify-end { justify-content: flex-end !important; }
            .mon-flex-1 { flex: 1 1 0% !important; }
            .mon-shrink-0 { flex-shrink: 0 !important; }

            .mon-gap-1 { gap: 4px !important; }
            .mon-gap-1\.5 { gap: 6px !important; }
            .mon-gap-2 { gap: 8px !important; }
            .mon-gap-2\.5 { gap: 10px !important; }
            .mon-gap-3 { gap: 12px !important; }
            .mon-gap-4 { gap: 16px !important; }
            .mon-gap-5 { gap: 20px !important; }

            /* TIPOGRAFÍA NATIVA Y LEGIBLE */
            .mon-text-xs { font-size: 11px !important; line-height: 15px !important; }
            .mon-text-sm { font-size: 13px !important; line-height: 18px !important; }
            .mon-text-base { font-size: 14px !important; line-height: 20px !important; }
            .mon-text-lg { font-size: 16px !important; line-height: 22px !important; }
            .mon-text-xl { font-size: 20px !important; line-height: 26px !important; }
            .mon-text-2xl { font-size: 26px !important; line-height: 30px !important; }
            .mon-text-3xl { font-size: 30px !important; line-height: 34px !important; }
            .mon-font-medium { font-weight: 500 !important; }
            .mon-font-semibold { font-weight: 600 !important; }
            .mon-font-bold { font-weight: 700 !important; }
            .mon-font-extrabold { font-weight: 800 !important; }
            .mon-font-black { font-weight: 900 !important; }
            .mon-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important; }
            .mon-uppercase { text-transform: uppercase !important; }
            .mon-tracking-wide { letter-spacing: 0.04em !important; }
            .mon-tracking-tight { letter-spacing: -0.02em !important; }

            /* BARRA SUPERIOR DE ESTADO EN VIVO */
            .mon-topbar {
                background: linear-gradient(135deg, rgba(236, 253, 245, 0.9) 0%, rgba(255, 255, 255, 0.95) 50%, rgba(248, 250, 252, 0.9) 100%);
                border: 1px solid rgba(16, 185, 129, 0.25);
                border-radius: 16px;
                padding: 14px 20px;
                margin-bottom: 22px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            }
            .dark .mon-topbar {
                background: linear-gradient(135deg, rgba(6, 78, 59, 0.2) 0%, #0f172a 50%, rgba(30, 41, 59, 0.5) 100%);
                border-color: rgba(16, 185, 129, 0.2);
            }
            .mon-sync-btn {
                background: #ffffff;
                color: #334155;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                padding: 6px 13px;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }
            .mon-sync-btn:hover {
                background: #f8fafc;
                border-color: #94a3b8;
                color: #0f172a;
            }
            .dark .mon-sync-btn {
                background: #1e293b;
                color: #e2e8f0;
                border-color: #334155;
            }
            .dark .mon-sync-btn:hover {
                background: #334155;
                color: #ffffff;
            }

            /* BENTO GRID DE 5 TARJETAS SUPERIORES */
            .mon-bento-grid {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 14px;
                margin-bottom: 24px;
            }
            @media (max-width: 1200px) {
                .mon-bento-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            @media (max-width: 768px) {
                .mon-bento-grid {
                    grid-template-columns: 1fr;
                }
            }

            .mon-bento-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 16px 18px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 105px;
            }
            .mon-bento-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px -2px rgba(0,0,0,0.06);
            }
            .dark .mon-bento-card {
                background: #0f172a;
                border-color: #1e293b;
            }

            .mon-bento-icon-box {
                width: 42px;
                height: 42px;
                min-width: 42px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            /* TARJETAS DE TÉCNICOS */
            .mon-tech-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            @media (max-width: 640px) {
                .mon-tech-grid {
                    grid-template-columns: 1fr;
                }
            }

            .mon-tech-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 20px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 16px;
            }
            .mon-tech-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px -4px rgba(0, 132, 53, 0.12);
            }
            .dark .mon-tech-card {
                background: #0f172a;
                border-color: #1e293b;
            }

            /* AVATAR DE TÉCNICO */
            .mon-avatar {
                width: 46px;
                height: 46px;
                min-width: 46px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 15px;
                letter-spacing: 0.5px;
                position: relative;
                color: #ffffff;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            }

            /* BADGES Y PÍLDORAS */
            .mon-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 10px;
                border-radius: 9999px;
                font-size: 10.5px;
                font-weight: 800;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }
            .mon-pill-busy {
                background-color: #fef2f2;
                color: #b91c1c;
                border: 1px solid #fecaca;
            }
            .dark .mon-pill-busy {
                background-color: rgba(127, 29, 29, 0.3);
                color: #fca5a5;
                border-color: rgba(185, 28, 28, 0.5);
            }
            .mon-pill-free {
                background-color: #f0fdf4;
                color: #15803d;
                border: 1px solid #bbf7d0;
            }
            .dark .mon-pill-free {
                background-color: rgba(20, 83, 45, 0.3);
                color: #86efac;
                border-color: rgba(22, 163, 74, 0.5);
            }
            .mon-pill-pending {
                background-color: #fefce8;
                color: #a16207;
                border: 1px solid #fef08a;
            }
            .dark .mon-pill-pending {
                background-color: rgba(113, 63, 18, 0.3);
                color: #fde047;
                border-color: rgba(161, 98, 7, 0.5);
            }

            /* CAJA DE ATENCIÓN ACTIVA DENTRO DE LA TARJETA */
            .mon-active-box {
                background: #fff5f5;
                border: 1px solid #fed7d7;
                border-radius: 12px;
                padding: 14px 16px;
            }
            .dark .mon-active-box {
                background: rgba(153, 27, 27, 0.15);
                border-color: rgba(239, 68, 68, 0.3);
            }

            /* CAJA DE ESTADO DISPONIBLE */
            .mon-free-box {
                background: #f0fdf4;
                border: 1px dashed #86efac;
                border-radius: 12px;
                padding: 14px 16px;
                text-align: center;
            }
            .dark .mon-free-box {
                background: rgba(6, 78, 59, 0.15);
                border-color: rgba(16, 185, 129, 0.3);
            }

            /* TABLA Y CONTENEDOR DE TICKETS */
            .mon-table-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                overflow: hidden;
            }
            .dark .mon-table-card {
                background: #0f172a;
                border-color: #1e293b;
            }

            /* BOTONES DE PESTAÑAS DE FILTRO */
            .mon-tab-btn {
                font-size: 12px;
                font-weight: 700;
                padding: 6px 14px;
                border-radius: 9999px;
                border: 1px solid transparent;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .mon-tab-btn.active {
                background-color: #008435;
                color: #ffffff;
                box-shadow: 0 2px 6px rgba(0, 132, 53, 0.25);
            }
            .dark .mon-tab-btn.active {
                background-color: #10b981;
                color: #042f2e;
            }
            .mon-tab-btn:not(.active) {
                background-color: #f1f5f9;
                color: #475569;
            }
            .dark .mon-tab-btn:not(.active) {
                background-color: #1e293b;
                color: #94a3b8;
            }
            .mon-tab-btn:not(.active):hover {
                background-color: #e2e8f0;
                color: #0f172a;
            }
            .dark .mon-tab-btn:not(.active):hover {
                background-color: #334155;
                color: #ffffff;
            }

            /* TABLA */
            .mon-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                font-size: 12.5px;
            }
            .mon-table th {
                background-color: #f8fafc;
                color: #475569;
                font-weight: 800;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 11px 16px;
                border-bottom: 1px solid #e2e8f0;
            }
            .dark .mon-table th {
                background-color: #1e293b;
                color: #94a3b8;
                border-color: #334155;
            }
            .mon-table td {
                padding: 12px 16px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            .dark .mon-table td {
                border-color: #1e293b;
            }
            .mon-table tr:hover td {
                background-color: #f8fafc;
            }
            .dark .mon-table tr:hover td {
                background-color: rgba(30, 41, 59, 0.5);
            }

            /* BOTONES DE ACCIÓN */
            .mon-btn-outline {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 5px 10px;
                border-radius: 8px;
                font-size: 11.5px;
                font-weight: 700;
                text-decoration: none;
                background: #ffffff;
                color: #334155;
                border: 1px solid #cbd5e1;
                transition: all 0.15s ease;
            }
            .mon-btn-outline:hover {
                background: #f1f5f9;
                color: #0f172a;
                border-color: #94a3b8;
            }
            .dark .mon-btn-outline {
                background: #1e293b;
                color: #cbd5e1;
                border-color: #334155;
            }
            .dark .mon-btn-outline:hover {
                background: #334155;
                color: #ffffff;
            }

            .mon-btn-filled {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 5px 10px;
                border-radius: 8px;
                font-size: 11.5px;
                font-weight: 700;
                text-decoration: none;
                background: #008435;
                color: #ffffff;
                border: 1px solid #008435;
                transition: all 0.15s ease;
            }
            .mon-btn-filled:hover {
                background: #006828;
                border-color: #006828;
                color: #ffffff;
            }
            .dark .mon-btn-filled {
                background: #10b981;
                color: #042f2e;
                border-color: #10b981;
            }
            .dark .mon-btn-filled:hover {
                background: #059669;
                border-color: #059669;
                color: #ffffff;
            }
        </style>

        @php
            $techs = $techniciansData ?? (isset($this) ? $this->techniciansData : []);
            $unclosed = $unclosedTickets ?? (isset($this) ? $this->unclosedTickets : collect());
            $stats = $unclosedStats ?? (isset($this) ? $this->unclosedStats : ['all' => 0, 'in_progress' => 0, 'open' => 0, 'resolved' => 0]);
            $currentFilter = $ticketFilter ?? 'all';
            $totalTechs = count($techs);
            $busyCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'busy'));
            $pendingCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'pending'));
            $freeCount = count(array_filter($techs, fn($t) => $t['statusKey'] === 'free'));
        @endphp

        <!-- 1. BARRA SUPERIOR DE ESTADO EN VIVO Y SINCRONIZACIÓN -->
        <div class="mon-topbar mon-flex mon-items-center mon-justify-between mon-flex-wrap mon-gap-3">
            <div class="mon-flex mon-items-center mon-gap-3">
                <div class="mon-shrink-0" style="position: relative; width: 14px; height: 14px;">
                    <span class="mon-pulse-green" style="position: absolute; inset: 0; border-radius: 9999px; background-color: #34d399; opacity: 0.75;"></span>
                    <span style="position: relative; display: block; width: 14px; height: 14px; border-radius: 9999px; background-color: #10b981;"></span>
                </div>
                <div>
                    <div class="mon-flex mon-items-center mon-gap-2">
                        <span class="mon-text-xs mon-font-black mon-uppercase mon-tracking-wide" style="color: #0f172a;">
                            SOPORTE ODT • MONITOREO OPERATIVO EN TIEMPO REAL
                        </span>
                        <span class="mon-pill mon-pill-free" style="padding: 2px 8px; font-size: 9.5px;">
                            SISTEMA ACTIVO
                        </span>
                    </div>
                    <div class="mon-text-xs mon-font-medium" style="color: #64748b; margin-top: 2px;">
                        Actualización automática activa (cada 20s) • Sincronizado: {{ now()->format('H:i:s') }}
                    </div>
                </div>
            </div>

            <div class="mon-flex mon-items-center mon-gap-2">
                <button wire:click="$refresh" type="button" class="mon-sync-btn mon-flex mon-items-center mon-gap-1.5">
                    <svg width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" class="mon-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Sincronizar</span>
                </button>
            </div>
        </div>

        <!-- 2. BENTO GRID DE 5 INDICADORES OPERATIVOS -->
        <div class="mon-bento-grid">
            <!-- 1: Total Técnicos -->
            <div class="mon-bento-card" style="border-top: 3px solid #64748b;">
                <div class="mon-flex mon-items-center mon-justify-between">
                    <div>
                        <div class="mon-text-xs mon-font-bold mon-uppercase mon-tracking-wide" style="color: #64748b;">
                            Personal Registrado
                        </div>
                        <div class="mon-text-3xl mon-font-black mon-font-mono mon-tracking-tight" style="color: #0f172a; margin-top: 4px;">
                            {{ $totalTechs }}
                        </div>
                    </div>
                    <div class="mon-bento-icon-box" style="background-color: #f1f5f9; color: #475569;">
                        <svg width="22" height="22" style="width: 22px; height: 22px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="mon-text-xs mon-font-medium" style="color: #94a3b8; margin-top: 8px;">
                    Equipo de soporte ODT
                </div>
            </div>

            <!-- 2: En Atención Activa (Rojo) -->
            <div class="mon-bento-card" style="border-top: 3px solid #ef4444; background: linear-gradient(180deg, rgba(254, 242, 242, 0.4) 0%, #ffffff 100%);">
                <div class="mon-flex mon-items-center mon-justify-between">
                    <div>
                        <div class="mon-flex mon-items-center mon-gap-1.5">
                            <span class="mon-pulse-red" style="width: 7px; height: 7px; border-radius: 9999px; background-color: #ef4444; display: inline-block;"></span>
                            <span class="mon-text-xs mon-font-bold mon-uppercase mon-tracking-wide" style="color: #dc2626;">
                                En Atención Activa
                            </span>
                        </div>
                        <div class="mon-text-3xl mon-font-black mon-font-mono mon-tracking-tight" style="color: #b91c1c; margin-top: 4px;">
                            {{ $busyCount }}
                        </div>
                    </div>
                    <div class="mon-bento-icon-box" style="background-color: #fee2e2; color: #dc2626;">
                        <svg width="22" height="22" style="width: 22px; height: 22px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                        </svg>
                    </div>
                </div>
                <div class="mon-text-xs mon-font-medium" style="color: #ef4444; margin-top: 8px;">
                    Ocupados en soporte presencial
                </div>
            </div>

            <!-- 3: Con Pendientes (Ámbar) -->
            <div class="mon-bento-card" style="border-top: 3px solid #f59e0b;">
                <div class="mon-flex mon-items-center mon-justify-between">
                    <div>
                        <div class="mon-text-xs mon-font-bold mon-uppercase mon-tracking-wide" style="color: #d97706;">
                            Con Pendientes
                        </div>
                        <div class="mon-text-3xl mon-font-black mon-font-mono mon-tracking-tight" style="color: #b45309; margin-top: 4px;">
                            {{ $pendingCount }}
                        </div>
                    </div>
                    <div class="mon-bento-icon-box" style="background-color: #fef3c7; color: #d97706;">
                        <svg width="22" height="22" style="width: 22px; height: 22px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="mon-text-xs mon-font-medium" style="color: #92400e; margin-top: 8px;">
                    Asignados en espera de inicio
                </div>
            </div>

            <!-- 4: Disponibles (Verde Esmeralda) -->
            <div class="mon-bento-card" style="border-top: 3px solid #10b981; background: linear-gradient(180deg, rgba(236, 253, 245, 0.4) 0%, #ffffff 100%);">
                <div class="mon-flex mon-items-center mon-justify-between">
                    <div>
                        <div class="mon-flex mon-items-center mon-gap-1.5">
                            <span class="mon-pulse-green" style="width: 7px; height: 7px; border-radius: 9999px; background-color: #10b981; display: inline-block;"></span>
                            <span class="mon-text-xs mon-font-bold mon-uppercase mon-tracking-wide" style="color: #059669;">
                                Disponibles (Libres)
                            </span>
                        </div>
                        <div class="mon-text-3xl mon-font-black mon-font-mono mon-tracking-tight" style="color: #047857; margin-top: 4px;">
                            {{ $freeCount }}
                        </div>
                    </div>
                    <div class="mon-bento-icon-box" style="background-color: #d1fae5; color: #059669;">
                        <svg width="22" height="22" style="width: 22px; height: 22px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>
                </div>
                <div class="mon-text-xs mon-font-medium" style="color: #047857; margin-top: 8px;">
                    Listos para nueva asignación
                </div>
            </div>

            <!-- 5: Tickets Sin Cerrar (Azul) -->
            <div class="mon-bento-card" style="border-top: 3px solid #3b82f6;">
                <div class="mon-flex mon-items-center mon-justify-between">
                    <div>
                        <div class="mon-text-xs mon-font-bold mon-uppercase mon-tracking-wide" style="color: #2563eb;">
                            Tickets Sin Cerrar
                        </div>
                        <div class="mon-text-3xl mon-font-black mon-font-mono mon-tracking-tight" style="color: #1d4ed8; margin-top: 4px;">
                            {{ $stats['all'] }}
                        </div>
                    </div>
                    <div class="mon-bento-icon-box" style="background-color: #dbeafe; color: #2563eb;">
                        <svg width="22" height="22" style="width: 22px; height: 22px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                </div>
                <div class="mon-text-xs mon-font-medium" style="color: #1e40af; margin-top: 8px;">
                    En curso, en cola o por cerrar
                </div>
            </div>
        </div>

        <!-- 3. SECCIÓN DE TARJETAS DE TÉCNICOS -->
        <div class="mon-flex mon-items-center mon-justify-between mon-gap-3" style="margin-bottom: 14px;">
            <div class="mon-flex mon-items-center mon-gap-2">
                <span class="mon-flex mon-items-center mon-justify-center" style="width: 28px; height: 28px; border-radius: 8px; background-color: #ecfdf5; color: #008435;">
                    <svg width="16" height="16" style="width: 16px; height: 16px;" class="mon-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </span>
                <h3 class="mon-text-sm mon-font-extrabold mon-uppercase mon-tracking-wide" style="color: #0f172a; margin: 0;">
                    Disponibilidad y Estado del Personal de Soporte
                </h3>
            </div>
            <div class="mon-text-xs mon-font-semibold" style="color: #64748b;">
                Mostrando {{ count($techs) }} técnico(s)
            </div>
        </div>

        <div class="mon-tech-grid">
            @forelse($techs as $item)
                @php
                    $isBusy = ($item['statusKey'] === 'busy');
                    $isPending = ($item['statusKey'] === 'pending');
                    $borderTopColor = $isBusy ? '#ef4444' : ($isPending ? '#f59e0b' : '#10b981');
                    $avatarBg = $isBusy ? 'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)' : ($isPending ? 'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)' : 'linear-gradient(135deg, #10b981 0%, #047857 100%)');
                @endphp

                <div class="mon-tech-card" style="border-top: 4px solid {{ $borderTopColor }};">
                    <!-- PARTE SUPERIOR DE LA TARJETA -->
                    <div>
                        <!-- HEADER DEL TÉCNICO: AVATAR, NOMBRE Y BADGE DE ESTADO -->
                        <div class="mon-flex mon-items-start mon-justify-between mon-gap-3" style="margin-bottom: 14px;">
                            <div class="mon-flex mon-items-center mon-gap-3">
                                <!-- AVATAR CON INICIALES -->
                                <div class="mon-avatar" style="background: {{ $avatarBg }};">
                                    {{ $item['initials'] }}
                                    @if($isBusy)
                                        <span class="mon-pulse-red" style="position: absolute; bottom: -2px; right: -2px; width: 13px; height: 13px; border-radius: 9999px; background-color: #ef4444; border: 2px solid #ffffff;"></span>
                                    @elseif($isPending)
                                        <span style="position: absolute; bottom: -2px; right: -2px; width: 13px; height: 13px; border-radius: 9999px; background-color: #f59e0b; border: 2px solid #ffffff;"></span>
                                    @else
                                        <span style="position: absolute; bottom: -2px; right: -2px; width: 13px; height: 13px; border-radius: 9999px; background-color: #10b981; border: 2px solid #ffffff;"></span>
                                    @endif
                                </div>

                                <!-- NOMBRE Y METADATOS -->
                                <div>
                                    <div class="mon-text-sm mon-font-extrabold mon-uppercase mon-tracking-tight" style="color: #0f172a; line-height: 1.2;">
                                        {{ mb_strtoupper($item['user']->name, 'UTF-8') }}
                                    </div>
                                    <div class="mon-flex mon-items-center mon-gap-1.5" style="margin-top: 4px;">
                                        <span class="mon-font-mono mon-text-xs mon-font-bold" style="padding: 2px 6px; border-radius: 6px; background-color: #f1f5f9; color: #008435; border: 1px solid #e2e8f0;">
                                            {{ mb_strtoupper($item['user']->username ?? 'Sin usuario', 'UTF-8') }}
                                        </span>
                                        @if($item['user']->office)
                                            <span style="color: #cbd5e1;">•</span>
                                            <span class="mon-text-xs mon-font-bold" style="padding: 2px 6px; border-radius: 6px; background-color: #f8fafc; color: #64748b;">
                                                🏢 {{ mb_strtoupper($item['user']->office->acronym ?? $item['user']->office->name, 'UTF-8') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- BADGE DE ESTADO -->
                            <div class="mon-shrink-0">
                                @if($isBusy)
                                    <span class="mon-pill mon-pill-busy">
                                        <span class="mon-pulse-red" style="width: 7px; height: 7px; border-radius: 9999px; background-color: #ef4444; display: inline-block;"></span>
                                        <span>EN ATENCIÓN ({{ $item['activeTickets']->count() }})</span>
                                    </span>
                                @elseif($isPending)
                                    <span class="mon-pill mon-pill-pending">
                                        <span style="width: 7px; height: 7px; border-radius: 9999px; background-color: #f59e0b; display: inline-block;"></span>
                                        <span>CON PENDIENTES</span>
                                    </span>
                                @else
                                    <span class="mon-pill mon-pill-free">
                                        <span style="width: 7px; height: 7px; border-radius: 9999px; background-color: #10b981; display: inline-block;"></span>
                                        <span>DISPONIBLE</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- ESTADO CENTRAL: SI ESTÁ ATENDIENDO O SI ESTÁ DISPONIBLE -->
                        @if($isBusy && $item['activeTickets']->isNotEmpty())
                            @foreach($item['activeTickets'] as $ticket)
                                <div class="mon-active-box" style="margin-bottom: 8px;">
                                    <div class="mon-flex mon-items-center mon-justify-between mon-gap-2" style="margin-bottom: 6px;">
                                        <div class="mon-flex mon-items-center mon-gap-1.5">
                                            <span class="mon-font-mono mon-text-xs mon-font-black" style="color: #b91c1c;">
                                                {{ $ticket->ticket_code }}
                                            </span>
                                            <span class="mon-pill" style="padding: 1px 6px; font-size: 9.5px; background: #fee2e2; color: #991b1b;">
                                                {{ strtoupper($ticket->priority ?? 'MEDIA') }}
                                            </span>
                                        </div>
                                        @if($ticket->elapsed_attention_time)
                                            <div class="mon-flex mon-items-center mon-gap-1 mon-text-xs mon-font-bold" style="color: #b91c1c;">
                                                <svg width="13" height="13" style="width: 13px; height: 13px;" class="mon-icon-xs" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                <span>{{ $ticket->elapsed_attention_time }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mon-text-xs mon-font-bold" style="color: #0f172a; line-height: 1.3; margin-bottom: 6px;">
                                        {{ Str::limit($ticket->title ?? $ticket->description ?? 'Incidencia en atención presencial', 85) }}
                                    </div>

                                    <div class="mon-text-xs" style="color: #64748b; margin-bottom: 10px;">
                                        👤 {{ $ticket->user?->name ?? 'Usuario no especificado' }}
                                        @if($ticket->user?->office)
                                            • 🏢 {{ $ticket->user->office->acronym ?? $ticket->user->office->name }}
                                        @endif
                                    </div>

                                    <div class="mon-flex mon-items-center mon-gap-2">
                                        <a href="{{ route('fichas.ticket', $ticket->id) }}" target="_blank" class="mon-btn-outline">
                                            <svg width="13" height="13" style="width: 13px; height: 13px;" class="mon-icon-xs" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.867-2.025-1.782-2.731C3.12 9.72 2.25 7.97 2.25 6A4.5 4.5 0 0 1 6.75 1.5h10.5A4.5 4.5 0 0 1 21.75 6c0 1.97-.87 3.72-2.688 5.098-.915.706-1.542 1.675-1.782 2.731M6.72 13.829l-1.04 4.576A1.5 1.5 0 0 0 7.143 20.25h9.714a1.5 1.5 0 0 0 1.463-1.845l-1.04-4.576" />
                                            </svg>
                                            <span>Ficha ↗</span>
                                        </a>
                                        <a href="/helpdesk/tickets/{{ $ticket->id }}/edit" class="mon-btn-filled">
                                            <span>Gestionar →</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="mon-free-box">
                                <div class="mon-flex mon-items-center mon-justify-center mon-gap-1.5" style="color: #15803d;">
                                    <svg width="18" height="18" style="width: 18px; height: 18px;" class="mon-icon-md" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="mon-text-xs mon-font-black mon-uppercase mon-tracking-wide">
                                        Disponible para asignación
                                    </span>
                                </div>
                                <div class="mon-text-xs mon-font-medium" style="color: #64748b; margin-top: 3px;">
                                    Sin incidencias en proceso. Listo para nuevo despacho.
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- PIE DE TARJETA CON MÉTRICAS DEL TÉCNICO -->
                    <div class="mon-flex mon-items-center mon-justify-between" style="padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #64748b;">
                        <div>
                            Hoy resueltos: <strong style="color: #0f172a;">{{ $item['resolvedToday'] }}</strong>
                        </div>
                        <div>
                            Total pendientes: <strong style="color: {{ $item['pendingTickets']->count() > 0 ? '#b45309' : '#059669' }};">{{ $item['pendingTickets']->count() }}</strong>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <p class="mon-text-sm mon-font-bold" style="color: #64748b;">No hay personal técnico asignado actualmente en este subsistema.</p>
                </div>
            @endforelse
        </div>

        <!-- 4. CONTROL GENERAL DE TICKETS SIN CERRAR EN EL SISTEMA -->
        <div class="mon-table-card">
            <!-- CABECERA Y FILTROS INTERACTIVOS -->
            <div class="mon-flex mon-items-center mon-justify-between mon-flex-wrap mon-gap-3" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background-color: #ffffff;">
                <div>
                    <div class="mon-flex mon-items-center mon-gap-2">
                        <span class="mon-flex mon-items-center mon-justify-center" style="width: 28px; height: 28px; border-radius: 8px; background-color: #dbeafe; color: #1d4ed8;">
                            <svg width="16" height="16" style="width: 16px; height: 16px;" class="mon-icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </span>
                        <h3 class="mon-text-sm mon-font-extrabold mon-uppercase mon-tracking-wide" style="color: #0f172a; margin: 0;">
                            Control General de Tickets Sin Cerrar
                        </h3>
                        <span class="mon-font-mono mon-text-xs mon-font-black" style="padding: 2px 8px; border-radius: 9999px; background-color: #dbeafe; color: #1e40af;">
                            {{ $stats['all'] }}
                        </span>
                    </div>
                    <div class="mon-text-xs" style="color: #64748b; margin-top: 3px;">
                        Listado completo de incidencias abiertas, en atención presencial o resueltas pendientes de cierre final.
                    </div>
                </div>

                <!-- BOTONES DE FILTRO REACTIVO -->
                <div class="mon-flex mon-items-center mon-flex-wrap mon-gap-1.5">
                    <button wire:click="setTicketFilter('all')" type="button" class="mon-tab-btn {{ $currentFilter === 'all' ? 'active' : '' }}">
                        <span>Todos</span>
                        <span class="mon-font-mono" style="opacity: 0.85;">({{ $stats['all'] }})</span>
                    </button>
                    <button wire:click="setTicketFilter('in_progress')" type="button" class="mon-tab-btn {{ $currentFilter === 'in_progress' ? 'active' : '' }}">
                        <span>⚡ En Atención</span>
                        <span class="mon-font-mono" style="opacity: 0.85;">({{ $stats['in_progress'] }})</span>
                    </button>
                    <button wire:click="setTicketFilter('open')" type="button" class="mon-tab-btn {{ $currentFilter === 'open' ? 'active' : '' }}">
                        <span>📌 En Cola / Abiertos</span>
                        <span class="mon-font-mono" style="opacity: 0.85;">({{ $stats['open'] }})</span>
                    </button>
                    <button wire:click="setTicketFilter('resolved')" type="button" class="mon-tab-btn {{ $currentFilter === 'resolved' ? 'active' : '' }}">
                        <span>⏳ Por Cerrar</span>
                        <span class="mon-font-mono" style="opacity: 0.85;">({{ $stats['resolved'] }})</span>
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div style="overflow-x: auto;">
                <table class="mon-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Ticket / Prioridad</th>
                            <th>Asunto & Solicitante</th>
                            <th style="width: 190px;">Técnico Asignado</th>
                            <th style="width: 140px;">Estado</th>
                            <th style="width: 130px;">Creación</th>
                            <th style="width: 140px; text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unclosed as $t)
                            @php
                                $statusLower = strtolower(trim($t->status ?? ''));
                                $priorityLower = strtolower(trim($t->priority ?? 'media'));
                                $pColor = match($priorityLower) {
                                    'alta', 'urgente' => '#ef4444',
                                    'baja' => '#10b981',
                                    default => '#f59e0b',
                                };
                                $pBg = match($priorityLower) {
                                    'alta', 'urgente' => '#fee2e2',
                                    'baja' => '#d1fae5',
                                    default => '#fef3c7',
                                };
                                $stBadge = match($statusLower) {
                                    'en proceso', 'en_proceso', 'internado' => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => '⚡ EN PROCESO'],
                                    'resuelto' => ['bg' => '#e0e7ff', 'color' => '#4338ca', 'label' => '⏳ POR CERRAR'],
                                    'en espera', 'esperando terceros' => ['bg' => '#fef3c7', 'color' => '#d97706', 'label' => '⏳ EN ESPERA'],
                                    default => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'label' => '📌 ABIERTO'],
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="mon-flex mon-flex-col mon-gap-1">
                                        <span class="mon-font-mono mon-text-xs mon-font-black" style="color: #0f172a;">
                                            {{ $t->ticket_code }}
                                        </span>
                                        <span class="mon-pill" style="align-self: flex-start; padding: 1px 6px; font-size: 9.5px; background-color: {{ $pBg }}; color: {{ $pColor }};">
                                            {{ strtoupper($t->priority ?? 'MEDIA') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mon-text-xs mon-font-bold" style="color: #0f172a; margin-bottom: 2px;">
                                        {{ Str::limit($t->title ?? $t->description ?? 'Sin descripción', 70) }}
                                    </div>
                                    <div class="mon-text-xs" style="color: #64748b;">
                                        👤 {{ $t->user?->name ?? 'Usuario no especificado' }}
                                        @if($t->user?->office)
                                            • 🏢 {{ $t->user->office->acronym ?? $t->user->office->name }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($t->assignee)
                                        <div class="mon-flex mon-items-center mon-gap-2">
                                            <div class="mon-flex mon-items-center mon-justify-center mon-text-xs mon-font-black" style="width: 26px; height: 26px; border-radius: 8px; background-color: #008435; color: #ffffff;">
                                                {{ substr($t->assignee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="mon-text-xs mon-font-bold" style="color: #0f172a;">
                                                    {{ mb_strtoupper($t->assignee->name, 'UTF-8') }}
                                                </div>
                                                <div class="mon-font-mono mon-text-xs" style="color: #64748b; font-size: 10px;">
                                                    {{ '@' . ($t->assignee->username ?? 'soporte') }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="mon-pill" style="background-color: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1;">
                                            SIN ASIGNAR
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="mon-pill" style="background-color: {{ $stBadge['bg'] }}; color: {{ $stBadge['color'] }};">
                                        {{ $stBadge['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="mon-text-xs mon-font-medium" style="color: #64748b;">
                                        {{ $t->created_at ? $t->created_at->diffForHumans() : '-' }}
                                    </div>
                                    <div class="mon-text-xs" style="color: #94a3b8; font-size: 10px;">
                                        {{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '' }}
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <div class="mon-flex mon-items-center mon-justify-end mon-gap-1.5">
                                        <a href="{{ route('fichas.ticket', $t->id) }}" target="_blank" class="mon-btn-outline" style="padding: 4px 8px; font-size: 11px;">
                                            <span>Ficha ↗</span>
                                        </a>
                                        <a href="/helpdesk/tickets/{{ $t->id }}/edit" class="mon-btn-filled" style="padding: 4px 8px; font-size: 11px;">
                                            <span>Ver →</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">
                                    <div class="mon-flex mon-flex-col mon-items-center mon-justify-center mon-gap-1.5">
                                        <svg width="24" height="24" style="width: 24px; height: 24px;" class="mon-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span class="mon-text-sm mon-font-bold">No hay tickets pendientes en este filtro.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>