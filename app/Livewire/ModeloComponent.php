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
    public $mostrarInactivos = false;
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
                Rule::unique('modelos')
                    ->where(fn($q) => $q->where('marca_id', $this->marca_id))
                    ->ignore($this->modeloId)
            ],
            'marca_id' => 'required|exists:marcas,id',
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $modelos = Modelo::with(['marca'])->withCount('productos')
            ->when($this->search !== '', function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhereHas('marca', fn($m) => $m->where('nombre', 'like', "%{$this->search}%"));
            })
            ->when($this->mostrarInactivos,
                fn($q) => $q->where('activo', false),
                fn($q) => $q->where('activo', true)
            )
            ->orderBy('nombre')
            ->paginate(12);

        $marcas = Marca::where('activo', true)->orderBy('nombre')->get();

        return view('livewire.modelo-component', compact('modelos', 'marcas'));
    }

    // ABRIR MODAL PARA CREAR
    public function abrirCrear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    // EDITAR
    public function editar($id)
    {
        $m = Modelo::findOrFail($id);
        $this->modeloId     = $m->id;
        $this->nombre       = $m->nombre;
        $this->descripcion  = $m->descripcion ?? '';
        $this->marca_id     = $m->marca_id;
        $this->modal        = true;
    }

    // GUARDAR (CREAR O ACTUALIZAR)
    public function guardar()
    {
        $this->validate();

        Modelo::updateOrCreate(['id' => $this->modeloId], [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'marca_id'    => $this->marca_id,
            'activo'      => true,
        ]);

        $this->dispatch('toast', $this->modeloId
            ? 'Modelo actualizado correctamente'
            : 'Modelo creado exitosamente'
        );

        $this->cerrarModal();
    }

    // CONFIRMAR ELIMINAR/ACTIVAR
    public function confirmarEliminar($id)
    {
        $this->modeloId = $id;
        $this->confirmDelete = true;
    }

    // ACTIVAR / DESACTIVAR
    public function eliminar()
    {
        $m = Modelo::find($this->modeloId);

        if ($m) {
            $m->activo = !$m->activo;
            $m->save();

            $this->dispatch('toast', $m->activo
                ? 'Modelo reactivado correctamente'
                : 'Modelo desactivado (y todos sus productos también)'
            );
        }

        $this->confirmDelete = false;
        $this->modeloId = null;
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

    public function updatedMostrarInactivos()
    {
        $this->resetPage();
    }
}