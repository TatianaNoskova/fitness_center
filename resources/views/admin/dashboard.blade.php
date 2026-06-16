@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">¡Bienvenida al Panel de Control!</h1>
    <p class="text-slate-500 mt-1">Sistema de Gestion</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sucursales</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Sedes</h3>
                <p class="text-sm text-slate-500 mt-2">Gestionar Sedes</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="bi bi-building text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/sedes-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Gestionar Sedes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Base de datos</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Socios</h3>
                <p class="text-sm text-slate-500 mt-2">Registro de miembros del club, verificación de estados de actividad y tarjetas de membresía.</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="bi bi-people text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/socios-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Ver Clientes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Base de datos</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Entrenadores</h3>
                <p class="text-sm text-slate-500 mt-2">Registro y gestion de entrenadores, vinculación con sedes.</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="bi bi-people text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/entrenadores-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Ver Entrenadores
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ventas</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Planes</h3>
                <p class="text-sm text-slate-500 mt-2">Constructor de abonos, configuración de precios y duración del acceso.</p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i class="bi bi-card-checklist text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/plans-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Ver Planes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Horario</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Clases</h3>
                <p class="text-sm text-slate-500 mt-2">Gestión de entrenamientos, horarios de entrenadores y reservas de clientes.</p>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i class="bi bi-calendar3 text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/clases-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Gestionar Clases
            </a>
        </div>
    </div>

    {{-- КАРТОЧКА НОВОГО РАЗДЕЛА: КОМБО И ДОП. УСЛУГИ (COMPOSITE) --}}
    <div class="border border-black/5 rounded-3xl p-6 flex flex-col justify-between bg-white hover:border-[#002d55]/30 hover:shadow-md transition duration-300 relative group">
        <div>
            <div class="flex justify-between items-start mb-4">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Constructor</span>
                    <h3 class="text-lg font-bold text-slate-900">Servicios Extras (Combos)</h3>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                    <i class="bi bi-diagram-3 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 font-light leading-relaxed">
                Gestión de servicios extras y creación de paquetes combinados complejos.
            </p>
        </div>
        <div class="mt-6">
            <a href="{{ route('composite.index') }}" class="block w-full text-center bg-[#002d55] hover:bg-[#ff9a01] text-white py-2.5 text-xs font-bold tracking-wide uppercase transition duration-300 rounded-xl shadow-xs">
                Configurar Servicios
            </a>
        </div>
    </div>

</div>
@endsection