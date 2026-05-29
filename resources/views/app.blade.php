<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Club - Панель управления</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 text-xl font-bold text-slate-900 tracking-tight">
                        <span class="p-2 bg-rose-500 text-white rounded-xl shadow-sm shadow-rose-200">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </span>
                        <span>Fitness<span class="text-rose-500 font-medium">Club</span></span>
                    </a>
                    
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ url('/dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition"><i class="bi bi-speedometer2"></i> Dashboard</a>
                        <a href="{{ url('/sedes-view') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition"><i class="bi bi-building"></i> Sedes</a>
                        <a href="{{ url('/socios-view') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition"><i class="bi bi-people"></i> Socios</a>
                        <a href="{{ url('/plans-view') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition"><i class="bi bi-card-checklist"></i> Planes</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <footer class="max-w-7xl mx-auto px-4 text-center text-sm text-slate-400 py-8 border-t border-slate-200 mt-12">
        &copy; {{ date('Y') }} — Sistema de Gestión Fitness Club • Разработано со вкусом
    </footer>

</body>
</html>