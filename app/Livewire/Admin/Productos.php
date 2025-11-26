<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\{Producto, Marca, Modelo, Categoria, Promo};
use Livewire\Attributes\On;

class Productos extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalVer = false; // NUEVO: modal para ver detalles
    public $productoSeleccionado; // para el modal ver

    public $productoId = null;

    // Campos del formulario
    public $nombre, $descripcion, $precio, $stock, $costo_compra;
    public $color, $tipo, $categoria_id, $marca_id, $modelo_id, $promo_id;
    public $codigo, $url_imagen, $imagen;

    protected $rules = [
        'nombre'        => 'required|string|max:255',
        'codigo'        => 'required|string|max:50|unique:productos,codigo',
        'precio'        => 'required|numeric|min:0',
        'costo_compra'  => 'required|numeric|min:0',
        'stock'         => 'required|integer|min:0',
        'categoria_id'  => 'required|exists:categorias,id',
        'marca_id'      => 'required|exists:marcas,id',
        'modelo_id'     => 'required|exists:modelos,id',
        'promo_id'      => 'nullable|exists:promos,id',
        'color'         => 'nullable|string|max:50',
        'tipo'          => 'nullable|string|max:100',
        'descripcion'   => 'nullable|string|max:1000',
        'imagen'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // máx 5MB
    ];

    protected function messages()
    {
        return [
            'nombre.required'       => 'El nombre del producto es obligatorio.',
            'nombre.max'            => 'El nombre no puede tener más de 255 caracteres.',

            'codigo.required'       => 'El código es obligatorio.',
            'codigo.max'            => 'El código no puede tener más de 50 caracteres.',
            'codigo.unique'         => 'Este código ya está registrado. Usa otro.',

            'precio.required'       => 'El precio de venta es obligatorio.',
            'precio.numeric'        => 'El precio debe ser un número válido.',
            'precio.min'            => 'El precio no puede ser negativo.',

            'costo_compra.required' => 'El costo de compra es obligatorio.',
            'costo_compra.numeric'  => 'El costo debe ser un número válido.',
            'costo_compra.min'      => 'El costo no puede ser negativo.',

            'stock.required'        => 'El stock es obligatorio.',
            'stock.integer'         => 'El stock debe ser un número entero.',
            'stock.min'             => 'El stock no puede ser negativo.',

            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists'   => 'La categoría seleccionada no es válida.',

            'marca_id.required'     => 'Debes seleccionar una marca.',
            'marca_id.exists'       => 'La marca seleccionada no es válida.',

            'modelo_id.required'    => 'Debes seleccionar un modelo.',
            'modelo_id.exists'      => 'El modelo seleccionado no es válido.',

            'promo_id.exists'       => 'La promoción seleccionada no es válida.',

            'color.max'             => 'El color no puede tener más de 50 caracteres.',
            'tipo.max'              => 'El tipo no puede tener más de 100 caracteres.',
            'descripcion.max'       => 'La descripción no puede tener más de 1000 caracteres.',

            'imagen.image'          => 'El archivo debe ser una imagen.',
            'imagen.mimes'          => 'Solo se permiten formatos: jpeg, png, jpg o webp.',
            'imagen.max'            => 'La imagen no puede pesar más de 5 MB.',
        ];
    }

    
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.productos', [
            'productos' => Producto::with(['marca', 'modelo', 'categoria'])
                ->where('nombre', 'like', "%{$this->search}%")
                ->orWhere('codigo', 'like', "%{$this->search}%")
                ->orderBy('id', 'desc')
                ->paginate(12),
            'marcas' => Marca::orderBy('nombre')->get(),
            'modelos' => Modelo::orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'promos' => Promo::orderBy('nombre')->get(),
        ]);
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Producto::findOrFail($id);
        $this->fill($p->only([
            'id', 'nombre', 'descripcion', 'precio', 'stock', 'costo_compra',
            'color', 'tipo', 'categoria_id', 'marca_id', 'modelo_id', 'promo_id', 'codigo', 'url_imagen'
        ]));
        $this->productoId = $p->id;
        $this->modal = true;
    }

    public function ver($id)
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria', 'promo'])->findOrFail($id);
        $this->modalVer = true;
    }

    public function guardar()
    {
        $this->validate();

        if ($this->imagen) {
            $this->url_imagen = $this->imagen->store('productos', 'public');
        }

        Producto::updateOrCreate(['id' => $this->productoId], [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'costo_compra' => $this->costo_compra,
            'color' => $this->color,
            'tipo' => $this->tipo,
            'categoria_id' => $this->categoria_id,
            'marca_id' => $this->marca_id,
            'modelo_id' => $this->modelo_id,
            'promo_id' => $this->promo_id,
            'codigo' => $this->codigo,
            'url_imagen' => $this->url_imagen,
        ]);

        $this->dispatch('toast', $this->productoId ? 'Producto actualizado' : 'Producto creado');
        $this->cerrarModal();
    }

    public function confirmarEliminar($id)
    {
        $this->productoId = $id;
        $this->dispatch('confirmar-eliminar');
    }

    #[On('eliminar')]
    public function eliminar()
    {
        Producto::find($this->productoId)?->delete();
        $this->dispatch('toast', 'Producto eliminado');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->modalVer = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'productoId', 'nombre', 'descripcion', 'precio', 'stock', 'costo_compra',
            'color', 'tipo', 'categoria_id', 'marca_id', 'modelo_id', 'promo_id',
            'codigo', 'url_imagen', 'imagen', 'productoSeleccionado'
        ]);
    }
}