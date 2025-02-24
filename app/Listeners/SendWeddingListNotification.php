<?php

namespace App\Listeners;
use App\Events\PublishedWeddingList;
use App\Mail\WeddingListPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
class SendWeddingListNotification implements ShouldQueue
{
    public function handle(PublishedWeddingList $event)
    {
        Mail::to($event->user->email)
            ->bcc(config('mail.from.address'))
            ->send(new WeddingListPublished($event->user));
    }
}
