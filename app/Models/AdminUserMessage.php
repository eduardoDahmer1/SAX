<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class AdminUserMessage extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['conversation_id', 'message', 'user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_messages')
            ->logFillable()
            ->logOnlyDirty();
    }
    public function conversation()
    {
        return $this->belongsTo(AdminUserConversation::class, 'conversation_id')
            ->withDefault(fn($data) => collect($data->getFillable())->each(fn($dt) => $data[$dt] = __('Deleted')));
    }
}
