<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;

use App\Models\Categoria;

class ProductoController extends Controller
{

    public function index()
    {
        $productos = Producto::with(['categoria', 'marca', 'modelo'])
            ->orderBy('nombre')
            ->get();

        $productosJson = $productos->map(function ($p) {
            return [
                'id'         => $p->id,
                'nombre'     => $p->nombre,
                'descripcion'=> $p->descripcion,
                'precio'     => $p->precio,
                'stock'      => $p->stock,
                'imagen_url' => $p->imagen_url,
                'marca'      => $p->marca ? [
                    'id' => $p->marca->id,
                    'nombre' => $p->marca->nombre,
                ] : null,
                'categoria'  => $p->categoria ? [
                    'id' => $p->categoria->id,
                    'nombre' => $p->categoria->nombre,
                ] : null,
                'modelo'     => $p->modelo ? [
                    'id' => $p->modelo->id,
                    'nombre' => $p->modelo->nombre,
                ] : null,
            ];
        });

        $categorias = Categoria::orderBy('nombre')->get();
        $marcas     = Marca::orderBy('nombre')->get();
        $modelos    = Modelo::orderBy('nombre')->get();

        return view('tienda.productos-index', [
            'productos'     => $productos,
            'productosJson' => $productosJson,
            'categorias'    => $categorias,
            'marcas'        => $marcas,
            'modelos'       => $modelos,
        ]);
    }



    public function show(Producto $producto)
    {
        // Carga relaciones necesarias
        $producto->load(['categoria', 'marca', 'modelo']);

        return view('tienda.producto-show', compact('producto'));
    }
    public function catalogo()
    {
        $productos = Producto::with(['categoria', 'marca', 'modelo'])
            ->where('activo', true)
            ->latest()
            ->get();

        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $marcas     = Marca::where('activo', true)->orderBy('nombre')->get();
        $modelos    = Modelo::whereHas('productos', fn($q) => $q->where('activo', true))
            ->orderBy('nombre')
            ->get();

        return view('tienda.catalogo', compact('productos', 'categorias', 'marcas', 'marcas', 'modelos'));
}
    
}
