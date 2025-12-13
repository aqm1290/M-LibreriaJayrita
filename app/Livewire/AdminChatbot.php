<?php

namespace App\Livewire; // o App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\ClienteWeb;
use App\Models\TurnoCaja;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;

class AdminChatbot extends Component
{
    public $messages = [];
    public $input = '';

    public function mount()
    {
        $resumen = $this->resumenBienvenidaCajeros();

        $this->messages[] = [
            'from' => 'bot',
            'text' => "Hola, soy Jayrita, la asistente del administrador de la librería.\n".
                    $resumen."\n".
                    "Puedes preguntarme sobre ventas, clientes, inventario, categorías, etc.",
        ];
    }
    protected function resumenBienvenidaCajeros(): string
    {
        // Ventas de hoy
        $ventasHoy = Venta::whereDate('created_at', now()->toDateString())->count();
        $montoHoy  = Venta::whereDate('created_at', now()->toDateString())->sum('total');

        // Último turno de caja
        $ultimoTurno = TurnoCaja::with('usuario')
            ->orderByDesc('created_at')
            ->first();

        $lineas = [];

        if ($ventasHoy > 0) {
            $lineas[] = "Hoy se registraron {$ventasHoy} ventas por un total de {$montoHoy}.";
        } else {
            $lineas[] = "Hoy todavía no se registraron ventas.";
        }

        if ($ultimoTurno) {
            $nombre = $ultimoTurno->usuario->name ?? ('Usuario '.$ultimoTurno->usuario_id);
            $estado = $ultimoTurno->activo ? 'abierto' : 'cerrado';
            $lineas[] = "El último turno de caja es de {$nombre} y está actualmente {$estado}.";
        }

        return implode("\n", $lineas);
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
        $response = $this->handleMessage($text);

        $this->messages[] = [
            'from' => 'bot',
            'text' => $response,
        ];

        $this->input = '';
    }
    protected function normalize(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');

    $replacements = [
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'ñ' => 'n',
    ];

    return strtr($text, $replacements);
}


    protected function handleMessage(string $text): string
    {
        $command = strtolower($text);
        $command = $this->normalize($text);
        // --------- RESÚMENES DE VENTAS POR PERIODO ---------
        if (str_contains($command, 'resumen de ventas diario') || $command === 'diario') {
            return $this->respuestaResumenVentasPeriodo('diario');
        }

        if (str_contains($command, 'resumen de ventas semanal') || $command === 'semanal') {
            return $this->respuestaResumenVentasPeriodo('semanal');
        }

        if (str_contains($command, 'resumen de ventas mensual') || $command === 'mensual') {
            return $this->respuestaResumenVentasPeriodo('mensual');
        }

        // --------- CATEGORÍAS, MARCAS, PROVEEDORES ---------
        if (
            str_contains($command, 'categorias hay') ||
            str_contains($command, 'que categorias tengo') ||
            str_contains($command, 'lista de categorias')
        ) {
            return $this->respuestaCategorias();
        }

        if (
            str_contains($command, 'marcas hay') ||
            str_contains($command, 'que marcas tengo') ||
            str_contains($command, 'lista de marcas')
        ) {
            return $this->respuestaMarcas();
        }

        if (
            str_contains($command, 'proveedores hay') ||
            str_contains($command, 'que proveedores tengo') ||
            str_contains($command, 'lista de proveedores')
        ) {
            return $this->respuestaProveedores();
        }


        // --------- OTROS ATAJOS CON BD ---------
        if (
            str_contains($command, 'resumen ventas') ||
            str_contains($command, 'total de ventas') ||
            str_contains($command, 'total ventas')
        ) {
            return $this->respuestaResumenVentas();
        }


        if (str_contains($command, 'top ventas')) {
            return $this->respuestaTopVentas();
        }

        if (
            str_contains($command, 'poco stock') ||
            str_contains($command, 'productos por agotarse') ||
            str_contains($command, 'productos de bajo stock') ||
            str_contains($command, 'productos con bajo stock') ||
            str_contains($command, 'que productos estan con bajo stock') ||
            str_contains($command, 'que productos están con bajo stock')||
            str_contains($command, 'que productos no tienen stock') ||
            str_contains($command, 'que productos están con poco stock')||
            str_contains($command, 'stock bajo') ||
            str_contains($command, 'bajo stock')
        ) {
            return $this->respuestaPocoStock();
        }
        if (
            str_contains($command, 'recomenda que comprar')||
            str_contains($command, 'que productos recomiendas comprar') ||
            str_contains($command, 'productos que recomiendes') ||
            str_contains($command, 'que comprar') ||
            str_contains($command, 'que puedo comprar') ||
            str_contains($command, 'que compramos') ||
            str_contains($command, 'que compro mas') 
            
            ) {
            return $this->respuestaRecomendacionesReposicion();
        }

        if (str_contains($command, 'stock total') || (str_contains($command, 'inventario') && str_contains($command, 'total'))) {
            return $this->respuestaStockTotal();
        }
        if (
            str_contains($command, 'recomiend') &&
            (str_contains($command, 'comprar') || str_contains($command, 'reponer'))
        ) {
            return $this->respuestaRecomendacionesReposicion();
        }
        
        if (
            str_contains($command, 'productos que no se venden') ||
            str_contains($command, 'no estan saliendo') ||
            str_contains($command, 'no están saliendo')
        ) {
            return $this->respuestaProductosPocoMovimiento();
        }


        if (str_contains($command, 'resumen clientes web')) {
            return $this->respuestaResumenClientesWeb();
        }

        if (str_contains($command, 'resumen clientes')) {
            return $this->respuestaResumenClientes();
        }

        if (str_contains($command, 'resumen cajeros') || str_contains($command, 'turnos de caja')) {
            return $this->respuestaResumenCajeros();
        }

        // --------- SALUDOS SENCILLOS ---------
        if (in_array($command, ['hola', 'hola jayrita', 'buenas', 'buenos dias', 'buenas tardes', 'buenas noches'])) {
            return "Hola, soy Jayrita. Puedo ayudarte con resúmenes de ventas, clientes, inventario y productos con poco stock. Pregúntame lo que necesites.";
        }

        // Si no coincide con nada → IA para respuesta fluida
        return $this->respuestaIA($text);
    }

