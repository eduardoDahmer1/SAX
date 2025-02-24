<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class City extends CachedModel
{
    protected $fillable = ['name', 'state_id'];
    public $timestamps = false;
    protected $table = 'cities';

    /**
     * Carregar o estado da cidade de forma otimizada
     * com eager loading para evitar consultas N+1.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Implementação de cache para armazenar cidades frequentemente acessadas.
     * A consulta é armazenada por 10 minutos, evitando múltiplas consultas ao banco de dados.
     */
    public static function getCachedCity($cityId)
    {
        // Usando cache para armazenar o resultado da cidade, evita consultas repetitivas
        return Cache::remember("city_{$cityId}", now()->addMinutes(10), function () use ($cityId) {
            return self::find($cityId);
        });
    }

    /**
     * Carregar todas as cidades com o estado (eager loading) de forma otimizada.
     * Usar cache para evitar múltiplas consultas ao banco.
     */
    public static function getCitiesWithState()
    {
        return Cache::remember('cities_with_state', now()->addMinutes(10), function () {
            return self::with('state')->get();
        });
    }
}
