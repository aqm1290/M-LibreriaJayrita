<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Marca;
use Illuminate\Validation\Rule;

class MarcasComponent extends Component
{
    public $search = '';
    public $modal = false;
    public $confirmDelete = false;

    public $marca_id = null;
    public $nombre = '';
    public $descripcion = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('marcas')->ignore($this->marca_id),
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre de la marca es obligatorio.',
            'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',
            'nombre.unique'   => 'Ya existe una marca con este nombre.',
            'descripcion.max'=> 'La descripción no puede tener más de 500 caracteres.',
        ];
    }

    public function render()
    {
        $marcas = Marca::where('nombre', 'like', "%{$this->search}%")
            ->orWhere('descripcion', 'like', "%{$this->search}%")
            ->orderBy('nombre')
            ->paginate(12);

        return view('livewire.marcas-component', compact('marcas'));
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $marca = Marca::findOrFail($id);
        $this->marca_id    = $marca->id;
        $this->nombre      = $marca->nombre;
        $this->descripcion = $marca->descripcion ?? '';
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Marca::updateOrCreate(
            ['id' => $this->marca_id],
            [
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion,
            ]
        );

        $this->dispatch('toast', 
            $this->marca_id 
                ? 'Marca actualizada correctamente' 
                : 'Marca creada exitosamente'
        );

        $this->cerrarModal();
    }

    public function confirmarEliminar($id)
    {
        $this->marca_id = $id;
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        $marca = Marca::find($this->marca_id);

        if ($marca && $marca->productos()->count() > 0) {
            $this->dispatch('toast', 'No se puede eliminar: hay productos asociados a esta marca.');
            $this->confirmDelete = false;
            return;
        }

        $marca?->delete();
        $this->dispatch('toast', 'Marca eliminada correctamente');
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
        $this->reset(['marca_id', 'nombre', 'descripcion']);
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}