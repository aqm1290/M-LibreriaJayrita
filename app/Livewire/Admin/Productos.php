<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Categoria;
use App\Models\Promo;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Productos extends Component
{
    use WithPagination;
    use WithFileUploads;


    public $search = '';
    public $productoId;
    public $modal = false;
    public $modalVer = false;
    public $filtroCategoria = '';
    public $filtroMarca = '';
    public $soloStockBajo = false;
    public $mostrarInactivos = false;

    public $nombre, $descripcion, $precio, $stock, $costo_compra;
    public $color, $tipo, $categoria_id, $marca_id, $modelo_id, $promo_id;
    public $codigo, $url_imagen, $imagen, $productoSeleccionado;

    protected $queryString = ['search'];

    protected $rules = [
        'nombre'       => 'required|string|max:255',
        'codigo'       => 'required|string|max:50|unique:productos,codigo',
        'precio'       => 'required|numeric|min:0',
        'costo_compra' => 'required|numeric|min:0',
        'stock'        => 'required|integer|min:0',
        'categoria_id' => 'required|exists:categorias,id',
        'marca_id'     => 'required|exists:marcas,id',
        'modelo_id'    => 'required|exists:modelos,id',
        'promo_id'     => 'nullable|exists:promos,id',
        'color'        => 'nullable|string|max:50',
        'tipo'         => 'nullable|string|max:100',
        'descripcion'  => 'nullable|string|max:1000',
        'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.productos', [
            'productos' => Producto::with(['marca', 'modelo', 'categoria', 'promo'])
                ->when($this->search !== '', fn($q) => $q->where(function ($sq) {
                    $sq->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('codigo', 'like', "%{$this->search}%");
                }))
                ->when($this->filtroCategoria !== '', fn($q) => $q->where('categoria_id', $this->filtroCategoria))
                ->when($this->filtroMarca !== '', fn($q) => $q->where('marca_id', $this->filtroMarca))
                ->when($this->soloStockBajo, fn($q) => $q->where('stock', '<=', 5))
                ->when($this->mostrarInactivos, function ($q) {return $q->where('activo', false);}, function ($q) {return $q->where('activo', true);})
                ->orderBy('id', 'desc')
                ->paginate(10),

            'marcas'     => Marca::orderBy('nombre')->get(),
            'modelos'    => Modelo::orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'promos'     => Promo::orderBy('nombre')->get(),
        ]);
    }

    // === AÑADE ESTOS MÉTODOS (para que al cambiar filtro se reinicie página) ===
    public function updatedSearch()          { $this->resetPage(); }
    public function updatedFiltroCategoria() { $this->resetPage(); }
    public function updatedFiltroMarca()     { $this->resetPage(); }
    public function updatedSoloStockBajo()   { $this->resetPage(); }
    public function updatedMostrarInactivos(){ $this->resetPage(); }

    
    public function limpiarFiltros()
    {
        $this->reset(['search', 'filtroCategoria', 'filtroMarca', 'soloStockBajo', 'mostrarInactivos']);
        $this->resetPage();
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
            'color', 'tipo', 'categoria_id', 'marca_id', 'modelo_id',
            'promo_id', 'codigo', 'url_imagen',
        ]));

        $this->productoId = $p->id;
        $this->modal = true;
    }

    public function ver($id)
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria', 'promo'])
            ->findOrFail($id);

        $this->modalVer = true;
    }

    public function guardar()
    {
        $rules = $this->rules;

        if ($this->productoId) {
            $rules['codigo'] = "required|string|max:50|unique:productos,codigo,{$this->productoId}";
        }

        $this->validate($rules);

        if ($this->imagen) {
            if ($this->url_imagen) {
                Storage::disk('public')->delete($this->url_imagen);
            }

            $this->url_imagen = $this->imagen->store('productos', 'public');
        }

        Producto::updateOrCreate(['id' => $this->productoId], [
            'nombre'       => $this->nombre,
            'codigo'       => $this->codigo,
            'precio'       => $this->precio,
            'costo_compra' => $this->costo_compra,
            'stock'        => $this->stock,
            'categoria_id' => $this->categoria_id,
            'marca_id'     => $this->marca_id,
            'modelo_id'    => $this->modelo_id,
            'promo_id'     => $this->promo_id ?: null,
            'color'        => $this->color,
            'tipo'         => $this->tipo,
            'descripcion'  => $this->descripcion,
            'url_imagen'   => $this->url_imagen,
            'activo'       => true,
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
        $producto = Producto::find($this->productoId);

        if ($producto) {
            $producto->activo = false;
            $producto->save();
            $this->dispatch('toast', 'Producto desactivado');
        }

        $this->productoId = null;
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
            'codigo', 'url_imagen', 'imagen', 'productoSeleccionado',
        ]);
    }

    

    
}
