<?php

namespace App\Http\Controllers\Frontend;

use App\classifcation;
use App\customer;
use App\funding_source;
use App\Helpers\MyHelpers;
use App\Http\Controllers\AdminController;
use App\Model\PendingRequest;
use App\salary_source;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

//to take date

class PendingRequestController extends AdminController
{
    public function index()
    {
        $pending_requests = PendingRequest::all();
        $notifys = $this->fetchNotify();

        $salesAgents = User::where('role', 0)->where('status', 1)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $collaborators = (new Collection($collaborators))->unique('id');
        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $collaborators->values()->all();
        $request_sources = DB::table('request_source')->get();
        $worke_sources = WorkSource::all();
        return view('Admin.Request.pendingRequests',
            compact('pending_requests','salesManagers', 'regions', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'classifcations_qu', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'notifys',
                'request_sources', 'worke_sources'));
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

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[35]);
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

    public function datatable(Request $request)
    {

        //$requests = DB::table('pending_requests')
        //    ->join('customers', 'customers.id', '=', 'pending_requests.customer_id')
        //    ->leftjoin('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')
        //    ->select('customers.*', 'real_estats.has_property', 'real_estats.owning_property', 'pending_requests.*');

        $requests = PendingRequest::query()->with(['customer', 'realEstate']);

        if ($request->get('updated_at_from')) {
            $requests->where('updated_at', '>=', $request->get('updated_at_from'));
        }

        if ($request->get('updated_at_to')) {
            $requests->where('updated_at', '<=', $request->get('updated_at_to'));
        }

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('salary', '>=', $request->get('customer_salary')));
            //$requests = $requests->where('customers.salary', '>=', $request->get('customer_salary'));
        }

        if ($request->get('customer_salary_to')) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('salary', '<=', $request->get('customer_salary')));
            //$requests = $requests->where('customers.salary', '<=', $request->get('customer_salary_to'));
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
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }

        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('user_id', $request->get('agents_ids'));
        }
        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('region_ip', $request->get('region_ip')));
            //$requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('work', $request->get('work_source')));

            //$requests = $requests->whereIn('customers.work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('salary_id', $request->get('salary_source')));
            //$requests = $requests->whereIn('customers.salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->whereHas('customer', fn(Builder $builder) => $builder->where('mobile', $request->get('customer_phone')));
            //$requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        //if ($request->get('customer_birth')) {
        //    $requests = $requests->whereHas('customer',fn (Builder $builder) => $builder->where('mobile', $request->get('customer_phone')));
        //    $requests = $requests->filter(function ($item) use ($request) {
        //        return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
        //    });
        //}

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
            if (array_key_exists('value',$request->search)){
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9){
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile',$request->search['value'])->first();
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
                    'search' => $search
                ]);
            }

        }*/
        //dd(request()->all());
        //$requests = $requests->latest()->paginate()->;
        //$limit = request()->get('length', 25);
        //$page = request()->get('draw', 1);
        //$limit = 50;
        //$requests = $requests->latest()->limit($limit)->get();
        $requests = $requests->latest();
        //$requests = $requests->latest()->forPage($page,$limit)->get();
        return DataTables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->editColumn('updated_at', fn($row) => $row->updated_at ? $row->updated_at->format('Y-m-d g:i a') : '-')->editColumn('name', fn($row) => optional($row->customer)->name)->editColumn('mobile', fn($row) => @$row->customer->mobile)->editColumn('work',
            fn($row) => @$row->customer->work)->editColumn('salary', fn($row) => @$row->customer->salary)->editColumn('birth_date_higri', fn($row) => @$row->customer->birth_date_higri)->editColumn('is_supported', fn($row) => $row->owning_property == 'yes' ? 'نعم' : 'لا')->editColumn('owning_property',
            fn($row) => $row->owning_property == 'yes' ? 'نعم' : 'لا')->editColumn('has_property', fn($row) => $row->has_property == 'yes' ? 'نعم' : 'لا')->editColumn('has_joint', fn($row) => $row->has_joint == 'yes' ? 'نعم' : 'لا')->editColumn('has_obligations',
            fn($row) => $row->has_obligations == 'yes' ? 'نعم' : 'لا')->editColumn('has_financial_distress', fn($row) => $row->has_financial_distress == 'yes' ? 'نعم' : 'لا')->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {
                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();
                if ($collInfo != null) {
                    $data = $data.' - '.@$collInfo->name;
                }
            }
            return $data;
        })->editColumn('created_at', fn($row) => Carbon::parse($row->created_at ?: now())->format('Y-m-d g:ia'))->addColumn('action',
            fn($row) => '<div class="tableAdminOption">'.'<span class="item" id="move" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                                    <i class="fas fa-random"></i>
                                </span> '.'</div>')->make(true);
    }
}
