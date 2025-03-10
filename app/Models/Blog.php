<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Blog extends LocalizedModel
{
    use LogsActivity;

    // Eager loading automático para evitar consultas N+1
    protected $with = ['translations', 'category'];

    protected $translatedAttributes = ['title', 'details', 'meta_tag', 'meta_description', 'tags'];
    protected $fillable = ['category_id', 'photo', 'source', 'views', 'updated_at', 'status'];
    protected $dates = ['created_at'];
    public $timestamps = false;

    /**
     * Configuração do log de atividade
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blogs')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Define o timestamp de criação automaticamente
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * Relacionamento com a categoria do blog
     * Usa eager loading para evitar consultas desnecessárias
     */
    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id')
            ->withDefault(function ($data) {
                foreach ($data->getFillable() as $dt) {
                    $data[$dt] = __('Deleted');
                }
            });
    }

    /**
     * Escopo para filtrar apenas blogs ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Manipula os atributos `tags` e `meta_tag` para garantir que sejam arrays
     */
    public function getTagsAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    public function getMetaTagAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }
}
