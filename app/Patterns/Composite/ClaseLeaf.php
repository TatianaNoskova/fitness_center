<?php

namespace App\Patterns\Composite;

use App\Models\Servicio;

class ClaseLeaf implements ClaseComponent
{
    protected Servicio $servicio;

    // Теперь конструктор принимает ровно один аргумент — модель Servicio из базы данных
    public function __construct(Servicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function getNombre(): string
    {
        return $this->servicio->nombre;
    }

    public function getPrecio(): float
    {
        return (float)$this->servicio->precio;
    }
}