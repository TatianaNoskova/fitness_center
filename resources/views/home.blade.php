@extends('layouts.app')

@section('content')
<div class="space-y-32 py-12">
    
    <header class="grid grid-cols-1 md:grid-cols-12 gap-16 items-center">
        <div class="md:col-span-6 space-y-8">
            <span class="inline-block bg-[#ff9a01]/10 text-[#ff9a01] text-xs font-bold tracking-wider uppercase px-3 py-1.5 rounded-lg border border-[#ff9a01]/20">
                Estilo & Disciplina
            </span>
            
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/hero-runners.png') }}" 
                    alt="Runners Icon" 
                    class="h-[3.5em] md:h-[4.5em] w-auto object-contain select-none pointer-events-none">
                
                <h1 class="text-4xl md:text-5xl font-light tracking-tight leading-none text-[#002d55]">
                    Tu cuerpo. <br>
                    <span class="font-bold text-[#002d55]">Tu espacio.</span>
                </h1>
            </div>      

            <p class="text-slate-500 text-lg max-w-md font-light leading-relaxed">
                Una red integrada de clubes modernos diseñados para el alto rendimiento y tu bienestar diario.
            </p>
            
            @guest
                <div class="pt-2">
                    <a href="{{ route('register') }}" class="inline-block bg-[#002d55] text-white px-8 py-4 text-xs font-bold tracking-widest uppercase hover:opacity-90 transition rounded-xl shadow-sm">
                        Empezar ahora &rarr;
                    </a>
                </div>
            @endguest
        </div>

        <div class="md:col-span-6">
            <div id="hero-slider" class="bg-white aspect-[4/3] w-full flex items-center justify-center border border-black/5 rounded-3xl relative group overflow-hidden shadow-sm">
                
                <img src="{{ asset('images/hero-focus.png') }}" 
                     alt="WorldClass Lifestyle 1" 
                     class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out group-hover:scale-105 opacity-100 z-10">
                
                <img src="{{ asset('images/hero-focus-2.png') }}" 
                     alt="WorldClass Lifestyle 2" 
                     class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out group-hover:scale-105 opacity-0 z-0">
                
                <img src="{{ asset('images/hero-focus-3.png') }}" 
                     alt="WorldClass Lifestyle 3" 
                     class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out group-hover:scale-105 opacity-0 z-0">

                <div class="absolute inset-0 bg-gradient-to-t from-[#002d55]/10 to-transparent pointer-events-none z-20"></div>
            </div>
        </div>

        <script>
            (function() {
                const slider = document.getElementById('hero-slider');
                if (!slider) return;

                const slides = slider.querySelectorAll('.slide-img');
                if (slides.length === 0) return;

                let currentSlide = 0;
                const intervalTime = 4000;

                function changeSlide() {
                    slides[currentSlide].classList.remove('opacity-100', 'z-10');
                    slides[currentSlide].classList.add('opacity-0', 'z-0');
                    
                    currentSlide = (currentSlide + 1) % slides.length;
                    
                    slides[currentSlide].classList.remove('opacity-0', 'z-0');
                    slides[currentSlide].classList.add('opacity-100', 'z-10');
                }

                setInterval(changeSlide, intervalTime);
            })();
        </script>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 !mt-12">
        <div class="{{ $recommendation['bg'] }} rounded-3xl p-6 flex items-start space-x-4 transition duration-300 shadow-sm md:col-span-2">
            <div class="p-3 bg-white rounded-2xl shadow-sm flex-shrink-0 flex items-center justify-center">
                <i class="bi {{ $recommendation['icon'] }} text-2xl"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#002d55] tracking-tight">{{ $recommendation['title'] }}</h3>
                <p class="text-sm text-slate-500 mt-1 font-light leading-relaxed">{{ $recommendation['text'] }}</p>
            </div>
        </div>

        <div class="bg-white border border-black/5 rounded-3xl p-6 flex flex-col justify-between shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Estado del Clima</p>
                    <p class="text-xs text-slate-400 mt-0.5">Buenos Aires</p>
                </div>
                <i class="bi bi-cloud-sun text-slate-400 text-lg"></i>
            </div>
            
            <div class="mt-4 flex items-baseline">
                @if($weatherData)
                    <span class="text-3xl font-bold tracking-tight text-[#002d55]">{{ round($weatherData['temperature_2m']) }}°C</span>
                    <span class="text-xs font-medium text-slate-400 ml-2">en vivo</span>
                @else
                    <span class="text-sm text-slate-400 font-light">Clima no disponible</span>
                @endif
            </div>
        </div>
    </div>

    <section>
        <div class="mb-12">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Espacios</p>
            <h2 class="text-2xl font-bold tracking-tight text-[#002d55] mt-1">Nuestras Sedes</h2>
        </div>
        
        @if($sedes->isEmpty())
            <div class="bg-white border border-dashed border-black/10 rounded-2xl p-12 text-center text-slate-400 text-sm tracking-wide shadow-sm">
                No hay sedes disponibles en este momento.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($sedes as $sede)
                    <div class="space-y-4 group cursor-pointer">
                        <div class="bg-white aspect-[16/10] w-full flex items-center justify-center border border-black/5 rounded-2xl overflow-hidden shadow-sm transition duration-500 group-hover:border-[#002d55]/20 relative">
                            @php
                                $nombreSede = strtolower($sede->nombre);
                                $imageName = 'sede-centro.png';

                                if (str_contains($nombreSede, 'norte')) {
                                    $imageName = 'sede-norte.png';
                                } elseif (str_contains($nombreSede, 'sur')) {
                                    $imageName = 'sede-sur.png';
                                }
                            @endphp
                            <img src="{{ asset('images/' . $imageName) }}" 
                                 alt="Sede {{ $sede->nombre }}" 
                                 class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-110 opacity-90">
                            <div class="absolute inset-0 bg-[#002d55]/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                <span class="text-[10px] uppercase tracking-widest text-white font-bold">{{ $sede->nombre }}</span>
                            </div>
                        </div>
                        <div class="space-y-1 px-1">
                            <h3 class="text-base font-bold text-[#002d55] transition">{{ $sede->nombre }}</h3>
                            <p class="text-xs text-slate-400 font-normal flex items-center">
                                <i class="bi bi-geo-alt text-[#ff9a01] mr-1"></i> {{ $sede->direccion ?? 'Dirección no especificada' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="pb-12">
        <div class="mb-12">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Membresías</p>
            <h2 class="text-2xl font-bold tracking-tight text-[#002d55] mt-1">Planes Disponibles</h2>
        </div>
        
        @if($plans->isEmpty())
            <div class="bg-white border border-dashed border-black/10 rounded-2xl p-12 text-center text-slate-400 text-sm tracking-wide shadow-sm">
                Consulte los planes vigentes de forma presencial.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                @foreach($plans as $plan)
                    <div class="border border-black/5 rounded-3xl p-8 flex flex-col justify-between min-h-[340px] bg-white hover:border-[#002d55]/30 hover:shadow-md transition duration-300 relative group">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-[#002d55]">{{ $plan->nombre }}</h3>
                                <i class="bi bi-patch-check text-[#ff9a01] text-base"></i>
                            </div>
                            <p class="text-s text-slate-400 font-light leading-relaxed">{{ $plan->descripcion ?? 'Acceso completo a las instalaciones.' }}</p>
                        </div>
                        <div class="mt-8 space-y-6">
                            <div class="flex items-baseline">
                                <span class="text-4xl font-bold tracking-tight text-[#ff9a01]">${{ number_format($plan->precio ?? 0, 0) }}</span>
                                <span class="text-slate-400 text-xs font-normal ml-2">/ mes</span>
                            </div>
                            <a href="{{ route('register') }}" class="block w-full text-center border-2 border-[#002d55] text-[#002d55] py-3 text-[11px] font-bold tracking-widest uppercase hover:bg-[#002d55] hover:text-white transition duration-300 rounded-xl">
                                Empezar Ahora
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection