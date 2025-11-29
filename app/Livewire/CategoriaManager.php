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
    public $mostrarInactivos = false;
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
                Rule::unique('categorias')->ignore($this->categoriaId)
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
        'nombre.unique'   => 'Ya existe una categoría con ese nombre.',
        'nombre.max'      => 'El nombre no puede tener más de 100 caracteres.',
    ];

    public function render()
    {
        $categorias = Categoria::withCount('productos')
            ->when($this->search !== '', function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('descripcion', 'like', "%{$this->search}%");
            })
            ->when($this->mostrarInactivos, fn($q) => $q->where('activo', false), fn($q) => $q->where('activo', true))            ->orderBy('nombre')
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
        $this->modal       = true;
    }

    public function guardar()
    {
        $this->validate();

        Categoria::updateOrCreate(['id' => $this->categoriaId], [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'activo'      => true,
        ]);

        $this->dispatch('toast', $this->categoriaId
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
        $cat = Categoria::find($this->categoriaId);

        if ($cat) {
            $cat->activo = !$cat->activo;
            $cat->save(); // ← Dispara el Observer → todos los productos se desactivan/reactivan

            $this->dispatch('toast', $cat->activo
                ? 'Categoría reactivada correctamente'
                : 'Categoría y todos sus productos desactivados'
            );
        }

        $this->confirmDelete = false;
        $this->categoriaId = null;
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

    public function updatedMostrarInactivos()
    {
        $this->resetPage();
    }

    // EL MÉTODO MÁGICO QUE ELIMINA EL ERROR PARA SIEMPRE
    public function getCategoriaProperty()
    {
        return $this->categoriaId ? Categoria::find($this->categoriaId) : null;
    }
}