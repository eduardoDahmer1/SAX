<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Banner extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['photo', 'link', 'type'];
    protected $storeSettings;

    // Utilizando o método de cache para armazenar configurações e reduzir as chamadas de resolve()
    public function __construct()
    {
        // Configurações da loja são recuperadas de maneira eficiente
        if (!$this->storeSettings) {
            $this->storeSettings = resolve('storeSettings');
        }
        parent::__construct();
    }

    // Limitando o log a campos específicos
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('banners')
            ->logOnly(['photo', 'link', 'type']) // Logando apenas os campos essenciais
            ->logOnlyDirty();
    }

    // Consultas de relacionamento podem ser otimizada para evitar excessos de joins ou queries
    public function stores()
    {
        return $this->belongsToMany('App\Models\Generalsetting', 'banner_store', 'banner_id', 'store_id');
    }

    // Scope otimizado para aplicar filtragem de forma eficiente
    public function scopeByStore($query)
    {
        // Cache para evitar execução repetida do resolve() em cada consulta
        if ($this->storeSettings) {
            return $query->whereHas('stores', function ($query) {
                $query->where('store_id', $this->storeSettings->id);
            });
        }

        return $query; // Caso storeSettings não tenha sido carregado, retorna o query sem alteração
    }

    // Scope para banners ativos
    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }
}
