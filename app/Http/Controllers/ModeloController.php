<?php

namespace App\Http\Controllers;

use App\Models\Modelo;

class ModeloController extends Controller
{
    public function show(Modelo $modelo)
    {
        $productos = $modelo->productos()
            ->with(['marca','categoria'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('tienda.modelo-show', compact('modelo','productos'));
    }
}
