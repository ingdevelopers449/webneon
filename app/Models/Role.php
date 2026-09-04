<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
        'descripcion'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol', 'rol_id', 'permiso_id');
    }
}
