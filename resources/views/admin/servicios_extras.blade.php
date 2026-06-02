@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Кнопка возврата на Дашборд --}}
    <div class="mb-4">
        <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d55] transition flex items-center gap-1">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
    
    {{-- ==================== ДЕМОНСТРАЦИЯ PATTERN COMPOSITE ==================== --}}
    <div class="mb-8 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 p-6 rounded-2xl shadow-sm">
        
        {{-- БЛОК КНОПОК / ФОРМ СОЗДАНИЯ (Элементы управления архитектурой) --}}
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-emerald-100 pb-6">
            
            {{-- 1. ФОРМА СОЗДАНИЯ НОВОЙ УСЛУГИ (LEAF) --}}
            <div class="bg-white/60 border border-emerald-200/60 p-4 rounded-xl flex flex-col justify-between">
                <h5 class="text-xs font-bold text-emerald-950 uppercase tracking-wider mb-3">
                    <i class="bi bi-plus-circle-fill text-emerald-600 mr-1"></i> 1. Crear Nuevo Servicio Individual (Leaf)
                </h5>
                <form action="{{ route('composite.servicio.store') }}" method="POST" class="flex flex-col sm:flex-row gap-2 items-end">
                    @csrf
                    <div class="flex-1 space-y-1 w-full">
                        <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block">Nombre del Servicio</label>
                        <input type="text" name="nombre" placeholder="Ej. Sauna, Kinesiología..." required
                               class="w-full bg-white border border-slate-200 text-xs px-3 py-1.5 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div class="w-full sm:w-24 space-y-1">
                        <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block">Precio ($)</label>
                        <input type="number" name="precio" placeholder="0" min="0" step="0.01" required
                               class="w-full bg-white border border-slate-200 text-xs px-3 py-1.5 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg font-bold transition shadow-2xs whitespace-nowrap h-[30px]">
                        Guardar
                    </button>
                </form>
            </div>

            {{-- 2. ФОРМА СОЗДАНИЯ НОВОГО КОМБО-ПАКЕТА (COMPOSITE) --}}
            <div class="bg-white/60 border border-emerald-200/60 p-4 rounded-xl flex flex-col justify-between">
                <h5 class="text-xs font-bold text-emerald-950 uppercase tracking-wider mb-3">
                    <i class="bi bi-folder-plus text-emerald-600 mr-1"></i> 2. Crear Nuevo Combo Vacío (Composite)
                </h5>
                <form action="{{ route('composite.combo.store') }}" method="POST" class="flex flex-col sm:flex-row gap-2 items-end">
                    @csrf
                    <div class="flex-1 space-y-1 w-full">
                        <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block">Nombre del Combo</label>
                        <input type="text" name="nombre" placeholder="Ej. Combo Boxeo & Cardio, VIP Festivo..." required
                               class="w-full bg-white border border-slate-200 text-xs px-3 py-1.5 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white text-xs px-4 py-1.5 rounded-lg font-bold transition shadow-2xs whitespace-nowrap h-[30px]">
                        Crear Combo
                    </button>
                </form>
            </div>

        </div>

        {{-- ШАПКА ТЕКУЩЕГО КОМБО И ИТОГОВАЯ ЦЕНА --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="space-y-1 flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <h4 class="text-sm font-bold text-emerald-900 uppercase tracking-wide whitespace-nowrap">
                        Servicios Complejos
                    </h4>
                    
                    {{-- СЕЛЕКТОР ДЛЯ ДИНАМИЧЕСКОГО ПЕРЕКЛЮЧЕНИЯ МЕЖДУ РАЗНЫМИ КОМБО --}}
                    <form action="{{ url()->current() }}" method="GET" id="combo-selector-form" class="w-full sm:w-auto">
                        <select name="combo_id" onchange="document.getElementById('combo-selector-form').submit()" class="bg-white border border-emerald-200 text-emerald-800 text-xs px-3 py-1.5 rounded-xl font-medium shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            @foreach($todosLosCombos as $c)
                                <option value="{{ $c->id }}" {{ $comboModel->id == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
                {{-- Динамическое имя текущего выбранного Композита с подсчетом услуг --}}
                <p class="text-xl font-extrabold text-slate-900 pt-2">
                    <i class="bi bi-gift-fill text-emerald-500 mr-1.5"></i> {{ $comboServicios->getNombre() }}
                </p>
                
                {{-- Динамический вывод состава комбо через связи --}}
                <div class="pt-2 flex flex-wrap gap-2">
                    @foreach($comboModel->servicios as $s)
                        <span class="bg-emerald-100/80 border border-emerald-200 text-emerald-800 text-xs px-3 py-1 rounded-lg font-medium shadow-2xs">
                            <i class="bi bi-check-circle-fill text-emerald-500 mr-1"></i> {{ $s->nombre }} (${{ number_format($s->precio, 0) }})
                        </span>
                    @endforeach
                    @if($comboModel->servicios->isEmpty())
                        <span class="text-xs text-slate-400 italic">El combo está vacío. ¡Agrega servicios abajo!</span>
                    @endif
                </div>
            </div>

            {{-- Динамический расчет цены Композита --}}
            <div class="bg-white px-6 py-4 rounded-xl border border-emerald-100 text-center shadow-xs min-w-[180px] self-stretch lg:self-center flex flex-col justify-center">
                <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold block">Precio Calculated</span>
                <span class="text-3xl font-black text-emerald-600">
                    ${{ number_format($comboServicios->getPrecio(), 2) }}
                </span>
                <span class="text-xs text-emerald-700 font-bold block mt-0.5">$ / combo total</span>
            </div>
        </div>

        {{-- ТАБЛИЦА-КОНСТРУКТОР (НАПОЛНЕНИЕ КОМБО) --}}
        <div class="mt-6 pt-6 border-t border-emerald-100">
            <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                <i class="bi bi-sliders mr-1"></i> Gestor de Configuración del Combos
            </h5>
            <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-2xs">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400 font-semibold">
                        <tr>
                            <th class="px-4 py-2.5">Nombre del Servicio</th>
                            <th class="px-4 py-2.5">Precio Individual</th>
                            <th class="px-4 py-2.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($todosLosServicios as $servicio)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $servicio->nombre }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">${{ number_format($servicio->precio, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('composite.toggle', [$comboModel->id, $servicio->id]) }}" method="POST" class="inline">
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

</div>
@endsection