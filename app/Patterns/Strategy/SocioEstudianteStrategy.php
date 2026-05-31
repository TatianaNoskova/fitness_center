<?php

namespace App\Patterns\Strategy;

class SocioEstudianteStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice * 0.8; // discuento 20%
    }
}