<?php

namespace App\Patterns\Composite;

interface ClaseComponent
{
    public function getNombre(): string;
    public function getCapacidad(): int;
}