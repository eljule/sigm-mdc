<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Conformidad de Ticket {{ $ticket->ticket_code }}</title>
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
            display: flex;
            align-items: center;
        }
        .header-logo img {
            height: 56px;
            width: auto;
            max-width: 260px;
            object-fit: contain;
            display: block;
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
            gap: 12px 24px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            align-items: baseline;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }
        .info-label {
            font-weight: 600;
            width: 160px;
            min-width: 160px;
            color: #475569;
            font-size: 13.5px;
        }
        .info-value {
            flex-grow: 1;
            color: #0f172a;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            line-height: 1.45;
        }
        .info-item-full {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .info-label-full {
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            font-size: 13.5px;
        }
        .info-value-full {
            color: #0f172a;
            white-space: pre-wrap;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            line-height: 1.5;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 10px 12px;
            font-size: 12px;
            text-transform: uppercase;
        }
        table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #0f172a;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            margin-top: 80px;
            text-align: center;
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
        <div class="header-logo"><img src="{{ asset('images/logo-castilla.png') }}" alt="Municipalidad de Castilla"></div>
        <div class="header-title">
            Mesa de Ayuda (Helpdesk) & ITAM<br>
            Sistema Integrado de Gestión Municipal - SIGM-MDC
        </div>
    </div>

    <div class="title-block">
        <h1>Ficha de Atención e Insumos de Incidencia</h1>
        <div class="doc-number">{{ $ticket->ticket_code }}</div>
    </div>

    <div class="section-title">1. Información General del Incidente</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Solicitante:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->requester?->name ?? $ticket->user?->name ?? 'N/D', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Oficina / Dependencia:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->office?->name ?? 'N/D', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Título / Asunto:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->title ?? '', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Fecha de Cierre:</div>
            <div class="info-value">{{ ($ticket->closed_at ?? $ticket->resolved_at ?? now())->format('d/m/Y H:i A') }}</div>
        </div>
    </div>

    <div class="info-item-full">
        <div class="info-label-full">Descripción del Síntoma:</div>
        <div class="info-value-full">{{ mb_strtoupper($ticket->description ?? '', 'UTF-8') }}</div>
    </div>

    <div class="section-title">2. Diagnóstico y Solución Técnica (Soporte TI)</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Categorización Real:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->category?->name ?? 'N/D', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Técnico Asignado:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->assignee?->name ?? 'N/D', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Causa Raíz / Cierre:</div>
            <div class="info-value">{{ mb_strtoupper($ticket->root_cause ?? 'N/D', 'UTF-8') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Estado del Ticket:</div>
            <div class="info-value" style="font-weight: bold; color: #16a34a;">{{ mb_strtoupper($ticket->status ?? '', 'UTF-8') }}</div>
        </div>
    </div>

    @if($ticket->affectedAsset || $ticket->replacementAsset)
        <div class="section-title">3. Activos Asociados al Incidente (ITAM)</div>
        <div class="grid-info">
            <div class="info-item">
                <div class="info-label">Activo Retirado:</div>
                <div class="info-value">
                    @if($ticket->affectedAsset)
                        [{{ $ticket->affectedAsset->computer_code }}] {{ mb_strtoupper($ticket->affectedAsset->category?->name ?? '', 'UTF-8') }} (S/N: {{ mb_strtoupper($ticket->affectedAsset->serial_number ?? '', 'UTF-8') }})
                    @else
                        NINGUNO / NO APLICA
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Activo de Repuesto:</div>
                <div class="info-value">
                    @if($ticket->replacementAsset)
                        [{{ $ticket->replacementAsset->computer_code }}] {{ mb_strtoupper($ticket->replacementAsset->category?->name ?? '', 'UTF-8') }} (S/N: {{ mb_strtoupper($ticket->replacementAsset->serial_number ?? '', 'UTF-8') }})
                    @else
                        NINGUNO / NO APLICA
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="info-item-full">
        <div class="info-label-full">Diagnóstico Técnico Detallado:</div>
        <div class="info-value-full">{{ mb_strtoupper($ticket->diagnosis ?? 'Sin diagnóstico registrado.', 'UTF-8') }}</div>
    </div>

    <div class="info-item-full">
        <div class="info-label-full">Solución Técnica Aplicada:</div>
        <div class="info-value-full">{{ mb_strtoupper($ticket->solution_applied ?? 'Sin solución registrada.', 'UTF-8') }}</div>
    </div>

    @if($ticket->ticketConsumables->count() > 0)
        <div class="section-title">4. Materiales y Consumibles Utilizados en la Atención</div>
        <table>
            <thead>
                <tr>
                    <th>Descripción del Insumo / Material</th>
                    <th>Cantidad</th>
                    <th>Unidad de Medida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->ticketConsumables as $tc)
                    <tr>
                        <td><strong>{{ mb_strtoupper($tc->consumable->name, 'UTF-8') }}</strong></td>
                        <td>{{ $tc->quantity }}</td>
                        <td>{{ mb_strtoupper($tc->consumable->unit, 'UTF-8') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="signatures">
        <div>
            <div class="signature-line">Firma del Especialista TI</div>
            <div class="signature-title">Técnico Resolutor</div>
        </div>
        <div>
            <div class="signature-line">Firma de Conformidad</div>
            <div class="signature-title">Servidor Municipal Solicitante</div>
        </div>
    </div>

    <div class="actions-panel no-print">
        <button class="btn btn-close" onclick="window.close()">Cerrar Pestaña</button>
        <button class="btn btn-print" onclick="window.print()">Imprimir Ficha</button>
    </div>

</body>
</html>
