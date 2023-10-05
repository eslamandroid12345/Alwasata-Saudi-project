<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendNotification extends Notification
{

    use Queueable;
    public $title;
    public $body;
    public $url;
    public $event_name;
    public function __construct($title,$body,$event_name,$url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
        $this->event_name = $event_name;
    }

    public
    function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon(asset('/images/icons/mipmap-ldpi.png'))
            ->body($this->body)
            ->action($this->event_name,$this->event_name) // page name to go for, avoid using slashes , just use underscores like "View_account"
            ->data(['url' => $this->url]);// id of user profile, order id, transaction id, page id .. ect
    }

}
