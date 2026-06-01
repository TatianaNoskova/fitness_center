<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan; // Подключаем твою модель тарифов
use App\Patterns\Composite\ClaseComposite; // Наш Композит (Пакет услуг)
use App\Patterns\Composite\ClaseLeaf;      // Наш Лист (Одиночная услуга)

class PlanesController extends Controller
{
    /**
     * Отображение страницы тарифов и дополнительных пакетов услуг.
     */
    public function index()
    {
        // 1. Извлекаем все стандартные безлимитные тарифы клуба из базы данных
        $planes = Plan::all();

        // 2. РЕАЛИЗАЦИЯ PATTERN COMPOSITE
        // Создаем главный контейнер-композит (наш премиальный пакет)
        $comboServicios = new ClaseComposite("Премиум-пакет «Полное восстановление»");

        // Создаем отдельные независимые услуги (Листья / Leaf) с их ценами
        $masaje = new ClaseLeaf("Спортивный восстанавливающий массаж", 35.00);
        $entrenador = new ClaseLeaf("Сессия с персональным тренером", 45.00);
        $nutricionista = new ClaseLeaf("Консультация нутрициолога", 20.00);

        // Наполняем наш Композит элементами (древовидная структура)
        $comboServicios->agregar($masaje);
        $comboServicios->agregar($entrenador);
        $comboServicios->agregar($nutricionista);

        // 3. Передаем в Blade-шаблон и стандартные планы, и объект паттерна Composite
        // Убедись, что твоя view находится по этому адресу (например, resources/views/planes.blade.php)
        // Если папка другая (например, resources/views/admin/planes.blade.php), замени на 'admin.planes'
        return view('planes', compact('planes', 'comboServicios'));
    }
}