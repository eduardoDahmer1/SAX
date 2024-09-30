<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class AdminEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $email_body;
    public $extraData;
    private $title;
    private $filePath;
    public function __construct($email_body, $title, $extraData = [], $filePath = null)
    {
        $this->email_body = $email_body;
        $this->extraData = $extraData;
        $this->title = $title;
        $this->filePath = $filePath;
    }
    public function build()
    {
        $mail = $this->replyTo($this->extraData['reply'] ?? config('mail.reply_to.address'))
            ->subject($this->title)
            ->view('admin.email.mailbody');
        if (!empty($this->extraData['from_email'])) {
            $mail->from($this->extraData['from_email'], $this->extraData['from_name'] ?? '');
        }
        if ($this->filePath) {
            $mail->attach($this->filePath);
        }
        return $mail;
    }
}
