<aside class="w-64 bg-black min-h-screen flex flex-col justify-between py-6 shadow-[5px_0_15px_rgba(0,0,0,0.5)] z-20">
    
    <div class="w-full flex flex-col">
        <!-- Logo Circular -->
        <div class="flex justify-center mb-8">
            <div class="w-28 h-28 rounded-full bg-[#111] flex items-center justify-center overflow-hidden border-[2px] border-gray-800 shadow-[0_0_15px_rgba(255,255,255,0.1)] p-2">
                <img src="{{ asset('img/neonlogo.png') }}" alt="Neon Streaming" class="w-full h-full object-contain">
            </div>
        </div>
        
        <!-- Enlaces de Navegación -->
        <nav class="flex flex-col gap-2 px-6">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Inicio
            </a>
            
            <a href="{{ route('admin.suscripciones.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.suscripciones.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Suscripciones
            </a>
            
            <a href="{{ route('admin.usuarios.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.usuarios.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Usuarios
            </a>
            
            <a href="{{ route('admin.pagos.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.pagos.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Pagos
            </a>
            
            <a href="{{ route('admin.demostraciones.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.demostraciones.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Demostraciones
            </a>
            
            <a href="{{ route('admin.sesiones.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.sesiones.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Sesiones
            </a>
            
            <a href="{{ route('admin.auditoria.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.auditoria.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Auditoría
            </a>

            <a href="{{ route('admin.exportar.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.exportar.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Exportar Datos
            </a>

            <a href="{{ route('admin.soporte.index') }}" 
               class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.soporte.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent' }}">
                Soporte
            </a>
            
            <!-- Enlaces Adicionales -->
            <div class="mt-4 pt-4 border-t border-gray-800 flex flex-col gap-2">
                <a href="{{ route('admin.configuracion.index') }}" 
                   class="px-4 py-2.5 rounded-lg transition-all duration-300 font-bold uppercase tracking-wider text-[11px] {{ request()->routeIs('admin.configuracion.*') ? 'bg-gray-900 text-[#00ffff] border-l-[3px] border-[#00ffff] shadow-[inset_2px_0_10px_rgba(0,255,255,0.1)]' : 'text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent hover:border-[#ff00ff]' }}">
                    Configuración
                </a>
            </div>
        </nav>
    </div>

    <!-- Sección Inferior (Contacto y Redes Sociales) -->
    <div class="mt-6 w-full flex flex-col items-center">
        <p class="text-gray-500 text-[9px] uppercase font-bold tracking-widest mb-1">Escríbenos Ahora</p>
        <p class="text-white font-extrabold text-lg mb-4 tracking-wide">+573245772345</p>
        
        <div class="flex gap-5">
            <a href="#" class="text-gray-400 hover:text-[#00ffff] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#00ffff] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-[#ff00ff] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
        </div>
    </div>
</aside>