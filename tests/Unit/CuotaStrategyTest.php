<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Socio;
use PHPUnit\Framework\Attributes\Test; // Добавили импорт атрибута

class CuotaStrategyTest extends TestCase
{
    #[Test] // Теперь вместо текстового комментария используется современный атрибут PHP
    public function it_calculates_cuota_correctly_using_different_strategies()
    {
        $socio = new Socio();
        $basePrice = 10000.0;

        $precioNormal = $socio->obtenerPrecioCuota('NORMAL', $basePrice);
        $this->assertEquals(10000.0, $precioNormal);

        $precioEstudiante = $socio->obtenerPrecioCuota('ESTUDIANTE', $basePrice);
        $this->assertEquals(8000.0, $precioEstudiante);

        $precioVip = $socio->obtenerPrecioCuota('VIP', $basePrice);
        $this->assertEquals(15000.0, $precioVip);
    }
}