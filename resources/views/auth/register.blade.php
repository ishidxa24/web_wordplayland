<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - WordPlayland</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased">

    {{-- WADAH LUAR UNTUK MELETAKKAN KARTU DI TENGAH --}}
    <div class="min-h-screen flex items-center justify-center bg-wordplay-bg px-4">

        {{-- KARTU UTAMA YANG BERISI DUA KOLOM --}}
        <div class="w-full max-w-4xl grid md:grid-cols-2 rounded-3xl shadow-2xl overflow-hidden">

            {{-- Bagian Kiri (Brand) --}}
            <div class="hidden md:flex flex-col items-center justify-center bg-indigo-900 text-white p-12 relative">
                <div class="z-20 text-center">

                    {{-- ▼▼▼ PERBAIKAN DITERAPKAN DI SINI ▼▼▼ --}}
                    <h1 class="text-5xl lg:text-6xl font-bold mb-4 whitespace-nowrap">
                        <span class="fade-in-up" style="animation-delay: 100ms;">W</span><span class="fade-in-up" style="animation-delay: 200ms;">o</span><span class="fade-in-up" style="animation-delay: 300ms;">r</span><span class="fade-in-up" style="animation-delay: 400ms;">d</span><span class="fade-in-up" style="animation-delay: 500ms;">P</span><span class="fade-in-up" style="animation-delay: 600ms;">l</span><span class="fade-in-up" style="animation-delay: 700ms;">a</span><span class="fade-in-up" style="animation-delay: 800ms;">y</span><span class="fade-in-up" style="animation-delay: 900ms;">l</span><span class="fade-in-up" style="animation-delay: 1000ms;">a</span><span class="fade-in-up" style="animation-delay: 1100ms;">n</span><span class="fade-in-up" style="animation-delay: 1200ms;">d</span>
                    </h1>

                    <p class="fade-in-up text-indigo-200" style="animation-delay: 1.4s;">Your fun way to learn English!</p>
                </div>
            </div>

            {{-- Bagian Kanan (Form Register) --}}
            <div class="flex flex-col justify-center bg-white p-8 sm:p-12">
                <div class="w-full">

                    <div class="w-full mb-8">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-gray-500 hover:text-indigo-600 font-semibold transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7m4 14l-7-7 7-7"></path></svg>
                            Kembali ke Menu Utama
                        </a>
                    </div>

                    <h2 class="text-3xl font-bold mb-2 text-gray-800">Create your account</h2>
                    <p class="text-gray-500 mb-8">Let's get started with your fun learning journey!</p>

                    <form method="POST" action="{{ route('register') }}" x-data="{ showPassword: false, showConfirmPassword: false }">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">NAME</label>
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus class="mt-1 block w-full px-3 py-2 bg-white border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">EMAIL</label>
                            <input id="email" type="email" name="email" :value="old('email')" required class="mt-1 block w-full px-3 py-2 bg-white border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700">PASSWORD</label>
                            <div class="relative">
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required class="mt-1 block w-full px-3 py-2 bg-white border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500">
                                <span @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer text-gray-500 hover:text-gray-700">Show</span>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">CONFIRM PASSWORD</label>
                            <div class="relative">
                                <input id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required class="mt-1 block w-full px-3 py-2 bg-white border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500">
                                <span @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer text-gray-500 hover:text-gray-700">Show</span>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button type="submit" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transition duration-300">
                            Sign Up
                        </button>

                        <div class="text-center mt-6">
                                <p class="text-sm text-gray-600">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:underline">
                                        Sign In
                                    </a>
                                </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
