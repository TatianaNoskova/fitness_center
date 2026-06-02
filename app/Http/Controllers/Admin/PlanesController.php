<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan; // Твоя модель обычных тарифов клуба

class PlanesController extends Controller
{
    /**
     * Отображение стандартных тарифных планов клуба.
     */
    public function index()
    {
        // Берем из базы только стандартные абонементы
        $planes = Plan::all();

        // Возвращаем их на чистую страницу тарифов
        return view('planes', compact('planes'));
    }
}