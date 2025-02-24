<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogTranslation extends CachedModel
{
    use LogsActivity;

    public $timestamps = false;
    protected $fillable = ['title', 'details', 'meta_tag', 'meta_description', 'tags'];

    // Defina os índices de banco de dados para colunas frequentemente consultadas
    protected $casts = [
        'tags' => 'array', // Se 'tags' for armazenado como JSON ou um array no banco de dados
    ];

    /**
     * Recupera as opções de log de atividade.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blogs')
            ->logFillable()  // Log dos campos preenchidos
            ->logOnlyDirty()  // Log apenas dos campos alterados
            ->setDescriptionForEvent(function(string $eventName) {
                // Customize a descrição do evento para cada tipo de ação
                return "Blog translation {$eventName}";
            });
    }

    /**
     * Customize as propriedades da atividade de log.
     *
     * @param Activity $activity
     * @param string $eventName
     * @return void
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        // Armazene apenas dados essenciais para evitar sobrecarga
        $activity->properties = $activity->properties->put('blog_id', $this->blog_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }

    /**
     * Otimize a recuperação de traduções com cache.
     *
     * @param int $blogId
     * @param string $locale
     * @return BlogTranslation|null
     */
    public static function getTranslation(int $blogId, string $locale)
    {
        // Utilize cache para evitar consultas repetidas ao banco de dados
        return cache()->remember("blog_translation_{$blogId}_{$locale}", 60, function() use ($blogId, $locale) {
            return self::where('blog_id', $blogId)
                       ->where('locale', $locale)
                       ->first();
        });
    }
}
