<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sesion;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    public function index()
    {
        $sesiones = Sesion::where('usuario_id', Auth::id())
            ->orderBy('activa', 'desc')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);
            
        return view('admin.sesiones.index', compact('sesiones'));
    }

    public function destroy($id)
    {
        $sesion = Sesion::where('usuario_id', Auth::id())->findOrFail($id);
        
        $sesion->update([
            'activa' => false,
            'fecha_cierre' => now()
        ]);
        
        // Destruir la sesión real en Laravel que coincida con esa IP y UserAgent
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('ip_address', $sesion->direccion_ip)
            ->where('user_agent', $sesion->dispositivo)
            ->where('id', '!=', request()->session()->getId())
            ->delete();
        
        return back()->with('success', 'Sesión cerrada exitosamente en ese dispositivo.');
    }

    public function destroyAll()
    {
        // 1. Cerramos todas las sesiones activas en nuestro Log excepto la actual
        Sesion::where('usuario_id', Auth::id())
            ->where('activa', true)
            ->where(function ($query) {
                $query->where('dispositivo', '!=', request()->userAgent())
                      ->orWhere('direccion_ip', '!=', request()->ip());
            })
            ->update([
                'activa' => false,
                'fecha_cierre' => now()
            ]);
            
        // 2. Destruir las sesiones reales en la tabla nativa de Laravel para forzar el logout
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
            
        return back()->with('success', 'Se cerraron todas las demás sesiones activas.');
    }
}
