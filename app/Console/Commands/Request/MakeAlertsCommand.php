<?php

namespace App\Console\Commands\Request;

use App\Models\Classification;
use App\Models\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MakeAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:make-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make alerts of requests';

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
     */
    public function handle(): void
    {
        // First Get Alert Settings
        //$settings = ClassificationAlertSetting::select(['classification_id', DB::raw('COUNT(id) as count')])->groupBy(['classification_id'])->get();
        //$groups = $settings->pluck('classification_id')->toArray();
        $requestDates = Carbon::make("2021-01-01 00:00");
        // Second get the requests have classification & doesn't have the schedule no insert new rows
        $requests = Request::query()
            //->where('id',29743)
            //->whereIn('class_id_agent', $groups)
            ->whereIn('class_id_agent', [Classification::AGENT_UNABLE_TO_COMMUNICATE])
            ->whereDoesntHave('classificationAlertSchedules')
            ->where('is_freeze', !1)
            ->whereDate('req_date', '>=', $requestDates)
            ->get();
        if (!$requests->count()) {
            $this->line("There is no requests");
        }
        // Then create new records of classification schedule
        foreach ($requests as $request) {
            $s = $request->makeNewClassificationAlertSchedule();
            $this->line("Created new schedule  {$s->id}");
        }
    }
}
