<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('staff')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault(fn($data) => collect($data->getFillable())->each(fn($dt) => $data[$dt] = __('Deleted')));
    }

    public function isSuper(): bool
    {
        return $this->role_id == 0;
    }

    public function sectionCheck($value): bool
    {
        return $this->isSuper() || in_array($value, explode(' , ', $this->role->section));
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset("storage/images/admins/{$this->photo}") : asset('assets/images/user.jpg');
    }
}
