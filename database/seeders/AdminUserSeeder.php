<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ahora = Carbon::now();

        User::updateOrCreate(
            ['email' => 'admin@neon.com'], // Condición única para no duplicar
            [
                'name'                        => 'Administrador del Sistema',
                'password'                    => 'Admin123', // El cast 'password' => 'hashed' del modelo lo encripta solo
                'telefono'                    => '3001234567',
                'id_rol'                      => 1,
                'estado_cuenta'               => 'activo',
                'demo_utilizada'              => false,
                'tipo_periodo_actual'         => 'suscripcion',
                'fecha_inicio_periodo'        => $ahora,
                'fecha_fin_periodo'           => $ahora->copy()->addYears(10), // Vigencia amplia para el admin
                'estado_suscripcion'          => 'activa',
                'cancelacion_solicitada'      => false,
                'fecha_solicitud_cancelacion' => null,
                'suspension_inmediata'        => false,
                'moneda'                      => 'COP',
            ]
        );
    }
}
