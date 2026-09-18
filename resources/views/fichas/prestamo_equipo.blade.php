<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Préstamo {{ $loan->loan_number }}</title>
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
        .alert-status {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 5px solid #2563eb;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #1e40af;
        }
        .status-returned {
            background: #f0fdf4;
            border-color: #bbf7d0;
            border-left-color: #16a34a;
            color: #166534;
        }
        .status-overdue {
            background: #fef2f2;
            border-color: #fecaca;
            border-left-color: #dc2626;
            color: #991b1b;
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
        <h1>ACTA DE PRÉSTAMO TEMPORAL DE EQUIPO</h1>
        <div class="doc-number">{{ $loan->loan_number }}</div>
    </div>

    @php
        $statusClass = match($loan->status) {
            'returned' => 'status-returned',
            'overdue'  => 'status-overdue',
            default    => '',
        };
        $statusText = match($loan->status) {
            'pending'   => '🟡 RESERVA PENDIENTE DE ENTREGA',
            'active'    => '🟢 EQUIPO EN PRÉSTAMO ACTIVO',
            'returned'  => '✅ EQUIPO DEVUELTO A TI',
            'overdue'   => '🔴 PRÉSTAMO VENCIDO (PENDIENTE DE DEVOLUCIÓN)',
            'cancelled' => '⚪ RESERVA CANCELADA',
            default     => strtoupper($loan->status),
        };
    @endphp

    <div class="alert-status {{ $statusClass }}">
        {{ $statusText }}
    </div>

    <div class="section-title">1. Datos del Equipo Prestado</div>
    <div class="grid-2">
        <div class="data-group">
            <div class="data-label">Código de Activo / Informático</div>
            <div class="data-value">{{ $loan->asset->computer_code ?? $loan->asset->asset_code ?? 'S/N' }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Categoría / Tipo de Equipo</div>
            <div class="data-value">{{ $loan->asset->category?->name ?? 'Equipo TI' }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Marca y Modelo</div>
            <div class="data-value">{{ $loan->asset->model?->brand?->name ?? '' }} {{ $loan->asset->model?->name ?? 'No especificado' }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Número de Serie</div>
            <div class="data-value">{{ $loan->asset->serial_number ?? 'S/N' }}</div>
        </div>
    </div>

    <div class="section-title">2. Datos de la Reserva y Préstamo</div>
    <div class="grid-2">
        <div class="data-group">
            <div class="data-label">Oficina / Dependencia Solicitante</div>
            <div class="data-value">{{ $loan->office?->name ?? 'No especificada' }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Solicitante / Responsable del Equipo</div>
            <div class="data-value">{{ $loan->borrower_name }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Fecha y Hora de Inicio del Préstamo</div>
            <div class="data-value">{{ $loan->start_time->format('d/m/Y H:i A') }}</div>
        </div>
        <div class="data-group">
            <div class="data-label">Fecha y Hora Fin Estimada</div>
            <div class="data-value">{{ $loan->end_time->format('d/m/Y H:i A') }}</div>
        </div>
        @if($loan->returned_at)
            <div class="data-group" style="grid-column: span 2;">
                <div class="data-label">Fecha y Hora Real de Devolución</div>
                <div class="data-value" style="color: #16a34a;">{{ $loan->returned_at->format('d/m/Y H:i A') }}</div>
            </div>
        @endif
    </div>

    @if($loan->purpose || $loan->notes)
        <div class="section-title">3. Motivo y Observaciones</div>
        @if($loan->purpose)
            <div class="data-group" style="margin-bottom: 10px;">
                <div class="data-label">Propósito / Motivo del Préstamo</div>
                <div class="data-value" style="font-weight: 500;">{{ $loan->purpose }}</div>
            </div>
        @endif
        @if($loan->notes)
            <div class="data-group">
                <div class="data-label">Observaciones / Accesorios Entregados</div>
                <div class="data-value" style="font-weight: 400; font-style: italic;">{{ $loan->notes }}</div>
            </div>
        @endif
    @endif

    <div class="section-title">4. Conformidad y Compromiso de Custodia</div>
    <p style="font-size: 12px; color: #475569; margin-top: 5px; margin-bottom: 20px;">
        El solicitante declara recibir el equipo en óptimas condiciones de funcionamiento y se compromete a hacer uso adecuado del mismo, garantizando su custodia y devolución puntual al término de la actividad registrada.
    </p>

    <div class="signatures-block">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $loan->creator?->name ?? 'PERSONAL TI' }}</div>
            <div class="signature-role">ENTREGÓ (PERSONAL TI)</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $loan->borrower_name }}</div>
            <div class="signature-role">RECIBIÓ CONFORME (SOLICITANTE)</div>
        </div>
    </div>

    <div class="print-actions">
        <button class="btn" onclick="window.print()">🖨️ Imprimir Acta</button>
        <button class="btn btn-secondary" onclick="window.close()">✖️ Cerrar</button>
    </div>

</body>
</html>
