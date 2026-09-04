<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    
    protected $fillable = [
        'clave',
        'valor',
        'grupo',
        'tipo',
    ];

    /**
     * Helper estático para obtener un valor rápido de la base de datos
     */
    public static function getValor($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        if (!$config) {
            return $default;
        }

        // Parseo según tipo
        if ($config->tipo === 'boolean') {
            return filter_var($config->valor, FILTER_VALIDATE_BOOLEAN);
        }
        if ($config->tipo === 'integer') {
            return (int) $config->valor;
        }
        
        return $config->valor;
    }
}
