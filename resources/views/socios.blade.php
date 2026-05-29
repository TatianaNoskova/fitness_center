@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Socios (Клиенты)</h1>
        <p class="text-slate-500 mt-1">Полная база членов фитнес-клуба, привязка к филиалам и статусы карт.</p>
    </div>
    <button class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-person-plus me-2"></i> Registrar Socio
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <th class="py-4 px-6">Socio (Клиент)</th>
                <th class="py-4 px-6">DNI / Документ</th>
                <th class="py-4 px-6">Sede (Филиал)</th>
                <th class="py-4 px-6">Fecha Alta (Регистрация)</th>
                <th class="py-4 px-6 text-center">Estado (Статус)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($socios as $socio)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 px-6">
                    <div class="flex flex-col">
                        <span class="font-semibold text-slate-900">{{ $socio->user->nombre }} {{ $socio->user->apellido }}</span>
                        <span class="text-xs text-slate-400">{{ $socio->user->email }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 font-mono text-xs text-slate-500">
                    {{ $socio->user->dni }}
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                        <i class="bi bi-building me-1 text-slate-400"></i> {{ $socio->sede->nombre ?? 'Sin sede' }}
                    </span>
                </td>
                <td class="py-4 px-6 text-slate-500">
                    {{ \Carbon\Carbon::parse($socio->fecha_alta)->format('d/m/Y') }}
                </td>
                <td class="py-4 px-6 text-center">
                    @if($socio->estado === 'ACTIVO')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            {{ $socio->estado }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                            {{ $socio->estado }}
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-slate-400">No hay socios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection