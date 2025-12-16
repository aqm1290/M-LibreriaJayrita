<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function imprimirWeb(Venta $venta)
    {
        $venta->load(['usuario', 'detalles.producto', 'pedido']);

        $pdf = Pdf::loadView('pdf.web', compact('venta'))
                ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->download('ticket-venta-' . $venta->id . '.pdf');
    }

}
