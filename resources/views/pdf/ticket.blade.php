<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 11.5px;
            margin: 0;
            padding: 12px 6px;
            width: 55mm;
            line-height: 1.4;
            background: white;
            color: black;
        }
        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .big    { font-size: 16px; }
        .huge   { font-size: 20px; font-weight: bold; }
        hr {
            border: none;
            border-top: 2px dashed #000;
            margin: 10px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .border {
            border: 3px double #000;
            padding: 12px 8px;
            margin: 12px 0;
            border-radius: 10px;
            text-align: center;
            background: #f8f8f8;
        }
        .header-title {
            font-size: 21px;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <div class="center bold header-title">LIBRERÍA JAYRITA</div>
    <hr style="border-top: 4px double #000;">

    <div class="center" style="font-size:10px; line-height:1.5;">
        NIT: 123456789<br>
        Tel: 7070-7070<br>
        Av.Bolivar y Porvenir<br>
        Cochabamba - Bolivia
    </div>
    <hr>

    <!-- INFO VENTA -->
    <table style="font-size:10.5px;">
        <tr><td><strong>Recibo #:</strong></td><td class="right">#{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><td><strong>Fecha/Hora:</strong></td><td class="right">{{ $venta->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td><strong>Cajero:</strong></td><td class="right">{{ $venta->usuario->name ?? 'Admin' }}</td></tr>
    </table>
    <hr>

    <!-- CLIENTE BIEN DESTACADO -->
    <div class="border">
        <div class="huge" style="margin:8px 0 4px;">
            {{ $venta->cliente_nombre ?? 'CLIENTE GENÉRICO' }}
        </div>
        @if($venta->cliente_documento)
            <div class="bold" style="font-size:13px;">
                CI/NIT: {{ $venta->cliente_documento }}
            </div>
        @endif
    </div>
    <hr>

   
    @foreach($venta->detalles as $item)
        <div class="bold" style="font-size:12px;">{{ Str::limit($item->producto->nombre, 28) }}</div>
        <table>
            <tr>
                <td style="width:55%;">{{ $item->cantidad }} × {{ number_format($item->precio, 2) }}</td>
                <td class="right bold">Bs {{ number_format($item->subtotal, 2) }}</td>
            </tr>
        </table>
    @endforeach

    <hr style="border-top: 3px dashed #000; margin:18px 0;">

    <!-- TOTALES -->
    <table style="font-size:15px;">
        @if($venta->descuento_total > 0)
        <tr>
            <td>Subtotal</td>
            <td class="right">Bs {{ number_format($venta->total + $venta->descuento_total, 2) }}</td>
        </tr>
        <tr>
            <td style="color:#c00;">Descuento</td>
            <td class="right bold" style="color:#c00;">- Bs {{ number_format($venta->descuento_total, 2) }}</td>
        </tr>
        <tr>
            <td class="bold big">TOTAL PAGADO</td>
            <td class="right bold huge">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
        @else
        <tr>
            <td class="bold big">TOTAL PAGADO</td>
            <td class="right bold huge">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
        @endif
    </table>

    <hr style="border-top: 4px double #000; margin:18px 0;">

    <!-- FORMA DE PAGO -->
    <div class="center bold big" style="margin:15px 0;">
        {{ $venta->metodo_pago === 'efectivo' ? 'PAGADO CON EFECTIVO' : 'PAGADO CON QR / TRANSFERENCIA' }}
    </div>

    <!-- MENSAJE FINAL ÉPICO -->
    <div class="center bold" style="font-size:21px; margin:20px 0 10px;">
        ¡GRACIAS POR SU COMPRA!
    </div>
    <div class="center bold big" style="margin-bottom:20px; color:#333;">
        ¡Vuelva prontito a Librería Jayrita!
    </div>

    <div class="center" style="font-size:9px; opacity:0.7; margin-top:25px;">
        Librería Jayrita © {{ date('Y') }} 
    </div>

    <!-- ESPACIO PARA CORTE -->
    <div style="height: 30px;"></div>

</body>
</html>