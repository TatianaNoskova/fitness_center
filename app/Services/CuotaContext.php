<?php

namespace App\Services;

class CuotaContext
{
    private $strategy;

    /**
     * Determinamos strategy, ej.: Normal, Estudiante, Vip)
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Redirigimos el cálculo del costo a la estrategia seleccionada
     */
    public function calcularCuota(float $precioBase): float
    {
    
        if (!$this->strategy) {
            return $precioBase;
        }

        return $this->strategy->calcular($precioBase);
    }
}
