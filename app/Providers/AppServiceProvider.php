<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DetalleEntrada;
use App\Observers\DetalleEntradaObserver;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Modelo;
use App\Observers\MarcaObserver;
use App\Observers\CategoriaObserver;
use App\Observers\ModeloObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DetalleEntrada::observe(DetalleEntradaObserver::class);
        Marca::observe(MarcaObserver::class);
        Categoria::observe(CategoriaObserver::class);
        Modelo::observe(ModeloObserver::class);   
        
    }
}
