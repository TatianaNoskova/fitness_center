<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Sede;
use PHPUnit\Framework\Attributes\Test;

class SocioApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_socio_through_the_api()
    {
        // 1. GIVEN: Создаем тестовый филиал со всеми его обязательными полями
        $sede = Sede::create([
            'nombre' => 'Sede Belgrano',
            'direccion' => 'Av. Cabildo 2000',
            'telefono' => '1144556677',
            'email' => 'belgrano@gym.com'
        ]);

        // Данные для отправки: теперь со всеми полями, которые контроллер ждет от фронтенда
        $data = [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'dni' => '39888777',
            'telefono' => '1122334455', // <-- ВОТ ОН, НАШ СПАСИТЕЛЬНЫЙ ТЕЛЕФОН!
            'email' => 'juan.perez@example.com',
            'password' => 'secret123',
            'sede_id' => $sede->id,
            'fecha_alta' => '2026-05-28',
            'estado' => 'ACTIVO'
        ];

        // 2. WHEN: Делаем POST-запрос на создание
        $response = $this->postJson('/api/socios', $data);

        // Смотрим, какая именно деталь сломалась внутри 500 ошибки:
        $response->dump(); 

        // 3. THEN: Проверяем статус 201 Created
        $response->assertStatus(201);

        // Проверяем, что студент зафиксирован в базе данных
        $this->assertDatabaseHas('socios', [
            'sede_id' => $sede->id,
            'estado' => 'ACTIVO'
        ]);
    }
}