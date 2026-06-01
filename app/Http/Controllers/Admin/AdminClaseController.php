<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Patterns\Composite\ClaseComposite;
use App\Patterns\Composite\ClaseLeaf;
use App\Models\Clase;

class AdminClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Твой родной код: берём все классы с филиалами и сортировкой
        $clases = \App\Models\Clase::with('sede')->orderBy('fecha')->orderBy('hora')->get();
        
        // 2. РЕАЛИЗАЦИЯ COMPOSITE: Создаем комбо-пакет «Интенсив: Тело к лету»
        $comboCurso = new \App\Patterns\Composite\ClaseComposite("Пакет «Интенсив: Тело к лету»");

        // Берём первые 3 занятия из твоей коллекции, чтобы наполнить комбо реальными данными
        $primerasClases = $clases->take(3);

        foreach ($primerasClases as $claseModel) {
            // Оборачиваем каждую модель Clase в Лист (Leaf) и добавляем в Композит
            $comboCurso->agregar(new \App\Patterns\Composite\ClaseLeaf($claseModel));
        }

        // 3. Возвращаем твой view, добавив в compact переменную comboCurso
        return view('admin.clases', compact('clases', 'comboCurso'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
}
