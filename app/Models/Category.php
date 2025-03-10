<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends LocalizedModel
{
    use LogsActivity;

    protected $with = ['translations'];
    protected $fillable = [
        'slug', 'photo', 'is_featured', 'image', 'status', 'is_customizable', 
        'ref_code', 'is_customizable_number', 'banner', 'link'
    ];
    public $translatedAttributes = ['name'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('categories')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function subs()
    {
        return $this->hasMany(Subcategory::class)->where('status', 1);
    }

    public function childs()
    {
        return $this->hasMany(Childcategory::class)->where('status', 1);
    }

    public function subs_order_by()
    {
        return $this->hasMany(Subcategory::class)->where('status', 1)->orderBy('slug');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories_galleries()
    {
        return $this->hasMany(CategoryGallery::class);
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function scopeWithProducts($query)
    {
        return $query->has('products');
    }

    public function scopeWithoutProducts($query)
    {
        return $query->doesntHave('products');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', strtolower($value));
    }

    public function getBannerLinkAttribute()
    {
        return $this->banner ? asset("storage/images/categories/banners/{$this->banner}") : null;
    }
}
