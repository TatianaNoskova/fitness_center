<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Combo;
use App\Patterns\Composite\ClaseComposite;
use App\Patterns\Composite\ClaseLeaf;

class ServicioExtraController extends Controller
{

public function index(Request $request)
{
    // 1. Получаем все комбо и все одиночные услуги для списков
    $todosLosCombos = Combo::all();
    $todosLosServicios = Servicio::all();

    // 2. Определяем текущий выбранный комбо (из URL или берем самый первый из базы)
    $comboId = $request->query('combo_id', $todosLosCombos->first()?->id);
    
    // Если в базе вообще нет комбо, создаем «пустышку», чтобы страница не падала
    if (!$comboId) {
        $comboModel = new Combo(['nombre' => 'Ninguno', 'descuento' => 0]);
        $comboServicios = new ClaseComposite($comboModel->nombre, 0);
        $serviciosEnComboIds = [];
    } else {
        // Загружаем комбо вместе с его услугами
        $comboModel = Combo::with('servicios')->findOrFail($comboId);
        
        // !!! ВОТ ЗДЕСЬ ИСПРАВЛЕНА МАГИЯ МАТЕМАТИКИ !!!
        // Передаем в паттерн имя И СКИДКУ из базы данных
        $comboServicios = new ClaseComposite($comboModel->nombre, $comboModel->descuento);

        // Наполняем наш Composite «листьями» (Leaf)
        foreach ($comboModel->servicios as $servicio) {
            $comboServicios->agregar(new ClaseLeaf($servicio));
        }

        // Получаем ID услуг, которые уже есть в этом комбо (для кнопок Quitar/Agregar)
        $serviciosEnComboIds = $comboModel->servicios->pluck('id')->toArray();
    }

    

    // 3. Отдаем всё это в твой Blade
    return view('admin.servicios_extras', compact(
        'comboModel',
        'comboServicios',
        'todosLosCombos',
        'todosLosServicios',
        'serviciosEnComboIds'
    ));
}


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
        'descuento' => 'required|integer|min:0|max:100',
    ]);

    $nuevoCombo = Combo::create([
        'nombre' => $request->input('nombre'),
        'descuento' => $request->input('descuento'),
    ]);

    return redirect()->route('composite.index', ['combo_id' => $nuevoCombo->id]);
}

public function toggleServicioEnCombo($comboId, $servicioId)
{
    $combo = Combo::findOrFail($comboId);
    $combo->servicios()->toggle($servicioId);

    return redirect()->route('composite.index', ['combo_id' => $comboId]);
}

    
}