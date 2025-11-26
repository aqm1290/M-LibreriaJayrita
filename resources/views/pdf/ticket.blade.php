<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            margin: 0;
            padding: 10px 6px;
            width: 55mm;
            line-height: 1.35;
            background: white;
            color: black;
        }
        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .big    { font-size: 16px; font-weight: bold; }
        .huge   { font-size: 20px; font-weight: bold; }
        hr {
            border: none;
            border-top: 2px dashed #000;
            margin: 8px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; }
        .border { border: 2px solid #000; padding: 8px; margin: 10px 0; border-radius: 6px; }
        .header-title { font-size: 18px; letter-spacing: 1px; }
    </style>
</head>
<body>

    <!-- ENCABEZADO ÉPICO -->
    <div class="center bold header-title">LIBRERÍA JAYRITA</div>
    <div class="center bold" style="font-size:13px;">✨ La mejor papelería de Cochabamba ✨</div>
    <hr style="border-top: 3px double #000;">

    <div class="center" style="font-size:10px;">
        NIT: 12345678<br>
        Tel: 7070-7070<br>
        Av. Heroínas esq. San Martín<br>
        Cochabamba - Bolivia
    </div>
    <hr>

    <!-- INFO DE VENTA -->
    <table style="font-size:10px;">
        <tr>
            <td><strong>Ticket:</strong></td>
            <td class="right">#{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td class="right">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Cajero:</strong></td>
            <td class="right">{{ $venta->usuario->name ?? 'Admin' }}</td>
        </tr>
    </table>
    <hr>

    <!-- CLIENTE BIEN DESTACADO -->
    <div class="border">
        <div class="center bold" style="font-size:14px; margin-bottom:4px;">
            CLIENTE
        </div>
        <div class="center bold huge">
            {{ $venta->cliente_id && $venta->cliente ? $venta->cliente->nombre : ($venta->cliente_nombre ?? 'CLIENTE GENÉRICO') }}
        </div>
        @if($venta->cliente_id && $venta->cliente?->ci)
            <div class="center bold" style="font-size:13px; margin-top:4px;">
                CI: {{ $venta->cliente->ci }}
            </div>
        @elseif($venta->cliente_documento)
            <div class="center bold" style="font-size:13px; margin-top:4px;">
                CI/NIT: {{ $venta->cliente_documento }}
            </div>
        @endif
    </div>
    <hr>

    <!-- DETALLES DE PRODUCTOS -->
    @foreach($venta->detalles as $item)
        <div class="bold" style="font-size:12px;">{{ $item->producto->nombre }}</div>
        <table>
            <tr>
                <td style="width:55%;">
                    {{ $item->cantidad }} × {{ number_format($item->precio, 2) }}
                </td>
                <td class="right bold">
                    Bs {{ number_format($item->subtotal, 2) }}
                </td>
            </tr>
        </table>
    @endforeach
    <hr style="border-top: 2px dashed #000;">

    <!-- TOTALES -->
    <table style="font-size:14px;">
        @if($venta->descuento_total > 0)
        <tr>
            <td>Subtotal</td>
            <td class="right">Bs {{ number_format($venta->total - $venta->impuesto + $venta->descuento_total, 2) }}</td>
        </tr>
        <tr>
            <td>Descuento</td>
            <td class="right bold" style="color:#d00;">- Bs {{ number_format($venta->descuento_total, 2) }}</td>
        </tr>
        @endif
        <tr class="big">
            <td class="bold">TOTAL A PAGAR</td>
            <td class="right bold huge">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <hr style="border-top: 3px double #000; margin:12px 0;">

    <!-- FORMA DE PAGO -->
    <div class="center bold big" style="margin:10px 0;">
        @if($venta->metodo_pago === 'efectivo')
            PAGADO CON EFECTIVO
        @else
            PAGADO CON QR / TRANSFERENCIA
        @endif
    </div>

    <!-- GRACIAS ÉPICO -->
    <div class="center bold" style="font-size:18px; margin:15px 0 5px;">
        ¡GRACIAS POR SU COMPRA!
    </div>
    <div class="center bold" style="font-size:16px; margin-bottom:10px;">
        Vuelva pronto
    </div>

    <div class="center" style="font-size:9px; margin-top:15px; opacity:0.7;">
        Librería Jayrita © {{ date('Y') }} - Sistema POS
    </div>

</body>
</html>