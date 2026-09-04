<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobantes extends Model
{
    protected $table = 'comprobantes_pago';
    
    public $timestamps = false; // Manejado por 'fecha_carga' manualmente

    protected $fillable = [
        'suscripcion_id',
        'nombre_archivo',
        'ruta_archivo',
        'fecha_carga',
        'administrador_id'
    ];

    protected function casts(): array
    {
        return [
            'fecha_carga' => 'datetime',
        ];
    }

    public function suscripcion()
    {
        return $this->belongsTo(Subscripcion::class, 'suscripcion_id');
    }

    public function administrador()
    {
        return $this->belongsTo(User::class, 'administrador_id');
    }
}
