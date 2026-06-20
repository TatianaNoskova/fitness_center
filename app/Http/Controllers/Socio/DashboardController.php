<?php

namespace App\Http\Controllers\Socio;

use App\Http\Controllers\Controller;
use App\Models\Socio;
use App\Models\Clase;
use App\Models\Sede; 
use App\Models\Plan; 
use App\Models\Combo;    
use App\Models\Servicio; 
use App\Models\Pago;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $socio = Socio::with(['sede', 'plan'])
            ->where('user_id', auth()->id())
            ->first();

            

        $todosCombos = \App\Models\Combo::with('servicios')->get();
        $todosServicios = \App\Models\Servicio::get();
        $clasesDisponibles = collect();
        $misInscripciones = collect();
        $precioCuota = 0;
        $historialPagos = collect(); 
        $todosLosPlanes = collect();
        $todasLasSedes = collect();

        if ($socio) {
            $hoy = now()->toDateString();
            $ahoraShort = now()->format('H:i');

            $clasesDisponibles = Clase::with(['sede', 'entrenador']) 
                ->where('estado', 'PROGRAMADA') 
                ->where(function($query) use ($hoy, $ahoraShort) {
                    $query->where('fecha', '>', $hoy)
                        ->orWhere(function($subQuery) use ($hoy, $ahoraShort) {
                            $subQuery->where('fecha', '=', $hoy)
                                    ->where('hora', '>=', $ahoraShort);
                        });
                })
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            $misInscripciones = $socio->clases()
                ->where(function($query) use ($hoy, $ahoraShort) {
                    $query->where('fecha', '>', $hoy)
                          ->orWhere(function($subQuery) use ($hoy, $ahoraShort) {
                              $subQuery->where('fecha', '=', $hoy)
                                       ->where('hora', '>=', $ahoraShort);
                          });
                })
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            if ($socio->plan) {
                $precioCuota = $socio->obtenerPrecioCuota(); 

                $historialPagos = \App\Models\Pago::where('socio_id', $socio->user_id)
                    ->orderBy('id', 'desc')
                    ->get();

                if ($historialPagos->isEmpty()) {
                    $primerPago = \App\Models\Pago::create([
                        'fecha'       => now()->toDateString(),
                        'monto'       => $precioCuota,
                        'metodo_pago' => 'Efectivo/Tarjeta', 
                        'estado'      => 'PENDIENTE',       
                        'socio_id'    => $socio->user_id,
                        'plan_id'     => $socio->plan_id,
                    ]);

                    $historialPagos = collect([$primerPago]);
                }
            }
        } else {
        
            $todasLasSedes = Sede::all();
            $todosLosPlanes = Plan::all();
        }

        return view('socio.dashboard', compact(
            'socio',
            'clasesDisponibles',
            'misInscripciones',
            'precioCuota',
            'historialPagos',
            'todosLosPlanes',
            'todasLasSedes',
            'todosCombos',     
            'todosServicios'   
        ));
    }

    public function crearPerfil(Request $request)
    {
        $request->validate([
            'sede_id'   => 'required',
            'plan_id'   => 'required|exists:plans,id',
            'categoria' => 'required|in:NORMAL,ESTUDIANTE,VIP', 
        ]);

        Socio::create([
            'user_id'   => auth()->id(),
            'sede_id'   => $request->sede_id,
            'plan_id'   => $request->plan_id,
            'categoria' => $request->categoria, 
            'estado'    => 'ACTIVO', 
            'fecha_alta'=> now()->toDateString(),
        ]);

        return redirect()->route('socio.dashboard')->with('success', '¡Tu perfil ha sido activado! Bienvenido a WorldClass.');
    }

    public function pagarCuota($id)
    {
        $pago = \App\Models\Pago::findOrFail($id);
        $exito = $pago->validarPago();

        if ($exito) {
            return redirect()->route('socio.dashboard')
                ->with('success', '¡Pago realizado con éxito! Tu cuota ha sido validada.');
        }

        return redirect()->route('socio.dashboard')
            ->with('error', 'No se pudo validar el pago. Verifique los datos.');
    }

    public function inscribir($id)
    {
        try {
            $clase = Clase::findOrFail($id);
            $socio = Socio::where('user_id', auth()->id())->first();

            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            $socio->inscribirseAClase($clase);

            return redirect()->route('socio.dashboard')->with('success', '¡Te has inscrito en la clase con éxito!');
            
        } catch (\Exception $e) {

            return redirect()->route('socio.dashboard')->with('error', $e->getMessage());
        }
    }

    public function cancelar($id)
    {
        try {
            $clase = Clase::findOrFail($id);
            $socio = Socio::where('user_id', auth()->id())->first();

            if (!$socio) {
                return redirect()->back()->with('error', 'No tienes un perfil de socio activo.');
            }

            $socio->cancelarInscripcion($clase);

            return redirect()->route('socio.dashboard')->with('success', 'Inscripción cancelada con éxito.');
            
        } catch (\Exception $e) {
            return redirect()->route('socio.dashboard')->with('error', $e->getMessage());
        }
    }


    public function contratarExtras(Request $request)
    {
        $comboIds = $request->input('combos', []);
        $servicioIds = $request->input('servicios', []);

        if (empty($comboIds) && empty($servicioIds)) {
            return redirect()->back()->with('error', 'Por favor, selecciona al menos un servicio o combo.');
        }

        $socio = Socio::where('user_id', auth()->id())->first();

        if (!$socio) {
            return redirect()->back()->with('error', 'No se encontró el perfil de socio.');
        }

        if (!empty($servicioIds)) {
            $servicios = Servicio::whereIn('id', $servicioIds)->get();
            
            foreach ($servicios as $servicio) {
                Pago::create([
                    'fecha'       => now()->toDateString(),
                    'monto'       => $servicio->precio,
                    'metodo_pago' => 'Efectivo/Tarjeta',
                    'estado'      => 'PENDIENTE',
                    'socio_id'    => $socio->user_id,
                    'plan_id'     => null, 
                    'combo_id'    => null,        
                    'servicio_id' => $servicio->id,
                     
                ]);
            }
        }

        if (!empty($comboIds)) {
            $combos = Combo::with('servicios')->whereIn('id', $comboIds)->get();
            
            foreach ($combos as $combo) {
                $precioCombo = $combo->precio_calculado; 

                Pago::create([
                    'fecha'       => now()->toDateString(),
                    'monto'       => $precioCombo,
                    'metodo_pago' => 'Efectivo/Tarjeta',
                    'estado'      => 'PENDIENTE',
                    'socio_id'    => $socio->user_id,
                    'plan_id'     => null, 
                    'combo_id'    => $combo->id,   
                    'servicio_id' => null,       
                
                ]);
            }
        }

        return redirect()->route('socio.dashboard')->with('success', '¡Servicios extras añadidos a tu lista de pagos pendientes!');
    }

    public function cancelarPago($id)
    {

        $pago = Pago::where('id', $id)
            ->where('socio_id', auth()->id())
            ->where('estado', 'PENDIENTE')
            ->first();

        if ($pago) {
            $pago->delete();
            return redirect()->back()->with('success', 'El servicio extra fue cancelado correctamente.');
        }

        return redirect()->back()->with('error', 'No se pudo cancelar этот платеж.');
    }
}