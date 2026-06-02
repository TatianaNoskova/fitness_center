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
        /* Твой существующий репозиторий
        $this->app->bind(SedeRepositoryInterface::class, SedeRepository::class); */

        // Твой Singleton через Service Container (Паттерн Стратегия)
        $this->app->singleton(CuotaContext::class, function ($app) {
            $return = new CuotaContext();
            return $return; // Исправил синтаксис для стабильности контейнера
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Твой рабочий паттерн Observer (Наблюдатель за моделью Clase)
        Clase::observe(ClaseObserver::class);

        // 2. Внедрение паттерна Composite (Автоматическая сборка для шаблона)
        View::composer('admin.servicios_extras', function ($view) {
            // Получаем ID выбранного комбо из параметров текущего URL-запроса
            $currentComboId = request()->route('comboId') ?? request()->input('combo_id');

            // Ищем конкретное комбо по ID, либо берем самое первое как дефолтное
            if ($currentComboId) {
                $comboModel = Combo::with('servicios')->find($currentComboId);
            } else {
                $comboModel = Combo::with('servicios')->first();
            }

            // Если в базе вообще нет комбо, создаем одно тестовое для безопасности
            if (!$comboModel) {
                $comboModel = Combo::create(['nombre' => 'Combo Premium «Recuperación Total»']);
            }

            // Инициализируем паттерн Composite именем конкретного комбо
            $comboServicios = new ClaseComposite($comboModel->nombre);

            // Наполняем Композит "Листьями" (Leafs) именно этой модели
            $serviciosEnComboIds = [];
            if ($comboModel->servicios) {
                foreach ($comboModel->servicios as $servicioModel) {
                    $comboServicios->agregar(new ClaseLeaf($servicioModel));
                    $serviciosEnComboIds[] = $servicioModel->id;
                }
            }

            // Передаем переменные паттерна строго в этот шаблон
            $view->with([
                'comboServicios' => $comboServicios,
                'todosLosServicios' => Servicio::all(),
                'serviciosEnComboIds' => $serviciosEnComboIds,
                'comboModel' => $comboModel,
                'todosLosCombos' => Combo::all() // Передаем список всех комбо для селектора
            ]);
        });
    }
}