<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CierreCaja;

class CheckCajaAbierta
{
    public function handle(Request $request, Closure $next)
    {
        if (!CierreCaja::cajaAbiertaHoy() && $request->route()->getName() !== 'caja.apertura') {
            return redirect()->route('caja.apertura')
                ->with('error', '¡Debes abrir caja antes de vender!');
        }

        return $next($request);
    }
}