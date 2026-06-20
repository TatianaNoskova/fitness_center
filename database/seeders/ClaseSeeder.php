<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Sede;
use App\Models\User; 
use Illuminate\Database\Seeder;

class ClaseSeeder extends Seeder
{
    public function run(): void
    {
        
        $sedeCentral = Sede::where('nombre', 'Sede Central')->first();
        $sedeNorte = Sede::where('nombre', 'Sede Norte')->first();
        $sedeSur = Sede::where('nombre', 'Sede Sur')->first();
        
        $idCentral = $sedeCentral ? $sedeCentral->id : Sede::first()->id;
        $idNorte = $sedeNorte ? $sedeNorte->id : Sede::first()->id;
        $idSur = $sedeSur ? $sedeSur->id : Sede::first()->id;

        $idJuana = User::where('nombre', 'Juana')->value('id');
        $idMariano = User::where('nombre', 'Mariano')->value('id');
        $idValentina = User::where('nombre', 'Valentina')->value('id');
        $idFacundo = User::where('nombre', 'Facundo')->value('id');
        $idCamila = User::where('nombre', 'Camila')->value('id');

        // =======================================================
        // CREAR CLASES CON INSCRIPCION DIRECTA DE USER_ID EN LA TABLA CLASES
        // =======================================================

        // Clase para trainer@gym.com (Juana Perez)
        Clase::create([
            'nombre' => 'Crossfit Intenso',
            'descripcion' => 'Entrenamiento de alta intensidad para quemar grasa y ganar fuerza.',
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '18:30:00',
            'capacidad' => 10,
            'estado' => 'PROGRAMADA',
            'sede_id' => $idNorte,
            'entrenador_id' => $idJuana 
        ]);

        // Clase para mariano@gym.com (Mariano Silva)
        Clase::create([
            'nombre' => 'Spinning Pro',
            'descripcion' => 'Clase de ciclismo de interior de alta energía con simulación de ruta.',
            'fecha' => now()->addDays(1)->toDateString(),
            'hora' => '08:00:00',
            'capacidad' => 20,
            'estado' => 'PROGRAMADA',
            'sede_id' => $idSur,
            'entrenador_id' => $idMariano
        ]);

        // Clase para valentina@gym.com (Valentina Russo)
        Clase::create([
            'nombre' => 'Clase de Yoga Flow',
            'descripcion' => 'Yoga relajante para todos los niveles y control de la respiración.',
            'fecha' => now()->addDays(1)->toDateString(),
            'hora' => '10:00:00',
            'capacidad' => 15,
            'estado' => 'PROGRAMADA',
            'sede_id' => $idCentral,
            'entrenador_id' => $idValentina
        ]);

        // Clase para facundo@gym.com (Facundo Diaz)
        Clase::create([
            'nombre' => 'Boxeo Recreativo',
            'descripcion' => 'Descarga tensiones y aprende las técnicas básicas de golpeo.',
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '20:00:00',
            'capacidad' => 12,
            'estado' => 'PROGRAMADA',
            'sede_id' => $idNorte,
            'entrenador_id' => $idFacundo
        ]);

        // Clase sin entrenador asignado
        Clase::create([
            'nombre' => 'Functional Training',
            'descripcion' => 'Circuitos dinámicos para mejorar la agilidad, coordinación y fuerza.',
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '17:00:00',
            'capacidad' => 15,
            'estado' => 'PROGRAMADA',
            'sede_id' => $idSur,
            'entrenador_id' => null
        ]);

        // clases pasadas

        Clase::create([
                'nombre' => 'Crossfit Principiantes',
                'descripcion' => 'Sesión introductoria realizada ayer.',
                'fecha' => now()->subDays(1)->toDateString(),
                'hora' => '12:00:00',
                'capacidad' => 10,
                'estado' => 'PROGRAMADA',
                'sede_id' => $idNorte,
                'entrenador_id' => $idJuana
            ]);

            Clase::create([
                'nombre' => 'Spinning Endurance',
                'descripcion' => 'Clase de resistencia cardiovascular de ayer.',
                'fecha' => now()->subDays(1)->toDateString(),
                'hora' => '09:00:00',
                'capacidad' => 20,
                'estado' => 'PROGRAMADA',
                'sede_id' => $idSur,
                'entrenador_id' => $idMariano
            ]);

            Clase::create([
                'nombre' => 'Yoga de la Mañana',
                'descripcion' => 'Estiramiento básico matutino realizado ayer.',
                'fecha' => now()->subDays(1)->toDateString(),
                'hora' => '07:30:00',
                'capacidad' => 15,
                'estado' => 'PROGRAMADA',
                'sede_id' => $idCentral,
                'entrenador_id' => $idValentina
            ]);

            Clase::create([
                'nombre' => 'Sparring Controlado',
                'descripcion' => 'Práctica de boxeo técnico de la semana pasada.',
                'fecha' => now()->subDays(2)->toDateString(),
                'hora' => '19:00:00',
                'capacidad' => 12,
                'estado' => 'PROGRAMADA',
                'sede_id' => $idNorte,
                'entrenador_id' => $idFacundo
            ]);

            Clase::create([
                'nombre' => 'Zumba Cardio Mix',
                'descripcion' => 'Ritmos y baile aeróbico realizado ayer.',
                'fecha' => now()->subDays(1)->toDateString(),
                'hora' => '16:00:00',
                'capacidad' => 25,
                'estado' => 'PROGRAMADA',
                'sede_id' => $idCentral,
                'entrenador_id' => $idCamila
            ]);
    }
}