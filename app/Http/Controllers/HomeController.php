<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $sedes = Sede::all();
        $plans = Plan::where('estado', 'ACTIVO')->get();

        // widget meteorológico
        $weatherData = Cache::remember('weather_data', 1800, function () {
            try {
                // Buenos Aires
                $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => -34.60, // 13.75
                    'longitude' => -58.38, // 100.51 Bangkok
                    'current' => 'temperature_2m,weather_code',
                    'timezone' => 'auto'
                ]);

                if ($response->successful()) {
                    return $response->json()['current'];
                }
            } catch (\Exception $e) {
                return null;
            }
            return null;
        });

        // Recomendaciones dinamicos basados en el clima
        $recommendation = [
            'title' => '¡Buen día!',
            'text' => 'Te esperamos hoy en nuestras instalaciones para entrenar a tope.',
            'bg' => 'bg-slate-50 border border-slate-100',
            'icon' => 'bi-emoji-smile-fill text-slate-400'
        ];

        if ($weatherData) {
            $code = $weatherData['weather_code'];
            $temp = $weatherData['temperature_2m'];

            if (in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82])) {
                // Llueva
                $recommendation = [
                    'title' => '¿Está lloviendo afuera? 🌧️',
                    'text' => '¡No dejes que el clima te detenga! En nuestros clubes el ambiente está perfecto. Ven a una clase de Yoga o relájate en nuestra zona de spa.',
                    'bg' => 'bg-blue-50/70 border border-blue-100',
                    'icon' => 'bi-cloud-rain-fill text-blue-500'
                ];
            } elseif ($temp >= 28) {
                // Hace calor
                $recommendation = [
                    'title' => '¡Hace calor afuera! ☀️',
                    'text' => 'Recuerda mantenerte hidratado. El aire acondicionado de nuestras salas ya está encendido. ¡O ven a darte un chapuzón en la piscina!',
                    'bg' => 'bg-amber-50/70 border border-amber-100',
                    'icon' => 'bi-thermometer-high text-amber-600'
                ];
            } elseif ($temp <= 12) {
                // hace frio
                $recommendation = [
                    'title' => 'El día está fresco ❄️',
                    'text' => '¡El mejor calentamiento te espera en el gym! Ven a activar tu cuerpo en nuestra zona de CrossFit o en las cintas de correr.',
                    'bg' => 'bg-sky-50/70 border border-sky-100',
                    'icon' => 'bi-thermometer-low text-sky-500'
                ];
            } else {
                // El clima es ideal
                $recommendation = [
                    'title' => '¡El clima está perfecto! 😎',
                    'text' => 'Un día ideal para cumplir tus objetivos. Elige tu plan preferido abajo, regístrate y empieza hoy mismo.',
                    'bg' => 'bg-emerald-50/70 border border-emerald-100',
                    'icon' => 'bi-lightning-charge-fill text-emerald-500'
                ];
            }
        }

    
        return view('home', compact('sedes', 'plans', 'weatherData', 'recommendation'));
    }
}