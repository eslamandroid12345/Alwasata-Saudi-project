<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\SplitController;

use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\funding_source;
use App\Models\User;
use App\salary_source;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;

trait SplitAdminFourControllerTrait
{


    public function starReqs(Request $request)
    {

        $requests = DB::table('requests')->whereIn('statusReq', [0, 1, 4, 31])->where('is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->distinct('requests.id')->select('requests.*', 'customers.name as customer_name',
            'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')->count();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesAgents = User::where('role', 0)->get();
        $qualityUsers = User::where('role',5)->get();
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
        $qulitys = User::where('role', 5)->where('status', 1)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        return view('Admin.Request.staredReqs',
            compact('requests', 'regions', 'classifcations_qu', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'qulitys', 'worke_sources',
                'request_sources','qualityUsers'));
    }
    
    public function starReqs_datatable(Request $request)
    {

        $requests = DB::table('requests')->whereIn('statusReq', [0, 1, 4, 31])->where('requests.is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=',
            'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->leftjoin('quality_reqs', 'quality_reqs.req_id', '=',
            'requests.id')->distinct('requests.id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.mobile', 'customers.app_downloaded', 'requests.class_id_quality as is_quality_recived')->orderBy('requests.req_date', 'DESC');

        if ($request->get('updated_at_from')) {
            $requests = $requests->where('requests.updated_at', '>=', $request->get('updated_at_from'));
        }

        if ($request->get('updated_at_to')) {
            $requests = $requests->where('requests.updated_at', '<=', $request->get('updated_at_to'));
        }

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

        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            $requests = $requests->whereIn('app_downloaded', $request->get('app_downloaded'));
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
                $requests = $requests->where(function ($query) {
                    $query->where('requests.class_id_quality', '!=', null)
                          ->orWhere('requests.quacomment', '!=', null);
                });
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null)->where('requests.quacomment', null);
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

        if ($request->get('quality_users') && is_array($request->get('quality_users'))) {
            # shall be recived by this user (is_recived_by_quality)
            $requests = $requests->where(function ($query) {
                $query->where('requests.class_id_quality', '!=', null)
                      ->orWhere('requests.quacomment', '!=', null);
            });
            # if it's recived by the quality
            $get_requests_id = $requests->pluck('requests.id')->toArray();
            $quality_requests_req_ids = DB::table('quality_reqs')->whereIn('user_id',$request->get('quality_users'))->whereIn('req_id',$get_requests_id)->pluck('req_id')->toArray();
            $requests = $requests->whereIn('requests.id', $quality_requests_req_ids);
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
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
        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('action', function ($row) {
            $data = '<div id="tableAdminOption" class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                $data = $data.'<span class="item pointer"  id="move" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                                    <i class="fas fa-random"></i>
                                </span> ';
            }

            $data = $data.'<span class="item pointer"  class="item" id="addQuality" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality').'">
            <i class="fas fa-paper-plane"></i></span> ';

            $data = $data.'<span class="item pointer"  id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-alt"></i></a></span>';

            $data = $data.'<span class="item pointer" id="needActionReq" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add to need action req').'">
            <a onclick="addReqToNeedActionReqFromAdmin('.$row->id.')"><i class="fas fa-directions"></i></a></span>';

            $data = $data.'</div>';

