<?php

namespace App\Models;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class Coupon extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['code', 'type', 'price', 'times', 'start_date', 'end_date', 'minimum_value', 'maximum_value', 'category_id', 'brand_id', 'discount_type'];
    public $timestamps = false;

    /**
     * Configura as opções de log para o modelo Coupon.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('coupons')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Cache para obter um cupom por código, evitando consultas repetidas.
     * 
     * @param string $code
     * @return Coupon|null
     */
    public static function getByCode($code)
    {
        // Cache com duração de 30 minutos (pode ser ajustado conforme a necessidade)
        return Cache::remember("coupon_{$code}", 30 * 60, function () use ($code) {
            return self::where('code', $code)->first();
        });
    }

    /**
     * Cache para listar cupons por categoria e marca.
     * 
     * @param int $categoryId
     * @param int $brandId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCouponsByCategoryAndBrand($categoryId, $brandId)
    {
        $cacheKey = "coupons_category_{$categoryId}_brand_{$brandId}";
        
        return Cache::remember($cacheKey, 30 * 60, function () use ($categoryId, $brandId) {
            return self::where('category_id', $categoryId)
                ->where('brand_id', $brandId)
                ->get();
        });
    }

    /**
     * Método que apaga o cache do cupom sempre que ele for atualizado ou excluído.
     */
    public static function boot()
    {
        parent::boot();

        // Quando um cupom for salvo ou excluído, limpe o cache relacionado a ele.
        static::saved(function ($coupon) {
            Cache::forget("coupon_{$coupon->code}");
        });

        static::deleted(function ($coupon) {
            Cache::forget("coupon_{$coupon->code}");
        });
    }
}
