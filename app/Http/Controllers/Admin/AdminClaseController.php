<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        
        


        return view('admin.clases', compact('clases'));
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
