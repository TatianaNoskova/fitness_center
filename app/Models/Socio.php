<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Strategies\SocioNormalStrategy;
use App\Strategies\SocioEstudianteStrategy;
use App\Strategies\SocioVipStrategy;

class Socio extends Model
{
    use HasFactory;

    // Указываем его родную таблицу
    protected $table = 'socios'; 

    protected $fillable = ['user_id', 'sede_id', 'fecha_alta', 'estado'];

        /**
     * Метод паттерна Strategy для расчета стоимости
     */
    public function obtenerPrecioCuota(string $tipoAbono, float $precioBase): float
    {
        // Выбираем стратегию в зависимости от типа абонемента
        $strategy = match ($tipoAbono) {
            'ESTUDIANTE' => new SocioEstudianteStrategy(),
            'VIP'        => new SocioVipStrategy(),
            default      => new SocioNormalStrategy(),
        };

        // Делегируем расчет выбранной стратегии
        return $strategy->calcular($precioBase);
    }
    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}