<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
{
    // Оставляем пока только 'socios', так как эту связь мы уже проверили, и она точно работает!
    return response()->json(Sede::with(['socios'])->get(), 200);
}
}