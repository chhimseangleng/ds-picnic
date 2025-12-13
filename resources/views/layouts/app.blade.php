<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="flex min-h-screen bg-gray-100">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Toast Messages (bottom-right) --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-3">

        @if (session('success'))
            <div id="toast-success"
                class="bg-green-600 text-white px-4 py-3 rounded shadow-lg flex items-center justify-between transition-opacity duration-500">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-3 text-white font-bold">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div id="toast-error"
                class="bg-red-600 text-white px-4 py-3 rounded shadow-lg transition-opacity duration-500">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Auto-hide Toast --}}
    <script>
        // SUCCESS TOAST
        const successToast = document.getElementById('toast-success');
        if (successToast) {
            setTimeout(() => {
                successToast.classList.add('opacity-0');
                setTimeout(() => successToast.remove(), 500);
            }, 3000);
        }

        // ERROR TOAST
        const errorToast = document.getElementById('toast-error');
        if (errorToast) {
            setTimeout(() => {
                errorToast.classList.add('opacity-0');
                setTimeout(() => errorToast.remove(), 500);
            }, 3000);
        }
    </script>

</body>

</html>
