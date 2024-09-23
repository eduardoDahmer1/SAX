<?php

namespace App\Models;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ChildcategoryTranslation extends CachedModel
{
    use LogsActivity;
    public $timestamps = false;
    protected $fillable = ['name'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('child_categories')
            ->logFillable()
            ->logOnlyDirty();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties->put('childcategory_id', $this->childcategory_id)
                              ->put('locale', $this->locale);
    }
}
