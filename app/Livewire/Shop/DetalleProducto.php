<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Producto;

class DetalleProducto extends Component
{
    public $producto;

    public function mount($slug)
    {
        $this->producto = Producto::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.shop.detalle-producto')
            ->layout('layouts.app');
    }
}