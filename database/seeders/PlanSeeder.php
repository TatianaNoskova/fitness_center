<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
    
        Plan::create([
            'nombre' => 'Plan Pase Libre',
            'descripcion' => 'Acceso ilimitado a todas las sedes, sala de musculación y clases',
            'precio' => 150000.00,
            'duracion' => 30,
            'estado' => 'ACTIVO'
        ]);

        
        Plan::create([
            'nombre' => 'Plan Horario Reducido',
            'descripcion' => 'Acceso de lunes a viernes en el horario de 8:00 a 16:00',
            'precio' => 100000.00,
            'duracion' => 30,
            'estado' => 'ACTIVO'
        ]);
    }
}