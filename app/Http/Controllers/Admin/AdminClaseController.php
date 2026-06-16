<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Clase;
use App\Models\Sede;
use App\Models\Entrenador; 
use App\Models\User;

class AdminClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 1. Показ списка занятий и формы создания
public function index(Request $request)
{
    $clases = Clase::with(['sede', 'entrenador.user'])->orderBy('fecha')->orderBy('hora')->get();
    $sedes = Sede::all();
    
    // 1. Для модалок редактирования получаем вообще всех активных тренеров
    $allEntrenadores = Entrenador::with('user')->where('estado', 'ACTIVO')->get();

    // 2. Получаем ID выбранного филиала из шага 1 (верхний селект)
    $selectedSedeId = $request->get('selected_sede_id');
    
    // 3. Фильтруем тренеров СТРОГО для формы создания нового класса.
    // Приводим типы к инту, чтобы фильтрация сработала на 100% корректно.
    $entrenadores = $selectedSedeId 
        ? $allEntrenadores->filter(function($entrenador) use ($selectedSedeId) {
            return (int) $entrenador->sede_id === (int) $selectedSedeId;
        })->values()
        : collect();

    // Передаем в Blade обе переменные отдельно
    return view('admin.clases', [
        'clases' => $clases,
        'sedes' => $sedes,
        'entrenadores' => $entrenadores,       // Чистые тренеры для формы создания
        'allEntrenadores' => $allEntrenadores, // ИСПРАВЛЕНО: маленькая буква 'a' и 'e'! Полный список для модалок
        'selectedSedeId' => $selectedSedeId
    ]);
}


    public function store(Request $request)
{
    $data = $request->validate([
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'fecha' => 'required|date',
        'hora' => 'required',
        'capacidad' => 'required|integer|min:1',
        'entrenador_id' => 'required|exists:entrenadors,user_id',
        'sede_id' => 'required|exists:sedes,id', // Валидируем пришедшую из скрытого поля Sede
    ]);

    Clase::crearClase($data);

    return redirect()->route('admin.clases.index', ['selected_sede_id' => $request->sede_id])
        ->with('success', '¡Clase creada con éxito en la sede seleccionada!');
}

    // Показ формы редактирования
public function edit($id)
{
    $clase = Clase::findOrFail($id);
    $sedes = Sede::all();
    
    // Показываем тренеров именно того филиала, к которому привязано занятие
    $entrenadores = Entrenador::with('user')
        ->where('sede_id', $clase->sede_id)
        ->where('estado', 'ACTIVO')
        ->get();

    return view('admin.clases_edit', compact('clase', 'sedes', 'entrenadores'));
}

// Обработка сохранения изменений
public function update(Request $request, $id)
{
    $clase = Clase::findOrFail($id);

    $inscritosEnInscripciones = $clase->inscripciones()->count();
    $inscritosEnSocios = $clase->socios()->count();
    
    $tieneInscritos = ($inscritosEnInscripciones > 0 || $inscritosEnSocios > 0);
    $totalInscritos = max($inscritosEnInscripciones, $inscritosEnSocios);

    // 1. Настройка правил валидации
    $rules = [
        'descripcion' => 'nullable|string',
        'capacidad' => 'required|integer|min:' . max(1, $totalInscritos),
    ];

    // Валидируем остальные поля только если их вообще передали в форме и нет учеников
    if (!$tieneInscritos) {
        $rules['nombre'] = 'required|string|max:100';
        $rules['entrenador_id'] = 'required|exists:entrenadors,user_id';
        
        if ($request->has('fecha')) $rules['fecha'] = 'required|date';
        if ($request->has('hora')) $rules['hora'] = 'required|string';
    }

    $request->validate($rules);

    // 2. Формируем финальный массив
    if ($tieneInscritos) {
        $finalData = [
            'nombre'        => $clase->nombre,
            'fecha'         => $clase->fecha,
            'hora'          => $clase->hora,
            'entrenador_id' => $clase->entrenador_id,
            'sede_id'       => $clase->sede_id,
            'descripcion'   => $request->input('descripcion'),
            'capacidad'     => $request->input('capacidad'),
        ];
    } else {
        // Защита «на дурака»: если дата/время не пришли из формы, оставляем те, что были в БД
        $finalData = [
            'nombre'        => $request->input('nombre'),
            'fecha'         => $request->input('fecha', $clase->fecha),
            'hora'          => $request->input('hora', $clase->hora),
            'entrenador_id' => $request->input('entrenador_id'),
            'descripcion'   => $request->input('descripcion'),
            'capacidad'     => $request->input('capacidad'),
        ];
        
        $entrenador = \App\Models\Entrenador::where('user_id', $request->input('entrenador_id'))->firstOrFail();
        $finalData['sede_id'] = $entrenador->sede_id;
    }

    // 3. Сохраняем стандартным и надёжным методом Laravel Eloquent
    $clase->update($finalData);

    $msg = $tieneInscritos 
        ? '¡Los ajustes de capacidad y descripción se guardaron de forma segura!' 
        : '¡La clase ha sido modificada con éxito!';

    $redirectSedeId = $request->input('selected_sede_id') ?? $clase->sede_id;

    return redirect()->route('admin.clases.index', ['selected_sede_id' => $redirectSedeId])
        ->with('success', $msg);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Находим класс по ID
        $clase = \App\Models\Clase::findOrFail($id);
        
        // 2. Удаляем его через Eloquent (это запускает ClaseObserver)
        $clase->delete();

        // 3. ЖЕСТКО перенаправляем админа обратно на страницу со списком занятий
        // Используем роут 'admin.dashboard', который мы привязали к '/clases-view'
        return redirect()->route('admin.clases.index')
        ->with('success', '¡La clase ha sido eliminada y el sistema ha notificado a los socios!');
    }

    public function inscribir(string $id)
    {
        try {
            $clase = \App\Models\Clase::findOrFail($id);
            
            // Ищем профиль текущего авторизованного клиента
            $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
            
            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            // Вызываем бизнес-метод (проверяет дубликаты и вместимость)
            $socio->inscribirseAClase($clase);

            // Уменьшаем количество доступных мест на 1
            $clase->decrement('capacidad');

            return redirect()->back()->with('success', "¡Inscripción exitosa! Te has inscrito en {$clase->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Отмена записи клиентом (Перенесено из ClaseController)
     */
    public function cancelar(string $id)
    {
        try {
            $clase = \App\Models\Clase::findOrFail($id);
            
            // Ищем профиль текущего клиента
            $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
            
            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            // Вызываем метод отмены в модели
            $socio->cancelarInscripcion($clase);

            // Возвращаем свободное место занятию
            $clase->increment('capacidad');

            return redirect()->back()->with('success', "Has cancelado tu inscripción de: {$clase->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
