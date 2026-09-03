<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'telefono',
    'id_rol',
    'estado_cuenta',
    'demo_utilizada',
    'tipo_periodo_actual',
    'fecha_inicio_periodo',
    'fecha_fin_periodo',
    'estado_suscripcion',
    'cancelacion_solicitada',
    'fecha_solicitud_cancelacion',
    'suspension_inmediata',
    'moneda',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Tabla vinculada en la base de datos.
     *
     * @var string
     */
    protected $table = 'usuarios_sistema';

    /**
     * Desactiva los campos automáticos created_at y updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Conversión de tipos de datos (Casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'                    => 'hashed',
            'demo_utilizada'              => 'boolean',
            'cancelacion_solicitada'      => 'boolean',
            'suspension_inmediata'        => 'boolean',
            'fecha_inicio_periodo'        => 'datetime',
            'fecha_fin_periodo'           => 'datetime',
            'fecha_solicitud_cancelacion' => 'datetime',
            'fecha_registro'              => 'datetime',
        ];
    }

    /**
     * Relación con la tabla roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function subscripcionActiva()
    {
        return $this->hasOne(Subscripcion::class, 'usuario_id', 'id')
                    ->where('fecha_vencimiento', '>=', now())
                    ->latestOfMany();
    }
}
