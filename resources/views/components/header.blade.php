<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
    <!-- Título dinámico de la página/sección actual -->
    <div class="text-xl font-bold text-gray-800">
        {{ $slot->isEmpty() ? ($title ?? 'Panel Principal') : $slot }}
    </div>

    <!-- Menú del Usuario Autenticado -->
    <div class="flex items-center gap-4">
        <x-dropdown align="right" width="48">
            <!-- Botón que abre el menú -->
            <x-slot name="trigger">
                <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->nombre ?? Auth::user()->name }}</div>
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <!-- Contenido desplegable -->
            <x-slot name="content">
                <!-- Enlace a la edición de perfil -->
                <x-dropdown-link :href="route('profile.edit')">
                    Mi Perfil
                </x-dropdown-link>

                <!-- Cerrar sesión seguro con token CSRF -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar Sesión
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>