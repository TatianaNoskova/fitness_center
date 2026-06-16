<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Socio;
use App\Models\Sede;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SocioController extends Controller
{
    public function index()
    {
        // Подгружаем socios вместе с пользователем, филиалом и планом
        $socios = Socio::with(['user', 'sede', 'plan'])->get();
        
        $sedes = Sede::all();
        $planes = Plan::all();

        // Категории берем массивом строк, так как под них нет отдельной таблицы
        $categorias = ['NORMAL', 'ESTUDIANTE', 'VIP'];

        return view('admin.socios', compact('socios', 'sedes', 'planes', 'categorias'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'dni' => 'required|string|unique:users,dni',
        'telefono' => 'nullable|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6', // Пароль теперь ОБЯЗАТЕЛЕН при создании админом
        'sede_id' => 'required|exists:sedes,id',
        'plan_id' => 'required|exists:plans,id',
        'categoria' => 'required|in:NORMAL,ESTUDIANTE,VIP',
    ]);

    DB::beginTransaction();
    try {
        $user = User::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Хешируем введенный админом пароль
            'rol' => 'socio',
        ]);

        Socio::create([
            'user_id' => $user->id,
            'sede_id' => $validated['sede_id'],
            'plan_id' => $validated['plan_id'],
            'categoria' => strtoupper($validated['categoria']),
            'fecha_alta' => now()->toDateString(),
            'estado' => 'ACTIVO',
        ]);

        DB::commit();
        return redirect()->route('admin.socios.index')->with('success', '¡Socio registrado con éxito!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}

public function update(Request $request, $id)
{
    $socio = Socio::find($id);
    if (!$socio) return redirect()->route('admin.socios.index')->withErrors(['error' => 'Socio no encontrado']);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $socio->user_id,
        'sede_id' => 'required|exists:sedes,id',
        'plan_id' => 'required|exists:plans,id',
        'categoria' => 'required|in:NORMAL,ESTUDIANTE,VIP',
        'estado' => 'required|in:ACTIVO,INACTIVO',
        'password' => 'nullable|string|min:6', // При редактировании — по желанию
    ]);

    DB::beginTransaction();
    try {
        $socio->update([
            'sede_id' => $request->sede_id,
            'plan_id' => $request->plan_id,
            'categoria' => strtoupper($request->categoria),
            'estado' => $request->estado
        ]);
        
        $userData = $request->only(['nombre', 'apellido', 'telefono', 'email']);

        // Меняем пароль только если поле заполнено
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($socio->user) {
            $socio->user->update($userData);
        }

        DB::commit();
        return redirect()->route('admin.socios.index')->with('success', '¡Socio actualizado con éxito!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}

    public function destroy($id)
    {
        $socio = Socio::find($id);
        if (!$socio) return redirect()->route('admin.socios.index')->withErrors(['error' => 'Socio no encontrado']);
        $socio->update(['estado' => 'INACTIVO']);
        return redirect()->route('admin.socios.index')->with('success', 'Socio desactivado.');
    }
}