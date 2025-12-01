<?php

namespace App\Http\Controllers;

use App\Models\TurnoCaja;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CierrePdfController extends Controller
{
    // Ver en el navegador (como stream), similar a ticket()
    public function generar($id)
    {
        $turno = TurnoCaja::with('usuario')->findOrFail($id);

        // Ventas del turno
        $ventas = Venta::where('turno_id', $turno->id)->get();

        $totalDia   = $ventas->sum('total');
        $efectivo   = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $qr         = Venta::where('turno_id', $turno->id)
                            ->whereIn('metodo_pago', ['qr', 'transferencia'])
                            ->sum('total');
        $apertura   = $turno->monto_apertura;
        $esperado   = $efectivo + $apertura;
        $diferencia = ($turno->monto_fisico_cierre ?? 0) - $esperado;

        // Productos vendidos (todos, no solo top 10)
        $productosVendidos = \DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->where('ventas.turno_id', $turno->id)
            ->select(
                'productos.nombre',
                \DB::raw('SUM(detalle_ventas.cantidad) as cantidad'),
                \DB::raw('SUM(detalle_ventas.subtotal) as monto')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad')
            ->get();

        $pdf = Pdf::loadView('pdf.cierre-turno', [
            'turno'            => $turno,
            'totalDia'         => $totalDia,
            'efectivo'         => $efectivo,
            'qr'               => $qr,
            'apertura'         => $apertura,
            'diferencia'       => $diferencia,
            'montoFisico'      => $turno->monto_fisico_cierre ?? 0,
            'observaciones'    => $turno->observaciones ?? '',
            'productosVendidos'=> $productosVendidos,
            'cantidadVentas'   => $ventas->count(),
        ])->setPaper('a4');

        $fileName = 'cierre_turno_' . str_pad($turno->id, 4, '0', STR_PAD_LEFT) . '.pdf';
        $ruta     = 'cierres/' . $fileName;

        // Guardar en disco
        Storage::disk('public')->put($ruta, $pdf->output());

        // Guardar ruta en el turno si no estaba
        if (! $turno->reporte_pdf) {
            $turno->update(['reporte_pdf' => $ruta]);
        }

        return $pdf->stream($fileName);
    }

    // Descargar directo el archivo guardado
    public function descargar($id)
    {
        $turno = TurnoCaja::findOrFail($id);

        if ($turno->reporte_pdf && Storage::disk('public')->exists($turno->reporte_pdf)) {
            return Storage::disk('public')->download($turno->reporte_pdf);
        }

        return back()->with('error', 'Reporte de cierre no encontrado');
    }
}
