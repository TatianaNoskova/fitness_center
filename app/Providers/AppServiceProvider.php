<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

// Твои существующие импорты
use App\Repositories\SedeRepositoryInterface;
use App\Repositories\SedeRepository;
use App\Models\Clase;
use App\Observers\ClaseObserver;
use App\Services\CuotaContext;

// Новые импорты для паттерна Composite
use App\Models\Combo;
use App\Models\Servicio;
use App\Patterns\Composite\ClaseComposite;
use App\Patterns\Composite\ClaseLeaf;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /* Repositorio
        $this->app->bind(SedeRepositoryInterface::class, SedeRepository::class); */

        //  Singleton via Service Container (El parton Estrategia)
        $this->app->singleton(CuotaContext::class, function ($app) {
            $return = new CuotaContext();
            return $return; 
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Observer (Observe el modelo Clase)
        Clase::observe(ClaseObserver::class);


        
    }
}