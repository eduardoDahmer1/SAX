<?php

namespace App\Mail;

use App\Models\License;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedplayLicenseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $license;

    public function __construct(Order $order, License $license)
    {
        $this->order = $order;
        $this->license = $license;
    }

    public function build()
    {
        return $this->markdown('emails.redplay.license');
    }
}
