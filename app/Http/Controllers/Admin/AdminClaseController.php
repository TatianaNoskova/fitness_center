<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Clase;
use App\Models\Sede;
use App\Models\Entrenador; 
use App\Models\User;

class AdminClaseController extends Controller
{
    
public function index(Request $request)
{
    $clases = Clase::with(['sede', 'entrenador.user'])->orderBy('fecha')->orderBy('hora')->get();
    $sedes = Sede::all();
    
    $allEntrenadores = Entrenador::with('user')->where('estado', 'ACTIVO')->get();

    $selectedSedeId = $request->get('selected_sede_id');
    
    $entrenadores = $selectedSedeId 
        ? $allEntrenadores->filter(function($entrenador) use ($selectedSedeId) {
            return (int) $entrenador->sede_id === (int) $selectedSedeId;
        })->values()
        : collect();

    return view('admin.clases', [
        'clases' => $clases,
        'sedes' => $sedes,
        'entrenadores' => $entrenadores,       
        'allEntrenadores' => $allEntrenadores, 
        'selectedSedeId' => $selectedSedeId
    ]);
}


    public function store(Request $request)
{
    $data = $request->validate([
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'fecha' => 'required|date|after_or_equal:today',
        'hora' => 'required',
        'capacidad' => 'required|integer|min:1',
        'entrenador_id' => 'required|exists:entrenadors,user_id',
        'sede_id' => 'required|exists:sedes,id', 
    ]);

    Clase::crearClase($data);

    return redirect()->route('admin.clases.index', ['selected_sede_id' => $request->sede_id])
        ->with('success', '¡Clase creada con éxito en la sede seleccionada!');
}

public function edit($id)
{
    $clase = Clase::findOrFail($id);
    $sedes = Sede::all();
    
    $entrenadores = Entrenador::with('user')
        ->where('sede_id', $clase->sede_id)
        ->where('estado', 'ACTIVO')
        ->get();

    return view('admin.clases_edit', compact('clase', 'sedes', 'entrenadores'));
}

public function update(Request $request, $id)
{
    $clase = Clase::findOrFail($id);

    $inscritosEnInscripciones = $clase->inscripciones()->count();
    $inscritosEnSocios = $clase->socios()->count();
    
    $tieneInscritos = ($inscritosEnInscripciones > 0 || $inscritosEnSocios > 0);
    $totalInscritos = max($inscritosEnInscripciones, $inscritosEnSocios);

    $rules = [
        'descripcion' => 'nullable|string',
        'capacidad' => 'required|integer|min:' . max(1, $totalInscritos),
    ];

    if (!$tieneInscritos) {
        $rules['nombre'] = 'required|string|max:100';
        $rules['entrenador_id'] = 'required|exists:entrenadors,user_id';
        
        if ($request->has('fecha')) $rules['fecha'] = 'required|date|after_or_equal:today';
        if ($request->has('hora')) $rules['hora'] = 'required|string';
    }

    $request->validate($rules);

    if ($tieneInscritos) {
        $finalData = [
            'nombre'        => $clase->nombre,
            'fecha'         => $clase->fecha,
            'hora'          => $clase->hora,
            'entrenador_id' => $clase->entrenador_id,
            'sede_id'       => $clase->sede_id,
            'descripcion'   => $request->input('descripcion'),
            'capacidad'     => $request->input('capacidad'),
        ];
    } else {
        
        $finalData = [
            'nombre'        => $request->input('nombre'),
            'fecha'         => $request->input('fecha', $clase->fecha),
            'hora'          => $request->input('hora', $clase->hora),
            'entrenador_id' => $request->input('entrenador_id'),
            'descripcion'   => $request->input('descripcion'),
            'capacidad'     => $request->input('capacidad'),
        ];
        
        $entrenador = \App\Models\Entrenador::where('user_id', $request->input('entrenador_id'))->firstOrFail();
        $finalData['sede_id'] = $entrenador->sede_id;
    }

  
    $clase->update($finalData);

    $msg = $tieneInscritos 
        ? '¡Los ajustes de capacidad y descripción se guardaron de forma segura!' 
        : '¡La clase ha sido modificada con éxito!';

    $redirectSedeId = $request->input('selected_sede_id') ?? $clase->sede_id;

    return redirect()->route('admin.clases.index', ['selected_sede_id' => $redirectSedeId])
        ->with('success', $msg);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $clase = \App\Models\Clase::findOrFail($id);
        
        $clase->delete();

        return redirect()->route('admin.clases.index')
        ->with('success', '¡La clase ha sido eliminada y el sistema ha notificado a los socios!');
    }

    public function inscribir(string $id)
    {
        try {
            $clase = \App\Models\Clase::findOrFail($id);
            
            $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
            
            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            $socio->inscribirseAClase($clase);

            $clase->decrement('capacidad');

            return redirect()->back()->with('success', "¡Inscripción exitosa! Te has inscrito en {$clase->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

       public function cancelar(string $id)
    {
        try {
            $clase = \App\Models\Clase::findOrFail($id);
            
            $socio = \App\Models\Socio::where('user_id', auth()->id())->first();
            
            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            $socio->cancelarInscripcion($clase);

            $clase->increment('capacidad');

            return redirect()->back()->with('success', "Has cancelado tu inscripción de: {$clase->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
