<?php

namespace App\Console\Commands\Request;

use App\Models\Classification;
use App\Models\Request;
use Illuminate\Console\Command;

class PostponedCommunicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:postponed-communication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Postponed Communication Alerts';

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
        $requests = Request::query()
            ->whereIn('class_id_agent', [Classification::TEST_CLASSIFICATION])
            // ->whereIn('class_id_agent', [Classification::POSTPONED_COMMUNICATION])
            ->whereDoesntHave('classificationAlertSchedules')
            ->where('is_freeze', !1)
            ->where('customer_want_to_contact_date','=',null)
            ->get();

        if (!$requests->count()) {
            $this->line("There is no requests");
        }
        foreach ($requests as $request) {
                $s = $request->makeNewClassificationAlertScheduleForPostponed();
                $this->line("Created new schedule {$s->id}");
        }
    }
}
