<?php

namespace App\Console\Commands\Request;
use App\Models\Classification;
use App\Models\ClassificationAlertSchedule as Model;
use Illuminate\Console\Command;

class ReadPostponedCommunicationAlertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:postponed-communication-real-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the alerts schedules for postponed communication';

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
        $schedules = Model::with('request')
            ->whereHas('request',function ($q){
                $q->where('customer_want_to_contact_date','=',null)
                ->where('class_id_agent',Classification::TEST_CLASSIFICATION);
                // ->where('class_id_agent',Classification::POSTPONED_COMMUNICATION);
            })->get();
        // Then check from schedule & update | remove theme
        foreach ($schedules as $k => $schedule) {
            $this->line("run on request {$schedule->request_id}");
            $result = $schedule->sendAlertForPostponed();
        }
    }
}
