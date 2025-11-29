<?php

namespace App\Observers;

use App\Models\Categoria;

class CategoriaObserver
{
    public function updating(Categoria $categoria)
    {
        if ($categoria->isDirty('activo')) {
            $categoria->productos()->update(['activo' => $categoria->activo]);
        }
    }
}