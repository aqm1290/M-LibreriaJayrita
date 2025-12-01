<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CierreCaja;
use App\Models\TurnoCaja;

class CheckCajaAbierta
{

public function handle($request, Closure $next)
{
    $turnoId = session('turno_activo_id');

    if (!$turnoId) {
        return redirect()->route('caja.apertura');
    }

    $turno = TurnoCaja::find($turnoId);

    if (!$turno || !$turno->activo) {
        session()->forget('turno_activo_id');
        return redirect()->route('caja.apertura');
    }

    return $next($request);
}

}