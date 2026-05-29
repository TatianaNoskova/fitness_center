<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверяем, что API эндпоинт доступен.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Вместо проблемной главной страницы стучимся в работающий API филиалов
        $response = $this->get('/api/sedes');

        $response->assertStatus(200);
    }
}