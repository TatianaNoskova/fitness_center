@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Clases (Занятия)</h1>
        <p class="text-slate-500 mt-1">Расписание тренировок, тренеры и свободные места в реальном времени.</p>
    </div>
    <button class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
        Programar Clase
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($clases as $clase)
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-slate-800">{{ $clase->nombre }}</h3>
                    <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                        {{ \Carbon\Carbon::parse($clase->hora)->format('H:i') }} hs
                    </span>
                </div>
                
                <p class="text-sm text-slate-500 mb-4">
                    {{ $clase->descripcion ?? 'Sin descripción disponible.' }}
                </p>

                <div class="space-y-1.5 text-xs text-slate-600 mb-6 bg-slate-50 p-3 rounded-xl">
                    <div>🏢 <strong class="text-slate-700">Sede:</strong> {{ $clase->sede->nombre ?? 'No asignada' }}</div>
                    <div>👟 <strong class="text-slate-700">Profesor:</strong> {{ $clase->entrenador->name ?? 'Por asignar' }}</div>
                    <div>📅 <strong class="text-slate-700">Fecha:</strong> {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}</div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-xs text-slate-400 mb-1">
                    <span>Capacidad</span>
                    <span class="font-bold text-slate-700">{{ $clase->capacidad }} lugares</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 35%"></div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button class="flex-1 text-center text-xs font-medium py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition border border-slate-200">
                        Modificar
                    </button>
                    <button class="text-center text-xs font-medium py-2 px-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition border border-red-100">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white border border-dashed border-slate-200 rounded-2xl p-12 text-center text-slate-400">
            No hay clases programadas en el sistema.
        </div>
    @endforelse
</div>
@endsection