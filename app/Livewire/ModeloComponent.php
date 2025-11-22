<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class ModeloComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $modeloId = null;
    public $nombre = '';
    public $descripcion = '';
    public $marca_id = null;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'marca_id' => 'required|exists:marcas,id',
        'descripcion' => 'nullable|string',
    ];

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $modelos = Modelo::with('marca')
            ->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhereHas('marca', fn($m) => $m->where('nombre', 'like', "%{$this->search}%"));
            })
            ->orderBy('nombre')
            ->paginate(10);

        $marcas = Marca::orderBy('nombre')->get();

        return view('livewire.modelo-component', compact('modelos', 'marcas'));
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $m = Modelo::findOrFail($id);
        $this->modeloId = $m->id;
        $this->nombre = $m->nombre;
        $this->descripcion = $m->descripcion;
        $this->marca_id = $m->marca_id;
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Modelo::updateOrCreate(
            ['id' => $this->modeloId],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'marca_id' => $this->marca_id,
            ]
        );

        $mensaje = $this->modeloId ? 'Modelo actualizado.' : 'Modelo creado.';

        $this->dispatch('toast', $mensaje);

        $this->resetForm();
        $this->modal = false;
        $this->resetPage();
    }

    public function confirmarEliminar($id)
    {
        $this->modeloId = $id;
        $this->dispatch('confirmar-eliminar');
    }

    #[On('eliminar')]
    public function eliminar()
    {
        Modelo::find($this->modeloId)?->delete();

        $this->dispatch('toast', 'Modelo eliminado.');
        $this->resetPage();
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    private function resetForm()
    {
        $this->modeloId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->marca_id = null;
    }
}
