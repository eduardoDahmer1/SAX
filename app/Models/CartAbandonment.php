<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CartAbandonment extends Model
{
    protected $fillable = ['temp_cart'];
    protected function tempCart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => new Cart(json_decode($value, true) ?: [])
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(fn($data) => array_fill_keys($data->getFillable(), __('Deleted')));
    }
}
