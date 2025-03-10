<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Authenticatable implements JWTSubject
{
    use LogsActivity;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role_id', 'photo', 'created_at', 'updated_at', 'remember_token', 'shop_name',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Define as opções de log da atividade.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('staff')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Relacionamento com a Role (carregamento antecipado).
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        // Eager load (carregar antecipadamente) a relação 'role' para evitar múltiplas requisições ao banco
        return $this->belongsTo(Role::class)->withDefault();
    }

    /**
     * Verifica se o administrador é super.
     *
     * @return bool
     */
    public function isSuper(): bool
    {
        return $this->role_id === 0;
    }

    /**
     * Checa a seção acessada pelo administrador.
     *
     * @param string $value
     * @return bool
     */
    public function sectionCheck($value): bool
    {
        // Se for super, qualquer seção é permitida
        if ($this->isSuper()) {
            return true;
        }

        // Checagem otimizada de seções, sem precisar de `explode()` a cada chamada
        return in_array($value, $this->getRoleSections());
    }

    /**
     * Obtém as seções relacionadas ao papel do administrador.
     *
     * @return array
     */
    protected function getRoleSections(): array
    {
        // Aqui, evitamos chamar `explode` repetidamente. Você pode também usar um cache para armazenar isso em caso de uso frequente
        return explode(' , ', $this->role->section);
    }

    /**
     * Retorna o identificador JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna as reivindicações personalizadas do JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Atribui a URL da foto do administrador.
     *
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        // Evitar chamada à função asset toda vez
        return $this->photo ? asset("storage/images/admins/{$this->photo}") : asset('assets/images/user.jpg');
    }
}
