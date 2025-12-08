<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function imprimirWeb(Venta $venta)
    {
        $pdf = Pdf::loadView('pdf.web', compact('venta'))
                    ->setPaper([0, 0, 226.77, 600], 'portrait'); // tamaño ticket

        return $pdf->download('ticket-venta-' . $venta->id . '.pdf');
    }
}
