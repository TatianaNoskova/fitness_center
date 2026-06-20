<?php

namespace App\Observers;

use App\Models\Clase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // Импортируем фасад кэша

class ClaseObserver
{
    public function deleted(Clase $clase)
    {
        $mensajeAviso = "¡ATENCIÓN! La clase de '{$clase->nombre}' programada para el día {$clase->fecha} ha sido CANCELADA por la administración.";

       
        Log::warning("[OBSERVER] " . $mensajeAviso);

       
        Cache::put('alerta_clase_cancelada', $mensajeAviso, now()->addMinutes(10));
    }
}