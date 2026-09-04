<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaTicket extends Model
{
    use HasFactory;

    protected $table = 'respuestas_ticket';

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'mensaje',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
