<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    public function run(): void
    {
        Sede::create([
            'nombre' => 'Sede Central',
            'direccion' => 'Av. Fantasía 3200, CABA',
            'telefono' => '+54 11 4567-8901',
            'email' => 'centro@fitnessgym.com'
        ]);

        Sede::create([
            'nombre' => 'Sede Norte',
            'direccion' => 'Calle de los Magos, 4100, CABA',
            'telefono' => '+54 11 3372-0199',
            'email' => 'norte@fitnessgym.com'
        ]);

        Sede::create([
            'nombre' => 'Sede Sur',
            'direccion' => 'Av. Fortuna, 1200, CABA',
            'telefono' => '+54 11 7502-3519',
            'email' => 'sur@fitnessgym.com'
        ]);
    }
}
