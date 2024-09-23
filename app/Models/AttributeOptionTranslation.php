<?php

namespace App\Models;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeOptionTranslation extends CachedModel
{
    use LogsActivity;
    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attribute_options')
            ->logFillable()
            ->logOnlyDirty();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties
            ->put('attribute_option_id', $this->attribute_option_id)
            ->put('locale', $this->locale);

        $activity->causer_id = auth('admin')->id();
        $activity->causer_type = Admin::class;
    }
}
