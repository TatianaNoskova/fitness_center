<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        // Показываем только активные тарифные планы
        return response()->json(Plan::where('estado', 'ACTIVO')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
        ]);

        // Используем метод из модели UML
        $plan = Plan::registrarPlan($validated);

        return response()->json(['message' => 'Plan creado con éxito', 'data' => $plan], 201);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan no encontrado'], 404);
        }

        $plan->actualizarPlan($request->all());

        return response()->json(['message' => 'Plan actualizado', 'data' => $plan], 200);
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan no encontrado'], 404);
        }

        // Переводим в статус INACTIVO через метод модели
        $plan->desactivarPlan();

        return response()->json(['message' => 'Plan desactivado con éxito'], 200);
    }
}