<?php

namespace App\Observers;

use App\Models\Marca;

class MarcaObserver
{
    public function updating(Marca $marca)
    {
        if ($marca->isDirty('activo')) {
            // Si cambió el estado activo/inactivo
            $marca->modelos()->update(['activo' => $marca->activo]);
            $marca->productos()->update(['activo' => $marca->activo]);
        }
    }
}