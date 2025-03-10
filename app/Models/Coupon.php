<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends CachedModel
{
    use LogsActivity;

    protected $fillable = [
        'code', 'type', 'price', 'times', 'start_date', 'end_date',
        'minimum_value', 'maximum_value', 'category_id', 'brand_id', 'discount_type'
    ];

    public $timestamps = false;

    /**
     * Configuração do log de atividade para o modelo Coupon.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('coupons')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com a categoria (Eager Loading opcional).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    /**
     * Relacionamento com a marca (Eager Loading opcional).
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }
}
