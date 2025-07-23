<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Menambahkan style untuk latar belakang --}}
    <style>
        .auth-background {
            background-color: #87CEEB; /* Sky Blue Fallback */
            background-image: linear-gradient(to top, #a7f3d0, #60a5fa); /* Gradasi dari hijau mint ke biru */
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="auth-background min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 p-4">

        {{-- Judul Aplikasi --}}
        <a href="{{ url('/') }}">
            <h1 class="text-5xl font-bold text-white mb-6" style="font-family: 'Fredoka One', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                WordPlayland
            </h1>
        </a>

        {{-- Kartu Formulir --}}
        <div class="w-full sm:max-w-md px-6 py-8 bg-white/80 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-2xl">

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-700">Forgot Password?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    No problem. We'll send you a reset link.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Input Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-6">
                    <x-primary-button class="w-full justify-center text-lg py-3 bg-blue-500 hover:bg-blue-600">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="text-center mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    &larr; Back to login
                </a>
            </div>

        </div>
    </div>
</body>
</html>
