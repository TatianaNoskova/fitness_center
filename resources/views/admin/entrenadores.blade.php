@extends('layouts.app')

@section('content')
{{-- Notificaciones --}}
@if(session('success'))
    <div id="msg-success-alert" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start">
        <div class="flex">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg mr-2"></i>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="document.getElementById('msg-success-alert').remove()" class="text-emerald-400 hover:text-emerald-600 transition ml-4 focus:outline-none p-0.5 rounded-lg hover:bg-emerald-100/50">
            <i class="bi bi-x-lg text-sm flex items-center justify-center w-4 h-4"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div id="msg-errors-alert" class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex justify-between items-start">
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
        <button type="button" onclick="document.getElementById('msg-errors-alert').remove()" class="text-rose-400 hover:text-rose-600 transition ml-4 focus:outline-none p-0.5 rounded-lg hover:bg-rose-100/50">
            <i class="bi bi-x-lg text-sm flex items-center justify-center w-4 h-4"></i>
        </button>
    </div>
@endif

{{-- Header --}}
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Entrenadores</h1>
        <p class="text-slate-500 mt-1">Base completa de entrenadores del club, sedes asignadas y especialidades.</p>
    </div>
    <a href="{{ route('admin.entrenadores.index', ['open_create' => 1]) }}" 
       class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-person-plus me-2"></i> Registrar Entrenador
    </a>
</div>

{{-- Tabla de los entrenadores --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <th class="py-4 px-6">Entrenador</th>
                <th class="py-4 px-6">DNI / Documento</th>
                <th class="py-4 px-6">Sede Asignada</th>
                <th class="py-4 px-6">Especialidad</th>
                <th class="py-4 px-6 text-center">Estado</th>
                <th class="py-4 px-6 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($entrenadores as $e)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 px-6">
                    <div class="flex flex-col">
                        <span class="font-semibold text-slate-900">{{ $e->user->nombre }} {{ $e->user->apellido }}</span>
                        <span class="text-xs text-slate-400">{{ $e->user->email }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 font-mono text-xs text-slate-500">
                    {{ $e->user->dni }}
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                        <i class="bi bi-building me-1 text-slate-400"></i> {{ $e->obedienceSede->nombre }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="font-medium text-slate-800 inline-flex items-center">
                        <i class="bi bi-award text-indigo-500 me-1.5"></i> {{ $e->especialidad }}
                    </span>
                </td>
                <td class="py-4 px-6 text-center">
                    @if(strtoupper($e->estado) === 'ACTIVO')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            {{ $e->estado }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                            {{ $e->estado }}
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.entrenadores.index', ['edit_id' => $e->user_id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition shadow-sm">
                            <i class="bi bi-pencil me-1.5 text-slate-400"></i> Editar
                        </a>

                        <form action="{{ route('admin.entrenadores.forceDelete', $e->user_id) }}" method="POST" 
                              onsubmit="return confirm('¿Está seguro de que desea ELIMINAR permanentemente a este entrenador? Esta acción no se puede deshacer. (Nota: Si solo desea suspenderlo temporalmente, cambie su estado a INACTIVO en la pantalla de Editar).');" 
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg border border-rose-100 transition shadow-sm">
                                <i class="bi bi-trash3 me-1.5 text-rose-500"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-slate-400">No hay entrenadores registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL: Registro de entrenador --}}
@if(request('open_create'))
<div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full overflow-hidden flex flex-col antialiased my-auto">
        
        <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-person-plus text-rose-500 me-2"></i> Registrar Nuevo Entrenador</h3>
            <a href="{{ route('admin.entrenadores.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
        </div>

        <form action="{{ route('admin.entrenadores.store') }}" method="POST" class="p-4 space-y-3 text-left">
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
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">DNI / Documento</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Contraseña</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres" 
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
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sede Asignada</label>
                    <select name="sede_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20 shadow-sm">
                            <option value="" disabled {{ !old('sede_id') ? 'selected' : '' }}>Selecciona una sede</option>
                        
                        @foreach($sedes as $s)
                            <option value="{{ $s->id }}" {{ old('sede_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Especialidad</label>
                    <input type="text" name="especialidad" value="{{ old('especialidad') }}" placeholder="Ej. Pilates, Crossfit" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500/20">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                <a href="{{ route('admin.entrenadores.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-rose-100">Registrar</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL: Editar --}}
@if(request('edit_id'))
    @php $entrenadorParaEditar = $entrenadores->firstWhere('user_id', request('edit_id')); @endphp

    @if($entrenadorParaEditar)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full overflow-hidden flex flex-col antialiased my-auto">
            
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-900"><i class="bi bi-pencil text-amber-500 me-2"></i> Editar Entrenador</h3>
                <a href="{{ route('admin.entrenadores.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
            </div>

            <form action="{{ route('admin.entrenadores.update', $entrenadorParaEditar->user_id) }}" method="POST" class="p-4 space-y-3 text-left">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $entrenadorParaEditar->user->nombre) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido', $entrenadorParaEditar->user->apellido) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $entrenadorParaEditar->user->email) }}" required 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $entrenadorParaEditar->user->telefono) }}" 
                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sede / Filial</label>
                        <select name="sede_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            <option value="">Sin sede (Externo)</option>
                            @foreach($sedes as $s)
                                <option value="{{ $s->id }}" {{ old('sede_id', $entrenadorParaEditar->sede_id) == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Estado</label>
                        <select name="estado" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20 shadow-sm">
                            <option value="ACTIVO" {{ old('estado', strtoupper($entrenadorParaEditar->estado)) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', strtoupper($entrenadorParaEditar->estado)) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Especialidad</label>
                    <input type="text" name="especialidad" value="{{ old('especialidad', $entrenadorParaEditar->especialidad) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 space-y-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center">
                        <i class="bi bi-key-fill text-amber-500 me-1.5 text-xs"></i> Cambiar Contraseña
                    </label>
                    <input type="password" name="password" placeholder="Dejar vacío para mantener la actual"
                           class="w-full rounded-xl border border-slate-200 px-3 py-1.5 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                    <a href="{{ route('admin.entrenadores.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-amber-100">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endif
@endsection