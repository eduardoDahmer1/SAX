<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogCategory extends LocalizedModel
{
    use LogsActivity;

    // Carregamento adiantado das traduções para evitar múltiplas consultas
    protected $with = ['translations'];

    public $translatedAttributes = ['name'];
    protected $fillable = ['slug'];
    public $timestamps = false;

    /**
     * Configuração do log de atividade.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blog_categories')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com os blogs.
     * Adiciona ordenação padrão por `created_at` para melhor desempenho.
     */
    public function blogs()
    {
        return $this->hasMany('App\Models\Blog', 'category_id')
            ->orderByDesc('created_at');
    }

    /**
     * Define o slug removendo espaços e convertendo para minúsculas.
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', strtolower($value));
    }
}
