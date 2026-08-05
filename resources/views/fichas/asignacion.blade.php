<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Asignación de Activo Informático #{{ $assignment->id }}</title>
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
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .terms {
            font-size: 12px;
            color: #475569;
            text-align: justify;
            margin-top: 30px;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            background-color: #fafafa;
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
        <div class="header-logo">Municipalidad de Castilla</div>
        <div class="header-title">
            Subgerencia de Informática y Tecnología<br>
            Sistema Integrado de Gestión Municipal - SIGM-MDC
        </div>
    </div>

    <div class="title-block">
        <h1>Acta de Asignación y Entrega de Equipo Informático</h1>
        <div class="doc-number">N° ACTA-ASIG-{{ str_pad((string)$assignment->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section-title">1. Datos del Solicitante / Servidor</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Nombres y Apellidos:</div>
            <div class="info-value">{{ $assignment->user->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cargo / Condición:</div>
            <div class="info-value">{{ $assignment->user->laborCondition->name ?? 'N/D' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Oficina / Dependencia:</div>
            <div class="info-value">{{ $assignment->office->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Fecha de Asignación:</div>
            <div class="info-value">{{ $assignment->assigned_at->format('d/m/Y H:i A') }}</div>
        </div>
    </div>

    <div class="section-title">2. Detalles del Activo Asignado</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Categoría del Activo:</div>
            <div class="info-value">{{ $assignment->asset->category->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Marca / Modelo:</div>
            <div class="info-value">{{ $assignment->asset->model->brand->name }} / {{ $assignment->asset->model->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Informático:</div>
            <div class="info-value" style="font-family: monospace; font-weight: bold;">{{ $assignment->asset->computer_code }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Cód. Patrimonial:</div>
            <div class="info-value">{{ $assignment->asset->asset_code ?? 'N/D' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Nro. de Serie:</div>
            <div class="info-value">{{ $assignment->asset->serial_number }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Estado de Entrega:</div>
            <div class="info-value" style="font-weight: bold; color: #16a34a;">{{ $assignment->asset->status }}</div>
        </div>
    </div>

    @if($assignment->asset->components->count() > 0)
        <div class="section-title">3. Componentes y Periféricos Vinculados al Equipo</div>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Cód. Informático</th>
                    <th>Cód. Patrimonial</th>
                    <th>Marca / Modelo</th>
                    <th>Nro. Serie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignment->asset->components as $component)
                    <tr>
                        <td><strong>{{ $component->category->name }}</strong></td>
                        <td style="font-family: monospace;">{{ $component->computer_code }}</td>
                        <td>{{ $component->asset_code ?? 'N/D' }}</td>
                        <td>{{ $component->model->brand->name ?? '' }} / {{ $component->model->name ?? '' }}</td>
                        <td>{{ $component->serial_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">4. Observaciones adicionales</div>
    <div style="margin-bottom: 25px; padding: 10px; border-bottom: 1px dashed #cbd5e1; font-style: italic;">
        {{ $assignment->notes ?? 'Sin observaciones adicionales.' }}
    </div>

    <div class="terms">
        <strong>TÉRMINOS Y CONDICIONES DE LA ASIGNACIÓN:</strong><br>
        El servidor municipal firmante declara recibir a su entera conformidad los bienes descritos en la presente acta. Se compromete a custodiar, dar el uso adecuado y exclusivo para funciones laborales al equipo asignado. Cualquier desperfecto técnico o físico debe ser reportado de inmediato a la Subgerencia de Informática y Tecnología. En caso de retiro o reubicación, los bienes deberán ser devueltos en las mismas condiciones operativas en las que fueron entregados.
    </div>

    <div class="signatures">
        <div>
            <div class="signature-line">Entregado por (IT / Soporte)</div>
            <div class="signature-title">Firma y Sello del Técnico</div>
        </div>
        <div>
            <div class="signature-line">Recibido por (Servidor Municipal)</div>
            <div class="signature-title">Firma del Servidor / DNI</div>
        </div>
    </div>

    <div class="actions-panel no-print">
        <button class="btn btn-close" onclick="window.close()">Cerrar Pestaña</button>
        <button class="btn btn-print" onclick="window.print()">Imprimir Acta</button>
    </div>

</body>
</html>
