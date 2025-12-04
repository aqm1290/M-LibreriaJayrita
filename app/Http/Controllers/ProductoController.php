<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;

use App\Models\Categoria;

class ProductoController extends Controller
{

    public function show(Producto $producto)
    {
        // Carga relaciones necesarias
        $producto->load(['categoria', 'marca', 'modelo']);

        return view('tienda.producto-show', compact('producto'));
    }
    
}
