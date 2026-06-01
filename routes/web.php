<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClaseController;



/*
|--------------------------------------------------------------------------
| Web Routes (Визуальный интерфейс Blade)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');


// Гости видят формы входа и регистрации
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Защищенные маршруты (С жестким разделением по ролям)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Выйти могут все авторизованные пользователи
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- 1. ДОСТУП ТОЛЬКО ДЛЯ АДМИНИСТРАТОРА ---
    Route::middleware('role:administrador')->group(function () {
        // Главная панель админа (пока оставляем Blade напрямую, если под неё нет сложной логики)
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
        
        // Вьюшки для админки (Они пока берут данные напрямую, их можно будет при желании тоже причесать)
        Route::get('/sedes-view', function () { return view('admin.sedes', ['sedes' => \App\Models\Sede::with(['socios'])->get()]); });
        Route::get('/plans-view', [PlanController::class, 'index'])->name('plans.index');
        Route::post('/plans-view/toggle/{servicioId}', [PlanController::class, 'toggleServicioEnCombo'])->name('composite.toggle');
        
        // Открытие списка занятий админом
        Route::get('/clases-view', [\App\Http\Controllers\Admin\AdminClaseController::class, 'index'])->name('admin.clases.index');

        // Удаление занятия админом
        Route::delete('/admin/clases/{id}', [\App\Http\Controllers\Admin\AdminClaseController::class, 'destroy'])->name('admin.clases.destroy');
        
        // А вот список клиентов админа теперь берется через наш новый Admin\SocioController, если захочешь сделать там Blade-вывод
        Route::get('/socios-view', function () { return view('admin.socios', ['socios' => \App\Models\Socio::with(['user', 'sede'])->get()]); });
    });

    // --- 2. ДОСТУП ТОЛЬКО ДЛЯ ТРЕНЕРА ---
    Route::middleware('role:entrenador')->group(function () {
        // На следующем шаге создадим этот метод в Entrenador\DashboardController
        Route::get('/entrenador/dashboard', [App\Http\Controllers\Entrenador\DashboardController::class, 'index'])->name('entrenador.dashboard');
    });

    // --- 3. ДОСТУП ТОЛЬКО ДЛЯ КЛИЕНТА (SOCIO) ---
    Route::middleware('role:socio')->group(function () {
        // Наш новенький изолированный контроллер для Мартина!
        Route::get('/socio/dashboard', [App\Http\Controllers\Socio\DashboardController::class, 'index'])->name('socio.dashboard');
        // НОВАЯ СТРОЧКА: Создание профиля при первом входе
        Route::post('/socio/crear-perfil', [App\Http\Controllers\Socio\DashboardController::class, 'crearPerfil']);
        // Наши прошлые роуты записи и отмены
    Route::post('/clases/{id}/inscribir', [App\Http\Controllers\ClaseController::class, 'inscribir']);
    Route::post('/clases/{id}/cancelar', [App\Http\Controllers\ClaseController::class, 'cancelar']);
    Route::post('/socio/pagar/{id}', [\App\Http\Controllers\Socio\DashboardController::class, 'pagarCuota'])->name('socio.pagar');
    
    });

    // --- ОБЩАЯ БИЗНЕС-ЛОГИКА ---
    Route::post('/clases/{id}/inscribir', [ClaseController::class, 'inscribir'])->middleware('role:socio,entrenador');
});


/*
|--------------------------------------------------------------------------
| API Routes (Бэкенд CRUD для Администратора)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::apiResource('sedes', SedeController::class);
    Route::apiResource('plans', PlanController::class);
    Route::apiResource('clases', ClaseController::class);
    
    // ВАЖНО: Указываем путь к новому админскому SocioController
    Route::apiResource('socios', App\Http\Controllers\Admin\SocioController::class);
});

