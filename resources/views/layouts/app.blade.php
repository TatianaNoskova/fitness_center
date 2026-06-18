<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Club - Спортивный Стальной</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @keyframes heartbeat-three-times {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 8, 57, 0.4); }
            50% { transform: scale(1.12); box-shadow: 0 0 0 10px rgba(212, 8, 57, 0); }
        }
        .animate-pulse-three { animation: heartbeat-three-times 0.7s ease-in-out 3; }
    </style>
</head>
<body class="bg-[#eef2f5] text-slate-700 font-sans antialiased min-h-screen flex flex-col justify-between">

    <nav class="bg-white border-b border-slate-300/40 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            
            <div class="flex items-center space-x-10">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 text-2xl font-bold tracking-tight">
                    <span class="p-2.5 bg-[#d40839] text-white rounded-xl inline-flex items-center justify-center animate-pulse-three">
                        <i class="bi bi-heart-pulse-fill text-xl leading-none"></i>
                    </span>
                    <span class="font-bold text-[#002d55] tracking-tight">World<span class="text-[#d40839] font-medium italic">Class</span></span>
                </a>
                
                @auth
                <div class="hidden md:flex space-x-1">
                    
                    @if(auth()->user()->rol === 'administrador')
                        @foreach($adminNavbarItems as $item)
                            <a href="{{ route($item['route']) }}" 
                            class="px-3 py-2 rounded-lg text-xs font-semibold uppercase tracking-wider transition {{ request()->routeIs($item['route']) ? 'bg-slate-100 text-[#002d55]' : 'text-slate-500 hover:bg-slate-100 hover:text-[#002d55]' }}">
                                <i class="bi {{ $item['icon'] }} mr-1"></i> {{ $item['label'] }}
                            </a>
                        @endforeach
                    @endif

                    

                </div>
@endauth
            </div>

            <div class="flex items-center space-x-6 text-xs font-semibold tracking-wider">
                @auth
                    <span class="text-slate-400 font-normal">Hola, <span class="text-[#002d55] font-medium">{{ Auth::user()->nombre ?? Auth::user()->email }}</span></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-[#d40839] hover:opacity-80 font-bold transition uppercase">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-500 hover:text-[#002d55] transition uppercase">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="bg-[#002d55] text-white px-5 py-3 hover:opacity-90 transition rounded-xl uppercase tracking-wider">Registrarse</a>
                @endauth
            </div>

        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12 flex-grow w-full">
        @yield('content')
    </main>

    <footer class="w-full bg-white border-t border-slate-300/40 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400 tracking-wide gap-4">
            <p>&copy; {{ date('Y') }} — Fitness Club</p>
            <p class="font-bold text-slate-400 tracking-widest text-[10px]">STAY FOCUSED</p>
        </div>
    </footer>

    <script>
    // Una vez por hora (3600000 ms) checkeamos y renovamos CSRF-tocken
    setInterval(function() {
        fetch('/refresh-csrf')
            .then(response => response.json())
            .then(data => {
                
                let meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', data.token);
                
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
            }).catch(err => console.log('Error al refrescar el token'));
    }, 3600000); 
</script>

</body>
</html>