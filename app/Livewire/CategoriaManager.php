<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Categoria;
use Illuminate\Validation\Rule;

class CategoriaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $confirmDelete = false;

    public $categoriaId = null;
    public $nombre = '';
    public $descripcion = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->fill(['search' => request()->query('search', '')]);
    }

    public function render()
    {
        $categorias = Categoria::query()
            ->where('nombre', 'like', "%{$this->search}%")
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.categoria-manager', compact('categorias'));
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $cat = Categoria::findOrFail($id);
        $this->categoriaId = $cat->id;
        $this->nombre = $cat->nombre;
        $this->descripcion = $cat->descripcion;
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Categoria::updateOrCreate(
            ['id' => $this->categoriaId],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]
        );

        session()->flash('message', $this->categoriaId ? 'Categoría actualizada.' : 'Categoría creada.');
        $this->resetForm();
        $this->modal = false;
    }

    public function confirmarEliminar($id)
    {
        $this->categoriaId = $id;
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        Categoria::find($this->categoriaId)?->delete();
        session()->flash('message', 'Categoría eliminada.');
        $this->confirmDelete = false;
        $this->resetPage();
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    private function resetForm()
    {
        $this->categoriaId = null;
        $this->nombre = '';
        $this->descripcion = '';
    }
}
