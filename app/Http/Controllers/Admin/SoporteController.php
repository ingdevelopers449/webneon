<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\RespuestaTicket;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoporteController extends Controller
{
    public function index(Request $request)
    {
        $filtroEstado = $request->get('estado', 'todos');

        $query = Ticket::with(['usuario', 'ultimaRespuesta.usuario'])
                       ->orderByRaw("FIELD(estado, 'abierto', 'en_proceso', 'resuelto')")
                       ->orderBy('created_at', 'desc');

        if ($filtroEstado !== 'todos') {
            $query->where('estado', $filtroEstado);
        }

        $tickets = $query->paginate(10);

        // Notificaciones internas (tabla existente)
        $notificaciones = DB::table('notificaciones_internas')
                            ->orderBy('fecha_generacion', 'desc')
                            ->paginate(15, ['*'], 'page_noti');

        $totalNoLeidas = DB::table('notificaciones_internas')->where('leida', false)->count();

        return view('admin.soporte.index', compact('tickets', 'notificaciones', 'totalNoLeidas', 'filtroEstado'));
    }

    public function crearTicket(Request $request)
    {
        $request->validate([
            'asunto'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad'   => 'required|in:baja,media,alta',
        ]);

        $ticket = Ticket::create([
            'usuario_id'  => Auth::id(),
            'asunto'      => $request->asunto,
            'descripcion' => $request->descripcion,
            'prioridad'   => $request->prioridad,
            'estado'      => 'abierto',
        ]);

        Auditoria::create([
            'usuario_id'  => Auth::id(),
            'accion'      => 'TICKET_CREADO',
            'detalle'     => "Ticket #{$ticket->id}: {$ticket->asunto}",
            'direccion_ip'=> $request->ip(),
            'resultado'   => 'exitoso',
            'fecha_hora'  => now(),
        ]);

        return back()->with('success', "Ticket #{$ticket->id} creado exitosamente.");
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:abierto,en_proceso,resuelto']);

        $ticket = Ticket::findOrFail($id);
        $estadoAnterior = $ticket->estado;
        $ticket->estado = $request->estado;
        $ticket->save();

        Auditoria::create([
            'usuario_id'  => Auth::id(),
            'accion'      => 'TICKET_ESTADO_CAMBIADO',
            'detalle'     => "Ticket #{$ticket->id} cambió de '{$estadoAnterior}' a '{$request->estado}'.",
            'direccion_ip'=> $request->ip(),
            'resultado'   => 'exitoso',
            'fecha_hora'  => now(),
        ]);

        return back()->with('success', "Estado del ticket #{$ticket->id} actualizado.");
    }

    public function responderTicket(Request $request, $id)
    {
        $request->validate(['mensaje' => 'required|string|min:5']);

        $ticket = Ticket::findOrFail($id);

        RespuestaTicket::create([
            'ticket_id'  => $ticket->id,
            'usuario_id' => Auth::id(),
            'mensaje'    => $request->mensaje,
        ]);

        // Si el admin responde y el ticket está abierto, pasarlo a "en_proceso"
        if ($ticket->estado === 'abierto') {
            $ticket->estado = 'en_proceso';
            $ticket->save();
        }

        return back()->with('success', 'Respuesta añadida al ticket.');
    }

    public function marcarLeida($id)
    {
        DB::table('notificaciones_internas')->where('id', $id)->update(['leida' => true]);
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        DB::table('notificaciones_internas')->update(['leida' => true]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
