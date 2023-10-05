<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendResetPasswordLinkCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $verifyUrl;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url,$user)
    {
        $this->verifyUrl = $url;
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
        $subject = 'إعادة تعيين كلمة المرور';
        return $this->to($this->user)->subject($subject)->from($address, $name)->
        markdown('emails.reset-password-admin',['url' => $this->verifyUrl,'user' => $this->user]);
    }
}
