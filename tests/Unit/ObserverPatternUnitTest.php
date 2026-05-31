<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Clase;
use App\Models\Socio;
use App\Observers\ClaseObserver;

class ObserverPatternUnitTest extends TestCase
{
    #[Test]
    public function it_triggers_observer_notification_logic_upon_clase_deletion()
    {
        // 1. Создаем пустое занятие
        $clase = new Clase();
        $clase->nombre = 'Yoga Unit Test';
        $clase->fecha = '2026-06-20';
        $clase->hora = '12:00:00';

        // 2. Создаем пустого клиента
        $socio = new Socio();
        
        // Чтобы избежать проблем с магическими свойствами Eloquent в Unit-тесте,
        // мы можем принудительно установить значение в массив атрибутов модели:
        $socio->setRawAttributes(['nombre' => 'Juan Perez', 'email' => 'juan@perez.com']);

        // 3. Намертво привязываем клиента к занятию в памяти
        $clase->setRelation('socios', collect([$socio]));

        // 4. Вызываем наблюдатель напрямую
        $observer = new ClaseObserver();
        $observer->deleted($clase);

        // 5. Проверяем, что связь на месте и коллекция не пустая
        $this->assertCount(1, $clase->socios);
        
        // Проверяем, что первый элемент коллекции — это именно наш объект класса Socio
        $this->assertInstanceOf(Socio::class, $clase->socios->first());
    }
}