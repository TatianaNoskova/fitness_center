@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12">
    <div class="bg-white p-10 rounded-3xl border border-slate-300/30 shadow-sm w-full max-w-md space-y-8">
        
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold tracking-tight text-[#002d55]">¡Hola de nuevo!</h2>
            <p class="text-xs text-slate-400 uppercase tracking-widest">Ingresa a tu cuenta de Fitness Club</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-[#eef2f5]/50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#002d55] focus:bg-white transition duration-300">
                </div>
                @error('email')
                    <span class="text-xs text-[#d40839] font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Contraseña</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] text-slate-400 hover:text-[#ff9a01] transition">¿La olvidaste?</a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password" type="password" name="password" required
                        class="w-full pl-11 pr-4 py-3.5 bg-[#eef2f5]/50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#002d55] focus:bg-white transition duration-300">
                </div>
                @error('password')
                    <span class="text-xs text-[#d40839] font-medium mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="h-4 w-4 rounded border-slate-300 text-[#002d55] focus:ring-[#002d55]">
                <label for="remember_me" class="ml-2 text-xs text-slate-400">Recordarme en este dispositivo</label>
            </div>

            <div>
                <button type="submit" 
                    class="w-full bg-[#002d55] text-white py-4 px-4 text-xs font-bold tracking-widest uppercase hover:bg-[#001e3a] transition rounded-xl shadow-sm focus:outline-none">
                    Iniciar Sesión &rarr;
                </button>
            </div>
        </form>

        <div class="text-center pt-2">
            <p class="text-xs text-slate-400">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="font-bold text-[#ff9a01] hover:underline ml-1">Regístrate gratis</a>
            </p>
        </div>

    </div>
</div>
@endsection