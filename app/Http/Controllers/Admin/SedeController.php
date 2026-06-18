<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        
        $sedes = Sede::withCount('socios')->get();

        return view('admin.sedes', compact('sedes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            Sede::create($validated);
            return redirect()->route('admin.sedes.index')->with('success', '¡Sede registrada con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $sede = Sede::find($id);
        if (!$sede) return redirect()->route('admin.sedes.index')->withErrors(['error' => 'Sede no encontrada']);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        try {
            $sede->update($validated);
            return redirect()->route('admin.sedes.index')->with('success', '¡Sede actualizada con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);

        if ($sede->socios_count > 0 || ($sede->users && $sede->users()->exists())) {
            return redirect()->back()->withErrors([
                'error' => "No se puede eliminar la sede «{$sede->nombre}» porque tiene socios activos vinculados."
            ]);
        }

        $sede->delete();

        return redirect()->route('admin.sedes.index')
            ->with('success', "La sede «{$sede->nombre}» fue eliminada correctamente.");
    }
}