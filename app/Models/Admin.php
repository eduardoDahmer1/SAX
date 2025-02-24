<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;

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

    // Eager load the role when retrieving Admins
    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault([
            'name' => __('Deleted'),  // Provide a default value for 'name' if role is not found
        ]);
    }

    public function isSuper(): bool
    {
        // Direct comparison, simplified
        return $this->role_id == 0;
    }

    public function sectionCheck($value): bool
    {
        // Cache the result of this check if it's not frequently changing
        $sections = Cache::remember("admin_{$this->id}_sections", now()->addMinutes(30), function () {
            // Ensure to cache the sections after being split
            return explode(' , ', $this->role->section);
        });

        return $this->isSuper() || in_array($value, $sections);
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
