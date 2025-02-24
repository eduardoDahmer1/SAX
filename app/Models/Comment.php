<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class Comment extends CachedModel
{
    use LogsActivity;
    
    protected $fillable = ['product_id', 'user_id', 'text'];

    /**
     * Configuração do log de atividades.
     * Logar apenas os campos modificados.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('comments')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com o usuário.
     * Utiliza eager loading e fallback simplificado.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(['name' => __('Deleted')]);
    }

    /**
     * Relacionamento com o produto.
     * Utiliza eager loading e fallback simplificado.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(['name' => __('Deleted')]);
    }

    /**
     * Relacionamento com as respostas.
     * Utiliza eager loading para evitar consultas N+1.
     */
    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    /**
     * Cache para carregar comentários por produto.
     * Usado para evitar múltiplas consultas repetitivas.
     */
    public static function getCachedCommentsForProduct($productId)
    {
        // Usando cache para armazenar os comentários para o produto por 10 minutos.
        return Cache::remember("comments_for_product_{$productId}", now()->addMinutes(10), function () use ($productId) {
            return self::with(['user', 'replies'])->where('product_id', $productId)->get();
        });
    }
}
