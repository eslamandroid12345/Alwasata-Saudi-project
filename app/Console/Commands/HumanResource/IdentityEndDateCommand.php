<?php

namespace App\Console\Commands\HumanResource;

use App\Employee;
use App\notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IdentityEndDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'identity:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identity End Date Alert';

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
        $employees = Employee::where('residence_end_date','<=',date("Y-m-d",strtotime( Carbon::now('Asia/Riyadh')->subMonth())))
            ->orWhere('residence_end_date','<=',date("Y-m-d",strtotime( Carbon::now('Asia/Riyadh')->subWeek())))
            ->orWhere('residence_end_date','<=',date("Y-m-d",strtotime( Carbon::now('Asia/Riyadh')->subDay())))->get();

        $users = User::whereIn('role',[7,12])->get();
        foreach ($employees as $employee) {
            foreach ($users as $user) {

                Notification::firstOrCreate([
                    'recived_id' => $user->id,
                    'receiver_type' => 'hr',
                    'request_type' => $employee->residence_end_date,
                ],[
                    'value'         => 'تنبيه بإقتراب موعد نهاية هوية الموظف '.$employee->name.' تنتهى الهوية فى '.$employee->residence_end_date,
                    'recived_id'    => $user->id,
                    'receiver_type' => 'hr',
                    'request_type'  => $employee->residence_end_date,
                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                    'is_done'       => 0,
                ]);
            }
        }
    }
}
