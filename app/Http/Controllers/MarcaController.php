<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
   public function show(Marca $marca)
    {
        $modelos = $marca->modelos()->orderBy('nombre')->get();

        return view('tienda.marca-show', compact('marca', 'modelos'));
    }
    public function productosAjax(Modelo $modelo)
    {
        $productos = $modelo->productos()
            ->where('activo', true)
            ->with('marca')
            ->latest()
            ->get();

        return view('tienda.partials.productos-modelo-grid', compact('productos', 'modelo'))->render();
    }
}