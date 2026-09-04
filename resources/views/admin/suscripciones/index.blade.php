<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suscripciones de Usuarios') }}
        </h2>
    </x-slot>

    <!-- Alertas de Vencimiento (HU-026) -->
    @if(count($alertasVencimiento) > 0)
        <div class="mb-6 space-y-2">
            @foreach($alertasVencimiento as $alerta)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-yellow-700 text-sm font-medium">{{ $alerta }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Mensajes de Éxito o Error -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
            <p class="text-green-700 text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
            <p class="text-red-700 text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tabla de Suscripciones (HU-006) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Estado Cuenta</th>
                        <th class="px-6 py-4 text-center">F. Inicio</th>
                        <th class="px-6 py-4 text-center">F. Vencimiento</th>
                        <th class="px-6 py-4 text-center">Días Restantes</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $usuario->name }}</p>
                                <p class="text-xs text-gray-500">{{ $usuario->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <!-- HU-007: Integración de Estados -->
                                @if($usuario->estado_cuenta === 'activo')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @elseif($usuario->estado_cuenta === 'bloqueado')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Bloqueado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($usuario->estado_cuenta) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($usuario->subscripcionActiva)
                                    <div>{{ $usuario->subscripcionActiva->fecha_inicio->format('d M Y') }}</div>
                                    @if($usuario->tipo_periodo_actual === 'demo')
                                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-300">
                                            DEMO MANUAL
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-300">
                                            SUSCRIPCIÓN
                                        </span>
                                    @endif
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($usuario->subscripcionActiva)
                                    <span class="{{ $usuario->subscripcionActiva->dias_restantes_calculados < 0 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                        {{ $usuario->subscripcionActiva->fecha_vencimiento->format('d M Y') }}
                                    </span>
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($usuario->subscripcionActiva)
                                    @if($usuario->subscripcionActiva->dias_restantes_calculados > 0)
                                        <span class="font-bold text-blue-600 text-base">{{ $usuario->subscripcionActiva->dias_restantes_calculados }}</span> 
                                        <span class="text-xs text-gray-500">días</span>
                                    @else
                                        <span class="font-bold text-red-600 text-sm">Vencido</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 font-medium">Sin Subs.</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="abrirModal({{ $usuario->id }}, '{{ addslashes($usuario->name) }}')" class="btn-neon-primary shadow-sm">
                                    Gestionar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No hay usuarios registrados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($usuarios->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Gestión Manual -->
    <div id="modal-gestion" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-xl w-full max-w-md relative">
            <button onclick="cerrarModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-lg font-bold text-gray-900 mb-1">Gestionar Días de Suscripción</h3>
            <p class="text-sm text-gray-500 mb-6">Usuario: <span id="modal-user-name" class="font-semibold text-indigo-600"></span></p>

            <form id="form-gestion" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad de Días (Num. Positivo)</label>
                    <input type="number" name="dias" min="1" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comprobante de Pago (Opcional)</label>
                    <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-[#00ffff] file:text-black hover:file:bg-[#00cccc] cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">Solo JPG, PNG o PDF. Max 2MB.</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" name="accion" value="restar" class="btn-neon-secondary w-full shadow-sm">
                        - Restar
                    </button>
                    <button type="submit" name="accion" value="sumar" class="btn-neon-primary w-full shadow-sm">
                        + Sumar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Vanilla JS para el modal -->
    <script>
        function abrirModal(idUsuario, nombreUsuario) {
            document.getElementById('modal-user-name').innerText = nombreUsuario;
            document.getElementById('form-gestion').action = '/admin/suscripciones/' + idUsuario + '/dias';
            document.getElementById('modal-gestion').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-gestion').classList.add('hidden');
        }
    </script>
</x-app-layout>