<?php

namespace App\Console\Commands\Request;

use App\Models\Classification;
use App\Models\Request;
use App\Models\RequestRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NotAnswerToUnableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:not-answer-to-unable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run not answer to unable command';

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
        if (!setting('schedule_not_answer_to_unable')) {
            return;
        }
        $disk = Storage::disk('log');
        $filename = now()->format("Y-m-d").".log";
        $folder = 'schedule_not_answer_to_unable';
        $dir = "{$folder}/{$filename}";
        $errorDir = "{$folder}/error-{$filename}";

        /** @var Classification $notAnswer */
        $limit = null;
        $requests = Request::where('class_id_agent', Classification::AGENT_NOT_ANSWER)->limit($limit)->get();
        $notAnswer = Classification::findOrFail(Classification::AGENT_NOT_ANSWER);
        $days = (int) setting('not_answer_to_unable_days');
        $disk->append($dir, "Requests Count: {$requests->count()}");
        foreach ($requests as $request) {
            try {
                $history = $request->requestRecords()
                    ->where('colum', RequestRecord::AGENT_CLASS_RECORD, $notAnswer->value)
                    ->latest('updateValue_at')
                    ->first();

                if (!$history) {
                    $disk->append($errorDir, "!History: $request->id");
                    continue;
                }
                $def = Carbon::make("2022-01-11")->setHours(23)->setMinutes(59)->setSeconds(59);
                $time = Carbon::make($history->updateValue_at);
                $now = now();
                if ($time < $def) {
                    $time = $def->copy();
                }
                //$disk->append($dir, "Request: $request->id. [Updated]");
                if ($time->copy()->addDays($days) < $now) {
                    $request->update([
                        'class_id_agent' => Classification::AGENT_UNABLE_TO_COMMUNICATE,
                    ]);
                    $l = now()->toDateTimeString();
                    $disk->append($dir, $l.PHP_EOL."Request: $request->id. Data: [".json_encode([
                            'time' => $time,
                            'days' => $days,
                        ], JSON_UNESCAPED_UNICODE)."]");
                    $request->createRecordHistory(RequestRecord::AGENT_CLASS_RECORD, $notAnswer->value, ['comment' => __("messages.notAnswerAction")]);
                }
            }
            catch (\Exception $exception) {
                $disk->append($errorDir, "Request: $request->id");
                $disk->append($errorDir, $exception);
            }
        }
    }
}
