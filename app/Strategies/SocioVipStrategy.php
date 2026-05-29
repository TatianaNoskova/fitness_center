<?php

namespace App\Strategies;

class SocioVipStrategy implements CuotaStrategy
{
    public function calcular(float $basePrice): float
    {
        return $basePrice * 1.5; // Наценка 50% за VIP-статус
    }
}