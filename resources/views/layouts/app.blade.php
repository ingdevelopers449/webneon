<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="flex min-h-screen">

        <!-- 1. SIDEBAR CONDICIONAL POR ROL -->
        @if(Auth::check() && Auth::user()->id_rol === 1)
            <x-sidebar-admin />
        @else
            <x-sidebar-cliente />
        @endif

        <!-- 2. CONTENEDOR PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header compartido -->
            <x-header>
                {{ $header ?? 'Panel de Control' }}
            </x-header>

            <!-- Contenido dinámico inyectado por las vistas -->
            <main class="p-6 flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>
