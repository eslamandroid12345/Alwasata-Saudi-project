<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\RemindersNotification::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        /*
        //$schedule->command('request:not-answer-to-unable')->everyMinute();
        //$schedule->command('request:jobs')->everyMinute();
        //$schedule->command('waiting:list')->everyMinute();
        //$schedule->command('remove:announcements')->hourly();
        //$schedule->call('App\Http\Controllers\AgentController@check_on_agent_ready_recive')->everyMinute(); // each  minute

        //$schedule->call('App\Http\Controllers\QualityController@delayedTask')->everyMinute(); // each  minute
        //
        //$schedule->call('App\Http\Controllers\QualityController@qualityReqsNotRecived')->everyMinute(); // each  minute
        //
        //$schedule->command('reminder:notify')->everyMinute();
        //
        //if (app()->environment('production')) {
        //    $schedule->exec('sudo chown -R www:www /var/www/alwsata.com.sa/storage')->daily();
        //}
        //else {
        //$schedule->command('request:welcome-messages')->everyMinute();
        //}
        //dd("teetet");
        //if (!app()->environment('production')) {
        //if ((bool) setting('schedule_unable_to_communicate')) {
        //    $schedule->command('request:make-alerts')->daily()->at('11:00');
        //    $schedule->command('request:read-alerts')->daily()->at('11:00');
        //}

        //$schedule->command('request:postponed-communication')->everyMinute();
        //$schedule->command('request:postponed-communication-real-alerts')->everyMinute();

        //$schedule->command('request:make-alerts')->everyMinute();
        //$schedule->command('request:read-alerts')->everyMinute();
        //$schedule->call('App\Http\Controllers\OtaredController@api')
        //    ->everyMinute(); // each  minute

        //$schedule->call(function () {
        //    Artisan::call('queue:work');
        //})->everyMinute();


        //  $schedule->call(function () {
        //          \Artisan::call('queue:work');
        //      })->everyMinute();

        $schedule->command('request:move-guests')->everyMinute(); // each  minute
        $schedule->command('request:quality-requests')->daily();


        $schedule->call(function () {
            \Artisan::call('queue:work');
        })->everyMinute();
        */



        $schedule->command('request:not-answer-to-unable')->hourly();

        $schedule->command('request:jobs')->hourly();

        $schedule->command('waiting:list')->hourly();

        $schedule->call('App\Http\Controllers\AgentController@check_on_agent_ready_recive')->hourly();

        $schedule->call('App\Http\Controllers\QualityController@delayedTask')->hourly();

        //$schedule->call('App\Http\Controllers\QualityController@qualityReqsNotRecived')->hourly();
        $schedule->call('App\Http\Controllers\QualityController@new_auto_transfer_quality_reqs')->daily();
        $schedule->call('App\Http\Controllers\QualityController@check_updates_on_quality_reqs')->hourly();

        $schedule->command('customer:duplicate')->hourly();


        $schedule->command('reminder:notify')->hourly();

        if (app()->environment('production')) {
            $schedule->exec('sudo chown -R www:www /var/www/alwsata.com.sa/storage')->daily();
        }
        $schedule->command('request:welcome-messages')->hourly();
        // if (!app()->environment('production')) {
        // if ((bool) setting('schedule_unable_to_communicate')) {
        //     $schedule->command('request:make-alerts')->daily()->at('11:00');
        //     $schedule->command('request:read-alerts')->daily()->at('11:00');
        // }
        // }
        /* تعطيل تعذر الاتصال*/
        if ((bool) setting('schedule_unable_to_communicate')) {
            $schedule->command('request:make-alerts')->hourly();
            $schedule->command('request:read-alerts')->hourly();
        }
        /* تعطيل اجل التواصل*/

        if ((bool) setting('postponed_communication')) {
            $schedule->command('request:postponed-communication')->everyFifteenMinutes();
            $schedule->command('request:postponed-communication-real-alerts')->everyFifteenMinutes();
        }

        $schedule->command('request:move-guests')->everyFifteenMinutes();
        /*
        // $schedule->command('request:quality-requests')->daily();

        // $schedule->command('request:postponed-communication')->hourly();
        // $schedule->command('request:postponed-communication-real-alerts')->hourly();
        */
        $schedule->command('request:move-guests')->hourly();
        //$schedule->command('request:quality-requests')->daily();

        $schedule->command('remove:announcements')->hourly();
        $schedule->call('App\Http\Controllers\OtaredController@api')->hourly();
        $schedule->call(function () {
            Artisan::call('queue:work');
        })->hourly();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
