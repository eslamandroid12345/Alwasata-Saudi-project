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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class GeneralManagerController extends Controller
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

    public function homePage()
    {
        return view('GeneralManager.home.home');
    }

    ///////////////////////////////////////////////////

    public function myReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {
            $query->whereIn('statusReq', [12, 32]) //wating for generall manager approval
            ->orWhere('requests.isSentGeneralManager', 1); //yes sent
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->pluck('customers.id')->toArray();

        $customers = customer::whereIn('id', [])->get();

        foreach (array_chunk($coll_users, 6000) as $t) { # because error : Prepared statement contains too many placeholders

            $customers_arry = customer::whereIn('id', $t)->get();
            $customers = $customers->merge($customers_arry);
        }

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        // dd($collaborators);

        return view('GeneralManager.Request.myReqs',
            compact('requests', 'requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources'));
    }

    ///

    public function fetchNotify()
    { // to get notificationes of users

        $checkFollow = DB::table('notifications')->where('recived_id', (auth()->user()->id))->where('reminder_date', "<=", Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s"))->where('status', 2) //Not Active (for following)
        ->first();

        if (!empty($checkFollow)) {
            DB::table('notifications')->where('id', $checkFollow->id)->update([
                'status'     => 0,
                'created_at' => Carbon::now('Asia/Riyadh'),
            ]);
        }

        return DB::table('notifications')->where('recived_id', (auth()->user()->id))->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.status', 0) // new
        ->orderBy('notifications.id', 'DESC')->select('notifications.*', 'customers.name')->get();
    }

    //This new function to show dataTabel in view(GeneralManager.Request.myReqs)

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
            30 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            31 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            32 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            33 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            34 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            35 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }

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

    public function myreqs_datatable(Request $request)
    {
        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {
            $query->whereIn('statusReq', [12, 32]) //wating for generall manager approval
            ->orWhere('requests.isSentGeneralManager', 1); //yes sent
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=',
            'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select(

            'requests.*', 'customers.name as cust_name', 'users.name as user_name', //'customers.salary',
            // 'customers.salary_id',
            // 'customers.madany_id',
            // 'customers.military_rank',
            'customers.mobile', // 'customers.is_supported',
            // 'customers.birth_date_higri',
            // 'customers.has_obligations',
            // 'customers.work',
            /*
            'joints.name as joint_name',
            'joints.mobile as joint_mobile',
            'joints.salary as joint_salary',
            'joints.salary_id as joint_salary_id',
            'joints.birth_date_higri as joint_birth_date_higri',
            'joints.work as joint_work',
            'joints.madany_id as joint_madany_id',
            'joints.military_rank as joint_military_rank',
            'real_estats.name as real_name',
            'real_estats.mobile as real_mobile',
            'real_estats.age as real_age',
            'real_estats.city as real_city',
            'real_estats.region',
            'real_estats.status as real_status',
            'real_estats.cost as real_cost',
            'real_estats.pursuit as real_pursuit',
            'real_estats.type as real_type',
            'real_estats.evaluated as real_evaluated',
            'real_estats.tenant as real_tenant',
            'real_estats.mortgage as real_mortgage',
            'real_estats.has_property as real_has_property',
            'fundings.funding_source as funding_funding_source',
            'fundings.funding_duration as funding_funding_duration',
            'fundings.personalFun_pre as funding_personalFun_pre',
            'fundings.personalFun_cost as funding_personalFun_cost',
            'fundings.realFun_pre as funding_realFun_pre',
            'fundings.realFun_cost as funding_realFun_cost',
            'fundings.ded_pre as funding_ded_pre',
            'fundings.monthly_in as funding_monthly_in',
            */ 'prepayments.payStatus', /*
                'prepayments.realCost',
                'prepayments.incValue',
                'prepayments.prepaymentVal',
                'prepayments.prepaymentPre',
                'prepayments.prepaymentCos',
                'prepayments.netCustomer',
                'prepayments.deficitCustomer',
                'prepayments.visa',
                'prepayments.carLo',
                'prepayments.personalLo',
                'prepayments.realLo',
                'prepayments.credit',
                'prepayments.other',
                'prepayments.debt',
                'prepayments.mortPre',
                'prepayments.mortCost',
                'prepayments.proftPre',
                'prepayments.profCost',
                'prepayments.addedVal',
                'prepayments.adminFee',
                */ 'requests.class_id_quality as is_quality_recived')->orderBy('requests.req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work',
                'fundings.funding_source')->get()->whereIn('funding_source', $request->get('founding_sources'));
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
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
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
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
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
        /*if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                 <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                 <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

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
            /*
       })->editColumn('is_supported', function ($row) {

            if ($row->is_supported == 'yes' || $row->is_supported == 'Yes')
                $row->is_supported = 'نعم';
            if ($row->is_supported == 'no' || $row->is_supported == 'No')
                $row->is_supported = 'لا';

            return $row->is_supported;
        })->editColumn('has_obligations', function ($row) {

            if ($row->has_obligations == 'yes' || $row->has_obligations == 'Yes')
                $row->has_obligations = 'نعم';
            if ($row->has_obligations == 'no' || $row->has_obligations == 'No')
                $row->has_obligations = 'لا';

            return $row->has_obligations;
        })->editColumn('real_has_property', function ($row) {

            if ($row->real_has_property == 'yes' || $row->real_has_property == 'Yes')
                $row->real_has_property = 'نعم';
            if ($row->real_has_property == 'no' || $row->real_has_property == 'No')
                $row->real_has_property = 'لا';

            return $row->real_has_property;
        })->editColumn('real_city', function ($row) {
            $regionValue = DB::table('cities')->where('id', $row->real_city)->first();
            if ($regionValue)
                return $regionValue->value;
            return $row->real_city;

        })->editColumn('salary_id', function ($row) {

            $salaryValue = salary_source::find($row->salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->salary_id;

        })->editColumn('funding_funding_source', function ($row) {

            $fundingValue =   DB::table('funding_sources')->where('id', $row->funding_funding_source)->first();

            if ($fundingValue)
                return $fundingValue->value;
            return $row->funding_funding_source;
        })->editColumn('joint_salary_id', function ($row) {

            $salaryValue = salary_source::find($row->joint_salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->joint_salary_id;
        })->editColumn('madany_id', function ($row) {

            $workValue = madany_work::find($row->madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->madany_id;
        })->editColumn('joint_madany_id', function ($row) {

            $workValue = madany_work::find($row->joint_madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->joint_madany_id;
        })->editColumn('joint_military_rank', function ($row) {

            $workValue = madany_work::find($row->joint_military_rank);
            if ($workValue)
                return $workValue->value;
            return $row->joint_military_rank;
        })->editColumn('military_rank', function ($row) {

            $workValue = DB::table('military_ranks')
                ->where('id', $row->military_rank)
                ->first();

            if ($workValue)
                return $workValue->value;
            return $row->military_rank;
            */
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
            /*
        })->editColumn('class_id_sm', function ($row) {

            $classValue = classifcation::find($row->class_id_sm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_sm;
        })->editColumn('class_id_fm', function ($row) {

            $classValue = classifcation::find($row->class_id_fm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_fm;
        })->editColumn('class_id_mm', function ($row) {

            $classValue = classifcation::find($row->class_id_mm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_mm;
            */
        })->editColumn('class_id_gm', function ($row) {

            $classValue = classifcation::find($row->class_id_gm);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_gm;
        })->editColumn('class_id_quality', function ($row) {

            $classValue = classifcation::find($row->class_id_quality);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_quality;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('payStatus', function ($row) {
            return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
            <i class="fa fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
            <i class="fa fa-close"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function recivedReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->whereIn('statusReq', [12, 32]);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', 23);
                $query->where('type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name',
            'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->pluck('customers.id')->toArray();

        $customers = customer::whereIn('id', [])->get();

        foreach (array_chunk($coll_users, 6000) as $t) { # because error : Prepared statement contains too many placeholders

            $customers_arry = customer::whereIn('id', $t)->get();
            $customers = $customers->merge($customers_arry);
        }

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        // dd($collaborators);

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('GeneralManager.Request.recivedReqs',
            compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources', 'request_sources'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.recivedReqs)
    public function recivedReqs_datatable(Request $request)
    {

        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->whereIn('statusReq', [12, 32]);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', 23);
                $query->where('requests.type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id',
            '=', 'requests.real_id')->join('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select(

            'requests.*', 'customers.name as cust_name', 'users.name as user_name', /*
                'customers.salary',
                'customers.salary_id',
                'customers.madany_id',
                'customers.military_rank',
                */ 'customers.mobile', /*
                'customers.is_supported',
                'customers.birth_date_higri',
                'customers.has_obligations',
                'customers.work',
                'joints.name as joint_name',
                'joints.mobile as joint_mobile',
                'joints.salary as joint_salary',
                'joints.salary_id as joint_salary_id',
                'joints.birth_date_higri as joint_birth_date_higri',
                'joints.work as joint_work',
                'joints.madany_id as joint_madany_id',
                'joints.military_rank as joint_military_rank',
                'real_estats.name as real_name',
                'real_estats.mobile as real_mobile',
                'real_estats.age as real_age',
                'real_estats.city as real_city',
                'real_estats.region',
                'real_estats.status as real_status',
                'real_estats.cost as real_cost',
                'real_estats.pursuit as real_pursuit',
                'real_estats.type as real_type',
                'real_estats.evaluated as real_evaluated',
                'real_estats.tenant as real_tenant',
                'real_estats.mortgage as real_mortgage',
                'real_estats.has_property as real_has_property',
                'fundings.funding_source as funding_funding_source',
                'fundings.funding_duration as funding_funding_duration',
                'fundings.personalFun_pre as funding_personalFun_pre',
                'fundings.personalFun_cost as funding_personalFun_cost',
                'fundings.realFun_pre as funding_realFun_pre',
                'fundings.realFun_cost as funding_realFun_cost',
                'fundings.ded_pre as funding_ded_pre',
                'fundings.monthly_in as funding_monthly_in',
                */ 'prepayments.payStatus', /*
                'prepayments.realCost',
                'prepayments.incValue',
                'prepayments.prepaymentVal',
                'prepayments.prepaymentPre',
                'prepayments.prepaymentCos',
                'prepayments.netCustomer',
                'prepayments.deficitCustomer',
                'prepayments.visa',
                'prepayments.carLo',
                'prepayments.personalLo',
                'prepayments.realLo',
                'prepayments.credit',
                'prepayments.other',
                'prepayments.debt',
                'prepayments.mortPre',
                'prepayments.mortCost',
                'prepayments.proftPre',
                'prepayments.profCost',
                'prepayments.addedVal',
                'prepayments.adminFee',
                */ 'requests.class_id_quality as is_quality_recived')->orderBy('requests.req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work',
                'fundings.funding_source')->get()->whereIn('funding_source', $request->get('founding_sources'));
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
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
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
            $requests = $requests->where('customers.salary', $request->get('customer_salary'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('customers.work', $request->get('work_source'));
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
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
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
        }/*/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                 <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                 <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

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
            /*
        })->editColumn('is_supported', function ($row) {

            if ($row->is_supported == 'yes' || $row->is_supported == 'Yes')
                $row->is_supported = 'نعم';
            if ($row->is_supported == 'no' || $row->is_supported == 'No')
                $row->is_supported = 'لا';

            return $row->is_supported;
        })->editColumn('has_obligations', function ($row) {

            if ($row->has_obligations == 'yes' || $row->has_obligations == 'Yes')
                $row->has_obligations = 'نعم';
            if ($row->has_obligations == 'no' || $row->has_obligations == 'No')
                $row->has_obligations = 'لا';

            return $row->has_obligations;
        })->editColumn('real_has_property', function ($row) {

            if ($row->real_has_property == 'yes' || $row->real_has_property == 'Yes')
                $row->real_has_property = 'نعم';
            if ($row->real_has_property == 'no' || $row->real_has_property == 'No')
                $row->real_has_property = 'لا';

            return $row->real_has_property;
        })->editColumn('real_city', function ($row) {
            $regionValue = DB::table('cities')->where('id', $row->real_city)->first();
            if ($regionValue)
                return $regionValue->value;
            return $row->real_city;
        })->editColumn('salary_id', function ($row) {

            $salaryValue = salary_source::find($row->salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->salary_id;
        })->editColumn('funding_funding_source', function ($row) {

            $fundingValue =   DB::table('funding_sources')->where('id', $row->funding_funding_source)->first();

            if ($fundingValue)
                return $fundingValue->value;
            return $row->funding_funding_source;
        })->editColumn('joint_salary_id', function ($row) {

            $salaryValue = salary_source::find($row->joint_salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->joint_salary_id;
        })->editColumn('madany_id', function ($row) {

            $workValue = madany_work::find($row->madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->madany_id;
        })->editColumn('joint_madany_id', function ($row) {

            $workValue = madany_work::find($row->joint_madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->joint_madany_id;
        })->editColumn('joint_military_rank', function ($row) {

            $workValue = madany_work::find($row->joint_military_rank);
            if ($workValue)
                return $workValue->value;
            return $row->joint_military_rank;
        })->editColumn('military_rank', function ($row) {

            $workValue = DB::table('military_ranks')
                ->where('id', $row->military_rank)
                ->first();

            if ($workValue)
                return $workValue->value;
            return $row->military_rank;
            */
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
            /*
        })->editColumn('class_id_sm', function ($row) {

            $classValue = classifcation::find($row->class_id_sm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_sm;
        })->editColumn('class_id_fm', function ($row) {

            $classValue = classifcation::find($row->class_id_fm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_fm;
        })->editColumn('class_id_mm', function ($row) {

            $classValue = classifcation::find($row->class_id_mm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_mm;
            */
        })->editColumn('class_id_gm', function ($row) {

            $classValue = classifcation::find($row->class_id_gm);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_gm;
        })->editColumn('class_id_quality', function ($row) {

            $classValue = classifcation::find($row->class_id_quality);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_quality;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('payStatus', function ($row) {
            return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
            <i class="fa fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
            <i class="fa fa-close"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function followReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('statusReq', 12) //wating for generall manager approval
        ->where('isSentGeneralManager', 1) //yes sent
        ->where('is_followed', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get();

        $notifys = $this->fetchNotify();

        return view('GeneralManager.Request.followReqs', compact('requests', 'notifys'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.followReqs)
    public function followReqs_datatable(Request $request)
    {

        $requests = DB::table('requests')->where('statusReq', 12) //wating for generall manager approval
        ->where('isSentGeneralManager', 1) //yes sent
        ->where('is_followed', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get();
        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                     <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                     <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                 <a href="'.route('general.manager.restoreRequest', $row->id).'"> <i class="fa fa-reply"></i></a>
                           </span>';
            $data = $data.'</div>';
            return $data;
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

    public function starReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('statusReq', 12) //wating for generall manager approval
        ->where('isSentGeneralManager', 1) //yes sent
        ->where('is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get();

        $notifys = $this->fetchNotify();

        return view('GeneralManager.Request.staredReqs', compact('requests', 'notifys'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.starReqs)
    public function starReqs_datatable(Request $request)
    {

        $requests = DB::table('requests')->where('statusReq', 12) //wating for generall manager approval
        ->where('isSentGeneralManager', 1) //yes sent
        ->where('is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                     <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                     <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                 <a href="'.route('general.manager.restoreRequest', $row->id).'"> <i class="fa fa-reply"></i></a>
                           </span>';
            $data = $data.'</div>';
            return $data;
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

    public function canceledReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->where('statusReq', 15);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', 27);
                $query->where('type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('GeneralManager.Request.canceledReqs',
            compact('requests', 'requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.canceledReqs)
    public function canceledReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->where('statusReq', 15);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', 27);
                $query->where('type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary',
            'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'prepayments.payStatus')->orderBy('requests.req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work',
                'fundings.funding_source')->get()->whereIn('funding_source', $request->get('founding_sources'));
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
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
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
            $requests = $requests->where('mobile', $request->get('customer_phone'));
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
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                      <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                      <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                  <a href="'.route('general.manager.restMorPur', $row->id).'"> <i class="fa fa-reply"></i></a>
                            </span>';
            }
            else {
                $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                    <a href="'.route('general.manager.reCancelFunding', $row->id).'"> <i class="fa fa-reply"></i></a>
              </span>';
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
        })->editColumn('class_id_gm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_gm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_gm;
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

    public function completedReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->whereNotIn('statusReq', [12, 14, 15, 32]);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('type', ['شراء', 'شراء-دفعة', 'رهن', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted

            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', '!=', 23);
                $query->where('type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        // dd($collaborators);

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('GeneralManager.Request.completedReqs',
            compact('requests', 'requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.compleredReq)
    public function completedReqs_datatable(Request $request)
    {
        $requests = DB::table('requests')->where(function ($query) {

            $query->where(function ($query) {
                $query->whereNotIn('statusReq', [12, 14, 15, 32]);
                $query->where('requests.isSentGeneralManager', 1);
                $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'رهن', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', '!=', 23);
                $query->where('requests.type', 'رهن-شراء');
                $query->where('requests.isSentGeneralManager', 1);
            });
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=',
            'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select(

            'requests.*', 'customers.name as cust_name', 'users.name as user_name', /*
                'customers.salary',
                'customers.salary_id',
                'customers.madany_id',
                'customers.military_rank',
                */ 'customers.mobile', /*
                'customers.is_supported',
                'customers.birth_date_higri',
                'customers.has_obligations',
                'customers.work',
                'joints.name as joint_name',
                'joints.mobile as joint_mobile',
                'joints.salary as joint_salary',
                'joints.salary_id as joint_salary_id',
                'joints.birth_date_higri as joint_birth_date_higri',
                'joints.work as joint_work',
                'joints.madany_id as joint_madany_id',
                'joints.military_rank as joint_military_rank',
                'real_estats.name as real_name',
                'real_estats.mobile as real_mobile',
                'real_estats.age as real_age',
                'real_estats.city as real_city',
                'real_estats.region',
                'real_estats.status as real_status',
                'real_estats.cost as real_cost',
                'real_estats.pursuit as real_pursuit',
                'real_estats.type as real_type',
                'real_estats.evaluated as real_evaluated',
                'real_estats.tenant as real_tenant',
                'real_estats.mortgage as real_mortgage',
                'real_estats.has_property as real_has_property',
                'fundings.funding_source as funding_funding_source',
                'fundings.funding_duration as funding_funding_duration',
                'fundings.personalFun_pre as funding_personalFun_pre',
                'fundings.personalFun_cost as funding_personalFun_cost',
                'fundings.realFun_pre as funding_realFun_pre',
                'fundings.realFun_cost as funding_realFun_cost',
                'fundings.ded_pre as funding_ded_pre',
                'fundings.monthly_in as funding_monthly_in',
                */

            'prepayments.payStatus', /*
                'prepayments.realCost',
                'prepayments.incValue',
                'prepayments.prepaymentVal',
                'prepayments.prepaymentPre',
                'prepayments.prepaymentCos',
                'prepayments.netCustomer',
                'prepayments.deficitCustomer',
                'prepayments.visa',
                'prepayments.carLo',
                'prepayments.personalLo',
                'prepayments.realLo',
                'prepayments.credit',
                'prepayments.other',
                'prepayments.debt',
                'prepayments.mortPre',
                'prepayments.mortCost',
                'prepayments.proftPre',
                'prepayments.profCost',
                'prepayments.addedVal',
                'prepayments.adminFee',
                */ 'requests.class_id_quality as is_quality_recived')->orderBy('requests.req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work',
                'fundings.funding_source')->get()->whereIn('funding_source', $request->get('founding_sources'));
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
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
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
            $requests = $requests->where('mobile', $request->get('customer_phone'));
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
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

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
            /*
        })->editColumn('is_supported', function ($row) {

            if ($row->is_supported == 'yes' || $row->is_supported == 'Yes')
                $row->is_supported = 'نعم';
            if ($row->is_supported == 'no' || $row->is_supported == 'No')
                $row->is_supported = 'لا';

            return $row->is_supported;
        })->editColumn('has_obligations', function ($row) {

            if ($row->has_obligations == 'yes' || $row->has_obligations == 'Yes')
                $row->has_obligations = 'نعم';
            if ($row->has_obligations == 'no' || $row->has_obligations == 'No')
                $row->has_obligations = 'لا';

            return $row->has_obligations;
        })->editColumn('real_has_property', function ($row) {

            if ($row->real_has_property == 'yes' || $row->real_has_property == 'Yes')
                $row->real_has_property = 'نعم';
            if ($row->real_has_property == 'no' || $row->real_has_property == 'No')
                $row->real_has_property = 'لا';

            return $row->real_has_property;
        })->editColumn('real_city', function ($row) {
            $regionValue = DB::table('cities')->where('id', $row->real_city)->first();
            if ($regionValue)
                return $regionValue->value;
            return $row->real_city;
        })->editColumn('salary_id', function ($row) {

            $salaryValue = salary_source::find($row->salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->salary_id;
        })->editColumn('funding_funding_source', function ($row) {

            $fundingValue =   DB::table('funding_sources')->where('id', $row->funding_funding_source)->first();

            if ($fundingValue)
                return $fundingValue->value;
            return $row->funding_funding_source;
        })->editColumn('joint_salary_id', function ($row) {

            $salaryValue = salary_source::find($row->joint_salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->joint_salary_id;
        })->editColumn('madany_id', function ($row) {

            $workValue = madany_work::find($row->madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->madany_id;
        })->editColumn('joint_madany_id', function ($row) {

            $workValue = madany_work::find($row->joint_madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->joint_madany_id;
        })->editColumn('joint_military_rank', function ($row) {

            $workValue = madany_work::find($row->joint_military_rank);
            if ($workValue)
                return $workValue->value;
            return $row->joint_military_rank;
        })->editColumn('military_rank', function ($row) {

            $workValue = DB::table('military_ranks')
                ->where('id', $row->military_rank)
                ->first();

            if ($workValue)
                return $workValue->value;
            return $row->military_rank;
            */
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
            /*
        })->editColumn('class_id_sm', function ($row) {

            $classValue = classifcation::find($row->class_id_sm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_sm;
        })->editColumn('class_id_fm', function ($row) {

            $classValue = classifcation::find($row->class_id_fm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_fm;
        })->editColumn('class_id_mm', function ($row) {

            $classValue = classifcation::find($row->class_id_mm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_mm;
            */
        })->editColumn('class_id_gm', function ($row) {

            $classValue = classifcation::find($row->class_id_gm);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_gm;
        })->editColumn('class_id_quality', function ($row) {

            $classValue = classifcation::find($row->class_id_quality);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_quality;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('payStatus', function ($row) {
            return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
            <i class="fa fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item btn" style="cursor: default;  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
            <i class="fa fa-close"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function manageReq($id, $action)
    {

        $userID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('requests.id', $id)->where('isSentGeneralManager', 1)->first();

        //dd($restRequest);

        if (!empty($restRequest)) {
            $restRequest = DB::table('requests')->where('requests.id', $id)->update(['is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //set request

            $restRequest = DB::table('requests')->where('id', $id)->update(['is_'.$action => 1]);

            if ($restRequest == 1) {
                return redirect()->route('general.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function archReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('statusReq', 14) //archived in general manager
        ->where('isSentGeneralManager', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        // dd($collaborators);
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('GeneralManager.Request.archReqs',
            compact('requests', 'requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources'));
    }

    //This new function to show dataTabel in view(GeneralManager.Request.archReqs)
    public function archReqs_datatable(Request $request)
    {
        $requests = DB::table('requests')->where('statusReq', 14) //archived in general manager
        ->where('requests.isSentGeneralManager', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=',
            'requests.real_id')->join('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', /*
                'customers.salary',
                'customers.salary_id',
                'customers.madany_id',
                'customers.military_rank',
                */ 'customers.mobile', /*
                'customers.is_supported',
                'customers.birth_date_higri',
                'customers.has_obligations',
                'customers.work',
                'joints.name as joint_name',
                'joints.mobile as joint_mobile',
                'joints.salary as joint_salary',
                'joints.salary_id as joint_salary_id',
                'joints.birth_date_higri as joint_birth_date_higri',
                'joints.work as joint_work',
                'joints.madany_id as joint_madany_id',
                'joints.military_rank as joint_military_rank',
                'real_estats.name as real_name',
                'real_estats.mobile as real_mobile',
                'real_estats.age as real_age',
                'real_estats.city as real_city',
                'real_estats.region',
                'real_estats.status as real_status',
                'real_estats.cost as real_cost',
                'real_estats.pursuit as real_pursuit',
                'real_estats.type as real_type',
                'real_estats.evaluated as real_evaluated',
                'real_estats.tenant as real_tenant',
                'real_estats.mortgage as real_mortgage',
                'real_estats.has_property as real_has_property',
                'fundings.funding_source as funding_funding_source',
                'fundings.funding_duration as funding_funding_duration',
                'fundings.personalFun_pre as funding_personalFun_pre',
                'fundings.personalFun_cost as funding_personalFun_cost',
                'fundings.realFun_pre as funding_realFun_pre',
                'fundings.realFun_cost as funding_realFun_cost',
                'fundings.ded_pre as funding_ded_pre',
                'fundings.monthly_in as funding_monthly_in',
                */ 'prepayments.payStatus',/*
                'prepayments.realCost',
                'prepayments.incValue',
                'prepayments.prepaymentVal',
                'prepayments.prepaymentPre',
                'prepayments.prepaymentCos',
                'prepayments.netCustomer',
                'prepayments.deficitCustomer',
                'prepayments.visa',
                'prepayments.carLo',
                'prepayments.personalLo',
                'prepayments.realLo',
                'prepayments.credit',
                'prepayments.other',
                'prepayments.debt',
                'prepayments.mortPre',
                'prepayments.mortCost',
                'prepayments.proftPre',
                'prepayments.profCost',
                'prepayments.addedVal',
                'prepayments.adminFee',
                */)->orderBy('requests.req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'customers.id', '=', 'fundings.customer_id')->select('requests.*', 'customers.name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work',
                'fundings.funding_source')->get()->whereIn('funding_source', $request->get('founding_sources'));
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
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
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
            $requests = $requests->where('mobile', $request->get('customer_phone'));
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
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {
            return '<div class="tableAdminOption"><span class="item pointer"  id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                    <a href="'.route('general.manager.restoreRequest', $row->id).'"> <i class="fa fa-reply"></i></a>
                                </span></div>';
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
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
            /*
        })->editColumn('is_supported', function ($row) {

            if ($row->is_supported == 'yes' || $row->is_supported == 'Yes')
                $row->is_supported = 'نعم';
            if ($row->is_supported == 'no' || $row->is_supported == 'No')
                $row->is_supported = 'لا';

            return $row->is_supported;
        })->editColumn('has_obligations', function ($row) {

            if ($row->has_obligations == 'yes' || $row->has_obligations == 'Yes')
                $row->has_obligations = 'نعم';
            if ($row->has_obligations == 'no' || $row->has_obligations == 'No')
                $row->has_obligations = 'لا';

            return $row->has_obligations;
        })->editColumn('real_has_property', function ($row) {

            if ($row->real_has_property == 'yes' || $row->real_has_property == 'Yes')
                $row->real_has_property = 'نعم';
            if ($row->real_has_property == 'no' || $row->real_has_property == 'No')
                $row->real_has_property = 'لا';

            return $row->real_has_property;
        })->editColumn('real_city', function ($row) {
            $regionValue = DB::table('cities')->where('id', $row->real_city)->first();
            if ($regionValue)
                return $regionValue->value;
            return $row->real_city;
        })->editColumn('salary_id', function ($row) {

            $salaryValue = salary_source::find($row->salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->salary_id;
        })->editColumn('funding_funding_source', function ($row) {

            $fundingValue =   DB::table('funding_sources')->where('id', $row->funding_funding_source)->first();

            if ($fundingValue)
                return $fundingValue->value;
            return $row->funding_funding_source;
        })->editColumn('joint_salary_id', function ($row) {

            $salaryValue = salary_source::find($row->joint_salary_id);
            if ($salaryValue)
                return $salaryValue->value;
            return $row->joint_salary_id;
        })->editColumn('madany_id', function ($row) {

            $workValue = madany_work::find($row->madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->madany_id;
        })->editColumn('joint_madany_id', function ($row) {

            $workValue = madany_work::find($row->joint_madany_id);
            if ($workValue)
                return $workValue->value;
            return $row->joint_madany_id;
        })->editColumn('joint_military_rank', function ($row) {

            $workValue = madany_work::find($row->joint_military_rank);
            if ($workValue)
                return $workValue->value;
            return $row->joint_military_rank;
        })->editColumn('military_rank', function ($row) {

            $workValue = DB::table('military_ranks')
                ->where('id', $row->military_rank)
                ->first();

            if ($workValue)
                return $workValue->value;
            return $row->military_rank;
            */
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
            /*
        })->editColumn('class_id_sm', function ($row) {

            $classValue = classifcation::find($row->class_id_sm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_sm;
        })->editColumn('class_id_fm', function ($row) {

            $classValue = classifcation::find($row->class_id_fm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_fm;
        })->editColumn('class_id_mm', function ($row) {

            $classValue = classifcation::find($row->class_id_mm);
            if ($classValue)
                return $classValue->value;
            return $row->class_id_mm;
            */
        })->editColumn('class_id_gm', function ($row) {

            $classValue = classifcation::find($row->class_id_gm);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_gm;
        })->editColumn('class_id_quality', function ($row) {

            $classValue = classifcation::find($row->class_id_quality);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_quality;
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('payStatus', function ($row) {
            return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
        })->make(true);
    }

    public function restReq(Request $request, $id)
    {

        $userID = (auth()->user()->id);
        $restRequest = DB::table('requests')->where('id', $id)->where('isSentGeneralManager', 1)->where('type', '!=', 'تساهيل')->where(function ($query) {
            $query->where('statusReq', 14); //archive request in general manager
        })->update(['statusReq' => 12, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for general manager

        $restRequest = DB::table('requests')->where('id', $id)->where('isSentGeneralManager', 1)->where('type', 'تساهيل')->where(function ($query) {
            $query->where('statusReq', 14); //archive request in general manager
        })->update(['statusReq' => 32, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for general manager

        if ($restRequest == 0) // not updated
        {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }

        if ($restRequest == 1) // updated sucessfully
        {
            return redirect()->route('general.manager.archRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Restore sucessfully'));
        }
    }

    public function fundingreqpage($id)
    {

        $userID = auth()->user()->id;

        $request = DB::table('requests')->where('requests.id', '=', $id)
            //       ->where('isSentGeneralManager', 1)
            ->first();

        //dd( $request);

        $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

        if (!empty($request)) {

            $reqStatus = $request->statusReq;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_gm')->where('requests.id', '=', $id)->first();

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            if ($request->type == 'رهن-شراء') {
                $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
            }
            else {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $user_role = DB::table('users')->select('role')->where('id', $userID)->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

            /*$histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

            $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            $regions = customer::select('region_ip')->groupBy('region_ip')->get();

            $notifys = $this->fetchNotify(); //get notificationes

            $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            $salesAgents = User::where(['role' => 0, 'status' => 1])->get();

            return view('GeneralManager.fundingReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'regions', 'classifcations', 'id', //Request ID
                // 'histories',
            'salesAgents',
                'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'followtime', 'realTypes', 'worke_sources', 'request_sources'));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function updatefunding(Request $request)
    {

        // dd($request);
        $rules = [
            //   'name' => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            //'jointmobile'=> 'regex:/^(05)[0-9]{8}$/',
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
            // 'jointmobile.regex' => 'Should start with 05 ',
            //  'birth.required' => 'The birth date filed is required ',
        ];

        $this->validate($request, $rules, $customMessages);

        //REQUEST
        $reqID = $request->reqID; //request id for update
        $fundingReq = DB::table('requests')->where('id', $reqID)->where('isSentGeneralManager', 1)->where(function ($query) {
            $query->where('statusReq', 12) //wating for general maanager
            ->orWhere('statusReq', 23) //mor-pur in general maanager
            ->orWhere('statusReq', 32);
        })->first();
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
            $classId = $fundingReq->class_id_gm;
            //

            $checkmobile = DB::table('customers')->where('mobile', $request->mobile)->first();

            if (empty($checkmobile) || $customerInfo->mobile == $request->mobile) {

                if ($request->name == null) {
                    $request->name = 'بدون اسم';
                }

                $this->records($reqID, 'customerName', $request->name);
                $this->records($reqID, 'mobile', $request->mobile);
                $this->records($reqID, 'sex', $request->sex);
                $this->records($reqID, 'birth_date', $request->birth);
                $this->records($reqID, 'birth_hijri', $request->birth_hijri);

                $this->records($reqID, 'salary', $request->salary);
                $this->records($reqID, 'regionip', $request->regionip);

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
                $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'work', $getworkValue->value);
                }

                $this->records($reqID, 'obligations_value', $request->obligations_value);
                $this->records($reqID, 'financial_distress_value', $request->financial_distress_value);

                $this->records($reqID, 'jobTitle', $request->job_title);
                $this->records($reqID, 'rank', $request->rank);

                $updateResult = DB::table('customers')->where([
                    ['id', '=', $customerId],
                ])->update([
                    'name'                     => $request->name,
                    'mobile'                   => $request->mobile,
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
                    'region_ip'                => $request->regionip,
                ]);

                //
                $name = $request->jointname;
                $mobile = $request->jointmobile;
                $birth = $request->jointbirth;
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

                $this->records($reqID, 'jointJobTitle', $job_title);
                $this->records($reqID, 'jointRank', $job_title);

                $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'jointWork', $getworkValue->value);
                }

                DB::table('joints')->where('id', $jointId)->update([
                    'name'          => $name,
                    'mobile'        => $mobile,
                    'salary'        => $salary,
                    'birth_date'    => $birth,
                    'age'           => $age,
                    'work'          => $work,
                    'salary_id'     => $salary_source,
                    'military_rank' => $rank,
                    'madany_id'     => $madany,
                    'job_title'     => $job_title,
                    'funding_id'    => $jointfunding_source,
                    'askary_id'     => $askary_work,
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
                $this->records($reqID, 'realStatus', $request->realstatus);
                $this->records($reqID, 'realCost', $request->realcost);
                $this->records($reqID, 'owning_property', 'لا');
                if ($request->owning_property == 'yes') {
                    $this->records($reqID, 'owning_property', 'نعم');
                }
                $this->records($reqID, 'mortValue', $request->mortgage_value);
                $gettypeValue = DB::table('real_types')->where('id', $request->realtype)->first();
                if (!empty($gettypeValue)) {
                    $this->records($reqID, 'realType', $gettypeValue->value);
                }

                DB::table('real_estats')->where('id', $realId)->update([
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

                if ($fundingReq->type == 'رهن' || $fundingReq->type == 'تساهيل') { //add tsaheel info

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

                    DB::table('prepayments')->where('id', $payId)->update([
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

                    $payupdate = DB::table('prepayments')->where('id', $payId)->update([
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
                }
                //

                ////********************REMINDERS BODY************************* */

                //only one reminder to each request
                $checkFollow = DB::table('notifications')->where('req_id', '=', $reqID)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->where('status', '=', 2)->first(); // check dublicate

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

                        $overWriteReminder = DB::table('notifications')->where('id', $checkFollow->id)->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy

                    }
                }
                else {

                    #if empty reminder, so the reminder ll remove if it's existed.
                    if (!empty($checkFollow)) {
                        DB::table('notifications')->where('id', $checkFollow->id)->delete();
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

                DB::table('fundings')->where('id', $fundingId)->update([
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

                if ($fundingReq->is_approved_by_mortgageManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Mortgage Manager'),
                                                             'user_id'      => (auth()->user()->id),
                                                             'history_date' => (Carbon::now('Asia/Riyadh')),
                                                             'req_id'       => $reqID,
                    ]);
                }
                if ($fundingReq->is_approved_by_salesManager == 1 || $fundingReq->is_aqar_approved_by_salesManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Sales Manager'),
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

                DB::table('requests')->where('id', $reqID)->update([
                    'is_approved_by_generalManager'      => 0,
                    'approved_date_generalManager'       => null,
                    'is_approved_by_mortgageManager'     => 0,
                    'approved_date_mortgageManager'      => null,
                    'is_approved_by_salesManager'        => 0,
                    'approved_date_salesManager'         => null,
                    'is_aqar_approved_by_generalManager' => 0,
                    'approved_aqar_date_generalManager'  => null,
                    'is_approved_by_fundingManager'      => 0,
                    'approved_date_fundingManager'       => null,
                    'is_approved_by_tsaheel_acc'         => 0,
                    'is_approved_by_wsata_acc'           => 0,
                    'noteWebsite'                        => $webcomm,
                    'gm_comment'                         => $reqcomm,
                    'updated_at'                         => $update,
                    'is_aqar_approved_by_salesManager'   => 0,
                    'approved_aqar_date_salesManager'    => null,
                ]);

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, $fundingReq->statusReq, $fundingReq->user_id, $reqclass);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::
                //

                //

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function records($reqID, $coloum, $value)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')->where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment

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

    public function reqArchive(Request $request, $id)
    {

        $fundingReq = DB::table('requests')->where('id', $request->id)->first();
        $archRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
            $query->where('statusReq', 12) //wating for general maanager
            ->orWhere('statusReq', 23) //mor-pur in general maanager
            ->orWhere('statusReq', 32);
        })->update(['statusReq' => 14, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //archive request in general maanager

        if ($archRequest == 0) // not updated
        {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }

        if ($archRequest == 1) { // updated sucessfully

            //for quality intent::::::::::::::::

            if (MyHelpers::checkQualityReq($request->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 14, $fundingReq->user_id, $fundingReq->class_id_gm);
            }

            //end quality :::::::::::::::::::::::::::::::::::::::
            return redirect()->route('general.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function rejectReq(Request $request)
    {

        $restRequest1 = DB::table('requests')->where('id', $request->id)->where('isSentGeneralManager', 1)->first();

        $userID = $restRequest1->user_id;

        $userInfo = DB::table('users')->where('id', $userID)->first();

        if (!empty($restRequest1)) {

            if ($restRequest1->type == 'رهن-شراء') {
                $restRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 23) //wating for generral manager
                ->update(['statusReq' => 25]); //reject from generral manager
            }
            elseif ($restRequest1->type == 'تساهيل') {
                $restRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 32) //wating for generral manager
                ->update(['statusReq' => 33, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //reject from generral manager

            }
            else {
                $restRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 12) //wating for generral manager
                ->update(['statusReq' => 13, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //reject from generral manager

            }

            if ($restRequest == 1) { //rejecting succesfully

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                if ($restRequest1->type == 'رهن-شراء' || $restRequest1->type == 'شراء') {

                    $fundingManager = $restRequest1->funding_manager_id;

                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'rejected'), $fundingManager, $request->comment);

                    //for quality intent::::::::::::::::

                    if (MyHelpers::checkQualityReq($request->id)) {
                        $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 13, $restRequest1->user_id, $restRequest1->class_id_gm);
                    }

                    //end quality :::::::::::::::::::::::::::::::::::::::

                    /*     DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'rejected'), 'user_id' => (auth()->user()->id), 'recive_id' =>  $userInfo->funding_mnager_id,
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
                }
                elseif ($restRequest1->type == 'تساهيل') {

                    $mortgageManager = $restRequest1->mortgage_manager_id;
                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'rejected'), $mortgageManager, $request->comment);

                    /*
                    DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'rejected'), 'user_id' => (auth()->user()->id), 'recive_id' =>  $userInfo->mortgage_mnager_id,
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
                else {
                    $mortgageManager = $restRequest1->mortgage_manager_id;
                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'rejected'), $mortgageManager, $request->comment);

                    /*
                    DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'rejected'), 'user_id' => (auth()->user()->id), 'recive_id' =>  $userInfo->mortgage_mnager_id,
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

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Rejecting successfully'), 'status' => $restRequest, 'id' => $request->id]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $restRequest, 'id' => $request->id]);
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

    public function approveFunding(Request $request)
    {

        $reqInfo = DB::table('requests')->where('id', $request->id)->first();
        $userInfo = DB::table('users')->where('id', $reqInfo->user_id)->first();

        if ($reqInfo->type != 'تساهيل') {
            $sendRequest = DB::table('requests')->where('id', $request->id)->where('isSentGeneralManager', 1)->where('statusReq', 12) //wating in general maanager
            ->update(['statusReq' => 16, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'class_id_agent' => 58, 'complete_date' => Carbon::now('Asia/Riyadh')->format('Y-m-d')]); //approved

            DB::table('req_records')->insert([
                'colum'          => 'class_agent',
                'user_id'        => null,
                'value'          => 58,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $request->id,
                'user_switch_id' => null,
                'comment'        => 'تلقائي - عن طريق النظام',
            ]);
        }
        else {
            $sendRequest = DB::table('requests')->where('id', $request->id)->where('isSentGeneralManager', 1)->where('statusReq', 32) //wating in general maanager
            ->update(['statusReq' => 35, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'complete_date' => Carbon::now('Asia/Riyadh')->format('Y-m-d')]);
        } //approved

        if ($sendRequest == 1) { //sent

            if ($request->comment == null) {
                $request->comment = "لايوجد";
            }

            //for quality intent::::::::::::::::

            if (MyHelpers::checkQualityReq($request->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 16, $reqInfo->user_id, $reqInfo->class_id_gm);
            }

            //end quality :::::::::::::::::::::::::::::::::::::::

            $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'), null, $request->comment);

            /*   DB::table('request_histories')->insert([ // add to request history
                'title' => MyHelpers::admin_trans(auth()->user()->id, 'Approved'), 'user_id' => (auth()->user()->id),
                'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                'req_id' => $request->id,
            ]);
            */

            //
            //Send notify for sales agent
            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'),
                                                 'recived_id' => $userInfo->id,
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 3,
                                                 'req_id'     => $request->id,
            ]);
            $emailNotify = MyHelpers::sendEmailNotifiaction('complete_req', $userInfo->id, 'طلبك أُفرغ', 'تم إفراغ طلبك'); //email notification

            //

            if ($reqInfo->type != 'تساهيل') {
                $salesManager = $reqInfo->sales_manager_id;
                //Send notify for sales manager
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'),
                                                     'recived_id' => $salesManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 3,
                                                     'req_id'     => $request->id,
                ]);

                $emailNotify = MyHelpers::sendEmailNotifiaction('complete_req', $salesManager, 'لديك طلب أُفرغ', 'تم إفراغ طلبك'); //email notification

            }
            //

            if ($reqInfo->type == 'شراء') {
                $funId = $reqInfo->funding_manager_id;
                //Send notify for funding manager
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'),
                                                     'recived_id' => $funId,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 3,
                                                     'req_id'     => $request->id,
                ]);
                //
                $emailNotify = MyHelpers::sendEmailNotifiaction('complete_req', $funId, 'لديك طلب أُفرغ', 'تم إفراغ طلبك'); //email notification

            }

            if ($reqInfo->type == 'تساهيل') {
                $morId = $reqInfo->mortgage_manager_id;
                //Send notify for funding manager
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'),
                                                     'recived_id' => $morId,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 3,
                                                     'req_id'     => $request->id,
                ]);
                //
                $emailNotify = MyHelpers::sendEmailNotifiaction('complete_req', $morId, 'لديك طلب أُفرغ', 'تم إفراغ طلبك'); //email notification

            }

            if ($reqInfo->type == 'رهن') {

                $mortgageManager = $reqInfo->mortgage_manager_id;
                //Send notify for funding manager
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Request Approved'),
                                                     'recived_id' => $mortgageManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 3,
                                                     'req_id'     => $request->id,
                ]);
                //
                $emailNotify = MyHelpers::sendEmailNotifiaction('complete_req', $mortgageManager, 'لديك طلب أُفرغ', 'تم إفراغ طلبك'); //email notification

            }

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $userInfo->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'completed_request',$request->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Approving successfully'), 'status' => $sendRequest, 'id' => $request->id]);
        }
        else // not send

        {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
        }
    }

    public function cancelFunding(Request $request)
    {

        $reqInfo = DB::table('requests')->where('id', $request->id)->first();
        $userInfo = DB::table('users')->where('id', $reqInfo->user_id)->first();

        $sendRequest = DB::table('requests')->where('id', $request->id)->where('isSentGeneralManager', 1)->whereIn('statusReq', [12, 32]) //wating for general maanager
        ->update(['statusReq' => 15, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //canceled in general manager

        if ($sendRequest == 1) { //sent

            $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Canceled'), null, null);

            /*   DB::table('request_histories')->insert([ // add to request history
                'title' => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'), 'user_id' => (auth()->user()->id),
                'history_date' => (Carbon::now('Asia/Riyadh')),
                'req_id' => $request->id,
            ]);
            */

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Canceled successfully'), 'status' => $sendRequest, 'id' => $request->id]);
        }
        else // not send

        {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
        }
    }

    public function reCancelFunding($id)
    {

        $cancelRequest = DB::table('requests')->where('id', $id)->where('isSentGeneralManager', 1)->where('statusReq', 15) //canceld in general maanager
        ->first();

        if (!empty($cancelRequest)) {

            $reCancel = DB::table('requests')->where('id', $id)->where('statusReq', 15) //canceld in general maanager
            ->where('type', '!=', 'تساهيل')->update(['statusReq' => 12, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]);  //wating for general maanager

            $reCancel = DB::table('requests')->where('id', $id)->where('statusReq', 15) //canceld in general maanager
            ->where('type', 'تساهيل')->update(['statusReq' => 32, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]);  //wating for general maanager

            if ($reCancel == 0) //nothing send
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
            else {

                $this->history($id, MyHelpers::admin_trans(auth()->user()->id, 'Recancele'), null, null);

                /*   DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Recancele'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $id,
                ]);
            */
                return redirect()->back();
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

        $docID = DB::table('documents')->insertGetId([
            'filename'    => $name,
            'location'    => $path,
            'upload_date' => $upload_date,
            'req_id'      => $reqID,
            'user_id'     => $userID,
        ]);

        //$docRow = DB::table('documents')->where('id', $docID)->first();

        $documents = DB::table('documents')->where('req_id', '=', $reqID)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
        ->select('documents.*', 'users.name')->get();

        return response()->json($documents);
    }

    public function openFile(Request $request, $id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();
        $userID = $document->user_id;

        $request = DB::table('requests')->where('id', '=', $document->req_id)->where('isSentGeneralManager', 1)->first();

        if (!empty($request)) {
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
        try {
            $filename = $document->location;
            return response()->download(storage_path('app/public/'.$filename));
        }catch (\Exception $e){
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

        }
    }

    //This new function to show dataTabel in view(GeneralManager.Request.morPurReqs)

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

    public function morPurReqs(Request $request)
    {

        $userID = (auth()->user()->id);

        $requests = DB::table('requests')->where('type', 'رهن-شراء')->where(function ($query) {
            $query->where('statusReq', 23) //approved and wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->get();

        // dd(  $requests);

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($requests)) {
            $check = 0; // check if this user is belong for at lest one user (sales agent)
            return view('GeneralManager.Request.morPurReqs', compact('requests', 'check', 'notifys'));
        }

        $check = 1; // sales manager not belong with any user (sales agent)
        return view('GeneralManager.Request.morPurReqs', compact('requests', 'check', 'notifys'));
    }

    public function morPurReqs_datatable(Request $request)
    {
        $requests = DB::table('requests')->where('type', 'رهن-شراء')->where(function ($query) {
            $query->where('statusReq', 23) //approved and wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            return '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                     <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span></div>';
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
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->make(true);
    }

    public function prepaymentReqs()
    {

        $requests = DB::table('requests')->where('type', 'شراء-دفعة')->where(function ($query) {
            $query->where('requests.isSentGeneralManager', 1); //yes sent
        })->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        // dd(  $requests);

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($requests)) {
            $check = 0; // check if this user is belong for at lest one user (sales agent)
            return view('GeneralManager.Request.prepayment', compact('requests', 'check', 'notifys'));
        }

        $check = 1; // sales manager not belong with any user (sales agent)
        return view('GeneralManager.Request.prepayment', compact('requests', 'check', 'notifys'));
    }

    public function prepaymentReqs_datatable()
    {

        $requests = DB::table('requests')->where('type', 'شراء-دفعة')->where(function ($query) {
            $query->where('requests.isSentGeneralManager', 1); //yes sent
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            $data = $data.'</div>';
            return $data;
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
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
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

    public function morPurpage($id)
    {

        $userID = auth()->user()->id;

        $morPur = DB::table('requests')->where('id', '=', $id)
            // ->where(function ($query) {
            //   $query->where('statusReq', 23) //approved and wating general manager
            //     ->orWhere('isSentGeneralManager', 1); //yes sent
            //  })
            ->first();

        // dd(  $morPur);

        if (!empty($morPur)) {

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            $reqStatus = $morPur->statusReq;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();

            $cities = DB::table('cities')->select('id', 'value')->get();
            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $regions = customer::select('region_ip')->groupBy('region_ip')->get();

            $user_role = DB::table('users')->select('role')->where('id', $userID)->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

            $histories = DB::table('req_records')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
            ->get();

            $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            $notifys = $this->fetchNotify(); //get notificationes

            $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

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
            return view('GeneralManager.morPurReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'id', //Request ID
                'histories', 'documents', 'reqStatus', 'payment', 'regions', 'morPur', 'followdate', 'notifys', 'collaborator', 'cities', 'followtime', 'realTypes', 'worke_sources', 'request_sources'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function appMorPur(Request $request)
    {

        $restRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
            $query->where('statusReq', 23) //approved and wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->first();

        if (!empty($restRequest)) {

            $sendRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 23) //approved and wating general manager

            ->update(['statusReq' => 26, 'class_id_agent' => 58, 'complete_date' => Carbon::now('Asia/Riyadh')->format('Y-m-d')]); //approved

            DB::table('req_records')->insert([
                'colum'          => 'class_agent',
                'user_id'        => null,
                //'value'          => 'مكتمل',
                'value'          => 58,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $request->id,
                'user_switch_id' => null,
                'comment'        => 'تلقائي - عن طريق النظام',
            ]);

            if ($sendRequest == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
            }

            else {

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($restRequest->req_id, MyHelpers::admin_trans(auth()->user()->id, 'Approved Mor-Pur'), null, $request->comment);

                /*    DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Approved Mor-Pur'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $restRequest->req_id,
                ]);
            */

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Approving successfully'), 'status' => $sendRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function rejectMorPur(Request $request)
    {

        $restRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
            $query->where('statusReq', 23) //wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->first();

        $reqID = $restRequest->req_id;

        $userID = $restRequest->user_id;

        $userInfo = DB::table('users')->where('id', $userID)->first();

        if (!empty($restRequest)) {

            $updateRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 23) ////wating general manager

            ->update(['statusReq' => 25]); // mor-pur rejected from genral manager and redirect to funding manager

            if ($updateRequest == 1) { //rejected sucessfully

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $fundingManager = $restRequest->funding_manager_id;
                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), $fundingManager, $request->comment);

                /* DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' =>   $userInfo->funding_mnager_id,
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' =>   $reqID,
                ]);
                */

                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $fundingManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that')), 'status' => $restRequest, 'id' => $request->id]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $updateRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function cancelMorPur($id)
    {

        $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
            $query->where('statusReq', 23) //wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->first();

        if (!empty($restRequest)) {

            $cancelMor = DB::table('requests')->where('id', $id)->where('statusReq', 23) //wating general manager
            ->update(['statusReq' => 27]); //canceled general manager

            if ($cancelMor == 0) //nothing send
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
            else {

                $this->history($id, MyHelpers::admin_trans(auth()->user()->id, 'Mor-Pur Canceled'), null, null);

                /* DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Mor-Pur Canceled'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $restRequest->req_id,
                ]);
                */

                return redirect()->back();
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function restMorPur($id)
    {

        $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
            $query->where('statusReq', 27) //wating general manager
            ->orWhere('isSentGeneralManager', 1); //yes sent
        })->first();

        if (!empty($restRequest)) {

            $cancelMor = DB::table('requests')->where('id', $id)->where('statusReq', 27) // canceled
            ->update(['statusReq' => 23]); //wating general manager

            if ($cancelMor == 0) //nothing send
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
            else {

                $this->history($id, MyHelpers::admin_trans(auth()->user()->id, 'Recancle Mor-Pur'), null, null);

                /*  DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Recancle Mor-Pur'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $restRequest->req_id,
                ]);
                */
                return redirect()->back();
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function archReqArr(Request $request)
    {

        $result = DB::table('requests')->whereIn('id', $request->array)
            //  ->where('user_id',  auth()->user()->id)
            ->whereIn('statusReq', [12, 32])->update([
                'statusReq' => 14, //archived in general manager
            ]);
        return response($result); // if 1: update succesfally

    }

    public function restReqArr(Request $request)
    {

        $result = DB::table('requests')->whereIn('id', $request->array)
            //->where('user_id',  auth()->user()->id)
            ->where('type', '!=', 'تساهيل')->where('statusReq', 14) //archived in general manager

            ->update([
                'statusReq' => 12,
            ]);

        $result = DB::table('requests')->whereIn('id', $request->array)
            //->where('user_id',  auth()->user()->id)
            ->where('type', 'تساهيل')->where('statusReq', 14) //archived in general manager

            ->update([
                'statusReq' => 32,
            ]);
        return response($result); // if 1: update succesfally

    }

    public function aprroveTsaheel(Request $request)
    {

        $reqInfo = DB::table('requests')->where('requests.id', '=', $request->id)->update(['is_approved_by_generalManager' => 1, 'approved_date_generalManager' => Carbon::now('Asia/Riyadh')->format("Y-m-d")]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'General Manager approve tsaheel'),
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

        $reqInfo = DB::table('requests')->where('requests.id', '=', $request->id)->update(['is_approved_by_generalManager' => 0, 'approved_date_generalManager' => null]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'General Manager cancel approve aqar'),
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

        $reqInfo = DB::table('requests')->where('requests.id', '=', $request->id)->update(['is_aqar_approved_by_generalManager' => 1, 'approved_aqar_date_generalManager' => Carbon::now('Asia/Riyadh')->format("Y-m-d")]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'General Manager approve aqar'),
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

        $reqInfo = DB::table('requests')->where('requests.id', '=', $request->id)->update(['is_aqar_approved_by_generalManager' => 0, 'approved_aqar_date_generalManager' => null]);

        if ($reqInfo) {

            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'General Manager cancel approve aqar'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }
}