    // ============ RESPUESTAS QUE USAN TU BASE DE DATOS ============

    protected function respuestaResumenVentasPeriodo(string $periodo): string
    {
        $query = Venta::query();
        $titulo = '';

        if ($periodo === 'diario') {
            $query->whereDate('created_at', now()->toDateString());
            $titulo = 'Resumen de ventas de hoy';
        } elseif ($periodo === 'semanal') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
            $titulo = 'Resumen de ventas de esta semana';
        } elseif ($periodo === 'mensual') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);
            $titulo = 'Resumen de ventas de este mes';
        }

        $total = $query->count();
        $monto = $query->sum('total');

        if ($total === 0) {
            return "{$titulo}: todavía no se registraron ventas en este periodo.";
        }

        return "{$titulo}:\n- Ventas: {$total}\n- Monto total: {$monto}";
    }
    protected function respuestaRecomendacionesReposicion(): string
    {
        // Agotados
        $agotados = Producto::where('activo', true)
            ->where('stock', '<=', 0)
            ->orderBy('nombre')
            ->take(5)
            ->get();

        // Con poco stock
        $umbral = 5;
        $pocoStock = Producto::where('activo', true)
            ->where('stock', '>', 0)
            ->where('stock', '<=', $umbral)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        if ($agotados->isEmpty() && $pocoStock->isEmpty()) {
            return "Revisé el inventario y no encontré productos agotados ni con poco stock. Por ahora no es urgente comprar.";
        }

        $lineas = ["Te recomiendo revisar estos productos para compra:"];

        if ($agotados->isNotEmpty()) {
            $lineas[] = "\nProductos agotados:";
            foreach ($agotados as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }
        }

        if ($pocoStock->isNotEmpty()) {
            $lineas[] = "\nProductos con poco stock (<= {$umbral}):";
            foreach ($pocoStock as $p) {
                $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
            }
        }

        return implode("\n", $lineas);
    }
    protected function respuestaProductosPocoMovimiento(): string
    {
        // Productos activos
        $productos = Producto::where('activo', true)->pluck('id', 'nombre');

        if ($productos->isEmpty()) {
            return "No encontré productos activos para analizar el movimiento de ventas.";
        }

        // Ventas por producto (últimos 3 meses, por ejemplo)
        $desde = now()->subMonths(3);

        $ventas = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereDate('ventas.created_at', '>=', $desde)
            ->select('detalle_ventas.producto_id', DB::raw('SUM(detalle_ventas.cantidad) as total'))
            ->groupBy('detalle_ventas.producto_id')
            ->pluck('total', 'producto_id');

        // Productos con cero ventas o muy pocas
        $pocoMovimiento = [];

        foreach ($productos as $nombre => $id) {
            $vendidas = $ventas[$id] ?? 0;
            if ($vendidas <= 1) { // puedes ajustar este umbral
                $pocoMovimiento[$nombre] = $vendidas;
            }
        }

        if (empty($pocoMovimiento)) {
            return "En los últimos meses no encontré productos con ventas tan bajas como para preocuparse demasiado.";
        }

        $pocoMovimiento = array_slice($pocoMovimiento, 0, 5, true);

        $lineas = ["Detecté productos con poco movimiento en los últimos meses:"];
        foreach ($pocoMovimiento as $nombre => $vendidas) {
            $lineas[] = "- {$nombre} → {$vendidas} unidades vendidas.";
        }

        $lineas[] = "\nTe recomiendo revisar si vale la pena hacer promoción, cambiar de ubicación o no volver a comprar algunos de ellos.";

        return implode("\n", $lineas);
    }



    protected function respuestaStockTotal(): string
    {
        $totalProductos = Producto::where('activo', true)->count();
        $stockSumado   = Producto::where('activo', true)->sum('stock');

        return "Actualmente tienes {$totalProductos} productos activos y un total de {$stockSumado} unidades en inventario.";
    }

    protected function respuestaPocoStock(): string
    {
        $umbral = 5;
        $productos = Producto::where('activo', true)
            ->where('stock', '<=', $umbral)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        if ($productos->isEmpty()) {
            return "Revisé el inventario y no encontré productos con stock menor o igual a {$umbral}. Todo está bien por ahora.";
        }

        $lineas = ["Detecté estos productos con poco stock (<= {$umbral}):"];
        foreach ($productos as $p) {
            $lineas[] = "- {$p->nombre} (código: {$p->codigo}) → stock: {$p->stock}";
        }

        return implode("\n", $lineas);
    }

    protected function respuestaTopVentas(): string
    {
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
            return "Aún no hay suficientes ventas registradas como para calcular un top de productos más vendidos.";
        }

        $lineas = ["Estos son algunos de los productos más vendidos:"];
        foreach ($top as $item) {
            $lineas[] = "- {$item->nombre} (código: {$item->codigo}) → {$item->total_cantidad} unidades vendidas.";
        }

        return implode("\n", $lineas);
    }

    protected function respuestaResumenClientes(): string
    {
        $totalClientes = Cliente::count();
        $clientesRecientes = Cliente::orderByDesc('created_at')->take(3)->get();

        $lineas = ["Resumen de clientes de tienda física:"];
        $lineas[] = "- Total de clientes registrados: {$totalClientes}";

        if ($clientesRecientes->isNotEmpty()) {
            $lineas[] = "Algunos de los clientes más recientes:";
            foreach ($clientesRecientes as $c) {
                $ci = $c->ci ?? 'sin CI';
                $lineas[] = "- {$c->nombre} ({$ci})";
            }
        }

        return implode("\n", $lineas);
    }

    protected function respuestaResumenClientesWeb(): string
    {
        $totalClientesWeb = ClienteWeb::count();
        $clientesRecientes = ClienteWeb::orderByDesc('created_at')->take(3)->get();

        $lineas = ["Resumen de clientes web:"];
        $lineas[] = "- Total de clientes web: {$totalClientesWeb}";

        if ($clientesRecientes->isNotEmpty()) {
            $lineas[] = "Algunos de los clientes web más recientes:";
            foreach ($clientesRecientes as $c) {
                $email = $c->email ?? 'sin email';
                $lineas[] = "- {$c->nombre} ({$email})";
            }
        }

        return implode("\n", $lineas);
    }
    protected function respuestaCategorias(): string
{
    $categorias = \App\Models\Categoria::where('activo', true)
        ->orderBy('nombre')
        ->get(['nombre']);

    if ($categorias->isEmpty()) {
        return "Por ahora no tienes categorías activas registradas en el sistema.";
    }

    $nombres = $categorias->pluck('nombre')->all();
    $lista = implode(", ", $nombres);

    return "Actualmente tienes estas categorías activas:\n".$lista;
}

