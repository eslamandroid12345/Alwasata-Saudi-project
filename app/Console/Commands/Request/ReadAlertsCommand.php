<?php

namespace App\Console\Commands\Request;

use App\Models\Classification;
use App\Models\ClassificationAlertSchedule as Model;
use Illuminate\Console\Command;

class ReadAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:read-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the alerts schedules';

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
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        // First read all data
        // $schedules = Model::with('request')->has('request')->get();
        $schedules = Model::with('request')->whereHas('request',function ($q){
            $q->where('customer_want_to_contact_date','=',null)
            // ->where('class_id_agent',Classification::TEST_CLASSIFICATION);
            ->where('class_id_agent',Classification::AGENT_UNABLE_TO_COMMUNICATE);
        })->get();

        // Then check from schedule & update | remove theme
        foreach ($schedules as $k => $schedule) {
            ++$k;
            if (!$schedule->request) {
                $this->line("Delete Row ");
                $schedule->delete();
                continue;
            }
            $result = $schedule->sendAlert();
            $this->line("{$k} Schedule result: {$result} ".now()->toDateTimeString());
            if ($result == Model::NO_NEXT_ALERT_SETTING) {
                //$schedule->makeActionNoNextSetting();
                $this->line("Schedule Make No Next Setting: {$schedule->id}");
            }

        }
        $this->line("Schedule count: {$schedules->count()}");
    }
}
