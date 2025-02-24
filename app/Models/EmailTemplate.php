<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class EmailTemplate extends LocalizedModel
{
    use LogsActivity;

    // Remova a carga automática de relações desnecessárias
    // Proteja a propriedade 'translations' apenas quando necessário
    protected $fillable = ['email_type', 'status'];
    public $timestamps = false;

    // O método de logging permanece igual para garantir eficiência
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('email_templates')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Método otimizado para recuperar EmailTemplate, com cache.
     * Evita consultas repetidas ao banco.
     */
    public static function getTemplate($templateId)
    {
        // Usar cache para evitar consultas repetidas.
        return Cache::remember("email_template_{$templateId}", now()->addMinutes(60), function() use ($templateId) {
            return self::find($templateId);
        });
    }

    /**
     * Se precisar de traduções, carregue-as manualmente.
     * Isso evita o uso do `with` que sempre carrega a relação.
     */
    public function translations()
    {
        return $this->hasMany(EmailTemplateTranslation::class);
    }
}
