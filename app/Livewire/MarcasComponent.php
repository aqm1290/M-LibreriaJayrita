<?php

namespace App\Livewire;

use App\Models\Marca;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;  

class MarcasComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $mostrarInactivos = false;    
    public $modal = false;
    public $confirmDelete = false;

    public $marca_id = null;
    public $nombre = '';
    public $descripcion = '';
    public $imagen;
    public $url_imagen;

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
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function render()
    {
        $marcas = Marca::withCount('productos')
            ->when($this->search !== '', fn($q) => $q->where('nombre', 'like', "%{$this->search}%")
                ->orWhere('descripcion', 'like', "%{$this->search}%"))
            ->when(!$this->mostrarInactivos, fn($q) => $q->where('activo', true))
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
        $this->url_imagen  = $marca->url_imagen;
        $this->imagen      = null;
        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        $rutaImagen = $this->url_imagen;

        if ($this->imagen) {
            if ($this->url_imagen && Storage::disk('public')->exists($this->url_imagen)) {
                Storage::disk('public')->delete($this->url_imagen);
            }
            $rutaImagen = $this->imagen->store('marcas', 'public');
        }

        Marca::updateOrCreate(['id' => $this->marca_id], [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'url_imagen'  => $rutaImagen,
            'activo'      => true,
        ]);

        $this->dispatch('toast', $this->marca_id ? 'Marca actualizada correctamente' : 'Marca creada exitosamente');
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

        if ($marca) {
            $marca->activo = !$marca->activo;
            $marca->save(); // ← Aquí se dispara el Observer y se desactivan productos + modelos

            $this->dispatch('toast', $marca->activo 
                ? 'Marca reactivada correctamente' 
                : 'Marca y todos sus productos/modelos desactivados'
            );
        }

        $this->confirmDelete = false;
        $this->marca_id = null;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->confirmDelete = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['marca_id', 'nombre', 'descripcion', 'imagen', 'url_imagen']);
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

    // ←←←← LA CLAVE: ESTE MÉTODO MAGICO ←←←←
    public function getMarcaProperty()
    {
        return $this->marca_id ? Marca::find($this->marca_id) : null;
    }
}