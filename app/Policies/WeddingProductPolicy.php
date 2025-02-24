<?php

namespace App\Policies;
use App\Models\User;
use App\Models\WeddingProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeddingProductPolicy
{
    use HandlesAuthorization;
    public function view(?User $user, User $owner)
    {
        return $owner->id === $user?->id || $owner->is_wedding;
    }
    public function buy(User $user, User $owner)
    {
        return $owner->is_wedding;
    }
}
