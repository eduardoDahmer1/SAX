<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class Childcategory extends LocalizedModel
{
    use LogsActivity;

    // Eager loading da relação translations
    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    protected $fillable = ['subcategory_id', 'slug', 'category_id', 'status', 'ref_code', 'banner'];

    public $timestamps = false;

    // Log de atividades
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('child_categories')
            ->logFillable()
            ->logOnlyDirty();  // Logar apenas os campos alterados
    }

    // Relacionamento com Subcategory
    public function subcategory()
    {
        // Usar eager loading sempre que possível para reduzir a quantidade de consultas
        return $this->belongsTo('App\Models\Subcategory');
    }

    // Relacionamento com Category
    public function category()
    {
        // Evitar `withDefault` se não for estritamente necessário
        return $this->belongsTo('App\Models\Category');
    }

    // Relacionamento com Produtos
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    // Definir Slug de forma otimizada
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    // Relacionamento com Atributos
    public function attributes()
    {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }

    // Gerar URL de Banner
    public function getBannerLinkAttribute()
    {
        // Verificação de banner diretamente
        return $this->banner ? asset('storage/images/childcategories/banners/' . $this->banner) : null;
    }

    // Utilização de Cache para otimizar consulta
    public static function getCachedCategory($id)
    {
        // Cache por 10 minutos para otimizar o desempenho em leituras frequentes
        return Cache::remember("childcategory_{$id}", now()->addMinutes(10), function () use ($id) {
            return self::find($id);
        });
    }
}
