<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Excepciones Manuales de Demostración') }}
        </h2>
    </x-slot>

    <!-- Toolbar de Acciones Principales -->
    <div class="mb-6 flex justify-end">
        <button onclick="abrirModalOtorgar()" class="btn-neon-primary shadow-sm">
            + Otorgar Demostración Manual
        </button>
    </div>

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
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
            <ul class="list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabla de Excepciones (HU-003) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4 text-center">Días Asignados</th>
                        <th class="px-6 py-4">Justificación</th>
                        <th class="px-6 py-4 text-center">Fecha de Asignación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($excepciones as $excepcion)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $excepcion->usuario->name ?? 'Usuario Eliminado' }}</p>
                                <p class="text-xs text-gray-500">{{ $excepcion->usuario->email ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-blue-600 text-base">+{{ $excepcion->dias_asignados }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700">{{ $excepcion->justificacion }}</p>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ $excepcion->fecha_asignacion->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No hay excepciones manuales de demostración registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($excepciones->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $excepciones->links() }}
            </div>
        @endif
    </div>

    <!-- Modal para Otorgar Demo Manual -->
    <div id="modal-otorgar" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-xl w-full max-w-md relative">
            <button onclick="cerrarModalOtorgar()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-lg font-bold text-gray-900 mb-1">Otorgar Demostración Manual</h3>
            <p class="text-sm text-gray-500 mb-6">Asigna días gratuitos a un cliente.</p>

            <form method="POST" action="{{ route('admin.demostraciones.otorgar') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Cliente</label>
                    <select name="usuario_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
                        <option value="">-- Elige un usuario --</option>
                        @foreach($usuarios as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad de Días (Num. Positivo)</label>
                    <input type="number" name="dias_asignados" min="1" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Justificación / Motivo</label>
                    <textarea name="justificacion" rows="3" required placeholder="Ej: Soporte técnico, promoción especial..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm"></textarea>
                </div>

                <button type="submit" class="btn-neon-primary w-full shadow-sm">
                    Guardar Excepción
                </button>
            </form>
        </div>
    </div>

    <!-- Script Vanilla JS para el modal -->
    <script>
        function abrirModalOtorgar() {
            document.getElementById('modal-otorgar').classList.remove('hidden');
        }

        function cerrarModalOtorgar() {
            document.getElementById('modal-otorgar').classList.add('hidden');
        }
    </script>
</x-app-layout>