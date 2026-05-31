<?php

namespace App\Patterns\Strategy;

class SocioVipStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice * 1.5; // + 50% por VIP-status
    }
}