<?php

namespace App\Http\Controllers\Socio;

use App\Http\Controllers\Controller;
use App\Models\Socio;
use App\Models\Clase;
use App\Models\Sede; 
use App\Models\Plan; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ищем профиль клиента по user_id
        $socio = Socio::with(['sede', 'plan'])
            ->where('user_id', auth()->id())
            ->first();

        // Инициализируем переменные по умолчанию
        $clasesDisponibles = collect();
        $misInscripciones = collect();
        $precioCuota = 0;
        $historialPagos = collect(); 
        $todosLosPlanes = collect();
        $todasLasSedes = collect();

        // 2. РАЗВЕТВЛЕНИЕ ЛОГИКИ
        if ($socio) {
            // СЦЕНАРИЙ А: Профиль существует (Стандартный дашборд)
            $clasesDisponibles = Clase::with('sede')
                ->where('fecha', '>=', now()->toDateString())
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            $misInscripciones = $socio->clases;

            if ($socio->plan) {
                $precioCuota = $socio->obtenerPrecioCuota(); 

                // Получаем ВСЕ платежи этого пользователя из базы через модель Pago
                $historialPagos = \App\Models\Pago::where('socio_id', $socio->user_id)
                    ->orderBy('id', 'desc')
                    ->get();

                // Если у пользователя вообще нет платежей, автоматически создаем первый (PENDIENTE)
                if ($historialPagos->isEmpty()) {
                    $primerPago = \App\Models\Pago::create([
                        'fecha'       => now()->toDateString(),
                        'monto'       => $precioCuota,
                        'metodo_pago' => 'Efectivo/Tarjeta', 
                        'estado'      => 'PENDIENTE',       
                        'socio_id'    => $socio->user_id,
                        'plan_id'     => $socio->plan_id,
                    ]);

                    // Помещаем созданный платеж в коллекцию для отображения
                    $historialPagos = collect([$primerPago]);
                }
            }
        } else {
            // СЦЕНАРИЙ Б: Профиля нет (Пользователь только зарегистрировался)
            $todasLasSedes = Sede::all();
            $todosLosPlanes = Plan::all();
        }

        return view('socio.dashboard', compact(
            'socio',
            'clasesDisponibles',
            'misInscripciones',
            'precioCuota',
            'historialPagos',
            'todosLosPlanes',
            'todasLasSedes'
        ));
    }

    // Создание профиля из формы онбординга
    public function crearPerfil(Request $request)
    {
        $request->validate([
            'sede_id'   => 'required',
            'plan_id'   => 'required|exists:plans,id',
            'categoria' => 'required|in:NORMAL,ESTUDIANTE,VIP', 
        ]);

        Socio::create([
            'user_id'   => auth()->id(),
            'sede_id'   => $request->sede_id,
            'plan_id'   => $request->plan_id,
            'categoria' => $request->categoria, 
            'estado'    => 'ACTIVO', 
            'fecha_alta'=> now()->toDateString(),
        ]);

        return redirect()->route('socio.dashboard')->with('success', '¡Tu perfil ha sido activado! Bienvenido a WorldClass.');
    }

    // МЕТОД ОПЛАТЫ: Выполняет валидацию и проведение платежа
    public function pagarCuota($id)
    {
        $pago = \App\Models\Pago::findOrFail($id);
        $exito = $pago->validarPago();

        if ($exito) {
            return redirect()->route('socio.dashboard')
                ->with('success', '¡Pago realizado con éxito! Tu cuota ha sido validada.');
        }

        return redirect()->route('socio.dashboard')
            ->with('error', 'No se pudo validar el pago. Verifique los datos.');
    }

    // --- НАШИ НОВЫЕ МЕТОДЫ, КОТОРЫХ НЕ ХВАТАЛО ---

    /**
     * Запись Мартина на занятие (использует логику из твоей модели)
     */
    public function inscribir($id)
    {
        try {
            $clase = Clase::findOrFail($id);
            $socio = Socio::where('user_id', auth()->id())->first();

            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            // Вызываем бизнес-метод твоей модели!
            $socio->inscribirseAClase($clase);

            return redirect()->route('socio.dashboard')->with('success', '¡Te has inscrito en la clase con éxito!');
            
        } catch (\Exception $e) {
            // Перехватываем ошибки валидации из модели (места кончились, подписка не активна и т.д.)
            return redirect()->route('socio.dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Отмена записи Мартина на занятие (использует логику из твоей модели)
     */
    public function cancelar($id)
    {
        try {
            $clase = Clase::findOrFail($id);
            $socio = Socio::where('user_id', auth()->id())->first();

            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            // Вызываем второй бизнес-метод твоей модели!
            $socio->cancelarInscripcion($clase);

            return redirect()->route('socio.dashboard')->with('success', 'Inscripción cancelada con éxito.');
            
        } catch (\Exception $e) {
            return redirect()->route('socio.dashboard')->with('error', $e->getMessage());
        }
    }
}