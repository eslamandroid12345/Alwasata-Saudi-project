<?php

namespace App\Console\Commands;

use App\Announcement;
use App\AnnounceSeen;
use App\AnnounceUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveEndedAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:announcements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        foreach (Announcement::with("users")->where("end_at","<",$today)->get() as $item) {
           if ($item->users){
               foreach ($item->users as $user) {
                   AnnounceSeen::firstOrCreate([
                       'user_id' =>  $user->user_id,
                       'announce_id'    => $item->id
                   ]);
               }
           }

           if ($item->roles){
               foreach ($item->roles as $role) {
                   foreach (User::where('role',$role->role)->get() as $user) {
                       AnnounceSeen::firstOrCreate([
                           'user_id' =>  $user->id,
                           'announce_id'    => $item->id
                       ]);
                   }
               }
          }
        }
    }
}
