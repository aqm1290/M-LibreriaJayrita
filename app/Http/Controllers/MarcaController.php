<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        // todas las marcas activas, puedes paginar si quieres
        $marcas = Marca::orderBy('nombre')->get();

        return view('tienda.marcas-index', compact('marcas'));
    }

    public function show(Marca $marca)
    {
        $modelos = $marca->modelos()
            ->withCount(['productos' => function ($q) {
                $q->where('activo', true);
            }])
            ->orderBy('nombre')
            ->get();

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