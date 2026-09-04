<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registro de Auditoría') }}
        </h2>
    </x-slot>

    <!-- Barra de Filtros -->
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('admin.auditoria.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Buscar (Correo o Detalle)</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Ej: admin@ejemplo.com" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tipo de Acción</label>
                <select name="accion" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
                    <option value="">Todas las acciones</option>
                    @foreach($acciones as $accion)
                        <option value="{{ $accion }}" {{ request('accion') === $accion ? 'selected' : '' }}>{{ $accion }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#00ffff] focus:ring-[#00ffff] sm:text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-neon-primary shadow-sm w-full py-2">
                    Filtrar
                </button>
                @if(request()->anyFilled(['keyword', 'accion', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('admin.auditoria.index') }}" class="btn-neon-danger shadow-sm px-3 flex items-center justify-center text-xl font-bold" title="Limpiar Filtros">
                        &times;
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Acciones -->
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.auditoria.exportar', request()->all()) }}" class="btn-neon-secondary shadow-sm px-4 py-2 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Exportar a CSV
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Fecha y Hora</th>
                        <th class="px-6 py-4">Usuario / IP</th>
                        <th class="px-6 py-4 text-center">Acción</th>
                        <th class="px-6 py-4">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($registros as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-mono">
                                <div class="text-gray-900 font-bold">{{ $log->fecha_hora->format('d M Y') }}</div>
                                <div class="text-gray-500">{{ $log->fecha_hora->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->usuario)
                                    <p class="font-bold text-gray-900">{{ $log->usuario->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->usuario->email }}</p>
                                @elseif($log->correo_intentado)
                                    <p class="font-bold text-red-600">No Auth</p>
                                    <p class="text-xs text-gray-500">{{ $log->correo_intentado }}</p>
                                @else
                                    <p class="text-gray-500 text-xs italic">Sistema</p>
                                @endif
                                <p class="text-[10px] text-gray-400 font-mono mt-1" title="Dirección IP">{{ $log->direccion_ip }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    {{ $log->accion }}
                                </span>
                                @if($log->resultado === 'fallido')
                                    <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                        FALLIDO
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-md">
                                {{ $log->detalle ?? 'Sin detalle.' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No se encontraron registros de auditoría que coincidan con los filtros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($registros->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $registros->links() }}
            </div>
        @endif
    </div>
</x-app-layout>