<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Entrega {{ $delivery->delivery_number }}</title>
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
        .old-header-logo {
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
            margin-bottom: 30px;
        }
        .title-block h1 {
            font-size: 20px;
            margin: 0 0 8px 0;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-block .doc-number {
            font-size: 16px;
            font-weight: 700;
            color: #2563eb;
        }
        .alert-delivery {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 5px solid #2563eb;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #1e40af;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .data-group {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }
        .data-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
        }
        .data-value {
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        table.items-table th {
            background-color: #0f172a;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }
        table.items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        table.items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .signatures-block {
            margin-top: 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px dashed #475569;
            margin-top: 60px;
            margin-bottom: 8px;
        }
        .signature-name {
            font-weight: 700;
            font-size: 13px;
            color: #0f172a;
        }
        .signature-role {
            font-size: 12px;
            color: #64748b;
        }
        .print-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            background: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn {
            background: #0f172a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-secondary {
            background: #64748b;
        }
        @media print {
            .print-actions { display: none; }
            body { padding: 0; }
        }
    
        
        /* RECOVERED DATA STYLING: ONLY DATA IN UPPERCASE & SMALL LEGIBLE SIZE */
        .info-value {
            color: #0f172a !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
            line-height: 1.45 !important;
        }
        .info-value-full {
            color: #0f172a !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.02em !important;
            line-height: 1.5 !important;
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 8px 12px !important;
        }
        .data-value {
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            color: #0f172a !important;
            letter-spacing: 0.025em !important;
        }
        table td {
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
            color: #0f172a !important;
            padding: 8px 12px !important;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo"><img src="{{ asset('images/logo-castilla.png') }}" alt="Municipalidad de Castilla"></div>
        <div class="header-title">
            <strong>SIGM-MDC</strong><br>
            Sistema Integrado de Gestión Municipal
        </div>
    </div>

    <div class="title-block">
        <h1>ACTA DE ENTREGA DE INSUMOS Y CONSUMIBLES</h1>
        <div class="doc-number">{{ $delivery->delivery_number }}</div>
    </div>

    <div class="alert-delivery">
        📦 Constancia de entrega de materiales e insumos de oficina / computación.
    </div>

    <div class="section-title">1. Datos Generales de la Entrega</div>
    <div class="grid-2">
        <div class="data-group">
            <div class="data-label">Fecha y Hora de Entrega</div>
            <div class="data-value">{{ $delivery->delivered_at->format('d/m/Y H:i A') }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Oficina / Dependencia Destino</div>
            <div class="data-value">{{ $delivery->office?->name ?? 'No especificada' }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Entregado Por (Personal TI)</div>
            <div class="data-value">{{ $delivery->delivered_by }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Recibido Por (Solicitante / Beneficiario)</div>
            <div class="data-value">{{ $delivery->received_by }}</div>
        </div>
    </div>

    <div class="section-title">2. Insumos y Consumibles Entregados</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">N°</th>
                <th>Descripción del Insumo / Consumible</th>
                <th style="width: 140px;">Unidad de Medida</th>
                <th style="width: 120px; text-align: center;">Cantidad Entregada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delivery->items as $index => $item)
                <tr>
                    <td style="text-align: center; font-weight: 600;">{{ $index + 1 }}</td>
                    <td style="font-weight: 600;">{{ $item->consumable?->name ?? 'Insumo eliminado' }}</td>
                    <td>{{ $item->consumable?->unit ?? 'Unidad' }}</td>
                    <td style="text-align: center; font-weight: 700; color: #2563eb;">{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($delivery->notes)
        <div class="section-title">3. Observaciones</div>
        <div class="data-group">
            <div class="data-value" style="font-weight: 400; font-style: italic;">
                {{ $delivery->notes }}
            </div>
        </div>
    @endif

    <div class="section-title">4. Conformidad y Firmas</div>
    <div class="signatures-block">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $delivery->delivered_by }}</div>
            <div class="signature-role">ENTREGÓ (PERSONAL TI)</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $delivery->received_by }}</div>
            <div class="signature-role">RECIBIÓ CONFORME (DNI / CARGO)</div>
        </div>
    </div>

    <div class="print-actions">
        <button class="btn" onclick="window.print()">🖨️ Imprimir Acta</button>
        <button class="btn btn-secondary" onclick="window.close()">✖️ Cerrar</button>
    </div>

</body>
</html>
