<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class PushTokenChannel
{

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Todo: Send Push Token
        $data = $notification->toPushToken($notifiable);
        $title = $data['title'] ?? '';
        $body = $data['body'] ?? '';
        $tokens = $data['tokens'] ?? [];
        $result = sendPushTokenNotification($tokens, $title, $body);
        $log = "[Tokens: ".(is_array($tokens) ? implode(',', $tokens) : $tokens)."]".PHP_EOL;
        $log = "[Result: ".(is_array($result) ? json_encode($tokens, JSON_UNESCAPED_UNICODE) : $tokens)."]".PHP_EOL;
    }
}
