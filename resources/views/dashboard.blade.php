<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - WordPlayland</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased font-sans bg-sky-200">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800" style="font-family: 'Fredoka One', cursive;">
                    My Dashboard
                </h1>
            </div>

            {{-- Kartu Profil Pengguna --}}
            <div class="bg-white/70 backdrop-blur-sm overflow-hidden shadow-2xl rounded-2xl mb-8">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-shrink-0 mb-6 md:mb-0 md:mr-8">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center border-4 border-white/50 text-white">
                                <span class="text-6xl font-bold" style="font-family: 'Fredoka One', cursive;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="flex-grow text-center md:text-left">
                            <p class="text-xl font-bold text-purple-600">{{ $user->title }}</p>
                            <h3 class="text-4xl md:text-5xl font-bold text-gray-800" style="font-family: 'Fredoka One', cursive;">{{ $user->name }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Permainan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-center">
                    <div class="text-5xl mb-2">🏆</div>
                    <h4 class="text-lg font-semibold text-gray-500">Total Score</h4>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ number_format($user->gameScores->sum('score')) }}
                    </p>
                </div>
                <div class="bg-white/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-center">
                    <div class="text-5xl mb-2">🎮</div>
                    <h4 class="text-lg font-semibold text-gray-500">Games Played</h4>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $user->gameScores->count() }}
                    </p>
                </div>
                <div class="bg-white/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-center">
                    <div class="text-5xl mb-2">📅</div>
                    <h4 class="text-lg font-semibold text-gray-500">Member Since</h4>
                    <p class="text-3xl font-bold text-gray-800">{{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 pt-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 justify-center">
                <a href="{{ route('welcome') }}" class="w-full sm:w-auto text-center inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition shadow-lg">
                    Back to Menu
                </a>
                <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto text-center inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-lg transition shadow-lg">
                    Edit Profile
                </a>
            </div>

        </div>
    </div>

    {{-- Script untuk auto-refresh data saat kembali ke halaman ini --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
