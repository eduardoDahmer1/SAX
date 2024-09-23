<?php

namespace App\Mail;

use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeddingListPublished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ?int $number;

    public function __construct(public User $user)
    {
        $this->number = Generalsetting::first()->number;
        $this->subject(__('Wedding List') . ' - ' . $user->name);
    }

    public function build()
    {
        return $this->view('emails.bridal.bridal-list');
    }
}
