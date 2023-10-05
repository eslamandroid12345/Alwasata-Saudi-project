<?php

namespace App\Http\Controllers;

use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\funding_source;
use App\salary_source;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class SalesManagerController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }

    /////////////////////NOTIFCATIONES////////////////////////

    public function agentmanager(Request $request)
    {
        $managerID = (auth()->user()->id);

        $myusers = DB::table('users')->where('manager_id', $managerID)
            ->where('status', 1) // active users only
            ->get();
        //  dd( $myusers);

        $requests = [];

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                $requests[] = DB::table('requests')->where('requests.user_id', $myuser->id)
                    ->join('users', 'users.id', '=', 'requests.user_id')
                    ->get();
            }

            //dd($requests);

            $check = 0; // check if this user is belong for at lest one user (sales agent)
            return view('SalesManager.Customer.agentManager', compact('requests', 'check'));
        }

        $check = 1; // sales manager not belong with any user (sales agent)
        return view('SalesManager.Customer.agentManager', compact('requests', 'check'));
    }

    public function agentcustomer($id)
    {
        $managerID = (auth()->user()->id);
        $myuser = DB::table('users')->where('manager_id', $managerID)
            ->where('id', $id)
            ->first();

        if (!empty($myuser)) {

            $requests = DB::table('requests')->where('requests.user_id', $id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
                ->get();

            // dd( $requests);

            return view('SalesManager.Customer.agentCustomer', compact('requests', 'id'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function agentcustomer_datatable(Request $request)
    {

        $requests = DB::table('requests')->where('requests.user_id', $request->id)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->get();

        return Datatables::of($requests)->addColumn('status', function ($row) {
            switch ($row->statusReq) {
                case 0:
                    $status = 'new req';
                    break;
                case 1:
                    $status = 'open req';
                    break;
                case 2:
                    $status = 'archive in sales agent req';
                    break;
                case 3:
                    $status = 'wating sales manager req';
                    break;
                case 4:
                    $status = 'rejected sales manager req';
                    break;
                case 5:
                    $status = 'archive in sales manager req';
                    break;
                case 6:
                    $status = 'wating funding manager req';
                    break;
                case 7:
                    $status = 'rejected funding manager req';
                    break;
                case 8:
                    $status = 'archive in funding manager req';
                    break;
                case 9:
                    $status = 'wating mortgage manager req';
                    break;
                case 10:
                    $status = 'rejected mortgage manager req';
                    break;
                case 11:
                    $status = 'archive in mortgage manager req';
                    break;
                case 12:
                    $status = 'wating general manager req';
                    break;
                case 13:
                    $status = 'rejected general manager req';
                    break;
                case 14:
                    $status = 'archive in general manager req';
                    break;
                case 15:
                    $status = 'Canceled';
                    break;
                case 16:
                    $status = 'Completed';
                    break;
                case 17:
                    $status = 'draft in mortgage maanger';
                    break;
                case 18:
                    $status = 'wating sales manager req';
                    break;
                case 19:
                    $status = 'wating sales agent req';
                    break;
                case 20:
                    $status = 'rejected sales manager req';
                    break;
                case 21:
                    $status = 'wating funding manager req';
                    break;
                case 22:
                    $status = 'rejected funding manager req';
                    break;
                case 23:
                    $status = 'wating general manager req';
                    break;
                case 24:
                    $status = 'cancel mortgage manager req';
                    break;
                case 25:
                    $status = 'rejected general manager req';
                    break;
                case 26:
                    $status = 'Completed';
                    break;
                case 27:
                    $status = 'Canceled';
                    break;
                default:
                    $status = 'Undefined';
                    break;
            }
            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->make(true);
    }

    /////////////////////////////////////////////////

    public function dailyreq()
    {
        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $salesAgents)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.sales_manager_id', $managerID)
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();
        $request_sources = DB::table('request_source')->get();
        $worke_sources = WorkSource::all();

        return view('SalesManager.Customer.dailyReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'request_sources',
            'worke_sources',
        ));
    }

    public function status($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->user()->id, 'new req'),
            1  => MyHelpers::admin_trans(auth()->user()->id, 'open req'),
            2  => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales agent req'),
            3  => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            4  => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            //5 => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales manager req'),
            5  => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            6  => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            7  => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->user()->id, 'archive in funding manager req'),
            8  => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            9  => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            // 11 => MyHelpers::admin_trans(auth()->user()->id, 'archive in mortgage manager req'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            12 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            13 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            //14 => MyHelpers::admin_trans(auth()->user()->id, 'archive in general manager req'),
            14 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            15 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            16 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            17 => MyHelpers::admin_trans(auth()->user()->id, 'draft in mortgage maanger'),
            18 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            19 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales agent req'),
            20 => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            21 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            22 => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            23 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            24 => MyHelpers::admin_trans(auth()->user()->id, 'cancel mortgage manager req'),
            25 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            26 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            27 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            28 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),
            29 => MyHelpers::admin_trans(auth()->user()->id, 'Rejected and archived'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }

    /////////////////////////////////////////////

    public function statusPay($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->user()->id, 'draft in funding manager'),
            1  => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales maanger'),
            2  => MyHelpers::admin_trans(auth()->user()->id, 'funding manager canceled'),
            3  => MyHelpers::admin_trans(auth()->user()->id, 'rejected from sales maanger'),
            4  => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales agent'),
            5  => MyHelpers::admin_trans(auth()->user()->id, 'wating for mortgage maanger'),
            6  => MyHelpers::admin_trans(auth()->user()->id, 'rejected from mortgage maanger'),
            7  => MyHelpers::admin_trans(auth()->user()->id, 'approve from mortgage maanger'),
            8  => MyHelpers::admin_trans(auth()->user()->id, 'mortgage manager canceled'),
            9  => MyHelpers::admin_trans(auth()->user()->id, 'The prepayment is completed'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected from funding manager'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),

        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }

    ////////////////////////////////////////

    public function dailyreq_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function homePage()
    {
        return view('SalesManager.home.home');
    }

    public function myReqs(Request $request)
    {
        //dd("tes 12");
        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            //->whereIn('statusReq', [3,7,10]) //wating for sales manager
            ->where('requests.isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('requests.sales_manager_id', $managerID)
            ->where('isSentSalesManager', 1) //yes sent
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.myReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'pay_status',
            'salesAgents',
            'collaborators',
            'worke_sources',
            'request_sources'
        ));
    }

    public function myReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('requests.isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';

            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function purReqs(Request $request)
    {

        $managerID = (auth()->user()->id);
        //$myusers = DB::table('users')->where('manager_id',  $managerID)->get()->toArray();
        //  $requests = array();
        $all = [];

        $managerID = (auth()->user()->id);
        //$myusers = DB::table('users')->where('manager_id',  $managerID)->get();

        $customers = [];

        //$salesAgents = User::where('manager_id', auth()->user()->id)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        //dd( $salesAgents);
        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'شراء')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $requests_for_customer = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'شراء')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customers.id');

        $customers = customer::whereIn('id', $requests_for_customer)->get();

        $salesAgents = User::where('manager_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.purReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'classifcations_fm',
            'classifcations_gm',
            'classifcations_mm',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function purReqs_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'شراء')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name')
            ->orderBy('req_date', 'DESC');

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                    <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {

            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->make(true);
    }

    public function morReqs(Request $request)
    {
        $managerID = (auth()->user()->id);
        //$myusers = DB::table('users')->where('manager_id',  $managerID)->get()->toArray();
        //  $requests = array();
        //$all = [];

        $managerID = (auth()->user()->id);
        //$myusers = DB::table('users')->where('manager_id',  $managerID)->get();
        $customers = [];

        $salesAgents = User::where('manager_id', auth()->user()->id)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        //dd( $salesAgents);
        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'رهن')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $requests_for_customer = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'رهن')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customers.id');

        // dd($requests);

        $customers = customer::whereIn('id', $requests_for_customer)->get();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.morReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'classifcations_fm',
            'classifcations_gm',
            'classifcations_mm',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function morReqs_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('type', 'رهن')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name')
            ->orderBy('req_date', 'DESC');

        //        dd($requests->count());

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                    <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {

            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->make(true);
    }

    public function recivedReqs(Request $request)
    {

        $managerID = (auth()->user()->id);
        //dd($managerID);
        $salesAgents = User::where('manager_id', $managerID)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [3, 7, 10]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [18, 22]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [1, 6]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('requests.id', 'DESC')
            ->count();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.recivedReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function recivedReqs_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);
        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $salesAgents)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [3, 7, 10]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [18, 22]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [1, 6]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('requests.id', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function followReqs(Request $request)
    {

        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            // /->whereIn('requests.user_id',   $salesAgents)
            //->whereIn('statusReq', [3,7,10]) //wating for sales manager
            //  ->where('requests.req_date',   $todayDate)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.is_followed', 1)
            ->where('is_canceled', 0)
            ->where('is_stared', 0)
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.followReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function followReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.is_followed', 1)
            ->where('is_canceled', 0)
            ->where('is_stared', 0)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function starReqs(Request $request)
    {
        $managerID = (auth()->user()->id);

        $salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.is_stared', 1)
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.staredReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function starReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.is_stared', 1)
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function agentCompletedReqs(Request $request)
    {

        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',   $salesAgents)
            //->whereIn('statusReq', [3,7,10]) //wating for sales manager
            //  ->where('requests.req_date',   $todayDate)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                    $query->whereIn('type', ['شراء-دفعة']);
                    $query->whereNotIn('prepayments.payStatus', [4, 3]);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                    $query->whereIn('type', ['رهن', 'شراء']);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('prepayments.isSentSalesAgent', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.agentComReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function agentCompletedReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                    $query->whereIn('type', ['شراء-دفعة']);
                    $query->whereNotIn('prepayments.payStatus', [4, 3]);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                    $query->whereIn('type', ['رهن', 'شراء']);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('prepayments.isSentSalesAgent', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function agentRecivedReqs(Request $request)
    {
        $managerID = (auth()->user()->id);

        $salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',   $salesAgents)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [0, 1, 4]);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 19);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesAgent', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [4, 3]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesAgent', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.agentRecivedReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function agentRecivedReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [0, 1, 4]);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 19);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesAgent', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [4, 3]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesAgent', 1);
                });
            })
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function agentArchReqs(Request $request)
    {
        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('statusReq', 2) //archived in sales agent
            ->select('users.name as user_name', 'users.id as user_id', 'customers.name as customer_name', 'requests.*', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.agentArchReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function agentArchReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')->whereIn('requests.user_id', $salesAgents)
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('statusReq', 2) //archived in sales agent
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></button>';
            }
            else {
                $data = $data.'<button class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></button>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])
            ->make(true);
    }

    public function canceledReqs(Request $request)
    {
        $managerID = (auth()->user()->id);
        // $myusers = DB::table('users')->where('manager_id',  $managerID)->get();
        // dd( $myusers);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating
                ->orWhere('statusReq', 7) //rejected funding
                ->orWhere('statusReq', 10); //rejected mortgage
            })
            ->where('is_canceled', 1)
            ->where('requests.sales_manager_id', $managerID)
            ->where('isSentSalesManager', 1) //yes sent
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->get();

        return view('SalesManager.Request.canceledReqs', compact('requests'));
    }

    //This new function to show dataTabel in view(SalesManager.Request.canceledReqs)
    public function canceledReqs_datatable()
    {

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->whereIn('statusReq', [3, 7, 10]) //(3:wating , 7:rejected funding , 10:rejected mortgage)
            ->where('is_canceled', 1)
            ->where('requests.sales_manager_id', $managerID)
            ->where('isSentSalesManager', 1) //yes sent
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC');

        return Datatables::of($requests)->addColumn('status', function ($row) {
            switch ($row->statusReq) {
                case 0:
                    $status = 'new req';
                    break;
                case 1:
                    $status = 'open req';
                    break;
                case 2:
                    $status = 'archive in sales agent req';
                    break;
                case 3:
                    $status = 'wating sales manager req';
                    break;
                case 4:
                    $status = 'rejected sales manager req';
                    break;
                case 5:
                    $status = 'archive in sales manager req';
                    break;
                case 6:
                    $status = 'wating funding manager req';
                    break;
                case 7:
                    $status = 'rejected funding manager req';
                    break;
                case 8:
                    $status = 'archive in funding manager req';
                    break;
                case 9:
                    $status = 'wating mortgage manager req';
                    break;
                case 10:
                    $status = 'rejected mortgage manager req';
                    break;
                case 11:
                    $status = 'archive in mortgage manager req';
                    break;
                case 12:
                    $status = 'wating general manager req';
                    break;
                case 13:
                    $status = 'rejected general manager req';
                    break;
                case 14:
                    $status = 'archive in general manager req';
                    break;
                case 15:
                    $status = 'Canceled';
                    break;
                case 16:
                    $status = 'Completed';
                    break;
                case 17:
                    $status = 'draft in mortgage maanger';
                    break;
                case 18:
                    $status = 'wating sales manager req';
                    break;
                case 19:
                    $status = 'wating sales agent req';
                    break;
                case 20:
                    $status = 'rejected sales manager req';
                    break;
                case 21:
                    $status = 'wating funding manager req';
                    break;
                case 22:
                    $status = 'rejected funding manager req';
                    break;
                case 23:
                    $status = 'wating general manager req';
                    break;
                case 24:
                    $status = 'cancel mortgage manager req';
                    break;
                case 25:
                    $status = 'rejected general manager req';
                    break;
                case 26:
                    $status = 'Completed';
                    break;
                case 27:
                    $status = 'Canceled';
                    break;
                default:
                    $status = 'Undefined';
                    break;
            }
            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                         <a href="'.route('sales.manager.morPurRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                                     </span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                         <a href="'.route('sales.manager.fundingRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                                     </span>';
            }

            $data = $data.'<span class="item pointer" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                     <a href="'.route('sales.manager.restoreRequest', $row->id).'"> <i class="fas fa-reply"></i></a>
                                 </span>';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function completedReqs(Request $request)
    {

        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();
        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)

            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [3, 7, 10, 5]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->whereNotIn('prepayments.payStatus', [1, 6]);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [3, 7, 10, 5]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [18, 22]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [1, 6]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.completedReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function completedReqs_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);
        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $salesAgents)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [3, 7, 10, 5]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->whereNotIn('prepayments.payStatus', [1, 6]);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [3, 7, 10, 5]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [18, 22]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [1, 6]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type != 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->make(true);
    }

    public function manageReq($id, $action)
    {

        $userID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('requests.id', $id)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('users.manager_id', $userID)
            ->first();

        //dd($restRequest);

        if (!empty($restRequest)) {
            $restRequest = DB::table('requests')->where('requests.id', $id)
                ->update(['is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //set request

            $restRequest = DB::table('requests')->where('id', $id)
                ->update(['is_'.$action => 1]);

            if ($restRequest == 1) {
                return redirect()->route('sales.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function morPurReqs(Request $request)
    {

        //get notificationes

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where('type', 'رهن-شراء')
            ->where('isSentSalesManager', 1)
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->get();

        return view('SalesManager.Request.morPurReqs', compact('requests'));
    }

    public function morPurReqs_datatable()
    {
        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where('type', 'رهن-شراء')
            ->where('isSentSalesManager', 1)
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC');

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->addColumn('status', function ($row) {
            switch ($row->statusReq) {
                case 0:
                    $status = 'new req';
                    break;
                case 1:
                    $status = 'open req';
                    break;
                case 2:
                    $status = 'archive in sales agent req';
                    break;
                case 3:
                    $status = 'wating sales manager req';
                    break;
                case 4:
                    $status = 'rejected sales manager req';
                    break;
                case 5:
                    $status = 'archive in sales manager req';
                    break;
                case 6:
                    $status = 'wating funding manager req';
                    break;
                case 7:
                    $status = 'rejected funding manager req';
                    break;
                case 8:
                    $status = 'archive in funding manager req';
                    break;
                case 9:
                    $status = 'wating mortgage manager req';
                    break;
                case 10:
                    $status = 'rejected mortgage manager req';
                    break;
                case 11:
                    $status = 'archive in mortgage manager req';
                    break;
                case 12:
                    $status = 'wating general manager req';
                    break;
                case 13:
                    $status = 'rejected general manager req';
                    break;
                case 14:
                    $status = 'archive in general manager req';
                    break;
                case 15:
                    $status = 'Canceled';
                    break;
                case 16:
                    $status = 'Completed';
                    break;
                case 17:
                    $status = 'draft in mortgage maanger';
                    break;
                case 18:
                    $status = 'wating sales manager req';
                    break;
                case 19:
                    $status = 'wating sales agent req';
                    break;
                case 20:
                    $status = 'rejected sales manager req';
                    break;
                case 21:
                    $status = 'wating funding manager req';
                    break;
                case 22:
                    $status = 'rejected funding manager req';
                    break;
                case 23:
                    $status = 'wating general manager req';
                    break;
                case 24:
                    $status = 'cancel mortgage manager req';
                    break;
                case 25:
                    $status = 'rejected general manager req';
                    break;
                case 26:
                    $status = 'Completed';
                    break;
                case 27:
                    $status = 'Canceled';
                    break;
                default:
                    $status = 'Undefined';
                    break;
            }
            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->make(true);
    }

    public function archReqs(Request $request)
    {
        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('statusReq', 5) //archived in sales manager
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.sales_manager_id', $managerID)
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name')
            ->orderBy('req_date', 'DESC')->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.archReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    //This new function to show dataTabel in view(SalesManager.Request.archReqs)
    public function archReqs_datatable(Request $request)
    {

        $managerID = (auth()->user()->id);
        // $salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('statusReq', 5) //archived in sales manager
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('requests.sales_manager_id', $managerID)
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name')
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  data-id="'.$row->id.'"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                    <a href="'.route('sales.manager.restoreRequest', $row->id).'"><i class="fas fa-reply"></i></a>
                                </span> ';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                        <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                        <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->make(true);
    }

    public function rejReqs(Request $request)
    {

        $managerID = (auth()->user()->id);

        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [7, 10]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 22);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('prepayments.payStatus', 6);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $salesAgents)
            ->where('isSentSalesManager', 1) //yes sent
            ->where('requests.sales_manager_id', $managerID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $salesAgents = User::where('manager_id', $managerID)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('SalesManager.Request.rejReqs', compact(
            'requests',
            'customers',
            'classifcations_sm',
            'classifcations_sa',
            'all_status',
            'all_salaries',
            'founding_sources',
            'collaborators',
            'pay_status',
            'salesAgents',
            'worke_sources',
            'request_sources'
        ));
    }

    public function rejReqs_datatable(Request $request)
    {
        $managerID = (auth()->user()->id);
        //$salesAgents = User::where('manager_id', $managerID)->pluck('id');

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $salesAgents)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [7, 10]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 22);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentSalesManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('prepayments.payStatus', 6);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesManager', 1);
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->get();

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')
                ->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'fundings.funding_source')->get()
                ->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_sm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_sm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_sm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->make(true);
    }

    public function prepaymentReqs()
    {

        //get notificationes

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.isSentSalesManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $check = 1; // sales manager not belong with any user
        return view('SalesManager.Request.prepayment', compact('requests', 'check'));
    }

    public function prepaymentReqs_datatable()
    {

        $managerID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',  $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.isSentSalesManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.sales_manager_id', $managerID)
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('sales.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->addColumn('source', function ($row) {
            $data = $row->source;
            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $row->source.' - '.$collInfo->name;
                }
                else {
                    $data = $row->source;
                }
            }
            return $data;
        })->addColumn('status', function ($row) {

            switch ($row->payStatus) {
                case 0:
                    $status = 'draft in funding manager';
                    break;
                case 1:
                    $status = 'wating for sales maanger';
                    break;
                case 2:
                    $status = 'funding manager canceled';
                    break;
                case 3:
                    $status = 'rejected from sales maanger';
                    break;
                case 4:
                    $status = 'wating for sales agent';
                    break;
                case 5:
                    $status = 'wating for mortgage maanger';
                    break;
                case 6:
                    $status = 'rejected from mortgage maanger';
                    break;
                case 7:
                    $status = 'approve from mortgage maanger';
                    break;
                case 8:
                    $status = 'mortgage manager canceled';
                    break;
                case 9:
                    $status = 'The prepayment is completed';
                    break;
                case 10:
                    $status = 'rejected from funding manager';
                    break;
                default:
                    $status = 'Undefined';
                    break;
            }

            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->make(true);
    }

    public function updatePage($id)
    {

        $managerID = (auth()->user()->id);
        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $checkManager = $request->sales_manager_id == $managerID;

        $payment = DB::table('prepayments')->where('id', '=', $request->payment_id)
            ->where('isSentSalesManager', 1)
            ->first();

        //get notificationes

        // dd($payment);
        if ($checkManager) {
            if (!empty($payment)) {

                $purchaseReal = DB::table('requests')
                    ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                return view('SalesManager.prepayement.updatePage', compact('id', 'request', 'payment', 'purchaseReal'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Payment not created for this request'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function rejectPrepay(Request $request)
    {

        $managerID = (auth()->user()->id);
        $request1 = DB::table('requests')->where('requests.id', '=', $request->id)->first();

        $checkManager = $request1->sales_manager_id == $managerID;

        $userInfo = DB::table('users')->where('id', $request1->user_id)
            ->first();

        $payment = DB::table('prepayments')->where('id', '=', $request1->payment_id)
            ->where('isSentSalesManager', 1)
            ->first();

        if ($checkManager) {
            if (!empty($payment)) {

                $rejectPay = DB::table('prepayments')->where('id', '=', $request1->payment_id)
                    ->whereIn('payStatus', [1, 6]) // wating for sales manager approval
                    ->update(['payStatus' => 3, 'isSentSalesAgent' => 1]); //rejected from sales manager

                if ($rejectPay == 1) { //rejected sucessfully

                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Rejected'), $userInfo->id, $request->comment);

                    /*  DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Rejected'), 'user_id' => (auth()->user()->id), 'recive_id' => $userInfo->id,
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);
                    */
                    return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Rejected Successfully'), 'status' => $rejectPay, 'id' => $request->id]);
                }
                else {
                    return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $rejectPay, 'id' => $request->id]);
                }
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function history($reqID, $title, $recived_id, $comment)
    {

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        DB::table('request_histories')->insert([ // add to request history
                                                 'title'          => $title,
                                                 'user_id'        => (auth()->user()->id),
                                                 'recive_id'      => $recived_id,
                                                 'history_date'   => (Carbon::now('Asia/Riyadh')),
                                                 'content'        => $comment,
                                                 'req_id'         => $reqID,
                                                 'user_switch_id' => $userSwitch,
        ]);

        //  dd($rowOfLastUpdate);
    }

    public function updatePre(Request $request)
    {

        $request1 = DB::table('requests')->where('requests.id', '=', $request->reqID)->first();

        $reqID = $request->reqID;

        //
        $real = $request->real;
        $incr = $request->incr;
        $preval = $request->preval;
        $prepre = $request->prepre;
        $precos = $request->precos;
        $net = $request->net;
        $deficit = $request->deficit;

        $visa = $request->visa;
        $carlo = $request->carlo;
        $perlo = $request->perlo;
        $realo = $request->realo;
        $credban = $request->credban;
        $other = $request->other;
        $debt = $request->debt;
        $morpre = $request->morpre;
        $morcos = $request->morcos;
        $propre = $request->propre;
        $procos = $request->procos;
        $valadd = $request->valadd;
        $admfe = $request->admfe;
        //

        //

        $this->records($reqID, 'realCost', $request->real);
        $this->records($reqID, 'incValue', $request->incr);
        $this->records($reqID, 'preValue', $request->preval);
        $this->records($reqID, 'prePresent', $request->prepre);
        $this->records($reqID, 'preCost', $request->precos);
        $this->records($reqID, 'netCust', $request->net);
        $this->records($reqID, 'deficitCust', $request->deficit);

        if ($request->visa != 0) {
            $this->records($reqID, 'preVisa', $request->visa);
        }

        if ($request->carlo != 0) {
            $this->records($reqID, 'carLo', $request->carlo);
        }

        if ($request->perlo != 0) {
            $this->records($reqID, 'personalLo', $request->perlo);
        }

        if ($request->realo != 0) {
            $this->records($reqID, 'realLo', $request->realo);
        }

        if ($request->credban != 0) {
            $this->records($reqID, 'credBank', $request->credban);
        }

        if ($request->other != 0) {
            $this->records($reqID, 'otherLo', $request->other);
        }

        $this->records($reqID, 'morPresnt', $request->morpre);
        $this->records($reqID, 'mortCost', $request->mortCost);
        $this->records($reqID, 'pursitPresnt', $request->propre);
        $this->records($reqID, 'profCost', $request->procos);
        $this->records($reqID, 'addedValue', $request->valadd);
        $this->records($reqID, 'adminFees', $request->admfe);

        //

        if ($request1->type == 'رهن-شراء') {

            $request1 = DB::table('requests')->where('requests.req_id', '=', $request->reqID)->first(); // to get mor-ppur info

            $payupdate = DB::table('prepayments')->where('id', $request1->payment_id)
                ->update([
                    'realCost'        => $real,
                    'incValue'        => $incr,
                    'prepaymentVal'   => $preval,
                    'prepaymentPre'   => $prepre,
                    'prepaymentCos'   => $precos,
                    'visa'            => $visa,
                    'carLo'           => $carlo,
                    'personalLo'      => $perlo,
                    'realLo'          => $realo,
                    'credit'          => $credban,
                    'netCustomer'     => $net,
                    'other'           => $other,
                    'debt'            => $debt,
                    'mortPre'         => $morpre,
                    'mortCost'        => $morcos,
                    'proftPre'        => $propre,
                    'deficitCustomer' => $deficit,
                    'profCost'        => $procos,
                    'addedVal'        => $valadd,
                    'adminFee'        => $admfe,
                    'req_id'          => $reqID,
                ]);
        }
        else {
            $payupdate = DB::table('prepayments')->where('id', $request1->payment_id)
                ->update([
                    'realCost'        => $real,
                    'incValue'        => $incr,
                    'prepaymentVal'   => $preval,
                    'prepaymentPre'   => $prepre,
                    'prepaymentCos'   => $precos,
                    'visa'            => $visa,
                    'carLo'           => $carlo,
                    'personalLo'      => $perlo,
                    'realLo'          => $realo,
                    'credit'          => $credban,
                    'netCustomer'     => $net,
                    'other'           => $other,
                    'debt'            => $debt,
                    'mortPre'         => $morpre,
                    'mortCost'        => $morcos,
                    'proftPre'        => $propre,
                    'deficitCustomer' => $deficit,
                    'profCost'        => $procos,
                    'addedVal'        => $valadd,
                    'adminFee'        => $admfe,
                    'req_id'          => $reqID,
                ]);
        }

        //

        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $payupdate, 'id' => $reqID]);
    }

    public function records($reqID, $coloum, $value)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')
            ->where('req_id', '=', $reqID)
            ->where('colum', '=', $coloum)
            ->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => (auth()->user()->id),
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => $userSwitch,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->user()->id),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function sendPre(Request $request)
    {

        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $userInfo = DB::table('users')->where('id', $userID)
            ->first();

        $checkManager = $restRequest->sales_manager_id == $managerID;

        if ($checkManager) {

            $sendTo = $request->sendTo;

            if ($sendTo == 'mortgage') {
                $sendPay = DB::table('prepayments')->where('id', $restRequest->payment_id)
                    ->whereIn('payStatus', [1, 6]) // wating sales manager approval
                    ->where('isSentSalesManager', 1)
                    ->update(['payStatus' => 5, 'isSentMortgageManager' => 1]); //wating for mortgage approval

                if ($sendPay == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $mortgageManager = $restRequest->mortgage_manager_id;
                    if ($mortgageManager == null) {
                        $updateMortgageManager = MyHelpers::mortgageManagerRequestProcess($request->id);
                        $mortgageManager = MyHelpers::getMortgageManagerRequest($request->id);
                    }
                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), $mortgageManager, $request->comment);

                    /*   DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractMortgage($request->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);

                    */

                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $mortgageManager,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                }
            }

            if ($sendTo == 'agent') {
                $sendPay = DB::table('prepayments')->where('id', $restRequest->payment_id)
                    ->whereIn('payStatus', [1, 6]) // wating sales manager approval
                    ->where('isSentSalesManager', 1)
                    ->update(['payStatus' => 4, 'isSentSalesAgent' => 1]); //wating for agent approval

                if ($sendPay == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), $userInfo->id, $request->comment);

                    /*     DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => $userInfo->id,
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);

                */

                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $userInfo->id,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);

                    if ($restRequest->type != 'رهن-شراء') {
                        $reqType = 'fundingreqpage';
                    }
                    else {
                        $reqType = 'morPurRequest';
                    }

                    //$pwaPush = MyHelpers::pushPWA($userInfo->id, ' يومك سعيد  ' . $userInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', $reqType, $request->id);
                }
            }

            if ($sendPay == 0) //nothing send

            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            else {
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function restReq(Request $request, $id)
    {

        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $id)->first();
        $userID = $restRequest->user_id;

        $checkManager = $restRequest->sales_manager_id == $managerID;

        if ($checkManager) {

            $restRequest = DB::table('requests')->where('id', $id)
                ->where(function ($query) {
                    $query->where('statusReq', 5) //archive request in sales manager
                    ->orWhere('is_canceled', 1)
                        ->orwhere('is_stared', 1)
                        ->orwhere('is_followed', 1);
                })
                ->update(['statusReq' => 3, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for sales manager approval

            if ($restRequest == 0) // not updated
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            if ($restRequest == 1) // updated sucessfully
            {
                return redirect()->route('sales.manager.archRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Restore sucessfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function rejectReq(Request $request)
    {
        // dd("22122333");
        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $checkManager = $restRequest->sales_manager_id == $managerID;

        if ($checkManager) {

            $restRequest1 = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 3) //wating for sales manager approval
                    ->orWhere('statusReq', 5) //following in salesmanager
                    ->orWhere('statusReq', 7) //rejected  from funding manager
                    ->orWhere('statusReq', 10); //rejected  from mortgage manager
                })
                ->update(['statusReq' => 4, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'class_id_agent' => 1]); //reject from sales manager

            DB::table('req_records')->insert([
                'colum'          => 'class_agent',
                'user_id'        => null,
                //'value'          => 'يحتاج متابعة',
                'value'          =>1,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $request->id,
                'user_switch_id' => null,
                'comment'        => 'تلقائي - عن طريق النظام',
            ]);

            if ($restRequest1 == 1) { //rejected sucessfully

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'rejected'), $userID, $request->comment);

                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $userID,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                if ($restRequest->type != 'رهن-شراء') {
                    $reqType = 'fundingreqpage';
                }
                else {
                    $reqType = 'morPurRequest';
                }

                //$pwaPush = MyHelpers::pushPWA($userID, ' يومك سعيد  ' , 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', $reqType, $request->id);

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 4, $restRequest->user_id, $restRequest->class_id_sm);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::

                /*    DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'rejected'), 'user_id' => (auth()->user()->id), 'recive_id' => $checkManager->id,
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $request->id,
                ]);

            */

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Rejecting successfully'), 'status' => $restRequest1, 'id' => $request->id]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $restRequest1, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function fundingreqpage(Request $request, $id)
    {

        $managerID = auth()->user()->id;
        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $checkManager = $request->sales_manager_id == $managerID;

        if ($checkManager) { // check if the request belong to sales agent or not

            $purchaseCustomer = DB::table('requests')
                ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('requests.id', '=', $id)
                ->first();

            // dd($purchaseCustomer);

            $reqStatus = $request->statusReq;

            $purchaseJoint = DB::table('requests')
                ->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')
                ->where('requests.id', '=', $id)
                ->first();

            $purchaseReal = DB::table('requests')
                ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->where('requests.id', '=', $id)
                ->first();

            $purchaseFun = DB::table('requests')
                ->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')
                ->where('requests.id', '=', $id)
                ->first();

            $purchaseClass = DB::table('requests')
                ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_sm')
                ->where('requests.id', '=', $id)
                ->first();

            $purchaseTsa = DB::table('requests')
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('requests.id', '=', $id)
                ->first();

            $collaborator = DB::table('requests')
                ->join('users', 'users.id', '=', 'requests.collaborator_id')
                ->where('requests.id', '=', $id)
                ->first();

            //dd($purchaseTsa);

            $payment = null;

            if ($request->type == 'رهن-شراء') {
                $payment = DB::table('prepayments')->where('id', '=', $request->payment_id)->first();
            }
            elseif ($request->type == 'رهن') {
                $payment = DB::table('prepayments')->where('id', '=', $request->payment_id)->first();
            }

            else {
                $payment = DB::table('prepayments')->where('id', '=', $request->payment_id)
                    ->where(function ($query) {
                        $query->where('payStatus', 1) //wating sales maanger approval
                        ->orWhere('isSentSalesManager', 1); //yes sent to sales manager
                    })
                    ->first();
            }

            if ($request->type == 'شراء-دفعة' && $payment == null) {
                $paymentForDisplayonly = DB::table('prepayments')->where('id', '=', $request->payment_id)
                    ->first();
            }
            else {
                $paymentForDisplayonly = null;
            }

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $user_role = DB::table('users')->select('role')->where('id', $managerID)->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

            /*$histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

            $documents = DB::table('documents')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                ->select('documents.*', 'users.name')
                ->get();

            //get notificationes

            $followdate = DB::table('notifications')->where('req_id', '=', $id)
                ->where('recived_id', '=', (auth()->user()->id))
                ->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()
                ->last(); //to get last reminder

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();

            // dd( $reqStatus);
            return view('SalesManager.fundingReq.fundingreqpage', compact(
                'purchaseCustomer',
                'purchaseJoint',
                'purchaseReal',
                'purchaseFun',
                'purchaseClass',
                'purchaseTsa',
                'salary_sources',
                'funding_sources',
                'askary_works',
                'madany_works',
                'classifcations',
                'id', //Request ID
                //  'histories',
                'documents',
                'reqStatus',
                'payment',

                'followdate',
                'collaborator',
                'cities',
                'ranks',
                'paymentForDisplayonly',
                'followtime',
                'realTypes',
                'worke_sources',
                'request_sources'
            ));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function sendFunding(Request $request)
    {
        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $checkManager = $restRequest->sales_manager_id == $managerID;

        if ($checkManager) {
            $updateFundingManager = MyHelpers::fundingManagerRequestProcess($request->id);
            $restRequest = DB::table('requests')->where('id', $request->id)->first();
            $fundingManager = $restRequest->funding_manager_id;

            if ($restRequest->type == 'شراء' || $restRequest->type == 'شراء-دفعة') {

                $sendRequest = DB::table('requests')->where('id', $request->id)
                    ->where(function ($query) {
                        $query->where('statusReq', 3) //wating for sales manager approval
                        ->orWhere('statusReq', 5) //following in salesmanager
                        ->orWhere('statusReq', 7) //rejected  from funding manager
                        ->orWhere('statusReq', 10); //rejected  from mortgage manager
                    })
                    ->update(['statusReq' => 6, 'isSentFundingManager' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for funding manager approval

                if ($sendRequest == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent'), $restRequest->funding_manager_id, $request->comment);

                    //for quality intent::::::::::::::::

                    if (MyHelpers::checkQualityReq($request->id)) {
                        $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 6, $restRequest->user_id, $restRequest->class_id_sm);
                    }

                    //end quality :::::::::::::::::::::::::::::::::::::::

                    /*     DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractFunding($request->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);

                */

                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $fundingManager,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                    //
                }
            }

            if ($restRequest->type == 'رهن') {
                $updateMortgageManager = MyHelpers::mortgageManagerRequestProcess($request->id);
                $restRequest = DB::table('requests')->where('id', $request->id)->first();
                $mortgageManager = $restRequest->mortgage_manager_id;

                $sendRequest = DB::table('requests')->where('id', $request->id)
                    ->where(function ($query) {
                        $query->where('statusReq', 3) //wating for sales manager approval
                        ->orWhere('statusReq', 5) //following in salesmanager
                        ->orWhere('statusReq', 7) //rejected  from funding manager
                        ->orWhere('statusReq', 10); //rejected  from mortgage manager
                    })
                    ->update(['statusReq' => 9, 'isSentMortgageManager' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for mortgage manager approval

                if ($sendRequest == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent'), $mortgageManager, $request->comment);

                    /*   DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractMortgage($request->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);
                    */

                    //for quality intent::::::::::::::::

                    if (MyHelpers::checkQualityReq($request->id)) {
                        $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 9, $restRequest->user_id, $restRequest->class_id_sm);
                    }

                    //end quality :::::::::::::::::::::::::::::::::::::::

                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $mortgageManager,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                }
            }

            if ($sendRequest == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
            }

            else {

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $sendRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
        }
    }

    public function updatefunding(Request $request)
    {
        //dd(123666);
        $rules = [
            //   'name' => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            //     'jointmobile'=> 'regex:/^(05)[0-9]{8}$/',
            // 'sex' => 'required',
            //   'birth' => 'required',
            //   'work' => 'required',
            //   'salary_source' => 'required',
            //   'salary' => 'numeric',
        ];

        $customMessages = [
            'mobile.regex'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.digits'   => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            // 'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //   'jointmobile.regex' => 'Should start with 05 ',
            //  'birth.required' => 'The birth date filed is required ',
        ];

        $this->validate($request, $rules, $customMessages);

        $managerID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $request->reqID)->first();

        $checkManager = $request1->sales_manager_id == $managerID;

        if ($checkManager) {
            //REQUEST
            $reqID = $request->reqID; //request id for update
            $fundingReq = DB::table('requests')->where('id', $reqID)
                ->where(function ($query) {
                    $query->where('statusReq', 3) //wating for sales manager approval
                    ->orWhere('statusReq', 5) //following in salesmanager
                    ->orWhere('statusReq', 7) //rejected  from funding manager
                    ->orWhere('statusReq', 10) //rejected  from mortgage manager
                    ->orWhere('statusReq', 18) // mor-pur wating for sales manager
                    ->orWhere('statusReq', 22); // mor-pur rejected from funding manager and redirect to sales manager
                })
                ->first();
            //

            if (!empty($fundingReq)) {

                //JOINT
                $jointId = $fundingReq->joint_id;
                //

                //CUSTOMER
                $customerId = $fundingReq->customer_id;
                $customerInfo = DB::table('customers')->where('id', '=', $customerId)->first();
                //

                //FUNDING INFO
                $fundingId = $fundingReq->fun_id;
                //

                //REAL ESTAT
                $realId = $fundingReq->real_id;
                //

                //CLASSIFICATION
                $classId = $fundingReq->class_id_sm;
                //

                /*
                $checkmobile = DB::table('customers')->where('mobile', $request->mobile)->first();
                $checkmobile2 = CustomersPhone::where('mobile', $request->mobile)->first();
                */

                //if ((empty($checkmobile2) && empty($checkmobile)) ||  $customerInfo->mobile == $request->mobile) {

                if ($request->name == null) {
                    $request->name = 'بدون اسم';
                }

                $this->records($reqID, 'customerName', $request->name);
                //$this->records($reqID, 'mobile', $request->mobile);
                $this->records($reqID, 'sex', $request->sex);
                $this->records($reqID, 'birth_date', $request->birth);
                $this->records($reqID, 'birth_hijri', $request->birth_hijri);
                $this->records($reqID, 'salary', $request->salary);
                if ($request->is_support != null) {
                    if ($request->is_support == 'no') {
                        $this->records($reqID, 'support', 'لا');
                    }
                    if ($request->is_support == 'yes') {
                        $this->records($reqID, 'support', 'نعم');
                    }
                }

                if ($request->has_obligations != null) {
                    if ($request->has_obligations == 'no') {
                        $this->records($reqID, 'obligations', 'لا');
                    }
                    if ($request->has_obligations == 'yes') {
                        $this->records($reqID, 'obligations', 'نعم');
                    }
                }

                if ($request->has_financial_distress != null) {
                    if ($request->has_financial_distress == 'no') {
                        $this->records($reqID, 'distress', 'لا');
                    }
                    if ($request->has_financial_distress == 'yes') {
                        $this->records($reqID, 'distress', 'نعم');
                    }
                }

                $this->records($reqID, 'obligations_value', $request->obligations_value);
                $this->records($reqID, 'financial_distress_value', $request->financial_distress_value);

                $this->records($reqID, 'jobTitle', $request->job_title);

                $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'work', $getworkValue->value);
                }

                $getsalaryValue = DB::table('salary_sources')->where('id', $request->salary_source)->first();
                if (!empty($getsalaryValue)) {
                    $this->records($reqID, 'salary_source', $getsalaryValue->value);
                }

                $getaskaryValue = DB::table('askary_works')->where('id', $request->askary_work)->first();
                if (!empty($getaskaryValue)) {
                    $this->records($reqID, 'askaryWork', $getaskaryValue->value);
                }

                $getmadanyValue = DB::table('madany_works')->where('id', $request->madany_work)->first();
                if (!empty($getmadanyValue)) {
                    $this->records($reqID, 'madanyWork', $getmadanyValue->value);
                }
                $getrankValue = DB::table('military_ranks')->where('id', $request->rank)->first();
                if (!empty($getrankValue)) {
                    $this->records($reqID, 'rank', $getrankValue->value);
                }

                $updateResult = DB::table('customers')->where([
                    ['id', '=', $customerId],
                ])->update([
                    'name'                     => $request->name,
                    //'mobile' => $request->mobile,
                    'sex'                      => $request->sex,
                    'birth_date'               => $request->birth,
                    'birth_date_higri'         => $request->birth_hijri,
                    'age'                      => $request->age,
                    'work'                     => $request->work,
                    'madany_id'                => $request->madany_work,
                    'job_title'                => $request->job_title,
                    'askary_id'                => $request->askary_work,
                    'military_rank'            => $request->rank,
                    'salary_id'                => $request->salary_source,
                    'salary'                   => $request->salary,
                    'is_supported'             => $request->is_support,
                    'has_obligations'          => $request->has_obligations,
                    'obligations_value'        => $request->obligations_value,
                    'has_financial_distress'   => $request->has_financial_distress,
                    'financial_distress_value' => $request->financial_distress_value,
                ]);

                //
                $name = $request->jointname;
                $mobile = $request->jointmobile;
                $birth = $request->jointbirth;
                $birth_higri = $request->jointbirth_hijri;
                $age = $request->jointage;
                $work = $request->jointwork;
                $salary = $request->jointsalary;
                $salary_source = $request->jointsalary_source;
                $rank = $request->jointrank;
                $madany = $request->jointmadany_work;
                $job_title = $request->jointjob_title;
                $askary_work = $request->jointaskary_work;
                $jointfunding_source = $request->jointfunding_source;

                $this->records($reqID, 'jointName', $request->jointname);
                $this->records($reqID, 'jointMobile', $request->jointmobile);
                $this->records($reqID, 'jointSalary', $request->jointsalary);
                $this->records($reqID, 'jointBirth', $request->jointbirth);
                $this->records($reqID, 'jointBirth_higri', $request->jointbirth_hijri);
                $this->records($reqID, 'jointJobTitle', $job_title);

                $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'jointWork', $getworkValue->value);
                }

                $getjointfundingValue = DB::table('funding_sources')->where('id', $request->jointfunding_source)->first();
                if (!empty($getjointfundingValue)) {
                    $this->records($reqID, 'jointfunding_source', $getjointfundingValue->value);
                }

                $getjointsalaryValue = DB::table('salary_sources')->where('id', $request->jointsalary_source)->first();
                if (!empty($getjointsalaryValue)) {
                    $this->records($reqID, 'jointsalary_source', $getjointsalaryValue->value);
                }

                $getjointrankValue = DB::table('military_ranks')->where('id', $request->jointrank)->first();
                if (!empty($getjointrankValue)) {
                    $this->records($reqID, 'jointRank', $getjointrankValue->value);
                }

                $getjointaskaryValue = DB::table('askary_works')->where('id', $request->jointaskary_work)->first();
                if (!empty($getjointaskaryValue)) {
                    $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
                }

                $getjointmadanyValue = DB::table('madany_works')->where('id', $request->jointmadany_work)->first();
                if (!empty($getjointmadanyValue)) {
                    $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
                }

                $updateResult = DB::table('joints')->where('id', $jointId)
                    ->update([
                        'name'             => $name,
                        'mobile'           => $mobile,
                        'salary'           => $salary,
                        'birth_date'       => $birth,
                        'birth_date_higri' => $birth_higri,
                        'age'              => $age,
                        'work'             => $work,
                        'salary_id'        => $salary_source,
                        'military_rank'    => $rank,
                        'madany_id'        => $madany,
                        'job_title'        => $job_title,
                        'funding_id'       => $jointfunding_source,
                        'askary_id'        => $askary_work,
                    ]);

                //

                //

                $realname = $request->realname;
                $realmobile = $request->realmobile;
                $realcity = $request->realcity;
                $region = $request->realregion;
                $realpursuit = $request->realpursuit;
                $realstatus = $request->realstatus;
                $realage = $request->realage;
                $realcost = $request->realcost;
                $realtype = $request->realtype;
                $realhas = $request->realhasprop;
                $othervalue = $request->othervalue;
                $realeva = $request->realeva;
                $realten = $request->realten;
                $realmor = $request->realmor;
                $mortgage_value = $request->mortgage_value;
                $owning_property = $request->owning_property;

                $this->records($reqID, 'realName', $request->realname);
                $this->records($reqID, 'realMobile', $request->realmobile);
                $getcityValue = DB::table('cities')->where('id', $request->realcity)->first();
                if (!empty($getcityValue)) {
                    $this->records($reqID, 'realCity', $getcityValue->value);
                }
                $this->records($reqID, 'realRegion', $request->realregion);
                $this->records($reqID, 'realPursuit', $request->realpursuit);
                $this->records($reqID, 'realAge', $request->realage);
                $this->records($reqID, 'owning_property', 'لا');
                if ($request->owning_property == 'yes') {
                    $this->records($reqID, 'owning_property', 'نعم');
                }
                $this->records($reqID, 'realStatus', $request->realstatus);
                $this->records($reqID, 'realCost', $request->realcost);
                $this->records($reqID, 'mortValue', $request->mortgage_value);
                $gettypeValue = DB::table('real_types')->where('id', $request->realtype)->first();
                if (!empty($gettypeValue)) {
                    $this->records($reqID, 'realType', $gettypeValue->value);
                }

                DB::table('real_estats')->where('id', $realId)
                    ->update([
                        'name'            => $realname,
                        'mobile'          => $realmobile,
                        'city'            => $realcity,
                        'region'          => $region,
                        'pursuit'         => $realpursuit,
                        'age'             => $realage,
                        'status'          => $realstatus,
                        'cost'            => $realcost,
                        'type'            => $realtype,
                        'other_value'     => $othervalue,
                        'evaluated'       => $realeva,
                        'tenant'          => $realten,
                        'mortgage'        => $realmor,
                        'has_property'    => $realhas,
                        'mortgage_value'  => $mortgage_value,
                        'owning_property' => $owning_property,
                    ]);

                //

                if ($fundingReq->type == 'رهن') { //add tsaheel info

                    //TSAHEEL
                    $payId = $fundingReq->payment_id;

                    //

                    $real = $request->real;
                    $incr = $request->incr;
                    $preval = $request->preval;
                    $prepre = $request->prepre;
                    $precos = $request->precos;
                    $net = $request->net;
                    $deficit = $request->deficit;
                    $visa = $request->visa;
                    $carlo = $request->carlo;
                    $perlo = $request->perlo;
                    $realo = $request->realo;
                    $credban = $request->credban;
                    $other1 = $request->other1;
                    $debt = $request->debt;
                    $morpre = $request->morpre;
                    $morcos = $request->morcos;
                    $propre = $request->propre;
                    $procos = $request->procos;
                    $valadd = $request->valadd;
                    $admfe = $request->admfe;
                    //

                    $this->records($reqID, 'realCost', $request->real);
                    $this->records($reqID, 'incValue', $request->incr);
                    $this->records($reqID, 'preValue', $request->preval);
                    $this->records($reqID, 'prePresent', $request->prepre);
                    $this->records($reqID, 'preCost', $request->precos);
                    $this->records($reqID, 'netCust', $request->net);
                    $this->records($reqID, 'deficitCust', $request->deficit);

                    if ($request->visa != 0) {
                        $this->records($reqID, 'preVisa', $request->visa);
                    }

                    if ($request->carlo != 0) {
                        $this->records($reqID, 'carLo', $request->carlo);
                    }

                    if ($request->perlo != 0) {
                        $this->records($reqID, 'personalLo', $request->perlo);
                    }

                    if ($request->realo != 0) {
                        $this->records($reqID, 'realLo', $request->realo);
                    }

                    if ($request->credban != 0) {
                        $this->records($reqID, 'credBank', $request->credban);
                    }

                    if ($request->other1 != 0) {
                        $this->records($reqID, 'otherLo', $request->other1);
                    }

                    $this->records($reqID, 'morPresnt', $request->morpre);
                    $this->records($reqID, 'mortCost', $request->mortCost);
                    $this->records($reqID, 'pursitPresnt', $request->propre);
                    $this->records($reqID, 'profCost', $request->procos);
                    $this->records($reqID, 'addedValue', $request->valadd);
                    $this->records($reqID, 'adminFees', $request->admfe);

                    //

                    DB::table('prepayments')->where('id', $payId)
                        ->update([
                            'realCost'        => $real,
                            'incValue'        => $incr,
                            'prepaymentVal'   => $preval,
                            'prepaymentPre'   => $prepre,
                            'prepaymentCos'   => $precos,
                            'visa'            => $visa,
                            'carLo'           => $carlo,
                            'personalLo'      => $perlo,
                            'realLo'          => $realo,
                            'credit'          => $credban,
                            'netCustomer'     => $net,
                            'other'           => $other1,
                            'debt'            => $debt,
                            'mortPre'         => $morpre,
                            'mortCost'        => $morcos,
                            'proftPre'        => $propre,
                            'deficitCustomer' => $deficit,
                            'profCost'        => $procos,
                            'addedVal'        => $valadd,
                            'adminFee'        => $admfe,
                            'req_id'          => $reqID,
                            'pay_date'        => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                        ]);
                }

                //
                if ($fundingReq->type == 'رهن-شراء') {

                    $payId = $fundingReq->payment_id;

                    $real = $request->real;
                    $incr = $request->incr;
                    $preval = $request->preval;
                    $prepre = $request->prepre;
                    $precos = $request->precos;
                    $net = $request->net;
                    $deficit = $request->deficit;

                    $visa = $request->visa;
                    $carlo = $request->carlo;
                    $perlo = $request->perlo;
                    $realo = $request->realo;
                    $credban = $request->credban;
                    $other = $request->other;
                    $debt = $request->debt;
                    $morpre = $request->morpre;
                    $morcos = $request->morcos;
                    $propre = $request->propre;
                    $procos = $request->procos;
                    $valadd = $request->valadd;
                    $admfe = $request->admfe;
                    //

                    //

                    $this->records($reqID, 'realCost', $request->real);
                    $this->records($reqID, 'incValue', $request->incr);
                    $this->records($reqID, 'preValue', $request->preval);
                    $this->records($reqID, 'prePresent', $request->prepre);
                    $this->records($reqID, 'preCost', $request->precos);
                    $this->records($reqID, 'netCust', $request->net);
                    $this->records($reqID, 'deficitCust', $request->deficit);

                    if ($request->visa != 0) {
                        $this->records($reqID, 'preVisa', $request->visa);
                    }

                    if ($request->carlo != 0) {
                        $this->records($reqID, 'carLo', $request->carlo);
                    }

                    if ($request->perlo != 0) {
                        $this->records($reqID, 'personalLo', $request->perlo);
                    }

                    if ($request->realo != 0) {
                        $this->records($reqID, 'realLo', $request->realo);
                    }

                    if ($request->credban != 0) {
                        $this->records($reqID, 'credBank', $request->credban);
                    }

                    if ($request->other1 != 0) {
                        $this->records($reqID, 'otherLo', $request->other1);
                    }

                    $this->records($reqID, 'morPresnt', $request->morpre);
                    $this->records($reqID, 'mortCost', $request->mortCost);
                    $this->records($reqID, 'pursitPresnt', $request->propre);
                    $this->records($reqID, 'profCost', $request->procos);
                    $this->records($reqID, 'addedValue', $request->valadd);
                    $this->records($reqID, 'adminFees', $request->admfe);

                    //

                    $payupdate = DB::table('prepayments')->where('id', $payId)
                        ->update([
                            'realCost'        => $real,
                            'incValue'        => $incr,
                            'prepaymentVal'   => $preval,
                            'prepaymentPre'   => $prepre,
                            'prepaymentCos'   => $precos,
                            'visa'            => $visa,
                            'carLo'           => $carlo,
                            'personalLo'      => $perlo,
                            'realLo'          => $realo,
                            'credit'          => $credban,
                            'netCustomer'     => $net,
                            'other'           => $other,
                            'debt'            => $debt,
                            'mortPre'         => $morpre,
                            'mortCost'        => $morcos,
                            'proftPre'        => $propre,
                            'deficitCustomer' => $deficit,
                            'profCost'        => $procos,
                            'addedVal'        => $valadd,
                            'adminFee'        => $admfe,
                        ]);

                    // dd( $payupdate);
                }
                //

                ////********************REMINDERS BODY************************* */

                //only one reminder to each request
                $checkFollow = DB::table('notifications')
                    ->where('req_id', '=', $reqID)
                    ->where('recived_id', '=', (auth()->user()->id))
                    ->where('type', '=', 1)
                    ->where('status', '=', 2)
                    ->first(); // check dublicate

                if ($request->follow != null) {

                    $date = $request->follow;
                    $time = $request->follow1;
                    if ($time == null) {
                        $time = "00:00";
                    }

                    $newValue = $date."T".$time;

                    if (empty($checkFollow)) { //first reminder

                        // add following notification
                        DB::table('notifications')->insert([
                            'value'         => MyHelpers::admin_trans(auth()->user()->id, 'The request need following'),
                            'recived_id'    => (auth()->user()->id),
                            'status'        => 2,
                            'type'          => 1,
                            'reminder_date' => $newValue,
                            'req_id'        => $reqID,
                            'created_at'    => (Carbon::now('Asia/Riyadh')),
                        ]);
                    }
                    else {

                        $overWriteReminder = DB::table('notifications')
                            ->where('id', $checkFollow->id)
                            ->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy

                    }
                }
                else {

                    #if empty reminder, so the reminder ll remove if it's existed.
                    if (!empty($checkFollow)) {
                        DB::table('notifications')
                            ->where('id', $checkFollow->id)
                            ->delete();
                    }
                }

                ////********************REMINDERS BODY************************* */

                $funding_source = $request->funding_source;
                $fundingdur = $request->fundingdur;
                $fundingpersonal = $request->fundingpersonal;
                $fundingpersonalp = $request->fundingpersonalp;
                $fundingreal = $request->fundingreal;
                $fundingrealp = $request->fundingrealp;
                $dedp = $request->dedp;
                $monthIn = $request->monthIn;

                $this->records($reqID, 'fundDur', $fundingdur);
                $this->records($reqID, 'fundPers', $fundingpersonal);
                $this->records($reqID, 'fundPersPre', $fundingpersonalp);
                $this->records($reqID, 'fundReal', $fundingreal);
                $this->records($reqID, 'fundRealPre', $fundingrealp);
                $this->records($reqID, 'fundDed', $dedp);
                $this->records($reqID, 'fundMonth', $monthIn);

                $getfundingValue = DB::table('funding_sources')->where('id', $request->funding_source)->first();
                if (!empty($getfundingValue)) {
                    $this->records($reqID, 'funding_source', $getfundingValue->value);
                }

                DB::table('fundings')->where('id', $fundingId)
                    ->update([
                        'funding_source'   => $funding_source,
                        'funding_duration' => $fundingdur,
                        'personalFun_cost' => $fundingpersonal,
                        'personalFun_pre'  => $fundingpersonalp,
                        'realFun_cost'     => $fundingreal,
                        'realFun_pre'      => $fundingrealp,
                        'ded_pre'          => $dedp,
                        'monthly_in'       => $monthIn,
                    ]);

                //

                $reqtype = $request->reqtyp;
                $reqclass = $request->reqclass;
                $reqcomm = $request->reqcomm;
                $webcomm = $request->webcomm;
                $update = Carbon::now('Asia/Riyadh');

                $this->records($reqID, 'comment', $reqcomm);
                $this->records($reqID, 'commentWeb', $webcomm);

                $getclassValue = DB::table('classifcations')->where('id', $request->reqclass)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'class_id_sm', $getclassValue->value);
                }

                if ($fundingReq->is_approved_by_mortgageManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Mortgage Manager'),
                                                             'user_id'      => (auth()->user()->id),
                                                             'history_date' => (Carbon::now('Asia/Riyadh')),
                                                             'req_id'       => $reqID,
                    ]);
                }
                if ($fundingReq->is_approved_by_generalManager == 1 || $fundingReq->is_aqar_approved_by_generalManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of General Manager'),
                                                             'user_id'      => (auth()->user()->id),
                                                             'history_date' => (Carbon::now('Asia/Riyadh')),
                                                             'req_id'       => $reqID,
                    ]);
                }
                if ($fundingReq->is_approved_by_fundingManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Funding Manager'),
                                                             'user_id'      => (auth()->user()->id),
                                                             'history_date' => (Carbon::now('Asia/Riyadh')),
                                                             'req_id'       => $reqID,
                    ]);
                }

                if ($fundingReq->is_approved_by_tsaheel_acc == 1) {
                    DB::table('request_histories')->insert([
                        'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Tsaheel Accountant'),
                        'user_id'      => (auth()->user()->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')),
                        'req_id'       => $reqID,
                    ]);
                }

                if ($fundingReq->is_approved_by_wsata_acc == 1) {
                    DB::table('request_histories')->insert([
                        'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Wsata Accountant'),
                        'user_id'      => (auth()->user()->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')),
                        'req_id'       => $reqID,
                    ]);
                }

                DB::table('requests')->where('id', $reqID)
                    ->update([
                        'class_id_sm'                        => $reqclass,
                        'is_approved_by_salesManager'        => 0,
                        'approved_date_salesManager'         => null,
                        'noteWebsite'                        => $webcomm,
                        'sm_comment'                         => $reqcomm,
                        'updated_at'                         => $update,
                        'is_aqar_approved_by_salesManager'   => 0,
                        'approved_aqar_date_salesManager'    => null,
                        'is_approved_by_mortgageManager'     => 0,
                        'approved_date_mortgageManager'      => null,
                        'is_approved_by_generalManager'      => 0,
                        'approved_date_generalManager'       => null,
                        'is_approved_by_fundingManager'      => 0,
                        'approved_date_fundingManager'       => null,
                        'is_aqar_approved_by_generalManager' => 0,
                        'approved_aqar_date_generalManager'  => null,
                        'is_approved_by_tsaheel_acc'         => 0,
                        'is_approved_by_wsata_acc'           => 0,
                    ]);

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, $fundingReq->statusReq, $fundingReq->user_id, $reqclass);
                }
                //end quality :::::::::::::::::::::::::::::::::::::::

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
                /* } else {
                    return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'));
                }
                */
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function reqArchive(Request $request, $id)
    {

        $managerID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $request->id)->first();

        $checkManager = $request1->sales_manager_id == $managerID;

        if ($checkManager) {
            $archRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 3) //wating for sales manager approval
                    ->orWhere('statusReq', 7) //rejected  from funding manager
                    ->orWhere('statusReq', 10); //rejected  from mortgage manager
                })
                ->update(['statusReq' => 5, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //archive request in sales manager

            if ($archRequest == 0) // not updated
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            if ($archRequest == 1) { // updated sucessfully

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 5, $request1->user_id, $request1->class_id_sm);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::

                return redirect()->route('sales.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function uploadFile(Request $request)
    {

        $rules = [
            'name' => 'required',
            'file' => 'required|file|max:10240',
        ];

        $customMessages = [
            'file.max'      => MyHelpers::admin_trans(auth()->user()->id, 'Should not exceed 10 MB'),
            'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'file.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $name = $request->name;
        $reqID = $request->id;
        $userID = auth()->user()->id;
        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $file = $request->file('file');

        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = $name.time().'.'.$file->getClientOriginalExtension();

        // save to storage/app/photos as the new $filename
        $path = $file->storeAs('documents', $filename);

        $docID = DB::table('documents')->insertGetId(
            [
                'filename'    => $name,
                'location'    => $path,
                'upload_date' => $upload_date,
                'req_id'      => $reqID,
                'user_id'     => $userID,
            ]
        );

        //$docRow = DB::table('documents')->where('id', $docID)->first();

        $documents = DB::table('documents')->where('req_id', '=', $reqID)
            ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')
            ->get();

        return response()->json($documents);
    }

    public function openFile(Request $request, $id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();
        $userID = $document->user_id;

        $request = DB::table('requests')->where('id', '=', $document->req_id)->first();

        $salesManager = $request->sales_manager_id;

        if ($salesManager == auth()->user()->id || (auth()->user()->id == $userID)) {

            try {
                $filename = $document->location;
                return response()->file(storage_path('app/public/'.$filename));
            }catch (\Exception $e){
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

            }
        } // open without dowunload

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function downloadFile(Request $request, $id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();
        $userID = $document->user_id;

        $request = DB::table('requests')->where('id', '=', $document->req_id)->first();

        $salesManager = $request->sales_manager_id;

        if ($salesManager == auth()->user()->id || (auth()->user()->id == $userID)) {

            try {
                $filename = $document->location;
                return response()->download(storage_path('app/public/'.$filename));
            }catch (\Exception $e){
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

            }
        }  // dowunload

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function deleteFile(Request $request)
    {

        $document = DB::table('documents')->where('id', $request->id)->first();

        unlink(storage_path('app/public/'.$document->location));

        //Storage::delete($document->filename);

        $resulte = DB::table('documents')->where('id', '=', $document->id)->delete();

        if ($resulte == 0) //nothing delete
        {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $resulte]);
        }

        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => $resulte]);
        }
    }

    public function morPurpage($id)
    {

        $managerID = auth()->user()->id;

        $morPur = DB::table('requests')->where('id', '=', $id)->first();

        if (!empty($morPur)) {

            $checkManager = $morPur->sales_manager_id == $managerID;

            if ($checkManager) { // check if the request belong to sales agent or not

                $purchaseCustomer = DB::table('requests')
                    ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $reqStatus = $morPur->statusReq;

                $purchaseJoint = DB::table('requests')
                    ->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $purchaseReal = DB::table('requests')
                    ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $purchaseFun = DB::table('requests')
                    ->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $collaborator = DB::table('requests')
                    ->join('users', 'users.id', '=', 'requests.collaborator_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $purchaseClass = DB::table('requests')
                    ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_sm')
                    ->where('requests.id', '=', $id)
                    ->first();

                $payment = DB::table('prepayments')->where('id', '=', $morPur->payment_id)->first();

                $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
                $cities = DB::table('cities')->select('id', 'value')->get();
                $ranks = DB::table('military_ranks')->select('id', 'value')->get();
                $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
                $askary_works = DB::table('askary_works')->select('id', 'value')->get();
                $madany_works = DB::table('madany_works')->select('id', 'value')->get();
                $realTypes = DB::table('real_types')->select('id', 'value')->get();

                $user_role = DB::table('users')->select('role')->where('id', $managerID)->get();
                $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

                $histories = DB::table('req_records')->where('req_id', '=', $id)
                    ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                    ->get();

                $documents = DB::table('documents')->where('req_id', '=', $id)
                    ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                    ->select('documents.*', 'users.name')
                    ->get();

                //get notificationes

                $followdate = DB::table('notifications')->where('req_id', '=', $id)
                    ->where('recived_id', '=', (auth()->user()->id))
                    ->where('type', '=', 1)
                    //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                    ->get()
                    ->last(); //to get last reminder

                $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

                if (!empty($followdate)) {
                    $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
                }

                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
                MyHelpers::openReqWillOpenNotify($id);
                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

                $worke_sources = WorkSource::all();
                $request_sources = DB::table('request_source')->get();

                // dd(  $morPur);
                return view('SalesManager.morPurReq.fundingreqpage', compact(
                    'purchaseCustomer',
                    'purchaseJoint',
                    'purchaseReal',
                    'purchaseFun',
                    'purchaseClass',
                    'salary_sources',
                    'funding_sources',
                    'askary_works',
                    'madany_works',
                    'classifcations',
                    'id', //Request ID
                    'histories',
                    'documents',
                    'reqStatus',
                    'payment',
                    'followdate',
                    'morPur',

                    'collaborator',
                    'cities',
                    'ranks',
                    'followtime',
                    'realTypes',
                    'worke_sources',
                    'request_sources'
                ));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'The mor-pur request not created yet'));
        }
    }

    public function rejectMorPur(Request $request)
    {

        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $reqID = $restRequest->req_id;
        $userID = $restRequest->user_id;

        $checkManager = $restRequest->sales_manager_id == $managerID;

        $userInfo = DB::table('users')->where('id', $userID) // check if user belong to manager
        ->first();

        if ($checkManager) {

            $restRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 18) // mor-pur wating for sales manager
                    ->orWhere('statusReq', 22); // mor-pur rejected from funding manager and redirect to sales manager
                })
                ->update(['statusReq' => 19, 'isSentSalesAgent' => 1, 'class_id_agent' => 1]); //wating for agent approval

            DB::table('req_records')->insert([
                'colum'          => 'class_agent',
                'user_id'        => null,
                //'value'          => 'يحتاج متابعة',
                'value'          => 1,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $request->id,
                'user_switch_id' => null,
                'comment'        => 'تلقائي - عن طريق النظام',
            ]);

            if ($restRequest == 1) { //rejected sucessfully

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), $userID, $request->comment);

                /*   DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractMortgage($request->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $reqID,
                ]);

            */

                DB::table('notifications')->insert([ // add notification to send general manager user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $userID,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                if ($restRequest->type != 'رهن-شراء') {
                    $reqType = 'fundingreqpage';
                }
                else {
                    $reqType = 'morPurRequest';
                }

                //$pwaPush = MyHelpers::pushPWA($userID, ' يومك سعيد  ' . $userInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', $reqType, $request->id);

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $restRequest, 'id' => $request->id]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $restRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function sendMorPur(Request $request)
    {

        $managerID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $checkManager = $restRequest->sales_manager_id == $managerID;

        $userInfo = DB::table('users')->where('id', $userID) // check if user belong to manager
        ->first();

        if ($checkManager) {

            $sendTo = $request->sendTo;

            if ($sendTo == 'funding') {
                $sendPay = DB::table('requests')->where('id', $request->id)
                    ->where(function ($query) {
                        $query->where('statusReq', 18) // mor-pur wating for sales manager
                        ->orWhere('statusReq', 22); // mor-pur rejected from funding manager and redirect to sales manager
                    })
                    ->update(['statusReq' => 21, 'isSentFundingManager' => 1]); //wating for funding approval

                if ($sendPay == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $fundingManager = $restRequest->funding_manager_id;
                    if ($fundingManager == null) {
                        $updateMortgageManager = MyHelpers::fundingManagerRequestProcess($request->id);
                        $fundingManager = MyHelpers::getFundingManagerRequest($request->id);
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), $fundingManager, $request->comment);

                    /*       DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractFunding($request->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $restRequest->req_id,
                    ]);
                    */

                    DB::table('notifications')->insert([ // add notification to send general manager user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $fundingManager,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                }
            }

            if ($sendTo == 'agent') {
                $sendPay = DB::table('requests')->where('id', $request->id)
                    ->where(function ($query) {
                        $query->where('statusReq', 18) // mor-pur wating for sales manager
                        ->orWhere('statusReq', 22); // mor-pur rejected from funding manager and redirect to sales manager
                    })
                    ->update(['statusReq' => 19, 'isSentSalesAgent' => 1]); //wating for agent approval

                if ($sendPay == 1) {
                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), $userID, $request->comment);

                    /*   DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' => $userID,
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $restRequest->req_id,
                    ]);
                    */

                    DB::table('notifications')->insert([ // add notification to send general manager user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $userID,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                }
            }

            if ($sendPay == 0) //nothing send

            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            else {
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function archReqArr(Request $request)
    {

        $result = DB::table('requests')
            ->whereIn('id', $request->array)
            // ->where('user_id',  auth()->user()->id)
            ->whereIn('statusReq', [3, 7, 10])
            ->update([
                'statusReq' => 5, //archived in sales manager
            ]);
        return response($result); // if 1: update succesfally

    }

    public function restReqArr(Request $request)
    {

        $result = DB::table('requests')
            ->whereIn('id', $request->array)
            // ->where('user_id',  auth()->user()->id)
            ->where('statusReq', 5) //archived in sales manager
            ->update([
                'statusReq' => 3,
            ]);
        return response($result); // if 1: update succesfally

    }

    public function aprroveTsaheel(Request $request)
    {

        $reqInfo = DB::table('requests')
            ->where('requests.id', '=', $request->id)
            ->update(['is_approved_by_salesManager' => 1, 'approved_date_salesManager' => Carbon::now('Asia/Riyadh')->format("Y-m-d")]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager approve tsaheel'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'already approved'), 'status' => 0]);
    }

    public function undoaprroveTsaheel(Request $request)
    {

        $reqInfo = DB::table('requests')
            ->where('requests.id', '=', $request->id)
            ->update(['is_approved_by_salesManager' => 0, 'approved_date_salesManager' => null]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager cancel approve aqar'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'already approved'), 'status' => 0]);
    }

    public function aprroveAqar(Request $request)
    {

        $reqInfo = DB::table('requests')
            ->where('requests.id', '=', $request->id)
            ->update(['is_aqar_approved_by_salesManager' => 1, 'approved_aqar_date_salesManager' => Carbon::now('Asia/Riyadh')->format("Y-m-d")]);

        if ($reqInfo) {

            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager approve aqar'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'already approved'), 'status' => 0]);
    }

    public function undoaprroveAqar(Request $request)
    {

        $reqInfo = DB::table('requests')
            ->where('requests.id', '=', $request->id)
            ->update(['is_aqar_approved_by_salesManager' => 0, 'approved_aqar_date_salesManager' => null]);

        if ($reqInfo) {

            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager cancel approve aqar'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }
}
