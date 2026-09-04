<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets_soporte';

    protected $fillable = [
        'usuario_id',
        'asunto',
        'descripcion',
        'estado',
        'prioridad',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaTicket::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function ultimaRespuesta()
    {
        return $this->hasOne(RespuestaTicket::class, 'ticket_id')->latestOfMany();
    }
}
