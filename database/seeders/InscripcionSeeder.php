<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\User;
use App\Models\Socio;
use Illuminate\Database\Seeder;

class InscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Получаем все занятия с их филиалами
        $clases = Clase::all();
        if ($clases->isEmpty()) return;

        // 2. Получаем всех клиентов из таблицы socios вместе с их пользователями
        $socios = Socio::with('user')->where('estado', 'ACTIVO')->get();
        if ($socios->isEmpty()) return;

        // Разделяем клиентов на группы по бизнес-логике
        $vips = $socios->where('categoria', 'VIP');
        $regulares = $socios->whereIn('categoria', ['NORMAL', 'ESTUDIANTE']);

        // =======================================================
        // ЛОГИКА 1: Записываем VIP-клиентов (им можно в любые филиалы)
        // =======================================================
        // Закинем VIP-ов на Кроссфит к Хуане в Sede Norte
        $claseCrossfit = $clases->where('nombre', 'Crossfit Intenso')->first();
        if ($claseCrossfit) {
            foreach ($vips as $vip) {
                // Записываем через user_id, так как связь завязана на него
                $claseCrossfit->socios()->attach($vip->user_id, [
                    'fecha_inscripcion' => now()->toDateString(),
                    'asistencia' => 'PENDIENTE'
                ]);
                $claseCrossfit->decrement('capacidad');
            }
        }

        // =======================================================
        // ЛОГИКА 2: Записываем обычных клиентов СТРОГО в их домашние филиалы
        // =======================================================
        foreach ($regulares as $socio) {
            // Ищем будущие занятия, которые проходят ИМЕННО в домашнем филиале этого клиента
            $claseEnSedePropia = $clases->where('sede_id', $socio->sede_id)
                                        ->where('nombre', '!=', 'Functional Training') // Оставим одно занятие пустым для тестов
                                        ->first();

            // Если в его филиале есть занятие и там есть места — записываем
            if ($claseEnSedePropia && $claseEnSedePropia->capacidad > 0) {
                $claseEnSedePropia->socios()->attach($socio->user_id, [
                    'fecha_inscripcion' => now()->toDateString(),
                    'asistencia' => 'PENDIENTE'
                ]);
                
                // Обновляем вместимость в памяти, чтобы не переполнить класс при итерации
                $claseEnSedePropia->capacidad -= 1;
                $claseEnSedePropia->save();
            }
        }
    }
}