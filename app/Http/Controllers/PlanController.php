<?php

namespace App\Http\Controllers;

use App\Models\Plan;   // Твоя модель тарифов с методами UML
use App\Models\Combo;  // Наша новая модель комбо-пакетов услуг из БД
use App\Patterns\Composite\ClaseComposite; // Наш Композит
use App\Patterns\Composite\ClaseLeaf;      // Наш Лист
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Отображение страницы тарифов в браузере (Blade)
     */
    public function index()
    {
        // 1. Базовые тарифы
        $planes = Plan::where('estado', 'ACTIVO')->get();

        // 2. Все существующие одиночные услуги для демонстрационной таблицы
        $todosLosServicios = \App\Models\Servicio::all();

        // 3. Собираем наш Композит из базы данных
        $comboModel = Combo::with('servicios')->first();
        $comboName = $comboModel ? $comboModel->nombre : "Combo Premium «Recuperación Total»";
        $comboServicios = new ClaseComposite($comboName);

        // Массив ID услуг, которые уже находятся в комбо (пригодится для Blade)
        $serviciosEnComboIds = [];

        if ($comboModel && $comboModel->servicios) {
            foreach ($comboModel->servicios as $servicioModel) {
                $comboServicios->agregar(new ClaseLeaf($servicioModel));
                $serviciosEnComboIds[] = $servicioModel->id;
            }
        }

        // Передаем всё во View
        return view('admin.planes', compact('planes', 'comboServicios', 'todosLosServicios', 'serviciosEnComboIds', 'comboModel'));
    }

    public function toggleServicioEnCombo(Request $request, $servicioId)
    {
        $combo = Combo::first();
        if (!$combo) {
            $combo = Combo::create(['nombre' => 'Combo Premium «Recuperación Total»']);
        }

        // Если услуга уже в комбо — удаляем, если нет — добавляем (метод toggle в Laravel пивоте)
        $combo->servicios()->toggle($servicioId);

        return redirect()->back()->with('success', 'Estructura del Composite actualizada.');
    }

    /**
     * Создание нового плана (Метод из UML-модели)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
        ]);

        // Используем твой родной метод из модели UML
        Plan::registrarPlan($validated);

        // После создания перенаправляем обратно на страницу со списком тарифов
        return redirect()->route('planes.index')->with('success', 'Plan creado con éxito');
    }

    /**
     * Обновление существующего плана
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->back()->with('error', 'Plan no encontrado');
        }

        // Вызываем твой метод из модели UML
        $plan->actualizarPlan($request->all());

        return redirect()->route('planes.index')->with('success', 'Plan actualizado');
    }

    /**
     * Удаление (деактивация) плана
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return redirect()->back()->with('error', 'Plan no encontrado');
        }

        // Переводим в статус INACTIVO через метод модели UML
        $plan->desactivarPlan();

        return redirect()->route('planes.index')->with('success', 'Plan desactivado con éxito');
    
        }
}