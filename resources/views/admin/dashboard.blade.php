@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">¡Bienvenida al Panel de Control!</h1>
    <p class="text-slate-500 mt-1">Система управления фитнес-центром на базе Tailwind CSS. Всё чисто, аккуратно и по сетке.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Мониторинг</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Sedes (Филиалы)</h3>
                <p class="text-sm text-slate-500 mt-2">Управление локациями, контактами и расписанием залов.</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="bi bi-building text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/sedes-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Gestionar Sedes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">База данных</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Socios (Клиенты)</h3>
                <p class="text-sm text-slate-500 mt-2">Регистрация членов клуба, проверка статусов активности и карт.</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="bi bi-people text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/socios-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Ver Clientes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Продажи</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Planes (Тарифы)</h3>
                <p class="text-sm text-slate-500 mt-2">Конструктор абонементов, настройка цен и длительности доступа.</p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i class="bi bi-card-checklist text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/plans-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Ver Planes
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Расписание</span>
                <h3 class="text-xl font-bold text-slate-800 mt-1">Clases (Занятия)</h3>
                <p class="text-sm text-slate-500 mt-2">Управление тренировками, графиком тренеров и записью клиентов.</p>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i class="bi bi-calendar3 text-xl"></i>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/clases-view') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition shadow-sm">
                Gestionar Clases
            </a>
        </div>
    </div>

</div>
@endsection