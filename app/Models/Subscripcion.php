<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscripcion extends Model
{
    use HasFactory;
    // 1. Nombre explícito de la tabla en tu base de datos
    protected $table = 'suscripciones';
    
    // 2. Clave primaria (nuestra migración creó la columna 'id' por defecto)
    protected $primaryKey = 'id';

    // 3. Atributos asignables masivamente
    protected $fillable = [
        'usuario_id',
        'plan_id',
        'precio',
        'tipo_suscripcion',
        'fecha_inicio',
        'fecha_vencimiento',
        'dias_restantes',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'      => 'datetime',
            'fecha_vencimiento' => 'datetime',
            'precio'            => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'usuario_id',
            'id'
        );
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanSuscripcion::class, 'plan_id');
    }

    public function comprobantes()
    {
        return $this->hasMany(Comprobantes::class, 'suscripcion_id');
    }

    public function ultimoComprobante()
    {
        return $this->hasOne(Comprobantes::class, 'suscripcion_id')->latestOfMany('fecha_carga');
    }
}
