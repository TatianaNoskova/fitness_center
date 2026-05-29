<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sede;
use App\Models\Plan;
use App\Models\Socio;
use App\Models\Entrenador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Создаем тестовый филиал (Sede)
        $sede = Sede::create([
            'nombre' => 'Sede Central Palermo',
            'direccion' => 'Av. Santa Fe 3200, CABA',
            'telefono' => '+54 11 4567-8901',
            'email' => 'palermo@fitnessgym.com'
        ]);

        // 2. Создаем планы (Plans)
        $planBase = Plan::create([
            'nombre' => 'Plan Pase Libre',
            'descripcion' => 'Acceso ilimitado a sala de musculación y clases',
            'precio' => 15000.00,
            'duracion' => 30, // 30 дней
            'estado' => 'ACTIVO'
        ]);

        Plan::create([
            'nombre' => 'Plan Estudiante',
            'descripcion' => 'Acceso de lunes a viernes de 8:00 a 16:00',
            'precio' => 10000.00,
            'duracion' => 30,
            'estado' => 'ACTIVO'
        ]);

        // 3. Создаем Администратора
        User::create([
            'nombre' => 'Carlos',
            'apellido' => 'Admin',
            'dni' => '11111111',
            'telefono' => '11223344',
            'email' => 'admin@gym.com',
            'password' => Hash::make('admin123'),
            'rol' => 'ADMINISTRADOR',
        ]);

        // 4. Создаем Тренера (Сначала User, потом расширение в Entrenador)
        $userEntrenador = User::create([
            'nombre' => 'Juana',
            'apellido' => 'Perez',
            'dni' => '22222222',
            'telefono' => '55667788',
            'email' => 'trainer@gym.com',
            'password' => Hash::make('trainer123'),
            'rol' => 'ENTRENADOR',
        ]);

        Entrenador::create([
            'user_id' => $userEntrenador->id,
            'sede_id' => $sede->id,
            'especialidad' => 'Crossfit y Musculación',
            'estado' => 'ACTIVO'
        ]);

        // 5. Создаем Клиента (Сначала User, потом расширение в Socio)
        $userSocio = User::create([
            'nombre' => 'Martin',
            'apellido' => 'Gomez',
            'dni' => '33333333',
            'telefono' => '99001122',
            'email' => 'socio@gym.com',
            'password' => Hash::make('socio123'),
            'rol' => 'SOCIO',
        ]);

        Socio::create([
            'user_id' => $userSocio->id,
            'sede_id' => $sede->id,
            'fecha_alta' => now()->toDateString(),
            'estado' => 'ACTIVO'
        ]);
    }
}