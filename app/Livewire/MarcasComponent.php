<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Marca;

class MarcasComponent extends Component
{
    public $marcas;
    public $nombre, $descripcion, $marca_id;
    public $modal = false;

    protected $listeners = [
        'deleteConfirmed' => 'delete'
    ];

    public function render()
    {
        $this->marcas = Marca::orderBy('id', 'DESC')->get();
        return view('livewire.marcas-component');
    }

    public function openModal()
    {
        $this->resetInput();
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function resetInput()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->marca_id = null;
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required',
        ]);

        Marca::updateOrCreate(
            ['id' => $this->marca_id],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]
        );

        $this->dispatch('toast', [
            'icon'  => 'success',
            'title' => $this->marca_id ? 'Marca actualizada' : 'Marca creada'
        ]);

        $this->closeModal();
        $this->resetInput();
    }

    public function edit($id)
    {
        $marca = Marca::findOrFail($id);

        $this->marca_id = $id;
        $this->nombre = $marca->nombre;
        $this->descripcion = $marca->descripcion;

        $this->modal = true;
    }

    public function confirmDelete($id)
    {
        $this->marca_id = $id;

        $this->dispatch('confirmDelete');
    }

    public function delete()
    {
        Marca::destroy($this->marca_id);

        $this->dispatch('toast', [
            'icon'  => 'success',
            'title' => 'Marca eliminada correctamente'
        ]);

        $this->resetInput();
    }
}
