<?php

namespace App\Livewire;

use Livewire\Component;

class Test extends Component
{
    public $contador = 0;

    public function sumar()
    {
        $this->contador++;
    }

    public function render()
    {
        return view('livewire.test');
    }
}
