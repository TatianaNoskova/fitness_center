<?php

namespace App\Strategies;

class SocioEstudianteStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice * 0.8; // Скидка 20%
    }
}