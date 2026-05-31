@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Planes de Membresía (Тарифы)</h1>
        <p class="text-slate-500 mt-1">Доступные сетки абонементов, стоимость и длительность планов.</p>
    </div>
    <button class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-plus-circle me-2"></i> Crear Nuevo Plan
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($planes as $plan)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
        <div>
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-bold text-slate-900">{{ $plan->nombre }}</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                    {{ $plan->duracion }} meses
                </span>
            </div>
            <p class="text-slate-500 text-sm mt-3 h-12 overflow-hidden">{{ $plan->descripcion }}</p>
            
            <div class="mt-4 pt-4 border-t border-slate-50">
                <span class="text-3xl font-black text-slate-900">${{ number_format($plan->precio, 0, ',', '.') }}</span>
                <span class="text-slate-400 text-sm">/ total</span>
            </div>
        </div>

        <div class="mt-6 space-y-2">
            <button class="w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-medium rounded-xl transition">
                Editar Plan
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white p-8 rounded-2xl border border-slate-100 text-center text-slate-400">
        No hay planes configurados.
    </div>
    @endforelse
</div>
@endsection