            return $data;
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
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
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
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null || $row->quacomment != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('updated_at', fn($row) => $row->updated_at ? \Illuminate\Support\Carbon::make($row->updated_at)->format('Y-m-d g:i a') : '-')->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function followReqs(Request $request)
    {
        $requests = DB::table('requests')->whereIn('statusReq', [0, 1, 4, 31])->where('is_followed', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->distinct('requests.id')->select('requests.*',
            'customers.name as customer_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')->count();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesAgents = User::where('role', 0)->get();
        $qualityUsers = User::where('role',5)->get();
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
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        return view('Admin.Request.followReqs',
            compact('requests', 'regions', 'classifcations_qu', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources','qualityUsers'));
    }

    public function followReqs_datatable(Request $request)
    {

        $requests = DB::table('requests')->whereIn('statusReq', [0, 1, 4, 31])->where('requests.is_followed', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=',
            'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->leftjoin('quality_reqs', 'quality_reqs.req_id', '=',
            'requests.id')->distinct('requests.id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.mobile', 'customers.app_downloaded', 'requests.class_id_quality as is_quality_recived')->orderBy('requests.req_date', 'DESC');

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

        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            $requests = $requests->whereIn('app_downloaded', $request->get('app_downloaded'));
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
                $requests = $requests->where(function ($query) {
                    $query->where('requests.class_id_quality', '!=', null)
                          ->orWhere('requests.quacomment', '!=', null);
                });
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null)->where('requests.quacomment', null);
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

        if ($request->get('quality_users') && is_array($request->get('quality_users'))) {
            # shall be recived by this user (is_recived_by_quality)
            $requests = $requests->where(function ($query) {
                $query->where('requests.class_id_quality', '!=', null)
                      ->orWhere('requests.quacomment', '!=', null);
            });
            # if it's recived by the quality
            $get_requests_id = $requests->pluck('requests.id')->toArray();
            $quality_requests_req_ids = DB::table('quality_reqs')->whereIn('user_id',$request->get('quality_users'))->whereIn('req_id',$get_requests_id)->pluck('req_id')->toArray();
            $requests = $requests->whereIn('requests.id', $quality_requests_req_ids);
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
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

        if ($request->get('updated_at_from')) {
            $requests = $requests->where('requests.updated_at', '>=', $request->get('updated_at_from'));
        }

        if ($request->get('updated_at_to')) {
            $requests = $requests->where('requests.updated_at', '<=', $request->get('updated_at_to'));
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
        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="table-data-feature">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }
            else {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }

            $data = $data.'<span class="item pointer" id="needActionReq" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add to need action req').'">
            <a onclick="addReqToNeedActionReqFromAdmin('.$row->id.')"><i class="fas fa-directions"></i></a></span>';

            $data = $data.'</div>';
            return $data;
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
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

           if ($row->class_id_quality != null || $row->quacomment != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('updated_at', fn($row) => $row->updated_at ? \Illuminate\Support\Carbon::make($row->updated_at)->format('Y-m-d g:i a') : '-')->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function agentRecivedReqs(Request $request)
    {

        $requests = DB::table('requests')
            // ->where('requests.req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
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
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesAgent', 1);
                });
            })->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->select('requests.*', 'customers.name as cust_name', 'customers.mobile', 'users.name as user_name',
                'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesAgents = User::where('role', 0)->get();
        $qualityUsers = User::where('role',5)->get();
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

        //dd($collaborators);
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        $classifcations_qu = classifcation::where('user_role', 5)->get();

        return view('Admin.Request.agentRecivedReqs',
            compact('requests', 'regions', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources', 'request_sources',
                'classifcations_qu','qualityUsers'));
    }

    public function agentRecivedReqs_datatable(Request $request)
    {
        $requests = DB::table('requests')
            ->whereNull('requests.deleted_at')

            // ->where('requests->where('requests.user_id','<>',null).req_date','>','2019-12-31')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('quality_reqs', 'quality_reqs.req_id', '=', 'requests.id')->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [0, 1, 4, 31]);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 19);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentSalesAgent', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [4, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesAgent', 1);
                });
            })->where('requests.is_canceled', 0)->where('requests.is_followed', 0)->where('requests.is_stared', 0)->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=',
                'requests.fun_id')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.mobile', 'customers.app_downloaded',
                'requests.class_id_quality as is_quality_recived')->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            //$requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
            $requests = $requests->whereBetween(\DB::raw('DATE(req_date)'), [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            $requests = $requests->whereIn('app_downloaded', $request->get('app_downloaded'));
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
                $requests = $requests->where(function ($query) {
                    $query->where('requests.class_id_quality', '!=', null)
                          ->orWhere('requests.quacomment', '!=', null);
                });
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null)->where('requests.quacomment', null);
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

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('quality_users') && is_array($request->get('quality_users'))) {
            # shall be recived by this user (is_recived_by_quality)
            $requests = $requests->where(function ($query) {
                $query->where('requests.class_id_quality', '!=', null)
                      ->orWhere('requests.quacomment', '!=', null);
            });
            # if it's recived by the quality
            $get_requests_id = $requests->pluck('requests.id')->toArray();
            $quality_requests_req_ids = DB::table('quality_reqs')->whereIn('user_id',$request->get('quality_users'))->whereIn('req_id',$get_requests_id)->pluck('req_id')->toArray();
            $requests = $requests->whereIn('requests.id', $quality_requests_req_ids);
        }

        if ($request->get('updated_at_from')) {
            $requests = $requests->where('requests.updated_at', '>=', $request->get('updated_at_from'));
        }

        if ($request->get('updated_at_to')) {
            $requests = $requests->where('requests.updated_at', '<=', $request->get('updated_at_to'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
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

            if ($row->class_id_quality != null || $row->quacomment != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('updated_at', fn($row) => $row->updated_at ? \Illuminate\Support\Carbon::make($row->updated_at)->format('Y-m-d g:i a') : '-')->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function archReqs(Request $request)
    {
        $requests = DB::table('requests')->whereIn('statusReq', [2])->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*',
            'customers.name as cust_name', 'customers.mobile as cust_mobile', 'users.name as user_name', 'customers.salary', 'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work')->distinct('requests.id')->orderBy('id', 'DESC')->count();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesAgents = User::where('role', 0)->get();
        $qualityUsers = User::where('role',5)->get();
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
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        return view('Admin.Request.archReqs',
            compact('requests', 'regions', 'classifcations_qu', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'worke_sources',
                'request_sources','qualityUsers'));
    }

    public function archReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->whereIn('statusReq', [2])
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=',
            'requests.real_id')->join('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->leftjoin('quality_reqs', 'quality_reqs.req_id', '=', 'requests.id')->distinct('requests.id')->select(

            'requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.mobile', 'customers.app_downloaded', 'requests.class_id_quality as is_quality_recived'

        )->orderBy('id', 'DESC');

        if ($request->get('updated_at_from')) {
            $requests = $requests->where('requests.updated_at', '>=', $request->get('updated_at_from'));
        }

        if ($request->get('updated_at_to')) {
            $requests = $requests->where('requests.updated_at', '<=', $request->get('updated_at_to'));
        }

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            $requests = $requests->whereIn('app_downloaded', $request->get('app_downloaded'));
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
                $requests = $requests->where(function ($query) {
                    $query->where('requests.class_id_quality', '!=', null)
                          ->orWhere('requests.quacomment', '!=', null);
                });
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null)->where('requests.quacomment', null);
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
        if ($request->get('quality_users') && is_array($request->get('quality_users'))) {
            # shall be recived by this user (is_recived_by_quality)
            $requests = $requests->where(function ($query) {
                $query->where('requests.class_id_quality', '!=', null)
                      ->orWhere('requests.quacomment', '!=', null);
            });
            # if it's recived by the quality
            $get_requests_id = $requests->pluck('requests.id')->toArray();
            $quality_requests_req_ids = DB::table('quality_reqs')->whereIn('user_id',$request->get('quality_users'))->whereIn('req_id',$get_requests_id)->pluck('req_id')->toArray();
            $requests = $requests->whereIn('requests.id', $quality_requests_req_ids);
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
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
            $data = '<div class="table-data-feature"><button class="item" id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                    <a href="'.route('admin.restoreRequest', $row->id).'"><i class=" archReqs_datatable zmdi zmdi-time-restore-setting"></i></a></button></div>';
            return $data;
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
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
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

            if ($row->class_id_quality != null || $row->quacomment != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('updated_at', fn($row) => $row->updated_at ? \Illuminate\Support\Carbon::make($row->updated_at)->format('Y-m-d g:i a') : '-')->rawColumns(['is_quality_recived', 'action'])->make(true);
    }

    public function archReq($id)
    {

        $requestInfo = DB::table('requests')->where('id', $id)->first();
        $lastStatus = $requestInfo->statusReq;

        $whereReq = MyHelpers::findUserOfReq($requestInfo->statusReq, $requestInfo->type);

        if ($whereReq == 0) {
            $result = DB::table('requests')->where('id', $id)->update([
                'statusReq' => 2, //archived in sales agent
            ]);
        }
        elseif ($whereReq == 1) {
            $result = DB::table('requests')->where('id', $id)->update([
                'statusReq' => 5, //archived in sales manager
            ]);
        }
        elseif ($whereReq == 2) {
            $result = DB::table('requests')->where('id', $id)->update([
                'statusReq' => 8, //archived in funding manager
            ]);
        }
        elseif ($whereReq == 3) {
            $result = DB::table('requests')->where('id', $id)->update([
                'statusReq' => 11, //archived in mortgage manager
            ]);
        }
        elseif ($whereReq == 4) {
            $result = DB::table('requests')->where('id', $id)->update([
                'statusReq' => 14, //archived in general manager
            ]);
        }
        else {
            $result = 0;
        }

        if ($result == 1) // if 1: archive succesfally
        {
            DB::table('req_records')->insert([
                'colum'          => 'statusReq',
                'user_id'        => (auth()->user()->id),
                'value'          => $lastStatus,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $id,
            ]);
        }

        if ($result == 0) // not updated
        {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }

        if ($result == 1) // updated sucessfully
        {
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Archive Successfully'));
        }
    }
}
