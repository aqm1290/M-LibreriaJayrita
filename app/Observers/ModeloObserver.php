<?php

namespace App\Observers;

use App\Models\Modelo;

class ModeloObserver
{
    public function updating(Modelo $modelo)
    {
        if ($modelo->isDirty('activo')) {
            $modelo->productos()->update(['activo' => $modelo->activo]);
        }
    }
}