<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre_rol'  => 'administrador',
                'descripcion' => 'Acceso total y administración del sistema.',
            ],
            [
                'nombre_rol'  => 'empleado',
                'descripcion' => 'Acceso restringido para tareas operativas.',
            ],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['nombre_rol' => $rol['nombre_rol']],
                ['descripcion' => $rol['descripcion']]
            );
        }
    }
}
