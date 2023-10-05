<?php

namespace App\Http\Controllers;

use View;
use Charts;
use App\User;
use Response;
use App\customer;
use Carbon\Carbon;
use App\Announcement;
use App\notification;
use App\classifcation;
use App\DailyPerformances;
use App\request as Request;
use App\Models\RequestSource;
use App\Models\Classification;
// use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request as Req;
use ConsoleTVs\Charts\Builder\Chart;
use App\Charts\DailyPerformanceChart;
use App\Charts\DailyPerformanceChartQuilty;

class ChartController extends Controller
{
    public function __construct()
    {
        if (config('app.debug')) {
            view()->share([
                'announces'                 => Announcement::where('status', 1)->get(),
                'all_reqs_count'            => null,
                'agent_received_reqs_count' => null,
                'follow_reqs_count'         => null,
                'star_reqs_count'           => null,
                'arch_reqs_count'           => null,
                'pending_request_count'     => null,
                'need_action_request_count' => null,
                'sent_task_count'           => null,
                'received_task_count'       => null,
                'completed_task_count'      => null,
                'calculator_suggests'       => null,
                'onlineUsers'               => [],
                'notifyWithoutReminders'    => collect(),
                'notifyWithOnlyReminders'   => collect(),
                'notifyWithHelpdesk'        => collect(),
                'unread_conversions'        => null,
                'unread_messages'           => collect(),
            ]);
        }
        else {
            \Illuminate\Support\Facades\View::composers([
                'App\Composers\HomeComposer'             => ['layouts.content'],
                'App\Composers\ActivityComposer'         => ['layouts.content'],
                'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
            ]);
        }
        view()->share([
            'announces'                 => collect(),
            'all_reqs_count'            => null,
            'agent_received_reqs_count' => null,
            'follow_reqs_count'         => null,
            'star_reqs_count'           => null,
            'arch_reqs_count'           => null,
            'pending_request_count'     => null,
            'need_action_request_count' => null,
            'pur_reqs_count'            => 0,
            'mor_reqs_count'            => 0,
            'rej_reqs_count'            => 0,
            'cancel_reqs_count'         => 0,
            'agent_com_reqs_count'            => 0,
            'agent_arch_reqs_count'            => 0,
            'daily_reqs_count'            => 0,
            'sent_task_count'           => null,
            'received_task_count'       => null,
            'completed_task_count'      => null,
            'calculator_suggests'       => null,
            'onlineUsers'               => [],
            'notifyWithoutReminders'    => collect(),
            'notifyWithOnlyReminders'   => collect(),
            'notifyWithHelpdesk'        => collect(),
            'unread_conversions'        => null,
            'unread_messages'           => collect(),
        ]);
    }


    public function index()
    {

        // this code to retrive chat view
        $user = auth()->user();
        // $user = User::find(8) ;  /* Just For test because rhis user have data */

        //get all sales agent (just ids) depending on auth user role
        $subs_ids = $this->subs($user->id);
        //get all sales agent (all info) depending on auth user role
        $subs = User::whereIn('id', $subs_ids)->get();
        /*
        /..
        /..
        /.. default charts for auth user
        /..
        /..
        */
        if ($user->role == 0) {
            $title = 'Result of My Requests';
        }
        else {
            $title = 'Sales Agents Requests';
        }

        // Bar Chart :: Requests in the last 6 months
        $all_requests = Request::whereIn('user_id', $subs_ids)->get();
        $chart = Charts::database($all_requests, 'bar', 'highcharts')
            ->title($title.' In Last 6 Months')
            ->elementLabel('Total Requests')
            ->dimensions(700, 500) // change chart size on  your view
            ->responsive(false)
            ->lastByMonth(6, true);

        // pie Chart :: Requests grouped by type
        $mortgage = Request::whereIn('user_id', $subs_ids)->where('type', 'رهن')->count();
        $purchase = Request::whereIn('user_id', $subs_ids)->where('type', 'شراء')->count();
        $mortgage_purchase = Request::whereIn('user_id', $subs_ids)->where('type', 'رهن-شراء')->count();
        $pie = Charts::create('pie', 'highcharts')
            ->title($title.' Grouped By Type')
            ->labels(['Mortgage', 'Purchase', 'Mortgage-Purchase'])
            ->values([$mortgage, $purchase, $mortgage_purchase])
            ->dimensions(700, 500)
            ->responsive(false);

        // donut Chart :: Requests grouped by status
        $new = Request::whereIn('user_id', $subs_ids)->where('statusReq', 0)->count();
        $inprogress = Request::where('user_id', $subs_ids)->whereNotIn('statusReq', [0, 15, 16])->count();
        $completed = Request::where('user_id', $subs_ids)->where('statusReq', 16)->count();
        $excluded = Request::where('user_id', $subs_ids)->where('statusReq', 15)->count();
        $donut = Charts::create('donut', 'highcharts')
            ->title($title.' Grouped By Status')
            ->labels(['New', 'In progress', 'Completed', 'Excluded'])
            ->colors(['#ededed', '#00b5e9', '#33bd7f', '#fa4251'])
            ->values([$new, $inprogress, $completed, $excluded])
            ->dimensions(700, 500)
            ->responsive(false);

        /*
        /..
        /..
        /.. default charts for admin user
        /..
        /..
        */

        // Areaspline Chart :: Today Requests
        $orders = [
            Request::whereIn('user_id', $subs_ids)->whereBetween('created_at', [Carbon::now()->subHours(32)->toDateTimeString(), Carbon::now()->subHours(24)->toDateTimeString()])->count(),
            Request::whereIn('user_id', $subs_ids)->whereBetween('created_at', [Carbon::now()->subHours(24)->toDateTimeString(), Carbon::now()->subHours(12)->toDateTimeString()])->count(),
            Request::whereIn('user_id', $subs_ids)->whereBetween('created_at', [Carbon::now()->subHours(12)->toDateTimeString(), Carbon::now()->subHours(6)->toDateTimeString()])->count(),
            Request::whereIn('user_id', $subs_ids)->whereBetween('created_at', [Carbon::now()->subHours(6)->toDateTimeString(), Carbon::now()->subHours(3)->toDateTimeString()])->count(),
        ];
        //For admin :: get today requests
        $area = Charts::create('area', 'highcharts')
            ->title('Today Requests')
            ->elementLabel('Requests')
            ->labels(['24 Hours', '12 Hours', '6 Hours', '3 Hours'])
            ->values($orders)
            ->dimensions(800, 500)
            ->responsive(false);

        // Multi Charts :: Subs users requests
        $areaspline = Charts::multi('areaspline', 'highcharts')
            ->title($title.'  In 4 Weeks ')
            ->labels(['Week 4 ', 'Week 3', 'Week 2 ', 'Week 1']);
        foreach ($subs_ids as $uid) {
            $areaspline->dataset($this->userName($uid), $this->userRequests($uid));
        }

        return view('Charts.chart', compact('user', 'subs', 'chart', 'pie', 'donut', 'areaspline', 'area'));
    }

    public function subs($id)
    {
        $user = User::find($id);
        // get all subs users
        switch ($user->role) {
            case 0:
                //Sales agent role
                $subs_ids = [$user->id];
                break;
            case 1:
                //Sales manager role
                $subs_ids = User::whereIn('role', [0])->where('manager_id', $user->id)->pluck('id')->toArray();
                break;
            case 2:
                //funding manager role
                $subs_ids = User::whereIn('role', [0])->where('funding_mnager_id', $user->id)->pluck('id')->toArray();
                break;
            case 3:
                //mortgage manager role
                $subs_ids = User::whereIn('role', [0])->where('mortgage_mnager_id', 5)->pluck('id')->toArray();
                break;
            case 4:
                //general manager role
                $subs_ids = User::whereIn('role', [0])->pluck('id')->toArray();
                break;
            case 5:
                //quality manager role
                $subs_ids = User::whereIn('role', [0])->pluck('id')->toArray();
                break;
            case 6:
                //otared
                $subs_ids = User::whereIn('role', [0])->pluck('id')->toArray();
                break;
            case 7:
                //admin
                $subs_ids = User::whereIn('role', [0])->pluck('id')->toArray();
                break;
            default:
                // another role == sales agent
                $subs_ids = [$user->id];
                break;
        }
        return $subs_ids;
    }

    //chart function to test library

    public function userName($id)
    {
        $user = User::find($id);
        return $user->name;
    }

    //get user name by user id

