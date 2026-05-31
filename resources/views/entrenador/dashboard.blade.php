@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <div class="bg-white p-8 rounded-3xl border border-slate-300/30 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#ff9a01]">Panel del Entrenador</span>
            <h1 class="text-3xl font-bold tracking-tight text-[#002d55] mt-1">¡Buen día, Coach {{ Auth::user()->nombre }}!</h1>
            <p class="text-sm text-slate-500 mt-1">Gestiona tus clases asignadas y lleva el control de asistencia de tus alumnos.</p>
        </div>
        <div class="bg-[#002d55] text-white px-4 py-2 rounded-xl text-xs font-semibold">
            Estado: <span class="text-emerald-400 font-bold uppercase">Activo</span>
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-bold text-[#002d55] flex items-center gap-2">
            <i class="bi bi-calendar3 text-[#ff9a01]"></i> Mis Clases Asignadas
        </h2>

        {{-- Предполагается, что из контроллера мы передадим переменную $clases --}}
        @if(isset($clases) && $clases->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clases as $clase)
                    <div class="bg-white rounded-3xl border border-slate-300/30 shadow-sm overflow-hidden flex flex-col justify-between">
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-start">
                                <span class="px-3 py-1 bg-[#ff9a01]/10 text-[#ff9a01] rounded-lg text-xs font-bold uppercase">
                                    {{ $clase->nombre }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">
                                    <i class="bi bi-geo-alt"></i> {{ $clase->sede->nombre ?? 'Sede Principal' }}
                                </span>
                            </div>

                            <p class="text-sm text-slate-600 line-clamp-2">{{ $clase->descripcion }}</p>

                            <div class="pt-2 grid grid-cols-2 gap-2 text-xs text-slate-500">
                                <div><i class="bi bi-calendar-event text-[#002d55]"></i> {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m/Y') }}</div>
                                <div><i class="bi bi-clock text-[#002d55]"></i> {{ $clase->hora }} hs</div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-xs font-medium text-slate-500">
                                <i class="bi bi-people"></i> {{ $clase->socios->count() }} / {{ $clase->capacidadMaxima }} scriptos
                            </span>
                            
                            {{-- Кнопка вызывает JavaScript, который откроет модалку для конкретного класса --}}
                            <button type="button" 
                                    onclick="openAsistenciaModal({{ $clase->id }}, '{{ $clase->nombre }}')" 
                                    class="text-xs font-bold text-[#ff9a01] hover:text-[#e08800] transition flex items-center gap-1">
                                <i class="bi bi-check2-square"></i> Tomar Asistencia
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-12 rounded-3xl border border-dashed border-slate-300 text-center space-y-3">
                <div class="text-4xl text-slate-300"><i class="bi bi-emoji-neutral"></i></div>
                <h3 class="text-base font-bold text-[#002d55]">No tienes clases asignadas</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Cuando el administrador te asocie a un horario, tus clases aparecerán organizadas en esta sección.</p>
            </div>
        @endif
    </div>
</div>

<div id="asistenciaModal" class="hidden fixed inset-0 bg-[#002d55]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-xl overflow-hidden border border-slate-200 flex flex-col max-h-[85vh]">
        
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 class="text-lg font-bold text-[#002d55]" id="modalClaseTitle">Control de Asistencia</h3>
                <p class="text-xs text-slate-400 mt-0.5">Marca a los alumnos presentes hoy</p>
            </div>
            <button onclick="closeAsistenciaModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
        </div>

        {{-- Экшн формы будет динамически меняться через JS --}}
        <form id="asistenciaForm" method="POST" action="" class="flex flex-col justify-between overflow-hidden flex-1">
            @csrf
            
            <div class="p-6 overflow-y-auto space-y-3 flex-1" id="sociosListContainer">
                </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeAsistenciaModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#ff9a01] hover:bg-[#e08800] text-white text-xs font-bold rounded-xl shadow-md transition">
                    Guardar Asistencia
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAsistenciaModal(claseId, claseNombre) {
        // 1. Меняем заголовок модалки
        document.getElementById('modalClaseTitle').innerText = 'Asistencia: ' + claseNombre;
        
        // 2. Устанавливаем динамический URL для отправки формы (меняй под свой роут обработки)
        document.getElementById('asistenciaForm').action = '/clases/' + claseId + '/asistencia';

        // 3. Эмуляция динамической загрузки списка учеников, записанных на этот класс (Inscripciones)
        // В реальном проекте тут лучше сделать быстрый fetch() запрос к вашему API, 
        // но для начала мы можем передавать данные или сгенерировать их для теста:
        const container = document.getElementById('sociosListContainer');
        container.innerHTML = ''; // Чистим старый список

        // Примерочный список студентов для теста верстки (в продакшене соберешь из связи $clase->socios)
        const mockSocios = [
            { id: 1, nombre: "Benicio", apellido: "Bravo", dni: "33333333" },
            { id: 2, nombre: "Juan", apellido: "Pérez", dni: "44444444" }
        ];

        if(mockSocios.length > 0) {
            mockSocios.forEach(socio => {
                const item = document.createElement('div');
                item.className = "flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-white hover:bg-slate-50 transition";
                item.innerHTML = `
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-[#002d55]">${socio.nombre} ${socio.apellido}</span>
                        <span class="text-[11px] text-slate-400">DNI: ${socio.dni}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="asistencia[]" value="${socio.id}" class="w-4 h-4 text-[#ff9a01] border-slate-300 rounded focus:ring-[#ff9a01]">
                    </label>
                `;
                container.appendChild(item);
            });
        } else {
            container.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">No hay alumnos inscritos en esta clase aún.</p>';
        }

        // Показываем модалку
        document.getElementById('asistenciaModal').classList.remove('hidden');
    }

    function closeAsistenciaModal() {
        document.getElementById('asistenciaModal').classList.add('hidden');
    }
</script>
@endsection