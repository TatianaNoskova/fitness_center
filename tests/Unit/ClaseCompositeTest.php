<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Clase;
use App\Strategies\ClaseComposite;

class ClaseCompositeTest extends TestCase
{
    #[Test]
    public function it_calculates_total_capacity_of_a_composite_course_correctly()
    {
        // 1. Создаем два независимых занятия с разной вместимостью
        $clase1 = new Clase(['nombre' => 'Spinning', 'capacidad' => 20]);
        $clase2 = new Clase(['nombre' => 'Crossfit', 'capacidad' => 15]);

        // 2. Создаем компоновщик (наш комплексный пакет курсов)
        $cursoComposite = new ClaseComposite("Пакет VIP Энергия");

        // 3. Объединяем их в дерево
        $cursoComposite->agregar($clase1);
        $cursoComposite->agregar($clase2);

        // 4. Проверяем, что Composite правильно сложил вместимость (20 + 15 = 35)
        $this->assertEquals(35, $cursoComposite->getCapacidad());

        // 5. Проверяем динамическое имя нашей группы объектов
        $this->assertStringContainsString("Combo de 2 clases", $cursoComposite->getNombre());
    }
}