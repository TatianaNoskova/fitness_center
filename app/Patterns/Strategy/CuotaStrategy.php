<?php

namespace App\Patterns\Strategy;

interface CuotaStrategy
{
    public function calcular(float $basePrice): float;
}