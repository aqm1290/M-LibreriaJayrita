<?php

// app/Http/Controllers/CategoriaController.php
namespace App\Http\Controllers;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function show(Categoria $categoria)
    {
        $productos = $categoria->productos()
            ->with(['marca','modelo'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('tienda.categoria-show', compact('categoria','productos'));
    }
}

