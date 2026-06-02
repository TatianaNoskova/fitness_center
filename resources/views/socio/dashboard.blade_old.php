@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    @if(!$socio)
        {{-- ==================== ЭКРАН ОНБОРДИНГА (ПРОФИЛЯ НЕТ) ==================== --}}
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xl mt-10">
            
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="bg-red-50 text-[#d40839] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    Paso Inicial
                </span>
                <h2 class="text-3xl text-[#002d55] mt-3">¡Bienvenido a <span class="font-bold tracking-tight">World<span class="text-[#d40839] italic">Class</span></span>, {{ auth()->user()->nombre }}!</h2>
                <p class="text-sm text-slate-400 mt-2">
                    Para activar tu panel de socio, elegir tus clases y gestionar tus cuotas, necesitamos conocer tu categoría, sede principal y el plan que deseas contratar.
                </p> 
            </div>

            <form action="/socio/crear-perfil" method="POST">
                @csrf

                {{-- ШАГ 1: Выбор категории --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-[#002d55] uppercase tracking-wider mb-2">
                        1. Selecciona tu Condición / Categoría de Socio
                    </label>
                    <select name="categoria" required class="w-full bg-slate-50 border border-slate-200 text-[#002d55] rounded-xl p-3.5 focus:outline-none focus:border-[#002d55] text-sm font-medium">
                        <option value="NORMAL" selected>Público General (Tarifa Estándar)</option>
                        <option value="ESTUDIANTE">Estudiante (20% de Descuento — Requiere Certificado)</option>
                        <option value="VIP">Socio VIP (+50% de recargo con Beneficios Premium Exclusivos)</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5 pl-1">
                        Nota: Se solicitará el comprobante correspondiente al momento de ingresar por primera vez a la sede.
                    </p>
                </div>

                {{-- ШАГ 2: Выбор филиала (Sede) --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-[#002d55] uppercase tracking-wider mb-2">
                        2. Selecciona tu Sede Principal (Gimnasio Destinado)
                    </label>
                    <select name="sede_id" required class="w-full bg-slate-50 border border-slate-200 text-[#002d55] rounded-xl p-3.5 focus:outline-none focus:border-[#002d55] text-sm font-medium">
                        <option value="" disabled selected>Elegir sede...</option>
                        @foreach($todasLasSedes as $sede)
                            <option value="{{ $sede->id }}">{{ $sede->nombre }} ({{ $sede->direccion }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- ШАГ 3: Выбор тарифного плана (Plan) --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-[#002d55] uppercase tracking-wider mb-4">
                        3. Selecciona tu Plan de Membresía (Precio Base)
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($todosLosPlanes as $p)
                            <label class="relative border-2 border-slate-100 hover:border-slate-300 rounded-2xl p-6 flex flex-col justify-between cursor-pointer bg-slate-50/50 has-[:checked]:border-[#002d55] has-[:checked]:bg-blue-50/20 transition duration-150">
                                <input type="radio" name="plan_id" value="{{ $p->id }}" required class="absolute top-4 right-4 accent-[#002d55]">
                                <div>
                                    <h4 class="text-lg font-bold text-[#002d55] uppercase">{{ $p->nombre }}</h4>
                                    <p class="text-xs text-slate-400 mt-1">{{ $p->descripcion }}</p>
                                    <div class="mt-4">
                                        <span class="text-2xl font-black text-[#002d55]">${{ number_format($p->precio, 0) }}</span>
                                        <span class="text-xs text-slate-400"> base / mes</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Кнопка отправки --}}
                <div class="text-center mt-10">
                    <button type="submit" class="bg-[#d40839] hover:bg-[#b0062e] text-white text-sm font-bold px-10 py-4 rounded-xl transition duration-150 uppercase tracking-wider shadow-md">
                        Activar mi Cuenta de Socio
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- ==================== СТАНДАРТНЫЙ РАБОЧИЙ ДАШБОРД ==================== --}}
        <div class="space-y-10">

            {{-- Блок уведомлений --}}
            @if(session('success'))
                <a href="{{ request()->url() }}" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm hover:bg-emerald-100 transition duration-150 block no-underline" title="Click para cerrar">
                    <div class="flex items-center space-x-2">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <i class="bi bi-x-lg text-sm text-emerald-400"></i>
                </a>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-rose-500 text-xl"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            
            {{-- Шапка дашборда --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-[#002d55]">Mi Panel de Socio</h1>
                    <p class="text-slate-500">Bienvenido, {{ auth()->user()->nombre }}. Aquí puedes gestionar tus entrenamientos y pagos.</p>
                </div>

                {{-- ПРОВЕРЯЕМ ГЛОБАЛЬНЫЙ КЭШ ОТ ОБСЕРВЕРА --}}
                @if(\Illuminate\Support\Facades\Cache::has('alerta_clase_cancelada'))
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-2xl shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg"></i>
                            </div>
                            <div class="ml-3 w-full flex justify-between items-start">
                                <div>
                                    <h3 class="text-sm font-bold text-rose-800 uppercase tracking-wide">
                                        Aviso Importante del Gimnasio (Observer Activo)
                                    </h3>
                                    <div class="mt-1 text-sm text-rose-700 font-medium">
                                        {{ \Illuminate\Support\Facades\Cache::get('alerta_clase_cancelada') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
                    <div class="p-3 bg-[#d40839]/10 text-[#d40839] rounded-xl">
                        <i class="bi bi-vignette text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Plan Actual</p>
                        <p class="font-bold text-[#002d55]">{{ $socio->plan->nombre ?? 'Sin Plan' }}</p>
                    </div>
                    <div class="border-l border-slate-100 pl-4">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Sede Principal</p>
                        <p class="font-medium text-slate-600">{{ $socio->sede->nombre ?? 'No asignada' }}</p>
                    </div>
                    {{-- Добавляем отображение категории прямо в виджет шапки --}}
                    <div class="border-l border-slate-100 pl-4">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Categoría</p>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full inline-block mt-0.5
                            @if($socio->categoria === 'VIP') bg-amber-100 text-amber-700 border border-amber-200
                            @elseif($socio->categoria === 'ESTUDIANTE') bg-sky-100 text-sky-700 border border-sky-200
                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                            {{ $socio->categoria }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Сетка колонок --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- ==================== ЛЕВАЯ КОЛОНКА (Занимает 2/3 экрана) ==================== --}}
                <div class="lg:col-span-2 space-y-10">
                    
                    {{-- ДИНАМИЧЕСКАЯ КАРТОЧКА СТРАТЕГИИ ТАРИФА (Новый блок преимуществ) --}}
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 @if($socio->categoria === 'VIP') bg-gradient-to-r from-amber-500 to-amber-600 text-white @elseif($socio->categoria === 'ESTUDIANTE') bg-gradient-to-r from-sky-500 to-sky-600 text-white @else bg-gradient-to-r from-[#002d55] to-[#004077] text-white @endif">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-xs uppercase font-bold tracking-widest opacity-75">Condición de Facturación</span>
                                    <h3 class="text-2xl font-black mt-0.5">Socio {{ $socio->categoria }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs uppercase font-bold tracking-widest opacity-75 block">Cuota Mensual Final</span>
                                    <span class="text-3xl font-black">${{ number_format($precioCuota, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between text-sm text-slate-600 border-b border-slate-100 pb-3 gap-2">
                                <div><strong>Plan base contratado:</strong> {{ $socio->plan->nombre }}</div>
                                <div><strong>Precio base regular:</strong> ${{ number_format($socio->plan->precio, 2) }}</div>
                            </div>
                            
                            {{-- Описание математики стратегии --}}
                            <div class="text-sm">
                                @if($socio->categoria === 'ESTUDIANTE')
                                    <p class="text-emerald-600 font-medium flex items-center">
                                        <i class="bi bi-percent mr-1.5 text-lg"></i> Beneficio Estudiante applied: Se bonificó un 20% del valor del plan seleccionado.
                                    </p>
                                @elseif($socio->categoria === 'VIP')
                                    <p class="text-amber-600 font-medium flex items-center">
                                        <i class="bi bi-crown mr-1.5 text-lg"></i> Acceso VIP Activado: Se aplica un recargo del 50%, desbloqueando privilegios premium en toda la red.
                                    </p>
                                @else
                                    <p class="text-slate-500 font-medium flex items-center">
                                        <i class="bi bi-dash-circle mr-1.5 text-lg"></i> Tarifa Estándar: Sin modificaciones ni descuentos corporativos de momento.
                                    </p>
                                @endif
                            </div>

                            {{-- Динамический список преимуществ --}}
                            <div class="pt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#002d55] mb-2.5">Beneficios de tu suscripción activa:</h4>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-medium text-slate-600">
                                    <li class="flex items-center text-emerald-600"><i class="bi bi-check-circle-fill mr-2"></i> Sala de musculación y cardio</li>
                                    <li class="flex items-center text-emerald-600"><i class="bi bi-check-circle-fill mr-2"></i> Reserva de clases en Sede {{ $socio->sede->nombre ?? '' }}</li>
                                    
                                    {{-- Дополнительные VIP плюшки --}}
                                    @if($socio->categoria === 'VIP')
                                        <li class="flex items-center text-amber-600 font-bold"><i class="bi bi-star-fill mr-2"></i> Acceso Libre a TODAS las Sedes</li>
                                        <li class="flex items-center text-amber-600 font-bold"><i class="bi bi-star-fill mr-2"></i> Zona de Spa & Sauna Exclusiva</li>
                                        <li class="flex items-center text-amber-600 font-bold"><i class="bi bi-star-fill mr-2"></i> Bebidas hidratantes gratis</li>
                                        <li class="flex items-center text-amber-600 font-bold"><i class="bi bi-star-fill mr-2"></i> Toalla premium en cada sesión</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Классы пользователя (Mis Próximas Clases) --}}
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold text-[#002d55] flex items-center">
                            <i class="bi bi-calendar-check mr-2 text-[#d40839]"></i> Mis Próximas Clases
                        </h2>
                        
                        @if($misInscripciones->isEmpty())
                            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                                Aún no estás inscrito en ninguna clase.
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($misInscripciones as $inscripcion)
                                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center gap-4">
                                        <div>
                                            <h3 class="font-bold text-[#002d55]">{{ $inscripcion->nombre }}</h3>
                                            <p class="text-xs text-slate-400 flex items-center mt-1">
                                                <i class="bi bi-calendar3 mr-1"></i> {{ $inscripcion->fecha }} 
                                                <span class="mx-2">|</span> 
                                                <i class="bi bi-clock mr-1"></i> {{ \Carbon\Carbon::parse($inscripcion->hora)->format('H:i') }} hs
                                            </p>
                                            <span class="inline-block bg-emerald-50 text-emerald-600 text-[9px] font-bold px-2 py-0.5 rounded mt-2 uppercase">
                                                Inscripto
                                            </span>
                                        </div>
                                        
                                        <form action="/clases/{{ $inscripcion->id }}/cancelar" method="POST" onsubmit="return confirm('¿Seguro quieres cancelar esta clase?');">
                                            @csrf
                                            <button type="submit" class="border border-rose-200 hover:bg-rose-50 text-rose-600 text-xs font-semibold px-3 py-2 rounded-xl transition duration-150">
                                                Cancelar
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- EXTRA УСЛУГИ & КОМБО (PATRON COMPOSITE) --}}
                    <div class="space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-xl font-bold text-[#002d55] flex items-center">
                                <i class="bi bi-bag-plus mr-2 text-[#d40839]"></i> Servicios Extra y Combos Especiales
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Potencia tu entrenamiento con servicios individuales o paquetes armados a tu medida.</p>
                        </div>
                        
                        <form action="{{ route('socio.contratar-extras') }}" method="POST" class="space-y-6">
                            @csrf

                            {{-- СЕГМЕНТ 1: Комплексные пакеты (Combos) --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center">
                                    <i class="bi bi-tags mr-1.5 text-amber-500"></i> 1. Combos Promocionales (Todo Incluido)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($todosCombos ?? [] as $combo)
                                        <label class="bg-gradient-to-br from-amber-50/40 to-white border border-amber-200 hover:border-amber-400 rounded-2xl p-5 flex items-start space-x-3 cursor-pointer shadow-sm transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                                            <input type="checkbox" name="combos[]" value="{{ $combo->id }}" class="mt-1.5 accent-amber-600 rounded">
                                            <div class="flex-1">
                                                <div class="flex justify-between items-baseline">
                                                    <span class="font-bold text-base text-[#002d55]">{{ $combo->nombre }}</span>
                                                    <span class="text-amber-600 font-black text-base">${{ number_format($combo->precio, 0) }}</span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-1">{{ $combo->descripcion }}</p>
                                                
                                                @if($combo->servicios && $combo->servicios->isNotEmpty())
                                                    <div class="mt-3 pt-2.5 border-t border-amber-100/70 space-y-1">
                                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700/80 block">Servicios incluidos:</span>
                                                        <div class="flex flex-wrap gap-1.5">
                                                            @foreach($combo->servicios as $sInclud)
                                                                <span class="bg-white border border-amber-200 text-[#002d55] text-[10px] font-medium px-2 py-0.5 rounded-lg shadow-2xs">
                                                                    ✓ {{ $sInclud->nombre }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    @empty
                                        <div class="col-span-2 bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-400">
                                            No hay combos compuestos disponibles en este momento.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

{{-- EXTRA SERVICIOS & COMBOS (SISTEMA DE PESTAÑAS PHP PURO) --}}
@php
    // Determinamos la pestaña activa desde el parámetro URL (por defecto 'combos')
    $activeTab = request()->query('tab', 'combos');
@endphp

<div class="space-y-6">
    
    {{-- Encabezado del bloque y selectores de pestañas --}}
    <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-[#002d55] flex items-center">
                <i class="bi bi-bag-plus mr-2 text-[#d40839]"></i> Servicios Extra y Combos Especiales
            </h2>
            <p class="text-xs text-slate-400 mt-1">Personaliza tu entrenamiento seleccionando combos promocionales o servicios individuales.</p>
        </div>
        
        {{-- Botones de navegación (Tabs) basados en URLs de PHP --}}
        <div class="flex bg-slate-100 p-1 rounded-xl self-start sm:self-center">
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'combos']) }}"
               class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-150 no-underline text-center
               {{ $activeTab === 'combos' ? 'bg-white text-[#002d55] shadow-sm' : 'text-slate-500 hover:text-[#002d55]' }}">
                <i class="bi bi-tags mr-1"></i> Combos
            </a>
            
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'servicios']) }}"
               class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-150 no-underline text-center
               {{ $activeTab === 'servicios' ? 'bg-white text-[#002d55] shadow-sm' : 'text-slate-500 hover:text-[#002d55]' }}">
                <i class="bi bi-grid-fill mr-1"></i> Individuales
            </a>
        </div>
    </div>
    
    {{-- Formulario único de contratación --}}
    <form action="{{ route('socio.contratar-extras') }}" method="POST" class="space-y-6">
        @csrf

        {{-- VISTA 1: COMBOS PROMOCIONALES --}}
        @if($activeTab === 'combos')
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center mb-2">
                    <i class="bi bi-tags mr-1.5 text-amber-500"></i> Combos Promocionales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($todosCombos ?? [] as $combo)
                        <label class="bg-gradient-to-br from-amber-50/40 to-white border border-amber-200 hover:border-amber-400 rounded-2xl p-5 flex items-start space-x-3 cursor-pointer shadow-sm transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/20">
                            <input type="checkbox" name="combos[]" value="{{ $combo->id }}" class="mt-1.5 accent-amber-600 rounded">
                            <div class="flex-1">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-bold text-base text-[#002d55]">{{ $combo->nombre }}</span>
                                    <span class="text-amber-600 font-black text-base">${{ number_format($combo->precio, 0) }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">{{ $combo->descripcion }}</p>
                                
                                @if($combo->servicios && $combo->servicios->isNotEmpty())
                                    <div class="mt-4 pt-2.5 border-t border-amber-100/70 space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700/80 block">Servicios incluidos:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($combo->servicios as $sInclud)
                                                <span class="bg-white border border-amber-200 text-[#002d55] text-[10px] font-medium px-2 py-0.5 rounded-lg shadow-sm">
                                                    ✓ {{ $sInclud->nombre }} (${{ number_format($sInclud->precio, 0) }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="col-span-2 bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-400">
                            No hay combos compuestos disponibles en este momento.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- VISTA 2: SERVICIOS INDIVIDUALES --}}
        @if($activeTab === 'servicios')
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center mb-2">
                    <i class="bi bi-grid-fill mr-1.5 text-sky-500"></i> Servicios Individuales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($todosServicios ?? [] as $servicio)
                        <label class="bg-white border border-slate-200 hover:border-slate-300 rounded-2xl p-4 flex items-start space-x-3 cursor-pointer shadow-sm transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/10">
                            <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" class="mt-1 accent-emerald-600 rounded">
                            <div class="flex-1">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-bold text-sm text-[#002d55]">{{ $servicio->nombre }}</span>
                                    <span class="text-emerald-600 font-extrabold text-sm">${{ number_format($servicio->precio, 0) }}</span>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-2 bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-400">
                            No hay servicios individuales adicionales disponibles.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- Botón de envío del Formulario --}}
        <div class="flex justify-end pt-2">
            <button type="submit" class="bg-[#d40839] hover:bg-[#b0062e] text-white text-xs font-bold px-8 py-3.5 rounded-xl uppercase tracking-wider shadow-md transition duration-150 flex items-center space-x-2">
                <i class="bi bi-cart-plus text-sm"></i>
                <span>Confirmar y Agregar a mi Próxima Cuota</span>
            </button>
        </div>
    </form>
</div>

---

{{-- HISTORIAL DE PAGOS --}}
<div class="space-y-4 mt-8">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-[#002d55] flex items-center">
            <i class="bi bi-credit-card mr-2 text-[#d40839]"></i> Historial de Pagos
        </h2>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                    <th class="p-4">Fecha Emisión</th>
                    <th class="p-4">Monto Final</th>
                    <th class="p-4">Método</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-600">
                @forelse($historialPagos as $pago)
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50">
                        <td class="p-4 text-[#002d55] font-bold">{{ $pago->fecha }}</td>
                        <td class="p-4">${{ number_format($pago->monto, 2) }}</td>
                        <td class="p-4 text-slate-400">{{ $pago->metodo_pago }}</td>
                        <td class="p-4">
                            @if($pago->estado == 'PAGADO')
                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">PAGADO</span>
                            @elseif($pago->estado == 'PENDIENTE')
                                <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">PENDIENTE</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">{{ $pago->estado }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($pago->estado == 'PENDIENTE')
                                <form action="{{ route('socio.pagar', $pago->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider flex items-center transition-colors shadow-sm">
                                        <i class="bi bi-wallet2 mr-1"></i> Pagar
                                    </button>
                                </form>
                            @else
                                <span class="text-emerald-600 flex items-center justify-center text-xs font-bold">
                                    <i class="bi bi-check-circle-fill mr-1 text-emerald-500"></i> Confirmado
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-sm text-slate-400">
                            No hay registros de pagos disponibles de momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

---

{{-- COLONNA DERECHA: CLASES DISPONIBLES --}}
<div class="space-y-6 mt-8">
    <h2 class="text-xl font-bold text-[#002d55] flex items-center">
        <i class="bi bi-plus-circle mr-2 text-[#d40839]"></i> Clases Disponibles (Toda la Red)
    </h2>
    
    @if($clasesDisponibles->isEmpty())
        <div class="bg-[#002d55] rounded-3xl p-6 text-white/50 text-center text-sm">
            No hay clases disponibles en este momento.
        </div>
    @else
        <div class="space-y-4">
            @foreach($clasesDisponibles as $clase)
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3 text-left">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <h3 class="font-bold text-[#002d55] text-lg leading-tight">{{ $clase->nombre }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ $clase->descripcion }}</p>
                        </div>
                        
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider whitespace-nowrap
                            {{ $clase->sede_id == $socio->sede_id ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                            {{ $clase->sede->nombre }}
                            {{ $clase->sede_id != $socio->sede_id ? '(Invitado)' : '' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span class="flex items-center"><i class="bi bi-calendar3 mr-1 text-[#d40839]"></i> {{ $clase->fecha }}</span>
                        <span class="flex items-center"><i class="bi bi-clock mr-1 text-[#d40839]"></i> {{ \Carbon\Carbon::parse($clase->hora)->format('H:i') }} hs</span>
                        <span class="flex items-center"><i class="bi bi-people mr-1 text-[#d40839]"></i> {{ $clase->capacidad }} cupos</span>
                    </div>

                    @if($misInscripciones->contains('id', $clase->id))
                        <button class="w-full bg-slate-100 text-slate-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed uppercase tracking-wider mt-2 flex items-center justify-center space-x-1" disabled>
                            <i class="bi bi-check2-all text-emerald-600 text-sm"></i>
                            <span>Ya estás inscrito</span>
                        </button>
                    @elseif($clase->capacidad <= 0)
                        <button class="w-full bg-slate-100 text-slate-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed uppercase tracking-wider mt-2" disabled>
                            Agotado (Sin cupos)
                        </button>
                    @else
                        @if($socio->categoria === 'VIP' || $clase->sede_id == $socio->sede_id)
                            <form action="/clases/{{ $clase->id }}/inscribir" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-[#d40839] hover:bg-[#b0062e] text-white text-xs font-bold py-2.5 rounded-xl transition duration-200 uppercase tracking-wider mt-2">
                                    Inscribirse
                                </button>
                            </form>
                        @else
                            <button class="w-full bg-slate-100 text-slate-400 text-[10px] font-bold py-2.5 rounded-xl cursor-not-allowed uppercase tracking-wider mt-2" title="Solo los socios VIP pueden entrenar en otras sedes" disabled>
                                Exclusivo Sede Propia o VIP
                            </button>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
                                      

            </div>
        </div>
    @endif

</div>
@endsection