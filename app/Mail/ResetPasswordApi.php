<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordApi extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code,$customer)
    {
        $this->code = $code;
        $this->customer = $customer;
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
        return $this->to($this->customer)->subject($subject)->from($address, $name)->
        markdown('emails.resetPasswordEmailApi',['code' => $this->code,'customer' => $this->customer]);
    }
}
