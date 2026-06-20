<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// LOGIN & REGISTRO
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Rutas protegidad (por los ROLES)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // LOGOUT (PARA TODOS)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- 1. SOLO PARA EL ADMIN ---
    Route::middleware('role:administrador')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
        
        // Servicios extras
        Route::get('/admin/servicios-extras', [\App\Http\Controllers\Admin\ServicioExtraController::class, 'index'])->name('composite.index');
        Route::post('/composite/toggle/{comboId}/{servicioId}', [\App\Http\Controllers\Admin\ServicioExtraController::class, 'toggleServicioEnCombo'])->name('composite.toggle');
        Route::post('/composite/servicio/store', [\App\Http\Controllers\Admin\ServicioExtraController::class, 'storeServicio'])->name('composite.servicio.store');
        Route::post('/composite/combo/store', [\App\Http\Controllers\Admin\ServicioExtraController::class, 'storeCombo'])->name('composite.combo.store');
        
        // Clases
        Route::get('/clases-view', [\App\Http\Controllers\Admin\AdminClaseController::class, 'index'])->name('admin.clases.index');
        Route::delete('/admin/clases/{id}', [\App\Http\Controllers\Admin\AdminClaseController::class, 'destroy'])->name('admin.clases.destroy');
        Route::post('/admin/clases', [\App\Http\Controllers\Admin\AdminClaseController::class, 'store'])->name('admin.clases.store');
        Route::get('/admin/clases/{id}/edit', [\App\Http\Controllers\Admin\AdminClaseController::class, 'edit'])->name('admin.clases.edit');
        Route::put('/admin/clases/{id}', [\App\Http\Controllers\Admin\AdminClaseController::class, 'update'])->name('admin.clases.update');

        // SOCIOS
        Route::get('/socios-view', [\App\Http\Controllers\Admin\SocioController::class, 'index'])->name('admin.socios.index');
        Route::post('/socios', [\App\Http\Controllers\Admin\SocioController::class, 'store'])->name('admin.socios.store');
        Route::put('/socios/{id}', [\App\Http\Controllers\Admin\SocioController::class, 'update'])->name('admin.socios.update');
        Route::delete('/socios/{id}', [\App\Http\Controllers\Admin\SocioController::class, 'destroy'])->name('admin.socios.destroy');

        // ENTRENADORES
        Route::get('/entrenadores-view', [\App\Http\Controllers\Admin\EntrenadorController::class, 'index'])->name('admin.entrenadores.index');
        Route::post('/entrenadores', [\App\Http\Controllers\Admin\EntrenadorController::class, 'store'])->name('admin.entrenadores.store');
        Route::put('/entrenadores/{id}', [\App\Http\Controllers\Admin\EntrenadorController::class, 'update'])->name('admin.entrenadores.update');
        Route::delete('/entrenadores/{id}', [\App\Http\Controllers\Admin\EntrenadorController::class, 'destroy'])->name('admin.entrenadores.destroy');
        Route::delete('/entrenadores/{id}/force', [\App\Http\Controllers\Admin\EntrenadorController::class, 'forceDelete'])->name('admin.entrenadores.forceDelete');
        
        // SEDES
        Route::get('/sedes-view', [\App\Http\Controllers\Admin\SedeController::class, 'index'])->name('admin.sedes.index');
        Route::post('/sedes', [\App\Http\Controllers\Admin\SedeController::class, 'store'])->name('admin.sedes.store');
        Route::put('/sedes/{id}', [\App\Http\Controllers\Admin\SedeController::class, 'update'])->name('admin.sedes.update');
        Route::delete('/sedes/{id}', [\App\Http\Controllers\Admin\SedeController::class, 'destroy'])->name('admin.sedes.destroy');
        Route::delete('/socios/{id}/force', [\App\Http\Controllers\Admin\SocioController::class, 'forceDelete'])->name('admin.socios.forceDelete');         
        
        // PLANES
        Route::get('/plans-view', [\App\Http\Controllers\Admin\PlanesController::class, 'index'])->name('planes.index');
        Route::post('/planes', [\App\Http\Controllers\Admin\PlanesController::class, 'store'])->name('planes.store');
        Route::put('/planes/{id}', [\App\Http\Controllers\Admin\PlanesController::class, 'update'])->name('planes.update');
    });
    });

    // --- 2. SOLO PARA EL ENTRENADOR ---
    Route::middleware('role:entrenador')->group(function () {
        Route::get('/entrenador/dashboard', [\App\Http\Controllers\Entrenador\DashboardController::class, 'index'])->name('entrenador.dashboard');
        
        // Asistencia
        Route::post('/entrenador/clases/{claseId}/socio/{socioId}/asistencia', [\App\Http\Controllers\Entrenador\DashboardController::class, 'marcarAsistencia']);
        Route::post('/entrenador/clases/{id}/finalizar', [\App\Http\Controllers\Entrenador\DashboardController::class, 'finalizarClase'])->name('entrenador.clases.finalizar');
    });


    // --- 3. SOLO PARA EL SOCIO
    Route::middleware('role:socio')->group(function () {
        Route::get('/socio/dashboard', [\App\Http\Controllers\Socio\DashboardController::class, 'index'])->name('socio.dashboard');
        Route::post('/socio/crear-perfil', [\App\Http\Controllers\Socio\DashboardController::class, 'crearPerfil']);
        Route::post('/socio/pagar/{id}', [\App\Http\Controllers\Socio\DashboardController::class, 'pagarCuota'])->name('socio.pagar');
        Route::post('/socio/contratar-extras', [\App\Http\Controllers\Socio\DashboardController::class, 'contratarExtras'])->name('socio.contratar-extras');
        Route::delete('/socio/cancelar-pago/{id}', [\App\Http\Controllers\Socio\DashboardController::class, 'cancelarPago'])->name('socio.cancelarPago');    
        
        Route::post('/clases/{id}/inscribir', [\App\Http\Controllers\Admin\AdminClaseController::class, 'inscribir']);
        Route::post('/clases/{id}/cancelar', [\App\Http\Controllers\Admin\AdminClaseController::class, 'cancelar']);
    });
    
    Route::post('/clases/{id}/inscribir', [\App\Http\Controllers\Admin\AdminClaseController::class, 'inscribir'])->middleware('role:socio,entrenador');


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::apiResource('sedes', \App\Http\Controllers\Admin\SedeController::class);
    Route::apiResource('plans', \App\Http\Controllers\Admin\PlanesController::class);
    Route::apiResource('clases', \App\Http\Controllers\Admin\AdminClaseController::class);
    Route::apiResource('socios', \App\Http\Controllers\Admin\SocioController::class);
});