<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Mantenimiento #{{ $maintenance->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-logo {
            font-weight: 700;
            font-size: 18px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-title {
            text-align: right;
            font-size: 12px;
            color: #64748b;
        }
        .title-block {
            text-align: center;
            margin-bottom: 40px;
        }
        .title-block h1 {
            font-size: 20px;
            margin: 0 0 10px 0;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-block .doc-number {
            font-size: 16px;
            font-weight: 600;
            color: #2563eb;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            background-color: #f1f5f9;
            padding: 6px 12px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 4px solid #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 8px;
        }
        .info-label {
            font-weight: 600;
            width: 150px;
            color: #475569;
        }
        .info-value {
            flex-grow: 1;
            color: #0f172a;
        }
        .info-item-full {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .info-label-full {
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }
        .info-value-full {
            color: #0f172a;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr;
            margin-top: 100px;
            text-align: center;
            justify-content: center;
        }
        .signature-block {
            max-width: 300px;
            margin: 0 auto;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            padding-top: 10px;
            font-weight: 600;
        }
        .signature-title {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .actions-panel {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ffffff;
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 10px;
            border: 1px solid #e2e8f0;
        }
        .btn {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease-in-out;
        }
        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
        .btn-close {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-close:hover {
            background-color: #e2e8f0;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .actions-panel {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo">Municipalidad de Castilla</div>
        <div class="header-title">
            Subgerencia de Informática y Tecnología<br>
            Sistema Integrado de Gestión Municipal - SIGM-MDC
        </div>
    </div>

    <div class="title-block">
        <h1>Ficha de Registro de Mantenimiento Técnico</h1>
        <div class="doc-number">N° FICH-MANT-{{ str_pad((string)$maintenance->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section-title">1. Información del Activo</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Categoría del Activo:</div>
            <div class="info-value">{{ $maintenance->asset->category->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Marca / Modelo:</div>
            <div class="info-value">{{ $maintenance->asset->model->brand->name }} / {{ $maintenance->asset->model->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Informático:</div>
            <div class="info-value" style="font-family: monospace; font-weight: bold;">{{ $maintenance->asset->computer_code }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Patrimonial:</div>
            <div class="info-value">{{ $maintenance->asset->asset_code ?? 'N/D' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Nro. de Serie:</div>
            <div class="info-value">{{ $maintenance->asset->serial_number }}</div>
        </div>
    </div>

    <div class="section-title">2. Detalles del Mantenimiento</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Tipo Mantenimiento:</div>
            <div class="info-value" style="font-weight: bold; color: #2563eb;">{{ $maintenance->type }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Costo Asociado:</div>
            <div class="info-value" style="font-weight: bold;">S/. {{ number_format((float)$maintenance->cost, 2) }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">F. Programada:</div>
            <div class="info-value">{{ $maintenance->scheduled_date->format('d/m/Y') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">F. Realización:</div>
            <div class="info-value">{{ $maintenance->performed_date ? $maintenance->performed_date->format('d/m/Y') : 'Pendiente' }}</div>
        </div>
    </div>

    <div class="info-item-full">
        <div class="info-label-full">Descripción del Problema o Tareas Programadas:</div>
        <div class="info-value-full">
            @php
                // Estrategia 1: si hay ticket vinculado directamente por ticket_id,
                // usamos su URL directamente (más fiable).
                $ticketUrl = null;
                if ($maintenance->ticket) {
                    $ticketUrl = route('fichas.ticket', ['id' => $maintenance->ticket->id]);
                }

                // Estrategia 2: convertir menciones textuales del patrón "Ticket: INC-XXXX-XXXXX"
                // en hipervínculos, buscando el ticket por su código.
                $description = e($maintenance->description);
                $description = preg_replace_callback(
                    '/Ticket:\s*([A-Z]+-\d{4}-\d{4,})/i',
                    function ($matches) {
                        $code = $matches[1];
                        $ticket = \App\Models\Ticket::where('ticket_code', $code)->first();
                        if ($ticket) {
                            $url = route('fichas.ticket', ['id' => $ticket->id]);
                            return 'Ticket: <a href="' . $url . '" target="_blank" style="color:#008435;font-weight:600;text-decoration:underline;">' . $code . '</a>';
                        }
                        // Si no encuentra el ticket, deja el texto tal cual
                        return 'Ticket: ' . $code;
                    },
                    $description
                );
            @endphp

            {{-- Badge de enlace directo si hay ticket_id vinculado --}}
            @if ($ticketUrl && $maintenance->ticket)
                <div style="margin-bottom:2px;margin-top:0;">
                    <a href="{{ $ticketUrl }}" target="_blank"
                       style="display:inline-flex;align-items:center;gap:5px;background:#e6f4eb;color:#008435;border:1px solid #008435;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;text-decoration:none;line-height:1.4;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Ver Ticket: {{ $maintenance->ticket->ticket_code }}
                    </a>
                </div>
            @endif

            <span style="white-space:pre-wrap;">{!! $description !!}</span>
        </div>
    </div>

    <div class="info-item-full">
        <div class="info-label-full">Notas del Técnico & Tareas Realizadas:</div>
        <div class="info-value-full">{{ $maintenance->technician_notes ?? 'Sin observaciones registradas.' }}</div>
    </div>

    <div class="signatures">
        <div class="signature-block">
            <div class="signature-line">Firma del Especialista Técnico</div>
            <div class="signature-title">Responsable del Servicio TI</div>
        </div>
    </div>

    <div class="actions-panel no-print">
        <button class="btn btn-close" onclick="window.close()">Cerrar Pestaña</button>
        <button class="btn btn-print" onclick="window.print()">Imprimir Ficha</button>
    </div>

</body>
</html>
