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
    $todosLosCombos = Combo::all();
    $todosLosServicios = Servicio::all();

    $comboId = $request->query('combo_id', $todosLosCombos->first()?->id);
    
    if (!$comboId) {
        $comboModel = new Combo(['nombre' => 'Ninguno', 'descuento' => 0]);
        $comboServicios = new ClaseComposite($comboModel->nombre, 0);
        $serviciosEnComboIds = [];
    } else {

        $comboModel = Combo::with('servicios')->findOrFail($comboId);
        
        $comboServicios = new ClaseComposite($comboModel->nombre, $comboModel->descuento);

        foreach ($comboModel->servicios as $servicio) {
            $comboServicios->agregar(new ClaseLeaf($servicio));
        }

        $serviciosEnComboIds = $comboModel->servicios->pluck('id')->toArray();
    }

    return view('admin.servicios_extras', compact(
        'comboModel',
        'comboServicios',
        'todosLosCombos',
        'todosLosServicios',
        'serviciosEnComboIds'
    ));
}

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