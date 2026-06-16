<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clase; // Убедитесь, что у вас есть модель Clase
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    // Получаем ID текущего авторизованного тренера
    $entrenadorId = \Illuminate\Support\Facades\Auth::user()->id; 

    // Загружаем занятия именно этого тренера с правильной сортировкой по вашей базе
    $clases = \App\Models\Clase::where('entrenador_id', $entrenadorId)
        ->with('socios')
        ->orderBy('fecha', 'asc') // Сначала сортируем по дате
        ->orderBy('hora', 'asc')  // Затем внутри дня — по вашей колонке 'hora'
        ->get();

    return view('entrenador.dashboard', compact('clases'));
}

    // Метод для отметки посещаемости через AJAX или обычную отправку формы
    public function marcarAsistencia(Request $request, $claseId, $socioId)
    {
        $request->validate([
            'asistio' => 'required|in:SI,NO,PENDIENTE'
        ]);

        $clase = Clase::findOrFail($claseId);
        
        // Обновляем статус в промежуточной таблице (pivot) между Clase и Socio
        $clase->socios()->updateExistingPivot($socioId, [
            'asistencia' => $request->asistio // Поле в вашей pivot-таблице (например, clase_socio)
        ]);

        return back()->with('success', 'Asistencia actualizada con éxito.');
    }
}