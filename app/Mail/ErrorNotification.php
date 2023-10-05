<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $content;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content,$subject)
    {
        $this->content = $content;
        $this->subject = $subject;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'afnan';
        return $this->from('alwsataofficial@alwsata.com.sa')
        ->subject($this->subject)
        ->view('emails.email')->with([
            'content'   => $this->content,
        ]);
    }
}
