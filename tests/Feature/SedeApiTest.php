<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Вот теперь это корректно найдет класс из файла №1
use App\Models\Sede;
use PHPUnit\Framework\Attributes\Test;

class SedeApiTest extends TestCase
{
    use RefreshDatabase; // Автоматически очищает тестовую базу данных

    /** @test */
    public function it_can_list_all_sedes_through_the_api()
    {
        // 1. GIVEN
        Sede::create([
            'nombre' => 'Sede Palermo Test',
            'direccion' => 'Av. Santa Fe 1234',
            'telefono' => '11223344',
            'email' => 'palermo@test.com'
        ]);

        // 2. WHEN
        $response = $this->getJson('/api/sedes');

        // 3. THEN 
        $response->assertStatus(200); 
        
        $response->assertJsonFragment([
            'nombre' => 'Sede Palermo Test'
        ]);
    }
}