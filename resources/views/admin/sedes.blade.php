@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Nuestras Sedes (Филиалы)</h1>
        <p class="text-slate-500 mt-1">Список активных филиалов фитнес-клуба, их локации и количество студентов.</p>
    </div>
    <button class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-plus-lg me-2"></i> Registrar Nueva Sede
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <th class="py-4 px-6">Nombre (Название)</th>
                <th class="py-4 px-6">Dirección (Адрес)</th>
                <th class="py-4 px-6">Contacto (Связь)</th>
                <th class="py-4 px-6 text-center">Socios Activos</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($sedes as $sede)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 px-6 font-semibold text-slate-900">{{ $sede->nombre }}</td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center text-slate-600">
                        <i class="bi bi-geo-alt text-slate-400 me-2"></i> {{ $sede->direccion }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex flex-col space-y-0.5">
                        <span class="text-slate-700"><i class="bi bi-telephone text-slate-400 me-1"></i> {{ $sede->telefono }}</span>
                        <span class="text-xs text-slate-400"><i class="bi bi-envelope text-slate-400 me-1"></i> {{ $sede->email }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        <i class="bi bi-person-check me-1"></i> {{ $sede->socios->count() }} alumnos
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-8 text-center text-slate-400">No hay sedes registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection