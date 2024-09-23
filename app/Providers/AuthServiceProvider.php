<?php

namespace App\Providers;
use App\Models\WeddingProduct;
use App\Models\WishlistGroup;
use App\Policies\WeddingProductPolicy;
use App\Policies\WishlistGroupPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        WishlistGroup::class => WishlistGroupPolicy::class,
        WeddingProduct::class => WeddingProductPolicy::class,
    ];
    public function boot()
    {
        $this->registerPolicies();
    }
}
