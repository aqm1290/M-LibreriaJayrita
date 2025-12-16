<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// === MIDDLEWARES PERSONALIZADOS ===
use App\Http\Middleware\CheckCajeroActivo;

// === COMPONENTES LIVEWIRE: ADMIN ===
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Productos;
use App\Livewire\Admin\Proveedores;
use App\Livewire\Admin\Promociones;
use App\Livewire\Admin\CrearPersonal;
use App\Livewire\Admin\PedidosWeb;

// === COMPONENTES LIVEWIRE: INVENTARIO ===
use App\Livewire\Inventario\CreateEntradaInventario;
use App\Livewire\Inventario\IndexEntradaInventario;
use App\Livewire\Inventario\EditEntradaInventario;

// === COMPONENTES LIVEWIRE: CAJA ===
use App\Livewire\Caja\AperturaCaja;
use App\Livewire\Caja\CierreDiario;
use App\Livewire\Caja\VentaPos;
use App\Livewire\Caja\BuscadorProductos;
use App\Livewire\Caja\HistorialPdfs;

// === COMPONENTES LIVEWIRE: AUTENTICACIÓN ADMIN ===
use App\Livewire\Auth\Login;

// === COMPONENTES LIVEWIRE: TIENDA / CLIENTE ===
use App\Livewire\Tienda\HomeProductos;
use App\Livewire\Tienda\PedidoActual;
use App\Livewire\Tienda\VerPedido;
use App\Livewire\Auth\ClienteLogin;
use App\Livewire\Auth\ClienteRegister;
use App\Livewire\Tienda\CatalogoMarca;
use App\Livewire\Tienda\ListaMarcas;
use App\Livewire\Tienda\CatalogoProductos;

// === OTROS COMPONENTES LIVEWIRE ===
use App\Livewire\CategoriaManager;
use App\Livewire\MarcasComponent;
use App\Livewire\ModeloComponent;
use App\Livewire\ProductosVendidosTable;

// === CONTROLADORES ===
use App\Http\Controllers\VentaPdfController;
use App\Http\Controllers\CierrePdfController;
use App\Http\Controllers\TicketController;

// ====================================================================
// TIENDA PÚBLICA
// ====================================================================

Route::get('/tienda', HomeProductos::class)->name('tienda.home');
Route::get('/pedido', VerPedido::class)->name('tienda.pedido');
Route::get('/catalogo', CatalogoProductos::class)->name('tienda.catalogo');

Route::get('/tienda/marcas', ListaMarcas::class)->name('tienda.marcas');
Route::get('/tienda/marca/{marca}', CatalogoMarca::class)->name('tienda.marca');

// Perfil cliente (tienda)
Route::get('/mi-perfil', \App\Livewire\Cliente\Perfil::class)
    ->name('cliente.perfil')
    ->middleware('auth:cliente');

Route::post('/cliente/logout', function () {
    Auth::guard('cliente')->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('tienda.home');
})->middleware('auth:cliente')->name('cliente.logout');

// ====================================================================
// AUTENTICACIÓN CLIENTE (TIENDA WEB)
// ====================================================================

// Solo invitados (no logueados como cliente)
Route::get('/cliente/login', ClienteLogin::class)
    ->middleware('guest:cliente')
    ->name('cliente.login');

Route::get('/cliente/registro', ClienteRegister::class)
    ->middleware('guest:cliente')
    ->name('cliente.register');

// Zona cliente autenticada
Route::middleware('auth:cliente')->group(function () {
    Route::get('/cliente/pedido', PedidoActual::class)->name('pedido.cliente');
});

// ====================================================================
// LOGIN ADMIN
// ====================================================================

Route::get('/login', Login::class)->name('login');

