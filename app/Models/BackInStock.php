<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackInStock extends CachedModel
{
    protected $fillable = ['product_id', 'email'];
    protected $table = "back_in_stock";

    /**
     * Relacionamento com o modelo de produto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        // Usando eager loading para carregar o relacionamento de 'product' de forma otimizada
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    /**
     * Adiciona um escopo global para carregar o relacionamento com o produto automaticamente.
     */
    protected static function booted()
    {
        static::addGlobalScope('withProduct', function ($query) {
            $query->with('product');
        });
    }
}
