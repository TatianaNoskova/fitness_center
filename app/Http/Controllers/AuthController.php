<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = strtolower(Auth::user()->rol);

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

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $parts = explode(' ', trim($data['name']), 2);
        $nombre = $parts[0];
        $apellido = $parts[1] ?? null;

        if (!$apellido) {
            return back()->withErrors(['name' => 'Por favor, ingresa tu nombre y apellido (ej. Benicio Bravo)'])->withInput();
        }

        $user = User::create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => 'socio',
        ]);

        Auth::login($user);

        return redirect('/socio/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}