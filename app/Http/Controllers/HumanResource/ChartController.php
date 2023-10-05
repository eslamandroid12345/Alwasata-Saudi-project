<?php

namespace App\Http\Controllers\HumanResource;
use App\User;
use Carbon\Carbon;

use App\Announcement;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ConsoleTVs\Charts\Builder\Chart;
use App\Charts\DailyPerformanceChart;
use Yajra\DataTables\Facades\DataTables;


class ChartController extends Controller
{
    // public function __construct()
    // {
    //     if (config('app.debug')) {
    //         view()->share([
    //             'announces'                 => Announcement::where('status', 1)->get(),
    //             'all_reqs_count'            => null,
    //             'agent_received_reqs_count' => null,
    //             'follow_reqs_count'         => null,
    //             'star_reqs_count'           => null,
    //             'arch_reqs_count'           => null,
    //             'pending_request_count'     => null,
    //             'need_action_request_count' => null,
    //             'sent_task_count'           => null,
    //             'received_task_count'       => null,
    //             'completed_task_count'      => null,
    //             'calculator_suggests'       => null,
    //             'onlineUsers'               => [],
    //             'notifyWithoutReminders'    => collect(),
    //             'notifyWithOnlyReminders'   => collect(),
    //             'notifyWithHelpdesk'        => collect(),
    //             'unread_conversions'        => null,
    //             'unread_messages'           => collect(),
    //         ]);
    //     }
    //     else {
    //         \Illuminate\Support\Facades\View::composers([
    //             'App\Composers\HomeComposer'             => ['layouts.content'],
    //             'App\Composers\ActivityComposer'         => ['layouts.content'],
    //             'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
    //         ]);
    //     }
    //     view()->share([
    //         'announces'                 => collect(),
    //         'all_reqs_count'            => null,
    //         'agent_received_reqs_count' => null,
    //         'follow_reqs_count'         => null,
    //         'star_reqs_count'           => null,
    //         'arch_reqs_count'           => null,
    //         'pending_request_count'     => null,
    //         'need_action_request_count' => null,
    //         'pur_reqs_count'            => 0,
    //         'mor_reqs_count'            => 0,
    //         'rej_reqs_count'            => 0,
    //         'cancel_reqs_count'         => 0,
    //         'agent_com_reqs_count'            => 0,
    //         'agent_arch_reqs_count'            => 0,
    //         'daily_reqs_count'            => 0,
    //         'sent_task_count'           => null,
    //         'received_task_count'       => null,
    //         'completed_task_count'      => null,
    //         'calculator_suggests'       => null,
    //         'onlineUsers'               => [],
    //         'notifyWithoutReminders'    => collect(),
    //         'notifyWithOnlyReminders'   => collect(),
    //         'notifyWithHelpdesk'        => collect(),
    //         'unread_conversions'        => null,
    //         'unread_messages'           => collect(),
    //     ]);
    // }

    public function dailyPrefromence() {//dailyPrefromenceChartR

        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);


        //------------ handle chart details --------------

        $users_of_charts=$this->usersOfCharts();
        $horizontal_of_charts=$users_of_charts->pluck('name')->toArray();

        //  $chart = Charts::database($verticle_of_charts, 'bar', 'highcharts')
            //     ->title('Bar Chart')
            //     ->elementLabel('Total Request of agents')
            //     ->dimensions(700, 500)
            //     ->responsive(false)
            //     ->labels($horizontal_of_charts) // X $user->name
            //     ->values($verticle_of_charts); // Y $user->sum_of_total_requests


        $chart2=new DailyPerformanceChart;
        $api=url('/HumanResource/ajax-chart-line'); //ajaxdailyPrefromenceChartR fun
        $chart2->labels($horizontal_of_charts)->load($api);

