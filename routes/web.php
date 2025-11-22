<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\Dashboard; 
use App\Livewire\CategoriaManager;
use App\Livewire\MarcasComponent;
use App\Livewire\ModeloComponent;
use App\Livewire\Admin\Productos;
use App\Livewire\Caja\VentaPos;                   

use App\Http\Controllers\VentaPdfController;


Route::get('/caja/apertura', \App\Livewire\Caja\AperturaCaja::class)->name('caja.apertura');
Route::get('/caja/cierre', \App\Livewire\Caja\CierreDiario::class)->name('caja.cierre');
Route::get('/venta/ticket/{id}', [VentaPdfController::class, 'ticket'])->name('venta.ticket');
Route::get('/venta/descargar/{id}', [VentaPdfController::class, 'descargar'])->name('venta.descargar');

Route::get('/caja/pos', VentaPos::class)->name('caja.pos');

Route::get('/productos', Productos::class)->name('productos');
Route::get('/marcas', MarcasComponent::class)->name('marcas');
Route::get('/modelos', ModeloComponent::class)->name('modelos');
Route::get('/categorias', CategoriaManager::class)->name('categorias');
Route::get('/', Dashboard::class)->name('dashboard');