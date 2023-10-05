<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use View;

class RequestContiotionController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer' => ['layouts.content'],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        /// not usedin last modify
        // if (auth()->user()->role == '7') {
        //     return view('Admin.RequestCondtion.Forms.index');
        // } else {
        //     return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $pendingRequests = DB::table('pending_requests')->join('customers', 'customers.id', '=', 'pending_requests.customer_id')->join('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')->select('customers.*', 'real_estats.has_property', 'real_estats.owning_property',
            'pending_requests.*')->get();

        $totalcount = count($pendingRequests);
        $request_achive_condition_list = [];

        $msg = "";
        foreach ($pendingRequests as $request_data) {

            $isAccept = MyHelpers::check_is_request_acheive_condition($request);
            //  $msg.="Condtion : from date ={ $request->request_validation_from_birth_date} ,to date = '2020-04-17' \r\n ";

            if ($isAccept) {
                $request_achive_condition_list[] = $request_data;
                $user_id = MyHelpers::getNextAgentForRequest();
                $reqID = DB::table('requests')->insertGetId([
                    'source'          => $request_data->source,
                    'req_date'        => $request_data->req_date,
                    'created_at'      => $request_data->created_at,
                    'user_id'         => $user_id,
                    'customer_id'     => $request_data->customer_id,
                    'collaborator_id' => $request_data->collaborator_id,
                    'statusReq'       => $request_data->statusReq,
                    'joint_id'        => $request_data->joint_id,
                    'fun_id'          => $request_data->fun_id,
                    'searching_id'    => $request_data->searching_id,
                    'real_id'         => $request_data->real_id,
                    'agent_date'      => carbon::now(),
                ]);
                setLastAgentOfDistribution($user_id);

                // to add notification
                $notify = MyHelpers::addNewNotify($reqID, $user_id);
                if ($request_data->source == 2) {
                    // to add new history record
                    if ($request_data->collaborator_id == 17) {
                        $record = MyHelpers::addNewReordOtared($reqID, $user_id, $request_data->created_at);
                    }
                    else {
                        if ($request_data->collaborator_id == 77) {
                            $record = MyHelpers::addNewReordTamweelk($reqID, $user_id, $request_data->created_at);
                        }
                    }
                }
                else {
                    $record = MyHelpers::addNewReordWebsite($reqID, $user_id, $request_data->created_at);
                }

                $content = MyHelpers::guest_trans('PendingRequests');
                $record = MyHelpers::addNewReordPending($reqID, $user_id, $content);

                DB::table('pending_requests')->where('id', $request_data->id)->delete();

            }
        }

        return response()->json([
            'status' => true,
            'msg'    => trans('language.Excute Succesffuly', [
                'count'          => $totalcount,
                'accepted_count' => count($request_achive_condition_list),
            ]),
        ]);
        // return redirect()->back()->with('success', trans('language.Excute Succesffuly',['count'=>count($request_achive_condition_list)]));
    }

    public static function checkReqest(
        $salary,
        $birth_date,
        $birth_hijri,
        $work,
        $is_supported,
        $has_property,
        $has_joint,
        $has_obligations,
        $has_financial_distress,
        $owning_property,
        $from_birth_date,
        $to_birth_date,
        $from_birth_hijri,
        $to_birth_hijri,
        $from_salary,
        $to_salary,
        $work_setting,
        $support_setting,
        $property_setting,
        $joint_setting,
        $obligations_setting,
        $distress_setting,
        $owning_property_setting
    ) {

        // start helper function for check
        $is_acheive = true;
        if (!empty($from_birth_date) || !empty($to_birth_date)) {
            $is_acheive = MyHelpers::check_data_is_between_rang($birth_date, $from_birth_date, $to_birth_date) && $is_acheive;
        }
        if (!empty($from_birth_hijri) || !empty($to_birth_hijri)) {
            $is_acheive = MyHelpers::check_data_is_between_rang($birth_hijri, $from_birth_hijri, $to_birth_hijri) && $is_acheive;
        }
        if (!empty($from_salary) || !empty($to_salary)) {
            $is_acheive = MyHelpers::check_salary_between_rang($salary, $from_salary, $to_salary) && $is_acheive;
        }
        if (!empty($work_setting)) {
            $is_acheive = MyHelpers::check_work($work, $work_setting) && $is_acheive;
        }
        if (!empty($support_setting)) {
            $is_acheive = MyHelpers::check_support($is_supported, $support_setting) && $is_acheive;
        }
        if (!empty($property_setting)) {
            $is_acheive = MyHelpers::check_property($has_property, $property_setting) && $is_acheive;
        }
        if (!empty($joint_setting)) {
            $is_acheive = MyHelpers::check_joint($has_joint, $joint_setting) && $is_acheive;
        }
        if (!empty($obligations_setting)) {
            $is_acheive = MyHelpers::check_obligations($has_obligations, $obligations_setting) && $is_acheive;
        }
        if (!empty($distress_setting)) {
            $is_acheive = MyHelpers::check_distress($has_financial_distress, $distress_setting) && $is_acheive;
        }
        if (!empty($owning_property_setting)) {
            $is_acheive = MyHelpers::owning_property($owning_property, $owning_property_setting) && $is_acheive;
        }

        return $is_acheive;
    }

    public function getAcceptedCondition(Request $request)
    {

        $pendingRequests = DB::table('pending_requests')->join('customers', 'customers.id', '=', 'pending_requests.customer_id')->join('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')->select('customers.*', 'real_estats.has_property', 'real_estats.owning_property',
            'pending_requests.*')->get();

        $totalcount = count($pendingRequests);
        $request_achive_condition_list = [];

        foreach ($pendingRequests as $request_data) {

            $isAccept = MyHelpers::check_is_request_acheive_condition($request);
            if ($isAccept) {
                $request_achive_condition_list[] = $request_data;
            }

        }

        return response()->json([
            'status'         => true,
            'count'          => $totalcount,
            'accepted_count' => count($request_achive_condition_list),
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
