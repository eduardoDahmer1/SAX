<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AdminUserMessage extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['conversation_id', 'message', 'user_id'];

    // Cache: Armazenar mensagens com seus relacionamentos
    public static function getMessageWithConversation($messageId)
    {
        return Cache::remember("message_{$messageId}_conversation", now()->addMinutes(60), function () use ($messageId) {
            return self::with('conversation')->find($messageId);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_messages')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function conversation()
    {
        // Definir valores padrão de forma mais eficiente
        return $this->belongsTo(AdminUserConversation::class, 'conversation_id')
            ->withDefault([
                'conversation_id' => null, // Exemplo de valor padrão
                'message' => __('Deleted'),
                'user_id' => null,
                // Adicione outros campos necessários com valores padrão
            ]);
    }
}
