<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WishlistGroup extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeCurrentUser($query, User $user)
    {
        $query->where('user_id', $user->id);
    }
}
