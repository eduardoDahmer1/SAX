<?php

namespace App\View\Components;

use App\Models\WishlistGroup;
use Illuminate\View\Component;

class ModalWishlist extends Component
{
    public $wishlistGroup;
    public function __construct()
    {
        $this->wishlistGroup = WishlistGroup::currentUser(auth()->user())->get();
    }
    public function render()
    {
        return view('components.modal-wishlist');
    }
}
