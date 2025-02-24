<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogCategoryTranslation extends CachedModel
{
    use LogsActivity;
    
    public $timestamps = false;
    
    protected $fillable = ['name'];

    /**
     * Configuração de opções de log para melhorar a performance
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blog_categories')
            ->logOnly(['name']) // Log apenas o campo necessário
            ->logOnlyDirty(); // Registra apenas mudanças
    }

    /**
     * Otimiza a gravação da atividade sem sobrescrever propriedades desnecessárias
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('blog_category_id', $this->blog_category_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
