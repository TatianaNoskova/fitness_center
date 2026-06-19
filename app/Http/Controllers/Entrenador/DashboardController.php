<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clase; 
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Берем ID авторизованного пользователя напрямую из таблицы users
        $entrenadorId = Auth::user()->id; 

        // 1. Актуальные занятия (ищем по user_id, сохраненному в entrenador_id)
        $clases = Clase::where('entrenador_id', $entrenadorId)
            ->where('estado', 'PROGRAMADA') // Игнорируем архивные
            ->with('socios')
            ->orderBy('fecha', 'asc') 
            ->orderBy('hora', 'asc')  
            ->get();

        // 2. Архивные занятия (только со статусом FINALIZADA)
        $historial = Clase::where('entrenador_id', $entrenadorId)
            ->where('estado', 'FINALIZADA') // Берем только завершенные
            ->with('socios')
            ->orderBy('fecha', 'desc') // Свежие завершенные будут сверху
            ->get();

        // Передаем в представление и активные классы, и архив
        return view('entrenador.dashboard', compact('clases', 'historial'));
    }

    // Метод для отметки посещаемости — полностью сохранен
    public function marcarAsistencia(Request $request, $claseId, $socioId)
    {
        $request->validate([
            'asistio' => 'required|in:SI,NO,PENDIENTE'
        ]);

        $clase = Clase::findOrFail($claseId);
        
        $clase->socios()->updateExistingPivot($socioId, [
            'asistencia' => $request->asistio 
        ]);

        return back()->with('success', 'Asistencia actualizada con éxito.');
    }

    // Метод перевода класса в историю — адаптирован под прямую проверку по user_id
    public function finalizarClase($id)
    {
        $entrenadorId = Auth::user()->id;

        // Находим класс, проверяя, что entrenador_id равен id пользователя
        $clase = Clase::where('id', $id)
            ->where('entrenador_id', $entrenadorId)
            ->firstOrFail();

        // Меняем статус на "Завершена"
        $clase->update([
            'estado' => 'FINALIZADA'
        ]);

        return back()->with('success', 'La clase ha sido movida al historial.');
    }
}