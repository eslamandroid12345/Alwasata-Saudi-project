<?php

namespace App\Console\Commands;

use App\Mail\SendNotificationReminder;
use App\Mail\SendResetPasswordLinkCustomer;
use App\Model\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RemindersNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Customer With Reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $day = Carbon::now('Asia/Riyadh');
        $today = date("Y-m-d",strtotime($day));
        $current =  date("H:i:s",strtotime($day));

        $reminders = Reminder::where('date','<=', $today)->where('time','<=', $current)->where(['status' => 'new'])->get();

        foreach ($reminders as $reminder) {
            // Insert In Notifications Table
            DB::table('notifications')->where('req_id',$reminder->id)->update(
                ['reminder_date' =>  date("Y-m-d H:i:s", strtotime($reminder->date.' '.$reminder->time)),]
            );
            
            if($reminder->customer != null){
            if($reminder->customer->email != null){
                Mail::to($reminder->customer->email)->send(new SendNotificationReminder($reminder->customer->email,$reminder->body));
            }}

            $reminder->update(['status' => 'end']);
        }
    }
}
