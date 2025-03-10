<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Slider extends LocalizedModel
{
    use LogsActivity;

    protected $storeSettings;
    protected $with = ['translations'];
    protected $translatedAttributes = ['subtitle_text', 'title_text', 'details_text', 'name'];
    protected $fillable = [
        'subtitle_size', 'subtitle_color', 'subtitle_anime',
        'title_size', 'title_color', 'title_anime',
        'details_size', 'details_color', 'details_anime',
        'photo', 'position', 'link', 'status'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->storeSettings = resolve('storeSettings');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sliders')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function stores()
    {
        return $this->belongsToMany(Generalsetting::class, 'slider_store', 'slider_id', 'store_id');
    }

    public function scopeByStore($query)
    {
        if ($this->storeSettings) {
            return $query->whereHas('stores', function ($q) {
                $q->where('store_id', $this->storeSettings->id);
            });
        }
        return $query;
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }
}
