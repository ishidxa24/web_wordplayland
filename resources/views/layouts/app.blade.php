<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .animated-clouds {
                background-color: #87CEEB; /* Warna langit biru sebagai fallback */
                background-image:
                    url('https://www.transparentpng.com/thumb/clouds/aJ5LIJ-clouds-transparent-background.png'),
                    url('https://www.transparentpng.com/thumb/clouds/T2kC4S-clouds-transparent-picture.png');
                background-repeat: no-repeat;
                background-position: 10% 20%, 90% 80%;
                background-size: 400px, 350px;
                /* Anda bisa mengganti URL di atas dengan gambar awan Anda sendiri */
            }
        </style>

        <script>
            function playSoundEffect(filename) {
                // Cari speaker khusus untuk SFX
                const sfxPlayer = document.getElementById('sfx-player');
                if (sfxPlayer) {
                    // Set sumber suara berdasarkan nama file
                    sfxPlayer.src = "{{ asset('audio/sfx/') }}/" + filename;
                    // Putar suaranya
                    sfxPlayer.play();
                }
            }
        </script>
    </head>
    {{-- ▼▼▼ MENAMBAHKAN KELAS LATAR BELAKANG AWAN DI SINI ▼▼▼ --}}
    <body class="font-sans antialiased animated-clouds">
        {{-- ▼▼▼ MENGHAPUS WARNA LATAR BELAKANG BAWAAN DARI DIV INI ▼▼▼ --}}
        <div class="min-h-screen">
            {{-- Navigasi sekarang dibuat semi-transparan --}}
            @include('layouts.navigation')

            @isset($header)
                {{-- Membuat header transparan agar menyatu --}}
                <header class="bg-white/30 backdrop-blur-sm shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <audio id="game-music" loop></audio>
        <audio id="sfx-player"></audio>
    </body>
</html>
