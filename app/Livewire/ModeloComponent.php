<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Validation\Rule;

class ModeloComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $confirmDelete = false;

    public $modeloId = null;
    public $nombre = '';
    public $descripcion = '';
    public $marca_id = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('modelos')->where(fn($query) => $query->where('marca_id', $this->marca_id))->ignore($this->modeloId),
            ],
            'marca_id' => 'required|exists:marcas,id',
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre del modelo es obligatorio.',
            'nombre.max'      => 'El nombre no puede tener más de 150 caracteres.',
            'nombre.unique'   => 'Ya existe un modelo con este nombre para la marca seleccionada.',
            'marca_id.required' => 'Debes seleccionar una marca.',
            'marca_id.exists'   => 'La marca seleccionada no es válida.',
            'descripcion.max'   => 'La descripción no puede tener más de 500 caracteres.',
        ];
    }

    public function render()
    {
        $modelos = Modelo::with('marca')
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', "%{$this->search}%")
                      ->orWhereHas('marca', fn($q) => $q->where('nombre', 'like', "%{$this->search}%"));
            })
            ->orderBy('nombre')
            ->paginate(12);

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
        $modelo = Modelo::findOrFail($id);
        $this->modeloId    = $modelo->id;
        $this->nombre      = $modelo->nombre;
        $this->descripcion = $modelo->descripcion ?? '';
        $this->marca_id    = $modelo->marca_id;
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Modelo::updateOrCreate(
            ['id' => $this->modeloId],
            [
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion,
                'marca_id'    => $this->marca_id,
            ]
        );

        $this->dispatch('toast',
            $this->modeloId
                ? 'Modelo actualizado correctamente'
                : 'Modelo creado exitosamente'
        );

        $this->cerrarModal();
    }

    public function confirmarEliminar($id)
    {
        $this->modeloId = $id;
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        $modelo = Modelo::find($this->modeloId);

        if ($modelo && $modelo->productos()->count() > 0) {
            $this->dispatch('toast', 'No se puede eliminar: hay productos con este modelo.');
            $this->confirmDelete = false;
            return;
        }

        $modelo?->delete();
        $this->dispatch('toast', 'Modelo eliminado correctamente');
        $this->confirmDelete = false;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->confirmDelete = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['modeloId', 'nombre', 'descripcion', 'marca_id']);
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}