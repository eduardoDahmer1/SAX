<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['name', 'slug', 'image', 'partner','ref_code', 'banner'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('brands')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Cache a verificação da imagem se ela já tiver sido checada
    public function getImageAttribute($value)
    {
        // Cache de verificação de imagem para evitar verificações repetidas
        return Cache::remember('brand_image_'.$value, 60, function() use ($value) {
            $imagePath = public_path('storage/images/brands/' . $value);
            if (!File::exists($imagePath) || File::isDirectory($imagePath)) {
                return null;
            }
            return $value;
        });
    }

    // Cache a verificação de thumbnail, com um fallback para imagem de 'sem imagem'
    public function getThumbnailAttribute($value)
    {
        return Cache::remember('brand_thumbnail_'.$value, 60, function() use ($value) {
            if (!$this->image || !$value) {
                return asset('assets/images/noimage.png');
            }

            $thumbnailPath = public_path('storage/images/thumbnails/' . $value);
            if (!File::exists($thumbnailPath) || File::isDirectory($thumbnailPath)) {
                return asset('assets/images/noimage.png');
            }
            return asset('storage/images/thumbnails/'.$value);
        });
    }

    // Escopo para marcas ativas
    public function scopeActive($query)
    {
        return $query->where('brands.status', 1);
    }

    // Escopo para marcas inativas
    public function scopeInactive($query)
    {
        return $query->where('brands.status', 0);
    }

    // Escopo para marcas com produtos
    public function scopeWithProducts($query)
    {
        return $query->has('products');
    }

    // Escopo para marcas sem produtos
    public function scopeWithoutProducts($query)
    {
        return $query->doesntHave('products');
    }
}
