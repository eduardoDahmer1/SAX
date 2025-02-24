<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class Blog extends LocalizedModel
{
    use LogsActivity;

    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'details', 'meta_tag', 'meta_description', 'tags'];
    protected $fillable = ['category_id', 'photo', 'source', 'views', 'updated_at', 'status'];
    protected $dates = ['created_at'];
    public $timestamps = false;

    /**
     * Opções de log para melhorar a performance
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blogs')
            ->logOnly(['category_id', 'photo', 'source', 'views', 'status', 'updated_at']) // Logando apenas os campos relevantes
            ->logOnlyDirty(); // Log apenas os campos modificados
    }

    /**
     * Inicialização do modelo. 'created_at' agora é gerenciado automaticamente.
     */
    public static function boot()
    {
        parent::boot();

        // O Eloquent já gerencia o 'created_at' automaticamente, sem necessidade de intervenção manual.
    }

    /**
     * Relacionamento otimizado com a categoria.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id')
                    ->withDefault(['name' => __('Deleted')]);  // Simplifica o uso do withDefault
    }

    /**
     * Cache e processamento otimizado de tags.
     */
    public function getTagsAttribute($value)
    {
        // Cache da propriedade 'tags' para evitar recalcular se não houve alteração
        return Cache::remember("blog_{$this->id}_tags", 60, function () use ($value) {
            return $value ? explode(',', $value) : [];
        });
    }

    /**
     * Cache e processamento otimizado de meta_tags.
     */
    public function getMetaTagAttribute($value)
    {
        // Cache da propriedade 'meta_tag' para evitar recalcular se não houve alteração
        return Cache::remember("blog_{$this->id}_meta_tag", 60, function () use ($value) {
            return $value ? explode(',', $value) : [];
        });
    }
}
