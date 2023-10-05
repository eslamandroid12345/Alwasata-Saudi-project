<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits;

trait HasSlackNotification
{

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        if (method_exists($notification, 'getSlackChannel')) {
            return $notification->getSlackChannel();
        }
        //dd($notification);
        return config('config.slack.url');
    }
}
