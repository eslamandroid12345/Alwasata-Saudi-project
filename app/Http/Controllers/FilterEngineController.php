<?php

namespace App\Http\Controllers;

use App\cities;
use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\madany_work;
use App\military_ranks;
use App\salary_source;
use App\User;
use App\WorkSource;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use Response;
use View;

class FilterEngineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'], //attaches HomeComposer to pages
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }

    public function filterEnginePage()
    {
        $salesAgents = User::where('role', 0)->where('status', 1)->get();
        $qulitys = User::where('role', 5)->where('status', 1)->get();
        $worke_sources = WorkSource::all();

        return view('FilterEngine.index', compact('salesAgents', 'qulitys', 'worke_sources'));
    }

    public function getMadanyValue()
    {

        $madany_works = madany_work::all();

        return Response::json(['success' => true, 'madany_works' => $madany_works]);
    }

    public function getMiliratyValue()
    {

        $military_ranks = military_ranks::all();

        return Response::json(['success' => true, 'military_ranks' => $military_ranks]);
    }

    public function getSalaryValue()
    {

        $salary_source = salary_source::all();

        return Response::json(['success' => true, 'salary_source' => $salary_source]);
    }

    public function getCityValue()
    {

        $cities = cities::all();

        return Response::json(['success' => true, 'cities' => $cities]);
    }

    public function getAgentsValues()
    {

        $users = User::where('status', 1)->where('role', 0)->get();

        return Response::json(['success' => true, 'users' => $users]);
    }

    public function getStatusReqValues()
    {

        $status = [
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
        ];

        return Response::json(['success' => true, 'status' => $status]);
    }

    public function getRegionValues()
    {

        $regions = customer::select('region_ip')
            ->groupBy('region_ip')
            ->get();

        return Response::json(['success' => true, 'regions' => $regions]);
    }

    public function getAgentClassValue()
    {

        $classes = classifcation::where('user_role', 0)->get();

        return Response::json(['success' => true, 'classes' => $classes]);
    }

    public function testFilter(Request $request)
    {

        $allInputs = $request->input('inputs');

        $entred_mobile = null;
        $entred_mobile_count = count($request->input('mobile'));
        if ($entred_mobile_count != 0) {
            $entred_mobile = $request->input('mobile')[0];
        }

        if ($entred_mobile != null) {# check if request in pending
            foreach ($allInputs as $key => $input) {

                if ($input == 'mobile') {
                    $cutomer = DB::table('customers')->where('mobile', $entred_mobile)->first();
                    if (empty($cutomer)) {
                        $mobiles = CustomersPhone::where('mobile', $entred_mobile)->first();
                        if (!empty($mobiles)) {
                            $customer = DB::table('customers')->find($mobiles->customer_id);
                            $requests = DB::table('requests')->where('customer_id', $customer->id)->first();
                        }
                    }
                    else {
                        $requests = DB::table('requests')->where('customer_id', $cutomer->id)->first();
                    }

                    if (empty($requests) && !empty($cutomer)) {
                        $requests = DB::table('pending_requests')->where('customer_id', $cutomer->id)->first();

                        if (!empty($requests)) {
                            return response()->json(['status' => 2, 'message' => 'الطلب في سلة المعلقة']);
                        }
                    }

                }
            }
        }

        //////
        if ($allInputs == '') {
            return response()->json(['status' => 2, 'message' => 'اختر عامل تصفية واحد على الأقل']);
        }

        return response($request);
    }

    public function requests_datatable(Request $request)
    {

        $requests = DB::table('requests')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')
            ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->leftjoin('users', 'users.id', '=', 'requests.user_id')
            ->select('customers.name as cust_name', 'customers.region_ip', 'real_estats.city', 'real_estats.region', 'real_estats.name as realname', 'real_estats.mobile as realmobile', 'customers.mobile', 'requests.*', 'classifcations.value', 'users.name as user_name');

        $allInputs = $request->requestData['inputs'];

        foreach ($allInputs as $key => $input) {
            if ($request->requestData[$input][$key]) {
                if ($input == 'realname') {
                    $requests = $requests->where('real_estats.name', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                }
                elseif ($input == 'realmobile') {
                    $requests = $requests->where('real_estats.mobile', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                }
                elseif ($input == 'user_id') {
                    $requests = $requests->where('requests.user_id', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                }
                elseif ($input == 'mobile') {
                    $mobile = DB::table('customers')->where('mobile', $request->requestData[$input][$key]);
                    //**************************************************************
                    // Task-17
                    //**************************************************************

                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->requestData[$input][$key])->first();
                        if (!empty($mobiles)) {
                            $requests = $requests->where('requests.customer_id', $request->requestData['condition'][$key], $mobiles->customer_id);
                        }
                        else {
                            $requests = $requests->where('customers.mobile', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                    }
                }
                elseif ($input == 'id') {
                    $requests = $requests->where('requests.id', $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                }

                else {
                    $requests = $requests->where($input, $request->requestData['condition'][$key], $request->requestData[$input][$key]);
                }
            }
        }

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            if (auth()->user()->role == 7) {
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="pointer item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="pointer item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            if (auth()->user()->role == 4) {
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="pointer item Red" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="pointer item Red" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }

            if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                $data = $data.'<span class="pointer item Red" id="move" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                <i class="fas fa-random"></i>
                                </span> ';
            }

            $data = $data.'<span class="pointer item Pink" id="addQuality" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality').'">
            <i class="fas fa-paper-plane"></i></span> ';

            $data = $data.'<span class="pointer item Silver" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-alt"></i></a></span>';

            $data = $data.'</div>';

            return $data;
        })->editColumn('statusReq', function ($row) {

            return @$this->status()[$row->statusReq] ?? $this->status()[28];
        })->make(true);
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
}
