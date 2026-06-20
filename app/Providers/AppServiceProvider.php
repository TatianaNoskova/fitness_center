<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Repositories\SedeRepositoryInterface;
use App\Repositories\SedeRepository;
use App\Models\Clase;
use App\Observers\ClaseObserver;
use App\Services\CuotaContext;
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

        // Navbar
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $menu = [
                ['route' => 'admin.dashboard',     'icon' => 'bi-speedometer2',   'label' => 'Dashboard'],
                ['route' => 'admin.sedes.index',    'icon' => 'bi-building',       'label' => 'Sedes'],
                ['route' => 'admin.socios.index',   'icon' => 'bi-people',         'label' => 'Socios'],
                ['route' => 'admin.entrenadores.index',   'icon' => 'bi-people',         'label' => 'Entrenadores'],
                ['route' => 'planes.index',         'icon' => 'bi-card-checklist', 'label' => 'Planes'],
                ['route' => 'admin.clases.index',   'icon' => 'bi-calendar3',      'label' => 'Clases'],
                ['route' => 'composite.index',      'icon' => 'bi-diagram-3',      'label' => 'Combos'],
            ];

        $view->with('adminNavbarItems', $menu);
    });


        
    }
}