<?php

namespace App\Providers;

use App\Channels\PushTokenChannel;
use App\Channels\SmsChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $cm = $this->app->make(ChannelManager::class);
        $cm->extend('sms', fn($app) => new SmsChannel());
        $cm->extend('push_token', fn($app) => new PushTokenChannel());
    }
}
