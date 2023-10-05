<?php

namespace App\Console\Commands\Request;

use App\Models\QualityRequest;
use App\Models\Request;
use App\Models\RequestCondition;
use App\Models\RequestHistory;
use App\Models\RequestRecord;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use MyHelpers;
class QualityRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:quality-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read all quality requests';

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
        $conditions = RequestCondition::with(['statusConditions', 'userConditions', 'classificationConditions'])->get();
        echo "fetch \n";
        foreach ($conditions as $k => $condition) {
            $users = $condition->userConditions->pluck('user_id')->toArray();
            $statuses = $condition->statusConditions->pluck('status')->toArray();
            $classifications = $condition->classificationConditions->pluck('class_id')->toArray();
            $days = $condition->timeDays;
            // dd($condition);
            //dd($users,$statuses,$classifications);
            echo "condition $condition->id \n";
            if (!empty($users) || !empty($statuses) || !empty($classifications)) {
                echo "users " . sizeof($users)." \n";
                $reqs = Request::query()->whereDate('req_date', '>=', '2022-01-01')
                // $reqs = Request::query()->whereDate('req_date', '>=', '2021-01-01')
                ->whereDoesntHave('qualityRequests', function($q){
                    $q->where(function($q2){
                        $q2->whereHas('user', fn(Builder $b) => $b->where('status', 1));
                        $q2->where('quality_reqs.allow_recive', 1);
                        $q2->whereIn('quality_reqs.status', [0, 1, 2]);
                    });
                })
                ->inRandomOrder()
                ->limit(2500);
                !empty($users) && $reqs->whereIn('user_id', $users);
                !empty($statuses) && $reqs->whereIn('statusReq', $statuses);
                !empty($classifications) && $reqs->whereIn('class_id_agent', $classifications);
                //dd($reqs->count());
                $reqs = $reqs->get();
                echo "reqs " . sizeof($reqs)." \n";
                foreach ($reqs as $req) {
                    // By User
                    echo "req $req->id \n";
                    if (
                        !($record = $req->requestRecords()->where([
                            'user_id' => $req->user_id,
                            'colum'   => RequestRecord::AGENT_CLASS_RECORD,
                            'value'   => $req->class_id_agent,
                        ])->latest('updateValue_at')->first())
                        && !($record = $req->requestRecords()->where([
                            'colum' => RequestRecord::AGENT_CLASS_RECORD,
                            'value' => $req->class_id_agent,
                        ])->latest('updateValue_at')->first())
                    ) {
                        $time = $req->agent_date ?: ($req->updated_at ?: $req->created_at);
                        $time = $time ? Carbon::make($time) : now()->subDays($days + 1);
                    }
                    else {
                        $time = $record->updateValue_at ? Carbon::make($record->updateValue_at) : now()->subDays($days + 1);
                    }
                    // if ($time->copy()->addDays($days)->isPast()) {
                    if ($time->copy()->addSeconds($days)->isPast()) {
                        /*$received = $req->qualityRequests()
                            ->whereHas('user', fn(Builder $b) => $b->where('status', 1))
                            ->where('quality_reqs.allow_recive', 1)
                            ->whereIn('quality_reqs.status', [0, 1, 2])
                            ->where('quality_reqs.is_followed', 0);
                        $followed = $req->qualityRequests()
                            ->whereHas('user', fn(Builder $b) => $b->where('status', 1))
                            ->where('quality_reqs.allow_recive', 1)
                            ->whereIn('quality_reqs.status', [0, 1, 2])
                            ->where('quality_reqs.is_followed', 1);*/
                        //$completed = $req->qualityRequests()
                        //    ->where('quality_reqs.allow_recive', 1)
                        //    ->where('quality_reqs.status',3);
                        /* if (!$received->exists() && !$followed->exists()) {*/
                            $quality_id = getLastQualityOfDistribution();
                            // new : check if there is an old quality req with status 3 || 5 and
                            // quality user is active and agent change classification
                            $completed_or_arch = $req->qualityRequests()
                                ->whereHas('user', fn(Builder $b) => $b->where('status', 1))
                            //    ->where('quality_reqs.allow_recive', 1)
                            //    ->where('quality_reqs.req_class_id_agent','<>', 'requests.class_id_agent')
                               ->whereIn('quality_reqs.status',[3, 5])->latest()->first();
                               if($completed_or_arch && $req->class_id_agent == $completed_or_arch->req_class_id_agent)
                               {
                                   return ;
                               }
                            if($completed_or_arch)
                            {
                                if($completed_or_arch->user->allow_recived == 1)
                                {
                                    $quality_id = $completed_or_arch->user_id;
                                }
                                $model = QualityRequest::create([
                                    'allow_recive'              => 1,
                                    'user_id'                   => $quality_id,
                                    'req_id'                    => $req->id,
                                    'req_class_id_agent'        => $req->class_id_agent,
                                    'con_id'                    => $condition->id,
                                    'status'                    => 0,
                                    'is_followed'               => 0,
                                ]);
                                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($req->quality)) {
                                    MyHelpers::addDailyPerformanceRecord($quality_id);
                                }
                                MyHelpers::incrementDailyPerformanceColumn($quality_id, 'received_basket', $req->id);
                                echo "QualityRequest $model->id Re created \n";
                                if ($model) {
                                    $req->createHistory([
                                        'user_id'        => null,
                                        'recive_id'      => $quality_id,
                                        'title'          => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                        'content'        => RequestHistory::CONTENT_AUTO_MOVE_QUALITY,
                                        'class_id_agent' => $req->class_id_agent,
                                    ]);
                                    setLastQualityOfDistribution($quality_id);
                                }
                                return ;
                            }
                            $model = QualityRequest::create([
                                'allow_recive'              => 1,
                                'user_id'                   => $quality_id,
                                'req_id'                    => $req->id,
                                'req_class_id_agent'        => $req->class_id_agent,
                                'con_id'                    => $condition->id,
                                'status'                    => 0,
                                'is_followed'               => 0,
                            ]);
                            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($req->quality)) {
                                MyHelpers::addDailyPerformanceRecord($quality_id);
                            }
                            MyHelpers::incrementDailyPerformanceColumn($quality_id, 'received_basket', $req->id);
                            echo "QualityRequest $model->id just created \n";
                            if ($model) {
                                $req->createHistory([
                                    'user_id'        => null,
                                    'recive_id'      => $quality_id,
                                    'title'          => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                    'content'        => RequestHistory::CONTENT_AUTO_MOVE_QUALITY,
                                    'class_id_agent' => $req->class_id_agent,
                                ]);
                                setLastQualityOfDistribution($quality_id);
                            }
                        /* } */
                    }
                }
            }
        }
    }
}
