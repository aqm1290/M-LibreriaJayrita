<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VentaPdfController extends Controller
{
    public function ticket($id)
    {
        $venta = Venta::with(['detalles.producto', 'usuario', 'cliente'])->findOrFail($id);

        // Generar PDF
        $pdf = Pdf::loadView('pdf.ticket', compact('venta'))
            ->setPaper([0, 0, 220, 1000], 'portrait'); // 80mm ancho

        // Nombre bonito del archivo
        $fileName = 'ticket_' . str_pad($id, 6, '0', STR_PAD_LEFT) . '.pdf';
        $ruta = 'tickets/' . $fileName;

        // 1 GUARDAR EN DISCO (public/storage/tickets/)
        Storage::disk('public')->put($ruta, $pdf->output());

        // 2 GUARDAR LA RUTA EN LA BD (solo si no existe ya)
        if (!$venta->ticket_pdf) {
            $venta->update(['ticket_pdf' => $ruta]);
        }

        // 3 MOSTRAR AL USUARIO
        return $pdf->stream($fileName);
    }

    // BONUS: Método para descargar directo (opcional)
    public function descargar($id)
    {
        $venta = Venta::findOrFail($id);
        if ($venta->ticket_pdf && Storage::disk('public')->exists($venta->ticket_pdf)) {
            return Storage::disk('public')->download($venta->ticket_pdf);
        }

        return redirect()->back()->with('error', 'Ticket no encontrado');
    }
}