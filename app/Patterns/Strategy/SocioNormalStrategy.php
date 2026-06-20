<?php

namespace App\Patterns\Strategy;

class SocioNormalStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice; 
    }
}