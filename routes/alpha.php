<?php
/**
 * @Author: Ahmed Fayez
 **/

Route::get('data_tables_language', function () {
    return json_encode(__('datatable'));
});

use App\Http\Controllers\V2\Admin\UserController;
use App\Models\Classification;
use App\Models\Customer;
use App\Models\Request;
use App\Models\RequestHistory;
use App\Models\RequestRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/datatableLanguage', fn() => response()->json([
    "sProcessing"   => "جاري التحميل...",
    "sLengthMenu"   => "أظهر مُدخلات _MENU_",
    "sZeroRecords"  => "لم يُعثر على أية سجلات",
    "sInfo"         => "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل",
    "sInfoEmpty"    => "يعرض 0 إلى 0 من أصل 0 سجلّ",
    "sInfoFiltered" => "(منتقاة من مجموع _MAX_ مُدخل)",
    "sInfoPostFix"  => "",
    "sSearch"       => "ابحث:",
    "sUrl"          => "",
    "oPaginate"     => [
        "sFirst"    => "الأول",
        "sPrevious" => "السابق",
        "sNext"     => "التالي",
        "sLast"     => "الأخير",
    ],
]))->name('datatableLanguage');
Route::get('/homePage', [UserController::class, 'homePage'])->name('homePage');

Route::group(['middleware' => 'auth', 'prefix' => 'V2', 'as' => 'V2.'], function () {
    foreach (glob(__DIR__.'/Alpha/*-routes.php') as $route) {
        require_once $route;
    }
});

