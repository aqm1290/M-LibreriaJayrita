<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cierre de Caja - {{ $fecha }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 30px; }
        h1 { text-align: center; color: #2c3e50; }
        .total { font-size: 36px; font-weight: bold; color: #e74c3c; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h1>LIBRERÍA JAYRA - CIERRE DE CAJA</h1>
    <p style="text-align:center;"><strong>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</strong></p>

    <p><strong>Apertura de caja:</strong> Bs {{ number_format($apertura, 2) }}</p>
    <p><strong>Ventas en efectivo:</strong> Bs {{ number_format($efectivo, 2) }}</p>
    <p><strong>Ventas QR:</strong> Bs {{ number_format($qr, 2) }}</p>
    <p><strong>Total del día:</strong> Bs {{ number_format($totalDia, 2) }}</p>
    <p class="total">DINERO ESPERADO EN CAJA: Bs {{ number_format($totalEsperado, 2) }}</p>

    @if($productosTop10->count() > 0)
    <h2>Top 10 Productos</h2>
    <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Monto</th></tr>
        @foreach($productosTop10 as $p)
        <tr>
            <td>{{ $p->nombre }}</td>
            <td>{{ $p->cantidad_vendida }}</td>
            <td>Bs {{ number_format($p->monto_vendido, 2) }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <br><br>
    <p>_________________________</p>
    <p>Firma del Cajero: {{ $cajero->name ?? 'Admin' }}</p>
</body>
</html>