<aside class="w-64 bg-slate-900 text-white min-h-screen flex flex-col justify-between shadow-lg">
    <div>
        <!-- Logo / Marca -->
        <div class="h-16 flex items-center px-6 bg-slate-950 font-bold text-lg text-indigo-400 border-b border-slate-800">
            ADMIN PANEL
        </div>

        <!-- Opciones de Navegación del Administrador -->
         <nav class="mt-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.suscripciones.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.suscripciones.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Suscripciones
            </a>

            <a href="{{ route('admin.usuarios.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.usuarios.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Usuarios
            </a>

            <a href="{{ route('admin.pagos.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pagos.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Pagos
            </a>

            <a href="{{ route('admin.demostraciones.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.demostraciones.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Demostraciones
            </a>

            <a href="{{ route('admin.exportar.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.exportar.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Exportar
            </a>

            <a href="{{ route('admin.sesiones.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.sesiones.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Sesiones
            </a>

            <a href="{{ route('admin.auditoria.index') }}" 
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.auditoria.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Auditoría
        </a>
</nav>
         
    </div>

    <!-- Pie con versión/rol -->
    <div class="p-4 border-t border-slate-800 text-xs text-slate-500">
        Rol: Administrador
    </div>
</aside>