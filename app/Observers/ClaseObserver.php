<?php

namespace App\Observers;

use App\Models\Clase;
use Illuminate\Support\Facades\Log;

class ClaseObserver
{
    /**
     * Срабатывает АВТОМАТИЧЕСКИ при удалении класса.
     */
    public function deleted(Clase $clase)
    {
        // Формируем понятное сообщение об отмене
        $mensajeAviso = "¡ATENCIÓN! La clase de '{$clase->nombre}' programada para el el día {$clase->fecha} a las {$clase->hora} ha sido CANCELADA por la administración.";

        // Записываем в лог (для порядка)
        Log::warning("[OBSERVER] " . $mensajeAviso);

        // Магия для браузера: сохраняем уведомление в глобальную сессию Laravel.
        // Любой контроллер или Blade-шаблон сможет отобразить его при следующем запросе.
        session()->flash('alerta_clase_cancelada', $mensajeAviso);
    }
}