<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DetalleEntrada;
use App\Observers\DetalleEntradaObserver;

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
        
    }
}
