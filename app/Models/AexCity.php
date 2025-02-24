<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AexCity extends CachedModel
{
    protected $fillable = [
        'codigo_ciudad',
        'denominacion',
        'codigo_departamento',
        'codigo_pais',
        'ubicacion_geografica',
        'departamento_denominacion',
        'pais_denominacion',
    ];

    public $timestamps = false;

    // Cache: Método para buscar cidades com cache
    public static function getCityByCode($codigoCiudad)
    {
        return Cache::remember("city_{$codigoCiudad}", now()->addMinutes(60), function () use ($codigoCiudad) {
            return self::where('codigo_ciudad', $codigoCiudad)->first();
        });
    }
}
