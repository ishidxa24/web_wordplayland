<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercises - WordPlayland</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body, html {
            height: 100%;
        }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="bg-red-300 min-h-screen flex flex-col items-center p-4 sm:p-6">

        {{-- Header --}}
        <div class="w-full max-w-2xl mx-auto mb-8">
            <div class="relative flex items-center justify-center">
                {{-- Tombol kembali ini sudah benar mengarah ke 'welcome' --}}
                <a href="{{ route('welcome') }}" class="absolute left-0 bg-white/50 p-2 rounded-full transform hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-4xl font-bold text-red-800">Exercises</h1>
            </div>
        </div>

        {{-- Kartu Pilihan Mode --}}
        <div class="bg-red-50/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg w-full max-w-2xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Choose Your Activity</h2>
            <p class="text-gray-600 mb-6">Select an activity to start learning and practicing!</p>

            <div class="space-y-4">

                {{-- Tautan ke halaman Learn Materials --}}
                <a href="{{ route('games.learn') }}" class="w-full flex items-center bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition-transform duration-300">
                    <div class="text-4xl mr-4">📚</div>
                    <div>
                        <h3 class="text-xl font-bold text-left text-gray-800">Learn Materials</h3>
                        <p class="text-left text-gray-500">Read and understand basic English concepts.</p>
                    </div>
                </a>

                {{-- Tautan ke halaman Pronunciation Practice --}}
                <a href="{{ route('games.pronunciation') }}" class="w-full flex items-center bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition-transform duration-300">
                    <div class="text-4xl mr-4">🎙️</div>
                    <div>
                        <h3 class="text-xl font-bold text-left text-gray-800">Pronunciation Practice</h3>
                        <p class="text-left text-gray-500">Practice your pronunciation and get feedback.</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</body>
</html>
