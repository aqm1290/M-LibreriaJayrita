<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ $venta->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 8px;
            width: 72mm;
            line-height: 1.4;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        .total { font-size: 14px; font-weight: bold; }
        .big { font-size: 16px; }
    </style>
</head>
<body>

    <div class="center uppercase bold">LIBRERÍA JAYRA</div>
    <div class="center">NIT: 12345678</div>
    <div class="center">Tel: 700-00000 • La Paz - Bolivia</div>
    <hr>

    <div style="font-size:10px;">
        <strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}<br>
        <strong>Ticket Nº:</strong> {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}<br>
        <strong>Cajero:</strong> {{ $venta->usuario->name ?? 'Sistema' }}
    </div>
    <hr>

    <!-- CLIENTE (registrado o rápido) -->
    <div style="margin: 6px 0;">
        <strong>Cliente:</strong><br>
        <span class="bold">
            {{ $venta->cliente_id && $venta->cliente ? $venta->cliente->nombre : ($venta->cliente_nombre ?? 'Cliente Genérico') }}
        </span>
        @if($venta->cliente_id && $venta->cliente?->ci)
            <br><small>CI: {{ $venta->cliente->ci }}</small>
        @elseif($venta->cliente_documento)
            <br><small>CI/NIT: {{ $venta->cliente_documento }}</small>
        @endif
    </div>
    <hr>

    <!-- PRODUCTOS -->
    @foreach($venta->detalles as $item)
        <table>
            <tr>
                <td colspan="2" class="bold">{{ $item->producto->nombre }}</td>
            </tr>
            <tr>
                <td>{{ $item->cantidad }} x {{ number_format($item->precio, 2) }}</td>
                <td class="right">Bs {{ number_format($item->subtotal, 2) }}</td>
            </tr>
        </table>
    @endforeach
    <hr>

    <!-- TOTALES -->
    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">Bs {{ number_format($venta->total - $venta->impuesto + $venta->descuento_total, 2) }}</td>
        </tr>
        @if($venta->impuesto > 0)
        <tr>
            <td>Impuesto (13%)</td>
            <td class="right">Bs {{ number_format($venta->impuesto, 2) }}</td>
        </tr>
        @endif
        @if($venta->descuento_total > 0)
        <tr>
            <td>Descuento</td>
            <td class="right">- Bs {{ number_format($venta->descuento_total, 2) }}</td>
        </tr>
        @endif
        <tr class="total big">
            <td class="bold">TOTAL A PAGAR</td>
            <td class="right bold big">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <hr>
    <div class="center bold" style="margin-top: 10px;">
        ¡GRACIAS POR SU COMPRA!<br>
        <small>Vuelva pronto ♥</small>
    </div>

    @if($venta->metodo_pago === 'efectivo')
        <div class="center" style="margin-top: 8px; font-size: 10px;">
            Pago con efectivo • Cambio devuelto
        </div>
    @else
        <div class="center" style="margin-top: 8px; font-size: 10px;">
            Pago con QR / Transferencia
        </div>
    @endif

    <div class="center" style="margin-top: 15px; font-size: 9px;">
            LIBRERIA JAYRITA
    </div>

</body>
</html>