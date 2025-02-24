<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdminUserConversation extends CachedModel
{
    // Eager Loading: Adicionar um método para carregar os relacionamentos com cache
    public static function getConversationWithRelations($conversationId)
    {
        return Cache::remember("conversation_{$conversationId}_relations", now()->addMinutes(60), function () use ($conversationId) {
            return self::with(['user', 'admin', 'messages', 'notifications'])
                ->find($conversationId);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault([
            'name' => __('Deleted'),
            // Adicione outros campos padrão conforme necessário
        ]);
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin')->withDefault([
            'name' => __('Deleted'),
            // Adicione outros campos padrão conforme necessário
        ]);
    }

    public function messages()
    {
        return $this->hasMany('App\Models\AdminUserMessage', 'conversation_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\UserNotification', 'conversation1_id');
    }
}
