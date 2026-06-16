@extends('layouts.app') {{-- Убедитесь, что имя лейаута совпадает с вашей системой --}}

@section('content')
<div class="py-6 bg-slate-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Encabezado del Dashboard --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                    Panel del Entrenador
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Gestiona tus clases asignadas y el control de asistencia de los socios.
                </p>
            </div>
        </div>

        {{-- Bloque de Alertas (Mensajes de éxito o error) --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-sm">
                <span class="font-bold">✓</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2 shadow-sm">
                <span class="font-bold">✕</span> {{ session('error') }}
            </div>
        @endif

        {{-- Listado de Clases --}}
        <div class="space-y-4">
            @forelse($clases as $clase)
                @php
                    // Validamos si la clase ya inició o pasó (Punto 1)
                    $fechaClase = \Carbon\Carbon::parse($clase->fecha . ' ' . $clase->hora);
                    $yaIniciada = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaClase);
                @endphp

                {{-- ИСПРАВЛЕНО: Убран атрибут open, теперь все классы закрыты по умолчанию --}}
                <details class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    
                    {{-- Cabecera de la Clase (Siempre visible) --}}
                    <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition list-none select-none border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            {{-- Flecha indicadora con animación de rotación al abrir --}}
                            <span class="transition-transform duration-200 group-open:rotate-180 text-slate-400 text-xs">
                                ▼
                            </span>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $clase->nombre }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 flex flex-wrap gap-x-3 gap-y-1-5">
                                    <span class="inline-flex items-center gap-1">
                                        📅 {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        🕒 {{ \Carbon\Carbon::parse($clase->hora)->format('H:i') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        📍 {{ $clase->sede->nombre ?? 'Sin sede asignada' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        {{-- Contador de Socios Inscritos --}}
                        <div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                Inscritos: {{ $clase->socios->count() }}
                            </span>
                        </div>
                    </summary>

                    {{-- Contenido Desplegable: Tabla de Alumnos --}}
                    <div class="p-4 bg-slate-50/50">
                        @if($clase->socios->isEmpty())
                            <div class="text-center py-6 text-sm text-slate-500 italic">
                                No hay alumnos inscritos en esta clase todavía.
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold tracking-wider">
                                        <tr>
                                            <th class="py-3 px-4">Alumno</th>
                                            <th class="py-3 px-4 text-center">Estado Actual</th>
                                            <th class="py-3 px-4 text-right">Tomar Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 text-slate-700">
                                        @foreach($clase->socios as $socio)
                                            @php
                                                $status = $socio->pivot->asistencia ?? 'PENDIENTE';
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 transition">
                                                
                                                {{-- Nombre y Apellido del Socio --}}
                                                <td class="py-3 px-4 font-medium text-slate-900 whitespace-nowrap">
                                                    {{ $socio->user->nombre }} {{ $socio->user->apellido }}
                                                </td>
                                                
                                                {{-- Badge del estado de asistencia --}}
                                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                                    @if($status === 'SI')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                            ● Asistió
                                                        </span>
                                                    @elseif($status === 'NO')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                                            ● Faltó
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                            ● Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                
                                                {{-- Botones de acción dinámicos --}}
                                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                                    @if(!$yaIniciada)
                                                        <span class="text-xs text-slate-400 italic">No disponible hasta el inicio</span>
                                                    @else
                                                        <form action="{{ url('/entrenador/clases/'.$clase->id.'/socio/'.$socio->user_id.'/asistencia') }}" method="POST" class="inline-flex gap-2 m-0">
                                                            @csrf
                                                            
                                                            {{-- Botón Presente --}}
                                                            <button type="submit" name="asistio" value="SI" 
                                                                {{ $status === 'SI' ? 'disabled' : '' }}
                                                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border
                                                                {{ $status === 'SI' 
                                                                    ? 'bg-slate-100 text-slate-400 cursor-not-allowed border-slate-200' 
                                                                    : 'bg-emerald-500 hover:bg-emerald-600 text-white border-transparent' }}"
                                                                title="Marcar Presente">
                                                                ✓ Presente
                                                            </button>

                                                            {{-- Botón Ausente --}}
                                                            <button type="submit" name="asistio" value="NO" 
                                                                {{ $status === 'NO' ? 'disabled' : '' }}
                                                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border
                                                                {{ $status === 'NO' 
                                                                    ? 'bg-slate-100 text-slate-400 cursor-not-allowed border-slate-200' 
                                                                    : 'bg-rose-500 hover:bg-rose-600 text-white border-transparent' }}"
                                                                title="Marcar Ausente">
                                                                ✗ Ausente
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </details>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-slate-300 text-slate-500 shadow-sm">
                    <p class="text-base font-medium text-slate-600">No tienes clases programadas asignadas.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection