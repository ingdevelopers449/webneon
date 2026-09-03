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
        'usuario_id', // Esta es la llave foránea correcta
        'fecha_inicio',
        'fecha_vencimiento',
        'dias_restantes',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'      => 'datetime',
            'fecha_vencimiento' => 'datetime',
            // 'precio' fue removido porque no existe en la migración
        ];
    }

    public function usuario(): BelongsTo
    {
        // Parámetros: Modelo destino, llave foránea local, llave primaria destino
        return $this->belongsTo(
            User::class, 
            'usuario_id', 
            'id'
        );
    }
}
