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
        // Динамически берем имя из поля в базе данных
        return $this->servicio->nombre;
    }

    public function getPrecio(): float
    {
        // Динамически берем цену из поля в базе данных и приводим к числу
        return (float)$this->servicio->precio;
    }
}