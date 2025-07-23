<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to WordPlayland!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
</head>
<body class="antialiased">

    <div x-data="{
            isLoading: false,
            startGame() {
                this.isLoading = true;
                document.getElementById('welcome-sfx')?.play().catch(e => console.error(e));
                setTimeout(() => {
                    window.location.href = '{{ route('welcome') }}';
                }, 1500);
            }
         }"
         class="splash-screen w-full min-h-screen flex flex-col items-center justify-center text-white p-4">

        <div class="text-center">
            {{-- ▼▼▼ KODE HTML UNTUK TEKS DIUBAH UNTUK ANIMASI ▼▼▼ --}}
            <div class="text-4xl sm:text-5xl md:text-6xl font-bold tracking-wider" style="font-family: 'Fredoka One', cursive; text-shadow: 3px 3px 6px rgba(0,0,0,0.4);">
                <span class="fade-in-up" style="animation-delay: 100ms;">W</span><span class="fade-in-up" style="animation-delay: 200ms;">e</span><span class="fade-in-up" style="animation-delay: 300ms;">l</span><span class="fade-in-up" style="animation-delay: 400ms;">c</span><span class="fade-in-up" style="animation-delay: 500ms;">o</span><span class="fade-in-up" style="animation-delay: 600ms;">m</span><span class="fade-in-up" style="animation-delay: 700ms;">e</span>
                <span class="fade-in-up" style="animation-delay: 800ms;">t</span><span class="fade-in-up" style="animation-delay: 900ms;">o</span>
            </div>

            {{-- Ukuran teks disamakan dengan "Welcome to" --}}
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mt-2" style="font-family: 'Fredoka One', cursive; text-shadow: 4px 4px 8px rgba(0,0,0,0.4);">
                <span class="fade-in-up" style="animation-delay: 1000ms;">W</span><span class="fade-in-up" style="animation-delay: 1100ms;">o</span><span class="fade-in-up" style="animation-delay: 1200ms;">r</span><span class="fade-in-up" style="animation-delay: 1300ms;">d</span><span class="fade-in-up" style="animation-delay: 1400ms;">P</span><span class="fade-in-up" style="animation-delay: 1500ms;">l</span><span class="fade-in-up" style="animation-delay: 1600ms;">a</span><span class="fade-in-up" style="animation-delay: 1700ms;">y</span><span class="fade-in-up" style="animation-delay: 1800ms;">l</span><span class="fade-in-up" style="animation-delay: 1900ms;">a</span><span class="fade-in-up" style="animation-delay: 2000ms;">n</span><span class="fade-in-up" style="animation-delay: 2100ms;">d</span>
            </h1>

            <p class="mt-4 text-lg sm:text-xl md:text-2xl text-gray-200 fade-in-up" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5); animation-delay: 2.3s;">
                Your fun way to learn English!
            </p>
        </div>

        <div class="mt-16 h-20 flex items-center justify-center">
            <button
                x-show="!isLoading"
                @click="startGame()"
                class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold text-2xl sm:text-3xl py-4 px-12 sm:py-5 sm:px-16 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out border-4 border-yellow-300 fade-in-up"
                style="animation-delay: 2.5s;">
                Start Game
            </button>
            <div x-show="isLoading" x-cloak class="loader"></div>
        </div>
    </div>

    <audio id="welcome-sfx" src="{{ asset('audio/sfx/welcome-sfx.mp3') }}" preload="auto"></audio>

</body>
</html>
