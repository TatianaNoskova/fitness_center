<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Подгружаем связи (филиал и тренера), чтобы ответ был информативным
        $clases = Clase::with(['sede', 'entrenador'])->get();
        return response()->json($clases, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'capacidad' => 'required|integer|min:1',
            'sede_id' => 'required|exists:sedes,id',
            'entrenador_id' => 'required|exists:users,id',
        ]);

        // Используем твой метод из модели UML
        $clase = Clase::crearClase($validated);

        return response()->json([
            'message' => 'Clase creada con éxito',
            'data' => $clase->load(['sede', 'entrenador'])
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clase = Clase::with(['sede', 'entrenador'])->find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($clase, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|date',
            'hora' => 'sometimes',
            'capacidad' => 'sometimes|integer|min:1',
            'sede_id' => 'sometimes|exists:sedes,id',
            'entrenador_id' => 'sometimes|exists:users,id',
        ]);

        // Используем твой метод обновления из модели UML
        $clase->actualizarClase($validated);

        return response()->json([
            'message' => 'Clase actualizada con éxito',
            'data' => $clase->load(['sede', 'entrenador'])
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Используем метод отмены (удаления) из модели UML
        $clase->cancelarClase();

        return response()->json(['message' => 'Clase eliminada con éxito'], Response::HTTP_OK);
    }

    /**
     * Симуляция записи клиента на занятие
     */
    /**
     * Симуляция записи клиента на занятие
     */
    public function inscribir(string $id)
    {
        try {
            $clase = Clase::findOrFail($id);
            
            // Ищем текущего авторизованного клиента
            $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
            
            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            // Вызываем бизнес-метод (он внутри себя проверит дубликаты и вместимость)
            $socio->inscribirseAClase($clase);

            // Ура! Запись прошла успешно. 
            // ВАЖНО: Уменьшаем количество доступных мест в базе данных на 1
            $clase->decrement('capacidad');

            return redirect()->back()->with('success', "¡Inscripción exitosa! Te has inscrito en {$clase->nombre}.");

        } catch (\Exception $e) {
            // Если сработал throw new \Exception из модели Socio (уже записан / нет мест),
            // мы НЕ возвращаем JSON, а перенаправляем назад с флагом ошибки!
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancelar(string $id)
{
    try {
        $clase = Clase::findOrFail($id);
        
        // Ищем текущего клиента
        $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
        
        if (!$socio) {
            return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
        }

        // Вызываем метод отмены
        $socio->cancelarInscripcion($clase);

        // ВАЖНО: Возвращаем 1 свободное место занятию!
        $clase->increment('capacidad');

        return redirect()->back()->with('success', "Has cancelado tu inscripción de: {$clase->nombre}.");

    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}
} 