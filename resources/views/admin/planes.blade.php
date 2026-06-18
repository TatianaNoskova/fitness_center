@extends('layouts.app')
@section('content')
{{-- Notificaciones --}}
@if(session('success'))
    <div id="section-alert-success" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start">
        <div class="flex">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg mr-2"></i>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="document.getElementById('section-alert-success').remove()" class="text-emerald-400 hover:text-emerald-600 transition ml-4 focus:outline-none p-0.5 rounded-lg hover:bg-emerald-100/50">
            <i class="bi bi-x-lg text-sm flex items-center justify-center w-4 h-4"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div id="section-alert-errors" class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start">
        <div class="flex flex-col flex-grow">
            <div class="flex items-center mb-1">
                <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg mr-2"></i>
                <p class="text-rose-800 font-bold">Por favor, corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-rose-700 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" onclick="document.getElementById('section-alert-errors').remove()" class="text-rose-400 hover:text-rose-600 transition ml-4 focus:outline-none p-0.5 rounded-lg hover:bg-rose-100/50">
            <i class="bi bi-x-lg text-sm flex items-center justify-center w-4 h-4"></i>
        </button>
    </div>
@endif

{{-- Header --}}
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Planes de Membresía</h1>
        <p class="text-slate-500 mt-1">Lista de abonos disponibles para los socios y gestión de precios.</p>
    </div>
    <a href="{{ route('planes.index', ['open_create' => 1]) }}" 
       class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-plus-lg me-2"></i> Crear Nuevo Plan
    </a>
</div>

<h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Planes Disponibles</h2>

{{-- Planes (tarjetas) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($planes as $plan)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between relative transition hover:shadow-md">
        
        @if(strtoupper($plan->estado) !== 'ACTIVO')
            <div class="absolute top-4 right-4 bg-amber-50 text-amber-700 text-[10px] uppercase font-bold px-2 py-0.5 rounded-md ring-1 ring-amber-600/20">
                Inactivo
            </div>
        @endif

        <div>
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">{{ $plan->nombre }}</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium">
                    {{ $plan->duracion }} meses
                </span>
            </div>
            
            <p class="text-slate-500 text-sm mb-6 min-h-[40px]">
                {{ $plan->descripcion ?? 'Sin descripción disponible.' }}
            </p>
        </div>

        <div>
            <div class="text-3xl font-black text-slate-900 mb-6">
                ${{ number_format($plan->precio, 0, '.', '.') }} <span class="text-xs font-medium text-slate-400">/ total</span>
            </div>

            <a href="{{ route('planes.index', ['edit_id' => $plan->id]) }}" 
               class="block w-full text-center py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition border border-slate-200/60 shadow-sm">
                Editar Plan
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl p-8 border border-slate-100 text-center text-slate-400">
        No hay planes registrados.
    </div>
    @endforelse
</div>

{{-- ======================================================================= --}}
{{-- MODAL: AGREGAR UN PLAN --}}
{{-- ======================================================================= --}}
@if(request('open_create'))
<div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden flex flex-col antialiased my-auto">
        
        <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-card-checklist text-rose-500 me-2"></i> Crear Nuevo Plan</h3>
            <a href="{{ route('planes.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
        </div>

        <form action="{{ route('planes.store') }}" method="POST" class="p-4 space-y-3 text-left">
            @csrf
            
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre del Plan</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Plan Pase Libre" required 
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion') }}" placeholder="Ej. Acceso ilimitado a todas las sedes" required 
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Precio ($)</label>
                    <input type="number" name="precio" step="0.01" value="{{ old('precio') }}" placeholder="Ej. 15000" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Duración (Meses)</label>
                    <input type="number" name="duracion" value="{{ old('duracion', 1) }}" placeholder="Ej. 1, 3, 12" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Estado</label>
                <select name="estado" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20 shadow-sm">
                    <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                    <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                <a href="{{ route('planes.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-rose-100">Crear Plan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================================= --}}
{{-- MODAL: EDITAR PLAN --}}
{{-- ======================================================================= --}}

@if(request('edit_id'))
    @php $planParaEditar = $planes->firstWhere('id', request('edit_id')); @endphp

    @if($planParaEditar)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden flex flex-col antialiased my-auto">
            
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-pencil text-amber-500 me-2"></i> Editar Plan</h3>
                <a href="{{ route('planes.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
            </div>

            <form action="{{ route('planes.update', $planParaEditar->id) }}" method="POST" class="p-4 space-y-3 text-left">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre del Plan</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $planParaEditar->nombre) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Descripción</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion', $planParaEditar->descripcion) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Precio ($)</label>
                        <input type="number" name="precio" step="0.01" value="{{ old('precio', $planParaEditar->precio) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Duración (Meses)</label>
                        <input type="number" name="duracion" value="{{ old('duracion', $planParaEditar->duracion) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Estado</label>
                    <select name="estado" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                        <option value="ACTIVO" {{ old('estado', $planParaEditar->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                        <option value="INACTIVO" {{ old('estado', $planParaEditar->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                    <a href="{{ route('planes.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-amber-100">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endif
@endsection