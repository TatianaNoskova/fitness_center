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
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Nuestras Sedes (Филиалы)</h1>
        <p class="text-slate-500 mt-1">Lista de sedes activas del club, sus ubicaciones y cantidad de socios.</p>
    </div>
    <a href="{{ route('admin.sedes.index', ['open_create' => 1]) }}" 
       class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-100">
        <i class="bi bi-plus-lg me-2"></i> Registrar Nueva Sede
    </a>
</div>

{{-- La table de SEDES --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <th class="py-4 px-6">Nombre</th>
                <th class="py-4 px-6">Dirección</th>
                <th class="py-4 px-6">Contacto</th>
                <th class="py-4 px-6">Socios Activos</th>
                <th class="py-4 px-6 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
            @forelse($sedes as $sede)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 px-6 font-semibold text-slate-900">
                    {{ $sede->nombre }}
                </td>
                <td class="py-4 px-6 text-slate-500">
                    <span class="inline-flex items-center">
                        <i class="bi bi-geo-alt me-1.5 text-slate-400"></i> {{ $sede->direccion }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex flex-col text-xs space-y-0.5">
                        <span class="text-slate-600 font-medium"><i class="bi bi-telephone me-1 text-slate-400"></i> {{ $sede->telefono }}</span>
                        @if($sede->email)
                            <span class="text-slate-400"><i class="bi bi-envelope me-1 text-slate-400"></i> {{ $sede->email }}</span>
                        @endif
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold">
                        <i class="bi bi-people me-1.5 text-emerald-500"></i> {{ $sede->socios_count ?? 0 }} socios
                    </span>
                </td>
                <td class="py-4 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.sedes.index', ['edit_id' => $sede->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition shadow-sm">
                            <i class="bi bi-pencil me-1.5 text-slate-400"></i> Editar
                        </a>

                        <form action="{{ route('admin.sedes.destroy', $sede->id) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar la sede «{{ $sede->nombre }}»? Esta acción no se puede deshacer.');" 
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
                <td colspan="5" class="py-8 text-center text-slate-400">No hay sedes registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ======================================================================= --}}
{{-- MODAL: AGREGAR SEDE --}}
{{-- ======================================================================= --}}
@if(request('open_create'))
<div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden flex flex-col antialiased">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900"><i class="bi bi-building text-rose-500 me-2"></i> Registrar Nueva Sede</h3>
            <a href="{{ route('admin.sedes.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
        </div>

        <form action="{{ route('admin.sedes.store') }}" method="POST" class="p-6 space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nombre de la Sede</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Sede Palermo" required 
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Ej. Av. Santa Fe 3400, CABA" required 
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. +54 11 9999-8888" required 
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Email de Contacto (Opcional)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ej. palermo@fitnessgym.com" 
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-rose-500 focus:ring-rose-500/20">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                <a href="{{ route('admin.sedes.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-rose-100">Registrar</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ======================================================================= --}}
{{-- MODAL: EDITAR SEDE --}}
{{-- ======================================================================= --}}
@if(request('edit_id'))
    @php $sedeParaEditar = $sedes->firstWhere('id', request('edit_id')); @endphp

    @if($sedeParaEditar)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden flex flex-col antialiased">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-900"><i class="bi bi-pencil text-amber-500 me-2"></i> Editar Sede</h3>
                <a href="{{ route('admin.sedes.index') }}" class="text-slate-400 hover:text-slate-600 transition"><i class="bi bi-x-lg"></i></a>
            </div>

            <form action="{{ route('admin.sedes.update', $sedeParaEditar->id) }}" method="POST" class="p-6 space-y-4 text-left">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nombre de la Sede</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $sedeParaEditar->nombre) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $sedeParaEditar->direccion) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $sedeParaEditar->telefono) }}" required 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Email de Contacto</label>
                    <input type="email" name="email" value="{{ old('email', $sedeParaEditar->email) }}" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500/20">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                    <a href="{{ route('admin.sedes.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">Cancelar</a>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition shadow-sm shadow-amber-100">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endif
@endsection