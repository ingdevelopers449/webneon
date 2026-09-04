<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Soporte del Sistema') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 bg-green-900/50 border-l-[3px] border-green-500 p-4 rounded shadow-sm">
            <p class="text-green-400 text-sm font-bold tracking-wide">{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar de Pestañas -->
        <div class="w-full md:w-56 flex flex-col gap-2 bg-black p-4 rounded-2xl shadow-[0_0_15px_rgba(0,255,255,0.1)] border border-gray-800 h-fit">
            <h3 class="text-xs font-black text-transparent bg-clip-text bg-gradient-to-r from-[#00ffff] to-[#ff00ff] tracking-widest uppercase mb-4 px-2">Soporte</h3>
            
            <button onclick="mostrarTab('tickets')" id="btn-tab-tickets" class="tab-btn active-tab px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-[#00ffff] bg-gray-900 border-l-[3px] border-[#00ffff] transition-all duration-300">
                🎫 Tickets
            </button>
            <button onclick="mostrarTab('notificaciones')" id="btn-tab-notificaciones" class="tab-btn px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent transition-all duration-300">
                🔔 Notificaciones
                @if($totalNoLeidas > 0)
                    <span class="ml-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-black bg-[#ff00ff] text-white rounded-full">{{ $totalNoLeidas }}</span>
                @endif
            </button>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-1 min-h-[500px]">

            <!-- TAB: TICKETS -->
            <div id="tab-tickets" class="tab-content block space-y-5">

                <!-- Header con botón crear -->
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-white uppercase tracking-widest">Tickets de Soporte</h3>
                    <button onclick="document.getElementById('modal-crear').classList.remove('hidden')" class="btn-neon-primary">
                        + Nuevo Ticket
                    </button>
                </div>

                <!-- Filtros de estado -->
                <div class="flex gap-2 flex-wrap">
                    @foreach(['todos' => 'Todos', 'abierto' => 'Abiertos', 'en_proceso' => 'En Proceso', 'resuelto' => 'Resueltos'] as $val => $label)
                        <a href="{{ route('admin.soporte.index', ['estado' => $val]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border transition-all duration-200 
                           {{ $filtroEstado === $val ? 'bg-[#00ffff] text-black border-[#00ffff]' : 'bg-black text-gray-400 border-gray-700 hover:border-[#00ffff] hover:text-[#00ffff]' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Tabla de tickets -->
                <div class="bg-black rounded-xl border border-gray-800 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-400">
                            <thead class="bg-[#111] text-xs uppercase font-bold text-gray-500 border-b border-gray-800">
                                <tr>
                                    <th class="px-5 py-4">#</th>
                                    <th class="px-5 py-4">Asunto</th>
                                    <th class="px-5 py-4">Creado Por</th>
                                    <th class="px-5 py-4 text-center">Prioridad</th>
                                    <th class="px-5 py-4 text-center">Estado</th>
                                    <th class="px-5 py-4 text-center">Fecha</th>
                                    <th class="px-5 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-[#111] transition-colors">
                                        <td class="px-5 py-4 font-mono text-gray-600">#{{ $ticket->id }}</td>
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-white">{{ $ticket->asunto }}</p>
                                            <p class="text-xs text-gray-600 mt-0.5 truncate max-w-[220px]">{{ $ticket->descripcion }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <p class="text-white font-medium text-xs">{{ $ticket->usuario?->name ?? 'N/A' }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @php
                                                $colorPrio = match($ticket->prioridad) {
                                                    'alta'  => 'text-[#ff00ff] border-[#ff00ff]',
                                                    'media' => 'text-yellow-400 border-yellow-400',
                                                    default => 'text-gray-400 border-gray-600',
                                                };
                                            @endphp
                                            <span class="inline-block px-2 py-0.5 rounded border text-[10px] font-black uppercase {{ $colorPrio }}">
                                                {{ ucfirst($ticket->prioridad) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @php
                                                $colorEstado = match($ticket->estado) {
                                                    'abierto'    => 'bg-green-900 text-green-400',
                                                    'en_proceso' => 'bg-yellow-900 text-yellow-400',
                                                    'resuelto'   => 'bg-gray-800 text-gray-400',
                                                };
                                            @endphp
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $colorEstado }}">
                                                {{ str_replace('_', ' ', ucfirst($ticket->estado)) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-center text-xs text-gray-600">
                                            {{ $ticket->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <button onclick="abrirTicket({{ $ticket->id }}, '{{ addslashes($ticket->asunto) }}', '{{ addslashes($ticket->descripcion) }}', '{{ $ticket->estado }}')" class="btn-neon-secondary text-[10px] py-1.5">
                                                Ver / Responder
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-10 text-center text-gray-600">No hay tickets de soporte registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($tickets->hasPages())
                        <div class="px-5 py-4 border-t border-gray-800">{{ $tickets->links() }}</div>
                    @endif
                </div>
            </div>

            <!-- TAB: NOTIFICACIONES -->
            <div id="tab-notificaciones" class="tab-content hidden space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-white uppercase tracking-widest">Centro de Notificaciones</h3>
                    <form method="POST" action="{{ route('admin.soporte.notificaciones.leer-todas') }}">
                        @csrf
                        <button type="submit" class="btn-neon-secondary text-[10px] py-1.5">✓ Marcar todas como leídas</button>
                    </form>
                </div>

                <div class="bg-black rounded-xl border border-gray-800 overflow-hidden divide-y divide-gray-800">
                    @forelse($notificaciones as $noti)
                        <div class="flex items-start justify-between px-5 py-4 {{ $noti->leida ? 'opacity-50' : '' }} hover:bg-[#111] transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    @if(!$noti->leida)
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#00ffff] shadow-[0_0_8px_rgba(0,255,255,0.8)]"></div>
                                    @else
                                        <div class="w-2.5 h-2.5 rounded-full bg-gray-700"></div>
                                    @endif
                                </div>
                                <div>
                                    @php
                                        $iconoTipo = match($noti->tipo) {
                                            'cuenta_por_vencer'        => '⚠️',
                                            'cliente_por_vencer'       => '👤',
                                            'suscripcion_por_vencer'   => '📅',
                                            default                    => '🔔',
                                        };
                                    @endphp
                                    <p class="text-sm {{ $noti->leida ? 'text-gray-500' : 'text-white font-semibold' }}">
                                        {{ $iconoTipo }} {{ $noti->mensaje }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ \Carbon\Carbon::parse($noti->fecha_generacion)->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if(!$noti->leida)
                                <form method="POST" action="{{ route('admin.soporte.notificaciones.leida', $noti->id) }}">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-gray-600 hover:text-[#00ffff] font-bold uppercase ml-4 mt-1 whitespace-nowrap transition-colors">Marcar leída</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-gray-600">No hay notificaciones internas registradas.</div>
                    @endforelse
                </div>
                @if($notificaciones->hasPages())
                    <div class="py-3">{{ $notificaciones->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL: Crear Ticket -->
    <div id="modal-crear" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-[#111] border border-gray-700 p-6 rounded-2xl shadow-2xl w-full max-w-lg relative">
            <button onclick="document.getElementById('modal-crear').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="text-lg font-black text-white uppercase tracking-widest mb-5">Nuevo Ticket</h3>
            <form method="POST" action="{{ route('admin.soporte.tickets.crear') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">Asunto</label>
                    <input type="text" name="asunto" required class="neon-input py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">Descripción</label>
                    <textarea name="descripcion" rows="4" required class="neon-input py-2 resize-none"></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-[#00ffff] uppercase tracking-widest mb-2">Prioridad</label>
                    <select name="prioridad" class="neon-input py-2">
                        <option value="baja">Baja</option>
                        <option value="media" selected>Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-crear').classList.add('hidden')" class="btn-neon-secondary">Cancelar</button>
                    <button type="submit" class="btn-neon-primary">Crear Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Ver/Responder Ticket -->
    <div id="modal-ticket" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-[#111] border border-gray-700 p-6 rounded-2xl shadow-2xl w-full max-w-xl relative max-h-[90vh] flex flex-col">
            <button onclick="document.getElementById('modal-ticket').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="mb-4 border-b border-gray-800 pb-4">
                <h3 id="modal-ticket-asunto" class="text-base font-black text-white uppercase tracking-wider"></h3>
                <p id="modal-ticket-desc" class="text-sm text-gray-400 mt-1"></p>
            </div>

            <!-- Cambiar Estado -->
            <form id="form-estado" method="POST" action="" class="mb-4 flex items-center gap-3">
                @csrf
                @method('PUT')
                <label class="text-xs font-bold text-[#ff00ff] uppercase tracking-widest whitespace-nowrap">Cambiar Estado:</label>
                <select name="estado" id="modal-ticket-estado" class="neon-input py-1.5 text-sm flex-1">
                    <option value="abierto">Abierto</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="resuelto">Resuelto</option>
                </select>
                <button type="submit" class="btn-neon-danger text-[10px] py-1.5 whitespace-nowrap">Guardar</button>
            </form>

            <!-- Responder -->
            <form id="form-respuesta" method="POST" action="" class="flex flex-col gap-3">
                @csrf
                <label class="text-xs font-bold text-[#00ffff] uppercase tracking-widest">Añadir Respuesta</label>
                <textarea name="mensaje" rows="3" required class="neon-input py-2 resize-none text-sm" placeholder="Escribe tu respuesta..."></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="btn-neon-primary">Enviar Respuesta</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function mostrarTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = 'tab-btn px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white hover:bg-gray-900 border-l-[3px] border-transparent transition-all duration-300';
            });
            const activeBtn = document.getElementById('btn-tab-' + tabId);
            activeBtn.className = 'tab-btn active-tab px-4 py-3 rounded-lg text-left text-sm font-bold uppercase tracking-wider text-[#00ffff] bg-gray-900 border-l-[3px] border-[#00ffff] transition-all duration-300';
            if (tabId === 'notificaciones') {
                // Preservar badge
                const badge = document.querySelector('#btn-tab-notificaciones .bg-\\[\\#ff00ff\\]');
                if (badge) activeBtn.appendChild(badge);
            }
        }

        function abrirTicket(id, asunto, desc, estado) {
            document.getElementById('modal-ticket-asunto').innerText = '#' + id + ' — ' + asunto;
            document.getElementById('modal-ticket-desc').innerText = desc;
            document.getElementById('modal-ticket-estado').value = estado;
            document.getElementById('form-estado').action = '/admin/soporte/tickets/' + id + '/estado';
            document.getElementById('form-respuesta').action = '/admin/soporte/tickets/' + id + '/responder';
            document.getElementById('modal-ticket').classList.remove('hidden');
        }
    </script>
</x-app-layout>
