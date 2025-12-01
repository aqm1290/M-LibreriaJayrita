<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Categoria;
use App\Models\Promocion;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Productos extends Component
{
    use WithPagination;
    use WithFileUploads;

    // === PROPIEDADES PÚBLICAS (obligatorio en Livewire 3) ===
    public $search = '';
    public $productoId = null;
    public $modal = false;
    public $modalVer = false;
    public $filtroCategoria = '';
    public $filtroMarca = '';
    public $soloStockBajo = false;
    public $mostrarInactivos = false;

    // Campos del formulario
    public $nombre = '';
    public $descripcion = '';
    public $precio = '';
    public $stock = '';
    public $costo_compra = '';
    public $color = '';
    public $tipo = '';
    public $categoria_id = '';
    public $marca_id = '';
    public $modelo_id = '';
    public $promo_id = null;
    public $codigo = '';

    // Imagen (¡IMPORTANTE! debe ser público)
    public $imagen = null;           // nueva imagen subida
    public $url_imagen = null;       // imagen actual en BD (ruta)

    public $productoSeleccionado;

    protected $queryString = ['search'];

    // === REGLAS DE VALIDACIÓN ===
    protected function rules()
    {
        return [
            'nombre'       => 'required|string|max:255',
            'codigo'       => [
                'required',
                'string',
                'max:50',
                'unique:productos,codigo' . ($this->productoId ? ',' . $this->productoId : '')
            ],
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
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique'   => 'Este código ya existe.',
            'precio.required' => 'El precio es obligatorio.',
            'stock.required'  => 'El stock es obligatorio.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'marca_id.required'    => 'Selecciona una marca.',
            'modelo_id.required'   => 'Selecciona un modelo.',
            'imagen.image'    => 'Debe ser una imagen válida.',
            'imagen.max'      => 'La imagen no puede pesar más de 5 MB.',
        ];
    }

    // === RESETEAR PÁGINA AL CAMBIAR FILTROS ===
    public function updated($property)
    {
        if (in_array($property, [
            'search', 'filtroCategoria', 'filtroMarca',
            'soloStockBajo', 'mostrarInactivos'
        ])) {
            $this->resetPage();
        }

        // Filtrar modelos cuando cambie la marca
        if ($property === 'marca_id') {
            $this->modelo_id = '';
        }
    }

    public function render()
    {
        $query = Producto::with(['marca', 'modelo', 'categoria', 'promo']);

        // Búsqueda
        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('codigo', 'like', "%{$this->search}%");
            });
        }

        // Filtros
        if ($this->filtroCategoria) $query->where('categoria_id', $this->filtroCategoria);
        if ($this->filtroMarca)     $query->where('marca_id', $this->filtroMarca);
        if ($this->soloStockBajo)   $query->where('stock', '<=', 5);

        // Activos / Inactivos
        $query->where('activo', $this->mostrarInactivos ? false : true);

        return view('livewire.admin.productos', [
            'productos'  => $query->orderByDesc('id')->paginate(10),
            'marcas'     => Marca::orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'promos'     => Promocion::orderBy('nombre')->get(),
            // Modelos filtrados por marca seleccionada
            'modelos'    => $this->marca_id
                ? Modelo::where('marca_id', $this->marca_id)->orderBy('nombre')->get()
                : Modelo::orderBy('nombre')->get(),
        ]);
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'filtroCategoria', 'filtroMarca', 'soloStockBajo', 'mostrarInactivos']);
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Producto::findOrFail($id);

        // Rellenar todos los campos
        $this->productoId     = $p->id;
        $this->nombre         = $p->nombre;
        $this->codigo         = $p->codigo;
        $this->descripcion    = $p->descripcion;
        $this->precio         = $p->precio;
        $this->costo_compra   = $p->costo_compra;
        $this->stock          = $p->stock;
        $this->color          = $p->color;
        $this->tipo           = $p->tipo;
        $this->categoria_id   = $p->categoria_id;
        $this->marca_id       = $p->marca_id;
        $this->modelo_id      = $p->modelo_id;
        $this->promo_id       = $p->promo_id;
        $this->url_imagen     = $p->url_imagen; // para mostrar imagen actual

        $this->imagen = null; // limpia el input file
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

        // Manejo de imagen
        $rutaImagen = $this->url_imagen;

        if ($this->imagen) {
            // Borrar imagen anterior si existe
            if ($this->url_imagen && Storage::disk('public')->exists($this->url_imagen)) {
                Storage::disk('public')->delete($this->url_imagen);
            }
            $rutaImagen = $this->imagen->store('productos', 'public');
        }

        Producto::updateOrCreate(['id' => $this->productoId], [
            'nombre'       => $this->nombre,
            'codigo'       => $this->codigo,
            'descripcion'  => $this->descripcion,
            'precio'       => $this->precio,
            'costo_compra' => $this->costo_compra,
            'stock'        => $this->stock,
            'categoria_id' => $this->categoria_id,
            'marca_id'     => $this->marca_id,
            'modelo_id'    => $this->modelo_id,
            'promo_id'     => $this->promo_id,
            'color'        => $this->color,
            'tipo'         => $this->tipo,
            'url_imagen'   => $rutaImagen,
            'activo'       => true,
        ]);

        $this->dispatch('toast', $this->productoId ? 'Producto actualizado' : 'Producto creado con éxito');
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
            $producto->update(['activo' => !$producto->activo]);
            $this->dispatch('toast', $producto->activo ? 'Producto reactivado' : 'Producto desactivado');
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
            'productoId', 'nombre', 'codigo', 'descripcion', 'precio', 'costo_compra',
            'stock', 'color', 'tipo', 'categoria_id', 'marca_id', 'modelo_id',
            'promo_id', 'imagen', 'url_imagen', 'productoSeleccionado'
        ]);
        $this->resetErrorBag();
    }
}