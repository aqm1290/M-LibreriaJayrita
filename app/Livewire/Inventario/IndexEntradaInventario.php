<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EntradaInventario;

class IndexEntradaInventario extends Component
{
    use WithPagination;

    public $detalleVisible = null;
    public $search = '';
    public $showToast = false;
    public $toastMessage = '';
    public $toastType = 'success';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function verDetalles($id)
    {
        $this->detalleVisible = $id;
    }

    public function cerrarModal()
    {
        $this->detalleVisible = null;
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmar-eliminacion', $id);
    }

    public function delete($id)
    {
        $entrada = EntradaInventario::with('detalles.producto')
            ->where('id', $id)
            ->first();

        if (!$entrada) {
            session()->flash('message', 'La entrada no existe o ya fue eliminada.');
            $this->detalleVisible = null;
            return;
        }

        foreach ($entrada->detalles as $detalle) {
            $producto = $detalle->producto;
            if ($producto) {
                $producto->stock -= $detalle->cantidad;
                $producto->save();
            }
            $detalle->delete();
        }

        $entrada->delete();
        $this->toastMessage = '¡Entrada eliminada correctamente y el stock ha sido revertido!';
        $this->toastType = 'error'; // o 'error' si quieres rojo
        $this->showToast = true;

        $this->detalleVisible = null;
    }
    // PROPIEDAD CALCULADA CORRECTA (esta vez SÍ funciona)
    public function entradaDetalle()
    {
        if (!$this->detalleVisible) {
            return null;
        }

        return EntradaInventario::with(['proveedor', 'detalles.producto'])
            ->where('id', $this->detalleVisible)
            ->first(); // ← first() en vez de findOrFail
    }

    public function render()
    {
        $entradas = EntradaInventario::with('proveedor')
            ->when($this->search, function ($query) {
                $query->whereHas('proveedor', fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
                    ->orWhere('fecha', 'like', "%{$this->search}%")
                    ->orWhere('observacion', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.inventario.index-entrada-inventario', [
            'entradas'      => $entradas,
            'comparaciones' => $this->comparaciones,
        ]);
    }


    public function getComparacionesProperty()
{
    $entradas = EntradaInventario::with(['detalles.producto', 'proveedor'])
        ->latest()
        ->take(30)
        ->get();

    $filas = [];

    foreach ($entradas as $entrada) {
        foreach ($entrada->detalles as $detalle) {
            if (!$detalle->producto) {
                continue;
            }

            $producto = $detalle->producto;

            $costoEntrada = (float) $detalle->costo;
            // AHORA COMPARA CONTRA costo_compra DEL PRODUCTO
            $costoReferencia = (float) ($producto->costo_compra ?? 0);

            $diferenciaAbs = $costoEntrada - $costoReferencia;
            $diferenciaPct = $costoReferencia > 0
                ? ($diferenciaAbs / $costoReferencia) * 100
                : null;

            $filas[] = [
                'entrada_id'       => $entrada->id,
                'fecha'            => $entrada->fecha,
                'proveedor'        => optional($entrada->proveedor)->nombre,
                'producto'         => $producto->nombre,
                'codigo'           => $producto->codigo,
                'costo_entrada'    => $costoEntrada,
                // IMPORTANTE: este es el costo_compra del producto
                'costo_compra_ref' => $costoReferencia,
                'diferencia_abs'   => $diferenciaAbs,
                'diferencia_pct'   => $diferenciaPct,
            ];
        }
    }

    return collect($filas)
        ->sortByDesc('fecha')
        ->values();
}


}