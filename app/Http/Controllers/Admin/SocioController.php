<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SocioController extends Controller
{
    public function index()
    {
        $socios = Socio::with(['user', 'sede'])->get();
        return response()->json($socios, 200);
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
            'sede_id' => 'required|exists:sedes,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'dni' => $validated['dni'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'rol' => 'socio',
            ]);

            $socio = Socio::create([
                'user_id' => $user->id,
                'sede_id' => $validated['sede_id'],
                'fecha_alta' => now()->toDateString(),
                'estado' => 'ACTIVO',
            ]);

            DB::commit();
            return response()->json(['message' => 'Socio registrado con éxito', 'user_id' => $user->id, 'socio_id' => $socio->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar el socio', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $socio = Socio::with(['user', 'sede'])->find($id);
        if (!$socio) return response()->json(['message' => 'Socio no encontrado'], 404);
        return response()->json($socio, 200);
    }

    public function update(Request $request, $id)
    {
        $socio = Socio::find($id);
        if (!$socio) return response()->json(['message' => 'Socio no encontrado'], 404);

        $socio->update($request->only(['sede_id', 'estado']));
        if ($socio->user) {
            $socio->user->update($request->only(['nombre', 'apellido', 'telefono', 'email']));
        }
        return response()->json(['message' => 'Socio actualizado con éxito', 'data' => $socio->load('user')], 200);
    }

    public function destroy($id)
    {
        $socio = Socio::find($id);
        if (!$socio) return response()->json(['message' => 'Socio no encontrado'], 404);

        $socio->update(['estado' => 'INACTIVO']);
        return response()->json(['message' => 'Socio desactivado con éxito (Estado: INACTIVO)'], 200);
    }
}