<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exportación de Datos') }}
        </h2>
    </x-slot>

    <!-- Usando la arquitectura Dark Neon / neon-card -->
    <div class="bg-black text-[#f0f0f0] p-6 rounded-2xl shadow-[0_0_20px_rgba(0,255,255,0.15)] border border-gray-800 max-w-4xl mx-auto mt-4">
        
        <div class="mb-8 border-b border-gray-800 pb-5">
            <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#00ffff] to-[#ff00ff] tracking-widest uppercase mb-2">Generador de Reportes</h3>
            <p class="text-sm text-gray-400">Selecciona el módulo y las columnas que deseas incluir en tu reporte. Luego elige el formato de descarga.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-900/50 border-l-[3px] border-[#ff00ff] p-4 rounded-r-lg shadow-sm">
                <ul class="list-disc pl-5 text-[#ff00ff] font-medium text-sm tracking-wide">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.exportar.procesar') }}" id="exportForm">
            @csrf
            
            <!-- Módulo -->
            <div class="mb-8">
                <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-3">1. Seleccionar Módulo</label>
                <div class="relative">
                    <select name="modulo" id="moduloSelector" class="neon-input appearance-none cursor-pointer text-sm font-semibold" required>
                        <option value="" disabled selected>-- Elige una categoría --</option>
                        <option value="usuarios">Usuarios Registrados</option>
                        <option value="suscripciones">Suscripciones / Inventario</option>
                        <option value="auditoria">Registros de Auditoría</option>
                    </select>
                    <!-- Icono personalizado para el select -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#00ffff]">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Columnas (Dinámicas) -->
            <div class="mb-10" id="columnasContainer" style="display: none;">
                <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-4">2. Seleccionar Columnas</label>
                
                <!-- Opciones Usuarios -->
                <div id="cols-usuarios" class="column-group hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 bg-[#111] p-5 rounded-xl border border-gray-800 shadow-[inset_0_2px_10px_rgba(0,0,0,0.5)]">
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="id" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">ID</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="name" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Nombre</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="email" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Correo Electrónico</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="created_at" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Fecha de Registro</span></label>
                </div>

                <!-- Opciones Suscripciones -->
                <div id="cols-suscripciones" class="column-group hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 bg-[#111] p-5 rounded-xl border border-gray-800 shadow-[inset_0_2px_10px_rgba(0,0,0,0.5)]">
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="id" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">ID</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="usuario_id" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Usuario</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="fecha_inicio" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Fecha Inicio</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="fecha_vencimiento" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Vencimiento</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="tipo_suscripcion" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Tipo</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="precio" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Precio</span></label>
                </div>

                <!-- Opciones Auditoria -->
                <div id="cols-auditoria" class="column-group hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 bg-[#111] p-5 rounded-xl border border-gray-800 shadow-[inset_0_2px_10px_rgba(0,0,0,0.5)]">
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="fecha_hora" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Fecha y Hora</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="accion" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Acción</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="usuario_id" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Usuario (Autor)</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="correo_intentado" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Correo Involucrado</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="direccion_ip" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">IP</span></label>
                    <label class="inline-flex items-center cursor-pointer group"><input type="checkbox" name="columnas[]" value="resultado" class="form-checkbox text-[#00ffff] border-gray-600 bg-black focus:ring-[#00ffff] focus:ring-offset-black rounded"><span class="ml-3 text-sm text-gray-400 group-hover:text-white transition-colors">Resultado</span></label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div id="botonesContainer" style="display: none;" class="flex items-center justify-end gap-4 pt-6 border-t border-gray-800">
                <button type="submit" name="formato" value="pdf" class="btn-neon-danger shadow-sm py-3 px-6 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Descargar PDF
                </button>
                <button type="submit" name="formato" value="csv" class="btn-neon-primary shadow-sm py-3 px-6 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Descargar CSV
                </button>
            </div>
        </form>
    </div>

    <!-- Script de dinamismo -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('moduloSelector');
            const columnasContainer = document.getElementById('columnasContainer');
            const botonesContainer = document.getElementById('botonesContainer');
            const groups = document.querySelectorAll('.column-group');

            selector.addEventListener('change', function() {
                // Ocultar todos y desmarcar
                groups.forEach(g => {
                    g.classList.add('hidden');
                    const checkboxes = g.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => {
                        cb.checked = false;
                        cb.disabled = true; // Deshabilitar para que no se envíen por POST
                    });
                });

                // Mostrar el correcto
                const val = this.value;
                if (val) {
                    columnasContainer.style.display = 'block';
                    botonesContainer.style.display = 'flex';
                    const activeGroup = document.getElementById('cols-' + val);
                    activeGroup.classList.remove('hidden');
                    
                    // Marcar todos por defecto y habilitar
                    const checkboxes = activeGroup.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(cb => {
                        cb.checked = true;
                        cb.disabled = false;
                    });
                }
            });
        });
    </script>
</x-app-layout>