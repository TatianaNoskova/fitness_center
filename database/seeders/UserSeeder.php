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
    
        $sedes = Sede::all();
        $planes = Plan::all();

        $sedeIds = $sedes->pluck('id')->toArray() ?: [null];
        $planIds = $planes->pluck('id')->toArray() ?: [null];

        $passwordAdmin = Hash::make('admin123');
        $passwordTrainer = Hash::make('trainer123');
        $passwordSocio = Hash::make('socio123');

        // ==========================================
        // 1. ADMIN (1 persona)
        // ==========================================
        User::create([
            'nombre' => 'Carlos',
            'apellido' => 'Admin',
            'dni' => '11111111',
            'telefono' => '11223344',
            'email' => 'admin@gym.com',
            'password' => $passwordAdmin,
            'rol' => 'ADMINISTRADOR', 
        ]);

        // ==========================================
        // 2. ENTRENADORES (5 personas)
        // ==========================================
        $trainersData = [
            ['Juana', 'Perez', '22222221', '1533445566', 'trainer@gym.com', 'Crossfit y Musculación'],
            ['Mariano', 'Silva', '22222222', '1533445567', 'mariano@gym.com', 'Spinning y Cardio'],
            ['Valentina', 'Russo', '22222223', '1533445568', 'valentina@gym.com', 'Yoga y Pilates'],
            ['Facundo', 'Diaz', '22222224', '1533445569', 'facundo@gym.com', 'Boxeo y Functional'],
            ['Camila', 'Torres', '22222225', '1533445570', 'camila@gym.com', 'Zumba y Ritmos'],
        ];

        foreach ($trainersData as $index => $data) {
            $user = User::create([
                'nombre' => $data[0],
                'apellido' => $data[1],
                'dni' => $data[2],
                'telefono' => $data[3],
                'email' => $data[4],
                'password' => $passwordTrainer,
                'rol' => 'ENTRENADOR',
            ]);

            Entrenador::create([
                'user_id' => $user->id,
                // Распределяем тренеров по существующим филиалам по очереди
                'sede_id' => $sedeIds[$index % count($sedeIds)],
                'especialidad' => $data[5],
                'estado' => 'ACTIVO',
            ]);
        }

        // ==========================================
        // 3. SOCIOS (10 personas)
        // ==========================================
        $sociosData = [
            ['Martin', 'Gomez', '33333331', '1544556611', 'socio@gym.com', 'VIP'],
            ['Lucas', 'Rodriguez', '33333332', '1544556622', 'lucas@gym.com', 'NORMAL'],
            ['Sofia', 'Fernandez', '33333333', '1544556633', 'sofia@gym.com', 'ESTUDIANTE'],
            ['Mateo', 'Alvarez', '33333334', '1544556644', 'mateo@gym.com', 'NORMAL'],
            ['Elena', 'Benitez', '33333335', '1544556655', 'elena@gym.com', 'VIP'],
            ['Diego', 'Herrera', '33333336', '1544556666', 'diego@gym.com', 'ESTUDIANTE'],
            ['Martina', 'Lopez', '33333337', '1544556677', 'martina@gym.com', 'NORMAL'],
            ['Bautista', 'Gonzalez', '33333338', '1544556688', 'bautista@gym.com', 'VIP'],
            ['Agustina', 'Romero', '33333339', '1544556699', 'agustina@gym.com', 'NORMAL'],
            ['Nicolas', 'Castro', '33333340', '1544556700', 'nicolas@gym.com', 'ESTUDIANTE'],
        ];

        foreach ($sociosData as $index => $data) {
            $user = User::create([
                'nombre' => $data[0],
                'apellido' => $data[1],
                'dni' => $data[2],
                'telefono' => $data[3],
                'email' => $data[4],
                'password' => $passwordSocio,
                'rol' => 'SOCIO',
            ]);

            Socio::create([
                'user_id' => $user->id,
                'sede_id' => $sedeIds[$index % count($sedeIds)], 
                'plan_id' => $planIds[$index % count($planIds)], 
                'categoria' => $data[5], 
                'fecha_alta' => now()->subMonths(rand(1, 6))->toDateString(), 
                'estado' => 'ACTIVO',
            ]);
        }
    }
}