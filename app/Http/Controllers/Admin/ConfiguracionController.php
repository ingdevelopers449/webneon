<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion;
use App\Models\PlanSuscripcion;
use App\Models\Role;
use App\Models\Permiso;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuraciones = Configuracion::all()->keyBy('clave');
        $planes = PlanSuscripcion::all();
        $roles = Role::with('permisos')->get();
        $permisos = Permiso::all();

        return view('admin.configuracion.index', compact('configuraciones', 'planes', 'roles', 'permisos'));
    }

    public function guardarGeneral(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $clave => $valor) {
            Configuracion::updateOrCreate(
                ['clave' => $clave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configuración general guardada exitosamente.');
    }

    public function guardarPlan(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'duracion_meses' => 'required|integer|min:1',
        ]);

        PlanSuscripcion::create($request->all());

        return back()->with('success', 'Plan de suscripción creado exitosamente.');
    }

    public function actualizarPermisos(Request $request, $id_rol)
    {
        $rol = Role::findOrFail($id_rol);
        $rol->permisos()->sync($request->permisos ?? []);

        return back()->with('success', 'Permisos actualizados para el rol ' . $rol->nombre_rol);
    }
}
