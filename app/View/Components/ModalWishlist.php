<?php

namespace App\View\Components;

use App\Models\WishlistGroup;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class ModalWishlist extends Component
{
    public $wishlistGroup;

    public function __construct()
    {
        // Cache para armazenar a consulta de wishlist do usuário
        $this->wishlistGroup = Cache::remember('wishlist_group_' . auth()->id(), now()->addMinutes(10), function () {
            return WishlistGroup::currentUser(auth()->user())->get();
        });
    }

    public function render()
    {
        return view('components.modal-wishlist');
    }
}
