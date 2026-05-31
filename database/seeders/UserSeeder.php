<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sede;
use App\Models\Plan;
use App\Models\Socio;
use App\Models\Entrenador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Берем первую попавшуюся sede из базы для привязки сотрудников и клиентов
        $sedeCentral = Sede::first();

        // Получаем первый доступный план для привязки к клиенту
        $planBase = Plan::first();

        // 1. Администратор
        User::create([
            'nombre' => 'Carlos',
            'apellido' => 'Admin',
            'dni' => '11111111',
            'telefono' => '11223344',
            'email' => 'admin@gym.com',
            'password' => Hash::make('admin123'),
            'rol' => 'ADMINISTRADOR',
        ]);

        // 2. Тренер
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
            'sede_id' => $sedeCentral ? $sedeCentral->id : null,
            'especialidad' => 'Crossfit y Musculación',
            'estado' => 'ACTIVO',
        ]);

        // 3. Клиент (Socio) с полной поддержкой паттерна Strategy
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
            'sede_id' => $sedeCentral ? $sedeCentral->id : null, // Его "домашний" филиал
            'plan_id' => $planBase ? $planBase->id : null,       // Привязываем к базовому плану
            'categoria' => 'VIP',                               // Категория: NORMAL, ESTUDIANTE или VIP
            'fecha_alta' => now()->toDateString(),
            'estado' => 'ACTIVO',
        ]);
    }
}