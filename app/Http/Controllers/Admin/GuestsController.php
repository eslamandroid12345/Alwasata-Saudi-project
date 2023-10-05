<?php

namespace App\Http\Controllers\Admin;

use App\customer as Customer;
use App\CustomersPhone;
use App\GuestCustomer;
use App\Http\Controllers\Controller;
use App\military_ranks;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\RequestHistory;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MyHelpers;
use View;

//to take date

class GuestsController extends Controller
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
    //-------------------------------------------------------------------
    // #Task-34
    //-------------------------------------------------------------------
    public function index()
    {
        /*$guests = GuestCustomer::all();*/
        $works = WorkSource::all();
        $militaries = military_ranks::all();

        $moveCondition = DB::table('settings')
            ->where('option_name', 'hasbah_net_movment')->first();
        $move_hours_Condition = DB::table('settings')
            ->where('option_name', 'hasbah_net_movment_hours')->first();
        $salesAgents = User::where('role', 0)
            ->where('status', 1)->get();

        return view('Admin.Guests.index', [
            'works' => $works,
            'militaries' => $militaries,
            'salesAgents' => $salesAgents,
            'moveCondition' => $moveCondition,
            'move_hours_Condition' => $move_hours_Condition
        ]);
    }

    public function filter(Request $request)
    {
        $rules = [
            'works' => 'required',
        ];

        $messages = [
            'works.required'       => 'جهه العمل مطلوبة *',
            'has_request.required' => 'هل لديه طلب سابق مطلوب *',
            'status.required'      => 'حالة الطلب مطلوبة *',
        ];
        if ($request->has('works')) {
            $val = array_search('عسكري', $request->works);
            if (is_numeric($val) != false) {
                $rules['ranks'] = 'required';
                $messages['ranks.required'] = 'الرتبة مطلوبة *';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $guests = GuestCustomer::orderBy('created_at');

            //-------------------------------------------------------------
            // Date Fram Time
            //-------------------------------------------------------------
            if ($request->start_date != null && $request->end_date != null) {
                // End Date & Start Date
                $guests->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            if ($request->start_date != null && $request->end_date == null) {
                // Start Date Only
                $guests->where('created_at', '>=', $request->start_date);
            }
            if ($request->start_date == null && $request->end_date != null) {
                // End Date Only
                $guests->where('created_at', '<=', $request->end_date);
            }
            //-------------------------------------------------------------
            // Salary
            //-------------------------------------------------------------

            if ($request->from_salary != null && $request->to_salary != null) {
                // From Salary & To Salary
                $guests->where('salary', '>=', $request->from_salary);
                $guests->where('salary', '<=', $request->to_salary);
            }

            if ($request->from_salary != null && $request->to_salary == null) {
                // From Salary Only
                $guests->where('salary', '>=', $request->from_salary);
            }

            if ($request->from_salary == null && $request->to_salary != null) {
                // To Salary Only
                $guests->where('salary', '<=', $request->to_salary);
            }

            //-------------------------------------------------------------
            // Status
            //-------------------------------------------------------------
            if (count($request->status) != 0) {
                $guests->whereIn('status', $request->status);
            }

            //-------------------------------------------------------------
            // Has Request
            //-------------------------------------------------------------
            if (count($request->has_request) != 0) {
                $arry =$request->has_request;

                if(!array_search("0",$arry) == -1){

                    array_push($arry,"2");
                }

                $guests->whereIn('has_request',$arry);
            }

            //-------------------------------------------------------------
            // Works
            //-------------------------------------------------------------
            $guest_new = 0;

            if ($request->works != null) {

                $val = array_search('عسكري', $request->works);
                if (is_numeric($val) != false) {
                    if ($request->ranks != null) {
                        $ranks = $request->ranks;
                        $guest_new = clone $guests;
                        $guest_new = $guest_new->whereIn('military_rank', $ranks);
                    }
                }
                $guests->whereIn('work', $request->works);
            }

            if (is_numeric($guest_new) == false) {
                $guest_news = $guest_new->pluck('id')->toArray();

                if (count($guest_news) != 0) {
                    $guest = $guests->orWhereIn('id', $guest_news)->get();
                }
                else {
                    $guest = $guests->get();
                }
            }
            else {
                $guest = $guests->get();
            }

            // Render Section

            $view = view('Admin.Guests.results')->with([
                'guests' => $guest,
            ])->renderSections();
            return response()->json([
                'success' => true,
                'view'    => $view,
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $Ask = GuestCustomer::find($id);
        $Ask->deleted_at = now();
        $Ask->save();

        return response()->json([
            "success"   => true,
            "message"   => "تم الحذف بنجاح"
        ]);
    }

    public function moveGuestUsers(Request $request)
    {
        /**
         * Automatic Distribution
         */

        $autoDistribution = !1;
        $counter = 0;
        $i = 0;
        $salesAgents = [];
        $guest_users_reqs = GuestCustomer::whereIn('id', $request->id)->get();
        if (!$request->agents_ids) {
            $autoDistribution = !0;
            //$salesAgents = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }

        //$req_source = 2;
        $collaborator_id = null;
        //$collaborator_id = $request->not_complete == 'false' ? 269 : 288;
        if ($request->not_complete == 'false' || !$request->not_complete) {
            $req_source = \App\Models\Request::HASBAH_SOURCE;
            //$collaborator_id = null;
        }
        else {
            $req_source = \App\Models\Request::HASBAH_SOURCE_NOT_COMPLETE;
            //$collaborator_id = 288;
        }

        foreach ($guest_users_reqs as $reqInfo) {
            if (count($salesAgents) == $i) {
                $i = 0;
            }

            if ($autoDistribution) {
                $salesAgents[$i] = getLastAgentOfDistribution();
            }

            $customer = null;
            $customerID = DB::table('customers')->where('mobile', $reqInfo->mobile)->first();
            if ($customerID == null) {
                $customer = DB::table('customers_phones')->where('mobile', $reqInfo->mobile)->first();
            }

            if ($customer == null && $customerID == null) {
                $input['birth_hijri'] = $reqInfo->birth_date;
                $input['salary'] = $reqInfo->salary;
                $input['work'] = $reqInfo->work;
                $input['birth_date'] = null;

                $is_approved = MyHelpers::check_is_request_acheive_condition($request);
                $customer_email = null;
                $customer_email_check = DB::table('customers')->where('email', $reqInfo->email)->first();
                if (empty($customer_email_check)) {
                    $customer_email = $reqInfo->email;
                }
                $work = null;
                $getworkValue = DB::table('work_sources')->where('id', $reqInfo->work)->first();
                if (!empty($getworkValue)) {
                    $work = $getworkValue->value;
                }

                $customerId = DB::table('customers')->insertGetId([
                    //add it once use insertGetId
                    'user_id'          => $salesAgents[$i],
                    'name'             => $reqInfo->name,
                    'mobile'           => $reqInfo->mobile,
                    'email'            => $customer_email,
                    'welcome_message'        => 2,
                    'birth_date_higri' => $reqInfo->birth_date,
                    'work'             => $work,
                    'salary'           => $reqInfo->salary,
                    'created_at'       => now('Asia/Riyadh'),
                ]);
                //insertGetId : insertGetId method to insert a record and then retrieve the ID
                $joinID = DB::table('joints')->insertGetId([
                    //add it once use insertGetId
                    // 'customer_id' => $customerId,
                    'created_at' => now('Asia/Riyadh'),
                ]);

                $realID = DB::table('real_estats')->insertGetId([
                    //'customer_id' => $customerId,
                    'created_at' => now('Asia/Riyadh'),
                ]);

                $funID = DB::table('fundings')->insertGetId([
                    // 'customer_id' => $customerId,
                    'created_at' => now('Asia/Riyadh'),
                ]);

                $reqDate = now('Asia/Riyadh');
                $searching_id = RequestSearching::create()->id;
                if ($is_approved) {
                    $reqID = DB::table('requests')->insertGetId([
                        'req_date'        => $reqDate,
                        'created_at'      => $reqInfo->created_at,
                        'searching_id'    => $searching_id,
                        'user_id'         => $salesAgents[$i],
                        'customer_id'     => $customerId,
                        'collaborator_id' => $collaborator_id,
                        'source'          => $req_source,
                        'joint_id'        => $joinID,
                        'real_id'         => $realID,
                        'fun_id'          => $funID,
                        'statusReq'       => 0,
                        'agent_date'      => carbon::now(),
                    ]);

                    if ($autoDistribution) {
                        setLastAgentOfDistribution($salesAgents[$i]);
                    }

                    // add notification to send user
                    DB::table('notifications')->insert([
                        'value'      => MyHelpers::guest_trans('New Request Added'),
                        'recived_id' => $salesAgents[$i],
                        'created_at' => now('Asia/Riyadh'),
                        'type'       => 0,
                        'req_id'     => $reqID,
                    ]);
                    // add to request history
                    DB::table('request_histories')->insert([
                        'title'        => RequestHistory::TITLE_MOVE_REQUEST,
                        'recive_id'    => $salesAgents[$i],
                        'history_date' => now('Asia/Riyadh'),
                        'req_id'       => $reqID,
                        'content'      => MyHelpers::guest_trans('hasbah_net'),
                    ]);

                    //***********UPDATE DAILY PREFROMENCE */
                    $agent_id = $salesAgents[$i];
                    if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                        $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                    }
                    MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
                   // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
                    //***********END - UPDATE DAILY PREFROMENCE */

                    GuestCustomer::where('id', $reqInfo->id)->delete();
                    $counter++;
                    $i++;

                }
                else {
                    #pending
                    PendingRequest::create([
                        'statusReq'       => 0,
                        'customer_id'     => $customerId,
                        'user_id'         => null,
                        'source'          => $req_source,
                        'req_date'        => $reqDate,
                        'created_at'      => $reqInfo->created_at,
                        'joint_id'        => $joinID,
                        'real_id'         => $realID,
                        'searching_id'    => $searching_id,
                        'fun_id'          => $funID,
                        'collaborator_id' => $collaborator_id,

                    ]);

                    GuestCustomer::where('id', $reqInfo->id)->delete();
                    $counter++;
                }
            }
            else {
                $this->notifyMoveRequests($reqInfo->mobile);
                # duplicate
                GuestCustomer::where('id', $reqInfo->id)->delete();
                $counter++;
            }

        }

        if ($counter == 0) {
            return response()->json(['updatereq' => 0, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }
        return response()->json(['counter' => $counter, 'updatereq' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function notifyMoveRequests($mobile)
    {
        try {
            $customer = Customer::where('mobile', $mobile)->first();
            $phones = CustomersPhone::where('mobile', $mobile)->first();
            if ($phones) {
                $customer = Customer::find($phones->customer_id)->first();
            }
            if (!($checkRequest = \App\request::where('customer_id', $customer->id)->first())) {
                $checkRequest = PendingRequest::where('customer_id', $customer->id)->first();
            }

            if ($checkRequest && $checkRequest->class_id_agent != 16 && $checkRequest->class_id_agent != 13) {
                //we will not notify the REJECTED & CUSTOMER NOT WANT TO COMPLETE classifications
                if (MyHelpers::resubmitCustomerReqTime($checkRequest->agent_date)) {
                    // If The Difference Between Days is Greater Than Specified
                    $gms = MyHelpers::getAllActiveGM();
                    #send notify to admin
                    foreach ($gms as $gm) {
                        $value = MyHelpers::guest_trans('The customer tried to submit a new request');
                        if (MyHelpers::checkDublicateNotification($gm->id, $value, $checkRequest->id)) {
                            DB::table('notifications')->insert([
                                'value'      => $value,
                                'recived_id' => $gm->id,
                                'created_at' => (Carbon::now('Asia/Riyadh')),
                                'type'       => 5,
                                'req_id'     => $checkRequest->id,
                            ]);
                            MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                        }
                    }
                }
                else {
                    // If The Difference Between Days is Less Than Specified
                    $value = MyHelpers::guest_trans('Your customer tried to submit a new request');
                    $user = DB::table('users')->where('id', $checkRequest->user_id)->first();
                    if (MyHelpers::checkDublicateNotification($user->id, $value, $checkRequest->id)) {
                        DB::table('notifications')->insert([
                            'value'      => $value,
                            'recived_id' => $user->id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                            'type'       => 5,
                            'req_id'     => $checkRequest->id,
                        ]);
                        MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $user->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                    }
                }
            }
        }
        catch (Exception $exception) {

        }
    }

    public function updatemovmenthours(Request $request)
    {
        $updatemovmenthours = DB::table('settings')->where('option_name', 'hasbah_net_movment_hours')->update(['option_value' => $request->hours]);

        return response()->json(['status' => $updatemovmenthours, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);

    }
}
