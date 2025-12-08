<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $venta->id }}</title>
    <style>
        body {
            font-family: monospace;
            width: 80mm;
            margin: 0;
            padding: 10mm;
            background: white;
            color: black;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed black;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .info {
            font-size: 14px;
            margin: 10px 0;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            padding: 5px 0;
            text-align: left;
            font-size: 13px;
        }

        .total {
            border-top: 2px dashed black;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }

        .yellow {
            color: #FFD700;
            background: black;
            padding: 5px;
            display: inline-block;
        }
    </style>
</head>

<body onload="window.print(); window.close();">
    <div class="header">
        <div class="title">LIBRERÍA JAYRITA</div>
        <div>Papelería & Más</div>
        <div>Pedido Web #{{ $venta->pedido?->id ?? $venta->id }}</div>
    </div>

    <div class="info">
        Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}<br>
        Cajero: {{ $venta->usuario->name }}<br>
        Cliente: {{ $venta->notas }}
    </div>

    <table class="items">
        <tbody>
            @foreach ($venta->detalles as $item)
                <tr>
                    <td colspan="3">{{ $item->nombre_producto }}</td>
                </tr>
                <tr>
                    <td>{{ $item->cantidad }} × {{ number_format($item->precio_unitario, 2) }}</td>
                    <td></td>
                    <td align="right">Bs {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL: Bs {{ number_format($venta->total, 2) }}
    </div>

    <div class="footer">
        <div class="yellow">¡GRACIAS POR TU COMPRA!</div>
        <br>WhatsApp: 77777777<br>
        Síguenos @libreriajayrita
    </div>
</body>

</html>