Route::get('/dev', function (\Illuminate\Http\Request $request) {
    return ;
    
    //$f = Request::query()->doesntHave('customer');
    //dd($f->delete());
    $a = Request::query()
        //->where('user_id',311)
        ->where('statusReq',1)
        ->where(fn($q) => $q
            ->whereNull('comment')
            ->orWhereNull('class_id_agent'));
    dd($a->get()->toArray());
    return;
    $a = \App\Models\QualityRequest::query()->whereHas('request', fn(Builder $builder) => $builder->whereIn('class_id_quality', [51, 53, 54, 56, 59,]));
    //dd($a->update([
    //    'status'      => 5,
    //    'is_followed' => 0,
    //]));
    dd($a->count());
    return;
    $a = \App\Models\QualityRequest::query()
        ->whereHas('request', fn(Builder $b) => $b->whereNull('class_id_quality'))
        //->whereDate('created_at','>=','2022-02-27')
        ->whereDate('updated_at', '>=', '2022-02-27')
        ->where('quality_reqs.con_id', 12)
        ->where('quality_reqs.allow_recive', 1)
        ->whereIn('quality_reqs.status', [0, 1, 2])
        ->where('quality_reqs.is_followed', 1);
    //$a->update(['is_followed' => 0]);
    dd($a->count());
    return;
    $customer = Customer::where('mobile', '590470092')->first();
    $setting = \App\Models\WelcomeMessageSetting::first();

    $message = __('replace.dear', ['name' => $customer->name]).PHP_EOL.$setting->welcome_message;
    MyHelpers::sendSMS($customer->mobile, $message);

    return;
    $a = "ahmed";
    $b = " hmed ";
    $rows = collect();
    $i = 0;

    $same = collect();
    //dd(44);
    $customerColumns = [''];
    Customer::query()
        ->has('request')
        ->limit(10000)
        //->whereDate("created", '>=', '2021-01-01')
        //->where(fn(Builder $builder) => $builder->whereNotNull(''))
        ->chunk(1000, fn(Collection $customers) => $customers->each(function (Customer $customer) use (&$same, &$rows) {
            $models = Customer::query()->where('id', '!=', $customer->id);
            $models->where('name', 'LIKE', "%{$customer->name}%");

            $salary = $customer->salary;

            $dates = ['birth_date_higri', 'birth_date',];
            foreach ($dates as $k) {
                $date = $customer->{$k};
                //dd($date);
                if ($date && strlen($date) == '10') {
                    $value = substr($date, 0, 7);
                    $models->where($k, 'LIKE', "$value&");
                }
            }

            $range = 500;
            $salary > 0 && $models->whereBetween('salary', [$salary + $range, $salary - $range]);

            $wheres = [
                'work',
                'madany_id',
                'askary_id',
                'military_rank',
                'salary_id',
                'is_supported',
            ];
            foreach ($wheres as $k) {
                //$customer->{$k} &&
                $models->where($k, $customer->{$k});
            }
            //$model = $model->get();
            //if ($model->count() > 0) {
            if ($models->exists()) {
                //$same->put($customer->id, $model);
                $same->put($customer->id, $models->pluck('id')->toArray());
                //dd($customer->toArray(), $models->get()->toArray());
            }
            //if ($r) {
            //}
            //dd($rows->count(), $customer);
            //$i++;

            //if (!$rows->has($customer->id)) {
            //    $rows->put($customer->id, $customer);
            //}
        }));
    dd($same->count());
    dd($same->take(3)->toArray());
    $rows->chunk(5000)->each(function (Illuminate\Support\Collection $customers) use (&$i, $rows, &$same) {
        foreach ($customers as $customer) {
            $s = $customer->salary;
            $r = !0;
            $res = null;
            $s > 0 &&
            ($r = $r && ($res = Customer::query()->where(fn(Builder $builder) => $builder->whereBetween('salary', [$s + 200, $s - 200]))) && $res->exists() && ($res = $res->get()));
            if ($r) {
                $same->put($customer->id, $res);
            }
            //dd($rows->count(), $customer);
            //$i++;
        }
    });
    dd($same->take(3)->toArray());
    dd(strcmp($a, $b));
    $cols = ['birth_date_higri'];
    //dd(array_merge(['id'], $cols));
    $duplicates = Customer::query()
        //->whereNotNull(...$cols)
        ->whereRaw(DB::raw('`name` LIKE %name%'))
        ->get();
    dd($duplicates->take(2));
    $duplicates = Customer::query()
        ->whereNotNull(...$cols)
        ->select(array_merge(['id', 'name'], $cols))
        ->whereIn('id', fn($q) => $q->select('id')
            ->from('customers')
            ->groupBy($cols)
            ->havingRaw('COUNT(*) > 1'))
        ->get();
    dd($duplicates->take(2));

    $duplicates = Customer::query()
        ->select('birth_date_higri', DB::raw('COUNT(*) as `count`'))
        ->groupBy('birth_date_higri')
        ->havingRaw('COUNT(*) > 1')
        ->get();

    //$dates = Customer::query()->groupBy(['']);
    dd($duplicates->take(5)->toArray());
    //$a = Request::whereRowValues()
    $query = Request::chunkById(200, function ($requests) {
        foreach ($requests as $request) {
            dd($request);
        }
    }, 'id');
    return;

    $r = Request::whereHas('agentClassification', fn(Builder $builder) => $builder->where('type', 0))->whereIn('statusReq', [0, 1, 4, 31]);
    $t = $r->update([
        'statusReq'       => 2,
        'is_canceled'     => 0,
        'is_stared'       => 0,
        'is_followed'     => 0,
        'add_to_archive'  => DB::raw('`updated_at`'),
        'add_to_stared'   => null,
        'add_to_followed' => null,
        //'updated_at'      => now('Asia/Riyadh'),
    ]);
    d($t);
    d($r->count());
    //return;
    //$o = Request::where('id',283);
    //$o->update([
    //    'created_at' => DB::raw('`updated_at`'),
    //]);
    //dd($o->get()->toArray()[0]);
    //dd($r->first()->id);
    //\Illuminate\Support\Facades\Artisan::call('request:quality-requests');

    //\App\Helpers\MyHelpers::sendSMS("590470092", "رمز التحقق: ".rand(1234, 9999));
    //Artisan::call('request:quality-requests');
    return;
    {
        {
            // Delete Null Values
            //$delete = RequestRecord::query()
            //    ->where('colum', 'class_agent')
            //    ->whereNull('value');
            //$delete->delete();
            //dd($delete->count());
        }
        //dd(123);
        // Todo: Fix req_records 2022-02-13
        //$record = RequestRecord::find(1936499);
        //$r1 = 0;
        $r2 = 500000;
        //$r2 = 1000000;
        //$r2 = 1500000;
        //$r2 = 2000000;
        $records = RequestRecord::query()
            //->limit(100000)
            ->where('colum', 'class_agent')
            //->where('id', '>=', $r1)
            //->where('id', '<=', $r2)
            //->has('agentClassification');
            ->doesntHave('agentClassification');
        //dd($records->limit(20)->get()->toArray());
        //dd($records->count());
        //dd($records->get()->pluck('value')->toArray());
        //dd($records->limit(20)->count());
        foreach ($records->get() as $value) {
            break;
            if (is_numeric($value->value)) {
                $value->delete();
            }
            else {
                $a = Classification::query()->where('value', $value->value)->first();
                if ($a) {
                    $value->update(['value' => $a->id]);
                }
                else {
                    //if (in_array($value->value, ['تقييم تساهيل'])) {
                    //    continue;
                    //}
                    //dd('No Classification', $value);
                }
            }
        }
        //dd($value);
        dd("OK");
        //dd($records->get()->toArray());
        //dd($record->agentClassification);
        //$reqcords = RequestRecord::query()->whereDoesntHave('request');
        //$actions->delete();
        //d($actions->get()->toArray());
    }
    {
        // Todo: Fix request_need_actions
        //$actions = RequestNeedAction::query()->whereDoesntHave('request');
        //$actions->delete();
        //d($actions->get()->toArray());
    }
    //560405979
    return;
    //$g = \App\GuestCustomer::withTrashed()->whereHas('customer', fn(Builder $builder) => $builder->whereDoesntHave('request'))->limit(500);
    //dd($g->count());
    //$a = \App\GuestCustomer::withTrashed()->where('mobile','560405979')->first();
    //$a = \App\GuestCustomer::withTrashed()->whereDoesntHave('customer')->limit(1);
    //dd($a->count());

    //$customers = Customer::whereDoesntHave('request')->has('guestCustomers');
    //dd($customers->get()->toArray());
    //return
    //$requests = Request::where('is_freeze',1)->get();
    //foreach ($requests as $r){
    //    dd($r->requestHistories()->latest()->get());
    //}
    //d($requests);

    {
        //$title = RequestHistory::where('content','LIKE','%'.RequestHistory::CONTENT_FROZEN_NEW_BACK.'%');
        //$r = $title->update([
        //    'title' => RequestHistory::MOVE_FROM_FREEZE
        //]);
        //dd($r);
        //$title = RequestHistory::where('content','LIKE','%'.RequestHistory::CONTENT_AGENT_TAKE_FROZEN_REQUEST.'%');
        //$r = $title->update([
        //    'title' => RequestHistory::MOVE_FROM_FREEZE
        //]);
        //dd($r);

        //$title = RequestHistory::where('content','LIKE','%'.RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO.'%');
        //$r = $title->update([
        //    'title' => RequestHistory::MOVE_TO_FREEZE
        //]);
        //dd($r);
    }
    //$title = RequestHistory::where('content', 'LIKE', '%'.RequestJob::CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE.'%');
    //$content = "";
    //dd($title->get()->toArray());
    //$records = RequestHistory::where('');
    //$a = \App\Models\WebNotification::whereDoesntHave('requests');
    //dd($a->count());
    return;
    /*
      $a = \App\Models\QualityRequest::doesntHave('request');
      //$a = \App\Models\QualityRequest::whereNull('req_id');
      $ids = $a->pluck('id')->toArray();
      dd($ids);
      dd($a->delete());
      dd($a->count());
      */
    //return;
    {
        $fromDate = Carbon::make('2021-11-01');
        /** @var Builder $a */
        $a = [];
        //$a->doesntHave()
        $requests = Request::
        whereHas('requestRecords', fn(Builder $builder) => $builder->where('colum', 'comment')->whereDate('updateValue_at', '<', $fromDate))
            ->doesntHave('qualityRequest')
            ->where('class_id_agent', 1)
            ->with([
                'requestRecords' => fn(HasMany $b) => $b->latest('updateValue_at')->where('colum', 'comment')->whereDate('updateValue_at', '<', $fromDate),
            ])
            ->get()
            ->map(function ($e) {
                $e->edit_time = $e->requestRecords->first()->updateValue_at;
                return $e;
            })
            ->sortBy('edit_time');
        //dd($requests->count());
        //dd($requests->first()->edit_time, $requests->last()->edit_time);
        $set = [];
        $u = [
            //237 => [
            //    'count' => 5,
            //],
            59  => [
                'count' => 1000,
            ],
            278 => [
                'count' => 1000,
            ],
            361 => [
                'count' => 500,
            ],
            238 => [
                'count' => 500,
            ],
        ];
        $disk = \Illuminate\Support\Facades\Storage::disk();
        //dd($requests);
        /**
         * @var int $user
         * @var array $item
         */
        foreach ($u as $id => $item) {
            $insert = [];
            $path = "users/{$id}.json";
            if (!$disk->exists($path)) {
                $disk->put($path, json_encode([]));
            }
            $save = json_decode($disk->get($path), !0);
            asort($save);
            $save = array_values($save);
            if (count($save) >= $item['count']) {
                continue;
            }
            //dd($save);
            //$user = User::find($id);
            //dd($user);
            /** @var Request $request */
            foreach ($requests as $k => $request) {
                $data = [
                    'user_id'      => $id,
                    'allow_recive' => 1,
                    'is_followed'  => 0,
                    'status'       => 0,
                    //'created_at'   => Carbon::make("2022-01-13")->setHours(19),
                    //'updated_at'   => Carbon::make("2022-01-13")->setHours(19),
                ];
                if ($request->qualityRequest()->exists()) {
                    continue;
                }
                if (in_array($request->id, $save)) {
                    continue;
                }
                $insert[] = $request->id;
                $a = $request->qualityRequest()->create($data);
                $m = rand(0, 59);
                $s = rand(0, 59);
                $a->created_at = Carbon::make("2022-01-13")->setHours(19)->setMinutes($m)->setSeconds($s);
                $a->updated_at = Carbon::make("2022-01-13")->setHours(19)->setMinutes($m)->setSeconds($s);
                $a->save();
                //dd($a);
                $requests->forget($k);
                if (count($insert) >= $item['count']) {
                    break;
                }
            }
            asort($insert);
            $insert = array_values($insert);
            //dd($insert);
            $disk->put($path, json_encode($insert));
            //dd($insert);
        }

        //$r = $requests->first();
        //dd($r);
        //$a = $requests->toArray();
        //dd([$a[0]['edit_time'], $a[1]['edit_time']]);
        //dd([$requests->first(), $requests->first()->requestRecords->toArray()]);

    }
    //\Illuminate\Support\Facades\Artisan::call('request:quality-requests');
    return;
    \Illuminate\Support\Facades\Artisan::call('request:not-answer-to-unable');
    return;
    $f = 'إضافة الطلب إلى الجودة';
    $requests = Request::whereHas('requestHistories', fn(Builder $builder) => $builder->where('title', $f)->whereDate('history_date', '>=', "2021-12-31")->whereDate('history_date', '<=', "2022-01-02"));
    dd($requests->get());
    return;
    $requests = Request::where("class_id_agent", "33")->whereIn("statusReq", [0, 1]);
    foreach ($requests->get() as $item) {
        if (!$item->add_to_archive) {
            dd($item);
        }
    }
    $requests->update([
        "statusReq" => 2,
    ]);
    dd($requests->count());
    //$a =  Storage::disk()->put("requests.json",$requests->get()->toJson(JSON_UNESCAPED_UNICODE));
    //dd($a,$requests->count());
    //dd( (new Request())->getForeignKey());
    return;
    {
        $requests = Request::freezeOnly()->get();
        //dd($requests->count());
        /** @var Request $r */
        $a = [];
        $w = [];
        $i = 0;
        $res = [];
        $status = [];
        foreach ($requests as $r) {
            if (in_array($r->id, [78407, 78121])) {
                continue;
            }
            $status[] = $r->statusReq;
            //if ($r->requestHistories()->where('content', RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO)->whereDate('history_date','<','2022-01-01')->exists()) {
            //    dd($r->id);
            //    continue;
            //}
            $i++;
            $h = $r->requestHistories()->where('title', RequestHistory::TITLE_MOVE_REQUEST)->where('content', RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO)->latest()->first();
            $agent = $h->user_id;
            $agentDate = $r->requestHistories()->where('recive_id', $agent)->latest('history_date')->first();
            if (!$agentDate) {
                $w[] = $r->id;
                $checkAgentDate = $r->requestHistories()->whereIn('title', [
                    'تم إنشاء الطلب',
                    'إضافة الطلب من قبل مدير النظام',
                ])->oldest('history_date')->exists();

                if (!$checkAgentDate) {
                    dd([1, $r]);
                }

                $agentDate = $r->req_date;
            }
            //dd($agentDate->toArray());
            //dd($h);
            if (!$h) {
                dd($r);
            }
            $archiveDate = $r->requestRecords()->where('colum', RequestRecord::AGENT_CLASS_RECORD)->latest('updateValue_at')->first();
            if (!$archiveDate) {
                dd([$archiveDate, $r]);
            }
            $comment = $r->requestRecords()->where('colum', 'comment')->latest('updateValue_at')->first();
            if (!$comment) {
                dd($r);
            }
            //dd($comment->toArray());
            //add_to_archive
            $comment = $comment->value;
            $agentDate = Carbon::make($agentDate);
            $archiveDate = $r->statusReq == 2 ? Carbon::make($archiveDate->updateValue_at) : null;
            //if ($h->content == 'قائمة الانتظار'){
            //    $a = $r->requestHistories()->get();
            //    dd($a);
            //    dd($h,$r);
            //}
            $a[] = $h->content;
            //dd($h);
            $r->fill([
                'user_id'        => $agent,
                'add_to_archive' => $archiveDate,
                'agent_date'     => $agentDate,
                'class_id_agent' => Classification::AGENT_UNABLE_TO_COMMUNICATE,
                'comment'        => $comment,
                'is_freeze'      => 0,
            ]);
            $res[] = $r->toArray();
            {
                // Delete
                //$d1 = $r->requestHistories()->where('content',RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO)->get();
                //if($d1->count() > 1 ){
                //    dd($d1);
                //}
                //dd($d1->count());
            }
            //if($r->id == 65335){
            $r->save();
            //}
            //dd($r);
        }
        $a = array_unique($a);
        $status = array_unique($status);
        dd($status);
        dd($w);
        dd($a);

    }
    return;
    ///** @var Request $r */
    //$r = Request::find(1);
    //$r->class_id_agent = 72;
    //$r->save();
    //$r->makeNewClassificationAlertSchedule();
    //dd($r);
    //\App\Models\RequestJob::runJobs();
    //$guestCustomer = \App\GuestCustomer::find(27076);
    ////$guestCustomer->update(['created_at' => null]);
    //$guestCustomer->created_at = now();
    ////$gu
    //dd($guestCustomer);
    //return;
    //$job = \App\Models\RequestJob::create([
    //    'request_id' => 1,
    //    'action'     => 2,
    //    //'data' => [
    //    //    'action' => \App\Models\RequestJob::AUTO_MOVE_FREEZE
    //    //]
    //]);
    //$a = \App\Models\RequestJob::latest()->first();
    //dd($a->data);
    return;
    $ig = trim(file_get_contents(__DIR__.'/a.txt'));
    $ig = str_ireplace('\\r', PHP_EOL, $ig);
    $ig = explode(PHP_EOL, $ig);
    $ig = array_filter($ig);
    $ig = array_unique($ig);
    //dd($ig);
    $date = today()->year(2020)->months(1)->days(1);
    //$e = \App\Models\Customer::where(fn(\Illuminate\Database\Eloquent\Builder $builder) => $builder->whereDate('created_at','>=',$date)->orWhereNull('created_at'))->whereNotNull('mobile')->groupBy('mobile')->latest('id')->pluck('mobile')->toArray();
    //$r = \App\Models\Request::whereDate('req_date','>=',$date)->latest()->get();
    //dd($r->count());
    $e = Customer::whereHas('request', fn(Builder $builder) => $builder->whereDate('req_date', '>=', $date))->whereNotNull('mobile')->where('app_downloaded', 0)->groupBy('mobile')->whereNotIn('mobile', $ig)->latest('id')->pluck('mobile')->toArray();
    echo count($e).'<BR>';
    foreach ($e as $v) {
        echo "{$v}<br>";
    }
    return;
    //$schedule = \App\Models\ClassificationAlertSchedule::first();
    //dd($schedule);
    // Ahmed Fayez. Customer ID: 75465.
    // Ahmed Fayez. Request ID: 1.
    //$a = \App\Models\Request::find(1);
    //$a->createHistory();
    d($a->customer);
    //$a = \App\Models\Classification::all()->toArray();
    //dd($a);
    //$customer = \App\Models\Customer::find(34);
    ////zz($customer);
    //$channel = $customer->email;
    //$type = 'mail';
    //$schedule = \App\Models\ClassificationAlertSchedule::first();
    //$schedule->sendAlert();
    //d([$schedule, $channel, $type]);
    //$notify = new ClassificationAlertNotification($schedule, $channel, $type);
    //$customer->notify($notify);
    //$schedule = \App\Models\ClassificationAlertSchedule::first();
    //zz($schedule->sendAlert());
    //setLastAgentOfDistribution(35);
    //while( ($id = getLastAgentOfDistribution()) != 34 ){
    //    echo $id."<BR>";
    //    setLastAgentOfDistribution($id);
    //}
    //
    //dd($id);
    return;
    /**
     * @var User $user
     * @var \Carbon\Carbon $start
     * @var \Carbon\Carbon $end
     */
    $user = User::query()->find($request->get('u'));
    if (!$user) {
        return;
    }
    $from = $request->get('f', now()->subWeek());
    $from = Carbon::parse($from);

    $to = $request->get('t', now());
    $to = Carbon::parse($to);

    $start = $from->hours(0)->minutes(0)->seconds(0);
    $end = $to->hours(23)->minutes(59)->seconds(59);
    // dd($start->toDateTimeString(), $end->toDateTimeString());

    // $userReqs = $user->requests()->where('is_followed', 0)->where('is_stared', 0)->whereBetween('agent_date', [$start, $end]);
    $r = RequestHistory::transferred()->byReceived($user)->groupBy('req_id')->betweenDate($start, $end)->with('request');
    $found = [];
    $ignored = [];
    $transferred = $r->get();
    /** @var RequestHistory $history */
    foreach ($transferred as $history) {
        /** @var Request $r */
        $r = $history->request;
        if ($r->user_id == $user->id) {
            array_push($found, $history->toArray());
        }
        else {
            if (($exists = $r->requestHistories()->where('id', '!=', $history->id)->forPerformance()->exists())) {
                array_push($ignored, $history->toArray());
            }
            else {
                array_push($found, $history->toArray());
            }
        }
    }
    dd([
        'user'        => $user->name,
        'transferred' => $transferred->count(),
        'form'        => $start->toDateTimeString(),
        'to'          => $end->toDateTimeString(),
        'found'       => count($found),
        'ignored'     => count($ignored),
    ], [
        'found'   => $found,
        'ignored' => $ignored,
    ]);
    #;
    // $from = RequestHistory::ofUser($user)->betweenDate($start,$end)->groupBy(['req_id']);
    // $r = RequestHistory::byReceived($user)->betweenDate($start,$end)->groupBy(['req_id']);
    // dd($r->get());
    // dd($r->toSql());
    // StatusReq = 0,1,3
    dd($r->get()->toArray());
    // d($r->pluck('content','id')->toArray());
    // $history = RequestHistory::query()->betweenDate($start, $end)->where(function(Builder $builder) use ($user){
    //     return $builder->where('user_id', $user->id)->orWhere('recive_id', $user->id);
    // });
    // dd($userReqs->pluck('id')->toArray());
    // dd($start->toDateTimeString(), $end->toDateTimeString());
    // dd($user->requests()->first());
    // d($user->name);
    // d($request->agent_date);
});
