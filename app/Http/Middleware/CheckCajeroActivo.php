<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Cajero;

class CheckCajeroActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->rol === 'cajero') {
            $cajero = Cajero::where('usuario_id', $user->id)->first();

            if ($cajero && $cajero->activo === false) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu usuario está deshabilitado.']);
            }
        }

        return $next($request);
    }
}
