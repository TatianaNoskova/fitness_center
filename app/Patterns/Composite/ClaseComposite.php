<?php

namespace App\Patterns\Composite;

class ClaseComposite implements ClaseComponent
{
    protected string $nombreCombo;
    protected array $componentes = [];

    public function __construct(string $nombreCombo)
    {
        $this->nombreCombo = $nombreCombo;
    }

    public function agregar(ClaseComponent $componente)
    {
        $this->componentes[] = $componente;
    }

    public function getNombre(): string
    {
        return $this->nombreCombo . " (Paquete de " . count($this->componentes) . " servicios)";
    }

    public function getPrecio(): float
    {
        $totalPrecio = 0;
        // Паттерн Composite обходит дерево и прозрачно суммирует цены
        foreach ($this->componentes as $componente) {
            $totalPrecio += $componente->getPrecio();
        }
        return $totalPrecio;
    }
}