protected function respuestaMarcas(): string
{
    $marcas = \App\Models\Marca::where('activo', true)
        ->orderBy('nombre')
        ->get(['nombre']);

    if ($marcas->isEmpty()) {
        return "Por ahora no tienes marcas activas registradas en el sistema.";
    }

    $nombres = $marcas->pluck('nombre')->all();
    $lista = implode(", ", $nombres);

    return "Actualmente trabajas con estas marcas:\n".$lista;
}

protected function respuestaProveedores(): string
{
    $proveedores = \App\Models\Proveedor::orderBy('nombre')->get(['nombre', 'empresa']);

    if ($proveedores->isEmpty()) {
        return "Todavía no tienes proveedores registrados en el sistema.";
    }

    $lineas = ["Estos son algunos de tus proveedores:"];
    foreach ($proveedores as $p) {
        $empresa = $p->empresa ? " ({$p->empresa})" : '';
        $lineas[] = "- {$p->nombre}{$empresa}";
    }

    return implode("\n", $lineas);
}


    protected function respuestaResumenVentas(): string
    {
        $totalVentas = Venta::count();
        $montoTotal = Venta::sum('total');
        $ventasHoy = Venta::whereDate('created_at', now()->toDateString())->count();

        $lineas = ["Resumen general de ventas:"];
        $lineas[] = "- Ventas totales registradas: {$totalVentas}";
        $lineas[] = "- Monto total vendido (todas las ventas): {$montoTotal}";
        $lineas[] = "- Ventas realizadas hoy: {$ventasHoy}";

        return implode("\n", $lineas);
    }

    protected function respuestaResumenCajeros(): string
    {
        $totalTurnos = TurnoCaja::count();
        $turnosActivos = TurnoCaja::where('activo', true)->count();

        $turnosRecientes = TurnoCaja::with('usuario')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $lineas = ["Resumen de turnos de caja:"];
        $lineas[] = "- Turnos registrados: {$totalTurnos}";
        $lineas[] = "- Turnos actualmente abiertos: {$turnosActivos}";

        if ($turnosRecientes->isNotEmpty()) {
            $lineas[] = "Algunos de los últimos turnos:";
            foreach ($turnosRecientes as $t) {
                $nombre = $t->usuario->name ?? ('Usuario '.$t->usuario_id);
                $estado = $t->activo ? 'abierto' : 'cerrado';
                $lineas[] = "- {$nombre} → turno {$estado}";
            }
        }

        return implode("\n", $lineas);
    }

    // ============ RESPUESTA IA GENERAL ============

    protected function respuestaIA(string $texto): string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini', // usa un modelo disponible en tu cuenta
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres Jayrita, la asistente del administrador de una librería.\n".
                                     "Respondes SIEMPRE en español, de forma breve, amigable y clara.\n".
                                     "Puedes dar consejos sobre gestión de inventario, categorías, marcas, modelos, clientes y ventas.\n".
                                     "Si el usuario te pide datos muy específicos que no te fueron proporcionados, responde de forma general sin inventar números concretos.",
                    ],
                    [
                        'role' => 'user',
                        'content' => $texto,
                    ],
                ],
            ]);

            $contenido = $response->choices[0]->message->content ?? null;

            if (! $contenido) {
                return "Lo siento, soy una IA que todavía está creciendo y en este momento no pude generar una respuesta adecuada.";
            }

            return $contenido;
        } catch (\Throwable $e) {
            return "Lo siento, soy una IA que todavía está creciendo y en este momento tuve un problema al responder. Intenta de nuevo más tarde.";
        }
    }

    public function render()
    {
        return view('livewire.admin-chatbot');
    }
}
