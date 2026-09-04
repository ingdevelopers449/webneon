<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSuscripcion extends Model
{
    protected $table = 'planes_suscripcion';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_meses',
        'activo',
    ];
}
