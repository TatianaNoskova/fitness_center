<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Combo;

class ServicioComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Creamos los servicios individuales (Hojas / Leafs)
        $masaje = Servicio::create([
            'nombre' => 'Masaje Deportivo Descontracturante', 
            'precio' => 35000.00
        ]);
        
        $entrenador = Servicio::create([
            'nombre' => 'Sesión de Entrenamiento Personalizado', 
            'precio' => 45000.00
        ]);
        
        $nutricionista = Servicio::create([
            'nombre' => 'Consulta Nutricional + Plan de Dieta', 
            'precio' => 20000.00
        ]);
        
        // Un servicio extra que se queda suelto (para demostrar que no todo entra al combo)
        Servicio::create([
            'nombre' => 'Alquiler de Locker VIP Anual', 
            'precio' => 10000.00
        ]);

        // 2. Creamos la estructura del Composite en la Base de Datos
        $combo = Combo::create([
            'nombre' => 'Combo Premium «Recuperación Total»',
            'descuento' => 15
        ]);

        // Vinculamos los servicios al combo a través de la tabla intermedia (Pivot)
        $combo->servicios()->attach([
            $masaje->id, 
            $entrenador->id, 
            $nutricionista->id
        ]);
    }
}