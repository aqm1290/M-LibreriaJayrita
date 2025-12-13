<?php

namespace App\Livewire\Admin;

use App\Models\CategoriaMayor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoriasMayores extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $mostrarInactivos = false;

    public $modal = false;
    public $confirmDelete = false;

    public $categoriaMayorId = null;
    public $nombre = '';
    public $slug = '';
    public $activo = true;

    public ?CategoriaMayor $categoriaMayor = null;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'slug'   => 'string|max:255|unique:categoria_mayors,slug',
        'activo' => 'boolean',
    ];

    protected $listeners = ['refreshCategoriasMayores' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedNombre()
    {
        if (!$this->categoriaMayorId) {
            $this->slug = Str::slug($this->nombre);
        }
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $cat = CategoriaMayor::findOrFail($id);

        $this->categoriaMayorId = $cat->id;
        $this->nombre = $cat->nombre;
        $this->slug = $cat->slug;
        $this->activo = (bool) $cat->activo;

        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
    }

    public function guardar()
    {
        $rules = $this->rules;

        if ($this->categoriaMayorId) {
            $rules['slug'] = 'required|string|max:255|unique:categoria_mayors,slug,' . $this->categoriaMayorId;
        }

        $data = $this->validate($rules);

        if ($this->categoriaMayorId) {
            CategoriaMayor::findOrFail($this->categoriaMayorId)->update($data);
            $msg = 'Categoría mayor actualizada correctamente.';
        } else {
            CategoriaMayor::create($data);
            $msg = 'Categoría mayor creada correctamente.';
        }

        $this->dispatch('toast', $msg);
        $this->cerrarModal();
        $this->resetPage();
    }

    public function confirmarEliminar($id)
    {
        $this->categoriaMayor = CategoriaMayor::findOrFail($id);
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        if (!$this->categoriaMayor) {
            return;
        }

        $this->categoriaMayor->activo = !$this->categoriaMayor->activo;
        $this->categoriaMayor->save();

        $msg = $this->categoriaMayor->activo
            ? 'Categoría mayor reactivada.'
            : 'Categoría mayor desactivada.';

        $this->dispatch('toast', $msg);

        $this->confirmDelete = false;
        $this->categoriaMayor = null;
        $this->resetPage();
    }

    protected function resetForm()
    {
        $this->reset(['categoriaMayorId', 'nombre', 'slug', 'activo']);
        $this->activo = true;
    }

    public function render()
    {
        $categoriasMayores = CategoriaMayor::query()
            ->when($this->search, function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when(!$this->mostrarInactivos, function ($q) {
                $q->where('activo', true);
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.admin.categorias-mayores', [
            'categoriasMayores' => $categoriasMayores,
        ])->layout('layouts.app');;
    }
}