    public function userRequests($id)
    {
        return [
            Request::where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->startOfMonth()->addWeeks(1)])->count(),
            Request::where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth()->addWeeks(1), Carbon::now()->startOfMonth()->addWeeks(2)])->count(),
            Request::where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth()->addWeeks(2), Carbon::now()->startOfMonth()->addWeeks(3)])->count(),
            Request::where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth()->addWeeks(3), Carbon::now()->endOfMonth()])->count(),
        ];
    }

    // get all requests for user

    public function extractCharts(Req $request)
    {
        // dd($request->all());
        $chart_type = $request->chart;
        $filters = $request->except(['_token', 'chart', 'subs_ids', 'avg']);
        $filters = array_filter($filters);
        $query = Request::get();

        // create chart for sales agent(if found) , another filter will be call with  userFilterRequests function
        if ($request->subs_ids && !$request->avg) {
            $subs_ids = $request->subs_ids;
            if (array_key_exists('fromdate', $filters) || array_key_exists('todate', $filters)) {
                //to create priod between dates
                $datetime1 = strtotime($filters['fromdate']);
                $datetime2 = strtotime($filters['todate']);
                $difference1 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.25;
                $difference2 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.5;
                $difference3 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.75;

                $date = Carbon::parse(date('Y-m-d', strtotime($filters['fromdate'])));
                $d1 = date('Y-m-d', strtotime($date->addDays((int) $difference1)));
                $d2 = date('Y-m-d', strtotime($date->addDays((int) $difference2)));
                $d3 = date('Y-m-d', strtotime($date->addDays((int) $difference3)));

                $from = date('Y-m-d', strtotime($filters['fromdate']));
                $to = date('Y-m-d', strtotime($filters['todate']));

                if ((int) $difference1 < 1 || (int) $difference2 < 2 || (int) $difference3 < 3) {
                    $labels = [$from, '-', $to];
                    $title = 'Requests from '.$from.' to '.$to;
                }
                else {
                    $labels = [$from, $d1, $d2, $d3, $to];
                    $title = 'Requests from '.$from.' to '.$to;
                }
            }
            else {
                $labels = ['Week 4 ', 'Week 3', 'Week 2 ', 'Week 1'];
                $title = 'Requests In Last Month';
            }
            // Multi Charts :: Subs users
            $chart = Charts::multi('areaspline', 'highcharts')
                ->title($title)
                ->labels($labels);
            foreach ($subs_ids as $uid) {
                $chart->dataset($this->userName($uid), $this->userFilterRequests($uid, $filters));
            }

            $query = $query->whereIn('user_id', $subs_ids);
            //pass data to show it on view table
            foreach ($filters as $key => $value) {
                if ($key == 'fromdate' || $key == 'todate') {
                    $from = date('Y-m-d', strtotime($filters['fromdate']));
                    $to = date('Y-m-d', strtotime($filters['todate']));
                    $query = $query->whereBetween('req_date', [$from, $to]);
                }
                else {
                    $query = $query->where($key, $value);
                }
            }
            $data = $query;
        }

        // create chart for sales agent avarage updates
        if ($request->avg) {
            if ($request->subs_ids) {
                $subs_ids = $request->subs_ids;
            }
            else {
                $subs_ids = $this->subs(auth()->user()->id);
            }
            $names = [];
            $avgs = [];
            $data = [];
            foreach ($subs_ids as $uid) {
                $name = $this->userName($uid);
                $avg = $this->requestAvg($uid);
                array_push($names, $name);
                array_push($avgs, $avg);
                //create object
                $obj = [];
                $obj['id'] = $uid;
                $obj['name'] = $name;
                $obj['avg'] = $avg;
                array_push($data, $obj);
            }

            $chart = Charts::create('line', 'highcharts')
                ->title('Average time of data handling')
                ->elementLabel('Requests Avg Updates in days')
                ->labels($names)
                ->values($avgs)
                ->dimensions(800, 500)
                ->responsive(false);
        } // if search dont have sales agent, chart will be create depending on dates
        elseif (array_key_exists('fromdate', $filters) || array_key_exists('todate', $filters)) {
            // get all my sales agent
            $query = $query->whereIn('user_id', $this->subs(auth()->user()->id));
            if (sizeof($filters) > 2) {
                $arr = $filters;
                unset($arr['fromdate']);
                unset($arr['todate']);
                foreach ($arr as $key => $value) {
                    $query = $query->where($key, $value);
                }
            }

            $from = date('Y-m-d', strtotime($filters['fromdate']));
            $to = date('Y-m-d', strtotime($filters['todate']));

            //to create priod between dates
            $datetime1 = strtotime($filters['fromdate']);
            $datetime2 = strtotime($filters['todate']);
            $difference1 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.25;
            $difference2 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.5;
            $difference3 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.75;
            //dd((int) $difference1,(int) $difference2 ,(int) $difference3);
            // get dates to insert it to charts
            $date = Carbon::parse(date('Y-m-d', strtotime($filters['fromdate'])));
            $d1 = date('Y-m-d', strtotime($date->addDays((int) $difference1)));
            $d2 = date('Y-m-d', strtotime($date->addDays((int) $difference2)));
            $d3 = date('Y-m-d', strtotime($date->addDays((int) $difference3)));

            if ((int) $difference1 < 1 || (int) $difference2 < 2 || (int) $difference3 < 3) {
                $labels = [$from, '-', $to];
                $title = 'Requests from '.$from.' to '.$to;
                $dates = [
                    $query->where('created_at', $from)->count(),
                    $query->whereBetween('created_at', [$from, $to])->count(),
                    $query->where('created_at', $to)->count(),
                ];
            }
            else {
                $labels = [$from, $d1, $d2, $d3, $to];
                $title = 'Requests from '.$from.' to '.$to;
                $dates = [
                    $query->where('created_at', $from)->count(),
                    $query->whereBetween('created_at', [$from, $date->addDays((int) $difference1)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference1)->toDateTimeString(), $date->addDays((int) $difference2)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference2)->toDateTimeString(), $date->addDays((int) $difference3)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference3)->toDateTimeString(), $to])->count(),
                ];
            }

            $chart = Charts::create('line', 'highcharts')
                ->title($title)
                ->elementLabel('Requests Result')
                ->labels($labels)
                ->values($dates)
                ->dimensions(800, 500)
                ->responsive(false);

            //pass data to show it on view table

            foreach ($filters as $key => $value) {
                if ($key == 'fromdate' || $key == 'todate') {
                    $from = date('Y-m-d', strtotime($filters['fromdate']));
                    $to = date('Y-m-d', strtotime($filters['todate']));
                    $query = $query->whereBetween('created_at', [$from, $to]);
                }
                else {
                    $query = $query->where($key, $value);
                }
            }
            $data = $query;
        } // if search dont have sales agent or dates, chart will be create depending on another filters
        else {
            // get all my sales agent
            $query = $query->whereIn('user_id', $this->subs(auth()->user()->id));
            foreach ($filters as $key => $value) {
                $query = $query->where($key, $value);
            }
            $data = $query;
            //dd($data);
            $year = date('Y');
            $chart = Charts::database($data, $chart_type, 'highcharts')
                ->title('Monthly Requests')
                ->elementLabel($year.' requests, another requests will be show on below table')
                ->dimensions(800, 600)
                ->responsive(false)
                ->groupByMonth(date('Y'), true);
        }

        return view('Charts.extracted', compact('chart', 'data'));
    }

    // get all subs users

    public function userFilterRequests($id, $filters)
    {
        $query = Request::where('user_id', $id)->get();
        if (!(array_key_exists('fromdate', $filters) && array_key_exists('todate', $filters))) {
            foreach ($filters as $key => $value) {
                $query = $query->where($key, $value);
            }
            return [
                $query->where('created_at', '>', Carbon::now()->subDays(7)->toDateTimeString())->count(),
                $query->whereBetween('created_at', [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->subDays(14)->toDateTimeString()])->count(),
                $query->whereBetween('created_at', [Carbon::now()->subDays(14)->toDateTimeString(), Carbon::now()->subDays(21)->toDateTimeString()])->count(),
                $query->whereBetween('created_at', [Carbon::now()->subDays(21)->toDateTimeString(), Carbon::now()->subDays(30)->toDateTimeString()])->count(),
            ];
        }
        else {
            if (sizeof($filters) > 2) {
                $arr = $filters;
                unset($arr['fromdate']);
                unset($arr['todate']);
                foreach ($arr as $key => $value) {
                    $query = $query->where($key, $value);
                }
            }
            $from = date('Y-m-d', strtotime($filters['fromdate']));
            $to = date('Y-m-d', strtotime($filters['todate']));
            //to create priod between dates
            $datetime1 = strtotime($filters['fromdate']);
            $datetime2 = strtotime($filters['todate']);
            $difference1 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.25;
            $difference2 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.5;
            $difference3 = (ceil(abs($datetime2 - $datetime1) / 86400)) * 0.75;
            //dd((int) $difference1,(int) $difference2 ,(int) $difference3);
            // get dates to insert it to charts
            $date = Carbon::parse(date('Y-m-d', strtotime($filters['fromdate'])));
            $d1 = date('Y-m-d', strtotime($date->addDays((int) $difference1)));
            $d2 = date('Y-m-d', strtotime($date->addDays((int) $difference2)));
            $d3 = date('Y-m-d', strtotime($date->addDays((int) $difference3)));
            if ((int) $difference1 < 1 || (int) $difference2 < 2 || (int) $difference3 < 2) {
                return [
                    $query->where('created_at', $from)->count(),
                    $query->whereBetween('created_at', [$from, $to])->count(),
                    $query->where('created_at', $to)->count(),
                ];
            }
            else {

                return [
                    $query->where('created_at', $from)->count(),
                    $query->whereBetween('created_at', [$from, $date->addDays((int) $difference1)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference1)->toDateTimeString(), $date->addDays((int) $difference2)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference2)->toDateTimeString(), $date->addDays((int) $difference3)->toDateTimeString()])->count(),
                    $query->whereBetween('created_at', [$date->addDays((int) $difference3)->toDateTimeString(), $to])->count(),
                ];
            }
        }
    }

    // get requests for user depending on another filters

    public function requestAvg($uid, $start = null, $end = null)
    {
        if ($start && $end) {
            $requests = Request::where('user_id', $uid)->whereBetween($start, $end)->get();
        }
        else {
            $requests = Request::where('user_id', $uid)->get();
        }

        $sum = 0;
        $count = sizeof($requests);

        foreach ($requests as $request) {
            $to = $request->updated_at;
            $from = $request->created_at;
            if ($to && $from) {
                $dif = $to->diffInDays($from);
                $sum = $sum + $dif;
            }
        }
        if ($sum != 0) {
            $result = $sum / $count;
        }
        else {
            $result = 0;
        }

        return $result;
    }

    public function chart()
    {
        $users = User::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
            ->get();

        $chart = Charts::database($users, 'bar', 'highcharts')
            ->title('Bar Chart')
            ->elementLabel('Total Users')
            ->dimensions(700, 500)
            ->responsive(false)
            ->groupByMonth(date('Y'), true);

        $pie = Charts::create('pie', 'highcharts')
            ->title('Pie Chart')
            ->labels(['First', 'Second', 'Third'])
            ->values([5, 10, 20])
            ->dimensions(700, 500)
            ->responsive(false);

        $donut = Charts::create('donut', 'highcharts')
            ->title('Donut Chart')
            ->labels(['First', 'Second', 'Third'])
            ->values([5, 10, 20])
            ->dimensions(1000, 500)
            ->responsive(false);
        $line = Charts::create('line', 'highcharts')
            ->title('Line  chart')
            ->elementLabel('line lable')
            ->labels(['First', 'Second', 'Third'])
            ->values([5, 10, 20])
            ->dimensions(1000, 500)
            ->responsive(false);
        $area = Charts::create('area', 'highcharts')
            ->title('Area chart')
            ->elementLabel('area label')
            ->labels(['First', 'Second', 'Third'])
            ->values([5, 10, 20])
            ->dimensions(1000, 500)
            ->responsive(false);

        $areaspline = Charts::multi('areaspline', 'highcharts')
            ->title('Areaspline chart')
            ->colors(['#ff0000', '#ffffff'])
            ->labels(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
            ->dataset('John', [3, 4, 3, 5, 4, 10, 12])
            ->dataset('Jane', [1, 3, 4, 3, 3, 5, 4]);
        $geo = Charts::create('geo', 'highcharts')
            ->title('Geo chart')
            ->elementLabel('My nice label')
            ->labels(['ES', 'FR', 'RU'])
            ->colors(['#C5CAE9', '#283593'])
            ->values([5, 10, 20])
            ->dimensions(1000, 500)
            ->responsive(false);
        $percentage = Charts::create('percentage', 'justgage')
            ->title('Percentage chart')
            ->elementLabel('My nice label')
            ->values([65, 0, 100])
            ->responsive(false)
            ->height(300)
            ->width(0);
        return view('chart', compact('chart', 'pie', 'donut', 'line', 'area', 'areaspline', 'geo', 'percentage'));
    }
    public function requestChartClassificationV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $classifications = classifcation::all()->where('user_role', 0);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.classification',[
            "users" => $users,
            "classifications" => $classifications,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestChartBasketV2(){
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.basket',[
            "users" => $users,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }
    public function requestChartStatusV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.status',[
            "users" => $users,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestChartBasketForSalesAgentV2(){
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.sales.basket',[
            "users" => $users,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }
    public function requestChartStatusForSalesAgentV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.sales.status',[
            "users" => $users,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }
    public function requestChartClassificationForSalesAgentV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $classifications = classifcation::all()->where('user_role', 0);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.sales.classification',[
            "users" => $users,
            "classifications" => $classifications,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestAgentChartClassificationV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $classifications = classifcation::all()->where('user_role', 0);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.agents.classification',[
            "users" => $users,
            "classifications" => $classifications,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestAgentChartBasketV2(){
        ini_set('memory_limit', '-1');
        return view('Charts.requests.agents.basket',[
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }
    public function requestAgentChartStatusV2()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.requests.agents.status',[
            "users" => $users,
            "managers" => $managers,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestChartTrainingClassificationV2()
    {
        ini_set('memory_limit', '-1');
        $classifications = classifcation::all()->where('user_role', 0);
        $users =  auth()->user()->trainings;
        return view('Charts.requests.trainings.classification',[
            "users" => $users,
            "classifications" => $classifications,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestChartTrainingBasketV2(){
        ini_set('memory_limit', '-1');
        $users =  auth()->user()->trainings;
        return view('Charts.requests.trainings.basket',[
            "users" => $users,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }
    public function requestChartTrainingStatusV2()
    {
        ini_set('memory_limit', '-1');
        $users =  auth()->user()->trainings;
        return view('Charts.requests.trainings.status',[
            "users" => $users,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)

        ]);
    }

    public function requestChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];
        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $start = $request['startdate'];
        $end = $request['enddate'];

        //FOR Basket OF REQUESTS
        $baskets = $request['basket'] ?: ['allBaskets']; //if not selected any basket will select all baskets
        $data_for_basket_chart = [];
        $all_basket_selected = in_array('allBaskets', $baskets);
        foreach ($users as $user) {
            $data_basket = [];

            $data_basket['name'] = $user->name;

            if ($all_basket_selected || in_array('received', $baskets)) {
                $data_basket['received'] = $user->receivedRequest($start, $end);
            }

            if ($all_basket_selected || in_array('star', $baskets)) {
                $data_basket['star'] = $user->starRequest($start, $end);
            }

            if ($all_basket_selected || in_array('following', $baskets)) {
                $data_basket['following'] = $user->followingRequest($start, $end);
            }

            if ($all_basket_selected || in_array('archived', $baskets)) {
                $data_basket['archived'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_basket_selected || in_array('complete', $baskets)) {
                $data_basket['complete'] = $user->complete($start, $end);
            }

            $data_for_basket_chart[] = $data_basket;
        }
        //END Basket OF REQUESTS

        //FOR STATUS OF REQUESTS
        $statuses = $request['status'] ?: ['allStatus'];
        $data_for_status_chart = [];
        $all_status_selected = in_array('allStatus', $statuses);
        foreach ($users as $user) {
            $data_status = [];

            $data_status['name'] = $user->name;

            if ($all_status_selected || in_array('newStatus', $statuses)) {
                $data_status['newStatus'] = $user->newRequest($start, $end);
            }

            if ($all_status_selected || in_array('openStatus', $statuses)) {
                $data_status['openStatus'] = $user->openRequest($start, $end);
            }

            if ($all_status_selected || in_array('archiveStatus', $statuses)) {
                $data_status['archiveStatus'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_status_selected || in_array('watingSMStatus', $statuses)) {
                $data_status['watingSMStatus'] = $user->watingForSalesManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedSMStatus', $statuses)) {
                $data_status['rejectedSMStatus'] = $user->rejectedFromSalesManagerRequest($start, $end);
            }

            /*if ($all_status_selected || in_array('archiveSMStatus', $statuses))
                $data_status['archiveSMStatus'] = $user->archivedInSalesManagerRequest( $start, $end);
                */

            if ($all_status_selected || in_array('watingFMStatus', $statuses)) {
                $data_status['watingFMStatus'] = $user->watingForFundingManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedFMStatus', $statuses)) {
                $data_status['rejectedFMStatus'] = $user->rejectedFromFundingManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveFMStatus', $statuses))
                $data_status['archiveFMStatus'] = $user->archivedInFundingManagerRequest( $start, $end);
                */

            if ($all_status_selected || in_array('watingMMStatus', $statuses)) {
                $data_status['watingMMStatus'] = $user->watingForMortgageManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedMMStatus', $statuses)) {
                $data_status['rejectedMMStatus'] = $user->rejectedFromMortgageManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveMMStatus', $statuses))
                $data_status['archiveMMStatus'] = $user->archivedInMortgageManagerRequest( $start, $end);
                */

            if ($all_status_selected || in_array('watingGMStatus', $statuses)) {
                $data_status['watingGMStatus'] = $user->watingForGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedGMStatus', $statuses)) {
                $data_status['rejectedGMStatus'] = $user->rejectedFromGeneralManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveGMStatus', $statuses))
                $data_status['archiveGMStatus'] = $user->archivedInGeneralManagerRequest( $start, $end);
                */

            if ($all_status_selected || in_array('canceledStatus', $statuses)) {
                $data_status['canceledStatus'] = $user->canceledFromGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('completedStatus', $statuses)) {
                $data_status['completedStatus'] = $user->completedInGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('fundingReportStatus', $statuses)) {
                $data_status['fundingReportStatus'] = $user->fundingReportRequest($start, $end);
            }

            if ($all_status_selected || in_array('mortgageReportStatus', $statuses)) {
                $data_status['mortgageReportStatus'] = $user->mortgageReportRequest($start, $end);
            }

            $data_for_status_chart[] = $data_status;
        }
        //END FOR STATUS OF REQUESTS

        //FOR CLASS OF REQUESTS
        $classes = $request['class'] ?: ['allClass'];
        $data_for_class_chart = [];
        $all_class_selected = in_array('allClass', $classes);
        $agent_class = classifcation::all()->where('user_role', 0);

        foreach ($users as $user) {
            $data_class = [];

            $data_class['name'] = $user->name;

            foreach ($agent_class as $class) {

                if ($all_class_selected || in_array('class-'.$class->id, $classes)) {
                    $data_class['class-'.$class->id] = $user->classRequest($class->id, $start, $end);
                }
            }

            $data_for_class_chart[] = $data_class;
        }
        //END FOR CLASS OF REQUESTS

        return view('Charts.requestChart', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'data_for_basket_chart',
            'baskets',
            'statuses',
            'data_for_status_chart',
            'classes',
            'agent_class',
            'manager_role',
            'data_for_class_chart',
        ));
    }

    public function requestChartRForAgent(Req $request)
    {

        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $all_users = User::all()->where('status', 1);
        $users = $all_users->where('id', auth()->user()->id);

        $start = $request['startdate'];
        $end = $request['enddate'];

        //FOR Basket OF REQUESTS
        $baskets = $request['basket'] ?: ['allBaskets']; //if not selected any basket will select all baskets
        $data_for_basket_chart = [];
        $all_basket_selected = in_array('allBaskets', $baskets);
        foreach ($users as $user) {
            $data_basket = [];

            $data_basket['name'] = $user->name;

            if ($all_basket_selected || in_array('received', $baskets)) {
                $data_basket['received'] = $user->receivedRequest($start, $end);
            }

            if ($all_basket_selected || in_array('star', $baskets)) {
                $data_basket['star'] = $user->starRequest($start, $end);
            }

            if ($all_basket_selected || in_array('following', $baskets)) {
                $data_basket['following'] = $user->followingRequest($start, $end);
            }

            if ($all_basket_selected || in_array('archived', $baskets)) {
                $data_basket['archived'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_basket_selected || in_array('complete', $baskets)) {
                $data_basket['complete'] = $user->complete($start, $end);
            }

            $data_for_basket_chart[] = $data_basket;
        }
        //END Basket OF REQUESTS

        //FOR STATUS OF REQUESTS
        $statuses = $request['status'] ?: ['allStatus'];
        $data_for_status_chart = [];
        $all_status_selected = in_array('allStatus', $statuses);
        foreach ($users as $user) {
            $data_status = [];

            $data_status['name'] = $user->name;

            if ($all_status_selected || in_array('newStatus', $statuses)) {
                $data_status['newStatus'] = $user->newRequest($start, $end);
            }

            if ($all_status_selected || in_array('openStatus', $statuses)) {
                $data_status['openStatus'] = $user->openRequest($start, $end);
            }

            if ($all_status_selected || in_array('archiveStatus', $statuses)) {
                $data_status['archiveStatus'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_status_selected || in_array('watingSMStatus', $statuses)) {
                $data_status['watingSMStatus'] = $user->watingForSalesManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedSMStatus', $statuses)) {
                $data_status['rejectedSMStatus'] = $user->rejectedFromSalesManagerRequest($start, $end);
            }

            /*if ($all_status_selected || in_array('archiveSMStatus', $statuses))
                $data_status['archiveSMStatus'] = $user->archivedInSalesManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingFMStatus', $statuses)) {
                $data_status['watingFMStatus'] = $user->watingForFundingManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedFMStatus', $statuses)) {
                $data_status['rejectedFMStatus'] = $user->rejectedFromFundingManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveFMStatus', $statuses))
                $data_status['archiveFMStatus'] = $user->archivedInFundingManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingMMStatus', $statuses)) {
                $data_status['watingMMStatus'] = $user->watingForMortgageManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedMMStatus', $statuses)) {
                $data_status['rejectedMMStatus'] = $user->rejectedFromMortgageManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveMMStatus', $statuses))
                $data_status['archiveMMStatus'] = $user->archivedInMortgageManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingGMStatus', $statuses)) {
                $data_status['watingGMStatus'] = $user->watingForGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedGMStatus', $statuses)) {
                $data_status['rejectedGMStatus'] = $user->rejectedFromGeneralManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveGMStatus', $statuses))
                $data_status['archiveGMStatus'] = $user->archivedInGeneralManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('canceledStatus', $statuses)) {
                $data_status['canceledStatus'] = $user->canceledFromGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('completedStatus', $statuses)) {
                $data_status['completedStatus'] = $user->completedInGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('fundingReportStatus', $statuses)) {
                $data_status['fundingReportStatus'] = $user->fundingReportRequest($start, $end);
            }

            if ($all_status_selected || in_array('mortgageReportStatus', $statuses)) {
                $data_status['mortgageReportStatus'] = $user->mortgageReportRequest($start, $end);
            }

            $data_for_status_chart[] = $data_status;
        }
        //END FOR STATUS OF REQUESTS

        //FOR CLASS OF REQUESTS
        $classes = $request['class'] ?: ['allClass'];
        $data_for_class_chart = [];
        $all_class_selected = in_array('allClass', $classes);
        $agent_class = classifcation::all()->where('user_role', 0);

        foreach ($users as $user) {
            $data_class = [];

            $data_class['name'] = $user->name;

            foreach ($agent_class as $class) {

                if ($all_class_selected || in_array('class-'.$class->id, $classes)) {
                    $data_class['class-'.$class->id] = $user->classRequest($class->id, $start, $end);
                }
            }

            $data_for_class_chart[] = $data_class;
        }
        //END FOR CLASS OF REQUESTS
        $classifications = classifcation::all()->where('user_role', 0);
        return view('Charts.requestChart', compact(
            'manager_role',
            'adviser_ids',
            'data_for_basket_chart',
            'baskets',
            'statuses',
            'data_for_status_chart',
            'classes',
            'agent_class',
            'data_for_class_chart',
        ));
    }

    public function requestChartRForTraining(Req $request)
    {

        $trainID = (auth()->user()->id);

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        if ($agents->count() != 0) {
            $agent_array = $agents->pluck('agent_id')->toArray();
            $all_users = User::all()->where('status', 1)->whereIn('id', $agent_array);
        }
        else {
            $all_users = User::all()->where('status', 1);
        }

        $managers = $all_users->where('role', '1')->where('status', 1);

        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users;

        if (in_array(0, $adviser_ids)) {
            $users = $all_users;
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $start = $request['startdate'];
        $end = $request['enddate'];

        //FOR Basket OF REQUESTS
        $baskets = $request['basket'] ?: ['allBaskets']; //if not selected any basket will select all baskets
        $data_for_basket_chart = [];
        $all_basket_selected = in_array('allBaskets', $baskets);
        foreach ($users as $user) {
            $data_basket = [];

            $data_basket['name'] = $user->name;

            if ($all_basket_selected || in_array('received', $baskets)) {
                $data_basket['received'] = $user->receivedRequest($start, $end);
            }

            if ($all_basket_selected || in_array('star', $baskets)) {
                $data_basket['star'] = $user->starRequest($start, $end);
            }

            if ($all_basket_selected || in_array('following', $baskets)) {
                $data_basket['following'] = $user->followingRequest($start, $end);
            }

            if ($all_basket_selected || in_array('archived', $baskets)) {
                $data_basket['archived'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_basket_selected || in_array('complete', $baskets)) {
                $data_basket['complete'] = $user->complete($start, $end);
            }

            $data_for_basket_chart[] = $data_basket;
        }
        //END Basket OF REQUESTS

        //FOR STATUS OF REQUESTS
        $statuses = $request['status'] ?: ['allStatus'];
        $data_for_status_chart = [];
        $all_status_selected = in_array('allStatus', $statuses);
        foreach ($users as $user) {
            $data_status = [];

            $data_status['name'] = $user->name;

            if ($all_status_selected || in_array('newStatus', $statuses)) {
                $data_status['newStatus'] = $user->newRequest($start, $end);
            }

            if ($all_status_selected || in_array('openStatus', $statuses)) {
                $data_status['openStatus'] = $user->openRequest($start, $end);
            }

            if ($all_status_selected || in_array('archiveStatus', $statuses)) {
                $data_status['archiveStatus'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_status_selected || in_array('watingSMStatus', $statuses)) {
                $data_status['watingSMStatus'] = $user->watingForSalesManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedSMStatus', $statuses)) {
                $data_status['rejectedSMStatus'] = $user->rejectedFromSalesManagerRequest($start, $end);
            }

            /*if ($all_status_selected || in_array('archiveSMStatus', $statuses))
                $data_status['archiveSMStatus'] = $user->archivedInSalesManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingFMStatus', $statuses)) {
                $data_status['watingFMStatus'] = $user->watingForFundingManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedFMStatus', $statuses)) {
                $data_status['rejectedFMStatus'] = $user->rejectedFromFundingManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveFMStatus', $statuses))
                $data_status['archiveFMStatus'] = $user->archivedInFundingManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingMMStatus', $statuses)) {
                $data_status['watingMMStatus'] = $user->watingForMortgageManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedMMStatus', $statuses)) {
                $data_status['rejectedMMStatus'] = $user->rejectedFromMortgageManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveMMStatus', $statuses))
                $data_status['archiveMMStatus'] = $user->archivedInMortgageManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingGMStatus', $statuses)) {
                $data_status['watingGMStatus'] = $user->watingForGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedGMStatus', $statuses)) {
                $data_status['rejectedGMStatus'] = $user->rejectedFromGeneralManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveGMStatus', $statuses))
                $data_status['archiveGMStatus'] = $user->archivedInGeneralManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('canceledStatus', $statuses)) {
                $data_status['canceledStatus'] = $user->canceledFromGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('completedStatus', $statuses)) {
                $data_status['completedStatus'] = $user->completedInGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('fundingReportStatus', $statuses)) {
                $data_status['fundingReportStatus'] = $user->fundingReportRequest($start, $end);
            }

            if ($all_status_selected || in_array('mortgageReportStatus', $statuses)) {
                $data_status['mortgageReportStatus'] = $user->mortgageReportRequest($start, $end);
            }

            $data_for_status_chart[] = $data_status;
        }
        //END FOR STATUS OF REQUESTS

        //FOR CLASS OF REQUESTS
        $classes = $request['class'] ?: ['allClass'];
        $data_for_class_chart = [];
        $all_class_selected = in_array('allClass', $classes);
        $agent_class = classifcation::all()->where('user_role', 0);

        foreach ($users as $user) {
            $data_class = [];

            $data_class['name'] = $user->name;

            foreach ($agent_class as $class) {

                if ($all_class_selected || in_array('class-'.$class->id, $classes)) {
                    $data_class['class-'.$class->id] = $user->classRequest($class->id, $start, $end);
                }
            }

            $data_for_class_chart[] = $data_class;
        }
        //END FOR CLASS OF REQUESTS

        return view('Charts.requestChart', compact(
            'managers',
            'manager_ids',
            'manager_role',
            'advisers',
            'adviser_ids',
            'data_for_basket_chart',
            'baskets',
            'statuses',
            'data_for_status_chart',
            'classes',
            'agent_class',
            'data_for_class_chart',
        ));
    }

    public function requestChartRForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $start = $request['startdate'];
        $end = $request['enddate'];

        //FOR Basket OF REQUESTS
        $baskets = $request['basket'] ?: ['allBaskets']; //if not selected any basket will select all baskets
        $data_for_basket_chart = [];
        $all_basket_selected = in_array('allBaskets', $baskets);
        foreach ($users as $user) {
            $data_basket = [];

            $data_basket['name'] = $user->name;

            if ($all_basket_selected || in_array('received', $baskets)) {
                $data_basket['received'] = $user->receivedRequest($start, $end);
            }

            if ($all_basket_selected || in_array('star', $baskets)) {
                $data_basket['star'] = $user->starRequest($start, $end);
            }

            if ($all_basket_selected || in_array('following', $baskets)) {
                $data_basket['following'] = $user->followingRequest($start, $end);
            }

            if ($all_basket_selected || in_array('archived', $baskets)) {
                $data_basket['archived'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_basket_selected || in_array('complete', $baskets)) {
                $data_basket['complete'] = $user->complete($start, $end);
            }

            $data_for_basket_chart[] = $data_basket;
        }
        //END Basket OF REQUESTS

        //FOR STATUS OF REQUESTS
        $statuses = $request['status'] ?: ['allStatus'];
        $data_for_status_chart = [];
        $all_status_selected = in_array('allStatus', $statuses);
        foreach ($users as $user) {
            $data_status = [];

            $data_status['name'] = $user->name;

            if ($all_status_selected || in_array('newStatus', $statuses)) {
                $data_status['newStatus'] = $user->newRequest($start, $end);
            }

            if ($all_status_selected || in_array('openStatus', $statuses)) {
                $data_status['openStatus'] = $user->openRequest($start, $end);
            }

            if ($all_status_selected || in_array('archiveStatus', $statuses)) {
                $data_status['archiveStatus'] = $user->archivedInSalesAgentRequest($start, $end);
            }

            if ($all_status_selected || in_array('watingSMStatus', $statuses)) {
                $data_status['watingSMStatus'] = $user->watingForSalesManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedSMStatus', $statuses)) {
                $data_status['rejectedSMStatus'] = $user->rejectedFromSalesManagerRequest($start, $end);
            }

            /*if ($all_status_selected || in_array('archiveSMStatus', $statuses))
                $data_status['archiveSMStatus'] = $user->archivedInSalesManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingFMStatus', $statuses)) {
                $data_status['watingFMStatus'] = $user->watingForFundingManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedFMStatus', $statuses)) {
                $data_status['rejectedFMStatus'] = $user->rejectedFromFundingManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveFMStatus', $statuses))
                $data_status['archiveFMStatus'] = $user->archivedInFundingManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingMMStatus', $statuses)) {
                $data_status['watingMMStatus'] = $user->watingForMortgageManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedMMStatus', $statuses)) {
                $data_status['rejectedMMStatus'] = $user->rejectedFromMortgageManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveMMStatus', $statuses))
                $data_status['archiveMMStatus'] = $user->archivedInMortgageManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('watingGMStatus', $statuses)) {
                $data_status['watingGMStatus'] = $user->watingForGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('rejectedGMStatus', $statuses)) {
                $data_status['rejectedGMStatus'] = $user->rejectedFromGeneralManagerRequest($start, $end);
            }

            /*
            if ($all_status_selected || in_array('archiveGMStatus', $statuses))
                $data_status['archiveGMStatus'] = $user->archivedInGeneralManagerRequest($start, $end);
                */

            if ($all_status_selected || in_array('canceledStatus', $statuses)) {
                $data_status['canceledStatus'] = $user->canceledFromGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('completedStatus', $statuses)) {
                $data_status['completedStatus'] = $user->completedInGeneralManagerRequest($start, $end);
            }

            if ($all_status_selected || in_array('fundingReportStatus', $statuses)) {
                $data_status['fundingReportStatus'] = $user->fundingReportRequest($start, $end);
            }

            if ($all_status_selected || in_array('mortgageReportStatus', $statuses)) {
                $data_status['mortgageReportStatus'] = $user->mortgageReportRequest($start, $end);
            }

            $data_for_status_chart[] = $data_status;
        }
        //END FOR STATUS OF REQUESTS

        //FOR CLASS OF REQUESTS
        $classes = $request['class'] ?: ['allClass'];
        $data_for_class_chart = [];
        $all_class_selected = in_array('allClass', $classes);
        $agent_class = classifcation::all()->where('user_role', 0);

        foreach ($users as $user) {
            $data_class = [];

            $data_class['name'] = $user->name;

            foreach ($agent_class as $class) {

                if ($all_class_selected || in_array('class-'.$class->id, $classes)) {
                    $data_class['class-'.$class->id] = $user->classRequest($class->id, $start, $end);
                }
            }

            $data_for_class_chart[] = $data_class;
        }
        //END FOR CLASS OF REQUESTS

        return view('Charts.requestChart', compact(
            'managers',
            'manager_ids',
            'manager_role',
            'advisers',
            'adviser_ids',
            'data_for_basket_chart',
            'baskets',
            'statuses',
            'data_for_status_chart',
            'classes',
            'agent_class',
            'data_for_class_chart',
        ));
    }

    public function requestSourcesWsata(Req $request)
    {
        !$request->has('status_user') &&$request->merge(['status_user' => 2]);

        $all_users = User::when($request->status_user != 2, fn ($q) => $q->where('status', $request->status_user));
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $start = Carbon::parse(date('Y-m-d', strtotime('-30 day')));
        $end = Carbon::parse(date('Y-m-d'));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        //#get all dates between range
        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }
        $ids = $users->pluck('id');
        $data_range = [];

        //FOR WSATA
        $data_for_source = [];
        foreach ($dates as $dateRange) {
            $count = 0;
            $data_range['dateRange'] = $dateRange;

            $data_range['frind'] = $this->frindReqSource($ids, $dateRange);
            $count += $data_range['frind'];
            $data_range['telphone'] = $this->telphonReqSource($ids, $dateRange);
            $count += $data_range['telphone'];
            $data_range['missedCall'] = $this->missedCallReqSource($ids, $dateRange);
            $count += $data_range['missedCall'];
            $data_range['admin'] = $this->adminReqSource($ids, $dateRange);
            $count += $data_range['admin'];
            $data_range['webAskFunding'] = $this->webAskFundingReqSource($ids, $dateRange);
            $count += $data_range['webAskFunding'];
            $data_range['webAskCons'] = $this->webAskConsReqSource($ids, $dateRange);
            $count += $data_range['webAskCons'];
            $data_range['webCal'] = $this->webCalculaterReqSource($ids, $dateRange);
            $count += $data_range['webCal'];
            $data_range['collobrator'] = $this->collobratorWithoutReqSource($ids, $dateRange);
            $count += $data_range['collobrator'];
            $data_range['otared'] = $this->otaredReqSource($ids, $dateRange);
            $count += $data_range['otared'];
            $data_range['tamweelk'] = $this->tamweelkReqSource($ids, $dateRange);
            $count += $data_range['tamweelk'];
            $data_range['callNotRecord'] = $this->callNotRecordReqSource($ids, $dateRange);
            $count += $data_range['callNotRecord'];
            $data_range['hasbah_net_completed'] = $this->hasbahNetReqSource($ids, $dateRange);
            $count += $data_range['hasbah_net_completed'];
            $data_range['hasbah_net_notcompleted'] = $this->hasbahNetNotReqSource($ids, $dateRange);
            $count += $data_range['hasbah_net_notcompleted'];
            $data_range['app_askcons'] = $this->app_askconsReqSource($ids, $dateRange);
            $count += $data_range['app_askcons'];
            $data_range['app_calc'] = $this->app_calcReqSource($ids, $dateRange);
            $count += $data_range['app_calc'];

            $data_range['total_all'] = $count;

            $data_for_source[] = $data_range;
        }

        return view('Charts.requestSourceChartWsata', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'data_for_source',
        ));
    }

    public function requestSourcesChartRequests(Req $request)
    {
        !$request->has('status_user') &&$request->merge(['status_user' => 2]);

        $all_users = User::when($request->status_user != 2, fn ($q) => $q->where('status', $request->status_user));
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $start = Carbon::parse(date('Y-m-d', strtotime('-30 day')));
        $end = Carbon::parse(date('Y-m-d'));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        #get all dates between range
        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }

        $ids = $users->pluck('id');
        $data_range = [];

        //FOR PENDING REQUESTS
        $data_pending = [];
        $data_for_pending = [];
        foreach ($dates as $dateRange) {
            $data_pending['dateRange'] = $dateRange;
            $data_pending['webAskFunding'] = $this->webAskFundingReqSource_pending($ids, $dateRange);
            $data_pending['webAskCons'] = $this->webAskConsReqSource_pending($ids, $dateRange);
            $data_pending['webCal'] = $this->webCalculaterReqSource_pending($ids, $dateRange);
            $data_pending['otared'] = $this->otaredReqSource_pending($ids, $dateRange);
            $data_pending['tamweelk'] = $this->tamweelkReqSource_pending($ids, $dateRange);
            $data_pending['hasbah_net_completed'] = $this->hasbahNetReqSource_pending($ids, $dateRange);
            $data_pending['hasbah_net_notcompleted'] = $this->hasbahNetNotReqSource_pending($ids, $dateRange);
            $data_pending['app_askcons'] = $this->app_askconsReqSource_pending($ids, $dateRange);
            $data_pending['app_calc'] = $this->app_calcReqSource_pending($ids, $dateRange);

            $data_for_pending[] = $data_pending;
        }

        $data_pending_total = [];
        $data_pending_total['webAskFunding'] = 0;
        $data_pending_total['webAskCons'] = 0;
        $data_pending_total['webCal'] = 0;
        $data_pending_total['otared'] = 0;
        $data_pending_total['tamweelk'] = 0;
        $data_pending_total['hasbah_net_completed'] = 0;
        $data_pending_total['app_askcons'] = 0;
        $data_pending_total['app_calc'] = 0;

        foreach ($data_for_pending as $data_pending) {

            $data_pending_total['webAskFunding'] = $data_pending_total['webAskFunding'] + $data_pending['webAskFunding'];
            $data_pending_total['webAskCons'] = $data_pending_total['webAskCons'] + $data_pending['webAskCons'];
            $data_pending_total['webCal'] = $data_pending_total['webCal'] + $data_pending['webCal'];
            $data_pending_total['otared'] = $data_pending_total['otared'] + $data_pending['otared'];
            $data_pending_total['tamweelk'] = $data_pending_total['tamweelk'] + $data_pending['tamweelk'];
            $data_pending_total['hasbah_net_completed'] = $data_pending_total['hasbah_net_completed'] + $data_pending['hasbah_net_completed'];
            $data_pending_total['app_askcons'] = $data_pending_total['app_askcons'] + $data_pending['app_askcons'];
            $data_pending_total['app_calc'] = $data_pending_total['app_calc'] + $data_pending['app_calc'];
        }
        $data_for_total[] = $data_pending_total;

        return view('Charts.requestSourceChartRequests', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'dates',
            'data_for_pending',
            'data_for_total'
        ));
    }

    public function requestSourcesChartR(Req $request)
    {
        !$request->has('status_user') && $request->merge(['status_user' => 2]);
        $all_users = User::when($request->status_user != 2, fn($q) => $q->where('status', $request->status_user));
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->where("id",34)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_source = [];

            $data_source['name'] = $user->name;

            $data_source['frind'] = $user->frindReqSource($request['startdate'], $request['enddate']);
            $data_source['telphone'] = $user->telphonReqSource($request['startdate'], $request['enddate']);
            $data_source['missedCall'] = $user->missedCallReqSource($request['startdate'], $request['enddate']);
            $data_source['admin'] = $user->adminReqSource($request['startdate'], $request['enddate']);
            $data_source['webAskFunding'] = $user->webAskFundingReqSource($request['startdate'], $request['enddate']);
            $data_source['webAskCons'] = $user->webAskConsReqSource($request['startdate'], $request['enddate']);
            $data_source['webCal'] = $user->webCalculaterReqSource($request['startdate'], $request['enddate']);
            $data_source['collobrator'] = $user->collobratorWithoutReqSource($request['startdate'], $request['enddate']);
            $data_source['otared'] = $user->otaredReqSource($request['startdate'], $request['enddate']);
            $data_source['tamweelk'] = $user->tamweelkReqSource($request['startdate'], $request['enddate']);
            $data_source['callNotRecord'] = $user->callNotRecordReqSource($request['startdate'], $request['enddate']);
            $data_source['hasbah_net_completed'] = $user->hasbahNetReqSource($request['startdate'], $request['enddate']);
            $data_source['hasbah_net_notcompleted'] = $user->hasbahNetNotReqSource($request['startdate'], $request['enddate']);
            $data_source['app_askcons'] = $user->app_askconsReqSource($request['startdate'], $request['enddate']);
            $data_source['app_calc'] = $user->app_calcReqSource($request['startdate'], $request['enddate']);

            $data_for_chart[] = $data_source;
        }
dd($data_source);
        $start = Carbon::parse(date('Y-m-d', strtotime('-30 day')));
        $end = Carbon::parse(date('Y-m-d'));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        #get all dates between range
        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }

        $ids = $users->pluck('id');
        $data_range = [];

        //FOR WSATA
        $data_for_source = [];
        foreach ($dates as $dateRange) {
            $count = 0;
            $data_range['dateRange'] = $dateRange;

            $data_range['frind'] = $this->frindReqSource($ids, $dateRange);
            $count += $data_range['frind'];
            $data_range['telphone'] = $this->telphonReqSource($ids, $dateRange);
            $count += $data_range['telphone'];
            $data_range['missedCall'] = $this->missedCallReqSource($ids, $dateRange);
            $count += $data_range['missedCall'];
            $data_range['admin'] = $this->adminReqSource($ids, $dateRange);
            $count += $data_range['admin'];
            $data_range['webAskFunding'] = $this->webAskFundingReqSource($ids, $dateRange);
            $count += $data_range['webAskFunding'];
            $data_range['webAskCons'] = $this->webAskConsReqSource($ids, $dateRange);
            $count += $data_range['webAskCons'];
            $data_range['webCal'] = $this->webCalculaterReqSource($ids, $dateRange);
            $count += $data_range['webCal'];
            $data_range['collobrator'] = $this->collobratorWithoutReqSource($ids, $dateRange);
            $count += $data_range['collobrator'];
            $data_range['otared'] = $this->otaredReqSource($ids, $dateRange);
            $count += $data_range['otared'];
            $data_range['tamweelk'] = $this->tamweelkReqSource($ids, $dateRange);
            $count += $data_range['tamweelk'];
            $data_range['callNotRecord'] = $this->callNotRecordReqSource($ids, $dateRange);
            $count += $data_range['callNotRecord'];
            $data_range['hasbah_net_completed'] = $this->hasbahNetReqSource($ids, $dateRange);
            $count += $data_range['hasbah_net_completed'];
            $data_range['hasbah_net_notcompleted'] = $this->hasbahNetNotReqSource($ids, $dateRange);
            $count += $data_range['hasbah_net_notcompleted'];
            $data_range['app_askcons'] = $this->app_askconsReqSource($ids, $dateRange);
            $count += $data_range['app_askcons'];
            $data_range['app_calc'] = $this->app_calcReqSource($ids, $dateRange);
            $count += $data_range['app_calc'];

            $data_range['total_all'] = $count;

            $data_for_source[] = $data_range;
        }

        //FOR PENDING REQUESTS
        $data_pending = [];
        $data_for_pending = [];
        foreach ($dates as $dateRange) {
            $data_pending['dateRange'] = $dateRange;
            $data_pending['webAskFunding'] = $this->webAskFundingReqSource_pending($ids, $dateRange);
            $data_pending['webAskCons'] = $this->webAskConsReqSource_pending($ids, $dateRange);
            $data_pending['webCal'] = $this->webCalculaterReqSource_pending($ids, $dateRange);
            $data_pending['otared'] = $this->otaredReqSource_pending($ids, $dateRange);
            $data_pending['tamweelk'] = $this->tamweelkReqSource_pending($ids, $dateRange);
            $data_pending['hasbah_net_completed'] = $this->hasbahNetReqSource_pending($ids, $dateRange);
            $data_pending['hasbah_net_notcompleted'] = $this->hasbahNetNotReqSource_pending($ids, $dateRange);
            $data_pending['app_askcons'] = $this->app_askconsReqSource_pending($ids, $dateRange);
            $data_pending['app_calc'] = $this->app_calcReqSource_pending($ids, $dateRange);

            $data_for_pending[] = $data_pending;
        }

        $data_pending_total = [];
        $data_pending_total['webAskFunding'] = 0;
        $data_pending_total['webAskCons'] = 0;
        $data_pending_total['webCal'] = 0;
        $data_pending_total['otared'] = 0;
        $data_pending_total['tamweelk'] = 0;
        $data_pending_total['hasbah_net_completed'] = 0;
        $data_pending_total['app_askcons'] = 0;
        $data_pending_total['app_calc'] = 0;

        foreach ($data_for_pending as $data_pending) {

            $data_pending_total['webAskFunding'] = $data_pending_total['webAskFunding'] + $data_pending['webAskFunding'];
            $data_pending_total['webAskCons'] = $data_pending_total['webAskCons'] + $data_pending['webAskCons'];
            $data_pending_total['webCal'] = $data_pending_total['webCal'] + $data_pending['webCal'];
            $data_pending_total['otared'] = $data_pending_total['otared'] + $data_pending['otared'];
            $data_pending_total['tamweelk'] = $data_pending_total['tamweelk'] + $data_pending['tamweelk'];
            $data_pending_total['hasbah_net_completed'] = $data_pending_total['hasbah_net_completed'] + $data_pending['hasbah_net_completed'];
            $data_pending_total['app_askcons'] = $data_pending_total['app_askcons'] + $data_pending['app_askcons'];
            $data_pending_total['app_calc'] = $data_pending_total['app_calc'] + $data_pending['app_calc'];
        }
        $data_for_total[] = $data_pending_total;

        return view('Charts.requestSourceChart', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
            'dates',
            'data_for_source',
            'data_for_pending',
            'data_for_total'

        ));
    }

    public function frindReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 3)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function telphonReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 5)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function missedCallReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 1)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function adminReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 4)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webAskFundingReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 7)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webAskConsReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 8)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webCalculaterReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 9)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function collobratorWithoutReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 2)
            ->where('collaborator_id', '!=', 17)
            ->where('collaborator_id', '!=', 77)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function otaredReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 2)
            ->where('collaborator_id', 17)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function tamweelkReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 2)
            ->where('collaborator_id', 77)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function callNotRecordReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 10)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function hasbahNetReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')
            //->where('collaborator_id', 269)
            ->where('source', \App\Models\Request::HASBAH_SOURCE)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function hasbahNetNotReqSource($ids, $dateRange)
    {
        $allReqs = DB::table('requests')
            //->where('collaborator_id', 288)
            ->where('source', \App\Models\Request::HASBAH_SOURCE_NOT_COMPLETE)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function app_askconsReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 11)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function app_calcReqSource($ids, $dateRange)
    {

        $allReqs = DB::table('requests')->where('source', 12)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webAskFundingReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 7)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webAskConsReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 8)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function webCalculaterReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 9)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function otaredReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 2)
            ->where('collaborator_id', 17)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function tamweelkReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 2)
            ->where('collaborator_id', 77)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function hasbahNetReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')
            //->where('collaborator_id', 269)
            ->where('source', \App\Models\Request::HASBAH_SOURCE)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function hasbahNetNotReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')
            //->where('collaborator_id', 288)
            ->where('source', \App\Models\Request::HASBAH_SOURCE_NOT_COMPLETE)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function app_askconsReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 11)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function app_calcReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 12)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function dailyPrefromenceChartR() {

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
        $api=url('/chart/ajax-chart-line'); //ajaxdailyPrefromenceChartR fun
        $chart2->labels($horizontal_of_charts)->load($api);

        return view('Charts.daily-performance.daily-performance-admin',[
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

    public function ajaxdailyPrefromenceChartR()
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

    //=========================quilty report chart========================
    //=============================users labels=========================
    public function quiltyRepoertChart(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,//مدير نشط
            ]);
        }

        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {
                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }

        (isset(request()->startdate))? $startdate=request()->startdate: $startdate=Carbon::now()->format('Y-m-d');
        (isset(request()->enddate))?$enddate=request()->enddate:$enddate=Carbon::now()->format('Y-m-d');
        (isset(request()->type_of_requests))?$type_of_requests=request()->type_of_requests:$type_of_requests=99;

        // Labels of chart
        //$users_of_charts=$this->usersOfCharts(5);
        $users = User::when($request->status_user!=2, function ($q, $v) use ($request) {
                        $q->where('status', $request->status_user);
                    })->when($request->adviser_id != 0, function ($q, $v) use ($request) {
                        $q->whereIn('id', $request->adviser_id);
                    })
                    ->where('role', 5);
                   // ->get();

        // Datasets of chart
        $result=[];
        $title=[];
        
        if($type_of_requests == 2){ //طلبات مميزة
            $result=$users->withCount(['performances AS star_basket' => function ($query) use($startdate,$enddate){
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(star_basket) as star_basket"));
                        }])->pluck('star_basket');
            $title='طلبات مميزة';
        }elseif($type_of_requests == 3){//طلبات متابعة
            $result=$users->withCount(['performances AS followed_basket' => function ($query) use($startdate,$enddate) {
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(followed_basket) as followed_basket"));
                        }])->pluck('followed_basket');
            $title='طلبات متابعة';
        }elseif($type_of_requests == 4){//طلبات مؤرشفة
            $result=$users->withCount(['performances AS archived_basket' => function ($query) use($startdate,$enddate){
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(archived_basket) as archived_basket"));
                        }])->pluck('archived_basket');
            $title='طلبات مؤرشفة';
          //  return 'ddd';
        }elseif($type_of_requests == 5){//طلبات مرفوعه طلبات غير مكتمله
            $result=$users->withCount(['performances AS sent_basket' => function ($query) use($startdate,$enddate) {
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(sent_basket) as sent_basket"));
                        }])->pluck('sent_basket');
            $title='طلبات مرفوعه';
        }elseif($type_of_requests == 6){//طلبات مفرغة
            $result=$users->withCount(['performances AS completed_request' => function ($query) use($startdate,$enddate){
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(completed_request) as completed_request"));
                        }])->pluck('completed_request');
            $title='طلبات مفرغة';
        }elseif($type_of_requests == 7){//طلبات محدث عليها
            $result=$users->withCount(['performances AS updated_request' => function ($query) use($startdate,$enddate) {
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(updated_request) as updated_request"));
                        }])->pluck('updated_request');
            $title='طلبات محدث عليها';
        }elseif($type_of_requests == 8){//الإستطلاعات طلبات تم فتحها
            $result=$users->withCount(['performances AS opened_request' => function ($query) use($startdate,$enddate) {
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(opened_request) as opened_request"));
                        }])->pluck('opened_request');
            $title='طلبات تم فتحها';
        }elseif($type_of_requests == 10){//مهام تم الرد عليها
            $result=$users->withCount(['performances AS replayed_task' => function ($query) use($startdate,$enddate){
                            $query->whereBetween('today_date', [$startdate, $enddate])
                            ->select(DB::raw("SUM(replayed_task) as replayed_task"));
                        }])->pluck('replayed_task');
            $title='مهام تم الرد عليها';
        }elseif($type_of_requests == 99){//جميع الطلبات
            $users_of_charts=$this->usersOfCharts(5);
            // return $users_of_charts;
            foreach ($users_of_charts as $key => $user) {
                if(in_array($user->id,$users->pluck('id')->toArray())){
                    $users_of_charts[$key]['sum_of_total_requests'] = 
                                                                    $user->star_basket +
                                                                    // $user->received_basket +
                                                                    $user->followed_basket +
                                                                    $user->archived_basket +
                                                                    $user->sent_basket +
                                                                    $user->completed_request +
                                                                    $user->updated_request +
                                                                    $user->opened_request  +
                                                                    $user->replayed_task  ;
                }
            }
            
            $result=$users_of_charts->pluck('sum_of_total_requests')->toArray();
            //remove null values from result
            foreach ($result as $i=>$row) {
                if ($row === null)
                unset($result[$i]);
            }
            
            $title='جميع الطلبات';
            //return 'ddd';
        }
        return response()->json(['users' => $users->get() ,'result' => $result , 'title' => $title]);
    }
    
    //=========================quilty report chart========================
    
        public function dailyPrefromenceChartForQuailityR() {
            ini_set('memory_limit', '-1');
            $userModel = User::where('status', 1);
            $users = clone ($userModel)->where('role', 5)->get();

            //------------ handle chart details --------------
            //5 is the role of qulity users
            $users_of_charts=$this->usersOfCharts(5);
            $horizontal_of_charts=$users_of_charts->pluck('name')->toArray();

            $chart2=new DailyPerformanceChartQuilty;
            $api=url('/chart/ajax-chart-line-quilty'); //ajaxdailyPrefromenceQuiltyChartR fun
            //$chart2= $this->ajaxdailyPrefromenceQuiltyChartR();
            
            $chart2->labels($horizontal_of_charts)->load($api);

            //  ------------------------------------------------------------------------
            return view('Charts.daily-performance.daily-performance-quaility',[
                "users"   => $users,
                "chart2"  => $chart2,
                "maxVal"  => number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)
            ]);
        }
        //  ------------------------------------------------------------------------
        //  ------------------------------------------------------------------------
        public function ajaxdailyPrefromenceQuiltyChartR(){
            $result=null;
            $title=null;

         //return request()->adviser_id;

            (isset(request()->startdate))? $startdate=request()->startdate: $startdate=Carbon::now()->format('Y-m-d');
            (isset(request()->enddate))?$enddate=request()->enddate:$enddate=Carbon::now()->format('Y-m-d');
            (isset(request()->type_of_requests))?$type_of_requests=request()->type_of_requests:$type_of_requests=99;
            
            // return response()->json(['startdate'=>$startdate,'enddate'=>$enddate]);

            //$query->whereBetween('today_date', [request('startdate'), request('enddate')])
            if($type_of_requests == 2){ //طلبات مميزة
                $result=User::where('role', 5)
                            ->withCount(['performances AS star_basket' => function ($query) use($startdate,$enddate){
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(star_basket) as star_basket"));
                            }])->pluck('star_basket');
                $title='طلبات مميزة';
            }elseif($type_of_requests == 3){//طلبات متابعة
                $result=User::where('role', 5)
                            ->withCount(['performances AS followed_basket' => function ($query) use($startdate,$enddate) {
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(followed_basket) as followed_basket"));
                            }])->pluck('followed_basket');
                $title='طلبات متابعة';
            }elseif($type_of_requests == 4){//طلبات مؤرشفة
                $result=User::where('role', 5)
                            ->withCount(['performances AS archived_basket' => function ($query) use($startdate,$enddate){
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(archived_basket) as archived_basket"));
                            }])->pluck('archived_basket');
                $title='طلبات مؤرشفة';
            //  return 'ddd';
            }elseif($type_of_requests == 5){//طلبات مرفوعه طلبات غير مكتمله
                $result=User::where('role', 5)
                            ->withCount(['performances AS sent_basket' => function ($query) use($startdate,$enddate) {
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(sent_basket) as sent_basket"));
                            }])->pluck('sent_basket');
                $title='طلبات مرفوعه';
            }elseif($type_of_requests == 6){//طلبات مفرغة
                $result=User::where('role', 5)
                            ->withCount(['performances AS completed_request' => function ($query) use($startdate,$enddate){
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(completed_request) as completed_request"));
                            }])->pluck('completed_request');
                $title='طلبات مفرغة';
            }elseif($type_of_requests == 7){//طلبات محدث عليها
                $result=User::where('role', 5)
                            ->withCount(['performances AS updated_request' => function ($query) use($startdate,$enddate) {
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(updated_request) as updated_request"));
                            }])->pluck('updated_request');
                $title='طلبات محدث عليها';
            }elseif($type_of_requests == 8){//الإستطلاعات طلبات تم فتحها
                $result=User::where('role', 5)
                            ->withCount(['performances AS opened_request' => function ($query) use($startdate,$enddate) {
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(opened_request) as opened_request"));
                            }])->pluck('opened_request');
                $title='طلبات تم فتحها';
            }elseif($type_of_requests == 10){//مهام تم الرد عليها
                $result=User::where('role', 5)
                            ->withCount(['performances AS replayed_task' => function ($query) use($startdate,$enddate){
                                $query->whereBetween('today_date', [$startdate, $enddate])
                                ->select(DB::raw("SUM(replayed_task) as replayed_task"));
                            }])->pluck('replayed_task');
                $title='مهام تم الرد عليها';
            }elseif($type_of_requests == 99){//جميع الطلبات
                $users_of_charts=$this->usersOfCharts(5);
            // return $users_of_charts;
                foreach ($users_of_charts as $key => $user) {
                //   return $user->received_basket;
                    $users_of_charts[$key]['sum_of_total_requests'] = $user->received_basket + $user->star_basket + $user->followed_basket +
                                                                    $user->archived_basket + $user->sent_basket + $user->completed_request + $user->updated_request +
                                                                    $user->opened_request  + $user->replayed_task  ;
                }
                $result=$users_of_charts->pluck('sum_of_total_requests')->toArray();
                $title='جميع الطلبات';
                //return 'ddd';
            }

            $chart2=new DailyPerformanceChartQuilty();
            
            
            //$users_of_charts=$this->usersOfCharts(5);
            
            //$adviser_ids=explode(',',request()->adviser_id);
            
            // $horizontal_of_charts=$users_of_charts->whereIn('id',$adviser_ids)->pluck('name')->toArray();
            //$chart2->labels($horizontal_of_charts)->dataset($title, 'bar',$result)->options([
            $chart2->dataset($title, 'bar',$result)->options([
                'fill' => 'true',
                'borderColor' => '#51C1C0',
                'backgroundColor'=>'#0f5b94',
                'color' => 'red',
                'responsive'=>true,
                'maintainAspectRatio'=>true,
                'barThickness'=>'50px',
            ]);
            return json_decode($chart2->api());
            // return $horizontal_of_charts;
            //    $chart2->labels(['Jan', 'Feb', 'Mar']);
            //    $chart2->dataset('Users by trimester', 'line', [10, 25, 13])
            //        ->color("rgb(255, 99, 132)")
            //        ->backgroundcolor("rgb(255, 99, 132)");
            //       return json_decode($chart2->api());
        }
    
    //  ------------------------------------------------------------------------

    public function missedReminder($agent_id, $date)
    {

        $reminders = notification::where('request_type', '=', 1)
            ->whereNotNull('reminder_date')
            ->where('recived_id', $agent_id)
            ->where('status', 0)
            ->whereDate('reminder_date', $date)
            ->get();

        return $reminders->count();
    }

    public function dailyPrefromenceChartRForSalesManager(Req $request)
    {

        ini_set('memory_limit', '-1');
        $users = User::where('manager_id', auth()->id())
            ->where('role', 0)
            ->where('status', 1)->get();

        return view('Charts.daily-performance.daily-performance-sales', [
            "users" => $users,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0)
        ]);
    }

    public function callNotRecordReqSource_pending($ids, $dateRange)
    {

        $allReqs = DB::table('pending_requests')->where('source', 10)
            ->whereIn('user_id', $ids)
            ->whereDate('created_at', $dateRange);

        return $allReqs->count();
    }

    public function websiteChartR(Req $request)
    {

        $data_for_chart = [];
        $data_region = [];

       // dd($request->startdate);

        //======================================================================
        (isset(request()->startdate))? $startdate=request()->startdate: $startdate=Carbon::now()->format('Y-m-d');
        (isset(request()->enddate))?$enddate=request()->enddate:$enddate=Carbon::now()->format('Y-m-d');
        //======================================================================
  
        $regions = customer::select('region_ip', DB::raw('COUNT(id) as regionCount'))
                    ->when($request->startdate, function ($q) use ($startdate,$enddate) {
                      $q->whereBetween('created_at', [$startdate, $enddate]);
                    })
                    ->groupBy('region_ip')
                    ->get();

        foreach ($regions as $region) {

            if ($region->region_ip != null) {

                $data_region['region'] = $region->region_ip;
                $data_region['count'] = $region->regionCount;

                $data_for_chart[] = $data_region;
            }
        }

        return view('Charts.websiteRequestChart', compact(
            'data_for_chart',
        ));
    }

    public function movedRequestChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_moved = [];

            $data_moved['name'] = $user->name;

            $data_moved['movedFrom'] = $user->movedRequestsFrom($request['startdate'], $request['enddate']);
            $data_moved['movedTo'] = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $diff = 0;

            if ($data_moved['movedFrom'] < $data_moved['movedTo']) {
                if ($data_moved['movedTo'] != 0) {
                    $diff = $data_moved['movedFrom'] / $data_moved['movedTo'];
                }
                else {
                    $diff = 0;
                }

                $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                if ($data_moved['movedFrom'] != 0 && $data_moved['movedTo'] != 0) {
                    $diff = $data_moved['movedTo'] / $data_moved['movedFrom'];
                    $data_moved['present'] = (number_format((float) $diff, 2, '.', '') * 100);
                }
                elseif ($data_moved['movedFrom'] != 0 && $data_moved['movedTo'] == 0) {
                    $diff = 1;
                    $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
                else {
                    $diff = 0;
                    $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
            }

            $data_for_chart[] = $data_moved;
        }

        return view('Charts.movedRequestChart', compact(
            'managers',
            'manager_role',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function movedRequestChartRForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->where('status', 1)->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_moved = [];

            $data_moved['name'] = $user->name;

            $data_moved['movedFrom'] = $user->movedRequestsFrom($request['startdate'], $request['enddate']);
            $data_moved['movedTo'] = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $diff = 0;

            if ($data_moved['movedFrom'] < $data_moved['movedTo']) {
                if ($data_moved['movedTo'] != 0) {
                    $diff = $data_moved['movedFrom'] / $data_moved['movedTo'];
                }
                else {
                    $diff = 0;
                }

                $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                if ($data_moved['movedFrom'] != 0 && $data_moved['movedTo'] != 0) {
                    $diff = $data_moved['movedTo'] / $data_moved['movedFrom'];
                    $data_moved['present'] = (number_format((float) $diff, 2, '.', '') * 100);
                }
                elseif ($data_moved['movedFrom'] != 0 && $data_moved['movedTo'] == 0) {
                    $diff = 1;
                    $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
                else {
                    $diff = 0;
                    $data_moved['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
            }

            $data_for_chart[] = $data_moved;
        }

        return view('Charts.movedRequestChart', compact(
            'managers',
            'advisers',
            'manager_role',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function movedRequestWtihPostiveClassChart(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }

        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_moved = [];

            $data_moved['id'] = $user->id;
            $data_moved['name'] = $user->name;

            //OVERALL
            $data_moved['movedSent'] = $user->movedRequestsSent($request['startdate'], $request['enddate']);
            $data_moved['movedComplete'] = $user->movedRequestsCompleted($request['startdate'], $request['enddate']);
            $data_moved['total'] = $data_moved['movedSent'] + $data_moved['movedComplete'];

            //MOVEING TYPES
            $data_moved['moved_AskReq'] = $user->movedRequests_AskReq($request['startdate'], $request['enddate']);
            $data_moved['moved_Admin'] = $user->movedRequests_Admin($request['startdate'], $request['enddate']);
            $data_moved['moved_NeedActionTable'] = $user->movedRequests_NeedActionTable($request['startdate'], $request['enddate']);
            $data_moved['moved_ArchiveBacket'] = $user->movedRequests_ArchiveBacket($request['startdate'], $request['enddate']);
            $data_moved['moved_ArchiveAgent'] = $user->movedRequests_ArchiveAgent($request['startdate'], $request['enddate']);
            $data_moved['moved_Pending'] = $user->movedRequests_Pending($request['startdate'], $request['enddate']);
            $data_moved['moved_Undefined'] = $user->movedRequests_Undefined($request['startdate'], $request['enddate']);

            $data_for_chart[] = $data_moved;
        }

        return view('Charts.movedRequestWithPostiveChart', compact(
            'managers',
            'manager_role',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function qualityTaskChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_task = [];

            $data_task['name'] = $user->name;

            $data_task['answredTask'] = $user->taskTo($request['startdate'], $request['enddate']);
            $data_task['completedTask'] = $user->completedTaskTo($request['startdate'], $request['enddate']);
            $data_task['notcompletedTask'] = $user->notCompletedTask($request['startdate'], $request['enddate']);
            $data_task['avreageTask'] = $this->averageTask($user->id, $request['startdate'], $request['enddate']);

            $total = $data_task['completedTask'] + $data_task['notcompletedTask'];

            //PRESENATG OF AVREAGE TASK
            $getHourOnly = substr($data_task['avreageTask'], 0, strpos($data_task['avreageTask'], ':', 0));

            if ($data_task['answredTask'] != 0) {

                $diff = 0;
                if ($getHourOnly < $data_task['answredTask']) {
                    $diff = $getHourOnly / $data_task['answredTask'];
                }
                else {
                    $diff = $data_task['answredTask'] / $getHourOnly;
                }

                $data_task['presentAverage'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_task['presentAverage'] = '';
            }

            //

            //PRESENTAGE OF COMPLETED TASK
            if ($total != 0) {

                $diff = 0;

                $diff = $data_task['completedTask'] / $total;

                $data_task['presentComplete'] = (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_task['presentComplete'] = '';
            }

            //

            $data_for_chart[] = $data_task;
        }

        //

        return view('Charts.qualityTaskChart', compact(
            'managers',
            'manager_role',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    function averageTask($userID, $startDate, $endDate)
    {
        $allReqs = DB::table('tasks')
            ->leftjoin('users as user', 'user.id', 'tasks.user_id')
            ->where('user.role', 5)
            ->where('tasks.recive_id', $userID)
            ->join('task_contents', 'task_contents.task_id', 'tasks.id');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '<=', $endDate);
        }

        $allReqs = $allReqs->select(DB::raw('(TIME_TO_SEC(TIMEDIFF(date_of_note, date_of_content))) AS day_diff'))
            ->get()
            ->avg('day_diff');

        //dd( $allReqs);

        $avg = gmdate('H:i:s', $allReqs);

        //dd($avg);
        //$avg=round($allReqs);

        return $avg;
    }

    public function qualityTaskChartRForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_task = [];

            $data_task['name'] = $user->name;

            $data_task['answredTask'] = $user->taskTo($request['startdate'], $request['enddate']);
            $data_task['completedTask'] = $user->completedTaskTo($request['startdate'], $request['enddate']);
            $data_task['notcompletedTask'] = $user->notCompletedTask($request['startdate'], $request['enddate']);
            $data_task['avreageTask'] = $this->averageTask($user->id, $request['startdate'], $request['enddate']);

            $total = $data_task['completedTask'] + $data_task['notcompletedTask'];

            //PRESENATG OF AVREAGE TASK
            $getHourOnly = substr($data_task['avreageTask'], 0, strpos($data_task['avreageTask'], ':', 0));

            if ($data_task['answredTask'] != 0) {

                $diff = 0;
                if ($getHourOnly < $data_task['answredTask']) {
                    $diff = $getHourOnly / $data_task['answredTask'];
                }
                else {
                    $diff = $data_task['answredTask'] / $getHourOnly;
                }

                $data_task['presentAverage'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_task['presentAverage'] = '';
            }

            //

            //PRESENTAGE OF COMPLETED TASK
            if ($total != 0) {

                $diff = 0;

                $diff = $data_task['completedTask'] / $total;

                $data_task['presentComplete'] = (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_task['presentComplete'] = '';
            }

            //

            $data_for_chart[] = $data_task;
        }

        //

        return view('Charts.qualityTaskChart', compact(
            'managers',
            'manager_role',
            'advisers',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function qualityServayChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_servay = [];

            $data_servay['name'] = $user->name;

            $data_servay['q1'] = $this->servayQuestion1Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q2'] = $this->servayQuestion2Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q3'] = $this->servayQuestion3Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q4'] = $this->servayQuestion4Result($user->id, $request['startdate'], $request['enddate']);

            $count = 0;
            $result = 0;

            if ($data_servay['q1'] != '') {
                $count++;
                $result = $result + $data_servay['q1'];
            }
            if ($data_servay['q2'] != '') {
                $count++;
                $result = $result + $data_servay['q2'];
            }
            if ($data_servay['q3'] != '') {
                $count++;
                $result = $result + $data_servay['q3'];
            }
            if ($data_servay['q4'] != '') {
                $count++;
                $result = $result + $data_servay['q4'];
            }

            if ($count != 0) {
                $result = $result / $count;
                $result = number_format((float) $result, 2, '.', '');
                $result = (float) $result;
                $data_servay['result'] = $result;
            }
            else {
                $data_servay['result'] = '';
            }

            $data_for_chart[] = $data_servay;
        }

        return view('Charts.qualityServayChart', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'manager_role',
            'data_for_chart',
        ));
    }

    function servayQuestion1Result($userID, $startDate, $endDate)
    {
        $result = '';

        $allReqs = DB::table('quality_reqs')
            ->where('servays.user_id', $userID)
            ->join('servays', 'servays.req_id', 'quality_reqs.id')
            ->join('serv_ques', 'serv_ques.serv_id', 'servays.id')
            ->where('ques_id', 1);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '<=', $endDate);
        }

        $anwserdQuestions = $allReqs->get()->count();
        $yesAnswer = $allReqs->where('serv_ques.answer', 2)->get()->count();

        if ($anwserdQuestions != 0) {
            $result = ($yesAnswer / $anwserdQuestions) * 100;

            $result = number_format((float) $result, 2, '.', '');

            $result = (float) $result;
        }

        return $result;
    }

    function servayQuestion2Result($userID, $startDate, $endDate)
    {
        $result = '';

        $allReqs = DB::table('quality_reqs')
            ->where('servays.user_id', $userID)
            ->join('servays', 'servays.req_id', 'quality_reqs.id')
            ->join('serv_ques', 'serv_ques.serv_id', 'servays.id')
            ->where('ques_id', 2);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '<=', $endDate);
        }

        $anwserdQuestions = $allReqs->get()->count();
        $yesAnswer = $allReqs->where('serv_ques.answer', 2)->get()->count();

        if ($anwserdQuestions != 0) {
            $result = ($yesAnswer / $anwserdQuestions) * 100;
            $result = number_format((float) $result, 2, '.', '');

            $result = (float) $result;
        }

        return $result;
    }

    function servayQuestion3Result($userID, $startDate, $endDate)
    {
        $result = '';

        $allReqs = DB::table('quality_reqs')
            ->where('servays.user_id', $userID)
            ->join('servays', 'servays.req_id', 'quality_reqs.id')
            ->join('serv_ques', 'serv_ques.serv_id', 'servays.id')
            ->where('ques_id', 3);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '<=', $endDate);
        }

        $anwserdQuestions = $allReqs->get()->count();
        $yesAnswer = $allReqs->where('serv_ques.answer', 2)->get()->count();

        if ($anwserdQuestions != 0) {
            $result = ($yesAnswer / $anwserdQuestions) * 100;
            $result = number_format((float) $result, 2, '.', '');

            $result = (float) $result;
        }

        return $result;
    }

    function servayQuestion4Result($userID, $startDate, $endDate)
    {
        $result = '';

        $allReqs = DB::table('quality_reqs')
            ->where('servays.user_id', $userID)
            ->join('servays', 'servays.req_id', 'quality_reqs.id')
            ->join('serv_ques', 'serv_ques.serv_id', 'servays.id')
            ->where('ques_id', 4);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('quality_reqs.created_at', '<=', $endDate);
        }

        $anwserdQuestions = $allReqs->get()->count();
        $yesAnswer = $allReqs->where('serv_ques.answer', 2)->get()->count();

        if ($anwserdQuestions != 0) {
            $result = ($yesAnswer / $anwserdQuestions) * 100;
            $result = number_format((float) $result, 2, '.', '');

            $result = (float) $result;
        }

        return $result;
    }

    public function qualityServayChartRForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_servay = [];

            $data_servay['name'] = $user->name;

            $data_servay['q1'] = $this->servayQuestion1Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q2'] = $this->servayQuestion2Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q3'] = $this->servayQuestion3Result($user->id, $request['startdate'], $request['enddate']);
            $data_servay['q4'] = $this->servayQuestion4Result($user->id, $request['startdate'], $request['enddate']);

            $count = 0;
            $result = 0;

            if ($data_servay['q1'] != '') {
                $count++;
                $result = $result + $data_servay['q1'];
            }
            if ($data_servay['q2'] != '') {
                $count++;
                $result = $result + $data_servay['q2'];
            }
            if ($data_servay['q3'] != '') {
                $count++;
                $result = $result + $data_servay['q3'];
            }
            if ($data_servay['q4'] != '') {
                $count++;
                $result = $result + $data_servay['q4'];
            }

            if ($count != 0) {
                $result = $result / $count;
                $result = number_format((float) $result, 2, '.', '');
                $result = (float) $result;
                $data_servay['result'] = $result;
            }
            else {
                $data_servay['result'] = '';
            }

            $data_for_chart[] = $data_servay;
        }

        return view('Charts.qualityServayChart', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'advisers',
            'manager_role',
            'data_for_chart',
        ));
    }

    public function updateRequestChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {

            $data_update = [];

            $data_update['name'] = $user->name;

            $data_update['avgvalue'] = $user->updateRequest($request['startdate'], $request['enddate']);

            $getHourOnly = substr($data_update['avgvalue'], 0, strpos($data_update['avgvalue'], ':', 0));

            $movedReq = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $noReqs = $user->noRequest($request['startdate'], $request['enddate']);

            $data_update['noReqs'] = $noReqs;

            $data_update['updateReqs'] = $this->updateReqs($user->id, $request['startdate'], $request['enddate']);

            #UPDATE REQS PRESENTAGE
            if ($data_update['noReqs'] != 0) {

                $pres = $data_update['updateReqs'] / $data_update['noReqs'];

                $data_update['updateReqpresent'] = (number_format((float) $pres, 2, '.', '') * 100);
            }
            else {
                $data_update['updateReqpresent'] = '';
            }

            #AVG OF UPDATE REQS PRESENTAGE
            if ($data_update['updateReqs'] != 0) {

                $diff = 0;
                if ($getHourOnly < $data_update['updateReqs']) {
                    $diff = $getHourOnly / $data_update['updateReqs'];
                }
                else {
                    $diff = $data_update['updateReqs'] / $getHourOnly;
                }

                $data_update['avgpresent'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['avgpresent'] = '';
            }

            if ($data_update['avgpresent'] != '' && $data_update['updateReqpresent'] != '') {
                $data_update['present'] = 100 * ((($data_update['avgpresent'] / 100) + ($data_update['updateReqpresent'] / 100)) / 2);
            }
            else {
                $data_update['present'] = '';
            }

            $data_for_chart[] = $data_update;
        }

        return view('Charts.updateRequestChart', compact(
            'managers',
            'manager_ids',
            'manager_role',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    function updateReqs($userID, $startDate, $endDate)
    {

        $allReqs = DB::table('requests');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        $allReqs = $allReqs
            ->where('user_id', $userID)
            ->get()->pluck('id');

        $count = DB::table('req_records')
            ->where('user_id', $userID)
            ->whereIn('req_id', $allReqs)
            ->get()->unique('req_id')->count();

        return $count;
    }

    public function updateRequestChartRForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_update = [];

            $data_update['name'] = $user->name;

            $data_update['avgvalue'] = $user->updateRequest($request['startdate'], $request['enddate']);

            $getHourOnly = substr($data_update['avgvalue'], 0, strpos($data_update['avgvalue'], ':', 0));

            $movedReq = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $noReqs = $user->noRequest($request['startdate'], $request['enddate']);

            if ($noReqs != 0) {

                $diff = 0;
                if ($getHourOnly < $noReqs) {
                    $diff = $getHourOnly / $noReqs;
                }
                else {
                    $diff = $noReqs / $getHourOnly;
                }

                $data_update['present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['present'] = '';
            }

            $data_for_chart[] = $data_update;
        }

        return view('Charts.updateRequestChart', compact(
            'managers',
            'manager_ids',
            'advisers',
            'manager_role',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function finalResultChartR(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_update = [];

            $data_update['name'] = $user->name;

            ////////////////////////////////////////////////////////

            $data_update['q1'] = $this->servayQuestion1Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q2'] = $this->servayQuestion2Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q3'] = $this->servayQuestion3Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q4'] = $this->servayQuestion4Result($user->id, $request['startdate'], $request['enddate']);

            $count = 0;
            $result = 0;

            if ($data_update['q1'] != '') {
                $count++;
                $result = $result + $data_update['q1'];
            }
            if ($data_update['q2'] != '') {
                $count++;
                $result = $result + $data_update['q2'];
            }
            if ($data_update['q3'] != '') {
                $count++;
                $result = $result + $data_update['q3'];
            }
            if ($data_update['q4'] != '') {
                $count++;
                $result = $result + $data_update['q4'];
            }

            if ($count != 0) {
                $result = $result / $count;
                $result = number_format((float) $result, 2, '.', '');
                $result = (float) $result;
                $data_update['servayResult'] = $result;
            }
            else {
                $data_update['servayResult'] = '';
            }

            ////////////////////////////////////////////////////////

            $avgValue = $user->updateRequest($request['startdate'], $request['enddate']);

            $getHourOnly = substr($avgValue, 0, strpos($avgValue, ':', 0));

            $movedReq = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $noReqs = $user->noRequest($request['startdate'], $request['enddate']);

            $updateReqs = $this->updateReqs($user->id, $request['startdate'], $request['enddate']);

            #UPDATE REQS PRESENTAGE
            if ($noReqs != 0) {

                $pres = $updateReqs / $noReqs;

                $data_update['updateReqpresent'] = (number_format((float) $pres, 2, '.', '') * 100);
            }
            else {
                $data_update['updateReqpresent'] = '';
            }

            #AVG OF UPDATE REQS PRESENTAGE
            if ($updateReqs != 0) {

                $diff = 0;
                if ($getHourOnly < $updateReqs) {
                    $diff = $getHourOnly / $updateReqs;
                }
                else {
                    $diff = $updateReqs / $getHourOnly;
                }

                $data_update['avgpresent'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['avgpresent'] = '';
            }

            if ($data_update['avgpresent'] != '' && $data_update['updateReqpresent'] != '') {
                $data_update['updateReq_present'] = 100 * ((($data_update['avgpresent'] / 100) + ($data_update['updateReqpresent'] / 100)) / 2);
            }
            else {
                $data_update['updateReq_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $avreageTask = $this->averageTask($user->id, $request['startdate'], $request['enddate']);
            $completedTask = $user->completedTaskTo($request['startdate'], $request['enddate']);
            $notcompletedTask = $user->notCompletedTask($request['startdate'], $request['enddate']);
            $getHourOnly = substr($avreageTask, 0, strpos($avreageTask, ':', 0));

            $noTask = $user->taskTo($request['startdate'], $request['enddate']);
            $totalTask = $completedTask + $notcompletedTask;

            if ($noTask != 0) {

                $diff = 0;

                if ($getHourOnly < $noTask) {
                    $diff = $getHourOnly / $noTask;
                }
                else {
                    $diff = $noTask / $getHourOnly;
                }

                $data_update['updateTask_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['updateTask_present'] = '';
            }

            if ($totalTask != 0) {

                $diff = 0;

                $diff = $completedTask / $totalTask;

                $data_update['completeTask_present'] = (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['completeTask_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $movedFrom = $user->movedRequestsFrom($request['startdate'], $request['enddate']);
            $movedTo = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $diff = 0;

            if ($movedFrom < $movedTo) {
                if ($movedTo != 0) {
                    $diff = $movedFrom / $movedTo;
                }
                else {
                    $diff = 0;
                }

                $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                if ($movedFrom != 0 && $movedTo != 0) {
                    $diff = $movedTo / $movedFrom;
                    $data_update['move_present'] = (number_format((float) $diff, 2, '.', '') * 100);
                }
                elseif ($movedFrom != 0 && $movedTo == 0) {
                    $diff = 1;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
                else {
                    $diff = 0;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
            }

            ////////////////////////////////////////////////////////

            /*
            $diff = $movedTo / $this->allMovedRequestsTo($request['startdate'], $request['enddate']);

            $data_update['noReqs'] = number_format((float) $diff, 2, '.', '') * 100;
            */

            ////////////////////////////////////////////////////////

            $count2 = 0;
            $finalResult = 0;

            if ($data_update['move_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['move_present'] / 100);
            }
            if ($data_update['updateTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateTask_present'] / 100);
            }
            if ($data_update['completeTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['completeTask_present'] / 100);
            }
            if ($data_update['updateReq_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateReq_present'] / 100);
            }
            if ($data_update['servayResult'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['servayResult'] / 100);
            }

            //dd($user->id,($data_update['servayResult'] ),($data_update['updateReq_present']),($data_update['completeTask_present'] ),($data_update['updateTask_present']),($data_update['move_present']) );

            if ($count2 != 0) {
                $finalResult = $finalResult / $count2;
                $data_update['finalResult'] = number_format((float) $finalResult, 2, '.', '') * 100;
            }
            else {
                $data_update['finalResult'] = '';
            }

            ///////////////////////////////////////////////////////

            $data_for_chart[] = $data_update;
        }

        return view('Charts.finalResultChart', compact(
            'managers',
            'manager_ids',
            'adviser_ids',
            'manager_role',
            'data_for_chart',
        ));
    }

    public function finalResultChartForAgent(Req $request)
    {

        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $all_users = User::all()->where('status', 1);
        $users = $all_users->where('id', auth()->user()->id);

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_update = [];

            $data_update['name'] = $user->name;

            ////////////////////////////////////////////////////////

            $data_update['q1'] = $this->servayQuestion1Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q2'] = $this->servayQuestion2Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q3'] = $this->servayQuestion3Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q4'] = $this->servayQuestion4Result($user->id, $request['startdate'], $request['enddate']);

            $count = 0;
            $result = 0;

            if ($data_update['q1'] != '') {
                $count++;
                $result = $result + $data_update['q1'];
            }
            if ($data_update['q2'] != '') {
                $count++;
                $result = $result + $data_update['q2'];
            }
            if ($data_update['q3'] != '') {
                $count++;
                $result = $result + $data_update['q3'];
            }
            if ($data_update['q4'] != '') {
                $count++;
                $result = $result + $data_update['q4'];
            }

            if ($count != 0) {
                $result = $result / $count;
                $result = number_format((float) $result, 2, '.', '');
                $result = (float) $result;
                $data_update['servayResult'] = $result;
            }
            else {
                $data_update['servayResult'] = '';
            }

            ////////////////////////////////////////////////////////

            $avgValue = $user->updateRequest($request['startdate'], $request['enddate']);

            $getHourOnly = substr($avgValue, 0, strpos($avgValue, ':', 0));

            $movedReq = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $noReqs = $user->noRequest($request['startdate'], $request['enddate']);

            $updateReqs = $this->updateReqs($user->id, $request['startdate'], $request['enddate']);

            #UPDATE REQS PRESENTAGE
            if ($noReqs != 0) {

                $pres = $updateReqs / $noReqs;

                $data_update['updateReqpresent'] = (number_format((float) $pres, 2, '.', '') * 100);
            }
            else {
                $data_update['updateReqpresent'] = '';
            }

            #AVG OF UPDATE REQS PRESENTAGE
            if ($updateReqs != 0) {

                $diff = 0;
                if ($getHourOnly < $updateReqs) {
                    $diff = $getHourOnly / $updateReqs;
                }
                else {
                    $diff = $updateReqs / $getHourOnly;
                }

                $data_update['avgpresent'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['avgpresent'] = '';
            }

            if ($data_update['avgpresent'] != '' && $data_update['updateReqpresent'] != '') {
                $data_update['updateReq_present'] = 100 * ((($data_update['avgpresent'] / 100) + ($data_update['updateReqpresent'] / 100)) / 2);
            }
            else {
                $data_update['updateReq_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $avreageTask = $this->averageTask($user->id, $request['startdate'], $request['enddate']);
            $completedTask = $user->completedTaskTo($request['startdate'], $request['enddate']);
            $notcompletedTask = $user->notCompletedTask($request['startdate'], $request['enddate']);
            $getHourOnly = substr($avreageTask, 0, strpos($avreageTask, ':', 0));

            $noTask = $user->taskTo($request['startdate'], $request['enddate']);

            $totalTask = $completedTask + $notcompletedTask;

            if ($noTask != 0) {

                $diff = 0;

                if ($getHourOnly < $noTask) {
                    $diff = $getHourOnly / $noTask;
                }
                else {
                    $diff = $noTask / $getHourOnly;
                }

                $data_update['updateTask_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['updateTask_present'] = '';
            }

            if ($totalTask != 0) {

                $diff = 0;

                $diff = $completedTask / $totalTask;

                $data_update['completeTask_present'] = (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['completeTask_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $movedFrom = $user->movedRequestsFrom($request['startdate'], $request['enddate']);
            $movedTo = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $diff = 0;

            if ($movedFrom < $movedTo) {
                if ($movedTo != 0) {
                    $diff = $movedFrom / $movedTo;
                }
                else {
                    $diff = 0;
                }

                $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                if ($movedFrom != 0 && $movedTo != 0) {
                    $diff = $movedTo / $movedFrom;
                    $data_update['move_present'] = (number_format((float) $diff, 2, '.', '') * 100);
                }
                elseif ($movedFrom != 0 && $movedTo == 0) {
                    $diff = 1;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
                else {
                    $diff = 0;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
            }

            ////////////////////////////////////////////////////////

            /*
            $diff = $movedTo / $this->allMovedRequestsTo($request['startdate'], $request['enddate']);

            $data_update['noReqs'] = number_format((float) $diff, 2, '.', '') * 100;
            */

            ////////////////////////////////////////////////////////

            $count2 = 0;
            $finalResult = 0;

            if ($data_update['move_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['move_present'] / 100);
            }
            if ($data_update['updateTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateTask_present'] / 100);
            }
            if ($data_update['completeTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['completeTask_present'] / 100);
            }
            if ($data_update['updateReq_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateReq_present'] / 100);
            }
            if ($data_update['servayResult'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['servayResult'] / 100);
            }

            if ($count2 != 0) {
                $finalResult = $finalResult / $count2;
                $data_update['finalResult'] = number_format((float) $finalResult, 2, '.', '') * 100;
            }
            else {
                $data_update['finalResult'] = '';
            }

            ///////////////////////////////////////////////////////

            $data_for_chart[] = $data_update;
        }

        return view('Charts.finalResultChart', compact(

            'manager_role',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function finalResultChartForSalesManager(Req $request)
    {

        $all_users = User::all()->where('status', 1);
        $managers = $all_users->where('role', '1')->where('status', 1);
        /// start default data
        $manager_ids = [auth()->user()->id];
        $manager_role = auth()->user()->role;

        $adviser_ids = $request['adviser_id'] ?: [0];
        $advisers = $all_users->whereIn('manager_id', $manager_ids);

        if (in_array(0, $adviser_ids)) {
            $users = $all_users->whereIn('manager_id', $manager_ids);
        }
        else {
            $users = $all_users->whereIn('id', $adviser_ids);
        }

        $data_for_chart = [];

        foreach ($users as $user) {
            $data_update = [];

            $data_update['name'] = $user->name;

            ////////////////////////////////////////////////////////

            $data_update['q1'] = $this->servayQuestion1Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q2'] = $this->servayQuestion2Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q3'] = $this->servayQuestion3Result($user->id, $request['startdate'], $request['enddate']);
            $data_update['q4'] = $this->servayQuestion4Result($user->id, $request['startdate'], $request['enddate']);

            $count = 0;
            $result = 0;

            if ($data_update['q1'] != '') {
                $count++;
                $result = $result + $data_update['q1'];
            }
            if ($data_update['q2'] != '') {
                $count++;
                $result = $result + $data_update['q2'];
            }
            if ($data_update['q3'] != '') {
                $count++;
                $result = $result + $data_update['q3'];
            }
            if ($data_update['q4'] != '') {
                $count++;
                $result = $result + $data_update['q4'];
            }

            if ($count != 0) {
                $result = $result / $count;
                $result = number_format((float) $result, 2, '.', '');
                $result = (float) $result;
                $data_update['servayResult'] = $result;
            }
            else {
                $data_update['servayResult'] = '';
            }

            ////////////////////////////////////////////////////////

            $avgValue = $user->updateRequest($request['startdate'], $request['enddate']);

            $getHourOnly = substr($avgValue, 0, strpos($avgValue, ':', 0));

            $movedReq = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $noReqs = $user->noRequest($request['startdate'], $request['enddate']);

            $updateReqs = $this->updateReqs($user->id, $request['startdate'], $request['enddate']);

            #UPDATE REQS PRESENTAGE
            if ($noReqs != 0) {

                $pres = $updateReqs / $noReqs;

                $data_update['updateReqpresent'] = (number_format((float) $pres, 2, '.', '') * 100);
            }
            else {
                $data_update['updateReqpresent'] = '';
            }

            #AVG OF UPDATE REQS PRESENTAGE
            if ($updateReqs != 0) {

                $diff = 0;
                if ($getHourOnly < $updateReqs) {
                    $diff = $getHourOnly / $updateReqs;
                }
                else {
                    $diff = $updateReqs / $getHourOnly;
                }

                $data_update['avgpresent'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['avgpresent'] = '';
            }

            if ($data_update['avgpresent'] != '' && $data_update['updateReqpresent'] != '') {
                $data_update['updateReq_present'] = 100 * ((($data_update['avgpresent'] / 100) + ($data_update['updateReqpresent'] / 100)) / 2);
            }
            else {
                $data_update['updateReq_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $avreageTask = $this->averageTask($user->id, $request['startdate'], $request['enddate']);
            $completedTask = $user->completedTaskTo($request['startdate'], $request['enddate']);
            $notcompletedTask = $user->notCompletedTask($request['startdate'], $request['enddate']);
            $getHourOnly = substr($avreageTask, 0, strpos($avreageTask, ':', 0));

            $noTask = $user->taskTo($request['startdate'], $request['enddate']);

            $totalTask = $completedTask + $notcompletedTask;

            if ($noTask != 0) {

                $diff = 0;

                if ($getHourOnly < $noTask) {
                    $diff = $getHourOnly / $noTask;
                }
                else {
                    $diff = $noTask / $getHourOnly;
                }

                $data_update['updateTask_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['updateTask_present'] = '';
            }

            if ($totalTask != 0) {

                $diff = 0;

                $diff = $completedTask / $totalTask;

                $data_update['completeTask_present'] = (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                $data_update['completeTask_present'] = '';
            }

            ////////////////////////////////////////////////////////

            $movedFrom = $user->movedRequestsFrom($request['startdate'], $request['enddate']);
            $movedTo = $user->movedRequestsTo($request['startdate'], $request['enddate']);

            $diff = 0;

            if ($movedFrom < $movedTo) {
                if ($movedTo != 0) {
                    $diff = $movedFrom / $movedTo;
                }
                else {
                    $diff = 0;
                }

                $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
            }
            else {
                if ($movedFrom != 0 && $movedTo != 0) {
                    $diff = $movedTo / $movedFrom;
                    $data_update['move_present'] = (number_format((float) $diff, 2, '.', '') * 100);
                }
                elseif ($movedFrom != 0 && $movedTo == 0) {
                    $diff = 1;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
                else {
                    $diff = 0;
                    $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
                }
            }

            ////////////////////////////////////////////////////////

            /*
            $diff = $movedTo / $this->allMovedRequestsTo($request['startdate'], $request['enddate']);

            $data_update['noReqs'] = number_format((float) $diff, 2, '.', '') * 100;
            */

            ////////////////////////////////////////////////////////

            $count2 = 0;
            $finalResult = 0;

            if ($data_update['move_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['move_present'] / 100);
            }
            if ($data_update['updateTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateTask_present'] / 100);
            }
            if ($data_update['completeTask_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['completeTask_present'] / 100);
            }
            if ($data_update['updateReq_present'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['updateReq_present'] / 100);
            }
            if ($data_update['servayResult'] != '') {
                $count2++;
                $finalResult = $finalResult + ($data_update['servayResult'] / 100);
            }

            if ($count2 != 0) {
                $finalResult = $finalResult / $count2;
                $data_update['finalResult'] = number_format((float) $finalResult, 2, '.', '') * 100;
            }
            else {
                $data_update['finalResult'] = '';
            }

            ///////////////////////////////////////////////////////

            $data_for_chart[] = $data_update;
        }

        return view('Charts.finalResultChart', compact(
            'managers',
            'manager_ids',
            'manager_role',
            'adviser_ids',
            'advisers',
            'data_for_chart',
        ));
    }

    public function otaredUpdateChartR(Req $request)
    {

        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];

        $manager_role = auth()->user()->role;

        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $data_for_chart = [];

        $start = Carbon::parse(date('Y-m-d', strtotime('-30 day')));
        $end = Carbon::parse(date('Y-m-d'));

        if ($request['startdate'] == null || $request['enddate'] == null) {
            $start = Carbon::parse(date('Y-m-d', strtotime('-30 day')));
            $end = Carbon::parse(date('Y-m-d'));
        }
        else {
            $start = $request['startdate'];
            $end = $request['enddate'];
        }

        $requests = $this->getRecords($start, $end);

        foreach ($users as $user) {
            $data_otared = [];

            $getUserRecords = clone $requests;
            $getUserRecords = $this->joinWithReqs($user->id, $getUserRecords);

            $data_otared['name'] = $user->name;

            $data_otared['otaredReqs'] = $user->otaredRequest($start, $end);
            //

            //
            $request = clone $getUserRecords;
            $data_otared['funding_source'] = $this->calculateChange($request, 'funding_source');
            $request = clone $getUserRecords;
            $data_otared['fundDur'] = $this->calculateChange($request, 'fundDur');
            $request = clone $getUserRecords;
            $data_otared['fundPersPre'] = $this->calculateChange($request, 'fundPersPre');
            $request = clone $getUserRecords;
            $data_otared['fundPers'] = $this->calculateChange($request, 'fundPers');
            $request = clone $getUserRecords;
            $data_otared['fundRealPre'] = $this->calculateChange($request, 'fundRealPre');
            $request = clone $getUserRecords;
            $data_otared['fundReal'] = $this->calculateChange($request, 'fundReal');
            $request = clone $getUserRecords;
            $data_otared['fundDed'] = $this->calculateChange($request, 'fundDed');
            $request = clone $getUserRecords;
            $data_otared['fundMonth'] = $this->calculateChange($request, 'fundMonth');
            //

            //
            $request = clone $getUserRecords;
            $data_otared['customerName'] = $this->calculateChange($request, 'customerName');
            $request = clone $getUserRecords;
            $data_otared['birth_hijri'] = $this->calculateChange($request, 'birth_hijri');
            $request = clone $getUserRecords;
            $data_otared['work'] = $this->calculateChange($request, 'work');
            $request = clone $getUserRecords;
            $data_otared['salary'] = $this->calculateChange($request, 'salary');
            $request = clone $getUserRecords;
            $data_otared['support'] = $this->calculateChange($request, 'support');
            $request = clone $getUserRecords;
            $data_otared['obligations'] = $this->calculateChange($request, 'obligations');
            $request = clone $getUserRecords;
            $data_otared['distress'] = $this->calculateChange($request, 'distress');
            $request = clone $getUserRecords;
            $data_otared['salary_source'] = $this->calculateChange($request, 'salary_source');
            $request = clone $getUserRecords;
            $data_otared['askaryWork'] = $this->calculateChange($request, 'askaryWork');
            $request = clone $getUserRecords;
            $data_otared['madanyWork'] = $this->calculateChange($request, 'madanyWork');
            $request = clone $getUserRecords;
            $data_otared['rank'] = $this->calculateChange($request, 'rank');
            //

            //
            $request = clone $getUserRecords;
            $data_otared['realName'] = $this->calculateChange($request, 'realName');
            $request = clone $getUserRecords;
            $data_otared['realMobile'] = $this->calculateChange($request, 'realMobile');
            $request = clone $getUserRecords;
            $data_otared['realCity'] = $this->calculateChange($request, 'realCity');
            $request = clone $getUserRecords;
            $data_otared['realRegion'] = $this->calculateChange($request, 'realRegion');
            $request = clone $getUserRecords;
            $data_otared['realPursuit'] = $this->calculateChange($request, 'realPursuit');
            $request = clone $getUserRecords;
            $data_otared['realAge'] = $this->calculateChange($request, 'realAge');
            $request = clone $getUserRecords;
            $data_otared['realStatus'] = $this->calculateChange($request, 'realStatus');
            $request = clone $getUserRecords;
            $data_otared['realCost'] = $this->calculateChange($request, 'realCost');
            $request = clone $getUserRecords;
            $data_otared['realType'] = $this->calculateChange($request, 'realType');
            //

            //
            $request = clone $getUserRecords;
            $data_otared['jointName'] = $this->calculateChange($request, 'jointName');
            $request = clone $getUserRecords;
            $data_otared['jointMobile'] = $this->calculateChange($request, 'jointMobile');
            $request = clone $getUserRecords;
            $data_otared['jointSalary'] = $this->calculateChange($request, 'jointSalary');
            $request = clone $getUserRecords;
            $data_otared['jointBirth_higri'] = $this->calculateChange($request, 'jointBirth_higri');
            $request = clone $getUserRecords;
            $data_otared['jointWork'] = $this->calculateChange($request, 'jointWork');
            $request = clone $getUserRecords;
            $data_otared['jointJobTitle'] = $this->calculateChange($request, 'jointJobTitle');
            //

            $data_for_chart[] = $data_otared;
        }

        return view('Charts.otaredUpdateChart', compact(
            'managers',
            'manager_role',
            'manager_ids',
            'adviser_ids',
            'data_for_chart',
        ));
    }

    public function getRecords($startDate, $endDate)
    {

        $requests = DB::table('req_records')
            ->where('req_records.user_id', 17)
            ->join('requests', 'requests.id', 'req_records.req_id');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $requests = $requests->whereDate('updateValue_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $requests = $requests->whereDate('updateValue_at', '<=', $endDate);
        }

        return $requests;
    }

    public function joinWithReqs($userID, $requests)
    {

        $requests = $requests
            ->where('requests.user_id', $userID);

        return $requests;
    }

    public function calculateChange($requests, $value)
    {

        $count = 0;

        $requestss = $requests
            ->where('req_records.colum', $value)
            ->select('requests.id')
            ->get();

        //dd( $requestss);

        foreach ($requestss as $request) {

            $records = DB::table('req_records')
                ->where('req_records.colum', $value)
                ->where('req_id', $request->id)
                ->get();

            if ($records->count() > 1) {
                $count++;
            }
        }

        return $count;
    }

    function requestChartTrainingApi(Req $request){
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }

        $usersArr =auth()->user()->trainings->pluck('agent_id')->toArray();
        $users =User::whereIn('id',$usersArr)->when($request->status_user!=2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->where('role', 0)->get();

        return response()->json(['success' => true, 'users' => $users]);
    }
    function requestChartRApi(Req $request)
    {

        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }
        if ($request->has("managerId")){
            if (in_array('allManager', $request->managerId)) {
                $request->merge([
                    "managerId"   => 0
                ]);
            }
        }
        $users = User::when($request->status_user!=2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->
        when($request->managerId != 0, function ($q, $v) use ($request) {
            $q->whereIn('manager_id', $request->managerId);
        })->where('role', 0)->get();

        return response()->json(['success' => true, 'users' => $users]);
    }
    function requestUsersApi(Req $request)
    {

        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }
        if (is_numeric(array_search(1,$request->roles))){
            $request->merge([
                "roles"   => [0]
            ]);
        }


        $users = User::when($request->status_user!=2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->when($request->has("managerId"), function ($q, $v) use ($request) {
            $q->whereIn('manager_id', $request->managerId);
        })->when($request->has("roles"), function ($q, $v) use ($request) {
            $q->whereIn('role', $request->roles);
        })->get();


        return response()->json(['success' => true, 'users' => $users]);
    }

    function requestRoleApi(Req $request)
    {

        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }
        if($request->roles){
            if (is_numeric(array_search(1,$request->roles))){
                $request->merge([
                    "roles"   =>  [0]
                ]);
            }

        }
        $users = User::where('status', 1)->
        when($request->has("roles"), function ($q, $v) use ($request) {
            $q->whereIn('role', $request->roles);
        })->get();

        return response()->json(['success' => true, 'users' => $users]);
    }

    function requestChartRApiQuality(Req $request)
    {

        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }

        $users = User::when($request->status_user!=2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->where('role', 5)->get();

        return response()->json(['success' => true, 'users' => $users]);
    }



    function requestChartRApiForManager(Req $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 1,
            ]);
        }
        $users = User::where('manager_id', auth()->id())->
        when($request->status_user!=2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->get();

        return Response::json(['success' => true, 'users' => $users]);
    }

    function servayResult($userID)
    {
        $result = 0;

        $allReqs = DB::table('servays')
            ->where('servays.user_id', $userID)
            ->join('serv_ques', 'serv_ques.serv_id', 'servays.id');

        $anwserdQuestions = $allReqs->get()->count();
        $yesAnswer = $allReqs->where('serv_ques.answer', 2)->get()->count();

        if ($anwserdQuestions != 0) {
            $result = ($yesAnswer / $anwserdQuestions) * 100;
        }

        $result = number_format((float) $result, 2, '.', '');

        $result = (float) $result;

        return $result;
    }

    function averageUpdate($userID)
    {
        $allReqs = DB::table('requests')
            ->where('requests.user_id', $userID)
            ->join('req_records', 'req_records.req_id', 'requests.id');

        $allReqs = $allReqs->select(DB::raw('(TIME_TO_SEC(TIMEDIFF(date_of_note, date_of_content))) AS day_diff'))
            ->get()
            ->avg('day_diff');

        //dd( $allReqs);

        $avg = gmdate('H:i:s', $allReqs);

        //dd($avg);
        //$avg=round($allReqs);

        return $avg;
    }

    public function allMovedRequestsTo($startDate, $endDate)
    {

        $allReqs = DB::table('request_histories')
            ->where('title', 'نقل الطلب');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function user($userId)
    {
        $user = User::find($userId);
        //OVERALL
        $moved = $user->request_histories_from()
            ->join('requests', 'requests.id', 'request_histories.req_id')
            ->where('title', 'نقل الطلب')
            ->where('statusReq', '>', 2)
            ->whereNotIn('statusReq', [16, 26])
            ->where('requests.class_id_agent', '!=', 58)
            ->distinct('requests.id')->pluck('requests.id')->toArray();

        $completed = $user->request_histories_from()
            ->join('requests', 'requests.id', 'request_histories.req_id')
            ->where('title', 'نقل الطلب')
            ->where(function ($query) {
                $query->whereIn('statusReq', [16, 26])
                    ->orWhere('requests.class_id_agent', 58);
            })->distinct('requests.id')->pluck('requests.id')->toArray();

        return view('Charts.users.index', [
            'completed' => request::whereIn('id', $completed)->get(),
            'moved'     => request::whereIn('id', $moved)->get(),
            'user'      => $user,
        ]);
    }
    public function request_single($requestId)
    {
        $histories = DB::table('request_histories')
            ->where('req_id', $requestId)
            ->where('request_histories.title', 'نقل الطلب')
            ->orderBy('request_histories.created_at')
            ->get();

        $request = request::find($requestId);

        return view('Charts.users.single', [
            'histories' => $histories,
            'requests'  => $request,
            'count'     => $histories->count() - 1,
        ]);
    }
    /**********************************************/
    //             Request Sources
    /**********************************************/
    public function sources_for_wsata()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $sources = RequestSource::all();
        $collaborators = User::where(["status" => 1,"role" => 6,"subdomain" => "report"])->get();
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.sources.sources-wsata',[
            "users" => $users,
            "sources" => $sources,
            "collaborators" => $collaborators,
            "managers" => $managers,
        ]);
    }
    public function sources_for_requests()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $sources = RequestSource::all();
        $collaborators = User::where(["status" => 1,"role" => 6,"subdomain" => "report"])->get();
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.sources.sources-pendings',[
            "users" => $users,
            "sources" => $sources,
            "collaborators" => $collaborators,
            "managers" => $managers,
        ]);
    }
    public function sources()
    {
        ini_set('memory_limit', '-1');
        $userModel = User::where('status', 1);
        $sources = RequestSource::all();
        $collaborators = User::where(["status" => 1,"role" => 6,"subdomain" => "report"])->get();
        $managers = clone ($userModel)->where('role', '1')->get();
        $users = clone ($userModel)->where('role', 0);
        return view('Charts.sources.sources',[
            "users" => $users,
            "sources" => $sources,
            "collaborators" => $collaborators,
            "maxVal"     =>number_format((strtotime(now())-strtotime("2022-04-02")) / (60*60*24),0),
            "managers" => $managers,
        ]);
    }
    public function measurement_tools()
    {
        ini_set('memory_limit', '-1');
        // $userModel = User::where('status', 1);
        // $sources = RequestSource::all();
        // $collaborators = User::where(["status" => 1,"role" => 6,"subdomain" => "report"])->get();
        // $managers = clone ($userModel)->where('role', '1')->get();
        // $users = clone ($userModel)->where('role', 0);
        $classifications = Classification::whereIn('id', [62])->get();
        return view('Charts.measurement_tools',[
            // "users" => $users,
            // "sources" => $sources,
            // "collaborators" => $collaborators,
            // "managers" => $managers,
            "classifications" => $classifications,
        ]);
    }
}
