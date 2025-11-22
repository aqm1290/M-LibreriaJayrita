<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .center { text-align: center; }
        .totales { margin-top: 10px; }
        table { width: 100%; }
        td { padding: 2px 0; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

    <h3 class="center">📘 LIBRERÍA JAYRA</h3>
    <p class="center">NIT: 12345678</p>
    <p class="center">Tel: 700-00000</p>
    <p>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</p>
    <p>Venta Nº: {{ $venta->id }}</p>
    <hr>

    <table>
        <div class="text-center mb-4">
                <p class="text-sm">Cliente: <strong>{{ $venta->cliente_nombre ?? 'Cliente Genérico' }}</strong></p>
                @if($venta->cliente_documento)
                    <p class="text-sm">Documento: {{ $venta->cliente_documento }}</p>
                @endif
            </div>
        @foreach($venta->detalles as $item)
        <tr>
            <td>{{ $item->producto->nombre }} x {{ $item->cantidad }}</td>
            <td class="right">Bs {{ number_format($item->precio * $item->cantidad, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <hr>
    <table class="totales">
        <tr>
            <td>Subtotal:</td>
            <td class="right">Bs {{ number_format($venta->total - $venta->impuesto + $venta->descuento_total, 2) }}</td>
        </tr>
        <tr>
            <td>Impuesto:</td>
            <td class="right">Bs {{ number_format($venta->impuesto, 2) }}</td>
        </tr>
        <tr>
            <td>Descuento:</td>
            <td class="right">- Bs {{ number_format($venta->descuento_total, 2) }}</td>
        </tr>
        <tr class="bold">
            <td>Total:</td>
            <td class="right">Bs {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <hr>
    <p class="center">¡Gracias por su compra!</p>

</body>
</html>
