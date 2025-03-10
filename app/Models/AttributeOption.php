<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeOption extends LocalizedModel
{
    use LogsActivity;

    // Carregamento adiantado das traduções e do atributo relacionado para evitar múltiplas requisições
    protected $with = ['translations', 'attribute'];

    // Definindo os atributos traduzidos
    public $translatedAttributes = ['name', 'description'];

    protected $fillable = ['attribute_id'];

    /**
     * Configuração de log de atividade.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attribute_options')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com o modelo de atributo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        // Usando eager loading para carregar o relacionamento de 'attribute' de forma otimizada
        return $this->belongsTo('App\Models\Attribute')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    /**
     * Adiciona um escopo global para carregar o relacionamento com o atributo automaticamente.
     */
    protected static function booted()
    {
        static::addGlobalScope('withAttribute', function ($query) {
            $query->with('attribute');
        });
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
