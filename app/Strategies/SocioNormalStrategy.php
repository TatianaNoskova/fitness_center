<?php

namespace App\Strategies;

class SocioNormalStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice; // Без скидок
    }
}