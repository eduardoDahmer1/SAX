<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Banner extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['photo', 'link', 'type'];
    protected $storeSettings;

    public function __construct()
    {
        $this->storeSettings = resolve('storeSettings');
        parent::__construct();
    }

    /**
     * Configuração para o log de atividades
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('banners')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento Many-to-Many com a tabela Generalsetting
     * Eager load pode ser utilizado ao buscar banners com seus stores relacionados
     */
    public function stores()
    {
        return $this->belongsToMany('App\Models\Generalsetting', 'banner_store', 'banner_id', 'store_id');
    }

    /**
     * Escopo para filtrar banners pela store associada
     * Usa `whereHas` para otimizar a consulta e evitar N+1 queries
     */
    public function scopeByStore($query)
    {
        // Garantir que o relacionamento 'stores' seja carregado eficientemente
        return $query->whereHas('stores', function($query) {
            $query->where('store_id', $this->storeSettings->id);
        })->with('stores'); // Eager loading adicionado aqui
    }

    /**
     * Escopo para filtrar banners ativos
     */
    public function scopeIsActive($query)
    {
        return $query->where('status', 1)->with('stores'); // Eager loading incluído para banners ativos
    }

    /**
     * Eager loading padrão ao buscar banners
     */
    protected static function booted()
    {
        static::addGlobalScope('withStores', function ($query) {
            $query->with('stores');
        });
    }
}
