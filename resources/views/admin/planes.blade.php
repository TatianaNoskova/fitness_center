@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Planes de Membresía (Тарифы)</h1>
        <p class="text-slate-500 mt-1">Доступные сетки абонементов и интерактивная демонстрация паттерна Composite.</p>
    </div>
    <button class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-plus-circle me-2"></i> Crear Nuevo Plan
    </button>
</div>

{{-- ==================== ДЕМОНСТРАЦИЯ PATTERN COMPOSITE ==================== --}}
<div class="mb-8 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 p-6 rounded-2xl shadow-sm">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="space-y-1 flex-1">
            <div class="flex items-center space-x-2">
                <span class="bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                    <i class="bi bi-diagram-3-fill mr-1"></i> Pattern Composite Activo
                </span>
                <h4 class="text-sm font-bold text-emerald-900 uppercase tracking-wide">
                    Servicios Complejos (Вывод Композита)
                </h4>
            </div>
            
            {{-- Динамическое имя Композита --}}
            <p class="text-xl font-extrabold text-slate-900 pt-1">
                <i class="bi bi-gift-fill text-emerald-500 mr-1.5"></i> {{ $comboServicios->getNombre() }}
            </p>
            
            <p class="text-xs text-slate-500 max-w-2xl">
                Паттерн объединяет отдельные услуги (Листья) в дерево. Метод <code>getPrecio()</code> рекурсивно суммирует стоимость всех вложенных компонентов.
            </p>
            
            {{-- Динамический вывод состава комбо через связи --}}
            <div class="pt-2 flex flex-wrap gap-2">
                @forelse($comboModel->servicios as $s)
                    <span class="bg-emerald-100/80 border border-emerald-200 text-emerald-800 text-xs px-3 py-1 rounded-lg font-medium shadow-2xs">
                        <i class="bi bi-check-circle-fill text-emerald-500 mr-1"></i> {{ $s->nombre }} (${{ number_format($s->precio, 0) }})
                    </span>
                @empty
                    <span class="text-xs text-slate-400 italic">El combo está vacío. ¡Agrega servicios abajo!</span>
                @endforelse
            </div>
        </div>

        {{-- Динамический расчет цены Композита --}}
        <div class="bg-white px-6 py-4 rounded-xl border border-emerald-100 text-center shadow-xs min-w-[180px] self-stretch lg:self-center flex flex-col justify-center">
            <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold block">Precio Calculado</span>
            <span class="text-3xl font-black text-emerald-600">
                ${{ number_format($comboServicios->getPrecio(), 2) }}
            </span>
            <span class="text-xs text-emerald-700 font-bold block mt-0.5">USD / combo total</span>
        </div>
    </div>

    {{-- ТАБЛИЦА-КОНСТРУКТОР ДЛЯ ПРОФЕССОРА --}}
    <div class="mt-6 pt-6 border-t border-emerald-100">
        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
            <i class="bi bi-sliders mr-1"></i> Панель управления элементами (Инспекция паттерна)
        </h5>
        <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-2xs">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400 font-semibold">
                    <tr>
                        <th class="px-4 py-2.5">Componente (Тип в паттерне)</th>
                        <th class="px-4 py-2.5">Nombre del Servicio</th>
                        <th class="px-4 py-2.5">Precio Individual</th>
                        <th class="px-4 py-2.5 text-right">Acción (Изменить структуру)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($todosLosServicios as $servicio)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md border border-blue-100">ClaseLeaf (Лист)</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $servicio->nombre }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">${{ number_format($servicio->precio, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('composite.toggle', $servicio->id) }}" method="POST" class="inline">
                                @csrf
                                @if(in_array($servicio->id, $serviciosEnComboIds))
                                    <button type="submit" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-medium rounded-lg transition">
                                        <i class="bi bi-dash-circle mr-1"></i> Quitar del Combo
                                    </button>
                                @else
                                    <button type="submit" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 text-xs font-medium rounded-lg transition">
                                        <i class="bi bi-plus-circle mr-1"></i> Agregar al Combo
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- ==================== КОНЕЦ ДЕМОНСТРАЦИИ ==================== --}}

<h2 class="text-xl font-bold text-slate-900 mb-4 uppercase tracking-wide text-xs text-slate-400">Planes Base Disponibles (Базовые тарифы)</h2>
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