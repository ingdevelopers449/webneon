<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Excepcion;
use App\Models\User;
use App\Models\Subscripcion;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExcepcionDemoController extends Controller
{
    public function index()
    {
        $excepciones = Excepcion::with('usuario')
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(10);
            
        $usuarios = User::where('id_rol', '!=', 1)->orderBy('name')->get();

        return view('admin.demostraciones.index', compact('excepciones', 'usuarios'));
    }

    public function otorgar(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios_sistema,id',
            'dias_asignados' => 'required|numeric|min:1',
            'justificacion' => 'required|string|max:255'
        ]);

        $usuario = User::findOrFail($request->usuario_id);
        $dias = (int) $request->dias_asignados;

        // 1. Obtener o crear suscripción
        $suscripcion = $usuario->subscripcionActiva;
        if (!$suscripcion) {
            $suscripcion = new Subscripcion();
            $suscripcion->usuario_id = $usuario->id;
            $suscripcion->fecha_inicio = now();
            $suscripcion->fecha_vencimiento = now();
        }

        // 2. Sumar los días a la fecha de vencimiento actual
        $suscripcion->fecha_vencimiento = Carbon::parse($suscripcion->fecha_vencimiento)->addDays($dias);
        $suscripcion->save();

        // 3. Modificar el tipo de periodo a 'demo' si es necesario
        if ($usuario->tipo_periodo_actual !== 'demo') {
            $usuario->tipo_periodo_actual = 'demo';
            $usuario->save();
        }

        // 4. Crear el registro en excepciones_demo
        Excepcion::create([
            'usuario_id' => $usuario->id,
            'dias_asignados' => $dias,
            'justificacion' => $request->justificacion,
            'fecha_asignacion' => now()
        ]);

        // 5. Registrar en auditoría
        Auditoria::create([
            'usuario_id' => Auth::id(),
            'accion' => 'EXCEPCION_DEMO',
            'detalle' => "Se otorgó demostración manual de {$dias} días al usuario {$usuario->email}. Razón: {$request->justificacion}",
            'direccion_ip' => $request->ip(),
            'resultado' => 'exitoso',
            'fecha_hora' => now()
        ]);

        return back()->with('success', 'Excepción de demostración otorgada exitosamente.');
    }
}
