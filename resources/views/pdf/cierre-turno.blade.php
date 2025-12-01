<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Turno #{{ $turno->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 13px;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
            line-height: 1.5;
        }
        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .big    { font-size: 18px; }
        .huge   { font-size: 28px; font-weight: bold; }
        .header-title {
            font-size: 36px;
            letter-spacing: 4px;
            margin: 10px 0;
            color: #000;
        }
        hr {
            border: none;
            border-top: 4px double #000;
            margin: 20px 0;
        }
        .dashed {
            border-top: 2px dashed #000;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #f0f0f0;
            padding: 10px;
            font-weight: bold;
            font-size: 14px;
            border: 2px solid #000;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #000;
            vertical-align: top;
        }
        .border-box {
            border: 4px double #000;
            padding: 20px;
            margin: 20px 0;
            background: #f8f8f8;
            border-radius: 15px;
        }
        .total-row {
            background: #e0e0e0 !important;
            font-size: 16px;
        }
        .diferencia-verde { color: #006400; }
        .diferencia-rojo  { color: #c00; }
    </style>
</head>
<body>

    <!-- ENCABEZADO ÉPICO -->
    <div class="center">
        <div class="header-title bold">LIBRERÍA JAYRITA</div>
        <div style="font-size:18px; margin:10px 0;">CIERRE DE TURNO OFICIAL</div>
    </div>
    <hr>

    <div class="center" style="font-size:14px; line-height:1.6;">
        NIT: 123456789 • Tel: 7070-7070<br>
        Av. Bolívar y Porvenir • Cochabamba - Bolivia
    </div>
    <hr>

    <!-- INFO DEL TURNO -->
    <table style="font-size:15px; margin:20px 0;">
        <tr>
            <td><strong>Turno #:</strong></td>
            <td class="right bold huge">#{{ str_pad($turno->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td><strong>Cajero:</strong></td>
            <td class="right bold big">{{ $turno->usuario->name ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td class="right">{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Hora Apertura:</strong></td>
            <td class="right">{{ \Carbon\Carbon::parse($turno->hora_apertura)->format('H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Hora Cierre:</strong></td>
            <td class="right bold">{{ now()->format('H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Total Operaciones:</strong></td>
            <td class="right bold big">{{ $cantidadVentas }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <!-- RESUMEN FINANCIERO -->
    <div class="border-box">
        <div class="center bold huge" style="margin-bottom:15px;">RESUMEN FINANCIERO</div>
        
        <table style="font-size:16px;">
            <tr><td>Apertura de caja</td><td class="right">Bs {{ number_format($apertura, 2) }}</td></tr>
            <tr><td>Ventas en efectivo</td><td class="right">Bs {{ number_format($efectivo, 2) }}</td></tr>
            <tr><td>Ventas QR / Transferencia</td><td class="right">Bs {{ number_format($qr, 2) }}</td></tr>
            <tr class="total-row">
                <td class="bold">TOTAL VENTAS DEL DÍA</td>
                <td class="right bold huge">Bs {{ number_format($totalDia, 2) }}</td>
            </tr>
            <tr><td>Monto físico contado</td><td class="right">Bs {{ number_format($montoFisico, 2) }}</td></tr>
            <tr class="total-row">
                <td class="bold">DIFERENCIA</td>
                <td class="right bold huge {{ $diferencia >= 0 ? 'diferencia-verde' : 'diferencia-rojo' }}">
                    {{ $diferencia >= 0 ? '+' : '' }}Bs {{ number_format($diferencia, 2) }}
                </td>
            </tr>
        </table>
    </div>

    @if(trim($observaciones))
        <div style="margin:25px 0;">
            <strong>Observaciones:</strong><br>
            <div style="background:#f0f0f0; padding:15px; border-left:6px solid #c00; margin-top:8px; border-radius:8px;">
                {{ $observaciones }}
            </div>
        </div>
    @endif

    <hr>

    <!-- TODOS LOS PRODUCTOS VENDIDOS -->
    <div class="center bold huge" style="margin:25px 0 15px;">
        PRODUCTOS VENDIDOS ({{ $productosVendidos->count() }})
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:6%;">#</th>
                <th>PRODUCTO</th>
                <th style="width:15%;">CANT.</th>
                <th style="width:20%;">MONTO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosVendidos as $i => $p)
                <tr @if($i % 2 == 0) style="background:#f9f9f9;" @endif>
                    <td class="center bold">{{ $i + 1 }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td class="center bold">{{ $p->cantidad }}</td>
                    <td class="right bold">Bs {{ number_format($p->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr style="margin:30px 0;">

    <!-- MENSAJE FINAL -->
    <div class="center bold huge" style="margin:30px 0; color:#c00;">
        ¡TURNO CERRADO CON ÉXITO!
    </div>
    <div class="center bold big" style="margin-bottom:40px;">
        Gracias por tu excelente trabajo hoy<br>
        ¡Librería Jayrita sigue creciendo contigo!
    </div>

    <div class="center" style="font-size:11px; opacity:0.7; margin-top:50px;">
        Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} 
        • Sistema POS Jayrita © {{ date('Y') }}
    </div>

</body>
</html>