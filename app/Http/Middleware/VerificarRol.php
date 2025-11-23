<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $permitido = false;
        foreach ($roles as $rol) {
            if ($user->rol === $rol || $user->rol === 'admin') {
                $permitido = true;
                break;
            }
        }

        if (!$permitido) {
            abort(403, 'No tienes permiso para acceder aquí, papá.');
        }

        return $next($request); 
    }
}