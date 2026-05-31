<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Запускаем строго по порядку, чтобы не нарушать связи foreign keys!
        $this->call([
            SedeSeeder::class,
            PlanSeeder::class,
            UserSeeder::class,
            ClaseSeeder::class,
        ]);
    }
}