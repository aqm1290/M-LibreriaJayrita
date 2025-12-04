<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Modelo;


class TiendaController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'marca', 'modelo'])
            ->where('activo', true)
            ->latest('id')
            ->take(12)
            ->get();

        $marcas = Marca::where('activo', true)
            ->orderBy('nombre')
            ->take(12)
            ->get();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('tienda.home', compact('productos', 'marcas', 'categorias'));
    }

    public function showAjax($id)
    {
        $producto = Producto::with(['categoria', 'marca', 'modelo'])
            ->findOrFail($id);

        return response()->json([
            'id'          => $producto->id,
            'nombre'      => $producto->nombre,
            'imagen_url'  => $producto->imagen_url ?? asset('shop/assets/img/no-image.jpg'),
            'precio'      => $producto->precio,
            'descripcion' => $producto->descripcion ?? 'Sin descripción disponible.',
            'categoria'   => $producto->categoria?->nombre ?? 'Sin categoría',
            'marca'       => $producto->marca?->nombre ?? 'Sin marca',
            'modelo'      => $producto->modelo?->nombre ?? 'Sin modelo',
        ]);
    }
    
}
