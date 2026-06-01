@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestionar Clases (Занятия)</h1>
        <p class="text-slate-500 mt-1">Панель управления расписанием тренировок фитнес-центра.</p>
    </div>
    <div class="text-xs font-bold text-slate-400 bg-slate-100 px-4 py-2 rounded-xl border border-slate-200">
        <i class="bi bi-cpu-fill text-amber-500 mr-1"></i> OBSERVER PATTERN ACTIVE
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-2xl shadow-sm">
        <div class="flex">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg mr-2"></i>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

{{-- ==================== COMPOSITE PATTERN DISPLAY ==================== --}}
<div class="mb-8 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div class="space-y-1">
        <div class="flex items-center space-x-2">
            <span class="bg-indigo-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                <i class="bi bi-diagram-3-fill mr-1"></i> Composite Pattern Active
            </span>
            <h4 class="text-sm font-bold text-indigo-900 uppercase tracking-wide">
                Módulo de Combos Promocionales (Estructura en Árbol)
            </h4>
        </div>
        {{-- Динамический вызов метода getNombre() нашего Композита --}}
        <p class="text-base font-bold text-slate-800 pt-1">
            <i class="bi bi-box-seam text-indigo-500 mr-1.5"></i> {{ $comboCurso->getNombre() }}
        </p>
        <p class="text-xs text-slate-400">
            Паттерн прозрачно группирует отдельные занятия (`Leaf`) в единый пакет (`Composite`), опрашивая дочерние элементы.
        </p>
    </div>
    <div class="bg-white px-5 py-3 rounded-xl border border-indigo-100 text-center shadow-xs min-w-[140px]">
        <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold block">Cupos Totales</span>
        {{-- Динамический вызов метода getCapacidad() нашего Композита --}}
        <span class="text-2xl font-black text-indigo-600">
            {{ $comboCurso->getCapacidad() }}
        </span>
        <span class="text-xs text-slate-400 font-medium block">asistentes</span>
    </div>
</div>
{{-- ==================== END COMPOSITE PATTERN ==================== --}}

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Horarios Disponibles</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <th class="p-4">Clase / Deporte</th>
                    <th class="p-4">Sede / Filial</th>
                    <th class="p-4">Fecha y Hora</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-600">
                @forelse($clases as $clase)
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-bold text-slate-900">{{ $clase->nombre }}</td>
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                {{ $clase->sede ? $clase->sede->nombre : 'Sin Sede' }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500">
                            <i class="bi bi-calendar3 mr-1 text-slate-400"></i> {{ $clase->fecha }} 
                            <span class="mx-1 text-slate-300">|</span> 
                            <i class="bi bi-clock mr-1 text-slate-400"></i> {{ $clase->hora }}
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('admin.clases.destroy', $clase->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta clase? Se activará el Observer.');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs px-4 py-2 rounded-xl inline-flex items-center transition-colors border border-rose-100 shadow-sm">
                                    <i class="bi bi-trash3-fill mr-1.5"></i> Cancelar Clase
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400">
                            No hay clases programadas en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection