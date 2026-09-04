<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Subscripcion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        // HU-007: Listado de usuarios excluyendo a los administradores (id_rol = 1)
        $usuarios = User::where('id_rol', '!=', 1)->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // HU-001, HU-002: Creación de usuarios con validación de correo único
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'nullable|string|max:20', // <--- Agregamos esta línea
            'password' => 'required|string|min:8',
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono, // <--- Atrapamos el dato y lo guardamos
            'password' => Hash::make($request->password),
            'id_rol' => 2, // Rol de Cliente del sistema por defecto
            'estado_cuenta' => 'activo', // Activo por defecto
            'fecha_inicio_periodo' => now(),
            'fecha_fin_periodo' => now()->addDays(7),
            'fecha_registro' => now(),
        ]);

        // HU-006: Generar un registro correspondiente en la tabla de suscripciones (Demo 7 días)
        Subscripcion::create([
            'usuario_id' => $usuario->id,
            'fecha_inicio' => now(),
            'fecha_vencimiento' => now()->addDays(7),
        ]);

        return back()->with('success', 'Usuario creado exitosamente con 7 días de demo.');
    }

    public function update(Request $request, $id)
    {
        // HU-007: Edición de usuarios
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function cambiarEstado(Request $request, $id)
    {
        // HU-007: Gestión de estados de usuario
        $request->validate([
            'estado' => 'required|in:activo,inactivo,bloqueado'
        ]);

        $usuario = User::findOrFail($id);
        $usuario->estado_cuenta = $request->estado;
        $usuario->save();

        // Al cambiar el estado aquí, la vista del Módulo de Suscripciones
        // reflejará este estado automáticamente porque lee User->estado_cuenta.
        return back()->with('success', "El estado del usuario {$usuario->name} ha cambiado a: {$request->estado}.");
    }
}
