<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Childcategory extends LocalizedModel
{
    use LogsActivity;

    protected $with = ['translations'];
    public $translatedAttributes = ['name'];
    protected $fillable = ['subcategory_id', 'slug', 'category_id', 'status', 'ref_code', 'banner'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('child_categories')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class)->withDefault($this->getDeletedDefaults());
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault($this->getDeletedDefaults());
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function getBannerLinkAttribute()
    {
        return $this->banner ? asset("storage/images/childcategories/banners/{$this->banner}") : null;
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    private function getDeletedDefaults()
    {
        return function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        };
    }
}
