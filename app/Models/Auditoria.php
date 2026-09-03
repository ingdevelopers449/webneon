<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    // 1. Especificamos el nombre exacto de la tabla
    protected $table = 'log_auditoria';

    // 2. Desactivamos los timestamps por defecto (created_at, updated_at) 
    // ya que nuestra migración usa 'fecha_hora' de forma personalizada.
    public $timestamps = false;

    // 3. Definimos los campos que se pueden insertar de forma masiva
    protected $fillable = [
        'usuario_id',
        'correo_intentado',
        'accion',
        'detalle',
        'direccion_ip',
        'resultado',
        'fecha_hora',
    ];

    // 4. Casteamos fecha_hora a un objeto Carbon (datetime) para facilitar su uso
    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
        ];
    }

    /**
     * Relación: Una auditoría pertenece a un usuario específico
     * (puede ser nulo si fue un intento fallido de login).
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
}
