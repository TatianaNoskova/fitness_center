@extends('layouts.app')

@section('content')
{{-- Notificaciones --}}
@if(session('success'))
    <div id="alert-success" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start transition-all duration-300">
        <div class="flex">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg mr-2"></i>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="document.getElementById('alert-success').remove()" class="text-emerald-400 hover:text-emerald-600 transition ml-4 focus:outline-none">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div id="alert-errors" class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start transition-all duration-300">
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
        <button type="button" onclick="document.getElementById('alert-errors').remove()" class="text-rose-400 hover:text-rose-600 transition ml-4 focus:outline-none">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    </div>
@endif

{{-- HEADER --}}
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Socios</h1>
        <p class="text-slate-500 mt-1">Base completa de socios del club, planes de membresía, categorías y cálculo de costos.</p>
    </div>
    <a href="{{ route('admin.socios.index', ['open_create' => 1]) }}" 
       class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-person-plus me-2"></i> Registrar Socio
    </a>
</div>

{{-- La tabla de los CLIENTES --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <th class="py-4 px-6">Socio</th>
                <th class="py-4 px-6">DNI</th>
                <th class="py-4 px-6">Sede</th>
                <th class="py-4 px-6">Plan / Categoría</th>
                <th class="py-4 px-6">Cuota</th>
                <th class="py-4 px-6">Fecha Alta</th>
                <th class="py-4 px-6 text-center">Estado</th>
                <th class="py-4 px-6 text-center">Acciones</th>
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
                {{-- Plan y categoría --}}
                <td class="py-4 px-6">
                    <div class="flex flex-col gap-1">
                        <span class="font-medium text-slate-800 text-xs inline-flex items-center">
                            <i class="bi bi-card-checklist me-1 text-indigo-400"></i> {{ $socio->plan->nombre ?? 'Sin plan' }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-500 inline-flex items-center uppercase">
                            <i class="bi bi-tag me-1 text-emerald-400"></i> {{ $socio->categoria ?? 'NORMAL' }}
                        </span>
                    </div>
                </td>
                {{-- Couta (atraves del medoto del Model) --}}
                <td class="py-4 px-6 font-semibold text-slate-900">
                    ${{ number_format($socio->obtenerPrecioCuota(), 2, '.', ',') }}
                </td>
                <td class="py-4 px-6 text-slate-500">
                    {{ \Carbon\Carbon::parse($socio->fecha_alta)->format('d/m/Y') }}
                </td>
                <td class="py-4 px-6 text-center">
                    @if(strtoupper($socio->estado) === 'ACTIVO')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            {{ $socio->estado }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                            {{ $socio->estado }}
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.socios.index', ['edit_id' => $socio->user_id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition shadow-sm">
                            <i class="bi bi-pencil me-1.5 text-slate-400"></i> Editar
                        </a>

                        <form action="{{ route('admin.socios.forceDelete', $socio->user_id) }}" method="POST" 
                            onsubmit="return confirm('¿Está seguro de que желает ELIMINAR permanentemente a este socio? Esta acción no se puede deshacer. (Nota: Si solo desea suspenderlo temporalmente, cambie su estado a INACTIVO en la pantalla de Editar).');" 
                            class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg border border-rose-100 transition shadow-sm">
                                <i class="bi bi-trash3 me-1.5 text-rose-500"></i> Eliminar
                            </button>
                        </form>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-8 text-center text-slate-400">No hay socios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ======================================================================= --}}
{{-- MODAL: AGREGAR CLIENTE --}}
{{-- ======================================================================= --}}
@if(request('open_create'))
<div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full overflow-hidden flex flex-col antialiased my-auto">
        
        <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-person-plus text-rose-500 me-2"></i> Registrar Nuevo Socio</h3>
            <a href="{{ route('admin.socios.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
        </div>

        <form action="{{ route('admin.socios.store') }}" method="POST" class="p-4 space-y-3 text-left">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">DNI / Documento</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Contraseña</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sede Inicial</label>
                <select name="sede_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20 shadow-sm">
                    <option value="" disabled selected>Selecciona una sede...</option>
                    @foreach($sedes as $s)
                        <option value="{{ $s->id }}" {{ old('sede_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Plan de Membresía</label>
                    <select name="plan_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20 shadow-sm">
                        <option value="" disabled selected>Selecciona un plan...</option>
                        @foreach($planes as $p)
                            <option value="{{ $p->id }}" {{ old('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }} (${{ $p->precio }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Categoría (Strategy)</label>
                    <select name="categoria" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20 shadow-sm">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                <a href="{{ route('admin.socios.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-rose-100">Registrar</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================================= --}}
{{-- MODAL: EDITAR CLIENTE --}}
{{-- ======================================================================= --}}
@if(request('edit_id'))
    @php $socioParaEditar = $socios->firstWhere('user_id', request('edit_id')); @endphp

    @if($socioParaEditar)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full overflow-hidden flex flex-col antialiased my-auto">
            
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-pencil text-amber-500 me-2"></i> Editar Socio</h3>
                <a href="{{ route('admin.socios.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
            </div>

            <form action="{{ route('admin.socios.update', $socioParaEditar->user_id) }}" method="POST" class="p-4 space-y-3 text-left">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $socioParaEditar->user->nombre) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido', $socioParaEditar->user->apellido) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $socioParaEditar->user->email) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $socioParaEditar->user->telefono) }}" 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sede / Filial</label>
                        <select name="sede_id" required 
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            @foreach($sedes as $s)
                                <option value="{{ $s->id }}" {{ old('sede_id', $socioParaEditar->sede_id) == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Estado</label>
                        <select name="estado" required 
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            <option value="ACTIVO" {{ old('estado', strtoupper($socioParaEditar->estado)) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', strtoupper($socioParaEditar->estado)) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Plan Activo</label>
                        <select name="plan_id" required 
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            @foreach($planes as $p)
                                <option value="{{ $p->id }}" {{ old('plan_id', $socioParaEditar->plan_id) == $p->id ? 'selected' : '' }}>{{ $p->nombre }} (${{ $p->precio }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Categoría (Strategy)</label>
                        <select name="categoria" required 
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', strtoupper($socioParaEditar->categoria)) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 space-y-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center">
                        <i class="bi bi-key-fill text-amber-500 me-1.5 text-xs"></i> Cambiar Contraseña
                    </label>
                    <input type="password" name="password" placeholder="Dejar vacío para mantener la actual"
                           class="w-full rounded-xl border border-slate-200 px-3 py-1.5 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500/20">
                    <p class="text-[10px] text-slate-400 leading-tight">
                        <i class="bi bi-info-circle me-0.5"></i> Solo en caso de emergencia.
                    </p>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                    <a href="{{ route('admin.socios.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-amber-100">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endif
@endsection