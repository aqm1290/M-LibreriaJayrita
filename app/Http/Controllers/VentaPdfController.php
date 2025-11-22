<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaPdfController extends Controller
{
    public function ticket($id)
    {
        $venta = Venta::with('detalles.producto', 'usuario')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.ticket', compact('venta'))
            ->setPaper([0, 0, 220, 600]); // tamaño de ticket (58mm o 80mm)

        return $pdf->stream("ticket_venta_$id.pdf");
    }
}
