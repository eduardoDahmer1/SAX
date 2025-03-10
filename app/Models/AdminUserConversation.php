<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserConversation extends CachedModel
{
    /**
     * Relacionamento com o usuário (usando Eager Loading).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    /**
     * Relacionamento com o administrador (usando Eager Loading).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin')->withDefault();
    }

    /**
     * Relacionamento com as mensagens (usando Eager Loading).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\AdminUserMessage', 'conversation_id');
    }

    /**
     * Relacionamento com as notificações (usando Eager Loading).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\UserNotification', 'conversation1_id');
    }
}
