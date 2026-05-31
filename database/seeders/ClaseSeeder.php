<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Sede;
use Illuminate\Database\Seeder;

class ClaseSeeder extends Seeder
{
    public function run(): void
    {
        // Находим филиалы по именам, которые созданы в SedeSeeder
        $sedeCentral = Sede::where('nombre', 'Sede Central')->first();
        $sedeNorte = Sede::where('nombre', 'Sede Norte')->first();

        // Создаем занятие в Центральном филиале БЕЗ тренера (научит админ)
        Clase::create([
            'nombre' => 'Clase de Yoga',
            'descripcion' => 'Yoga relajante para todos los niveles',
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '10:00:00',
            'capacidad' => 15,
            'sede_id' => $sedeCentral->id,
            'entrenador_id' => null // Тренер пока не назначен!
        ]);

        // Создаем занятие в Северном филиале тоже без тренера
        Clase::create([
            'nombre' => 'Crossfit Intenso',
            'descripcion' => 'Entrenamiento de alta intensidad',
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '18:30:00',
            'capacidad' => 10,
            'sede_id' => $sedeNorte->id,
            'entrenador_id' => null // Тренер пока не назначен!
        ]);
    }
}