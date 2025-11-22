<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ $venta->id }}</title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            margin: 0;
            padding: 8px;
            width: 55mm;
            line-height: 1.3;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        .big { font-size: 14px; font-weight: bold; }
        .title { font-size: 15px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="center title bold">LIBRERIA JAYRA</div>
    <div class="center">NIT: 12345678</div>
    <div class="center">Tel: 700-00000</div>
    <div class="center">Cochabamba - Bolivia</div>
    <hr>

    <div style="font-size:9px;">
        Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}<br>
        Ticket: {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}<br>
        Cajero: {{ $venta->usuario->name ?? 'Sistema' }}
    </div>
    <hr>

    <strong>Cliente:</strong><br>
    <span class="bold">
        {{ $venta->cliente_id && $venta->cliente ? $venta->cliente->nombre : ($venta->cliente_nombre ?? 'Cliente Generico') }}
    </span>
    @if($venta->cliente_id && $venta->cliente?->ci)
        <br>CI: {{ $venta->cliente->ci }}
    @elseif($venta->cliente_documento)
        <br>CI/NIT: {{ $venta->cliente_documento }}
    @endif
    <hr>

    @foreach($venta->detalles as $item)
        <div class="bold">{{ $item->producto->nombre }}</div>
        <table>
            <tr>
                <td>{{ $item->cantidad }} x {{ number_format($item->precio, 2) }}</td>
                <td class="right">Bs {{ number_format($item->subtotal, 2) }}</td>
            </tr>
        </table>
    @endforeach
    <hr>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">Bs {{ number_format($venta->total - $venta->impuesto + $venta->descuento_total, 2) }}</td>
        </tr>
        @if($venta->impuesto > 0)
        <tr>
            <td>Impuesto</td>
            <td class="right">Bs {{ number_format($venta->impuesto, 2) }}</td>
        </tr>
        @endif
        @if($venta->descuento_total > 0)
        <tr>
            <td>Descuento</td>
            <td class="right">- Bs {{ number_format($venta->descuento_total, 2) }}</td>
        </tr>
        @endif
        <tr class="big">
            <td class="bold">TOTAL</td>
            <td class="right bold big">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <hr>
    <div class="center bold">
        GRACIAS POR SU COMPRA
    </div>
    <div class="center">Vuelva pronto :)</div>

    <div class="center" style="margin-top:10px; font-size:8px;">
        {{ $venta->metodo_pago === 'efectivo' ? 'Pagado con efectivo' : 'Pagado con QR/Transferencia' }}
    </div>

</body>
</html>