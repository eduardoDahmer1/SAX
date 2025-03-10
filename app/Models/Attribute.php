<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Attribute extends LocalizedModel
{
    use LogsActivity;

    // Definindo a tradução como padrão para otimizar as requisições
    protected $with = ['translations'];

    // Propriedades que serão traduzidas
    public $translatedAttributes = ['name'];

    protected $fillable = [
        'attributable_id', 
        'attributable_type', 
        'input_name', 
        'price_status', 
        'show_price', 
        'details_status'
    ];

    /**
     * Configuração de log de atividade.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attributes')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento Polimórfico
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attributable()
    {
        return $this->morphTo();
    }

    /**
     * Relacionamento com as opções de atributo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute_options()
    {
        return $this->hasMany('App\Models\AttributeOption');
    }

    /**
     * Personalização do log de atividade.
     *
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = auth('admin')->user()->id;
        $activity->causer_type = Admin::class;
    }
}
