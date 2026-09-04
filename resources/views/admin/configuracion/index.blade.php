<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del Sistema') }}
        </h2>
    </x-slot>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-900/50 border-l-[3px] border-green-500 p-4 rounded shadow-sm">
                <p class="text-green-400 text-sm font-bold tracking-wide">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-red-900/50 border-l-[3px] border-[#ff00ff] p-4 rounded shadow-sm">
                <ul class="list-disc pl-5 text-[#ff00ff] font-medium text-sm tracking-wide">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar de Pestañas (Neon) -->
        <div class="w-full md:w-64 flex flex-col gap-2 bg-black p-4 rounded-2xl shadow-[0_0_15px_rgba(0,255,255,0.1)] border border-gray-800">
            <h3 class="text-xs font-black text-transparent bg-clip-text bg-gradient-to-r from-[#00ffff] to-[#ff00ff] tracking-widest uppercase mb-4 px-2">Panel de Control</h3>
            
            <button onclick="mostrarTab('general')" id="btn-tab-general" class="tab-btn active px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-[#00ffff] bg-gray-900 border-l-[3px] border-[#00ffff] transition-all duration-300">
                General
            </button>
            <button onclick="mostrarTab('suscripciones')" id="btn-tab-suscripciones" class="tab-btn px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent transition-all duration-300">
                Planes y Suscrip.
            </button>
            <button onclick="mostrarTab('seguridad')" id="btn-tab-seguridad" class="tab-btn px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent transition-all duration-300">
                Roles y Seguridad
            </button>
        </div>

        <!-- Contenedor de Pestañas -->
        <div class="flex-1 bg-black rounded-2xl shadow-[0_0_15px_rgba(0,0,0,0.8)] border border-gray-800 p-8 min-h-[500px]">
            
            <!-- TAB: GENERAL -->
            <div id="tab-general" class="tab-content block">
                <h3 class="text-2xl font-black text-white tracking-widest uppercase mb-6 border-b border-gray-800 pb-3">Ajustes Generales</h3>
                
                <form action="{{ route('admin.configuracion.general') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">Nombre de la Plataforma</label>
                            <input type="text" name="app_name" value="{{ $configuraciones['app_name']->valor ?? 'Neon Streaming' }}" class="neon-input">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">URL del Logotipo</label>
                            <input type="text" name="logo_url" value="{{ $configuraciones['logo_url']->valor ?? '' }}" class="neon-input" placeholder="/img/logo.png">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">Notificaciones de Bienvenida (Email)</label>
                            <select name="email_bienvenida" class="neon-input">
                                <option value="true" {{ ($configuraciones['email_bienvenida']->valor ?? 'true') === 'true' ? 'selected' : '' }}>Habilitado</option>
                                <option value="false" {{ ($configuraciones['email_bienvenida']->valor ?? 'true') === 'false' ? 'selected' : '' }}>Deshabilitado</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-[#ff00ff] uppercase tracking-widest mb-2">Color Primario (Hex)</label>
                            <input type="color" name="color_primario" value="{{ $configuraciones['color_primario']->valor ?? '#00ffff' }}" class="w-full h-12 bg-[#222] border border-[#444] rounded-md cursor-pointer">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-neon-primary px-8 py-3">Guardar Ajustes Generales</button>
                    </div>
                </form>
            </div>

            <!-- TAB: SUSCRIPCIONES -->
            <div id="tab-suscripciones" class="tab-content hidden">
                <div class="flex justify-between items-center border-b border-gray-800 pb-3 mb-6">
                    <h3 class="text-2xl font-black text-white tracking-widest uppercase">Planes de Suscripción</h3>
                </div>

                <!-- Lista de Planes Actuales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
                    @forelse($planes as $plan)
                        <div class="bg-[#111] border border-gray-800 rounded-xl p-5 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-[#00ffff] opacity-10 rounded-bl-full group-hover:opacity-20 transition-opacity"></div>
                            <h4 class="text-[#00ffff] font-bold text-lg mb-1">{{ $plan->nombre }}</h4>
                            <p class="text-2xl font-black text-white mb-2">${{ number_format($plan->precio, 2) }} <span class="text-sm font-normal text-gray-500">/ {{ $plan->duracion_meses }} Mes(es)</span></p>
                            <p class="text-xs text-gray-400 mb-4">{{ $plan->descripcion ?? 'Sin descripción' }}</p>
                            <span class="inline-block px-2 py-1 bg-{{ $plan->activo ? 'green' : 'red' }}-900 text-{{ $plan->activo ? 'green' : 'red' }}-400 text-[10px] font-bold rounded uppercase tracking-wider">
                                {{ $plan->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    @empty
                        <div class="col-span-full p-4 border border-dashed border-gray-700 text-center rounded-xl text-gray-500">
                            No hay planes de suscripción configurados.
                        </div>
                    @endforelse
                </div>

                <h4 class="text-sm font-bold text-[#ff00ff] uppercase tracking-widest mb-4">Añadir Nuevo Plan</h4>
                <form action="{{ route('admin.configuracion.plan') }}" method="POST" class="bg-[#111] p-5 rounded-xl border border-gray-800">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nombre</label>
                            <input type="text" name="nombre" required class="neon-input py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" required class="neon-input py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Duración (Meses)</label>
                            <input type="number" name="duracion_meses" value="1" min="1" required class="neon-input py-2">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Descripción</label>
                        <input type="text" name="descripcion" class="neon-input py-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-neon-primary">+ Crear Plan</button>
                    </div>
                </form>
            </div>

            <!-- TAB: SEGURIDAD -->
            <div id="tab-seguridad" class="tab-content hidden">
                <h3 class="text-2xl font-black text-white tracking-widest uppercase mb-6 border-b border-gray-800 pb-3">Gestor de Permisos</h3>
                
                <p class="text-sm text-gray-400 mb-6">Asigna los permisos a cada rol del sistema. Los usuarios heredarán estas capacidades dependiendo de su rol asignado.</p>

                <div class="flex flex-col gap-6">
                    @foreach($roles as $rol)
                        <div class="bg-[#111] border border-gray-800 rounded-xl p-5">
                            <h4 class="text-[#00ffff] font-bold text-lg uppercase tracking-wider mb-4 border-b border-gray-800 pb-2">Rol: {{ $rol->nombre_rol }}</h4>
                            
                            <form action="{{ route('admin.configuracion.permisos', $rol->id_rol) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    @foreach($permisos as $permiso)
                                        <label class="inline-flex items-center cursor-pointer group">
                                            <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}" 
                                                {{ $rol->permisos->contains('id', $permiso->id) ? 'checked' : '' }}
                                                class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded">
                                            <span class="ml-2 text-xs text-gray-400 group-hover:text-white uppercase tracking-wider">{{ $permiso->nombre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="btn-neon-secondary text-[10px] py-1.5">Guardar Permisos</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Script de Tabs -->
    <script>
        function mostrarTab(tabId) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Mostrar el solicitado
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            // Reiniciar botones
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = 'tab-btn px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent transition-all duration-300';
            });
            // Activar boton actual
            document.getElementById('btn-tab-' + tabId).className = 'tab-btn active px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-[#00ffff] bg-gray-900 border-l-[3px] border-[#00ffff] transition-all duration-300';
        }
    </script>
</x-app-layout>
