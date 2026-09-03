<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <p>¡Bienvenido al sistema, {{ Auth::user()->nombre ?? Auth::user()->name }}!</p>
    </div>
</x-app-layout>
