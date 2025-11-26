<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja - {{ $fecha }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 40px; background: white; color: #1f2937; }
        .header { text-align: center; margin-bottom: 40px; }
        .logo { width: 130px; margin-bottom: 15px; }
        .title { font-size: 38px; font-weight: bold; color: #1d4ed8; margin: 10px 0; }
        .subtitle { font-size: 18px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; font-size: 16px; }
        th { background: #1d4ed8; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #e5e7eb; }
        .total { font-size: 26px; font-weight: bold; background: #f3f4f6; }
        .diferencia { font-size: 32px; font-weight: bold; text-align: center; padding: 20px; }
        .positiva { color: #16a34a; }
        .negativa { color: #dc2626; }
        .footer { margin-top: 80px; text-align: center; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo-jayrita.png') }}" class="logo" alt="Librería Jayrita">
        <div class="title">REPORTE DE CIERRE DE CAJA</div>
        <div class="subtitle">
            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} | Cajero: {{ $cajero->name }}
        </div>
    </div>

    <table>
        <tr><th>Apertura de caja</th>           <td>Bs {{ number_format($apertura, 2) }}</td></tr>
        <tr><th>Ventas en efectivo</th>         <td>Bs {{ number_format($efectivo, 2) }}</td></tr>
        <tr><th>Ventas QR / Transferencia</th> <td>Bs {{ number_format($qr, 2) }}</td></tr>
        <tr class="total"><th>TOTAL VENTAS DEL DÍA</th> <td>Bs {{ number_format($totalDia, 2) }}</td></tr>
        <tr class="total"><th>DINERO ESPERADO</th>     <td>Bs {{ number_format($totalEsperado, 2) }}</td></tr>
        <tr class="total"><th>DINERO FÍSICO CONTADO</th><td>Bs {{ number_format($montoFisico, 2) }}</td></tr>
        <tr>
            <td colspan="2" class="diferencia {{ $diferencia >= 0 ? 'positiva' : 'negativa' }}">
                DIFERENCIA: Bs {{ number_format(abs($diferencia), 2) }}
                {{ $diferencia >= 0 ? 'SOBRANTE' : 'FALTANTE' }}
            </td>
        </tr>
    </table>

    @if($observaciones)
        <div style="margin: 40px 0; padding: 20px; background: #f3f4f6; border-radius: 12px; font-size: 16px;">
            <strong>Observaciones del cajero:</strong><br>{{ nl2br(e($observaciones)) }}
        </div>
    @endif

    @if($productosTop10->count())
        <h2 style="margin: 50px 0 20px; font-size: 26px; color: #1d4ed8;">Top 10 Productos Más Vendidos</h2>
        <table>
            <tr><th style="width: 60px;">#</th><th>Producto</th><th style="width: 120px;">Cant.</th><th style="width: 150px;">Monto</th></tr>
            @foreach($productosTop10 as $i => $p)
                <tr>
                    <td><strong>{{ $i+1 }}</strong></td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->cantidad }}</td>
                    <td>Bs {{ number_format($p->monto, 2) }}</td>
                </tr>
            @endforeach
        </table>
    @endif>

    <div class="footer">
        Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} | Librería Jayrita © 2025
    </div>

</body>
</html>