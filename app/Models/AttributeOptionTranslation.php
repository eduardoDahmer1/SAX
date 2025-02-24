<?php

namespace App\Models;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AttributeOptionTranslation extends CachedModel
{
    use LogsActivity;

    public $timestamps = false;

    // Campos "fillable" otimizados para limitar dados manipulados
    protected $fillable = ['name', 'description'];

    // Opções de log de atividade
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attribute_options')
            ->logFillable()
            ->logOnlyDirty();
    }

    // Manipulação de atividade otimizada
    public function tapActivity(Activity $activity, string $eventName)
    {
        // Cache de dados específicos de 'attribute_option_id' e 'locale'
        $activity->properties = $activity->properties
            ->put('attribute_option_id', $this->attribute_option_id)
            ->put('locale', $this->locale);

        // Definir o causer de forma eficiente
        if (auth('admin')->check()) {
            $activity->causer_id = auth('admin')->id();
            $activity->causer_type = Admin::class;
        }
    }

    // Usando Cache para carregar traduções e reduzir queries
    public function getTranslationCache($locale)
    {
        return Cache::remember("attribute_option_translation_{$this->id}_{$locale}", now()->addMinutes(60), function () use ($locale) {
            return $this->where('locale', $locale)->first();
        });
    }
}
