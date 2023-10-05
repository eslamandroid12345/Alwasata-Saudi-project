<?php

namespace App\Http\Controllers;

use App\classifcation;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

class TrainingController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }

    public function myReqs()
    {
        $trainID = (auth()->user()->id);
        $salesAgents = [];
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.myReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

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
            30 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            31 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            32 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            33 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            34 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            35 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[31]);
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)

    public function myreqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function recivedReqs()
    {

        $trainID = (auth()->user()->id);
        $salesAgents = null;
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
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
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
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
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.recivedReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

        ));
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)
    public function recivedReqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
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
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
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
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function followReqs()
    {

        $trainID = (auth()->user()->id);
        $salesAgents = null;
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 1)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 1)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.followReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

        ));
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)
    public function followReqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 1)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 1)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function starReqs()
    {

        $trainID = (auth()->user()->id);
        $salesAgents = null;
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.staredReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

        ));
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)
    public function starReqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function completedReqs()
    {

        $trainID = (auth()->user()->id);
        $salesAgents = null;
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4, 31]);
                        $query->whereIn('type', ['رهن', 'شراء', 'تساهيل']);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4, 31]);
                        $query->whereIn('type', ['رهن', 'شراء', 'تساهيل']);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.completedReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

        ));
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)
    public function completedreqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4, 31]);
                        $query->whereIn('type', ['رهن', 'شراء', 'تساهيل']);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4, 31]);
                        $query->whereIn('type', ['رهن', 'شراء', 'تساهيل']);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function archReqs()
    {

        $trainID = (auth()->user()->id);
        $salesAgents = null;
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];
        $all_status = $this->status();

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where('statusReq', 2)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where('statusReq', 2)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
                $salesAgents = DB::table('users')->whereIn('id', $agent_array)->where('role', 0)->where('status', 1)->get();
            }
            else {
                $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }

            $requests = $requests->count();
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Training.Request.archReqs', compact(
            'requests',
            'all_status',
            'salesAgents',
            'worke_sources',
            'request_sources'

        ));
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)
    public function archReqs_datatable(Request $request)
    {

        $trainID = (auth()->user()->id);
        $agent_array = [];
        $type_array = [];
        $reqType_array = [];

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            $requests = DB::table('requests')
                ->where('statusReq', 2)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');
        }

        else {

            //
            if ($agents->count() != 0) {
                $agent_array = $agents->pluck('agent_id')->toArray();
            }

            if ($types->count() != 0) {
                $type_array = $types->pluck('type')->toArray();

                if (in_array(0, $type_array)) {
                    $reqType_array[] = 'شراء';
                }

                if (in_array(1, $type_array)) {
                    $reqType_array[] = 'رهن';
                }

                if (in_array(2, $type_array)) {
                    $reqType_array[] = 'شراء-دفعة';
                }

                if (in_array(3, $type_array)) {
                    $reqType_array[] = 'رهن-شراء';
                }
            }
            //

            $requests = DB::table('requests')
                ->where('statusReq', 2)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')
                ->orderBy('req_date', 'DESC');

            if (count($agent_array) != 0) {
                $requests = $requests->whereIn('requests.user_id', $agent_array);
            }

            if (count($reqType_array) != 0) {
                $requests = $requests->whereIn('requests.type', $reqType_array);
            }
        }

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
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

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if ($row->type == 'رهن-شراء') {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('training.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

    public function fundingreqpage($id)
    {
        if ($this->checkPremation($id)) {

            $request = DB::table('requests')->where('requests.id', '=', $id)->first();

            $reqStatus = $request->statusReq;

            $purchaseCustomer = DB::table('requests')
                ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('requests.id', '=', $id)
                ->first();

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
                ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')
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

            $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
                ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
                ->where('user_collaborators.user_id', auth()->user()->id)
                ->get();

            $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();

            if ($request->type == 'شراء-دفعة' && $payment == null) {
                $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)
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
            $classifcations = DB::table('classifcations')->select('id', 'value')->get();

            $documents = DB::table('documents')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                ->select('documents.*', 'users.name')
                ->get();

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

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            return view('Training.fundingReq.fundingreqpage', compact(
                'purchaseCustomer',
                'purchaseJoint',
                'purchaseReal',
                'purchaseFun',
                'purchaseTsa',
                'purchaseClass',
                'salary_sources',
                'funding_sources',
                'askary_works',
                'madany_works',
                'classifcations',
                'id', //Request ID
                'documents',
                'reqStatus',
                'payment',
                'followdate',
                'collaborator',
                'cities',
                'ranks',
                'collaborators',
                'paymentForDisplayonly',
                'followtime',
                'realTypes',
                'worke_sources',
                'request_sources'
            ));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
        }
    }

    public function checkPremation($reqId)
    {

        $trainID = (auth()->user()->id);

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);
        $types = DB::table('training_and_req_types')->where('training_id', $trainID);

        if ($agents->count() == 0 && $types->count() == 0) {
            return true;
        } //no premation

        $flag = false;
        $type = null;

        $request = DB::table('requests')->where('requests.id', '=', $reqId)->first();

        if ($agents->count() != 0) {
            $agents = $agents->where('agent_id', $request->user_id)->first();

            if ($agents) {
                $flag = true;
            }
        }

        if ($types->count() != 0) {

            if ($request->type == 'شراء') {
                $type = 0;
            }
            if ($request->type == 'رهن') {
                $type = 1;
            }
            if ($request->type == 'شراء-دفعة') {
                $type = 2;
            }
            if ($request->type == 'رهن-شراء') {
                $type = 3;
            }

            $types = $types->where('type', $type)->first();

            if ($types) {
                $flag = true;
            }
        }

        return $flag;
    }

    public function morPurpage($id)
    {
        if ($this->checkPremation($id)) {

            $request = DB::table('requests')->where('requests.id', '=', $id)->first();

            $reqStatus = $request->statusReq;

            $purchaseCustomer = DB::table('requests')
                ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('requests.id', '=', $id)
                ->first();

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
                ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')
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

            $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
                ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
                ->where('user_collaborators.user_id', auth()->user()->id)
                ->get();

            $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();

            if ($request->type == 'شراء-دفعة' && $payment == null) {
                $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)
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
            $classifcations = DB::table('classifcations')->select('id', 'value')->get();

            $documents = DB::table('documents')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                ->select('documents.*', 'users.name')
                ->get();

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

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            return view('Training.morPurReq.fundingreqpage', compact(
                'purchaseCustomer',
                'purchaseJoint',
                'purchaseReal',
                'purchaseFun',
                'purchaseTsa',
                'purchaseClass',
                'salary_sources',
                'funding_sources',
                'askary_works',
                'madany_works',
                'classifcations',
                'id', //Request ID
                'documents',
                'reqStatus',
                'payment',
                'followdate',
                'collaborator',
                'cities',
                'ranks',
                'collaborators',
                'paymentForDisplayonly',
                'followtime',
                'realTypes',
                'worke_sources',
                'request_sources'
            ));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
        }
    }
}
