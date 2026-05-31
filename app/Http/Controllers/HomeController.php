<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Берем все филиалы и тарифы из базы данных
        $sedes = Sede::all();
        $plans = Plan::all();

        // Отдаем их в шаблон home.blade.php
        return view('home', compact('sedes', 'plans'));
    }
}