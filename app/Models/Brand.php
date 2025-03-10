<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['name', 'slug', 'image', 'partner', 'ref_code', 'banner'];
    public $timestamps = false;

    // Configuração do LogActivity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('brands')
            ->logFillable()
            ->logOnlyDirty();
    }

    // Relacionamento com os produtos, utilizando Eager Loading
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Atributo Image com otimização para não repetir as verificações
    public function getImageAttribute($value)
    {
        $imagePath = public_path('storage/images/brands/' . $value);
        if (File::exists($imagePath) && !File::isDirectory($imagePath)) {
            return asset('storage/images/brands/' . $value);
        }

        return null;
    }

    // Atributo Thumbnail com otimização
    public function getThumbnailAttribute($value)
    {
        $thumbnailPath = public_path('storage/images/thumbnails/' . $value);
        if ($value && File::exists($thumbnailPath) && !File::isDirectory($thumbnailPath)) {
            return asset('storage/images/thumbnails/' . $value);
        }

        // Caso não tenha thumbnail, retorna uma imagem padrão
        return asset('assets/images/noimage.png');
    }

    // Escopo para buscar marcas ativas
    public function scopeActive($query)
    {
        return $query->where('brands.status', 1);
    }

    // Escopo para buscar marcas inativas
    public function scopeInactive($query)
    {
        return $query->where('brands.status', 0);
    }

    // Escopo para buscar marcas com produtos
    public function scopeWithProducts($query)
    {
        return $query->with('products');  // Usando Eager Loading para o relacionamento
    }

    // Escopo para buscar marcas sem produtos
    public function scopeWithoutProducts($query)
    {
        return $query->doesntHave('products');
    }
}
