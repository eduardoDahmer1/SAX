<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WeddingProduct extends Pivot
{
    protected $table = 'wedding_products';
    public $timestamps = false;

    // Relação com o comprador (buyer), utilizando eager loading
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    // Relação com o usuário (user), utilizando eager loading
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relação com os pedidos (orders), utilizando eager loading
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_wedding_product', 'wedding_product_id', 'order_id');
    }

    // Método para carregar os dados de buyer, user e orders juntos, evitando múltiplas consultas
    public static function withEagerLoading()
    {
        return self::with(['buyer', 'user', 'orders'])->get();
    }
}
