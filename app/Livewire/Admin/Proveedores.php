<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class Proveedores extends Component
{
    use WithPagination;

    public $search = '';
    public $filtroEstado = '';
    public $modal = false;
    public $mode = 'create'; // 'create', 'edit', 'view'
    public $proveedor_id = null;
    public $nombre = '';
    public $empresa = '';
    public $correo = '';
    public $telefono = '';
    public $direccion = '';
    public $nit = '';
    public $contacto_nombre = '';
    public $contacto_telefono = '';
    public $estado = 'activo';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'empresa' => 'nullable|string|max:150',
        'correo' => 'required|email|max:255',
        'telefono' => 'nullable|string|max:50',
        'direccion' => 'nullable|string|max:255',
        'nit' => 'nullable|string|max:50',
        'contacto_nombre' => 'nullable|string|max:255',
        'contacto_telefono' => 'nullable|string|max:50',
        'estado' => 'required|in:activo,inactivo',
    ];

    protected $queryString = ['search', 'filtroEstado'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $proveedores = Proveedor::where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('empresa', 'like', "%{$this->search}%")
                  ->orWhere('correo', 'like', "%{$this->search}%");
            })
            ->when($this->filtroEstado, function ($q) {
                $q->where('estado', $this->filtroEstado);
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.admin.proveedores', compact('proveedores'));
    }

    public function crear()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Proveedor::findOrFail($id);
        $this->proveedor_id = $p->id;
        $this->nombre = $p->nombre;
        $this->empresa = $p->empresa;
        $this->correo = $p->correo;
        $this->telefono = $p->telefono;
        $this->direccion = $p->direccion;
        $this->nit = $p->nit;
        $this->contacto_nombre = $p->contacto_nombre;
        $this->contacto_telefono = $p->contacto_telefono;
        $this->estado = $p->estado;
        $this->mode = 'edit';
        $this->modal = true;
    }

    public function ver($id)
    {
        $p = Proveedor::findOrFail($id);
        $this->proveedor_id = $p->id;
        $this->nombre = $p->nombre;
        $this->empresa = $p->empresa;
        $this->correo = $p->correo;
        $this->telefono = $p->telefono;
        $this->direccion = $p->direccion;
        $this->nit = $p->nit;
        $this->contacto_nombre = $p->contacto_nombre;
        $this->contacto_telefono = $p->contacto_telefono;
        $this->estado = $p->estado;
        $this->mode = 'view';
        $this->modal = true;
    }

    public function guardar()
    {
        if ($this->mode == 'view') {
            return;
        }

        $this->validate();

        Proveedor::updateOrCreate(
            ['id' => $this->proveedor_id],
            [
                'nombre' => $this->nombre,
                'empresa' => $this->empresa,
                'correo' => $this->correo,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'nit' => $this->nit,
                'contacto_nombre' => $this->contacto_nombre,
                'contacto_telefono' => $this->contacto_telefono,
                'estado' => $this->estado,
            ]
        );

        $mensaje = $this->proveedor_id ? 'Proveedor actualizado.' : 'Proveedor creado.';

        $this->dispatch('toast', $mensaje);

        $this->resetForm();
        $this->modal = false;
        $this->resetPage();
    }

    public function confirmarEliminar($id)
    {
        $this->proveedor_id = $id;
        $this->dispatch('confirmar-eliminar');
    }

    #[On('eliminar')]
    public function eliminar()
    {
        Proveedor::find($this->proveedor_id)?->delete();

        $this->dispatch('toast', 'Proveedor eliminado.');
        $this->resetPage();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->proveedor_id = null;
        $this->nombre = '';
        $this->empresa = '';
        $this->correo = '';
        $this->telefono = '';
        $this->direccion = '';
        $this->nit = '';
        $this->contacto_nombre = '';
        $this->contacto_telefono = '';
        $this->estado = 'activo';
        $this->mode = 'create';
    }
}