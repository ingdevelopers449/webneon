<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comprobantes de Pago') }}
        </h2>
    </x-slot>

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

    <!-- Tabla de Comprobantes (HU-009) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Suscripción Asoc.</th>
                        <th class="px-6 py-4 text-center">Fecha de Carga</th>
                        <th class="px-6 py-4">Cargado Por</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($comprobantes as $comprobante)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $comprobante->suscripcion->usuario->name ?? 'Usuario Eliminado' }}</p>
                                <p class="text-xs text-gray-500">{{ $comprobante->suscripcion->usuario->email ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Vence: {{ $comprobante->suscripcion ? \Carbon\Carbon::parse($comprobante->suscripcion->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ $comprobante->fecha_carga->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $comprobante->administrador->name ?? 'Admin Desconocido' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ Storage::url($comprobante->ruta_archivo) }}" target="_blank" class="btn-neon-primary shadow-sm" title="Ver Comprobante">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.pagos.download', $comprobante->id) }}" class="btn-neon-secondary shadow-sm" title="Descargar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay comprobantes de pago registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($comprobantes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $comprobantes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>