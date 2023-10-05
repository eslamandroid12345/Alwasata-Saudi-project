<?php

namespace App\Channels;

use App\Helpers\MyHelpers;
use App\Notifications\Base\BaseSmsMessage;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /** @var BaseSmsMessage */
    protected $instance;

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
        // Todo: Send SMS Channel
        $smsContent = $notification->toSms($notifiable);
        MyHelpers::sendSMS($notification->getTo(), $smsContent);
        //dd($smsContent);
        //return;
        //$this->instance = $notification->toSms($notifiable);
        //$message = $this->instance->getContent();
        //$mobile = $this->instance->getMobile();
        ////d($message, $mobile);
        //$result = "SMS Disabled";
        //if ($message && config('config.send_sms')) {
        //    try {
        //        //Send SMS
        //        //$result = send_sms($message, $mobile);
        //    }
        //    catch (Exception $exception) {
        //        $result = $exception->getMessage();
        //    }
        //}
        //$message = $message ?: "Message is empty.";
        //$log = "[Mobile: ".(is_array($mobile) ? implode(',', $mobile) : $mobile)."]".PHP_EOL;
        //$log .= "[Message]:".PHP_EOL.$message.PHP_EOL;
        //$log .= "[Result: {$result}]";
        //
        ////$log;
    }
}
