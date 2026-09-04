<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    use HasFactory;

    protected $table = 'sesiones';

    public $timestamps = false; // La tabla no usa created_at y updated_at estándar

    protected $fillable = [
        'usuario_id',
        'dispositivo',
        'direccion_ip',
        'fecha_inicio',
        'ultima_actividad',
        'activa',
        'fecha_cierre',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'ultima_actividad' => 'datetime',
            'fecha_cierre' => 'datetime',
            'activa' => 'boolean',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
