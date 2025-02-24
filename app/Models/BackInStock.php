<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BackInStock extends CachedModel
{
    protected $fillable = ['product_id', 'email'];
    protected $table = "back_in_stock";

    // Eager load do relacionamento para otimizar as consultas
    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            // Melhorar a eficiência do preenchimento dos dados
            // Preencher apenas os campos necessários ou quando realmente necessário
            foreach ($data->getFillable() as $dt) {
                if (!isset($data[$dt])) {
                    $data[$dt] = __('Deleted');
                }
            }
        });
    }

    // Cache para evitar consultas repetidas desnecessárias
    public static function getBackInStockByProductId($productId)
    {
        return Cache::remember("back_in_stock_{$productId}", now()->addMinutes(60), function () use ($productId) {
            return self::where('product_id', $productId)->get();
        });
    }
}
