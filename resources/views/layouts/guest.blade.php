<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Kita ganti judulnya agar lebih sesuai --}}
        <title>WordPlayland</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- UBAH WARNA LATAR BELAKANG DI SINI --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-blue-200 dark:bg-blue-900">
            <div>
                <a href="/">
                    {{-- GANTI LOGO LARAVEL DENGAN LOGO ANDA --}}
                    <img src="{{ asset('images/logo.png') }}" alt="WordPlayland Logo" class="w-24 h-24">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{-- Di sinilah nanti form login/register akan muncul --}}
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
