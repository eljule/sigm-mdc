<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Vinculación de Componente #{{ $asset->id }}</title>
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
        .comparison-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        .comparison-box {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            background-color: #fafafa;
        }
        .comparison-box.highlight {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .box-title {
            font-weight: 700;
            font-size: 13px;
            color: #475569;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .box-title.highlight {
            color: #2563eb;
            border-bottom-color: #bfdbfe;
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
        <h1>Constancia de Integración de Componente / Periférico</h1>
        <div class="doc-number">N° FICH-COMP-{{ str_pad((string)$asset->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section-title">1. Fecha y Registro</div>
    <div class="grid-info">
        <div class="info-item">
            <div class="info-label">Fecha de Integración:</div>
            <div class="info-value">{{ $asset->updated_at->format('d/m/Y H:i A') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Técnico Operador:</div>
            <div class="info-value">Especialista de Soporte TI</div>
        </div>
    </div>

    <div class="comparison-container">
        <!-- Activo Padre -->
        <div class="comparison-box">
            <div class="box-title">Activo Principal (Equipo Receptor)</div>
            @if($asset->parent)
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div><strong>Categoría:</strong> {{ $asset->parent->category->name }}</div>
                    <div><strong>Marca / Modelo:</strong> {{ $asset->parent->model->brand->name }} / {{ $asset->parent->model->name }}</div>
                    <div><strong>Cód. Informático:</strong> <span style="font-family: monospace; font-weight: bold;">{{ $asset->parent->computer_code }}</span></div>
                    <div><strong>Cód. Patrimonial:</strong> {{ $asset->parent->asset_code ?? 'N/D' }}</div>
                    <div><strong>Nro. Serie:</strong> {{ $asset->parent->serial_number }}</div>
                    <div><strong>Estado PC:</strong> {{ $asset->parent->status }}</div>
                </div>
            @else
                <div style="color: #ef4444; font-weight: 600; text-align: center; margin-top: 20px;">
                    ¡Sin Activo Principal Vinculado!
                </div>
            @endif
        </div>

        <!-- Componente Vinculado -->
        <div class="comparison-box highlight">
            <div class="box-title highlight">Componente / Periférico Integrado</div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div><strong>Categoría:</strong> {{ $asset->category->name }}</div>
                <div><strong>Marca / Modelo:</strong> {{ $asset->model->brand->name }} / {{ $asset->model->name }}</div>
                <div><strong>Cód. Informático:</strong> <span style="font-family: monospace; font-weight: bold;">{{ $asset->computer_code }}</span></div>
                <div><strong>Cód. Patrimonial:</strong> {{ $asset->asset_code ?? 'N/D' }}</div>
                <div><strong>Nro. Serie:</strong> {{ $asset->serial_number }}</div>
                <div><strong>Estado Componente:</strong> {{ $asset->status }}</div>
            </div>
        </div>
    </div>

    <div class="section-title">2. Notas y Observaciones de Integración</div>
    <div style="margin-bottom: 25px; padding: 15px; border: 1px dashed #cbd5e1; border-radius: 6px; font-style: italic; background-color: #fafafa;">
        {{ $asset->notes ?? 'Se realiza la vinculación y acoplamiento físico y lógico del periférico en mención al equipo de cómputo principal a fin de reestablecer/mejorar operatividad del usuario.' }}
    </div>

    <div class="signatures">
        <div class="signature-block">
            <div class="signature-line">Firma del Especialista TI</div>
            <div class="signature-title">Subgerencia de Informática y Tecnología</div>
        </div>
    </div>

    <div class="actions-panel no-print">
        <button class="btn btn-close" onclick="window.close()">Cerrar Pestaña</button>
        <button class="btn btn-print" onclick="window.print()">Imprimir Constancia</button>
    </div>

</body>
</html>
