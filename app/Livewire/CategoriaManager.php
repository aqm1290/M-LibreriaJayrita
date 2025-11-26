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

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias')->ignore($this->categoriaId),
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',
            'nombre.unique'   => 'Ya existe una categoría con este nombre.',
            'descripcion.max'=> 'La descripción no puede tener más de 500 caracteres.',
        ];
    }

    public function mount()
    {
        $this->search = request()->query('search', '');
    }

    public function render()
    {
        $categorias = Categoria::where('nombre', 'like', "%{$this->search}%")
            ->orWhere('descripcion', 'like', "%{$this->search}%")
            ->orderBy('nombre')
            ->paginate(12);

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
        $this->nombre      = $cat->nombre;
        $this->descripcion = $cat->descripcion ?? '';
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Categoria::updateOrCreate(
            ['id' => $this->categoriaId],
            [
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion,
            ]
        );

        $this->dispatch('toast', 
            $this->categoriaId 
                ? 'Categoría actualizada correctamente' 
                : 'Categoría creada exitosamente'
        );

        $this->cerrarModal();
    }

    public function confirmarEliminar($id)
    {
        $this->categoriaId = $id;
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        $categoria = Categoria::find($this->categoriaId);
        
        if ($categoria && $categoria->productos()->count() > 0) {
            $this->dispatch('toast', 'No se puede eliminar: hay productos asociados a esta categoría.');
            $this->confirmDelete = false;
            return;
        }

        $categoria?->delete();
        $this->dispatch('toast', 'Categoría eliminada correctamente');
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
        $this->reset(['categoriaId', 'nombre', 'descripcion']);
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}