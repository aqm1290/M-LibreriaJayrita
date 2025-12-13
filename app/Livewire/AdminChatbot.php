<?php

namespace App\Livewire; // o App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\ClienteWeb;
use App\Models\TurnoCaja;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminChatbot extends Component
{
    public $messages = [];
    public $input = '';

    public function mount()
    {
        $this->messages[] = [
            'from' => 'bot',
            'text' => "Hola, soy Jayrita.\n".
                     "Comandos disponibles:\n".
                     "- stock total\n".
                     "- poco stock\n".
                     "- top ventas\n".
                     "- productos por marca NOMBRE\n".
                     "- productos por categoria NOMBRE\n".
                     "- productos por modelo NOMBRE\n".
                     "- resumen clientes\n".
                     "- resumen clientes web\n".
                     "- resumen ventas\n".
                     "- resumen cajeros\n",
        ];
    }

    public function sendMessage()
    {
        $text = trim($this->input);

        if ($text === '') {
            return;
        }

        // Mensaje del admin
        $this->messages[] = [
            'from' => 'admin',
            'text' => $text,
        ];

        // Respuesta del bot
        $response = $this->handleCommand(strtolower($text));

        $this->messages[] = [
            'from' => 'bot',
            'text' => $response,
        ];

        $this->input = '';
    }

    protected function handleCommand(string $command): string
    {
        // ---------- INVENTARIO BÁSICO ----------
        if ($command === 'stock total') {
            $totalProductos = Producto::where('activo', true)->count();
            $stockSumado   = Producto::where('activo', true)->sum('stock');

            return "Tienes {$totalProductos} productos activos y un total de {$stockSumado} unidades en inventario.";
        }

        if ($command === 'poco stock') {
            $umbral = 5;
            $productos = Producto::where('activo', true)
                ->where('stock', '<=', $umbral)
                ->orderBy('stock', 'asc')
                ->take(5)
                ->get();

            if ($productos->isEmpty()) {
                return "No hay productos con poco stock (menor o igual a {$umbral}).";
            }

            $lineas = ["Productos con poco stock (<= {$umbral}):"];
            foreach ($productos as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }

            return implode("\n", $lineas);
        }

        if ($command === 'top ventas') {
            $top = DB::table('detalle_ventas')
                ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
                ->select(
                    'productos.nombre',
                    'productos.codigo',
                    DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad')
                )
                ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
                ->orderByDesc('total_cantidad')
                ->take(5)
                ->get();

            if ($top->isEmpty()) {
                return "Aún no hay ventas registradas para calcular los productos más vendidos.";
            }

            $lineas = ["Top productos más vendidos:"];
            foreach ($top as $item) {
                $lineas[] = "- {$item->nombre} (código: {$item->codigo}) → {$item->total_cantidad} unidades vendidas";
            }

            return implode("\n", $lineas);
        }

        // ---------- FILTROS POR MARCA / CATEGORÍA / MODELO ----------
        if (str_starts_with($command, 'productos por marca ')) {
            $nombreMarca = trim(str_replace('productos por marca ', '', $command));

            $marca = Marca::where('nombre', 'like', $nombreMarca)->first();

            if (! $marca) {
                return "No encontré la marca \"{$nombreMarca}\".";
            }

            $productos = Producto::where('marca_id', $marca->id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->take(5)
                ->get();

            if ($productos->isEmpty()) {
                return "La marca {$marca->nombre} no tiene productos activos.";
            }

            $lineas = ["Productos activos de la marca {$marca->nombre}:"];
            foreach ($productos as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }

            return implode("\n", $lineas);
        }

        if (str_starts_with($command, 'productos por categoria ')) {
            $nombreCat = trim(str_replace('productos por categoria ', '', $command));

            $categoria = Categoria::where('nombre', 'like', $nombreCat)->first();

            if (! $categoria) {
                return "No encontré la categoría \"{$nombreCat}\".";
            }

            $productos = Producto::where('categoria_id', $categoria->id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->take(5)
                ->get();

            if ($productos->isEmpty()) {
                return "La categoría {$categoria->nombre} no tiene productos activos.";
            }

            $lineas = ["Productos activos de la categoría {$categoria->nombre}:"];
            foreach ($productos as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }

            return implode("\n", $lineas);
        }

        if (str_starts_with($command, 'productos por modelo ')) {
            $nombreModelo = trim(str_replace('productos por modelo ', '', $command));

            $modelo = Modelo::where('nombre', 'like', $nombreModelo)->first();

            if (! $modelo) {
                return "No encontré el modelo \"{$nombreModelo}\".";
            }

            $productos = Producto::where('modelo_id', $modelo->id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->take(5)
                ->get();

            if ($productos->isEmpty()) {
                return "El modelo {$modelo->nombre} no tiene productos activos.";
            }

            $lineas = ["Productos activos del modelo {$modelo->nombre}:"];
            foreach ($productos as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }

            return implode("\n", $lineas);
        }

        // ---------- RESÚMENES DE CLIENTES ----------
        if ($command === 'resumen clientes') {
            $totalClientes = Cliente::count();
            $clientesRecientes = Cliente::orderByDesc('created_at')->take(3)->get();

            $lineas = ["Resumen de clientes de tienda física:"];
            $lineas[] = "- Total de clientes registrados: {$totalClientes}";

            if ($clientesRecientes->isNotEmpty()) {
                $lineas[] = "Últimos 3 clientes web:";
                foreach ($clientesRecientes as $c) {
                    $lineas[] = "- {$c->nombre} (" . ($c->email ?? 'sin email') . ")";
                }
            }


            return implode("\n", $lineas);
        }

        if ($command === 'resumen clientes web') {
            $totalClientesWeb = ClienteWeb::count();
            $clientesRecientes = ClienteWeb::orderByDesc('created_at')->take(3)->get();

            $lineas = ["Resumen de clientes web:"];
            $lineas[] = "- Total de clientes web: {$totalClientesWeb}";

            if ($clientesRecientes->isNotEmpty()) {
                $lineas[] = "Últimos 3 clientes web:";
                foreach ($clientesRecientes as $c) {
                    $lineas[] = "- {$c->nombre} (" . ($c->email ?? 'sin email').")";
                }
            }

            return implode("\n", $lineas);
        }

        // ---------- RESUMEN DE VENTAS ----------
        if ($command === 'resumen ventas') {
            $totalVentas = Venta::count();
            $montoTotal = Venta::sum('total');
            $ventasHoy = Venta::whereDate('created_at', now()->toDateString())->count();

            $lineas = ["Resumen de ventas:"];
            $lineas[] = "- Ventas totales: {$totalVentas}";
            $lineas[] = "- Monto total vendido: {$montoTotal}";
            $lineas[] = "- Ventas de hoy: {$ventasHoy}";

            return implode("\n", $lineas);
        }

        // ---------- RESUMEN DE CAJEROS / TURNOS ----------
        if ($command === 'resumen cajeros') {
            $totalTurnos = TurnoCaja::count();
            $turnosActivos = TurnoCaja::whereNull('hora_cierre')->count();

            // Si tienes relación usuario en TurnoCaja
            $turnosRecientes = TurnoCaja::with('usuario')
                ->orderByDesc('created_at')
                ->take(3)
                ->get();

            $lineas = ["Resumen de cajeros / turnos de caja:"];
            $lineas[] = "- Turnos registrados: {$totalTurnos}";
            $lineas[] = "- Turnos actualmente abiertos: {$turnosActivos}";

            if ($turnosRecientes->isNotEmpty()) {
                $lineas[] = "Últimos turnos:";
                foreach ($turnosRecientes as $t) {
                    $nombre = $t->usuario->name ?? 'Usuario '.$t->usuario_id;
                    $estado = $t->hora_cierre ? 'cerrado' : 'abierto';
                    $lineas[] = "- {$nombre} → turno {$estado}";
                }
            }

            return implode("\n", $lineas);
        }

        return 'Comando no reconocido. Usa los textos que aparecen en el mensaje de bienvenida.';
    }

    public function render()
    {
        return view('livewire.admin-chatbot');
    }
}
