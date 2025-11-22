<?php

use Illuminate\Support\Facades\Route;

// Componentes Livewire correctos (namespace App\Livewire)
use App\Livewire\Admin\Dashboard; 
use App\Livewire\CategoriaManager;
use App\Livewire\MarcasComponent;
use App\Livewire\ModeloComponent;
use App\Livewire\Admin\Productos;
use App\Livewire\Caja\VentaPos;                    // ← Esta línea la agregas

use App\Http\Controllers\VentaPdfController;

Route::get('/venta/pdf/{id}', [VentaPdfController::class, 'ticket'])->name('venta.ticket');

Route::get('/caja/pos', VentaPos::class)->name('caja.pos');

Route::get('/productos', Productos::class)->name('productos');
Route::get('/marcas', MarcasComponent::class)->name('marcas');
Route::get('/modelos', ModeloComponent::class)->name('modelos');
Route::get('/categorias', CategoriaManager::class)->name('categorias');
Route::get('/', Dashboard::class)->name('dashboard');