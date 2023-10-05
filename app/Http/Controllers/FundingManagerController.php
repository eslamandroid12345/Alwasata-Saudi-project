<?php

namespace App\Http\Controllers;

use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\funding_source;
use App\madany_work;
use App\salary_source;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use App\Models\WasataRequestes;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class FundingManagerController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'               => ['layouts.content'],
            'App\Composers\ActivityComposer'           => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer'   => ['layouts.content'],
            'App\Composers\ClassificationsComposer'    => ['FundingManager.Request.filterReqs'],
            'App\Composers\CollaboratorsComposer'      => ['FundingManager.Request.filterReqs'],
            'App\Composers\FundingOptionsComposer'     => ['FundingManager.Request.filterReqs'],
            'App\Composers\EditColumnsSettingComposer' => ['FundingManager.Request.underReqs', 'FundingManager.Request.editCoulmn'],
        ]);
    }

    /////////////////////NOTIFCATIONES////////////////////////

    public function homePage()
    {
        return view('FundingManager.home.home');
    }

    ///////////////////////////////////////////////////

    public function myReqs()
    {

        $fundingID = (auth()->user()->id);

        //$myusers = MyHelpers::extractUsersFunding($fundingID);

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('isSentFundingManager', 1) //yes sent
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $request_for_salesAgent = DB::table('requests')
            ->where('isSentFundingManager', 1) //yes sent
            ->where('requests.funding_manager_id', $fundingID)
            ->pluck('user_id');

        $salesAgents = User::whereIn('id', $request_for_salesAgent)->get();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('isSentFundingManager', 1) //yes sent
            ->where('requests.funding_manager_id', $fundingID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('FundingManager.Request.myReqs', compact(
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

    ///

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

    public function myReqs_datatable(Request $request)
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('isSentFundingManager', 1) //yes sent
            //   ->where('isUnderProcFund', 0) //still not under funding process
            //  ->where('type', 'شراء') //funding will recive only purchase reqs
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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
/*
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
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            if ($row->isUnderProcFund == 0 && ($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {
                $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                        <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                    </span>';
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
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_fm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->make(true);
    }

    public function recivedReqs()
    {
        $fundingID = (auth()->user()->id);

        //$myusers = MyHelpers::extractUsersFunding($fundingID);

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء']);
                    $query->where('requests.isSentFundingManager', 1);
                });
                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });
                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 7]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->where('requests.funding_manager_id', $fundingID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 7]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $request_for_salesAgent = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 7]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->pluck('user_id');

        $salesAgents = User::whereIn('id', $request_for_salesAgent)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('FundingManager.Request.recivedReqs', compact(
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

    public function recivedReqs_datatable(Request $request)
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء']);
                    $query->where('requests.isSentFundingManager', 1);
                });
                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });
                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 7]);
                    $query->whereIn('statusReq', [6, 13]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_canceled', 0)
            ->where('is_followed', 0)
            ->where('is_stared', 0)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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
/*
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
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            if ($row->isUnderProcFund == 0) {
                $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                    <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                </span>';
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
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_fm;
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

    public function followReqs()
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {
                $query->where('statusReq', 6) //wating for funding manager approval
                ->orWhere('statusReq', 13); //rejected from general maanger

            })
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_followed', 1)
            ->where('isSentFundingManager', 1) //yes sent
            ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $check = 1; // sales manager not belong with any user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('FundingManager.Request.myReqs', compact('requests', 'check', 'worke_sources',
            'request_sources'));
    }

    public function followReqs_datatable()
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->whereIn('statusReq', [6, 13]) //(6:wating for funding manager approval ,13:rejected from general maanger)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_followed', 1)
            ->where('isSentFundingManager', 1) //yes sent
            ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC');

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                    <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                </span>';
            $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                <a href="'.route('funding.manager.restoreRequest', $row->id).'"> <i class="fas fa-reply"></i></a>
                          </span>';
            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                          <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

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

    public function starReqs()
    {

        $fundingID = (auth()->user()->id);
        $myusers = MyHelpers::extractUsersFunding($fundingID);
        // dd( $myusers);

        $requests = [];

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                foreach ($myuser as $user) {
                    $requests[] = DB::table('requests')->where('requests.user_id', $user->id)
                        ->where(function ($query) {
                            $query->where('statusReq', 6) //wating for funding manager approval
                            ->orWhere('statusReq', 13); //rejected from general maanger

                        })
                        ->where('isUnderProcFund', 0) //still not under funding process
                        ->where('is_stared', 1)
                        ->where('isSentFundingManager', 1) //yes sent
                        ->where('type', 'شراء') //funding will recive only purchase reqs
                        ->join('customers', 'customers.id', '=', 'requests.customer_id')
                        ->select('requests.*', 'customers.name')
                        ->orderBy('req_date', 'DESC')
                        ->get();
                }
            }

            // dd( $requests);

            $check = 0; // check if this user is belong for at lest one user
            return view('FundingManager.Request.staredReqs', compact('requests', 'check', 'notifys'));
        }

        $check = 1; // sales manager not belong with any user
        return view('FundingManager.Request.myReqs', compact('requests', 'check', 'notifys'));
    }

    public function fetchNotify()
    { // to get notificationes of users

        $checkFollow = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->where('reminder_date', "<=", Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s"))
            ->where('status', 2) //Not Active (for following)
            ->first();

        if (!empty($checkFollow)) {
            DB::table('notifications')->where('id', $checkFollow->id)
                ->update([
                    'status'     => 0,
                    'created_at' => Carbon::now('Asia/Riyadh'),
                ]);
        }

        return DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('notifications.status', 0) // new
            ->orderBy('notifications.id', 'DESC')
            ->select('notifications.*', 'customers.name')
            ->get();
    }

    public function starReqs_datatable()
    {

        $fundingID = (auth()->user()->id);
        $myusers = MyHelpers::extractUsersFunding($fundingID);

        $requests = [];
        $all = [];
        if (!empty($myusers[0])) {
            foreach ($myusers as $myuser) {
                foreach ($myuser as $user) {
                    $requests[] = DB::table('requests')->where('requests.user_id', $user->id)
                        ->whereIn('statusReq', [6, 13]) // (6:wating for funding manager approval , 13:rejected from general maanger )
                        ->where('isUnderProcFund', 0) //still not under funding process
                        ->where('is_stared', 1)
                        ->where('isSentFundingManager', 1) //yes sent
                        ->where('type', 'شراء') //funding will recive only purchase reqs
                        ->join('customers', 'customers.id', '=', 'requests.customer_id')
                        ->select('requests.*', 'customers.name')
                        ->orderBy('req_date', 'DESC')
                        ->get();
                }
            }
        }
        foreach ($requests as $request => $reqs) {
            foreach ($reqs as $req) {
                array_push($all, $req);
            }
        }

        return Datatables::of($all)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                    <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                </span>';
            $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                <a href="'.route('funding.manager.restoreRequest', $row->id).'"> <i class="fas fa-reply"></i></a>
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

    public function canceledReqs()
    {

        $fundingID = (auth()->user()->id);
        $myusers = MyHelpers::extractUsersFunding($fundingID);
        // dd( $myusers);

        $requests = [];

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                foreach ($myuser as $user) {
                    $requests[] = DB::table('requests')->where('requests.user_id', $user->id)
                        ->where(function ($query) {
                            $query->where('statusReq', 6) //wating for funding manager approval
                            ->orWhere('statusReq', 13); //rejected from general maanger

                        })
                        ->where('isUnderProcFund', 0) //still not under funding process
                        ->where('is_canceled', 1)
                        ->where('isSentFundingManager', 1) //yes sent
                        ->where('type', 'شراء') //funding will recive only purchase reqs
                        ->join('customers', 'customers.id', '=', 'requests.customer_id')
                        ->select('requests.*', 'customers.name')
                        ->orderBy('req_date', 'DESC')
                        ->get();
                }
            }

            //    dd( $requests);

            $check = 0; // check if this user is belong for at lest one user
            return view('FundingManager.Request.canceledReqs', compact('requests', 'check', 'notifys'));
        }

        $check = 1; // sales manager not belong with any user
        return view('FundingManager.Request.myReqs', compact('requests', 'check', 'notifys'));
    }

    //This new function to show dataTabel in view(FundingManager.Request.canceledReqs)
    public function canceledReqs_datatable()
    {

        $fundingID = (auth()->user()->id);
        $myusers = MyHelpers::extractUsersFunding($fundingID);
        $requests = [];
        $all = [];
        if (!empty($myusers[0])) {
            foreach ($myusers as $myuser) {
                foreach ($myuser as $user) {
                    $requests[] = DB::table('requests')->where('requests.user_id', $user->id)
                        ->whereIn('statusReq', [6, 13]) //(6:wating for funding manager approval ,13:rejected from general maanger)
                        ->where('isUnderProcFund', 0) //still not under funding process
                        ->where('is_canceled', 1)
                        ->where('isSentFundingManager', 1) //yes sent
                        ->where('type', 'شراء') //funding will recive only purchase reqs
                        ->join('customers', 'customers.id', '=', 'requests.customer_id')
                        ->select('requests.*', 'customers.name')
                        ->orderBy('req_date', 'DESC')
                        ->get();
                }
            }
        }

        foreach ($requests as $request => $reqs) {
            foreach ($reqs as $req) {
                array_push($all, $req);
            }
        }

        return Datatables::of($all)->addColumn('status', function ($row) {
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
                                         <a href="'.route('funding.manager.morPurRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                                     </span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                         <a href="'.route('funding.manager.fundingRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                                     </span>';
            }

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                     <a href="'.route('funding.manager.restoreRequest', $row->id).'"> <i class="fas fa-reply"></i></a>
                                 </span>';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function completedReqs()
    {
        $fundingID = (auth()->user()->id);
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();

        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [6, 13, 8]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [0, 7, 10]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            // ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customerIDS)->get();

        $request_for_sales_agent = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [6, 13, 8]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [0, 7, 10]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            // ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->pluck('user_id');

        $salesAgents = User::whereIn('id', $request_for_sales_agent)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [6, 13, 8]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->whereNotIn('prepayments.payStatus', [0, 7, 10]);
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            // ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('FundingManager.Request.completedReqs', compact(
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

    public function completedReqs_datatable(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [6, 13, 8]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [21, 25]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [0, 7, 10]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            // ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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
/*
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
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';
            }
            else {
                $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
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
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_fm;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
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
            ->first();

        $fundingReq = $restRequest->funding_manager_id;

        //dd($restRequest);

        if (!empty($restRequest) && $fundingReq == $userID) {
            $restRequest = DB::table('requests')->where('requests.id', $id)
                ->update(['is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //set request

            $restRequest = DB::table('requests')->where('id', $id)
                ->update(['is_'.$action => 1]);

            if ($restRequest == 1) {
                return redirect()->route('funding.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
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

        $fundingID = (auth()->user()->id);

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('statusReq', 8) //archived in funding manager
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isSentFundingManager', 1)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $customer_request = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('statusReq', 8) //archived in funding manager
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isSentFundingManager', 1)
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $customer_request)->get();

        $agent_request = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('statusReq', 8) //archived in funding manager
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isSentFundingManager', 1)
            ->pluck('user_id');

        $salesAgents = User::whereIn('id', $agent_request)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('FundingManager.Request.archReqs', compact(
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

    //This new function to show dataTabel in view(FundingManager.Request.archReqs)
    public function archReqs_datatable(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where('statusReq', 8) //archived in funding manager
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isSentFundingManager', 1)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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
/*
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
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top"  data-id="'.$row->id.'"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                      <a href="'.route('funding.manager.restoreRequest', $row->id).'"><i class="fas fa-reply"></i></a>
                                  </span> ';
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
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_fm;
            }
        })->make(true);
    }

    public function rejectReq(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('requests.id', $request->id)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->first();

        $fundingReq = $restRequest->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            $restRequest1 = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 6) //wating for funding manager approval
                    ->orWhere('statusReq', 8) //archived req in funding manager
                    ->orWhere('statusReq', 13); //rejected  from generral manager

                })
                ->update(['statusReq' => 7, 'isUnderProcFund' => 0, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //reject from funding manager

            if ($restRequest1 == 1) { //rejecting succesfully

                /*
                $previouesUser = DB::table('users')
                    ->where('funding_mnager_id',  $fundingID)
                    ->where('role',  1) //sales manager role that previos this funding manager
                    ->first();  //to know who's before this user once rejected
                    */
                $salesManager = MyHelpers::getSalesManagerRequest($request->id);

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'rejected'), $salesManager, $request->comment);

                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $salesManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 7, $restRequest->user_id, $restRequest->class_id_fm);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::

                /*  DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'rejected'), 'user_id' => (auth()->user()->id), 'recive_id' => $previouesUser->id,
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

    public function restReq($id)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $id)->first();
        $fundingReq = $restRequest->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            $restRequest = DB::table('requests')->where('id', $id)
                ->where(function ($query) {
                    $query->where('statusReq', 8) //archive request in funding manager
                    ->orWhere('is_canceled', 1)
                        ->orwhere('is_stared', 1)
                        ->orwhere('is_followed', 1);
                })
                ->update(['statusReq' => 6, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for funding manager approval

            if ($restRequest == 0) // not updated
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            if ($restRequest == 1) // updated sucessfully
            {
                return redirect()->route('funding.manager.archRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function morPurReqs(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('requests.funding_manager_id', $fundingID)
            ->where('type', 'رهن-شراء')
            ->where('isSentFundingManager', 1)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->get();

        $check = 1; // sales manager not belong with any user (sales agent)

        return view('FundingManager.Request.morPurReqs', compact('requests', 'check'));
    }

    public function morPurReqs_datatable()
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('requests.funding_manager_id', $fundingID)
            ->where('type', 'رهن-شراء')
            ->where('isSentFundingManager', 1)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC');

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            if ($row->isUnderProcFund == 0 && ($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {
                $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                        <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                    </span>';
            }
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

    public function rejReqs()
    {

        $fundingID = (auth()->user()->id);

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('statusReq', 13); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 25);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                /*   $query->orWhere(function ($query) {
                                $query->where('prepayments.payStatus', 3);
                                $query->where('type', 'شراء');
                            });
                            */
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $requests_for_customer = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('statusReq', 13); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 25);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                /*   $query->orWhere(function ($query) {
                                $query->where('prepayments.payStatus', 3);
                                $query->where('type', 'شراء');
                            });
                            */
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->pluck('customer_id');

        $customers = customer::whereIn('id', $requests_for_customer)->get();

        $requests_for_user = DB::table('requests')
            //->whereIn('requests.user_id',   $fn)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('statusReq', 13); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 25);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                /*   $query->orWhere(function ($query) {
                            $query->where('prepayments.payStatus', 3);
                            $query->where('type', 'شراء');
                        });
                        */
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->pluck('user_id');

        $salesAgents = User::whereIn('id', $requests_for_user)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        //  dd( $requests);

        $check = 1; // sales manager not belong with any user
        return view('FundingManager.Request.rejReqs', compact(
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

    public function rejReqs_datatable(Request $request)
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('statusReq', 13); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 25);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                /*   $query->orWhere(function ($query) {
                                $query->where('prepayments.payStatus', 3);
                                $query->where('type', 'شراء');
                            });
                            */
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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
/*
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
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            if ($row->isUnderProcFund == 0) {
                $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                    <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
                                </span>';
            }
            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_fm;
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
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->make(true);
    }

    public function addUnder($id)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $id)->first();
        $fundingReq = $restRequest->funding_manager_id;

        if ($restRequest->recived_date_report == null) {
            $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        }
        else {
            $date = Carbon::parse($restRequest->recived_date_report);
            $now = Carbon::now();

            $counter = $date->diffInDays($now);
            if ($counter >= 30) {
                $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
            }
            else {
                $reqdate = $restRequest->recived_date_report;
            }
        }

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('isUnderProcFund', 0) //not under process
                ->update(['isUnderProcFund' => 1, 'recived_date_report' => $reqdate, 'counter_report' => 0, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //under funding process
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Request added sucessfully'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function removeUnder($id)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $id)->first();
        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('isUnderProcFund', 1) //not under process
                ->update(['isUnderProcFund' => 0, 'counter_report' => 0, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //under funding process
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Request removed sucessfully'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function underPage()
    {

        $fundingID = (auth()->user()->id);

        $salesAgents = $this->users($fundingID);
        $customers = $this->customers($fundingID);
        $requests = $this->underPageRequests($fundingID);

        foreach ($requests as $request) {
            $counter = $this->counter($request->recived_date_report);

            if ($request->counter_report != $counter) {
                DB::table('requests')->where('id', $request->id)
                    ->update([
                        'counter_report' => $counter,
                    ]);
            }
        }

        //---------- to get last update----------------
        //            $requests = $this->underPageRequests($fn);

        $requests = sizeof($requests);
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        //        dd($requests ,$customers ,$salesAgents);
        return view(
            'FundingManager.Request.underReqs',
            [
                'requests'        => $requests,
                'customers'       => $customers,
                'salesAgents'     => $salesAgents,
                'worke_sources'   => $worke_sources,
                'request_sources' => $request_sources,
            ]
        );
    }

    public function users($fundingID)
    {
        $userIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $users)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('requests.statusReq', [6, 8, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('requests.statusReq', [21, 25, 17]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 1) //still not under funding process
            ->pluck('user_id');

        $users = User::whereIn('id', $userIDS)->get();
        return $users;
    }

    public function customers($fundingID)
    {
        $customerIDS = DB::table('requests')
            //->whereIn('requests.user_id',  $users)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('requests.statusReq', [6, 8, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('requests.statusReq', [21, 25, 17]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.funding_manager_id', $fundingID)
            ->where('isUnderProcFund', 1) //still not under funding process

            ->pluck('customer_id');
        $customers = customer::whereIn('id', $customerIDS)->get();
        return $customers;
    }

    public function underPageRequests($fundingID)
    {
        return DB::table('requests')
            //->whereIn('requests.user_id',  $users)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('requests.statusReq', [6, 8, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('requests.statusReq', [21, 25, 17]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->where('isUnderProcFund', 1) //still not under funding process
            //->where('isSentFundingManager', 1) //yes sent
            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->where('requests.funding_manager_id', $fundingID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'users.name as user_name', 'customers.name as cust_name', 'customers.mobile', 'customers.salary_id', 'real_estats.type as realtype', 'real_estats.name as realname', 'real_estats.mobile as realmobile', 'real_estats.city as realcity', 'real_estats.pursuit',
                'prepayments.mortCost', 'prepayments.profCost', 'prepayments.payStatus', 'fundings.funding_source')
            ->orderBy('req_date', 'DESC')
            ->get();
    }

    public function counter($date)
    {
        $date = Carbon::parse($date);
        $now = Carbon::now();
        $counter = $date->diffInDays($now);
        return $counter;
    }

    public function underpage_datatable(Request $request)
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('requests.statusReq', [6, 8, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة']);
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('requests.statusReq', [21, 25, 17]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->where('isUnderProcFund', 1) //still not under funding process
            //->where('isSentFundingManager', 1) //yes sent
            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('joints', 'joints.id', '=', 'requests.joint_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                'customers.name as cust_name',
                'customers.mobile',
                'customers.has_obligations',
                'customers.military_rank',
                'customers.is_supported',
                'customers.salary_id',
                'customers.birth_date_higri',
                'customers.salary',
                'customers.work',
                'customers.madany_id',
                'joints.name as joint_name',
                'joints.mobile as joint_mobile',
                'joints.salary as joint_salary',
                'joints.salary_id as joint_salary_id',
                'joints.birth_date_higri as joint_birth_date_higri',
                'joints.work as joint_work',
                'joints.madany_id as joint_madany_id',
                'joints.military_rank as joint_military_rank',
                'real_estats.type as realtype',
                'real_estats.age as real_age',
                'real_estats.status as real_status',
                'real_estats.evaluated as real_evaluated',
                'real_estats.tenant as real_tenant',
                'real_estats.mortgage as real_mortgage',
                'real_estats.cost as real_cost',
                'real_estats.name as realname',
                'real_estats.mobile as realmobile',
                'real_estats.city as realcity',
                'real_estats.mortgage_value as mortgage_value',
                'real_estats.pursuit',
                'prepayments.mortCost',
                'prepayments.profCost',
                'fundings.funding_source as funding_funding_source',
                'fundings.funding_duration as funding_funding_duration',
                'fundings.personalFun_pre as funding_personalFun_pre',
                'fundings.personalFun_cost as funding_personalFun_cost',
                'fundings.realFun_pre as funding_realFun_pre',
                'fundings.realFun_cost as funding_realFun_cost',
                'fundings.ded_pre as funding_ded_pre',
                'fundings.monthly_in as funding_monthly_in',
                'prepayments.payStatus',
                'prepayments.realCost',
                'prepayments.incValue',
                'prepayments.prepaymentVal',
                'prepayments.prepaymentPre',
                'prepayments.prepaymentCos',
                'fundings.funding_source'
            )
            ->orderBy('empBank', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {

            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
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

        if ($request->get('bank_employee')) {
            $requests = $requests->where('empBank', $request->get('bank_employee'));
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

        //if ($request->has('search')) {
        //    if (array_key_exists('value', $request->search)) {
        //        if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
        //            $mobile = DB::table('customers')->where('mobile', $request->search['value']);
        //            if ($mobile->count() == 0) {
        //                $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
        //                if ($mobiles != null) {
        //                    $requests = $requests->where('customer_id', $mobiles->customer_id);
        //                }
        //            }
        //            else {
        //                $requests = $requests->where('customers.mobile', $request->search['value']);
        //            }
        //        }
        //        $search = $request->search;
        //        $search['value'] = null;
        //        $request->merge([
        //            'search' => $search,
        //        ]);
        //    }
        //
        //}

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                                    <a href="'.route('funding.manager.removeUnderProcess', $row->id).'"> <i class="fas fa-times"></i></a>
                                </span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('payStatus', function ($row) {
            return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
        })->editColumn('madany_id', function ($row) {

            $workValue = madany_work::find($row->madany_id);
            if ($workValue) {
                return $workValue->value;
            }
            return $row->madany_id;
        })->editColumn('is_supported', function ($row) {

            if ($row->is_supported == 'yes' || $row->is_supported == 'Yes') {
                $row->is_supported = 'نعم';
            }
            if ($row->is_supported == 'no' || $row->is_supported == 'No') {
                $row->is_supported = 'لا';
            }

            return $row->is_supported;
        })->editColumn('joint_salary_id', function ($row) {

            $salaryValue = salary_source::find($row->joint_salary_id);
            if ($salaryValue) {
                return $salaryValue->value;
            }
            return $row->joint_salary_id;
        })->editColumn('joint_madany_id', function ($row) {

            $workValue = madany_work::find($row->joint_madany_id);
            if ($workValue) {
                return $workValue->value;
            }
            return $row->joint_madany_id;
        })->editColumn('joint_military_rank', function ($row) {

            $workValue = madany_work::find($row->joint_military_rank);
            if ($workValue) {
                return $workValue->value;
            }
            return $row->joint_military_rank;
        })->editColumn('has_obligations', function ($row) {

            if ($row->has_obligations == 'yes' || $row->has_obligations == 'Yes') {
                $row->has_obligations = 'نعم';
            }
            if ($row->has_obligations == 'no' || $row->has_obligations == 'No') {
                $row->has_obligations = 'لا';
            }

            return $row->has_obligations;
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
        })->editColumn('military_rank', function ($row) {

            $workValue = DB::table('military_ranks')
                ->where('id', $row->military_rank)
                ->first();

            if ($workValue) {
                return $workValue->value;
            }
            return $row->military_rank;
        })->editColumn('work', function ($row) {

            $worke_sources = WorkSource::where('id', $row->work)
                ->first();

            if ($worke_sources) {
                return $worke_sources->value;
            }
            return $row->work;

        })->addColumn('recived_date_report', function ($row) {
            $data = $row->recived_date_report.' <br />'.$row->counter_report.' يوم ';
            return $data;
        })->editColumn('fm_comment', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<textarea title="'.$row->fm_comment.'"  id="reqComment'.$row->id.'" class="textarea"  onblur="savecomm('.$row->id.')" >'.$row->fm_comment.' </textarea>';
            }
            else {
                $data = '<textarea  title="'.$row->fm_comment.'" disabled id="reqComment'.$row->id.'" class="textarea"  >'.$row->fm_comment.' </textarea>';
            }
            return $data;
        })->editColumn('pursuit', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="reqprof'.$row->id.'"  contenteditable="true" onblur="savecost('.$row->id.')" >'.$row->pursuit.' </p>';
            }
            else {
                $data = '<p  id="reqprof'.$row->id.'" >'.$row->pursuit.' </p>';
            }
            return $data;
        })->editColumn('mortgage_value', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="reqmor'.$row->id.'"  contenteditable="true" onblur="savecost('.$row->id.')" >'.$row->mortgage_value.' </p>';
            }
            else {
                $data = '<p  id="reqmor'.$row->id.'" >'.$row->mortgage_value.' </p>';
            }
            return $data;
        })->editColumn('realname', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="realname'.$row->id.'"  contenteditable="true" onblur="savereal('.$row->id.')" >'.$row->realname.' </p>';
            }
            else {
                $data = '<p  id="realname'.$row->id.'" >'.$row->realname.' </p>';
            }
            return $data;
        })->editColumn('realmobile', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="realmobile'.$row->id.'"  contenteditable="true" onblur="savereal('.$row->id.')" >'.$row->realmobile.' </p>';
            }
            else {
                $data = '<p  id="realmobile'.$row->id.'" >'.$row->realmobile.' </p>';
            }
            return $data;
        })->editColumn('empBank', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="empBank'.$row->id.'"  contenteditable="true" onblur="saveit('.$row->id.')" >'.$row->empBank.' </p>';
            }
            else {
                $data = '<p  id="empBank'.$row->id.'" >'.$row->empBank.' </p>';
            }
            return $data;
        })->editColumn('reqNoBank', function ($row) {

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<p  id="reqNoBank'.$row->id.'"  contenteditable="true" onblur="saveit('.$row->id.')" >'.$row->reqNoBank.' </p>';
            }
            else {
                $data = '<p  id="reqNoBank'.$row->id.'" >'.$row->reqNoBank.' </p>';
            }
            return $data;
        })->editColumn('realcity', function ($row) {

            $cities = DB::table('cities')->get();
            $city = DB::table('cities')->where('id', $row->realcity)->first();

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<select  id="city'.$row->id.'" style="width:auto;"  name="city" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saverealcity(this,'.$row->id.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($cities as $city) {

                    if ($city->id == $row->realcity) {
                        $data = $data.'<option value="'.$city->id.'" selected>'.$city->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$city->id.'" >'.$city->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {

                if ($city != null) {
                    return $city->value;
                }
                else {
                    return $row->realcity;
                }
            }
        })->editColumn('salary_id', function ($row) {

            $salary_sources = DB::table('salary_sources')->where('id', $row->salary_id)->first();

            if ($salary_sources != null) {
                return $salary_sources->value;
            }
            else {
                return $row->salary_id;
            }
        })->editColumn('funding_source', function ($row) {

            $funding_sources = DB::table('funding_sources')->get();
            $funding_sour = DB::table('funding_sources')->where('id', $row->funding_source)->first();

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<select  id="funSour'.$row->id.'" style="width:auto;"  name="funSour" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); savefunSour(this,'.$row->id.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($funding_sources as $funding_source) {

                    if ($funding_source->id == $row->funding_source) {
                        $data = $data.'<option value="'.$funding_source->id.'" selected>'.$funding_source->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$funding_source->id.'" >'.$funding_source->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                if ($funding_sour != null) {
                    return $funding_sour->value;
                }
                else {
                    return $row->funding_source;
                }
            }
        })->editColumn('class_id_fm', function ($row) {

            $classifcations_fm = classifcation::where('user_role', 2)->get();

            if (($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 17 || $row->statusReq == 21 || $row->statusReq == 25)) {

                $data = '<select  id="reqClass'.$row->id.'" style="width:auto;"  name="reqClass" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saveclass(this,'.$row->id.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($classifcations_fm as $classifcations) {

                    if ($classifcations->id == $row->class_id_fm) {
                        $data = $data.'<option value="'.$classifcations->id.'" selected>'.$classifcations->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$classifcations->id.'">'.$classifcations->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                $classifcations_sa = classifcation::where('id', $row->class_id_fm)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_fm;
                }
            }
        })->editColumn('realtype', function ($row) {

            $realTypes = DB::table('real_types')->get();

            $data = '<select  id="realType'.$row->id.'" style="width:auto;"  name="realType" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saverealType(this,'.$row->id.')" >';

            $data = $data.'<option value="" selected>---</option>';

            foreach ($realTypes as $realType) {

                if ($realType->id == $row->realtype) {
                    $data = $data.'<option value="'.$realType->id.'" selected>'.$realType->value.'</option>';
                }
                else {
                    $data = $data.'<option value="'.$realType->id.'" >'.$realType->value.'</option>';
                }
            }

            $data = $data.'</select>';

            return $data;
        })->rawColumns(['recived_date_report', 'pursuit', 'reqNoBank', 'empBank', 'realmobile', 'realname', 'mortgage_value', 'action', 'fm_comment', 'class_id_fm', 'realtype', 'funding_source', 'realcity'])
            ->make(true);
    }

    public function prepaymentReqs()
    {

        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.payStatus', '!=', null);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC')
            ->count();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $check = 1; // sales manager not belong with any user
        return view('FundingManager.Request.prepayment', compact('requests', 'check', 'worke_sources',
            'request_sources'));
    }

    public function prepaymentReqs_datatable()
    {
        $fundingID = (auth()->user()->id);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.payStatus', '!=', null);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            ->where('requests.funding_manager_id', $fundingID)
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name', 'prepayments.payStatus')
            ->orderBy('req_date', 'DESC');

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('funding.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            if ($row->isUnderProcFund == 0 && ($row->statusReq == 6 || $row->statusReq == 8 || $row->statusReq == 13 || $row->statusReq == 21 || $row->statusReq == 25) && ($row->payStatus == 0 || $row->payStatus == 3 || $row->payStatus == 7)) {
                $data = $data.'<span class="item pointer" id="add" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add').'">
                                        <a href="'.route('funding.manager.addUnderProcess', $row->id).'"> <i class="fas fa-plus"></i></a>
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

    public function fundingreqpage($id)
    {

        $fundingID = auth()->user()->id;

        $request = DB::table('requests')->where('requests.id', '=', $id)
            ->first();

        $fundingReq = $request->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($fundingReq)) {
            if ($fundingID == $fundingReq) { // check if it's correct funding or not

                $purchaseCustomer = DB::table('requests')
                    ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                    ->where('requests.id', '=', $id)
                    ->first();

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
                    ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_fm')
                    ->where('requests.id', '=', $id)
                    ->first();

                if ($request->type == 'رهن-شراء') {
                    $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
                }
                else {
                    $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
                }

                // dd(  $payment);

                $purchaseTsa = DB::table('requests')
                    ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $collaborator = DB::table('requests')
                    ->join('users', 'users.id', '=', 'requests.collaborator_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                $cities = DB::table('cities')->select('id', 'value')->get();
                $ranks = DB::table('military_ranks')->select('id', 'value')->get();
                $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
                $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
                $askary_works = DB::table('askary_works')->select('id', 'value')->get();
                $madany_works = DB::table('madany_works')->select('id', 'value')->get();
                $realTypes = DB::table('real_types')->select('id', 'value')->get();

                $user_role = DB::table('users')->select('role')->where('id', $fundingID)->get();
                $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

                /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->leftjoin('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

                $documents = DB::table('documents')->where('req_id', '=', $id)
                    ->leftjoin('users', 'users.id', '=', 'documents.user_id') // to retrive user information
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

                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
                MyHelpers::openReqWillOpenNotify($id);
                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

                $worke_sources = WorkSource::all();
                $request_sources = DB::table('request_source')->get();
                //dd( $purchaseCustomer);
                return view('FundingManager.fundingReq.fundingreqpage', compact(
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
                    // 'histories',
                    'documents',
                    'reqStatus',
                    'payment',
                    'notifys',
                    'followdate',
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
    }

    public function reqArchive(Request $request, $id)
    {

        $fundingID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $request->id)->first();

        $fundingReq = $request1->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not
            $archRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 6) //wating for funding manager approval
                    ->orWhere('statusReq', 13); //rejected  from generral manager

                })
                ->update(['statusReq' => 8, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //archive request in funding manager

            if ($archRequest == 0) // not updated
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            if ($archRequest == 1) { // updated sucessfully

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 8, $request1->user_id, $request1->class_id_fm);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::

                return redirect()->route('funding.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function sendFunding(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $fundingReq = $restRequest->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            $sendRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 6) //wating for funding manager approval
                    ->orWhere('statusReq', 8) //archived req in funding manager
                    ->orWhere('statusReq', 13); //rejected  from generral manager

                })
                ->update(['statusReq' => 12, 'isSentGeneralManager' => 1, 'isUnderProcFund' => 0, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //wating for general manager approval

            if ($sendRequest == 1) {
                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent'), (auth()->user()->manager_id), $request->comment);

                /*   DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => (auth()->user()->manager_id),
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $request->id,
                ]);

            */

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($request->id)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 12, $restRequest->user_id, $restRequest->class_id_fm);
                }

                //end quality :::::::::::::::::::::::::::::::::::::::

                DB::table('notifications')->insert([ // add notification to send general manager user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => (auth()->user()->manager_id),
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                if ($request->type != 'شراء-دفعة') {
                    $getAccID = User::where('role', 8)->where('accountant_type', 1)->pluck('id');
                }
                else {
                    $getAccID = User::where('role', 8)->pluck('id');
                }

                foreach ($getAccID as $accID) {
                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $accID,
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
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function updatefunding(Request $request)
    {

        $rules = [
            //   'name' => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            // 'jointmobile'=> 'regex:/^(05)[0-9]{8}$/',
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
            //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //   'jointmobile.regex' => 'Should start with 05 ',
            //  'birth.required' => 'The birth date filed is required ',
        ];

        $this->validate($request, $rules, $customMessages);

        $fundingID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $request->reqID)->first();

        $fundingReq = $request1->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        if ($fundingID == $fundingReq) { // check if it's correct funding or not

            //REQUEST
            $reqID = $request->reqID; //request id for update
            $fundingReq = DB::table('requests')->where('id', $reqID)
                ->where(function ($query) {
                    $query->where('statusReq', 6) //wating for funding manager approval
                    ->orWhere('statusReq', 8) //archived req in funding manager
                    ->orWhere('statusReq', 13) //rejected  from generral manager
                    ->orWhere('statusReq', 21) // mor-pur in funding
                    ->orWhere('statusReq', 25); // mor-pur in funding

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
                $classId = $fundingReq->class_id_fm;
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

                $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'work', $getworkValue->value);
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
                $this->records($reqID, 'jointBirth', $request->jointbirth);
                $this->records($reqID, 'jointSalary', $request->jointsalary);
                $this->records($reqID, 'jointBirth_higri', $request->jointbirth_hijri);
                $this->records($reqID, 'jointJobTitle', $job_title);

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

                $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'jointWork', $getworkValue->value);
                }

                $getjointaskaryValue = DB::table('askary_works')->where('id', $request->jointaskary_work)->first();
                if (!empty($getjointaskaryValue)) {
                    $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
                }

                $getjointmadanyValue = DB::table('madany_works')->where('id', $request->jointmadany_work)->first();
                if (!empty($getjointmadanyValue)) {
                    $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
                }

                DB::table('joints')->where('id', $jointId)
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
                }

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

                $getfundingValue = DB::table('funding_sources')->where('id', $request->funding_source)->first();
                if (!empty($getfundingValue)) {
                    $this->records($reqID, 'funding_source', $getfundingValue->value);
                }

                $this->records($reqID, 'fundDur', $fundingdur);
                $this->records($reqID, 'fundPers', $fundingpersonal);
                $this->records($reqID, 'fundPersPre', $fundingpersonalp);
                $this->records($reqID, 'fundReal', $fundingreal);
                $this->records($reqID, 'fundRealPre', $fundingrealp);
                $this->records($reqID, 'fundDed', $dedp);
                $this->records($reqID, 'fundMonth', $monthIn);

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
                $reqNoBank = $request->reqNoBank;
                $empBank = $request->empBank;
                $update = Carbon::now('Asia/Riyadh');

                $this->records($reqID, 'comment', $reqcomm);
                $this->records($reqID, 'commentWeb', $webcomm);
                $getclassValue = DB::table('classifcations')->where('id', $request->reqclass)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'class_id_fm', $getclassValue->value);
                }

                $this->records($reqID, 'reqNoBank', $reqNoBank);
                $this->records($reqID, 'empBank', $empBank);
                //

                if ($fundingReq->is_aqar_approved_by_salesManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of Sales Manager'),
                                                             'user_id'      => (auth()->user()->id),
                                                             'history_date' => (Carbon::now('Asia/Riyadh')),
                                                             'req_id'       => $reqID,
                    ]);
                }
                if ($fundingReq->is_aqar_approved_by_generalManager == 1) {
                    DB::table('request_histories')->insert([ // add to request history
                                                             'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Cancele approve of General Manager'),
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
                // if bank note updated
                if(trim($request1->bank_notes) != trim($request->bank_notes)){
                    $bank_notes = $request->bank_notes;
                    $this->records($reqID, 'bank_notes', $request->bank_notes);
                    foreach($request1->getBankAccountIds() as $u_id)
                    {
                        $msg = auth()->user()->name .': #Req '. $request1->id . ' ' . \App\Helpers\MyHelpers::admin_trans(auth()->id(), 'New Note');
                        \App\Helpers\MyHelpers::SendNotificationToUser($msg, $u_id, $request1->id);
                    }
                }else{
                    $bank_notes = $request1->bank_notes;
                }
                DB::table('requests')->where('id', $reqID)
                    ->update([
                        'class_id_fm'                        => $reqclass,
                        'empBank'                            => $empBank,
                        'reqNoBank'                          => $reqNoBank,
                        'noteWebsite'                        => $webcomm,
                        'fm_comment'                         => $reqcomm,
                        'updated_at'                         => $update,
                        'is_approved_by_fundingManager'      => 0,
                        'approved_date_fundingManager'       => null,
                        'is_aqar_approved_by_generalManager' => 0,
                        'approved_aqar_date_generalManager'  => null,
                        'is_aqar_approved_by_salesManager'   => 0,
                        'approved_aqar_date_salesManager'    => null,
                        'is_approved_by_tsaheel_acc'         => 0,
                        'is_approved_by_wsata_acc'           => 0,
                        'bank_notes'                         => $bank_notes,
                    ]);

                //for quality intent::::::::::::::::

                if (MyHelpers::checkQualityReq($reqID)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($reqID, $fundingReq->statusReq, $fundingReq->user_id, $reqclass);
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
        $reqID = $document->req_id;

        $reqInfo = DB::table('requests')->where('id', $reqID)->first();

        $funID = $reqInfo->funding_manager_id;

        if (!empty($reqInfo) || (auth()->user()->id == $funID)) {
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
        $reqID = $document->req_id;
        $reqInfo = DB::table('requests')->where('id', $reqID)->first();

        $funID = $reqInfo->funding_manager_id;

        if (!empty($reqInfo) || (auth()->user()->id == $funID)) {
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

    public function prepage($id)
    {

        $notifys = $this->fetchNotify(); //get notificationes

        $fundingID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $id)->first();

        $fundingReq = $request1->funding_manager_id;

        $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();

        if ($fundingID == $fundingReq) { // check if it's correct funding or not
            if (empty($payment)) {

                if ($request1->type == 'رهن-شراء') {
                    $purchaseReal = DB::table('requests')
                        ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                        ->where('requests.id', '=', $request1->req_id)
                        ->first();
                }

                if ($request1->type == 'شراء') {
                    $purchaseReal = DB::table('requests')
                        ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                        ->where('requests.id', '=', $id)
                        ->first();
                }

                $purchaseTsa = DB::table('requests')
                    ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                    ->where('requests.id', '=', $request1->req_id)
                    ->first();

                //  dd( $purchaseTsa);
                return view('FundingManager.prepayement.createPre', compact('purchaseTsa', 'id', 'purchaseReal', 'notifys'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Payment not created for this request'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function createPre(Request $request)
    {

        $fundingID = auth()->user()->id;
        $request1 = DB::table('requests')->where('requests.id', '=', $request->reqID)->first();

        $fundingReq = $request1->funding_manager_id;

        $notifys = $this->fetchNotify(); //get notificationes

        $payment = DB::table('prepayments')->where('req_id', '=', $request->reqID)->first();

        if ($fundingID == $fundingReq) { // check if it's correct funding or not
            if (empty($payment)) { // check if the payment already created

                if ($request1->type == 'شراء') { // create a new prepaymenr
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

                    $payID = DB::table('prepayments')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        [ //add it once use insertGetId
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
                          'pay_date'        => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                        ]
                    );

                    $updateReq = DB::table('requests')->where('id', $reqID)->update(['payment_id' => $payID, 'type' => 'شراء-دفعة', 'isUnderProcFund' => 1]); // add it to request

                    if ($updateReq == 1) {

                        $this->history($reqID, MyHelpers::admin_trans(auth()->user()->id, 'Create Prepayment'), null, null);

                        /*     DB::table('request_histories')->insert([ // add to request history
                            'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Create Prepayment'), 'user_id' => (auth()->user()->id),
                            'history_date' => (Carbon::now('Asia/Riyadh')),
                            'req_id' => $reqID,
                        ]);
                        */
                    }
                }

                if ($request1->type == 'رهن-شراء') {
                    $purchaseTsa = DB::table('requests')
                        ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                        ->where('requests.id', '=', $request1->req_id)
                        ->first();

                    // dd( $purchaseTsa);

                    $updateReq = DB::table('requests')->where('id', $request1->id)
                        ->update(['payment_id' => $purchaseTsa->payment_id]);

                    if ($updateReq == 1) {

                        $this->history($request1->req_id, MyHelpers::admin_trans(auth()->user()->id, 'Create Prepayment'), null, null);

                        /*   DB::table('request_histories')->insert([ // add to request history
                            'title' => MyHelpers::admin_trans(auth()->user()->id, 'Create Prepayment'), 'user_id' => (auth()->user()->id),
                            'history_date' => (Carbon::now('Asia/Riyadh')),
                            'req_id' => $request1->req_id,
                        ]);
                        */
                    }
                }

                //
                if ($request1->type != 'رهن-شراء') {
                    return redirect()->route('funding.manager.fundingRequest', ['id' => $request1->id])->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Payment created successfully'));
                }
                else // Mor-pur
                {
                    return redirect()->route('funding.manager.morPurRequest', ['id' => $request1->id])->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Payment created successfully'));
                }
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Payment for this request is already created'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function updatePage($id)
    {

        $notifys = $this->fetchNotify(); //get notificationes

        $fundingID = auth()->user()->id;
        $request = DB::table('requests')->where('requests.id', '=', $id)->first();
        // dd( $request);

        $fundingReq = $request->funding_manager_id;

        if ($request->type == 'رهن-شراء') {
            $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
        }
        else {
            $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
        }

        // dd($payment);

        if ($fundingID == $fundingReq) {
            if (!empty($payment)) {

                $purchaseReal = DB::table('requests')
                    ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                    ->where('requests.id', '=', $id)
                    ->first();

                return view('FundingManager.prepayement.updatePage', compact('notifys', 'id', 'request', 'payment', 'purchaseReal'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Payment not created for this request'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
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

            $payupdate = DB::table('prepayments')->where('req_id', $request1->req_id)
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
            $payupdate = DB::table('prepayments')->where('req_id', $reqID)
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

    public function sendPre(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;
        $salesManager = $restRequest->sales_manager_id;

        $userInfo = DB::table('users')->where('id', $userID)->first();

        if ($fundingReq == $fundingID) {

            $sendPay = DB::table('prepayments')->where('req_id', $request->id)
                ->where(function ($query) {
                    $query->where('payStatus', 0) // draft in funding manager
                    ->orWhere('payStatus', 3); //rejected  from sales manager

                })
                ->update(['payStatus' => 1, 'isSentSalesManager' => 1]); //wating for sales manager approval

            /* $updateReq = DB::table('requests')->where('requests.id', '=', $request->id)
                ->update(['isUnderProcFund' => 0]); //rejected from mortgage manager

*/

            if ($sendPay == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendPay, 'id' => $request->id]);
            }

            else {

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), $salesManager, null);

                /*    DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => $userInfo->manager_id,
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $request->id,
                ]);
                */

                DB::table('notifications')->insert([ // add notification to send general manager user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $salesManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $sendPay, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function rejectPrepay(Request $request)
    {

        $fundingID = (auth()->user()->id);
        $request1 = DB::table('requests')->where('requests.id', '=', $request->id)->first();

        $fundingReq = $request1->funding_manager_id;

        $userInfo = DB::table('users')->where('id', $request1->user_id)
            ->first();

        $payment = DB::table('prepayments')->where('req_id', '=', $request->id)
            ->first();

        if ($fundingID == $fundingReq) { // check if mortgage belong to req user id
            if (!empty($payment)) {

                $rejectPay = DB::table('prepayments')->where('req_id', $request->id)
                    ->where('payStatus', 7) // wating for funding manager approval after mortgage
                    ->update(['payStatus' => 10]); //rejected and redirect to mortgage manager

                /*     $updateReq = DB::table('requests')->where('requests.id', '=', $request->id)
                    ->update(['isUnderProcFund' => 0]); //rejected from mortgage manager


*/
                if ($rejectPay == 1) { //rejected sucessfully

                    if ($request->comment == null) {
                        $request->comment = "لايوجد";
                    }

                    $mortgageManager = $request1->mortgage_manager_id;
                    $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Rejected'), $mortgageManager, $request->comment);

                    /*   DB::table('request_histories')->insert([ // add to request history
                        'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Rejected'), 'user_id' => (auth()->user()->id), 'recive_id' => MyHelpers::extractMortgage($request->id),
                        'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                        'req_id' => $request->id,
                    ]);
                */

                    DB::table('notifications')->insert([ // add notification to send general manager user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $mortgageManager,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);

                    return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Rejected Successfully'), 'status' => $rejectPay, 'id' => $request->id]);
                }
                else {
                    return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $rejectPay, 'id' => $request->id]);
                }
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function cancelPre($id)
    {

        //dd($id);
        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $id)->first();
        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;

        if ($fundingReq == $fundingID) {

            $updateReq = 0;

            if ($restRequest->type == 'شراء' || $restRequest->type == 'شراء-دفعة') {
                $cancelPay = DB::table('prepayments')->where('req_id', $id)
                    ->where('payStatus', '!=', 9) // draft in funding manager
                    ->update(['payStatus' => 2]); //canceled from funding manager

                $updateReq = DB::table('requests')->where('requests.id', '=', $id)
                    ->whereIn('type', ['شراء-دفعة', 'شراء'])
                    ->update(['type' => 'شراء']);
            }

            if ($restRequest->type == 'رهن-شراء') {
                $cancelPay = DB::table('prepayments')->where('req_id', $restRequest->req_id)
                    ->where('payStatus', 0) // draft in funding manager
                    ->update(['payStatus' => 2]); //canceled from funding manager

                $updateReq = DB::table('requests')->where('requests.id', '=', $id)
                    ->whereIn('type', ['شراء-دفعة', 'شراء'])
                    ->update(['type' => 'شراء']);
            }

            if ($updateReq == 1) {
                $this->history($id, MyHelpers::admin_trans(auth()->user()->id, 'The prepayment canceled'), null, null);
            }

            /*    DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'The prepayment canceled'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $id,
                ]);
            */

            if ($cancelPay == 0) //nothing send
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
            }
            else {
                return redirect()->back();
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function restorePre($id)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('requests.id', $id)
            ->first();

        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;

        if ($fundingReq == $fundingID) {

            $updateReq = 0;
            if (($restRequest->type == 'شراء' || $restRequest->type == 'شراء-دفعة') && ($restRequest->statusReq == 6 || $restRequest->statusReq == 8 || $restRequest->statusReq == 13)) {
                $cancelPay = DB::table('prepayments')->where('req_id', $id)
                    ->whereIn('payStatus', [2, 8]) // canceled in funding manager
                    ->update(['payStatus' => 0]); //wating fOR funding manager (DRAFT)

                $updateReq = DB::table('requests')->where('requests.id', '=', $id)
                    ->update(['type' => 'شراء-دفعة']);
            }

            if ($restRequest->type == 'رهن-شراء') {
                $cancelPay = DB::table('prepayments')->where('req_id', $restRequest->req_id)
                    ->whereIn('payStatus', [2, 8]) // canceled in funding manager
                    ->update(['payStatus' => 0]); //wating fOR funding manager (DRAFT)

                $updateReq = DB::table('requests')->where('requests.id', '=', $id)
                    ->update(['type' => 'شراء-دفعة']);
            }

            if ($updateReq == 1) {
                $this->history($id, MyHelpers::admin_trans(auth()->user()->id, 'The prepayment recanceled'), null, null);
            }

            /*      DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'The prepayment recanceled'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' => $id,
                ]);
            */

            if ($cancelPay == 0) //nothing send
            {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
            }
            else {
                return redirect()->back();
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function appPre(Request $request)
    {

        $funID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;

        if ($funID == $fundingReq) {

            $sendPay = DB::table('prepayments')->where('req_id', $request->id)
                ->where('payStatus', 7) // approve from mortgage and wating funding
                ->update(['payStatus' => 9]); //approved

            /*      $updateReq = DB::table('requests')->where('requests.id', '=', $request->id)
                ->update(['isUnderProcFund' => 0]); //rejected from mortgage manager

*/

            //I DELETE HISTORIES FROM HERE************

            if ($sendPay == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendPay, 'id' => $request->id]);
            }

            else {

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'prepayment approved'), null, $request->comment);

                /*   DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'prepayment approved'), 'user_id' => (auth()->user()->id),
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $request->id,
                ]);
                */

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $sendPay, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function morPurpage($id)
    {

        $fundingID = (auth()->user()->id);

        $notifys = $this->fetchNotify(); //get notificationes

        $morPur = DB::table('requests')->where('id', '=', $id)->first();

        if (!empty($morPur)) {

            $fundingReq = $morPur->funding_manager_id;

            if (!empty($fundingReq)) {
                if ($fundingReq == $fundingID) {

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

                    $purchaseClass = DB::table('requests')
                        ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_fm')
                        ->where('requests.id', '=', $id)
                        ->first();

                    $collaborator = DB::table('requests')
                        ->join('users', 'users.id', '=', 'requests.collaborator_id')
                        ->where('requests.id', '=', $id)
                        ->first();

                    $cities = DB::table('cities')->select('id', 'value')->get();
                    $ranks = DB::table('military_ranks')->select('id', 'value')->get();
                    $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
                    $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
                    $askary_works = DB::table('askary_works')->select('id', 'value')->get();
                    $madany_works = DB::table('madany_works')->select('id', 'value')->get();
                    $realTypes = DB::table('real_types')->select('id', 'value')->get();

                    $user_role = DB::table('users')->select('role')->where('id', $fundingID)->get();
                    $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

                    $histories = DB::table('req_records')->where('req_id', '=', $id)
                        ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                        ->get();

                    $documents = DB::table('documents')->where('req_id', '=', $id)
                        ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                        ->select('documents.*', 'users.name')
                        ->get();

                    $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();

                    $purchaseTsa = DB::table('requests')
                        ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                        ->where('requests.id', '=', $id)
                        ->first();

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
                    //  dd( $payment );

                    return view('FundingManager.morPurReq.fundingreqpage', compact(
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
                        'histories',
                        'documents',
                        'reqStatus',
                        'morPur',
                        'payment',
                        'followdate',
                        'notifys',
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
                    return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
                }
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'The mor-pur request not created yet'));
        }
    }

    public function rejectMorPur(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $reqID = $restRequest->req_id;
        $userID = $restRequest->user_id;

        $fundingReq = $restRequest->funding_manager_id;
        $salesManager = $restRequest->sales_manager_id;

        $userInfo = DB::table('users')->where('id', $userID) // check if user belong to manager
        ->first();

        if ($fundingReq == $fundingID) {

            $restRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 21) //wating for funding manager approval
                    ->orWhere('statusReq', 25); //wating for funding manager approval

                })
                ->update(['statusReq' => 22, 'isUnderProcFund' => 0,]); // mor-pur rejected from funding manager and redirect to sales manager

            if ($restRequest == 1) { //rejected sucessfully

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), $salesManager, $request->comment);

                /*  DB::table('request_histories')->insert([ // add to request history
                    'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Rejected Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' => $userInfo->manager_id,
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $reqID,
                ]);
                */

                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $salesManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Rejecting successfully'), 'status' => $restRequest, 'id' => $request->id]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $restRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function appMorPur(Request $request)
    {

        $fundingID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->first();
        $userID = $restRequest->user_id;
        $reqID = $restRequest->req_id;

        $userInfo = DB::table('users')->where('id', $userID) // check if user belong to manager
        ->first();

        $fundingReq = $restRequest->funding_manager_id;

        if ($fundingReq == $fundingID) {

            if ($restRequest->isUnderProcMor == 1) {

                $mortgageManager = $restRequest->mortgage_manager_id;
                //Send notify for mortgage manager
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Please complete the request'),
                                                     'recived_id' => $mortgageManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);
                //

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Cannot send until complete the request in MG'), 'status' => 0, 'id' => $request->id]);
            }

            $sendRequest = DB::table('requests')->where('id', $request->id)
                ->where(function ($query) {
                    $query->where('statusReq', 21) //wating for funding manager approval
                    ->orWhere('statusReq', 25); //wating for funding manager approval

                })
                ->update(['statusReq' => 23, 'isSentGeneralManager' => 1, 'isUnderProcFund' => 0, 'isUnderProcMor' => 0,]); //approved

            if ($sendRequest == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
            }

            else {

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), auth()->user()->manager_id, $request->comment);

                /*  DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'Sent Mor-Pur'), 'user_id' => (auth()->user()->id), 'recive_id' => auth()->user()->manager_id,
                    'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                    'req_id' => $reqID,
                ]);
                */

                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => auth()->user()->manager_id,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                $getAccID = User::where('role', 8)->pluck('id');

                foreach ($getAccID as $accID) {
                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                         'recived_id' => $accID,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 0,
                                                         'req_id'     => $request->id,
                    ]);
                }

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Approving successfully'), 'status' => $sendRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function updateBank(Request $request)
    {

        if ($request->empBank != null) {
            $this->records($request->id, 'empBank', $request->empBank);
        }

        if ($request->reqNoBank != null) {
            $this->records($request->id, 'reqNoBank', $request->reqNoBank);
        }

        $request = DB::table('requests')->where('id', $request->id)
            ->update(['empBank' => $request->empBank, 'reqNoBank' => $request->reqNoBank]);

        return response($request);
    }

    public function updateCost(Request $request)
    {

        $req = DB::table('requests')->where('id', $request->id)->first();
        // return response($req->payment_id);

        $updateReq = 0;
        $updateReq2 = 0;

        if ($request->reqprof != null) {
            $this->records($request->id, 'realPursuit', $request->reqprof);

            $updateReq = DB::table('real_estats')->where('id', $req->real_id)
                ->update(['pursuit' => $request->reqprof]);
        }

        if ($request->reqmor != null) {
            $this->records($request->id, 'mortValue', $request->reqmor);

            $updateReq2 = DB::table('real_estats')->where('id', $req->real_id)
                ->update(['mortgage_value' => $request->reqmor]);
        }

        /*  if ($request->reqmor != null && $req->type != 'شراء') {
            $this->records($request->id, 'mortCost', $request->reqmor);

            $updateReq2 = DB::table('prepayments')->where('id', $req->payment_id)
            ->update([ 'mortCost' => $request->reqmor]);

            $updateReq2 = DB::table('prepayments')->where('id', $req->payment_id)
            ->update([ 'mortCost' => $request->reqmor]);
        }
        */

        if ($updateReq == 1 || $updateReq2 == 1) {
            return response(1);
        }
        else {
            return response(0);
        }
    }

    public function updateReal(Request $request)
    {

        $req = DB::table('requests')->where('id', $request->id)->first();

        if ($request->realname != null) {
            $this->records($request->id, 'realName', $request->realname);
        }

        if ($request->realmobile != null) {
            $this->records($request->id, 'realMobile', $request->realmobile);
        }

        $request = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['mobile' => $request->realmobile, 'name' => $request->realname]);

        return response($request);
    }

    public function updaterealType(Request $request)
    {

        $req = DB::table('requests')->where('id', $request->id)->first();

        if ($request->reqType != null) {
            $fn = $request->reqType;
            $gettypeValue = DB::table('real_types')->where('id', $fn)->first();
            if (!empty($gettypeValue)) {
                $this->records($request->id, 'realType', $gettypeValue->value);
            }
        }

        $request = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['type' => $request->reqType]);

        return response($request);
    }

    public function updateComm(Request $request)
    {

        if ($request->reqComm != null) {
            $this->records($request->id, 'comment', $request->reqComm);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['fm_comment' => $request->reqComm]);

        return response()->json(['status' => $request2, 'newComm' => $request->reqComm]);
    }

    public function updateClass(Request $request)
    {

        if ($request->reqClass != null) {
            $fn = $request->reqClass;
            $getclassValue = DB::table('classifcations')->where('id', $fn)->first();
            if (!empty($getclassValue)) {
                $this->records($request->id, 'class_id_fm', $getclassValue->value);
            }
        }

        $request = DB::table('requests')->where('id', $request->id)
            ->update(['class_id_fm' => $request->reqClass]);

        return response($request);
    }

    public function updatefunsour(Request $request)
    {

        $req = DB::table('requests')->where('id', $request->id)->first();

        if ($request->funSour != null) {
            $fn = $request->funSour;

            $getfunValue = DB::table('funding_sources')->where('id', $fn)->first();
            if (!empty($getfunValue)) {
                $this->records($request->id, 'funding_source', $getfunValue->value);
            }
        }

        $request = DB::table('fundings')->where('id', $req->fun_id)
            ->update(['funding_source' => $request->funSour]);

        return response($request);
    }

    public function updaterealcity(Request $request)
    {

        $req = DB::table('requests')->where('id', $request->id)->first();

        if ($request->realcity != null) {
            $fn = $request->realcity;

            $getcityValue = DB::table('cities')->where('id', $fn)->first();
            if (!empty($getcityValue)) {
                $this->records($request->id, 'realCity', $getcityValue->value);
            }
        }

        $request = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['city' => $request->realcity]);

        return response($request);
    }

    public function archReqArr(Request $request)
    {

        $result = DB::table('requests')
            ->whereIn('id', $request->array)
            // ->where('user_id',  auth()->user()->id)
            ->whereIn('statusReq', [6, 13])
            ->update([
                'statusReq' => 8, //archived in funding manager
            ]);
        return response($result); // if 1: update succesfally

    }

    public function restReqArr(Request $request)
    {

        $result = DB::table('requests')
            ->whereIn('id', $request->array)
            // ->where('user_id',  auth()->user()->id)
            ->where('statusReq', 8) //archived in funding manager
            ->update([
                'statusReq' => 6,
            ]);
        return response($result); // if 1: update succesfally

    }

    public function aprroveAqar(Request $request)
    {

        $reqInfo = DB::table('requests')
            ->where('requests.id', '=', $request->id)
            ->update(['is_approved_by_fundingManager' => 1, 'approved_date_fundingManager' => Carbon::now('Asia/Riyadh')->format("Y-m-d")]);

        if ($reqInfo) {
            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Funding Manager approve aqar'),
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
            ->update(['is_approved_by_fundingManager' => 0, 'approved_date_fundingManager' => null]);

        if ($reqInfo) {

            DB::table('request_histories')->insert([ // add to request history
                                                     'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Funding Manager cancel approve aqar'),
                                                     'user_id'      => (auth()->user()->id),
                                                     'history_date' => (Carbon::now('Asia/Riyadh')),
                                                     'req_id'       => $request->id,
            ]);

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }

    public function sendBank($user_id, $req_id, $type)
    {
        $f_user = auth()->user();
        $fund_user =\DB::table('users')->where('id', $user_id)->select('id', 'name', 'bank_id')->first();
        // check if bank user related to this funding user
        //
        $row = WasataRequestes::where('user_id', $user_id)
                                ->where('funding_user_id', $f_user->id)
                                ->where('req_id', $req_id)
                                ->first();
        $check_bank_user_of_same_bank = WasataRequestes::where('user_id','<>', $user_id)
                                ->whereHas('user', function($q)use($fund_user){
                                    $q->where('bank_id', $fund_user->bank_id);
                                })
                                // ->where('funding_user_id', $f_user->id)
                                ->where('req_id', $req_id)
                                ->exists();
        if($check_bank_user_of_same_bank)
        {
            return view('FundingManager.fundingReq.BankTransferActions',['msg' => 'تم ارسال الطلب الي موظف في نفس البنك .','row'=> $row, 'id'=> $req_id, 'fund_user' => $fund_user ])->render();
        }
        if($row && $type == 'send')
        {
            return view('FundingManager.fundingReq.BankTransferActions',['msg' => 'تم ارسال الطلب مسبقا','row'=> $row, 'id'=> $req_id, 'fund_user' => $fund_user ])->render();
        }
        switch($type)
        {
            case 'send':
                $row = new WasataRequestes;
                $row->req_id = $req_id;
                $row->type = 'internal';
                $row->user_id = $user_id;
                $row->req_status = 1;
                $row->req_type = 'send';
                $row->funding_user_id = $f_user->id;
                $row->save();
                // send notification to bnk user
                DB::table('notifications')->insert([
                                            'value'      => "طلب جديد من الوساطة",
                                            'recived_id' => $user_id,
                                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                            'status'       => 0,
                                            'type'       => 0,
                                            'req_id'     => $req_id,
                        ]);
                break;
            case 'pull':
                $row->req_type = 'pull';
                $row->save();
                break;
            case 'push':
                $row->req_type = 'push';
                $row->save();
                break;
            case 'close':
                $row->req_type = 'close';
                $row->save();
                DB::table('wasata_requestes')->where('req_id', $req_id)->update(['req_type' => 'close', 'comment' => 'closed in account '.$user_id]);
                break;
        }
        if($type == 'close')
        {
            return "هذا الطلب تم افراغة";

        }else{
            return view('FundingManager.fundingReq.BankTransferActions',['msg' => '', 'row'=> $row, 'id'=> $req_id, 'fund_user' => $fund_user ])->render();

        }

    }

}
