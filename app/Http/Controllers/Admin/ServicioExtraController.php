<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Combo;

class ServicioExtraController extends Controller
{
    /**
     * Шаг 1: Создание новой одиночной услуги (Leaf) в базе данных
     */
    public function storeServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ]);

        Servicio::create([
            'nombre' => $request->input('nombre'),
            'precio' => $request->input('precio'),
        ]);

        return redirect()->back();
    }

    /**
     * Шаг 2: Создание нового пустого комбо-пакета (Composite)
     */
    public function storeCombo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $nuevoCombo = Combo::create([
            'nombre' => $request->input('nombre')
        ]);

        // Сразу перенаправляем админа, открывая в селекторе созданный комбо
        return redirect()->to(url()->previous() . '?combo_id=' . $nuevoCombo->id);
    }

    /**
     * Шаг 3: Переключение услуг внутри выбранного комбо (Pattern Composite)
     */
    public function toggleServicioEnCombo($comboId, $servicioId)
    {
        $combo = Combo::findOrFail($comboId);
        
        // Переключаем связь Many-to-Many в промежуточной таблице
        $combo->servicios()->toggle($servicioId);

        return redirect()->to(url()->previous() . '?combo_id=' . $comboId);
    }

    
}