<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AttributeOption extends LocalizedModel
{
    use LogsActivity;

    // Condicionar o carregamento das traduções se necessário
    protected $with = ['translations'];

    // Atributos traduzíveis
    public $translatedAttributes = ['name', 'description'];

    // Atributos "fillable"
    protected $fillable = ['attribute_id'];

    // Opções de log de atividade
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attribute_options')
            ->logFillable()
            ->logOnlyDirty();
    }

    // Relacionamento com Attribute, com cache
    public function attribute()
    {
        // Cache: Armazena o relacionamento "attribute" por 60 minutos
        return Cache::remember("attribute_option_{$this->id}_attribute", now()->addMinutes(60), function () {
            return $this->belongsTo('App\Models\Attribute')->withDefault(function ($data) {
                foreach ($data->getFillable() as $dt) {
                    $data[$dt] = __('Deleted');
                }
            });
        });
    }

    // Registrar atividade
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = auth('admin')->user()->id;
        $activity->causer_type = Admin::class;
    }
}
