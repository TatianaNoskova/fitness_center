<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entrenador;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EntrenadorController extends Controller
{
    public function index()
    {
        // Подгружаем тренеров со связями
        $entrenadores = Entrenador::with(['user', 'obedienceSede'])->get();
        $sedes = Sede::all();

        return view('admin.entrenadores', compact('entrenadores', 'sedes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|unique:users,dni',
            'telefono' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'sede_id' => 'nullable|exists:sedes,id',
            'especialidad' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Создаем пользователя с ролью entrenador
            $user = User::create([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'dni' => $validated['dni'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'rol' => 'entrenador',
            ]);

            // Создаем запись в таблице entrenadors
            Entrenador::create([
                'user_id' => $user->id,
                'sede_id' => $validated['sede_id'],
                'especialidad' => $validated['especialidad'],
                'estado' => 'ACTIVO',
            ]);

            DB::commit();
            return redirect()->route('admin.entrenadores.index')->with('success', '¡Entrenador registrado con éxito!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $entrenador = Entrenador::find($id);
        if (!$entrenador) return redirect()->route('admin.entrenadores.index')->withErrors(['error' => 'Entrenador no encontrado']);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $entrenador->user_id,
            'sede_id' => 'nullable|exists:sedes,id',
            'especialidad' => 'required|string|max:50',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'password' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $entrenador->update([
                'sede_id' => $request->sede_id,
                'especialidad' => $request->especialidad,
                'estado' => $request->estado
            ]);
            
            $userData = $request->only(['nombre', 'apellido', 'telefono', 'email']);

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($entrenador->user) {
                $entrenador->user->update($userData);
            }

            DB::commit();
            return redirect()->route('admin.entrenadores.index')->with('success', '¡Entrenador actualizado con éxito!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $entrenador = Entrenador::find($id);
        if (!$entrenador) return redirect()->route('admin.entrenadores.index')->withErrors(['error' => 'Entrenador no encontrado']);
        
        $entrenador->update(['estado' => 'INACTIVO']);
        return redirect()->route('admin.entrenadores.index')->with('success', 'Entrenador desactivado.');
    }
}