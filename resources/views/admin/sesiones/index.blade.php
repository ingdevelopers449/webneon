<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Sesiones Activas') }}
        </h2>
    </x-slot>

    <!-- Toolbar -->
    <div class="mb-6 flex justify-end">
        <form method="POST" action="{{ route('admin.sesiones.destroy-all') }}" onsubmit="return confirm('¿Estás seguro de cerrar TODAS tus otras sesiones activas?');">
            @csrf
            <button type="submit" class="btn-neon-danger shadow-sm px-4 py-2 text-sm">
                Cerrar Todas las Otras Sesiones
            </button>
        </form>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
            <p class="text-green-700 text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Dispositivo</th>
                        <th class="px-6 py-4 text-center">Dirección IP</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-center">Última Actividad</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sesiones as $sesion)
                        @php 
                            $esActual = ($sesion->dispositivo === request()->userAgent() && $sesion->direccion_ip === request()->ip() && $sesion->activa);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ $esActual ? 'bg-blue-50/50' : '' }}">
                            <td class="px-6 py-4 max-w-xs truncate" title="{{ $sesion->dispositivo }}">
                                <p class="font-semibold text-gray-900">
                                    {{ \Illuminate\Support\Str::limit($sesion->dispositivo, 40) ?? 'Desconocido' }}
                                </p>
                                @if($esActual)
                                    <span class="text-xs text-blue-600 font-bold">Este dispositivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-xs">
                                {{ $sesion->direccion_ip ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sesion->activa)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-300">
                                        ACTIVA
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                        CERRADA
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ $sesion->ultima_actividad ? $sesion->ultima_actividad->diffForHumans() : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sesion->activa && !$esActual)
                                    <form method="POST" action="{{ route('admin.sesiones.destroy', $sesion->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition font-bold text-xs" title="Cerrar sesión">
                                            Cerrar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay registro de sesiones.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sesiones->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sesiones->links() }}
            </div>
        @endif
    </div>
</x-app-layout>