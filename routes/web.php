<?php

use Illuminate\Support\Facades\Route;

// === COMPONENTES LIVEWIRE ===
use App\Livewire\Admin\Dashboard;
use App\Livewire\CategoriaManager;
use App\Livewire\MarcasComponent;
use App\Livewire\ModeloComponent;
use App\Livewire\Admin\Productos;

use App\Livewire\Caja\AperturaCaja;
use App\Livewire\Caja\CierreDiario;
use App\Livewire\Caja\VentaPos;

// === CONTROLADORES ===
use App\Http\Controllers\VentaPdfController;



// routes/web.php
use App\Livewire\Auth\Login;

Route::get('/login', Login::class)->name('login');
// routes/web.php
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ====================================================================
// RUTAS PÚBLICAS (solo login o landing si querés)
// ====================================================================
Route::get('/', Dashboard::class)->name('dashboard');

// ====================================================================
// RUTAS DEL PUNTO DE VENTA (POS) – Solo usuarios con rol permitido
// ====================================================================
Route::middleware(['auth', 'rol:admin,cajero,vendedor'])->group(function () {
    Route::get('/caja/pos', VentaPos::class)
         ->name('caja.pos');
});

// ====================================================================
// APERTURA Y CIERRE DE CAJA – Solo admin y cajero
// ====================================================================
Route::middleware(['auth', 'rol:admin,cajero'])->group(function () {
    Route::get('/caja/apertura', AperturaCaja::class)
         ->name('caja.apertura');

    Route::get('/caja/cierre', CierreDiario::class)
         ->name('caja.cierre');
});

// ====================================================================
// GESTIÓN DE PRODUCTOS – Solo admin (o cajero si querés)
// ====================================================================
Route::middleware(['auth', 'rol:admin'])->group(function () {
    Route::get('/productos', Productos::class)->name('productos');
    Route::get('/categorias', CategoriaManager::class)->name('categorias');
    Route::get('/marcas', MarcasComponent::class)->name('marcas');
    Route::get('/modelos', ModeloComponent::class)->name('modelos');
});

// ====================================================================
// DESCARGA DE TICKETS Y PDF – Solo usuarios autenticados
// ====================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/venta/ticket/{id}', [VentaPdfController::class, 'ticket'])
         ->name('venta.ticket');

    Route::get('/venta/descargar/{id}', [VentaPdfController::class, 'descargar'])
         ->name('venta.descargar');
});