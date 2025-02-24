<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AttributeTranslation extends CachedModel
{
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = ['name'];

    // Otimização de log de atividades
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attributes')
            ->logFillable()
            ->logOnlyDirty();
    }

    // Otimização de manipulação de atividade
    public function tapActivity(Activity $activity, string $eventName)
    {
        // Consolidar a manipulação de propriedades em uma única linha
        $activity->properties->put('attribute_id', $this->attribute_id)
            ->put('locale', $this->locale);

        // Verificação eficiente de autenticação
        if ($user = auth('admin')->user()) {
            $activity->causer_id = $user->id;
            $activity->causer_type = Admin::class;
        }
    }

    // Implementação de cache para traduções
    public function getTranslationCache($locale)
    {
        return Cache::remember("attribute_translation_{$this->id}_{$locale}", now()->addMinutes(60), function () use ($locale) {
            return $this->where('locale', $locale)->first();
        });
    }
}
