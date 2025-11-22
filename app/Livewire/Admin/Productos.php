<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Categoria;
use App\Models\Promo;
use Livewire\Attributes\On;

class Productos extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modal = false;

    public $productoId = null;

    // Campos
    public $nombre, $descripcion, $precio, $stock, $costo_compra;
    public $color, $tipo, $categoria_id, $marca_id, $modelo_id, $promo_id;
    public $codigo, $url_imagen, $imagen;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'costo_compra' => 'required|numeric|min:0',
        'color' => 'nullable|string|max:255',
        'tipo' => 'nullable|string|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'marca_id' => 'required|exists:marcas,id',
        'modelo_id' => 'required|exists:modelos,id',
        'promo_id' => 'nullable|exists:promos,id',
        'codigo' => 'required|string|max:50',
        'url_imagen' => 'nullable|string',
    ];

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.productos', [
            'productos' => Producto::where('nombre', 'like', "%{$this->search}%")
                ->orWhere('codigo', 'like', "%{$this->search}%")
                ->orderBy('id', 'desc')
                ->paginate(10),
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

        $this->productoId = $p->id;
        $this->nombre = $p->nombre;
        $this->descripcion = $p->descripcion;
        $this->precio = $p->precio;
        $this->stock = $p->stock;
        $this->costo_compra = $p->costo_compra;
        $this->color = $p->color;
        $this->tipo = $p->tipo;
        $this->categoria_id = $p->categoria_id;
        $this->marca_id = $p->marca_id;
        $this->modelo_id = $p->modelo_id;
        $this->promo_id = $p->promo_id;
        $this->codigo = $p->codigo;
        $this->url_imagen = $p->url_imagen;

        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate();

        if ($this->imagen) {
            $this->url_imagen = $this->imagen->store('productos', 'public');
        }

        Producto::updateOrCreate(
            ['id' => $this->productoId],
            [
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
            ]
        );

        $mensaje = $this->productoId
            ? "Producto actualizado correctamente"
            : "Producto creado exitosamente";

        $this->dispatch('toast', $mensaje);
        $this->resetForm();
        $this->modal = false;
        $this->resetPage();
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
        $this->dispatch('toast', "Producto eliminado correctamente");
        $this->resetPage();
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    private function resetForm()
    {
        $this->productoId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->precio = '';
        $this->stock = '';
        $this->costo_compra = '';
        $this->color = '';
        $this->tipo = '';
        $this->categoria_id = null;
        $this->marca_id = null;
        $this->modelo_id = null;
        $this->promo_id = null;
        $this->codigo = '';
        $this->url_imagen = null;
        $this->imagen = null;
    }
}
