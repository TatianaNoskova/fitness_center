<?php

namespace App\Strategies;

interface CuotaStrategy
{
    // Метод, который каждая стратегия обязана реализовать по-своему
    public function calcular(float $basePrice): float;
}