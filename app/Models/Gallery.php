<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends CachedModel
{
    protected $fillable = ['product_id', 'photo'];
    public $timestamps = false;

    /**
     * Accessor para obter a URL da foto.
     * 
     * - Evita múltiplas verificações desnecessárias no banco de dados.
     * - Utiliza eager loading para carregar a relação com o produto.
     * 
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        // Verificação para sanitizar a URL da foto e garantir que não existe no caminho físico.
        if (
            filter_var($this->photo, FILTER_SANITIZE_URL) &&
            !file_exists(storage_path('app/public/images/galleries/' . $this->photo))
        ) {
            return $this->photo;
        }

        return asset('storage/images/galleries/' . $this->photo);
    }

    /**
     * Carregamento adiantado de produto.
     * 
     * Ao buscar as galerias, é otimizado o carregamento do produto associado,
     * evitando múltiplas consultas ao banco de dados.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product')->with(['category', 'brand']); // Eager loading de categorias e marcas do produto
    }
}
