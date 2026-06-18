<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
   public function index()
{
    $planes = \App\Models\Plan::all(); 
    
    return view('admin.planes', compact('planes'));
}
    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:100',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ]);

        try {
            Plan::create($validated);
            return redirect()->route('plans.index')->with('success', '¡Plan creado con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);
        if (!$plan) return redirect()->route('plans.index')->withErrors(['error' => 'Plan no encontrado']);

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:100',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ]);

        try {
            $plan->update($validated);
            return redirect()->route('plans.index')->with('success', '¡Plan actualizado con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}