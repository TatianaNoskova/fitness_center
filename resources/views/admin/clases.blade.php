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

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-slate-800">Clases Disponibles</h2>
    
    <a href="{{ route('admin.clases.index', ['open_create' => 1, 'selected_sede_id' => request('selected_sede_id')]) }}" 
       class="bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-colors inline-flex items-center shadow-sm gap-2">
        <i class="bi bi-plus-lg text-base"></i> Registrar Nueva Clase
    </a>
</div>
    
<div class="overflow-x-auto bg-white rounded-2xl border border-slate-100 shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                <th class="p-4">Clase / Deporte</th>
                <th class="p-4">Sede / Filial</th>
                <th class="p-4">Entrenador</th> 
                <th class="p-4">Fecha y Hora</th>
                <th class="p-4 text-center">Cupos / Disponibilidad</th> 
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-sm font-medium text-slate-600">
            @forelse($clases as $clase)
                <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50 transition-colors">
                    <td class="p-4">
                        <span class="font-bold text-slate-900 block text-base">{{ $clase->nombre }}</span>
                        @if($clase->descripcion)
                            <span class="text-xs text-slate-400 font-normal block mt-0.5">{{ Str::limit($clase->descripcion, 60) }}</span>
                        @else
                            <span class="text-xs text-slate-300 font-normal italic block mt-0.5">Sin descripción</span>
                        @endif
                    </td>
                    
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-semibold border border-blue-100 inline-block">
                            <i class="bi bi-geo-alt mr-1"></i>{{ $clase->sede ? $clase->sede->nombre : 'Sin Sede' }}
                        </span>
                    </td>
                    
                    <td class="p-4 text-slate-700 font-semibold">
                        @if($clase->entrenador && $clase->entrenador->user)
                            <div class="flex items-center gap-1.5">
                                <i class="bi bi-person-badge text-slate-400 text-base"></i> 
                                <div>
                                    <span class="block text-slate-900">{{ $clase->entrenador->user->nombre }} {{ $clase->entrenador->user->apellido }}</span>
                                    <span class="block text-[10px] text-slate-400 font-normal uppercase">{{ $clase->entrenador->especialidad }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-rose-500 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded text-xs font-semibold">Sin asignar</span>
                        @endif
                    </td>
                    
                    <td class="p-4 text-slate-500">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-800 font-medium whitespace-nowrap">
                                <i class="bi bi-calendar3 mr-1.5 text-slate-400"></i>{{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}
                            </span>
                            <span class="text-xs text-slate-400 font-normal">
                                <i class="bi bi-clock mr-1.5"></i>{{ \Carbon\Carbon::parse($clase->hora)->format('H:i') }} hs
                            </span>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        @php
                            $ocupados = $clase->socios()->count(); 
                            $total = $clase->capacidad;
                            $disponibles = $total - $ocupados;
                        @endphp

                        <div class="flex flex-col items-center justify-center">
                            <span class="font-bold text-slate-800 text-base">
                                {{ $ocupados }} <span class="text-slate-400 font-normal text-xs">/ {{ $total }}</span>
                            </span>
                            
                            @if($disponibles <= 0)
                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wider">
                                    <i class="bi bi-x-circle-fill mr-1"></i> Agotado
                                </span>
                            @elseif($disponibles <= 3)
                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wider">
                                    <i class="bi bi-exclamation-triangle-fill mr-1"></i> Últimos {{ $disponibles }}
                                </span>
                            @else
                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                                    <i class="bi bi-check-circle-fill mr-1"></i> {{ $disponibles }} Disponibles
                                </span>
                            @endif
                        </div>
                    </td>
                    
                    <td class="p-4 text-center whitespace-nowrap">
                        <div class="inline-flex gap-2">
                            @php $inscritos = $clase->inscripciones()->count(); @endphp

                            @if($inscritos > 0)
                                <a href="{{ route('admin.clases.index', ['edit_id' => $clase->id, 'selected_sede_id' => request('selected_sede_id')]) }}" 
                                class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-xs px-3 py-2 rounded-xl inline-flex items-center transition-colors shadow-sm"
                                title="Hay alumnos inscritos. Solo se permite editar descripción y capacidad.">
                                    <i class="bi bi-shield-lock-fill mr-1 text-blue-500"></i> Ajustar
                                </a>
                            @else
                                <a href="{{ route('admin.clases.index', ['edit_id' => $clase->id, 'selected_sede_id' => request('selected_sede_id')]) }}" 
                                class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-bold text-xs px-3 py-2 rounded-xl inline-flex items-center transition-colors shadow-sm">
                                    <i class="bi bi-pencil-square mr-1"></i> Editar
                                </a>
                            @endif

                            <form action="{{ route('admin.clases.destroy', $clase->id) }}" method="POST" 
                                onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta clase?{{ $inscritos > 0 ? " ¡Se notificará automáticamente a los $inscritos alumnos anotados!" : "" }}');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs px-3 py-2 rounded-xl inline-flex items-center transition-colors border border-rose-100 shadow-sm">
                                    <i class="bi bi-trash3-fill mr-1"></i> Cancelar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i class="bi bi-calendar-x text-3xl text-slate-300"></i>
                            <span>No hay clases programadas en el sistema.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ======================================================================= --}}
{{-- MODAL: EDITAR CLASE --}}
{{-- ======================================================================= --}}
@if(request('edit_id'))
    @php 
        $claseParaEditar = $clases->firstWhere('id', request('edit_id'));
    @endphp

    @if($claseParaEditar)
        @php 
            $inscritosCount = $claseParaEditar->socios()->count(); 
            $tieneInscritos = $inscritosCount > 0;
        @endphp

        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full overflow-hidden flex flex-col antialiased my-auto">
                
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900">
                        @if($tieneInscritos)
                            <i class="bi bi-shield-lock-fill text-blue-500 mr-2"></i> Ajustar Clase (Con Alumnos)
                        @else
                            <i class="bi bi-pencil-square text-amber-500 mr-2"></i> Editar y Reasignar Clase
                        @endif
                    </h3>
                    <a href="{{ route('admin.clases.index', ['selected_sede_id' => request('selected_sede_id')]) }}" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

                <form action="{{ route('admin.clases.update', [$claseParaEditar->id, 'edit_id' => request('edit_id'), 'selected_sede_id' => request('selected_sede_id')]) }}" method="POST" class="p-4 space-y-3 text-left">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs font-semibold flex flex-col gap-1">
                            <div class="flex items-center gap-2 mb-1 text-rose-600">
                                <i class="bi bi-exclamation-triangle-fill text-sm"></i>
                                <span class="font-bold">Por favor, corrige los siguientes errores:</span>
                            </div>
                            <ul class="list-disc list-inside pl-1 text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error_modal'))
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs font-semibold flex items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-rose-500 text-sm"></i>
                            <span>{{ session('error_modal') }}</span>
                        </div>
                    @endif

                    <input type="hidden" name="selected_sede_id" value="{{ request('selected_sede_id', $claseParaEditar->sede_id) }}">
                    <input type="hidden" name="sede_id" value="{{ $claseParaEditar->sede_id }}">

                    @if($tieneInscritos)
                        <div class="bg-blue-50 border border-blue-100 text-blue-800 rounded-xl px-3 py-2 text-xs flex items-start gap-2">
                            <i class="bi bi-info-circle-fill text-blue-500 mt-0.5 text-sm flex-shrink-0"></i> 
                            <div>
                                <span class="font-bold">Control de cambios activo:</span> Hay <span class="font-bold text-blue-600">{{ $inscritosCount }} alumno(s)</span> inscrito(s). Datos básicos, fecha y hora bloqueados para proteger sus agendas.
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre de la Clase</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $claseParaEditar->nombre) }}" required
                            {{ $tieneInscritos ? 'disabled' : '' }}
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm {{ $tieneInscritos ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', $claseParaEditar->fecha ? \Carbon\Carbon::parse($claseParaEditar->fecha)->format('Y-m-d') : '') }}" required
                                {{ $tieneInscritos ? 'disabled' : '' }}
                                min="{{ date('Y-m-d') }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm {{ $tieneInscritos ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Hora de Inicio</label>
                            <input type="time" name="hora" value="{{ old('hora', $claseParaEditar->hora ? \Carbon\Carbon::parse($claseParaEditar->hora)->format('H:i') : '') }}" required
                                {{ $tieneInscritos ? 'disabled' : '' }}
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm {{ $tieneInscritos ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Seleccionar Entrenador</label>
                        <select name="entrenador_id" required 
                            {{ $tieneInscritos ? 'disabled' : '' }}
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm {{ $tieneInscritos ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}">
                            
                            @foreach($sedes as $s)
                                @php
                                    $entrenadoresDeSede = $allEntrenadores->filter(function($e) use ($s) {
                                        return (int)$e->sede_id === (int)$s->id;
                                    });
                                @endphp

                                <optgroup label="Sede: {{ $s->nombre }}">
                                    @if($entrenadoresDeSede->isEmpty())
                                        <option value="" disabled>— No hay entrenadores activos —</option>
                                    @else
                                        @foreach($entrenadoresDeSede as $entrenador)
                                            @if($entrenador->user)
                                                <option value="{{ $entrenador->user_id }}" 
                                                    {{ old('entrenador_id', $claseParaEditar->entrenador_id) == $entrenador->user_id ? 'selected' : '' }}>
                                                    {{ $entrenador->user->nombre }} {{ $entrenador->user->apellido }} 
                                                    @if($entrenador->especialidad) — [{{ $entrenador->especialidad }}] @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </optgroup>
                            @endforeach
                        </select>
                        @if(!$tieneInscritos)
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                <i class="bi bi-info-circle mr-0.5"></i> Al reasignar a un entrenador de otra sede, la clase cambiará de ubicación de manera automática.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Capacidad Máxima</label>
                        <input type="number" name="capacidad" value="{{ old('capacidad', $claseParaEditar->capacidad) }}" 
                            min="{{ max(1, $inscritosCount) }}" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                        @if($tieneInscritos)
                            <p class="text-[10px] text-amber-600 mt-0.5 font-medium">
                                <i class="bi bi-exclamation-triangle mr-0.5"></i> No puede ser menor a los {{ $inscritosCount }} cupos ocupados.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="2" placeholder="Detalles o requisitos..."
                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">{{ old('descripcion', $claseParaEditar->descripcion) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                        <a href="{{ route('admin.clases.index', ['selected_sede_id' => request('selected_sede_id')]) }}" 
                           class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-5 py-2 text-white font-semibold text-sm rounded-xl transition shadow-sm {{ $tieneInscritos ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-100' : 'bg-amber-500 hover:bg-amber-600 shadow-amber-100' }}">
                            {{ $tieneInscritos ? 'Aplicar Ajustes' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endif


{{-- ======================================================================= --}}
{{-- MODAL: CREAR CLASE
{{-- ======================================================================= --}}
@if(request('open_create'))
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-2xl w-full overflow-hidden flex flex-col antialiased my-auto">
            
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-900 flex items-center">
                    <i class="bi bi-calendar-plus-fill text-blue-600 mr-2"></i> Programar Nueva Clase
                </h3>
                <a href="{{ route('admin.clases.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>

            <div class="p-4">
                @if(!$selectedSedeId)
                    @if ($errors->any())
                        <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-3 rounded-r-xl text-xs text-rose-800 font-medium">
                            <i class="bi bi-exclamation-triangle-fill text-rose-500 mr-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- CAMBIO: Cambiado a método GET apuntando al index para actualizar los parámetros de la URL --}}
                    <form action="{{ route('admin.clases.index') }}" method="GET" class="space-y-3">
                        <input type="hidden" name="open_create" value="1">
                        
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Paso 1: Selecciona la Sede / Filial</label>
                            <select name="selected_sede_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                                <option value="">Selecciona una sede para ver entrenadores disponibles...</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}" {{ request('selected_sede_id') == $sede->id ? 'selected' : '' }}>
                                        {{ $sede->nombre }} ({{ $sede->direccion }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                            <a href="{{ route('admin.clases.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                                Cancelar
                            </a>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-sm inline-flex items-center">
                                Siguiente <i class="bi bi-arrow-right ml-1.5"></i>
                            </button>
                        </div>
                    </form>
                @else
                    <form action="{{ route('admin.clases.store') }}" method="POST" class="space-y-3">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="mb-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs font-semibold flex flex-col gap-1">
                                <div class="flex items-center gap-2 mb-1 text-rose-600">
                                    <i class="bi bi-exclamation-triangle-fill text-sm"></i>
                                    <span class="font-bold">Por favor, corrige los siguientes errores:</span>
                                </div>
                                <ul class="list-disc list-inside pl-1 text-rose-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <input type="hidden" name="sede_id" value="{{ $selectedSedeId }}">
                        <input type="hidden" name="selected_sede_id" value="{{ $selectedSedeId }}">

                        <div class="bg-blue-50 border border-blue-100 text-blue-800 rounded-xl px-3 py-2 text-xs flex justify-between items-center">
                            <div class="flex items-center font-semibold">
                                <i class="bi bi-geo-alt-fill mr-1.5 text-blue-500"></i> 
                                Sede Elegida: <span class="ml-1 font-bold text-blue-700">{{ $sedes->find($selectedSedeId)->nombre }}</span>
                            </div>
                            <a href="{{ route('admin.clases.index', ['open_create' => 1]) }}" class="text-[11px] bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold px-2 py-1 rounded-lg transition-colors flex items-center gap-1">
                                <i class="bi bi-arrow-left"></i> Cambiar Sede
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nombre de la Clase / Deporte</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Crossfit, Yoga" required
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Entrenador Disponible</label>
                                <select name="entrenador_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                                    @if($entrenadores->isEmpty())
                                        <option value="">No hay entrenadores en esta sede</option>
                                    @else
                                        <option value="">Selecciona un entrenador...</option>
                                        @foreach($entrenadores as $entrenador)
                                            @if($entrenador->user)
                                                <option value="{{ $entrenador->user_id }}" {{ old('entrenador_id') == $entrenador->user_id ? 'selected' : '' }}>
                                                    {{ $entrenador->user->nombre }} {{ $entrenador->user->apellido }}
                                                    @if($entrenador->especialidad)
                                                        — [{{ $entrenador->especialidad }}]
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Fecha</label>
                                <input type="date" name="fecha" value="{{ old('fecha') }}" required
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Hora de Inicio</label>
                                <input type="time" name="hora" value="{{ old('hora') }}" required
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Capacidad Máxima</label>
                                <input type="number" name="capacidad" value="{{ old('capacidad', 15) }}" min="1" required
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Descripción (Opcional)</label>
                                <textarea name="descripcion" rows="2" placeholder="Detalles de la clase..."
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/20 shadow-sm">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
                            <a href="{{ route('admin.clases.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                                Cancelar
                            </a>
                            <button type="submit" {{ $entrenadores->isEmpty() ? 'disabled' : '' }} 
                                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-semibold text-sm rounded-xl transition shadow-sm inline-flex items-center gap-1.5">
                                <i class="bi bi-calendar-plus"></i> Crear Clase Asignada
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection