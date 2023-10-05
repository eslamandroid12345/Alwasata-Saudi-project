<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationReminder extends Mailable
{
    use Queueable, SerializesModels;
    protected $body;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$body)
    {
        $this->body = $body;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'alwsataofficial@alwsata.com.sa';
        $name = ' شركة الوساطة العقارية';
        $subject = 'تذكير جديد';

        return $this->to($this->user)->subject($subject)->from($address, $name)->
        markdown('emails.notification-reminder',['body' => $this->body]);
    }
}
