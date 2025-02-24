<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class CategoryGallery extends CachedModel
{
    protected $table = 'categories_gallery';

    protected $fillable = ['category_id', 'customizable_gallery', 'thumbnail'];

    public $timestamps = false;

    // Alterado para public para evitar o erro de nível de acesso
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('optimizeCategoryGallery', function ($builder) {
            $builder->with('category');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function cacheKey(): string
    {
        return 'category_gallery_all';
    }

    public static function getAllCategoryGalleries()
    {
        return Cache::remember(self::cacheKey(), now()->addMinutes(10), function () {
            return self::all();
        });
    }
}
