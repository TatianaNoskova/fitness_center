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
        $entrenadorId = Auth::user()->id; 

        $clases = Clase::where('entrenador_id', $entrenadorId)
            ->where('estado', 'PROGRAMADA')
            ->with('socios')
            ->orderBy('fecha', 'asc') 
            ->orderBy('hora', 'asc')  
            ->get();

        $historial = Clase::where('entrenador_id', $entrenadorId)
            ->where('estado', 'FINALIZADA') 
            ->with('socios')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('entrenador.dashboard', compact('clases', 'historial'));
    }

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

    public function finalizarClase($id)
    {
        $entrenadorId = Auth::user()->id;

        // Ищем класс, принадлежащий именно этому тренеру
        $clase = Clase::where('id', $id)
            ->where('entrenador_id', $entrenadorId)
            ->firstOrFail();

        // Вызываем красивый бизнес-метод модели
        $clase->finalizar();

        return back()->with('success', 'La clase ha sido finalizada y movida al historial con éxito.');
    }
}