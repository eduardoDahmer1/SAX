<?php

namespace App\Policies;
use App\Models\User;
use App\Models\WishlistGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistGroupPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, WishlistGroup $wishlistGroup)
    {
        return $wishlistGroup->is_public || optional($user)->id === $wishlistGroup->user_id;
    }
    public function delete(User $user, WishlistGroup $wishlistGroup)
    {
        return $user->id === $wishlistGroup->user_id;
    }
    public function update(User $user, WishlistGroup $wishlistGroup)
    {
        return $user->id === $wishlistGroup->user_id;
    }
}
