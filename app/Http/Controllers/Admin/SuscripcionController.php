<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Subscripcion;
use App\Models\Auditoria;
use App\Models\Comprobantes;
use App\Models\PlanSuscripcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SuscripcionController extends Controller
{
    public function index()
    {
        // HU-006: Mostrar un listado de todos los usuarios registrados en el sistema.
        // Excluimos a los administradores (id_rol = 1) y paginamos.
        $usuarios = User::with(['subscripcionActiva.ultimoComprobante', 'subscripcionActiva.plan'])
                        ->where('id_rol', '!=', 1)
                        ->paginate(10);

        $alertasVencimiento = [];

        foreach ($usuarios as $usuario) {
            if ($usuario->subscripcionActiva) {
                // Calculamos los días exactos de diferencia respecto a hoy
                $diasRestantes = now()->diffInDays($usuario->subscripcionActiva->fecha_vencimiento, false);
                $usuario->subscripcionActiva->dias_restantes_calculados = (int) $diasRestantes;
                
                // HU-026: Notificaciones de vencimiento (3 días o menos)
                if ($diasRestantes <= 3 && $diasRestantes >= 0) {
                    $alertasVencimiento[] = "Suscripción próxima a vencer: {$usuario->name} (en {$diasRestantes} días).";
                } elseif ($diasRestantes < 0) {
                    $alertasVencimiento[] = "Suscripción expirada: {$usuario->name}.";
                }
            }
        }

        $planes = PlanSuscripcion::where('activo', true)->get();

        return view('admin.suscripciones.index', compact('usuarios', 'alertasVencimiento', 'planes'));
    }

    public function gestionarDias(Request $request, $id)
    {
        $tieneComprobante = false;
        $usuarioTemp = User::find($id);
        if ($usuarioTemp && $usuarioTemp->subscripcionActiva && $usuarioTemp->subscripcionActiva->ultimoComprobante) {
            $tieneComprobante = true;
        }

        $request->validate([
            'dias'        => 'required|numeric|min:1',
            'accion'      => 'required|in:sumar,restar',
            'comprobante' => ($tieneComprobante ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'plan_id'     => 'nullable|exists:planes_suscripcion,id',
        ]);

        $dias = (int) $request->dias;
        $usuario = User::findOrFail($id);
        $suscripcion = $usuario->subscripcionActiva;

        // Si no tiene suscripción activa, creamos una nueva
        if (!$suscripcion) {
            $suscripcion = new Subscripcion();
            $suscripcion->usuario_id = $usuario->id;
            $suscripcion->fecha_inicio = now();
            $suscripcion->fecha_vencimiento = now();
        }

        // Si se seleccionó un Plan, guardarlo con su precio histórico
        if ($request->plan_id) {
            $plan = PlanSuscripcion::find($request->plan_id);
            $suscripcion->plan_id = $plan->id;
            $suscripcion->precio = $plan->precio;
            $suscripcion->tipo_suscripcion = $plan->nombre;
        }

        $fechaActual = Carbon::parse($suscripcion->fecha_vencimiento);
        
        if ($request->accion === 'sumar') {
            $suscripcion->fecha_vencimiento = $fechaActual->addDays($dias);
            $mensajeLog = "Se agregaron {$dias} días a la suscripción del usuario {$usuario->email}.";
        } else {
            $suscripcion->fecha_vencimiento = $fechaActual->subDays($dias);
            $mensajeLog = "Se restaron {$dias} días a la suscripción del usuario {$usuario->email}.";
        }

        $suscripcion->save();

        // HU-009: Guardar Comprobante si fue subido
        if ($request->hasFile('comprobante')) {
            $archivo = $request->file('comprobante');
            $nombreArchivo = $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->store('comprobantes', 'public');

            Comprobantes::create([
                'suscripcion_id' => $suscripcion->id,
                'nombre_archivo' => $nombreArchivo,
                'ruta_archivo' => $rutaArchivo,
                'fecha_carga' => now(),
                'administrador_id' => Auth::id()
            ]);
            
            $mensajeLog .= " (Con comprobante adjunto)";
        }

        // HU-006: Trazabilidad de cambios manuales en log_auditoria
        Auditoria::create([
            'usuario_id' => Auth::id(), // Admin que hace el cambio
            'accion' => 'GESTION_DIAS_SUSCRIPCION',
            'detalle' => $mensajeLog,
            'direccion_ip' => $request->ip(),
            'resultado' => 'exitoso',
            'fecha_hora' => now(),
        ]);

        return back()->with('success', $mensajeLog);
    }
}
