<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class Attribute extends LocalizedModel
{
    use LogsActivity;

    // Carregar traduções condicionalmente, apenas quando necessário
    protected $with = ['translations'];

    // Define os atributos traduzíveis
    public $translatedAttributes = ['name'];

    // Definir os atributos "fillable"
    protected $fillable = ['attributable_id', 'attributable_type', 'input_name', 'price_status', 'show_price', 'details_status'];

    // Atividade de log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attributes')
            ->logFillable()
            ->logOnlyDirty();
    }

    // Relacionamento polimórfico
    public function attributable()
    {
        return $this->morphTo();
    }

    // Carregar as opções de atributos com cache
    public function attribute_options()
    {
        // Cache: Armazena as opções de atributo em cache por 60 minutos
        return Cache::remember("attribute_{$this->id}_options", now()->addMinutes(60), function () {
            return $this->hasMany('App\Models\AttributeOption')->get();
        });
    }

    // Registrar atividade
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = auth('admin')->user()->id;
        $activity->causer_type = Admin::class;
    }
}
