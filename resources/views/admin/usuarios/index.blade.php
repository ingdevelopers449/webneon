<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            {{ __('Gestión de Usuarios (Clientes)') }}
        </h2>
    </x-slot>

    <!-- Toolbar de Acciones Principales -->
    <div class="mb-6 flex justify-end">
        <button onclick="abrirModalCrear()" class="btn-neon-primary shadow-sm">
            + Nuevo Usuario
        </button>
    </div>

    <!-- Validaciones / Mensajes -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
            <ul class="text-sm text-red-700 list-disc list-inside font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
            <p class="text-green-700 text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tabla de Usuarios (HU-007) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Correo Electrónico</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-center">Fecha Registro</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $usuario->name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Cliente
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $usuario->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($usuario->estado_cuenta === 'activo')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Activo</span>
                                @elseif($usuario->estado_cuenta === 'bloqueado')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Bloqueado</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($usuario->estado_cuenta) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ $usuario->fecha_registro ? $usuario->fecha_registro->format('d M Y') : '---' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="abrirModalEditar({{ $usuario->id }}, '{{ addslashes($usuario->name) }}', '{{ addslashes($usuario->email) }}')" class="text-indigo-600 hover:text-indigo-900 transition-colors p-1" title="Editar Información">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button onclick="abrirModalEstado({{ $usuario->id }}, '{{ addslashes($usuario->name) }}')" class="text-orange-600 hover:text-orange-900 transition-colors p-1" title="Cambiar Estado">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay usuarios registrados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($usuarios->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>

    <!-- Modal: Crear Usuario -->
    <div id="modal-crear" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-xl w-full max-w-md relative">
            <button onclick="cerrarModal('modal-crear')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Registrar Nuevo Usuario</h3>
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono (Opcional)</label>
                    <!-- Nota el name="telefono". Así es como el controlador reconocerá el dato -->
                    <input type="text" name="telefono" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" minlength="8" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <button type="submit" class="btn-neon-primary w-full shadow-sm">
                    Crear Usuario y Generar Demo
                </button>
            </form>
        </div>
    </div>

    <!-- Modal: Editar Usuario -->
    <div id="modal-editar" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-xl w-full max-w-md relative">
            <button onclick="cerrarModal('modal-editar')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Editar Usuario</h3>
            <form id="form-editar" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <input type="text" id="edit-name" name="name" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" id="edit-email" name="email" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <button type="submit" class="btn-neon-primary w-full shadow-sm">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal: Cambiar Estado (Diseño Neon) -->
    <div id="modal-estado" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-[#111] border border-gray-800 p-6 rounded-xl shadow-[0_0_25px_rgba(0,255,255,0.15)] w-full max-w-md relative">
            <button onclick="cerrarModal('modal-estado')" class="absolute top-4 right-4 text-gray-500 hover:text-[#ff00ff] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-lg font-bold text-[#00ffff] mb-1 uppercase tracking-wider">Cambiar Estado</h3>
            <p class="text-sm text-gray-400 mb-6">Usuario: <span id="estado-user-name" class="font-bold text-white tracking-wide"></span></p>
            
            <form id="form-estado" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 gap-4">
                    <!-- Botón Activar (Cyan Neon) -->
                    <button type="submit" name="estado" value="activo" class="btn-neon-primary w-full shadow-sm">
                        Activar Cuenta
                    </button>
                    
                    <!-- Botón Pausar (Gris con hover Cyan) -->
                    <button type="submit" name="estado" value="inactivo" class="btn-neon-secondary w-full shadow-sm">
                        Pausar / Inactivar
                    </button>
                    
                    <!-- Botón Bloquear (Magenta Neon) -->
                    <button type="submit" name="estado" value="bloqueado" class="btn-neon-danger w-full shadow-sm">
                        Bloquear Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts Modales -->
    <script>
        function abrirModalCrear() {
            document.getElementById('modal-crear').classList.remove('hidden');
        }

        function abrirModalEditar(id, nombre, email) {
            document.getElementById('edit-name').value = nombre;
            document.getElementById('edit-email').value = email;
            document.getElementById('form-editar').action = '/admin/usuarios/' + id;
            document.getElementById('modal-editar').classList.remove('hidden');
        }

        function abrirModalEstado(id, nombre) {
            document.getElementById('estado-user-name').innerText = nombre;
            document.getElementById('form-estado').action = '/admin/usuarios/' + id + '/estado';
            document.getElementById('modal-estado').classList.remove('hidden');
        }

        function cerrarModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-app-layout>