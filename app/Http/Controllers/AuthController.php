<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Показать форму логина
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register'); 
    }

    // Обработка логина
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Получаем роль вошедшего пользователя
            $role = strtolower(Auth::user()->rol);

            // Умная переадресация в зависимости от роли
            switch ($role) {
                case 'administrador':
                    return redirect()->intended('/dashboard');
                case 'entrenador':
                    return redirect()->to('/entrenador/dashboard');
                case 'socio':
                    return redirect()->to('/socio/dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Обработка легкой регистрации
    public function register(Request $request)
    {
        // 1. Валидируем входящие данные
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // 2. Разделяем строку по первому пробелу
        $parts = explode(' ', trim($data['name']), 2);
        $nombre = $parts[0];
        $apellido = $parts[1] ?? null;

        // 3. Жесткая проверка: если фамилию не ввели, возвращаем ошибку назад в форму
        if (!$apellido) {
            return back()->withErrors(['name' => 'Por favor, ingresa tu nombre y apellido (ej. Benicio Bravo)'])->withInput();
        }

        // 4. Создаем пользователя (по умолчанию это всегда socio)
        $user = User::create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => 'socio',
        ]);

        Auth::login($user);

        // Так как при регистрации создается всегда клиент (socio), сразу шлем его на клиентский дашборд
        return redirect('/socio/dashboard');
    }

    // Выход из системы
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}