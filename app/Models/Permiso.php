<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'clave',
        'grupo',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permiso_rol', 'permiso_id', 'rol_id');
    }
}