// ====================================================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN ADMIN (guard web)
// ====================================================================

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ====================================================================
    // POS - REQUIERE CAJA ABIERTA (solo cajero activo)
    // ====================================================================
    Route::middleware(['rol:cajero', 'caja.abierta', 'cajero.activo'])->group(function () {
        Route::get('/caja/pos', VentaPos::class)->name('caja.pos');
    });

    // ====================================================================
    // APERTURA Y CIERRE DE CAJA (admin y cajero activo)
    // ====================================================================
    Route::middleware(['rol:admin,cajero', 'cajero.activo'])->group(function () {
        Route::get('/caja/apertura', AperturaCaja::class)->name('caja.apertura');
        Route::get('/caja/cierre', CierreDiario::class)->name('caja.cierre');
        Route::get('/caja/buscar', BuscadorProductos::class)->name('caja.buscar');
        Route::get('/historial-pdfs', HistorialPdfs::class)->name('historial.pdfs');
    });

    // ====================================================================
    // INVENTARIO (admin y cajero activo)
    // ====================================================================
    Route::middleware(['rol:admin,cajero', 'cajero.activo'])->group(function () {
        Route::get('/entrada-inventario', CreateEntradaInventario::class)->name('entrada-inventario');
        Route::get('/entradas', IndexEntradaInventario::class)->name('entradas.index');
        Route::get('/entradas/{id}/edit', EditEntradaInventario::class)->name('entradas.edit');
    });

    // ====================================================================
    // CATÁLOGOS – SOLO ADMIN
    // ====================================================================
    Route::middleware('rol:admin')->group(function () {
        Route::get('/productos', Productos::class)->name('productos');
        Route::get('/categorias', CategoriaManager::class)->name('categorias');
        Route::get('/marcas', MarcasComponent::class)->name('marcas');
        Route::get('/modelos', ModeloComponent::class)->name('modelos');
        Route::get('/proveedores', Proveedores::class)->name('proveedores');
        Route::get('/admin/promociones', Promociones::class)->name('admin.promociones');
        Route::get('/admin/crear-personal', CrearPersonal::class)->name('admin.crear-personal');
        Route::get('/admin/pedidos-web', PedidosWeb::class)->name('admin.pedidos-web');

        Route::get('/productos-vendidos', ProductosVendidosTable::class)
            ->name('productos.vendidos');
    });

    // ====================================================================
    // PDFS: TICKETS DE VENTA
    // ====================================================================
    Route::get('/venta/ticket/{id}', [VentaPdfController::class, 'ticket'])
        ->name('venta.ticket');

    Route::get('/venta/descargar/{id}', [VentaPdfController::class, 'descargar'])
        ->name('venta.descargar');

    Route::get('/ticket/web/{venta}', [TicketController::class, 'imprimirWeb'])
        ->name('ticket.web');

    // ====================================================================
    // PDFS: CIERRE DE TURNO (admin y cajero activo)
    // ====================================================================
    Route::middleware(['rol:admin,cajero', 'cajero.activo'])->group(function () {
        Route::get('/cierre/{id}/pdf', [CierrePdfController::class, 'generar'])
            ->name('cierre.pdf');

        Route::get('/cierre/{id}/descargar', [CierrePdfController::class, 'descargar'])
            ->name('cierre.descargar');
    });
});

// ====================================================================
// CAMPANITA - PEDIDOS RESERVADOS (FUNCIONA EN CUALQUIER LUGAR)
// ====================================================================

Route::get('/campanita-pedidos', function () {
    $pedidos = \App\Models\Pedido::where('estado', 'reservado')
        ->select('id', 'cliente_nombre', 'total', 'expira_en', 'created_at')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($p) {
            $horas = now()->diffInHours($p->expira_en, false);
            $minutos = now()->diffInMinutes($p->expira_en, false) % 60;
            $tiempo = $horas > 0 ? "$horas h $minutos m" : "Vencido";

            return [
                'id' => $p->id,
                'cliente_nombre'   => $p->cliente_nombre ?? 'Sin nombre',
                'total'            => number_format($p->total, 2),
                'created_at'       => $p->created_at->format('d/m H:i'),
                'tiempo_restante'  => $tiempo,
            ];
        });

    return response()->json([
        'count'   => $pedidos->count(),
        'pedidos' => $pedidos,
    ]);
});
