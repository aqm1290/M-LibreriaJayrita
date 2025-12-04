<?php

use Illuminate\Support\Facades\Route;

// === COMPONENTES LIVEWIRE ===
use App\Livewire\Admin\Dashboard;
use App\Livewire\CategoriaManager;
use App\Livewire\MarcasComponent;
use App\Livewire\ModeloComponent;
use App\Livewire\Inventario\CreateEntradaInventario;
use App\Livewire\Inventario\IndexEntradaInventario;
use App\Livewire\Inventario\EditEntradaInventario;

use App\Livewire\Admin\Productos;
use App\Livewire\Admin\Proveedores;
use App\Livewire\Admin\Promociones;
use App\Livewire\Admin\CrearPersonal;

use App\Livewire\Caja\AperturaCaja;
use App\Livewire\Caja\CierreDiario;
use App\Livewire\Caja\VentaPos;
use App\Livewire\Caja\BuscadorProductos;
use App\Livewire\Caja\HistorialPdfs;


use App\Livewire\Auth\Login;

// === CONTROLADORES ===
use App\Http\Controllers\VentaPdfController;
use App\Http\Controllers\CierrePdfController;  // ← NUEVO




use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;



Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.home');
// Ruta para cargar el producto vía AJAX
Route::get('/producto/{id}', [TiendaController::class, 'showAjax'])
     ->name('producto.ajax');
     // Ruta para ver el producto completo

Route::get('/tienda/productos/{producto}', [ProductoController::class, 'show'])
    ->name('tienda.producto-show');


Route::get('/marcas/{marca}', [MarcaController::class, 'show'])
    ->name('marcas.show');

    // En routes/web.php



// ====================================================================
// LOGIN
// ====================================================================
Route::get('/login', Login::class)->name('login');

// ====================================================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN
// ====================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ====================================================================
    // POS - REQUIERE CAJA ABIERTA
    // ====================================================================
    Route::middleware('caja.abierta')->group(function () {
        Route::get('/caja/pos', VentaPos::class)->name('caja.pos');
    });

    // ====================================================================
    // APERTURA Y CIERRE DE CAJA (solo admin y cajero)
    // ====================================================================
    Route::middleware('rol:admin,cajero')->group(function () {
        Route::get('/caja/apertura', AperturaCaja::class)->name('caja.apertura');
        Route::get('/caja/cierre', CierreDiario::class)->name('caja.cierre');
        Route::get('/caja/buscar', BuscadorProductos::class)->name('caja.buscar');
        Route::get('/historial-pdfs', HistorialPdfs::class)->name('historial.pdfs');
    });

    // ====================================================================
    // INVENTARIO (admin y cajero)
    // ====================================================================
    Route::middleware('rol:admin,cajero')->group(function () {
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
    });

    // ====================================================================
    // PDFS: TICKETS DE VENTA
    // ====================================================================
    Route::get('/venta/ticket/{id}', [VentaPdfController::class, 'ticket'])
        ->name('venta.ticket');
    Route::get('/venta/descargar/{id}', [VentaPdfController::class, 'descargar'])
        ->name('venta.descargar');

    // ====================================================================
    // PDFS: CIERRE DE TURNO (igual que ticket, pero para cierre)
    // ====================================================================
    Route::get('/cierre/{id}/pdf', [CierrePdfController::class, 'generar'])
        ->name('cierre.pdf')
        ->middleware('rol:admin,cajero'); // opcional: solo cajeros y admin

    Route::get('/cierre/{id}/descargar', [CierrePdfController::class, 'descargar'])
        ->name('cierre.descargar')
        ->middleware('rol:admin,cajero');
});

