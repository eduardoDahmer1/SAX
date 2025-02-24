<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class CartAbandonment extends Model
{
    protected $fillable = ['temp_cart'];

    /**
     * Otimizar o acesso ao atributo temp_cart para evitar
     * a re-decodificação do valor em cada acesso.
     */
    protected function tempCart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getDecodedCart($value)
        );
    }

    /**
     * Decodificar o valor do temp_cart de forma otimizada, utilizando cache.
     */
    protected function getDecodedCart($value)
    {
        // Checar cache antes de processar a decodificação
        $cacheKey = "cart_abandonment_{$this->id}_temp_cart";

        // Verificar se o valor já está em cache
        return Cache::rememberForever($cacheKey, function () use ($value) {
            return new Cart(json_decode($value, true) ?: []);
        });
    }

    /**
     * Melhorar a performance da relação user através de eager loading.
     */
    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault(fn($data) => array_fill_keys($data->getFillable(), __('Deleted')));
    }
}
