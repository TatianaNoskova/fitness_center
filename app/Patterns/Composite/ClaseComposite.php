<?php

namespace App\Patterns\Composite;

class ClaseComposite implements ClaseComponent
{
    protected string $nombreCombo;
    protected array $componentes = [];
    protected int $descuento; 

    public function __construct(string $nombreCombo, int $descuento = 0)
    {
        $this->nombreCombo = $nombreCombo;
        $this->descuento = $descuento;
    }

    public function agregar(ClaseComponent $componente)
    {
        $this->componentes[] = $componente;
    }

    public function getNombre(): string
    {
        $texto = $this->nombreCombo . " (Paquete de " . count($this->componentes) . " servicios)";
        
        if ($this->descuento > 0) {
            $texto .= " [-{$this->descuento}%]";
        }
        
        return $texto;
    }

    public function getPrecio(): float
    {
        $totalPrecio = 0;

        
        foreach ($this->componentes as $componente) {
            $totalPrecio += $componente->getPrecio();
        }

        if ($this->descuento > 0) {
            $суммаСкидки = $totalPrecio * ($this->descuento / 100);
            $totalPrecio -= $суммаСкидки;
        }

        return $totalPrecio;
    }
}