<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdminUserMessage extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['conversation_id', 'message', 'user_id'];

    /**
     * Define as opções de log da atividade.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_messages')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com a conversa (usando Eager Loading).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(AdminUserConversation::class, 'conversation_id')->withDefault();
    }
}
