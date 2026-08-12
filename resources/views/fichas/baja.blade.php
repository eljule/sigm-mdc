<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Baja {{ $decommission->ficha_number }}</title>
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
            color: #dc2626;
        }
        .alert-baja {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-left: 5px solid #dc2626;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 30px;
            font-weight: 600;
            color: #991b1b;
            font-size: 13px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            background-color: #f1f5f9;
            padding: 6px 12px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 4px solid #dc2626;
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
            width: 170px;
            min-width: 170px;
            color: #475569;
        }
        .info-value {
            flex-grow: 1;
            color: #0f172a;
        }
        .info-value.danger {
            color: #dc2626;
            font-weight: 700;
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
        .badge-resolution {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 700;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 8px;
        }
        .history-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-align: left;
            padding: 6px 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .history-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 80px;
            text-align: center;
        }
        .signature-block { }
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
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
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
        .btn-print { background-color: #2563eb; color: #ffffff; }
        .btn-print:hover { background-color: #1d4ed8; }
        .btn-close { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-close:hover { background-color: #e2e8f0; }
        @media print {
            body { padding: 0; }
            .no-print, .actions-panel { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Encabezado --}}
    <div class="header">
        <div class="header-logo">Municipalidad de Castilla</div>
        <div class="header-title">
            Mesa de Ayuda (Helpdesk) & ITAM<br>
            Sistema Integrado de Gestión Municipal – SIGM-MDC
        </div>
    </div>

    {{-- Título --}}
    <div class="title-block">
        <h1>Acta de Baja de Activo Tecnológico</h1>
        <div class="doc-number">{{ $decommission->ficha_number }}</div>
    </div>

    {{-- Alerta visual --}}
    <div class="alert-baja">
        ⚠ Este documento certifica la baja definitiva del activo tecnológico indicado. El activo no podrá ser asignado nuevamente.
    </div>

    {{-- SECCIÓN 1: Información del Activo --}}
    <div class="section-title">1. Información del Activo</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Categoría:</div>
            <div class="info-value">{{ $decommission->asset->category->name ?? '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Marca / Modelo:</div>
            <div class="info-value">{{ $decommission->asset->model?->brand?->name ?? '—' }} / {{ $decommission->asset->model?->name ?? '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Informático:</div>
            <div class="info-value">{{ $decommission->asset->computer_code ?? '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Patrimonial:</div>
            <div class="info-value">{{ $decommission->asset->asset_code ?? '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Nro. de Serie:</div>
            <div class="info-value">{{ $decommission->asset->serial_number ?? '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Estado Final:</div>
            <div class="info-value danger">BAJA DEFINITIVA</div>
        </div>
    </div>

    {{-- SECCIÓN 2: Resolución de Baja --}}
    <div class="section-title">2. Resolución de Baja</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Fecha de Baja:</div>
            <div class="info-value">{{ $decommission->decommissioned_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Técnico Responsable:</div>
            <div class="info-value">{{ $decommission->decommissionedBy?->name ?? 'No registrado' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Motivo de Baja:</div>
            <div class="info-value">{{ $decommission->reason }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tipo de Resolución:</div>
            <div class="info-value">
                <span class="badge-resolution">{{ $decommission->resolution_type }}</span>
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Autorizado por:</div>
            <div class="info-value">{{ $decommission->authorized_by ?? 'No especificado' }}</div>
        </div>
        @if ($decommission->ticket)
        <div class="info-item">
            <div class="info-label">Ticket de Origen:</div>
            <div class="info-value">
                <a href="{{ route('fichas.ticket', ['id' => $decommission->ticket->id]) }}"
                   target="_blank"
                   style="color:#2563eb;font-weight:600;text-decoration:underline;">
                    {{ $decommission->ticket->ticket_code }}
                </a>
                — {{ $decommission->ticket->title }}
            </div>
        </div>
        @endif
    </div>

    {{-- SECCIÓN 3: Dictamen Técnico --}}
    @if ($decommission->evaluation_summary)
    <div class="section-title">3. Dictamen Técnico</div>
    <div class="info-item-full">
        <div class="info-value-full" style="white-space:pre-wrap;">{{ $decommission->evaluation_summary }}</div>
    </div>
    @endif

    {{-- SECCIÓN 4: Historial de Asignaciones --}}
    @if ($decommission->asset->assignments->count() > 0)
    <div class="section-title">4. Historial de Asignaciones</div>
    <table class="history-table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Oficina</th>
                <th>Fecha Asignación</th>
                <th>Fecha Devolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($decommission->asset->assignments as $assignment)
            <tr>
                <td>{{ $assignment->user?->name ?? '—' }}</td>
                <td>{{ $assignment->office?->name ?? '—' }}</td>
                <td>{{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d/m/Y') : '—' }}</td>
                <td>{{ $assignment->returned_at ? \Carbon\Carbon::parse($assignment->returned_at)->format('d/m/Y') : 'Activa' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- SECCIÓN 5: Firmas --}}
    <div class="signatures">
        <div class="signature-block">
            <div class="signature-line">{{ $decommission->decommissionedBy?->name ?? '________________________' }}</div>
            <div class="signature-title">Técnico Responsable TI</div>
        </div>
        <div class="signature-block">
            <div class="signature-line">{{ $decommission->authorized_by ?? '________________________' }}</div>
            <div class="signature-title">Responsable / Jefe de Área que Autoriza</div>
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="actions-panel no-print">
        <button class="btn btn-close" onclick="window.close()">Cerrar Pestaña</button>
        <button class="btn btn-print" onclick="window.print()">Imprimir Ficha</button>
    </div>

</body>
</html>
