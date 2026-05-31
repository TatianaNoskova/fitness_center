<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\SedeRepositoryInterface;
use App\Repositories\SedeRepository;

// Импортируем наши новые классы для паттернов
use App\Models\Clase;
use App\Observers\ClaseObserver;
use App\Services\CuotaContext;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Твой существующий репозиторий
        $this->app->bind(SedeRepositoryInterface::class, SedeRepository::class);

        // Singleton через Service Container Laravel
        $this->app->singleton(CuotaContext::class, function ($app) {
            return new CuotaContext();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Внедрение паттерна Observer (Наблюдатель за моделью Clase)
        Clase::observe(ClaseObserver::class);
    }
}