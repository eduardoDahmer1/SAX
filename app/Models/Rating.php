<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rating extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['user_id', 'product_id', 'review', 'rating', 'review_date'];
    public $timestamps = false;
    protected $dates = ['review_date'];

    // Eager Loading automático para reduzir consultas no banco
    protected $with = ['product', 'user'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ratings')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public static function ratings($productId)
    {
        return self::where('product_id', $productId)->avg('rating') * 20;
    }

    public static function rating($productId)
    {
        return number_format((float) self::where('product_id', $productId)->avg('rating'), 1, '.', '');
    }
}
