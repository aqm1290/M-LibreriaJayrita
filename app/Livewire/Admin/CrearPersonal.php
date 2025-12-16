<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Cajero;
use Illuminate\Support\Facades\Hash;

class CrearPersonal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // listado / filtros
    public $search = '';
    public $mostrarInactivos = false;

    // modal crear / editar
    public $modal = false;
    public $cajeroId = null; // id del user
    public $nombre = '';
    public $email = '';
    public $password = '';
    public $telefono = '';
    public $turno = 'mañana';
    public $horario = '';

    // modal confirmar deshabilitar
    public $confirmDelete = false;
    public $cajero; // instancia cargada para confirmar

    protected $rules = [
        'nombre'   => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'telefono' => 'nullable|string|max:15',
        'turno'    => 'required|in:mañana,tarde,noche',
        'horario'  => 'required|string|max:100',
    ];

    protected function rulesUpdate()
    {
        return [
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->cajeroId,
            'password' => 'nullable|min:8',
            'telefono' => 'nullable|string|max:15',
            'turno'    => 'required|in:mañana,tarde,noche',
            'horario'  => 'required|string|max:100',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedMostrarInactivos()
    {
        $this->resetPage();
    }

    public function crear()
    {
        $this->resetFormulario();
        $this->modal = true;
    }

    public function editar($id)
    {
        $user   = User::findOrFail($id);
        $cajero = Cajero::where('usuario_id', $id)->first();

        $this->cajeroId = $user->id;
        $this->nombre   = $user->name;
        $this->email    = $user->email;
        $this->telefono = $cajero->telefono ?? '';
        $this->turno    = $cajero->turno ?? 'mañana';
        $this->horario  = $cajero->horario ?? '';
        $this->password = '';

        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetFormulario();
    }

    public function guardar()
    {
        if ($this->cajeroId) {
            // actualizar
            $this->validate($this->rulesUpdate());

            $user = User::findOrFail($this->cajeroId);
            $user->name  = $this->nombre;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();

            $cajero = Cajero::firstOrNew(['usuario_id' => $user->id]);
            $cajero->telefono = $this->telefono;
            $cajero->turno    = $this->turno;
            $cajero->horario  = $this->horario;
            $cajero->activo   = $cajero->activo ?? true;
            $cajero->save();

            $this->dispatch('toast', 'Datos del cajero actualizados.');
        } else {
            // crear
            $this->validate();

            $user = User::create([
                'name'     => $this->nombre,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'rol'      => 'cajero',
            ]);

            Cajero::create([
                'usuario_id' => $user->id,
                'telefono'   => $this->telefono,
                'turno'      => $this->turno,
                'horario'    => $this->horario,
                'activo'     => true,
            ]);

            $this->dispatch('toast', 'Cajero creado con éxito.');
        }

        $this->cerrarModal();
    }

    public function confirmarEliminar($id)
    {
        $this->cajero = Cajero::where('usuario_id', $id)->first();
        $this->cajeroId = $id;
        $this->confirmDelete = true;
    }

    public function eliminar()
    {
        if (! $this->cajeroId) {
            return;
        }

        $cajero = Cajero::where('usuario_id', $this->cajeroId)->first();
        if ($cajero) {
            $cajero->activo = ! $cajero->activo;
            $cajero->save();
        }

        $this->confirmDelete = false;
        $this->dispatch('toast', $cajero->activo ? 'Cajero reactivado.' : 'Cajero deshabilitado.');
    }

    protected function resetFormulario()
    {
        $this->reset(['cajeroId', 'nombre', 'email', 'password', 'telefono', 'turno', 'horario']);
        $this->turno = 'mañana';
    }

    public function render()
    {
        $query = User::select(
                'users.id',
                'users.name',
                'users.email',
                'cajeros.horario',
                'cajeros.turno',
                'cajeros.telefono',
                'cajeros.activo'
            )
            ->join('cajeros', 'cajeros.usuario_id', '=', 'users.id')
            ->where('users.rol', 'cajero');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('users.name', 'like', '%' . $this->search . '%')
                  ->orWhere('users.email', 'like', '%' . $this->search . '%')
                  ->orWhere('cajeros.telefono', 'like', '%' . $this->search . '%');
            });
        }

        if (! $this->mostrarInactivos) {
            $query->where('cajeros.activo', true);
        }

        $cajeros = $query->orderBy('users.name')->paginate(10);

        return view('livewire.admin.crear-personal', compact('cajeros'))
            ->layout('layouts.app');
    }
}
