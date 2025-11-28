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

use App\Livewire\Auth\Login;

// === CONTROLADORES ===
use App\Http\Controllers\VentaPdfController;

// ====================================================================
// LOGIN Y LOGOUT
// ====================================================================
Route::get('/login', Login::class)->name('login');





// ====================================================================
// RUTAS QUE REQUIEREN CAJA ABIERTA (POS + VENTAS)
// ====================================================================
Route::middleware(['auth', 'caja.abierta'])->group(function () {
    Route::get('/caja/pos', VentaPos::class)->name('caja.pos');
});

// ====================================================================
// APERTURA Y CIERRE DE CAJA
// ====================================================================
Route::middleware(['auth', 'rol:admin,cajero'])->group(function () {
    Route::get('/caja/apertura', AperturaCaja::class)->name('caja.apertura');
    Route::get('/caja/cierre', CierreDiario::class)->name('caja.cierre');
    Route::get('/caja/buscar', \App\Livewire\Caja\BuscadorProductos::class)
        ->name('caja.buscar');
});

// ====================================================================
// DASHBOARD Y ÁREAS ADMINISTRATIVAS
// ====================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // === INVENTARIO (admin y cajero) ===
    Route::middleware('rol:admin,cajero')->group(function () {
        Route::get('/entrada-inventario', CreateEntradaInventario::class)
            ->name('entrada-inventario');
        Route::get('/entradas', IndexEntradaInventario::class)
            ->name('entradas.index');
        Route::get('/entradas/{id}/edit', EditEntradaInventario::class)
            ->name('entradas.edit');
    });

    // === CATÁLOGOS – Solo admin ===
    Route::middleware('rol:admin')->group(function () {
        Route::get('/productos', Productos::class)->name('productos');
        Route::get('/categorias', CategoriaManager::class)->name('categorias');
        Route::get('/marcas', MarcasComponent::class)->name('marcas');
        Route::get('/modelos', ModeloComponent::class)->name('modelos');
        Route::get('/proveedores', Proveedores::class)->name('proveedores');
        Route::get('/admin/promociones', Promociones::class)->name('admin.promociones');
        Route::get('/admin/crear-personal', CrearPersonal::class)->name('admin.crear-personal');
    });

    // === DESCARGA DE TICKETS Y PDF ===
    Route::get('/venta/ticket/{id}', [VentaPdfController::class, 'ticket'])
        ->name('venta.ticket');
    Route::get('/venta/descargar/{id}', [VentaPdfController::class, 'descargar'])
        ->name('venta.descargar');
});