        return view('HumanResource.Reports.daily-performance-for-agent',[
            "users" => $users,
            "managers" => $managers,
            // "chart" => $chart,
            "chart2" => $chart2,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)
        ]);
    }

    public function usersOfCharts($role=0)
    {

        (isset(request()->startdate))? $startdate=request()->startdate: $startdate=Carbon::now()->format('Y-m-d');
        (isset(request()->enddate))?$enddate=request()->enddate:$enddate=Carbon::now()->format('Y-m-d');
       
        //  return $users_of_charts = User::where('role', 0)
         return $users_of_charts = User::where('role', $role)
         ->withCount(['performances AS total_recived_request' => function ($query) use($startdate,$enddate) {
             $query->select(DB::raw("SUM(received_basket) + SUM(move_request_to) as total_recived_request"))
             ->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS received_basket' => function ($query)use($startdate,$enddate) {
             $query->select(DB::raw("SUM(received_basket) as received_basket"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS star_basket' => function ($query)use($startdate,$enddate) {
             $query->select(DB::raw("SUM(star_basket) as star_basket"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS followed_basket' => function ($query)use($startdate,$enddate) {
             $query->select(DB::raw("SUM(followed_basket) as followed_basket"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS archived_basket' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(archived_basket) as archived_basket"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS sent_basket' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(sent_basket) as sent_basket"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS completed_request' => function ($query)use($startdate,$enddate) {
             $query->select(DB::raw("SUM(completed_request) as completed_request"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS updated_request' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(updated_request) as updated_request"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS opened_request' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(opened_request) as opened_request"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS received_task' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(received_task) as received_task"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS replayed_task' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(replayed_task) as replayed_task"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS missed_reminders' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(missed_reminders) as missed_reminders"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS move_request_from' => function ($query)use($startdate,$enddate) {
             $query->select(DB::raw("SUM(move_request_from) as move_request_from"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->withCount(['performances AS move_request_to' => function ($query) use($startdate,$enddate){
             $query->select(DB::raw("SUM(move_request_to) as move_request_to"))->whereBetween('today_date', [$startdate, $enddate]);
         }])
         ->get();
    }

    public function ajaxdailyPrefromence()//ajaxdailyPrefromenceChartR
    {
        $result=null;
        $title=null;

        if(request()->type_of_requests == 0){
            $result=User::where('role', 0)
                         ->withCount(['performances AS total_recived_request' => function ($query) {
                            $query->select(DB::raw("SUM(received_basket) + SUM(move_request_to) as total_recived_request"));
                         }])->pluck('total_recived_request')->toArray();
            $title='طلبات مستلمة';
        }elseif(request()->type_of_requests == 1){
            $result=User::where('role', 0)
                         ->withCount(['performances AS received_basket' => function ($query) {
                            $query->select(DB::raw("SUM(received_basket) as received_basket"));
                         }])->pluck('received_basket');
            $title='طلبات جديدة';

        }elseif(request()->type_of_requests == 2){
            $result=User::where('role', 0)
                        ->withCount(['performances AS star_basket' => function ($query) {
                            $query->select(DB::raw("SUM(star_basket) as star_basket"));
                        }])->pluck('star_basket');
            $title='طلبات مميزة';
        }elseif(request()->type_of_requests == 3){
            $result=User::where('role', 0)
                        ->withCount(['performances AS followed_basket' => function ($query) {
                            $query->select(DB::raw("SUM(followed_basket) as followed_basket"));
                        }])->pluck('followed_basket');
            $title='طلبات متابعة';
        }elseif(request()->type_of_requests == 4){
            $result=User::where('role', 0)
                        ->withCount(['performances AS archived_basket' => function ($query) {
                            $query->select(DB::raw("SUM(archived_basket) as archived_basket"));
                        }])->pluck('archived_basket');
            $title='طلبات مؤرشفة';
        }elseif(request()->type_of_requests == 5){
            $result=User::where('role', 0)
                        ->withCount(['performances AS sent_basket' => function ($query) {
                            $query->select(DB::raw("SUM(sent_basket) as sent_basket"));
                        }])->pluck('sent_basket');
            $title='طلبات مرفوعه';
        }elseif(request()->type_of_requests == 6){
            $result=User::where('role', 0)
                        ->withCount(['performances AS completed_request' => function ($query) {
                            $query->select(DB::raw("SUM(completed_request) as completed_request"));
                        }])->pluck('completed_request');
            $title='طلبات مفرغة';
        }elseif(request()->type_of_requests == 7){
            $result=User::where('role', 0)
                        ->withCount(['performances AS updated_request' => function ($query) {
                            $query->select(DB::raw("SUM(updated_request) as updated_request"));
                        }])->pluck('updated_request');
            $title='طلبات محدث عليها';
        }elseif(request()->type_of_requests == 8){
            $result=User::where('role', 0)
                        ->withCount(['performances AS opened_request' => function ($query) {
                            $query->select(DB::raw("SUM(opened_request) as opened_request"));
                        }])->pluck('opened_request');
            $title='طلبات تم فتحها';
        }elseif(request()->type_of_requests == 9){
            $result=User::where('role', 0)
                        ->withCount(['performances AS received_task' => function ($query) {
                            $query->select(DB::raw("SUM(received_task) as received_task"));
                        }])->pluck('received_task');
            $title='مهام مستلمة';
        }elseif(request()->type_of_requests == 10){
            $result=User::where('role', 0)
                        ->withCount(['performances AS replayed_task' => function ($query) {
                            $query->select(DB::raw("SUM(replayed_task) as replayed_task"));
                        }])->pluck('replayed_task');
            $title='مهام تم الرد عليها';
        }elseif(request()->type_of_requests == 11){
            $result=User::where('role', 0)
                        ->withCount(['performances AS move_request_from' => function ($query) {
                            $query->select(DB::raw("SUM(move_request_from) as move_request_from"));
                        }])->pluck('move_request_from');
            $title='طلبات محولة منه';
        }elseif(request()->type_of_requests == 12){
            $result=User::where('role', 0)
                        ->withCount(['performances AS move_request_to' => function ($query) {
                            $query->select(DB::raw("SUM(move_request_to) as move_request_to"));
                        }])->pluck('move_request_to');
            $title='طلبات محوله اليه';
        }elseif(request()->type_of_requests == 99){
            $users_of_charts=$this->usersOfCharts();
            foreach ($users_of_charts as $key => $user) {
                $users_of_charts[$key]['sum_of_total_requests'] = $user->total_recived_request + $user->received_basket + $user->star_basket + $user->followed_basket +
                                                                $user->archived_basket + $user->sent_basket + $user->completed_request + $user->updated_request +
                                                                $user->opened_request + $user->received_task + $user->replayed_task + $user->missed_reminders +
                                                                $user->move_request_from + $user->move_request_to ;
            }
            $result=$users_of_charts->pluck('sum_of_total_requests')->toArray();
            $title='جميع الطلبات';
        }

        $chart2=new DailyPerformanceChart();
        $chart2->dataset($title, 'bar',$result)->options([
            'fill' => 'true',
            'borderColor' => '#51C1C0',
            'backgroundColor'=>'0f5b94',
            'color' => 'red',
            'responsive'=>true,
            'maintainAspectRatio'=>true,
            'barThickness'=>'50px',
        ]);

        return  json_decode($chart2->api());
    }

    public function report(Request $request)
    {

        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if (!$request->has("role")){
            $request->merge([
                "role"   => 0
            ]);
        }
        if (request('startdate') < "2022-04-02"){
            $diff = strtotime("2022-04-02")-strtotime(request('startdate'));

            $request->merge([
                "startdate" => "2022-04-02",
                'diff'  => $diff/(60*60*24)
            ]);

        }

        $users = User::where('role',0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->when($request->adviser_id != 0 && $request->adviser_id != null, function ($q, $v) use ($request) {
                $q->whereIn('id', [$request->adviser_id]);
            })->when($request->manager_id != 0 && $request->manager_id != null, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', [$request->manager_id]);
            })->withCount(['servays AS total_servays' => function ($query) {
                $query->whereBetween('created_at', [request('startdate'), request('enddate')]);
            }])->withCount(['performances AS total_recived_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(total_recived_request) as total_recived_request"));
            }]) ->withCount(['performances AS received_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(received_basket) as received_basket"));
            }])->withCount(['performances AS star_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(star_basket) as star_basket"));
            }])->withCount(['performances AS followed_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(followed_basket) as followed_basket"));
            }])->withCount(['performances AS archived_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(archived_basket) as archived_basket"));
            }])->withCount(['performances AS sent_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(sent_basket) as sent_basket"));
            }]) ->withCount(['performances AS completed_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(completed_request) as completed_request"));
            }])->withCount(['performances AS updated_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(updated_request) as updated_request"));
            }]) ->withCount(['performances AS opened_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(opened_request) as opened_request"));
            }])->withCount(['performances AS received_task' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(received_task) as received_task"));
            }])->withCount(['performances AS replayed_task' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(replayed_task) as replayed_task"));
            }])->withCount(['performances AS missed_reminders' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(missed_reminders) as missed_reminders"));
            }])->withCount(['performances AS move_request_from' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(move_request_from) as move_request_from"));
            }])->withCount(['performances AS move_request_to' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(move_request_to) as move_request_to"));
            }]);

        return DataTables::of($users)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                if ($item->role == 5){
                    return $item->name_for_admin;
                }
                return $item->name;
            })
            ->addColumn('total_recived_request',function ($item) {
                return $item->received_basket+$item->move_request_to;
            })
            ->addColumn('received_basket',function ($item) {
                return $item->received_basket ??0;
            })
            ->addColumn('star_basket',function ($item) {
                return $item->star_basket ??0;
            })
            ->addColumn('followed_basket',function ($item) {
                return $item->followed_basket ??0;
            })
            ->addColumn('archived_basket',function ($item) {
                return $item->archived_basket ??0;
            })
            ->addColumn('sent_basket',function ($item) {
                return $item->sent_basket ??0;
            })
            ->addColumn('completed_request',function ($item) {
                return $item->completed_request ??0;
            })
            ->addColumn('updated_request',function ($item) {
                return $item->updated_request ??0;
            })
            ->addColumn('opened_request',function ($item) {
                return $item->opened_request ??0;
            })
            ->addColumn('received_task',function ($item) {
                return $item->received_task ??0;
            })
            ->addColumn('replayed_task',function ($item) {
                return $item->replayed_task ??0;
            })
            ->addColumn('missed_reminders',function ($item) {
                return $item->missed_reminders ??0;
            })
            ->addColumn('total_servays',function ($item) {
                return $item->star_basket ??0;
            })
            ->addColumn('move_request_from',function ($item) {
                return $item->move_request_from ??0;
            })
            ->addColumn('move_request_to',function ($item) {
                return $item->move_request_to ??0;
            })
            ->addColumn('action',function ($item) {
                $data = '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('HumanResource.daily.statistics.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                    </div>';
                return $data;
            })
            ->rawColumns([
                'action',"total_servays",
                'idn','name','total_recived_request','received_basket','star_basket','followed_basket',
                'archived_basket','sent_basket','completed_request','updated_request','opened_request',
                'received_task','replayed_task','missed_reminders','move_request_from','move_request_to'
            ])->make(true);
    }

    public function reportSum(Request $request)
    {
        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if (!$request->has("role")){
            $request->merge([
               "role"   => 0
            ]);
        }

        $users = User::where('role', $request->role)
            ->when($request->role == 5 && auth()->user()->role == 9, function ($q, $v) use ($request) {
                $q->where("subdomain","<>",null);
            })
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', [$request->adviser_id]);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id',[$request->manager_id]);
            })
            ->withCount(['servays AS total_servays' => function ($query) {
                $query->whereBetween('created_at', [request('startdate'), request('enddate')]);
            }])
            ->withCount(['performances AS total_recived_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(total_recived_request) as total_recived_request"));
            }])
            ->withCount(['performances AS received_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(received_basket) as received_basket"));
            }])
            ->withCount(['performances AS star_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(star_basket) as star_basket"));
            }])
            ->withCount(['performances AS followed_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(followed_basket) as followed_basket"));
            }])
            ->withCount(['performances AS archived_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(archived_basket) as archived_basket"));
            }])
            ->withCount(['performances AS sent_basket' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(sent_basket) as sent_basket"));
            }])
            ->withCount(['performances AS completed_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(completed_request) as completed_request"));
            }])
            ->withCount(['performances AS updated_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(updated_request) as updated_request"));
            }])
            ->withCount(['performances AS opened_request' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(opened_request) as opened_request"));
            }])
            ->withCount(['performances AS received_task' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(received_task) as received_task"));
            }])
            ->withCount(['performances AS replayed_task' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(replayed_task) as replayed_task"));
            }])
            ->withCount(['performances AS missed_reminders' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(missed_reminders) as missed_reminders"));
            }])
            ->withCount(['performances AS move_request_from' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(move_request_from) as move_request_from"));
            }])
            ->withCount(['performances AS move_request_to' => function ($query) {
                $query->whereBetween('today_date', [request('startdate'), request('enddate')])
                    ->select(DB::raw("SUM(move_request_to) as move_request_to"));
            }])->get();
        $array =[
            "total_servays"   =>  0,
            "total_recived_request"   =>  0,
            "received_basket"   =>  0,
            "star_basket"       =>  0,
            "followed_basket"   =>  0,
            "archived_basket"   =>  0,
            "sent_basket"       =>  0,
            "completed_request" =>  0,
            "updated_request"   =>  0,
            "opened_request"    =>  0,
            "received_task"     =>  0,
            "replayed_task"     =>  0,
            "missed_reminders"  =>  0,
            "move_request_from" =>  0,
            "move_request_to"   =>  0
        ];
        $total_servays = 0;
        $received_basket = 0;
        $star_basket = 0;
        $followed_basket = 0;
        $archived_basket = 0;
        $sent_basket = 0;
        $completed_request = 0;
        $updated_request = 0;
        $opened_request = 0;
        $received_task = 0;
        $replayed_task = 0;
        $missed_reminders = 0;
        $move_request_from = 0;
        $move_request_to = 0;

        foreach ($users as $user) {

            $array=[
                "total_servays"   =>  $total_servays+=$user->total_servays,
                "received_basket"   =>  $received_basket+=$user->received_basket,
                "star_basket"       =>  $star_basket+=$user->star_basket,
                "followed_basket"   =>  $followed_basket+=$user->followed_basket,
                "archived_basket"   =>  $archived_basket+=$user->archived_basket,
                "sent_basket"       =>  $sent_basket+=$user->sent_basket,
                "completed_request" =>  $completed_request+=$user->completed_request,
                "updated_request"   =>  $updated_request+=$user->updated_request,
                "opened_request"    =>  $opened_request+=$user->opened_request,
                "received_task"     =>  $received_task+=$user->received_task,
                "replayed_task"     =>  $replayed_task+=$user->replayed_task,
                "missed_reminders"  =>  $missed_reminders+=$user->missed_reminders,
                "move_request_from" =>  $move_request_from+=$user->move_request_from,
                "move_request_to"   =>  $move_request_to+=$user->move_request_to,
            ];
        }
        $array["total_recived_request"] = $array["received_basket"]+$array["move_request_to"];
        return response()->json(["data" => $array]);
    }

    public function pdfStatistics($id,$start,$end){
        $user = User::find($id);
        $data=[
          'user'    => $user,
          'start'   => $start,
          'end'     => $end,
        ];

        
        $pdf = PDF::loadView('Charts.daily-performance.pdf.statistics-pdf',$data);

        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('Daily-'.$user->name.'-'.$id.'.pdf');

    }
}
