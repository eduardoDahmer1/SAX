<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartAbandonment extends Model
{
    protected $fillable = ['temp_cart'];

    /**
     * Decodifica o JSON do carrinho e retorna como um objeto Cart.
     */
    protected function tempCart(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => new Cart(json_decode($value, true) ?? [])
        );
    }

    /**
     * Relacionamento com User usando eager loading opcional para otimização.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(fn ($data) => collect($data->getFillable())->mapWithKeys(fn ($dt) => [$dt => __('Deleted')]));
    }
}
