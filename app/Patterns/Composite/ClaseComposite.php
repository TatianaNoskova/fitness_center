<?php

namespace App\Patterns\Composite;

class ClaseComposite implements ClaseComponent
{
    protected string $nombreCurso;
    protected array $componentes = [];

    public function __construct(string $nombreCurso)
    {
        $this->nombreCurso = $nombreCurso;
    }

    public function agregar(ClaseComponent $componente)
    {
        $this->componentes[] = $componente;
    }

    public function getNombre(): string
    {
        return $this->nombreCurso . " (Combo de " . count($this->componentes) . " clases)";
    }

    public function getCapacidad(): int
    {
        $totalCapacidad = 0;
        foreach ($this->componentes as $componente) {
            $totalCapacidad += $componente->getCapacidad();
        }
        return $totalCapacidad;
    }
}