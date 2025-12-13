<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Full screen split container -->
        <div class="flex flex-col md:flex-row min-h-screen">

            <!-- Left Side: Logo -->
            <div class="md:w-1/2 bg-white flex items-center justify-center p-10">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="w-2/3">
            </div>

            <!-- Right Side: Blue background + Login box -->
            <div class="md:w-1/2 bg-blue-600 flex items-center justify-center p-10">
                <div class="w-full max-w-lg bg-blue-100 p-8 rounded-lg shadow-lg">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
