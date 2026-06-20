@extends('layouts.app')

@section('content')
<div class="py-6 bg-slate-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Encabezado del Dashboard --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                    Panel del Entrenador
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Gestiona tus clases asignadas y el control de asistencia de los socios.
                </p>
            </div>
        </div>

        {{-- Bloque de Alertas (Mensajes de éxito o errores globales) --}}
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

        {{-- Listado de Clases --}}
        <div class="space-y-4">
            @forelse($clases as $clase)
                @php
                    $fechaClase = \Carbon\Carbon::parse($clase->fecha . ' ' . $clase->hora);
                    $yaIniciada = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaClase);
                @endphp

                <details class="group bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    
                    {{-- Cabecera de la Clase --}}
                    <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition list-none select-none border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <span class="transition-transform duration-200 group-open:rotate-180 text-slate-400 text-xs">
                                ▼
                            </span>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $clase->nombre }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 flex flex-wrap gap-x-3 gap-y-1.5">
                                    <span>📅 {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}</span>
                                    <span>🕒 {{ \Carbon\Carbon::parse($clase->hora)->format('H:i') }} hs</span>
                                    <span>📍 {{ $clase->sede->nombre ?? 'Sin Sede' }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                Inscritos: {{ $clase->socios->count() }}
                            </span>
                        </div>
                    </summary>

                    {{-- Contenido Desplegable: Tabla de Alumnos --}}
                    <div class="p-4 bg-slate-50/50">
                        @if($clase->socios->isEmpty())
                            <div class="text-center py-6 text-sm text-slate-500 italic">
                                No hay socios inscritos en esta clase todavía.
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold tracking-wider">
                                        <tr>
                                            <th class="py-3 px-4">Socio</th>
                                            <th class="py-3 px-4 text-center">Estado Actual</th>
                                            <th class="py-3 px-4 text-right">Tomar Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 text-slate-700">
                                        @foreach($clase->socios as $socio)
                                            @php
                                                $status = $socio->pivot->asistencia ?? 'PENDIENTE';
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="py-3 px-4 font-medium text-slate-900 whitespace-nowrap">
                                                    {{ $socio->user->nombre }} {{ $socio->user->apellido }}
                                                </td>
                                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                                    <div id="status-badge-{{ $clase->id }}-{{ $socio->user_id }}">
                                                        @if($status === 'SI')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">● Asistió</span>
                                                        @elseif($status === 'NO')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">● Faltó</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">● Pendiente</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                                    @if(!$yaIniciada)
                                                        <span class="text-xs text-slate-400 italic">No disponible hasta el inicio</span>
                                                    @else
                                                        <div class="inline-flex gap-2 m-0">
                                                            <button type="button" 
                                                                    onclick="cambiarAsistencia('{{ $clase->id }}', '{{ $socio->user_id }}', 'SI')"
                                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border bg-emerald-500 hover:bg-emerald-600 text-white border-transparent"
                                                                    title="Marcar Presente">
                                                                ✓ Presente
                                                            </button>
                                                            <button type="button" 
                                                                    onclick="cambiarAsistencia('{{ $clase->id }}', '{{ $socio->user_id }}', 'NO')"
                                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm border bg-rose-500 hover:bg-rose-600 text-white border-transparent"
                                                                    title="Marcar Ausente">
                                                                ✗ Ausente
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="mt-4 flex justify-end">
                            <form action="{{ route('entrenador.clases.finalizar', $clase->id) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de finalizar esta clase? Pasará al historial y guardará todo definitivamente.');">
                                @csrf
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold py-2 px-4 rounded-xl transition shadow-sm inline-flex items-center gap-1.5">
                                    <i class="bi bi-archive-fill"></i> Finalizar y Archivar Clase
                                </button>
                            </form>
                        </div>

                    </div>
                </details>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-slate-300 text-slate-500 shadow-sm">
                    <p class="text-base font-medium text-slate-600">No tienes clases programadas asignadas.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 border-t border-slate-200 pt-8">
            <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                ⏱ Historial de Clases Finalizadas
            </h3>
            
            @if($historial->isEmpty())
                <div class="text-center py-8 bg-slate-100/50 rounded-xl border border-slate-200 text-slate-500 italic text-sm">
                    No tienes clases archivadas en tu historial todavía.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($historial as $h)
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm opacity-85 hover:opacity-100 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-base">{{ $h->nombre }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 flex gap-3">
                                        <span>📅 {{ \Carbon\Carbon::parse($h->fecha)->format('d/m/Y') }}</span>
                                        <span>🕒 {{ \Carbon\Carbon::parse($h->hora)->format('H:i') }} hs</span>
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    Archivada
                                </span>
                            </div>
                            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-600 flex justify-between">
                                <span>Socios registrados: <strong>{{ $h->socios->count() }}</strong></span>
                                <span class="text-slate-400">Sede: {{ $h->sede->nombre ?? 'N/A' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<script>
function cambiarAsistencia(claseId, socioId, valor) {
    
    fetch(`/entrenador/clases/${claseId}/socio/${socioId}/asistencia`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSR-Origin': window.location.origin,
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ asistio: valor })
    }).then(() => {
        const badgeContainer = document.getElementById(`status-badge-${claseId}-${socioId}`);
        if (valor === 'SI') {
            badgeContainer.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">● Asistió</span>';
        } else {
            badgeContainer.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">● Faltó</span>';
        }
    }).catch(err => console.error("Error al guardar asistencia:", err));
}
</script>
@endsection