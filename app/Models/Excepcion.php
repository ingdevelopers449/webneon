<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excepcion extends Model
{
    protected $table = 'excepciones_demo';

    protected $fillable = [
        'usuario_id',
        'dias_asignados',
        'justificacion', // Corregido: sin tilde, igual que en la migración
        'fecha_asignacion'
    ];

    protected function casts(): array
    {
        return [
            'fecha_asignacion' => 'datetime',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
