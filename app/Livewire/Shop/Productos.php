<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination; // Para paginación
use App\Models\Producto; // Asumiendo tu modelo se llama Producto
use App\Models\Categoria; // Si tienes categorías para filtro

class Productos extends Component
{
    use WithPagination;

    public $search = ''; // Para búsqueda
    public $categoria = ''; // Filtro por categoría
    public $sort = 'nombre'; // Ordenar por (nombre, precio)
    public $direction = 'asc'; // asc/desc

    protected $paginationTheme = 'tailwind'; // Para que use Tailwind

    public function updatingSearch() { $this->resetPage(); } // Reset paginación al buscar

    public function render()
    {
        $categorias = Categoria::all(); // Para dropdown de filtros

        $productos = Producto::query()
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->categoria, fn($q) => $q->where('categoria_id', $this->categoria))
            ->orderBy($this->sort, $this->direction)
            ->paginate(12); // 12 por página, ajusta

        return view('livewire.shop.productos', compact('productos', 'categorias'))
            ->layout('layouts.tienda'); // Reusa tu layout principal si quieres, o crea uno nuevo para tienda
    }

    // Para real-time simple: Poll cada 30s (actualiza stock si cambia en DB)
    protected $poll = 'refresh'; // Habilita polling
    public function refresh() {} // Vacío, solo refresca
}