<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ClaseController;

/*
|--------------------------------------------------------------------------
| Web Routes (Интерфейс Blade на Tailwind)
|--------------------------------------------------------------------------
*/

// Теперь при входе на сайт сразу откроется наш стильный дашборд
Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// Роут для страницы филиалов
Route::get('/sedes-view', function () {
    $sedes = \App\Models\Sede::with(['socios'])->get();
    return view('sedes', compact('sedes'));
});

// Роут для страницы клиентов
Route::get('/socios-view', function () {
    $socios = \App\Models\Socio::with(['user', 'sede'])->get();
    return view('socios', compact('socios'));
});

// Роут для страницы тарифов
Route::get('/plans-view', function () {
    $planes = \App\Models\Plan::all();
    return view('planes', compact('planes'));
});


/*
|--------------------------------------------------------------------------
| API Routes (Твоя рабочая бэкенд-группа)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    
    // Автоматические CRUD маршруты для Филиалов (Sede)
    Route::apiResource('sedes', SedeController::class);
    
    // Автоматические CRUD маршруты для Клиентов (Socio)
    Route::apiResource('socios', SocioController::class);
    
    // Автоматические CRUD маршруты для Тарифов/Абонементов (Plan)
    Route::apiResource('plans', PlanController::class);

    // Автоматические CRUD маршруты для Занятий (Clase)
    Route::apiResource('clases', ClaseController::class);
    
});