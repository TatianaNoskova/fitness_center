<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Socio;
use Illuminate\Database\Seeder;

class InscripcionSeeder extends Seeder
{
    public function run(): void
    {
        $clases = Clase::all();
        if ($clases->isEmpty()) return;

        $socios = Socio::with('user')->where('estado', 'ACTIVO')->get();
        if ($socios->isEmpty()) return;

        $vips = $socios->where('categoria', 'VIP');
        $regulares = $socios->whereIn('categoria', ['NORMAL', 'ESTUDIANTE']);

        $claseCrossfit = $clases->where('nombre', 'Crossfit Intenso')->first();
        if ($claseCrossfit) {
            foreach ($vips as $vip) {
                $claseCrossfit->socios()->attach($vip->user_id, [
                    'fecha_inscripcion' => now()->toDateString(),
                    'asistencia' => 'PENDIENTE'
                ]);
                $claseCrossfit->decrement('capacidad');
            }
        }

        foreach ($regulares as $socio) {
            $claseEnSedePropia = $clases->where('sede_id', $socio->sede_id)
                                        ->where('estado', 'PROGRAMADA')
                                        ->where('nombre', '!=', 'Functional Training')
                                        ->first();

            if ($claseEnSedePropia && $claseEnSedePropia->capacidad > 0) {
                $claseEnSedePropia->socios()->attach($socio->user_id, [
                    'fecha_inscripcion' => now()->toDateString(),
                    'asistencia' => 'PENDIENTE'
                ]);
                $claseEnSedePropia->capacidad -= 1;
                $claseEnSedePropia->save();
            }
        }

        // =======================================================
        // 2. INSCRIPCION A LAS CLASES PASADAS
        // =======================================================
        $clasesPasadas = $clases->where('fecha', '<', now()->toDateString())
                                ->where('estado', 'PROGRAMADA');

        foreach ($clasesPasadas as $clasePasada) {
            // VIP
            foreach ($vips as $vip) {
                $clasePasada->socios()->attach($vip->user_id, [
                    'fecha_inscripcion' => now()->subDays(3)->toDateString(),
                    'asistencia' => 'PENDIENTE'
                ]);
            }

            // Normal
            foreach ($regulares as $socio) {
                if ($socio->sede_id == $clasePasada->sede_id) {
                    $clasePasada->socios()->attach($socio->user_id, [
                        'fecha_inscripcion' => now()->subDays(3)->toDateString(),
                        'asistencia' => 'PENDIENTE'
                    ]);
                }
            }
        }
    }
}