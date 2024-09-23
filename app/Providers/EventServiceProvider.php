<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\BackInStock;
use App\Events\PublishedWeddingList;
use App\Events\WatchPix;
use App\Listeners\HandleBackInStock;
use App\Listeners\HandleWatchPix;
use App\Listeners\SendWeddingListNotification;
use App\Models\Brand;
use App\Observers\BrandObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BackInStock::class => [
            HandleBackInStock::class
        ],
        WatchPix::class => [
            HandleWatchPix::class
        ],
        PublishedWeddingList::class => [
            SendWeddingListNotification::class
        ]
    ];
    public function boot()
    {
        parent::boot();
        Brand::observe(BrandObserver::class);
    }
}
