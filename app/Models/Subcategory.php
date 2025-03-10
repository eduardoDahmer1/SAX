<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subcategory extends LocalizedModel
{
    use LogsActivity;

    // Carregar as traduções com Eager Loading para evitar consultas adicionais
    protected $with = ['translations', 'category', 'childs', 'childs_order_by', 'products'];

    public $translatedAttributes = ['name'];
    protected $fillable = ['category_id','slug','status','ref_code', 'banner'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('subcategories')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Carregar filhos com status ativo usando Eager Loading
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Childcategory')
                    ->where('status', '=', 1);
    }

    /**
     * Carregar filhos ordenados por slug com status ativo, com Eager Loading
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs_order_by()
    {
        return $this->hasMany('App\Models\Childcategory')
                    ->where('status', '=', 1)
                    ->orderBy('slug');
    }

    /**
     * Carregar a categoria associada com Eager Loading
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    /**
     * Carregar produtos associados com Eager Loading
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Definir o slug da subcategoria
     * 
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    /**
     * Carregar atributos associados com Eager Loading
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attributes()
    {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }

    /**
     * Obter o link da imagem do banner
     * 
     * @return string|null
     */
    public function getBannerLinkAttribute()
    {
        return $this->banner ? asset('storage/images/subcategories/banners/'.$this->banner) : null;
    }
}
