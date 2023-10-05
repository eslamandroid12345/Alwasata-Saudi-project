<?php

namespace App\Http\Controllers;

use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\District;
use App\funding_source;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\RequestHistory;
use App\RejectionsReason;
use App\RequestWaitingList;
use App\salary_source;
use App\task;
use App\User;
use App\WorkSource;
use Auth;
use Carbon\Carbon;
use Datatables;
use Exception;
use GeniusTS\HijriDate\Hijri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MyHelpers;
use Session;
use View;

class AgentController extends Controller
{
    use AgentControllerTraitOne;

    protected $userId;

    public function __construct()
    {
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);

        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['themes.theme1.layouts.content'],
            'App\Composers\ActivityComposer'         => ['themes.theme1.layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['themes.theme1.layouts.content'],
        ]);
    }

    public function homePage()
    {
        return view('Agent.home.home');
    }

    public function convertToGregorian(Request $request)
    {
        // return($request->hijri);
        $date = $request->hijri;
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $output = Hijri::convertToGregorian((int) $day, (int) $month, (int) $year);

        $year2 = substr($output, 0, 4);
        $month2 = substr($output, 5, 2);
        $day2 = substr($output, 8, 2);

        return $year2.'-'.$month2.'-'.$day2;
    }

    /////////////////////NOTIFCATIONES////////////////////////

    public function convertToHijri(Request $request)
    {
        // return($request->gregorian);
        $date = Hijri::convertToHijri($request->gregorian);

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        return $year.'-'.$month.'-'.$day;
    }

    ///////////////////////////////////////////////////

    public function addCustomer_page()
    {

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();

        return view('Agent.Customer.addCustomer', compact('salary_sources', 'madany_works', 'askary_works'));
    }

    public function addCustomerWithReq()
    {

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $request_sources = DB::table('request_source')->get();
        if(env('NEW_THEME') == '1'){
            return view('themes.theme1.Agent.Customer.addCustomerWithReq', compact('salary_sources', 'madany_works', 'askary_works', 'collaborators', 'request_sources'));
        }else{
            return view('Agent.Customer.addCustomerWithReq', compact('salary_sources', 'madany_works', 'askary_works', 'collaborators', 'request_sources'));
        }
    }

    public function addCustomerWithReqPost(Request $request)
    {
        if ($request->reqsour == 2) { //should supicify who is collobreator once selectd req source from collobreator
            $rules = [
                'name'         => 'required',
                'collaborator' => 'required',
                'reqsour'      => 'required',
                'mobile'       => 'required|digits:9|regex:/^(5)[0-9]{8}$/|unique:customers,mobile,'.auth()->user()->id, // unique email',
            ];

            $customMessages = [
                'mobile.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'),
                'name.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'collaborator.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'         => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            ];
        }
        else {
            $rules = [
                'name'    => 'required',
                'reqsour' => 'required',
                'mobile'  => 'required|digits:9|regex:/^(5)[0-9]{8}$/|unique:customers,mobile,'.auth()->user()->id, // unique email',
            ];

            $customMessages = [
                'mobile.unique'    => MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'),
                'name.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            ];
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        // Task-17 Add Another Validation For Phones Table
        //**************************************************************
        $validator2 = Validator::make($request->all(), [
            'mobile' => ['unique:customers_phones'],
        ], [
            'mobile.unique' => 'رقم الجوال موجود بالفعل  *',
        ]);
        // Check validation failure
        if ($validator->fails() || $validator2->fails()) {
            $failedRules = $validator->failed();
            $failedRules2 = $validator2->failed();

            if (isset($failedRules['mobile']['Unique']) || isset($failedRules2['mobile']['Unique'])) {

                //Notify GM
                $customerID = DB::table('customers')->where('mobile', $request->mobile)->first();
                if ($customerID == null) {
                    $customer = DB::table('customers_phones')->where('mobile', $request->mobile)->first();
                    $customerID = DB::table('customers')->find($customer->customer_id);
                }

                $req_info = DB::table('requests')->where('customer_id', $customerID->id)->orderBy('id', 'desc')->first();
                if ($req_info){
                    if ($req_info->class_id_agent != 16 && $req_info->class_id_agent != 13) { //we will not notify the REJECTED & CUSTOMER NOT WANT TO COMPLETE clssifications
                        if (MyHelpers::resubmitCustomerReqTime($req_info->agent_date) || true) {

                            // If The Difference Between Days is Grether Than Specified
                            // $gms = MyHelpers::getAllActiveGM();
                            $gms = MyHelpers::getAllActiveGMAndAdmins();

                            #send notifiy to admin

                            foreach ($gms as $gm) {

                                $value = auth()->user()->name.'  حاول إضافة العميل';
                                if (MyHelpers::checkDublicateNotification($gm->id, $value, $req_info->id)) {
                                    DB::table('notifications')->insert([ // add notification to send user
                                                                         'value'      => $value,
                                                                         'recived_id' => $gm->id,
                                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                                         'type'       => 5,
                                                                         'req_id'     => $req_info->id,
                                    ]);

                                    MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                                }
                            }
                        }
                        else {
                            // If The Difference Between Days is Less Than Specified
                            $value = 'يوجد محاولة لإضافة عميل لديك';
                            $user = User::find($req_info->user_id);
                            if (MyHelpers::checkDublicateNotification($user->id, $value, $req_info->id)) {
                                DB::table('notifications')->insert([ // add notification to send user
                                                                     'value'      => $value,
                                                                     'recived_id' => $user->id,
                                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                                     'type'       => 5,
                                                                     'req_id'     => $req_info->id,
                                ]);

                                $emailNotify = MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $user->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                            }
                        }
                        //********************************************************************
                    }
                }


                /*
                //Notify Admin
                    $admins=MyHelpers::getAllActiveAdmin();

                    #send notifiy to admin
                    foreach( $admins as $admin){
                    DB::table('notifications')->insert([ // add notification to send user
                        'value' => MyHelpers::guest_trans('The customer tried to submit a new request'), 'recived_id' =>  $admin->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 5,
                        'req_id' =>  $request->id,
                    ]);

                    $emailNotify=MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $admin->id,' عميل مكرر ','العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                    if ($checkpending)
                    //$pwaPush=MyHelpers::pushPWA($admin->id, ' يومك سعيد  '.$admin->name, 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني', 'فتح الطلب','admin','fundingreqpage',$request->id);

                }
                //Notify Admin
                */
            }
        }

        $validator->validate();
        $validator2->validate();

        $customerId = DB::table('customers')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
            [ //add it once use insertGetId
              'user_id'         => auth()->user()->id,
              'name'            => $request->name,
              'mobile'          => $request->mobile,
              'welcome_message' => 2,
              'created_at'      => (Carbon::now('Asia/Riyadh')),
            ]);

        $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
            [ //add it once use insertGetId
              // 'customer_id' => $customerId,
              'created_at' => (Carbon::now('Asia/Riyadh')),
            ]);

        $realID = DB::table('real_estats')->insertGetId([
                //'customer_id' => $customerId,
                'created_at' => (Carbon::now('Asia/Riyadh')),
            ]

        );

        $funID = DB::table('fundings')->insertGetId([
                // 'customer_id' => $customerId,
                'created_at' => (Carbon::now('Asia/Riyadh')),
            ]

        );

        $reqdate = (Carbon::now('Asia/Riyadh'));

        $searching_id = RequestSearching::create()->id;
        $reqID = DB::table('requests')->insertGetId([
                'source'          => $request->reqsour,
                'req_date'        => $reqdate,
                'created_at'      => (Carbon::now('Asia/Riyadh')),
                'searching_id'    => $searching_id,
                'user_id'         => auth()->user()->id,
                'customer_id'     => $customerId,
                'collaborator_id' => $request->collaborator,
                'joint_id'        => $joinID,
                'real_id'         => $realID,
                'fun_id'          => $funID,
                'statusReq'       => 1,
                'agent_date'      => carbon::now(),
            ]

        );

        //dd($reqID );

        if ($reqID) {
            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = auth()->user()->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
            // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */
            // Request history
            $this->history($reqID, MyHelpers::admin_trans(auth()->user()->id, 'Create Request'), null, null);
            return redirect()->route('agent.fundingRequestFromMsg', $reqID)->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }

        return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
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

    public function moveRequestWithAvalibleConditionToMe(Request $request)
    {
        $check = false;
        $requestData = null;
        $typeOfReq = MyHelpers::typeOfRequest($request->mobile);
        //dd($typeOfReq);
        if ($typeOfReq == 'pending') //in pending table
        {
            $requestData = MyHelpers::getPendingRequestByMobile($request->mobile);
            $getReqId = MyHelpers::movePendingToRequestTable($requestData);
            if ($getReqId != null) {
                $check = true;
                $requestData = $getReqId;
            }
            else {
                return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        if ($typeOfReq == 'request' && !$check) {
            $requestData = MyHelpers::getActiveRequestByMobile($request->mobile);
            //dd($requestData->is_freeze);
            //dd($reqID);
            if (MyHelpers::checkIfNeedActionReqExisted($requestData->id)) { // existed in need action req table
                MyHelpers::moveRequestExistedInNeedAction($requestData);
                //***********UPDATE DAILY PREFROMENCE */
                $agent_id = $requestData->user_id;
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$requestData->id);
                //***********END - UPDATE DAILY PREFROMENCE */
                $check = true;
                $req_ID = $requestData->id;
            }
            else {
                $getAgent = DB::table('users')->where('id', $requestData->user_id)->where('status', 0)->first(); //Archive Agent

                if (($requestData->statusReq == 0 || $requestData->statusReq == 1 || $requestData->statusReq == 2 || $requestData->statusReq == 4)) {
                    //dd($requestData->is_freeze);
                    if ($getAgent || $requestData->is_freeze) {
                        //previous request in another archived agent
                        MyHelpers::moveRequestFromArchivedAgent($requestData);
                        $check = true;
                        $req_ID = $requestData->id;
                    }
                }
                elseif ($requestData->is_freeze) {
                    MyHelpers::moveRequestFromArchivedAgent($requestData);
                    //\App\Models\Request::query()->find($reqID->id)->update([
                    //    'is_freeze' => 0,
                    //    'user_id'   => auth()->id,
                    //]);
                }
            }
            //dd($requestData);

            /*
            if ($reqID->class_id_agent == 16 || $reqID->class_id_agent == 13) { // rejected request or customer doesn't want
                MyHelpers::moveRequestWithRejectedClassOrCustomerNotWantIt($reqID);
                return redirect()->route('agent.fundingRequestFromMsg', $reqID->id)->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
            }
            */

            if ($requestData->statusReq == 2 && !$check) {

                if (!MyHelpers::checkQualityReq($requestData->id)) {

                    $qualityRequest = DB::table('quality_reqs')->whereIn('status', [0, 1, 2, 5])->where('req_id', $requestData->id)->first();

                    if ($qualityRequest->status == 0) // still new and quality not open it yet
                    {
                        DB::table('quality_reqs')->where('id', $qualityRequest->id)->delete();
                    }
                    else {
                        if ($qualityRequest->status != 0 && $qualityRequest->status != 5) {
                            DB::table('quality_reqs')->where('id', $qualityRequest->id)->update(['status' => 3]);
                        }
                    }
                }

                MyHelpers::moveArchivedRequest($requestData);
                //***********UPDATE DAILY PREFROMENCE */
                $agent_id = $requestData->user_id;
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$requestData->id);
                //***********END - UPDATE DAILY PREFROMENCE */
                $check = true;
                $req_ID = $requestData->id;
            }
        }

        //dd($typeOfReq);
        if ($check) {
            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = auth()->user()->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$requestData->id);
            //  MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$req_ID);
            //***********END - UPDATE DAILY PREFROMENCE */

            return redirect()->route('agent.fundingRequestFromMsg', $req_ID)->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }

        return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    //This new function to show dataTabel in view(Agent.Customer.mycustomers)

    public function mycustomer()
    {

        $customers = DB::table('customers')->where([
            ['status', '=', '0'], //active
            ['user_id', '=', auth()->user()->id],
        ])->count();

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $notifys = $this->fetchNotify();

        return view('Agent.Customer.myCustomers', compact('customers', 'salary_sources', 'notifys'));
    }

    public function fetchNotify()
    { // to get notificationes of users

        $checkFollow = DB::table('notifications')->where('recived_id', (auth()->user()->id))->where('reminder_date', "<=", Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s"))->where('status', 2) //Not Active (for following)
        ->first();

        if (!empty($checkFollow)) {
            DB::table('notifications')->where('id', $checkFollow->id)->update([
                'status'     => 0,
                'created_at' => Carbon::now('Asia/Riyadh'),
            ]);

            //email notification
            !config('app.debug') && MyHelpers::sendEmailNotifiaction('follow_req', auth()->user()->id, 'طلب يحتاج متابعة', 'لديك طلب يحتاج لمتابعتك');

        }

        return DB::table('notifications')->where('recived_id', (auth()->user()->id))->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.status', 0) // new
        ->orderBy('notifications.id', 'DESC')->select('notifications.*', 'customers.name')->get();
    }

    public function mycustomer_datatable()
    {
        $customers = DB::table('customers')->where([
            ['status', '=', '0'], //active
            ['user_id', '=', auth()->user()->id],
        ])->orderBy('id', 'DESC');
        return Datatables::of($customers)->setRowId(function ($customer) {
            return $customer->id;
        })->addColumn('salry', function ($row) {
            if ($row->salary != null) {
                $data = $row->salary.MyHelpers::admin_trans(auth()->user()->id, 'SR');
            }
            else {
                $data = '---';
            }
            return $data;
        })->addColumn('supported', function ($row) {
            if ($row->is_supported == 'yes') {
                $data = 'نعم';
            }
            else {
                $data = 'لا';
            }
            return $data;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                    <a href="'.route('agent.profileCustomer', $row->id).'"> <i class="fas fa-eye"></i></a>
                                </span>';
            $data = $data.'<span class="item pointer" id="edit" type="span" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';
            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash"></i>
                                </span> ';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function archCustomerPage()
    {

        $customers = DB::table('customers')->where([
            ['status', '=', '1'], //archive customer
            ['user_id', '=', auth()->user()->id],
        ])->count();

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();

        $notifys = $this->fetchNotify(); //get notificationes

        return view('Agent.Customer.archCustomers', compact('customers', 'salary_sources', 'notifys'));
    }

    public function archCustomerPage_datatable()
    {

        $customers = DB::table('customers')->where([
            ['status', '=', '1'], //archive customer
            ['user_id', '=', auth()->user()->id],
        ])->orderBy('id', 'DESC');

        return Datatables::of($customers)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                            <a href="'.route('agent.restoreCustomer', $row->id).'"> <i class="fas fa-time-restore-setting"></i></a>
                      </span></div>';
            return $data;
        })->addColumn('salary', function ($row) {
            if ($row->salary != null) {
                $data = $row->salary.MyHelpers::admin_trans(auth()->user()->id, 'SR');
            }
            else {
                $data = '---';
            }
            return $data;
        })->addColumn('is_supported', function ($row) {
            if ($row->is_supported == 'yes') {
                $data = 'نعم';
            }
            else {
                $data = 'لا';
            }
            return $data;
        })->make(true);
    }

    public function editCustomer(Request $request)
    {

        $auth = Auth::user();  // get  auth info
        $id = $auth->id;

        $checkmobile = DB::table('customers')->where('mobile', $request->mobile)->first();

        if (!empty($checkmobile)) {
            if ($request->id != $checkmobile->id) {
                return response('existed');
            }
        }

        $rules = [
            'name'   => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            // 'sex' => 'required',
            // 'birth' => 'required',
            // 'work' => 'required',
            // 'salary_source' => 'required',
            //  'salary' => 'numeric',
        ];

        $customMessages = [
            // 'mobile.unique' => MyHelpers::admin_trans(auth()->user()->id, 'This customer already existed'),
            'mobile.regex'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.digits'   => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            // 'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateResult = DB::table('customers')->where([
            ['id', '=', $request->id],
            ['user_id', '=', auth()->user()->id],
        ])->update([
            'name'             => $request->name,
            'mobile'           => $request->mobile,
            'sex'              => $request->sex,
            'birth_date'       => $request->birth,
            'birth_date_higri' => $request->birth_hijri,
            'age'              => $request->age,
            'work'             => $request->work,
            'salary_id'        => $request->salary_source,
            'salary'           => $request->salary,
            'is_supported'     => $request->support,
        ]); //if $updateResult=1 , so it's edit a new thing but if return 0 , so no data change

        $arr = [

            'request' => $request->all(), //brcause it's contain alot of data
            'ss'      => $updateResult,
        ];

        return response($arr);
    }

    public function addCustomer2(Request $request)
    {

        $rules = [
            'name'   => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            //'sex' => 'required',
            // 'birth' => 'required',
            // 'work' => 'required',
            // 'salary_source' => 'required',
            //  'salary' => 'numeric',
        ];

        $customMessages = [
            'mobile.regex'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.digits'   => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $mobile = $request->mobile;
        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();

        if (empty($checkmobile)) {
            $userID = auth()->user()->id;
            $name = $request->name;
            $sex = $request->sex;
            $birth = $request->birth;
            $birth_hijri = $request->birth_hijri;
            $age = $request->age;
            $work = $request->work;
            $salary_source = $request->salary_source;
            $salary = $request->salary;
            $support = $request->support;
            $rank = $request->rank;
            $madany = $request->madany_work;
            $job_title = $request->job_title;
            $askary_work = $request->askary_work;

            $resultId = DB::table('customers')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  'user_id'          => $userID,
                  'name'             => $name,
                  'mobile'           => $mobile,
                  'sex'              => $sex,
                  'birth_date'       => $birth,
                  'birth_date_higri' => $birth_hijri,
                  'age'              => $age,
                  'work'             => $work,
                  'salary_id'        => $salary_source,
                  'salary'           => $salary,
                  'is_supported'     => $support,
                  'military_rank'    => $rank,
                  'madany_id'        => $madany,
                  'welcome_message'  => 2,
                  'job_title'        => $job_title,
                  'askary_id'        => $askary_work,
                ]);

            $arr = [

                'request' => $request->all(), //brcause it's contain alot of data
                'ss'      => $resultId,
            ];

            return response($arr);
        }
        else {
            $arr = [

                'request' => 'error', //brcause it's contain alot of data
                'ss'      => 'error',
            ];
        }
        return response($arr);
    }

    public function addCustomer(Request $request)
    {

        $rules = [
            'name'   => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
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
            'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //  'birth.required' => 'The birth date filed is required ',
        ];

        $this->validate($request, $rules, $customMessages);

        $mobile = $request->mobile;
        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();

        if (empty($checkmobile)) {
            $userID = auth()->user()->id;
            $name = $request->name;
            $sex = $request->sex;
            $birth = $request->birth;
            $birth_hijri = $request->birth_hijri;
            $age = $request->age;
            $work = $request->work;
            $salary_source = $request->salary_source;
            $salary = $request->salary;
            $support = $request->support;
            $rank = $request->rank;
            $madany = $request->madany_work;
            $job_title = $request->job_title;
            $askary_work = $request->askary_work;

            $result = DB::table('customers')->insert([
                [
                    'user_id'          => $userID,
                    'name'             => $name,
                    'mobile'           => $mobile,
                    'sex'              => $sex,
                    'birth_date'       => $birth,
                    'birth_date_higri' => $birth_hijri,
                    'age'              => $age,
                    'work'             => $work,
                    'salary_id'        => $salary_source,
                    'salary'           => $salary,
                    'is_supported'     => $support,
                    'military_rank'    => $rank,
                    'madany_id'        => $madany,
                    'job_title'        => $job_title,
                    'welcome_message'  => 2,
                    'askary_id'        => $askary_work,
                ],

            ]);

            if ($result == true) {
                return redirect()->route('agent.myCustomers')->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Customer added successfully'));
            }
            else {
                return redirect()->route('agent.myCustomers')->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {

            return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'));
        }
    }

    public function checkMobile(Request $request)
    {

        $mobile = $request->mobile;
        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();

        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
        ], [
            'mobile.required' => ' رقم الجوال مطلوب *',
            'mobile.numeric'  => 'رقم الجوال لابد ان يكون ارقام *',
            'mobile.unique'   => 'رقم الجوال موجود بالفعل  *',
            'mobile.regex'    => 'رقم الجوال غير صحيح *',
        ]);
        $validator2 = Validator::make($request->all(), [
            'mobile' => ['unique:customers_phones'],
        ], [
            'mobile.unique' => 'رقم الجوال موجود بالفعل  *',
        ]);

        if ($validator->passes() && $validator2->passes()) {
            return response('no'); // not existed
        }
        else {
            return response('yes'); //  existed
        }
    }

    public function updatecustomer(Request $request)
    {

        //  return response($request);

        $retriveCustomerData = DB::table('customers')->where([
            ['id', '=', $request->id],
            ['user_id', '=', (auth()->user()->id)],
        ])->first();

        if (!empty($retriveCustomerData)) {
            return response()->json([$retriveCustomerData, 'status' => 1]);
        }

        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0]);
        }
    }

    public function archiveCustomer(Request $request)
    {

        if ($request->ajax()) {

            $resulte = DB::table('customers')->where([
                ['id', '=', $request->id],
                ['user_id', '=', auth()->user()->id],
            ])->update(['status' => 1]); //archive
            if ($resulte == 0) //nothing delete
            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $resulte]);
            }

            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Archive successfully'), 'status' => $resulte]);
            }
        }
    }

    public function restoreCustomer(Request $request)
    {

        $resulte = DB::table('customers')->where([
            ['id', '=', $request->id],
            ['user_id', '=', auth()->user()->id],
        ])->update(['status' => 0]);
        if ($resulte == 0) //nothing delete
        {
            return redirect()->back()->with('msg2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
        }
        else {
            return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Restore successfully'));
        }
    }

    public function customerProfile(Request $request, $id)
    {

        $customer = DB::table('customers')->where('id', $id)->where('user_id', '=', auth()->user()->id)->first(); //will return first

        $notifys = $this->fetchNotify(); //get notificationes

        $purRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'شراء')->get();

        $morRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'رهن')->get();

        $morPurRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'رهن-شراء')->get();

        $payRequests = DB::table('requests')->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.customer_id', '=', $id)->where('type', '=', 'شراء')->get();

        //dd(  $payRequests);

        if (!empty($customer)) {
            return view('Agent.Customer.customerProfile', compact('customer', 'notifys', 'purRequests', 'morRequests', 'morPurRequests', 'payRequests'));
        }

        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function customerProfile_purRequests_datatable($id)
    {

        $purRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'شراء')->get();
        return Datatables::of($purRequests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('agent.fundingRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                          </span></div>';
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

    public function customerProfile_morRequests_datatable($id)
    {

        $morRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'رهن')->get();
        return Datatables::of($morRequests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('agent.fundingRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                          </span></div>';
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

    public function customerProfile_morPurRequests_datatable($id)
    {

        $morPurRequests = DB::table('requests')->where('customer_id', '=', $id)->where('type', '=', 'رهن-شراء')->get();
        return Datatables::of($morPurRequests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('agent.morPurRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                          </span></div>';
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

    public function customerProfile_payRequests_datatable($id)
    {

        $payRequests = DB::table('requests')->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.customer_id', '=', $id)->where('type', '=', 'شراء')->get();
        return Datatables::of($payRequests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('agent.fundingRequest', $row->id).'"> <i class="fas fa-eye"></i></a>
                          </span></div>';
            return $data;
        })->addColumn('status', function ($row) {
            switch ($row->payStatus) {
                case 0:
                    $status = 'draft pay';
                    break;
                case 1:
                    $status = 'wating sales manager req';
                    break;
                case 2:
                    $status = 'Canceled';
                    break;
                case 3:
                    $status = 'rejected sales manager req';
                    break;
                case 4:
                    $status = 'wating sales agent req';
                    break;
                case 5:
                    $status = 'wating mortgage manager req';
                    break;
                case 6:
                    $status = 'rejected mortgage manager req';
                    break;
                case 7:
                    $status = 'approved req';
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

    public function getPurchase($id, $title)
    {

        if ($id != 'null') {
            $customer = DB::table('customers')->where('id', $id)->first();
        }
        else {
            $customer = null;
        }

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        //dd($collaborators);

        $userID = auth()->user()->id;
        $user_role = DB::table('users')->select('role')->where('id', $userID)->get();
        $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

        $notifys = $this->fetchNotify(); //get notificationes

        $mycustomers = DB::table('customers')->where([
            ['status', '=', '0'],
            ['user_id', '=', auth()->user()->id],
        ])->get();

        return view('Agent.AddFundingReq.addPurchase', compact('notifys', 'title', 'customer', 'salary_sources', 'mycustomers', 'funding_sources', 'askary_works', 'madany_works', 'collaborators', 'classifcations'));
    }

    public function addfunding(Request $request)
    {

        //dd($request);

        if ($request->reqsour == 2) { //should supicify who is collobreator once selectd req source from collobreator

            $rules = [
                'customer'     => 'required',
                'collaborator' => 'required',
                'reqtyp'       => 'required',
                'reqsour'      => 'required',
                'mobile'       => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            ];

            $customMessages = [
                'customer.required'     => MyHelpers::admin_trans(auth()->user()->id, 'Should choose a customer'),
                'reqsour.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'collaborator.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqtyp.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'         => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'birth.required' => 'The birth date filed is required ',
            ];
        }
        else {
            $rules = [
                'customer' => 'required',
                'reqtyp'   => 'required',
                'reqsour'  => 'required',
            ];

            $customMessages = [
                'customer.required' => MyHelpers::admin_trans(auth()->user()->id, 'Should choose a customer'),
                'reqsour.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqtyp.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'     => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'birth.required' => 'The birth date filed is required ',
            ];
        }

        $this->validate($request, $rules, $customMessages);

        $customerID = $request->customer;

        // dd($request->customer);

        //
        $name = $request->jointname;
        $mobile = $request->jointmobile;
        $birth = $request->jointbirth;
        $birth_higri = $request->jointbirth_hijri;
        $age = $request->jointage;
        $work = $request->jointwork;
        $salary_source = $request->jointsalary_source;
        $rank = $request->jointrank;
        $madany = $request->jointmadany_work;
        $job_title = $request->jointjob_title;
        $askary_work = $request->jointaskary_work;
        $jointfunding_source = $request->jointfunding_source;

        $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
            [ //add it once use insertGetId
              'name'             => $name,
              'mobile'           => $mobile,
              'birth_date'       => $birth,
              'birth_date_higri' => $birth_higri,
              'age'              => $age,
              'work'             => $work,
              'salary_id'        => $salary_source,
              'military_rank'    => $rank,
              'created_at'       => (Carbon::now('Asia/Riyadh')),
              'madany_id'        => $madany,
              'job_title'        => $job_title,
              'funding_id'       => $jointfunding_source,
              'askary_id'        => $askary_work,
              //'customer_id' => $customerID,
            ]);

        //

        $realname = $request->realname;
        $realmobile = $request->realmobile;
        $realcity = $request->realcity;
        $realstatus = $request->realstatus;
        $realage = $request->realage;
        $realcost = $request->realcost;
        $realtype = $request->realtype;
        $realhas = $request->realhasprop;
        $othervalue = $request->othervalue;
        $realeva = $request->realeva;
        $realten = $request->realten;
        $realmor = $request->realmor;

        $realID = DB::table('real_estats')->insertGetId([
                'name'         => $realname,
                'mobile'       => $realmobile,
                'city'         => $realcity,
                'age'          => $realage,
                'status'       => $realstatus,
                'cost'         => $realcost,
                'type'         => $realtype,
                'other_value'  => $othervalue,
                'evaluated'    => $realeva,
                'tenant'       => $realten,
                'mortgage'     => $realmor,
                'created_at'   => (Carbon::now('Asia/Riyadh')),
                'has_property' => $realhas,
            ]

        );

        //

        $funding_source = $request->funding_source;
        $fundingdur = $request->fundingdur;
        $fundingpersonal = $request->fundingpersonal;
        $fundingpersonalp = $request->fundingpersonalp;
        $fundingreal = $request->fundingreal;
        $fundingrealp = $request->fundingrealp;
        $dedp = $request->dedp;
        $monthIn = $request->monthIn;

        $funID = DB::table('fundings')->insertGetId([
                'funding_source'   => $funding_source,
                'funding_duration' => $fundingdur,
                'personalFun_cost' => $fundingpersonal,
                'personalFun_pre'  => $fundingpersonalp,
                'realFun_cost'     => $fundingreal,
                'realFun_pre'      => $fundingrealp,
                'ded_pre'          => $dedp,
                'monthly_in'       => $monthIn,
                'created_at'       => (Carbon::now('Asia/Riyadh')),
            ]

        );

        //

        $reqtype = $request->reqtyp;
        $reqsour = $request->reqsour;
        $reqclass = $request->reqclass;
        $reqcomm = $request->reqcomm;
        $reqcollb = $request->collaborator;
        $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');

        $searching_id = RequestSearching::create()->id;
        $reqID = DB::table('requests')->insertGetId([
                'type'            => $reqtype,
                'source'          => $reqsour,
                'class_id_agent'  => $reqclass,
                'comment'         => $reqcomm,
                'req_date'        => $reqdate,
                'created_at'      => (Carbon::now('Asia/Riyadh')),
                'user_id'         => auth()->user()->id,
                'joint_id'        => $joinID,
                'real_id'         => $realID,
                'fun_id'          => $funID,
                'customer_id'     => $customerID,
                'collaborator_id' => $reqcollb,
                'statusReq'       => 1,
                'agent_date'      => carbon::now(),
                'searching_id'    => $searching_id,
            ]

        );

        /////////////////////////////////////////////

        if ($reqtype == 'رهن') { //add tsaheel info

            //

            $payID = DB::table('prepayments')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  'pay_date' => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                  'req_id'   => $reqID,
                ]);

            DB::table('requests')->where('id', $reqID)->update([
                'payment_id' => $payID,
            ]);
        }

        ///////////////////////////////////////

        $this->history($reqID, MyHelpers::admin_trans(auth()->user()->id, 'Create Request'), null, null);
        //

        return redirect()->route('agent.fundingRequest', ['id' => $reqID])->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Creating Successfully')); //your route has parameters, you may pass them as the second argument to the route
    }

    public function getCustomerInfo(Request $request)
    {

        //return response($request);

        $retriveCustomerData = DB::table('customers')->where([
            ['id', '=', $request->id],
            ['status', '=', 0] //Active user only
        ])->get();

        return response($retriveCustomerData);
    }

    public function fundingreqpage($id)
    {
        //dd(auth()->user()->id);//301-->agent
        $request = DB::table('requests')->where('requests.id', '=', $id)->first();
        if ($request->statusReq == 0) {
            $checkRecive = $this->checkReciveReqOpenAndWithoutCommentAndClass(auth()->user()->id);
            //dd($checkRecive);
            //dd(count($checkRecive));
            $countCheckRecive = count($checkRecive);
             if (($countCheckRecive > 0) && (!in_array($id, $checkRecive))&&false) {
                return redirect()->back()->with('message7', MyHelpers::admin_trans(auth()->user()->id, "You have open request without comment and class"));
            }
        }
        $userID = auth()->user()->id;
        $agentuser = DB::table('users')->where('id', $userID)->first();
        // $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();
        $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->select('*', 'requests.id AS request_id')->first();

        if (!empty($purchaseCustomer)) {
            if ($request->user_id == $userID) { // check if the request belong to sales agent or not
                $reqStatus = $request->statusReq;
                $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();
                $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();
                $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();
                $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();
                $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();
                $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();
                $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id',
                    auth()->user()->id)->where('users.status', 1)->get();
                //dd($collaborators);
                $product_types = null;
                $getTypes = MyHelpers::getProductType();
                if ($getTypes != null) {
                    $product_types = $getTypes;
                }
                //SHOW COMMENTS OR NOT (NEGAIVE Class)
                $get_agent_and_status_of_show = [];
                $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($id, $purchaseCustomer->comment);
                $history_negative_agent = $get_agent_and_status_of_show[0];
                $hide_negative_comment = $get_agent_and_status_of_show[1];
                ////////////////////////////////////////////////////////
                if ($request->type == 'رهن-شراء') {
                    $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
                }
                else {
                    $payment = DB::table('prepayments')->where('req_id', '=', $id)->where(function ($query) {
                        $query->where('payStatus', 4) //wating sales agent
                        ->orWhere('isSentSalesAgent', 1); //yes sent to sales manager
                    })->first();
                }
                if ($request->type == 'شراء-دفعة' && $payment == null) {
                    $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)->first();
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
                $user_role = DB::table('users')->select('role')->where('id', $userID)->get();
                $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();
                /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/
                $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                ->select('documents.*', 'users.name')->get();
                $notifys = $this->fetchNotify(); //get notificationes
                $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->get()->last(); //to get last reminder

                $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);
                if (!empty($followdate)) {
                    $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
                }
                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
                MyHelpers::openReqWillOpenNotify($id);
                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
                //***dispaly funding bank */
                $show_funding_source = MyHelpers::canShowBankName(auth()->user()->id);
                //***dispaly funding bank */
                //***to display alert about reopen request from customer */
                $is_customer_reopen_request = false;
                $histories = DB::table('request_histories')->where('req_id', $id)->where('title', 'فتح الطلب')->first();
                if (!empty($histories)) {
                    $is_customer_reopen_request = true;
                }
                //***to display alert about reopen request from customer */
                $districts = District::all();
                $prefix = 'agent';
                $worke_sources = WorkSource::all();
                $request_sources = DB::table('request_source')->get();
                $rejections = RejectionsReason::all();
                //***********UPDATE DAILY PREFROMENCE */
                $agent_id = auth()->user()->id;
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'opened_request',$id);
                //***********END - UPDATE DAILY PREFROMENCE */
                if(env('NEW_THEME') == '1')
                {
                    return view('themes.theme1.Agent.Funding-request.request-details',
                        compact('request','history_negative_agent', 'hide_negative_comment', 'purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id',
                            //Request ID
                            'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
                            'show_funding_source', 'is_customer_reopen_request', 'product_types', 'rejections', 'worke_sources', 'request_sources'));

                }else{
                    return view('Agent.fundingReq.fundingreqpage',
                        compact('request','history_negative_agent', 'hide_negative_comment', 'purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id',
                            //Request ID
                            'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
                            'show_funding_source', 'is_customer_reopen_request', 'product_types', 'rejections', 'worke_sources', 'request_sources'));

                }
            }
            else {

                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
            }
        }
        return view('Agent.fundingReq.fundingreqpage',
        compact('request','history_negative_agent', 'hide_negative_comment', 'purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id',
            //Request ID
            'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
            'show_funding_source', 'is_customer_reopen_request', 'product_types', 'rejections', 'worke_sources', 'request_sources'));

    }

    public function checkReciveReqOpenAndWithoutCommentAndClass($userID)
    {
       
        
//هجيب الاى دى بتاع الريكوست اللى حالتها ب 1 ومحدش لسه تابعها ولا رد عليها
        return DB::table('requests')->where('user_id', $userID)->where('statusReq', 1)->where('is_followed', 0)->where('is_stared', 0)->where(function ($query) {
            $query->where('class_id_agent', null)->orWhere('comment', null);
        })->pluck('id')->toArray();
    }

    public function fundingreqpageFromMsg(Request $request, $id)
    {

        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $userID = auth()->user()->id;

        $agentuser = DB::table('users')->where('id', $userID)->first();

        $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

        if (!empty($purchaseCustomer)) {
            if ($request->user_id == $userID) { // check if the request belong to sales agent or not

                $reqStatus = $request->statusReq;

                $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

                $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

                //dd ($purchaseReal);

                $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

                $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

                $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

                $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

                $product_types = null;
                $getTypes = MyHelpers::getProductType();
                if ($getTypes != null) {
                    $product_types = $getTypes;
                }
                // dd( $collaborator);

                $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

                // dd( $purchaseCustomer);

                //SHOW COMMENTS OR NOT (NEGAIVE Class)
                $get_agent_and_status_of_show = [];
                $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($id, $purchaseCustomer->comment);
                $history_negative_agent = $get_agent_and_status_of_show[0];
                $hide_negative_comment = $get_agent_and_status_of_show[1];
                ////////////////////////////////////////////////////////

                if ($request->type == 'رهن-شراء') {
                    $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
                }
                else {
                    $payment = DB::table('prepayments')->where('req_id', '=', $id)->where(function ($query) {
                        $query->where('payStatus', 4) //wating sales agent
                        ->orWhere('isSentSalesAgent', 1); //yes sent to sales manager
                    })->first();
                }

                if ($request->type == 'شراء-دفعة' && $payment == null) {
                    $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)->first();
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

                $user_role = DB::table('users')->select('role')->where('id', $userID)->get();
                $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', $user_role[0]->role)->get();

                /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

                $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
                ->select('documents.*', 'users.name')->get();

                $notifys = $this->fetchNotify(); //get notificationes

                $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->get()->last(); //to get last reminder

                $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

                if (!empty($followdate)) {
                    $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
                }

                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
                MyHelpers::openReqWillOpenNotify($id);
                //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

                /*
                $missedFileds = null;
                if (Session::has('missedFileds'))
                    $missedFileds = Session::get('missedFileds');
                    */

                //***dispaly funding bank */
                $show_funding_source = false;
                $show_funding_source = MyHelpers::canShowBankName(auth()->user()->id);
                //***dispaly funding bank */

                //***to display alert about reopen request from customer */
                $is_customer_reopen_request = false;
                $histories = DB::table('request_histories')->where('req_id', $id)->where('title', 'فتح الطلب')->first();
                if (!empty($histories)) {
                    $is_customer_reopen_request = true;
                }
                //***to display alert about reopen request from customer */

                $districts = District::all();

                $prefix = 'agent';

                $rejections = RejectionsReason::all();
                $worke_sources = WorkSource::all();
                $request_sources = DB::table('request_source')->get();

                if(env('NEW_THEME') == '1'){
                    return view('themes.theme1.Agent.fundingReq.fundingreqpage', compact('request','purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id', //Request ID
                    'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
                    'show_funding_source', 'is_customer_reopen_request', 'product_types', 'history_negative_agent', 'hide_negative_comment', 'rejections', 'worke_sources', 'request_sources'));
                }else{
                    return view('Agent.fundingReq.fundingreqpage', compact('request','purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id', //Request ID
                    'documents', 'reqStatus', 'payment', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
                    'show_funding_source', 'is_customer_reopen_request', 'product_types', 'history_negative_agent', 'hide_negative_comment', 'rejections', 'worke_sources', 'request_sources'));
                }

            }
            else {

                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
            }
        }
    }

    public function sendFunding(Request $request)
    {
        $reqType = $request->checktype;
        $salesManager = null;

        $currRequest = DB::table('requests')->where('id', $request->id)->first();

        if (($reqType == 'رهن' || $reqType == 'تساهيل') && ($currRequest->payment_id == null)) { //add tsaheel info

            //
            $paypreID = DB::table('prepayments')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  'pay_date' => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                  'req_id'   => $request->id,
                ]);

            DB::table('requests')->where('id', $request->id)->update([
                'payment_id' => $paypreID,
                'updated_at' => now('Asia/Riyadh'),
            ]);
        }

        if ($currRequest->source == null) {

            $sorce = $request->checkSource;

            if ($reqType != 'تساهيل') {
                $sendRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
                    $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 2) //open request
                    ->orWhere('statusReq', 4) //rejected from sales maanager
                    ->orWhere('statusReq', 31); //rejected from mortgage maanger

                })->update([
                    'statusReq'          => 3,
                    'isSentSalesManager' => 1,
                    'is_canceled'        => 0,
                    'type'               => $reqType,
                    'is_stared'          => 0,
                    'is_followed'        => 0,
                    'source'             => $sorce,
                    'collaborator_id'    => $request->checkColl,
                    'class_id_agent'     => 57,
                    'updated_at'         => now('Asia/Riyadh'),
                ]); //wating for sales manager approval

                $updateSalesManagerRequest = MyHelpers::salesManagerRequestProcess($request->id, auth()->user()->id);

                DB::table('req_records')->insert([
                    'colum'          => 'class_agent',
                    'user_id'        => null,
                    //'value'          => 'مرفوع',
                    'value'          => 57,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $request->id,
                    'user_switch_id' => null,
                    'comment'        => 'تلقائي - عن طريق النظام',
                ]);
            }
            else {
                $sendRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
                    $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 2) //open request
                    ->orWhere('statusReq', 4) //rejected from sales maanager
                    ->orWhere('statusReq', 31); //rejected from mortgage maanger

                })->update([
                    'statusReq'             => 30,
                    'isSentMortgageManager' => 1,
                    'is_canceled'           => 0,
                    'type'                  => $reqType,
                    'is_stared'             => 0,
                    'is_followed'           => 0,
                    'source'                => $sorce,
                    'collaborator_id'       => $request->checkColl,
                ]);
            } //wating for mortgage manager approval

            $this->records($request->id, 'reqSource', $sorce);
        }
        else {

            if ($reqType != 'تساهيل') {

                $sendRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
                    $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 2) //open request
                    ->orWhere('statusReq', 4) //rejected from sales maanager
                    ->orWhere('statusReq', 31); //rejected from mortgage maanger

                })->update([
                    'statusReq'          => 3,
                    'isSentSalesManager' => 1,
                    'is_canceled'        => 0,
                    'type'               => $reqType,
                    'is_stared'          => 0,
                    'is_followed'        => 0,
                    'class_id_agent'     => 57,
                    'updated_at'         => now('Asia/Riyadh'),
                ]); //wating for sales manager approval

                $updateSalesManagerRequest = MyHelpers::salesManagerRequestProcess($request->id, auth()->user()->id);

                DB::table('req_records')->insert([
                    'colum'          => 'class_agent',
                    'user_id'        => null,
                    //'value'          => 'مرفوع',
                    'value'          => 57,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $request->id,
                    'user_switch_id' => null,
                    'comment'        => 'تلقائي - عن طريق النظام',
                ]);
            }
            else {
                $sendRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
                    $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 2) //open request
                    ->orWhere('statusReq', 4) //rejected from sales maanager
                    ->orWhere('statusReq', 31); //rejected from mortgage maanger

                })->update([
                    'statusReq'             => 30,
                    'isSentMortgageManager' => 1,
                    'is_canceled'           => 0,
                    'type'                  => $reqType,
                    'is_stared'             => 0,
                    'is_followed'           => 0,
                    'updated_at'            => now('Asia/Riyadh'),
                ]);
            } //wating for mortgage manager approval

            $this->records($request->id, 'reqtyp', $reqType);
        }

        if ($sendRequest == 1) { //sent

            if ($request->comment == null) {
                $request->comment = "لايوجد";
            }

            if ($reqType != 'تساهيل') {
                $salesManager = MyHelpers::getSalesManagerRequest($request->id);
                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent'), ($salesManager), $request->comment);
                //
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => ($salesManager),
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);
            }
            else {
                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Sent'), (auth()->user()->mortgage_mnager_id), $request->comment);
                //
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => (auth()->user()->mortgage_mnager_id),
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);
            }
            //

            //for quality intent::::::::::::::::

            if (MyHelpers::checkQualityReq($request->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 3, $currRequest->user_id, $currRequest->class_id_agent);
            }

            //end quality :::::::::::::::::::::::::::::::::::::::

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = auth()->user()->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'sent_basket',$request->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $sendRequest, 'id' => $request->id]);
        }
        else // not send

        {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
        }
    }

    public function records($reqID, $coloum, $value, $comment = null)
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
                'comment'        => $comment,
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
                    'comment'        => $comment,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function checkSendFunding(Request $request)
    {

        Session::forget('missedFileds');
        //$missed_filed = MyHelpers::checkCompleteRequestFilds($request);
        //return response(['fn'=>$missed_filed]);

        $reqID = $request->reqID;
        $fundingReq = DB::table('requests')->where('id', $reqID)->first();

        $this->updateRequestWhenSending($request, $fundingReq);
        if ($request->reqcomm == null) {
            $request->merge([
                'reqcomm' => $fundingReq->comment,
            ]);
        }
        $missed_filed = MyHelpers::checkCompleteRequestFilds($request);
        $names = $this->nameOfMissedFields($missed_filed);

        if (count($missed_filed) > 0) {
            Session::put('missedFileds', $missed_filed);
        }

        return response()->json(['missed_filed' => $missed_filed, 'names' => $names]);
    }

    //This new function to show dataTabel in view(Agent.Request.myReqs)

    public function updateRequestWhenSending($request, $fundingReq)
    {

        $reqID = $fundingReq->id;

        //CUSTOMER
        $customerId = $fundingReq->customer_id;
        if ($request->name == null) {
            $request->name = 'بدون اسم';
        }

        $this->records($reqID, 'customerName', $request->name);
        $this->records($reqID, 'mobile', $request->mobile);
        $this->records($reqID, 'sex', $request->sex);
        $this->records($reqID, 'birth_date', $request->birth);
        $this->records($reqID, 'birth_hijri', $request->birth_hijri);
        $this->records($reqID, 'work', $request->work);
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
        if (($request->jointname != null) || ($request->jointmobile != null)) {
            $has_joint = 'yes';
        }
        else {
            $has_joint = 'no';
        }

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
            'has_joint'                => $has_joint,
        ]);
        //

        //FUNDING INFO
        $fundingId = $fundingReq->fun_id;

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

        //REAL ESTAT
        $realId = $fundingReq->real_id;
        $realname = $request->realname;
        $realmobile = $request->realmobile;
        $realcity = $request->realcity;
        $region = $request->realregion;
        $realpursuit = $request->realpursuit;
        $realstatus = $request->realstatus;
        $realage = $request->realage;
        $realcost = $request->realcost;
        $realhas = $request->realhasprop;
        $realtype = $request->realtype;
        $othervalue = $request->othervalue;
        $realeva = $request->realeva;
        $realten = $request->realten;
        $realmor = $request->realmor;
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
        if ($request->owning_property == 'no') {
            $this->records($reqID, 'owning_property', 'لا');
        }
        if ($request->owning_property == 'yes') {
            $this->records($reqID, 'owning_property', 'نعم');
        }

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
            'owning_property' => $owning_property,
        ]);

        //

        //REQUEST INFO
        $reqtype = $request->reqtyp;
        $reqclass = $request->reqclass;
        $reqcomm = $request->reqcomm;
        $webcomm = $request->webcomm;
        $update = Carbon::now('Asia/Riyadh');
        $this->records($reqID, 'reqtyp', $reqtype);
        $this->records($reqID, 'comment', $reqcomm);
        $this->records($reqID, 'commentWeb', $webcomm);

        $getclassValue = DB::table('classifcations')->where('id', $request->reqclass)->first();
        if (!empty($getclassValue)) {
            //$this->records($reqID, 'class_agent', $getclassValue->value);
            $this->records($reqID, 'class_agent', $getclassValue->id);
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

        if ($fundingReq->collaborator_id != null) {
            DB::table('requests')->where('id', $reqID)->update([
                'class_id_agent'             => $reqclass,
                'type'                       => $reqtype,
                'noteWebsite'                => $webcomm,
                'comment'                    => $reqcomm ?? $fundingReq->comment,
                'updated_at'                 => $update,
                'is_approved_by_tsaheel_acc' => 0,
                'is_approved_by_wsata_acc'   => 0,
                'agent_identity_number'   => $request->agent_identity_number,
            ]);
        }
        else {
            DB::table('requests')->where('id', $reqID)->update([
                'class_id_agent'             => $reqclass,
                'type'                       => $reqtype,
                'noteWebsite'                => $webcomm,
                'comment'                    => $reqcomm ?? $fundingReq->comment,
                'updated_at'                 => $update,
                'collaborator_id'            => $request->collaborator,
                'is_approved_by_tsaheel_acc' => 0,
                'is_approved_by_wsata_acc'   => 0,
                'agent_identity_number'   => $request->agent_identity_number,
            ]);
        }
        //

    }

    public function nameOfMissedFields($missed_filed)
    {

        $names = [
            "name"                     => "اسم العميل",
            "sex"                      => "جنس العميل",
            "mobile"                   => "رقم جوال العميل",
            "birth_hijri"              => "تاريخ ميلاد العميل - هجري",
            "work"                     => "جهة عمل العميل",
            "salary"                   => "راتب العميل",
            "salary_source"            => "جهة راتب العميل",
            "is_support"               => "الدعم للعميل",
            "has_financial_distress"   => "تعثرات العميل",
            "financial_distress_value" => "قيمة التعثرات",
            "has_obligations"          => "التزامات العميل",
            "obligations_value"        => "قيمة التزامات العميل",
            "realname"                 => "اسم المالك",
            "realmobile"               => "جوال المالك",
            "realcity"                 => "مدينة العقار",
            "realpursuit"              => "سعي التمويل",
            "realstatus"               => "حالة العقار",
            "realage"                  => "عمر العقار",
            "realtype"                 => "نوع العقار",
            "owning_property"          => "امتلاك العميل للعقار",
            "realmor"                  => "رهن العقار",
            "funding_source"           => "جهة التمويل",
            "fundingdur"               => "مدة التمويل",
            "fundingreal"              => "مبلغ التمويل العقاري",
            "fundingrealp"             => "نسبة التمويل العقاري",
            "dedp"                     => "نسبة الاستقطاع",
            "monthIn"                  => "القسط الشهري",
            "realo"                    => "القرض العقاري",
            "reqtyp"                   => "نوع الطلب",
            "reqclass"                 => "تصنيف المعاملة",
            "reqcomm"                  => "ملاحظة الطلب",
            "document"                 => "مرفقات الطلب",
            "real"                     => " القيمة الفعلية للعقار",
            // "morpre"                   => "نسبة الرهن",
            // "morcos"                   => "مبلغ الرهن",
            "propre"                   => "نسبة السعي",
            "agent_identity_number"    => " رقم الهوية",
        ];

        $names = array_flip($names);
        $result = array_intersect($names, $missed_filed);

        $result = array_flip($result);

        return $result;
    }

    public function reqArchive(Request $request, $id)
    {

        $reqInfo = DB::table('requests')->where('id', $request->id)->first();

        if ($reqInfo->class_id_agent != null) {
            if ($reqInfo->comment != null) {

                if (MyHelpers::checkClassType($reqInfo->class_id_agent)) {
                    $archRequest = DB::table('requests')->where('id', $request->id)->where(function ($query) {
                        $query->where('statusReq', 0) //new request
                        ->orWhere('statusReq', 1) //open request
                        ->orWhere('statusReq', 4) //rejected from sales maanger
                        ->orWhere('statusReq', 31); //rejected from mortgage maanger
                    })->update(['statusReq' => 2, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'add_to_archive' => Carbon::now('Asia/Riyadh'), 'add_to_stared' => null, 'add_to_followed' => null, 'updated_at' => now('Asia/Riyadh')]); //archive request in sales agent
                }
                else {
                    return redirect()->back()->with('message2', 'لاتستطيع أرشفة الطلب حتى يتم تصنيفه بشكل سلبي');
                }

                if ($archRequest == 0) // not updated
                {
                    return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
                }

                if ($archRequest == 1) { // updated sucessfully

                    //for quality intent::::::::::::::::

                    if ($reqInfo->isSentSalesManager != 1) {
                        if (MyHelpers::checkQualityReq($request->id)) {
                            $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 2, $reqInfo->user_id, $reqInfo->class_id_agent);
                        }
                    }

                    if ($reqInfo->isSentSalesManager == 1) {
                        if (MyHelpers::checkQualityReq($request->id)) {
                            $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 17, $reqInfo->user_id, $reqInfo->class_id_agent);
                        }
                    }

                    //end quality :::::::::::::::::::::::::::::::::::::::

                    return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
                }
            }
            else {
                return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->user()->id, 'The request comment is required'));
            }
        }
        else {
            return redirect()->back()->with('message4', MyHelpers::admin_trans(auth()->user()->id, 'The request class is required'));
        }
    }

    public function myReqs(Request $request)
    {

        Session::forget('missedFileds');
        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)
            //   ->where(function ($query) {
            //      $query->where('statusReq', 0) //new request
            //         ->orWhere('statusReq', 1); //open request
            // })
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        //AGENT ASSMENTS
        // dd(carbon::now()->submonths(3)->format('Y-m-d'));
        $user = User::where('id', auth()->user()->id)->first();
        $assments = $this->agentAssment($user);
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        //
        if (env('NEW_THEME') == '1') {
            $title = 'جميع الطلبات';
            return view('themes.theme1.Agent.Request.myReqs',
                compact('title', 'requests', 'assments', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));

        } else {
            return view('Agent.Request.myReqs',
                compact('requests', 'assments', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));

        }

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

    public function agentAssment($user)
    {
        ////////////////////////////////////////////////////////

        $data_update['q1'] = $this->servayQuestion1Result($user->id, carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $data_update['q2'] = $this->servayQuestion2Result($user->id, carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $data_update['q3'] = $this->servayQuestion3Result($user->id, carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $data_update['q4'] = $this->servayQuestion4Result($user->id, carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));

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

        $avgValue = $user->updateRequest(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));

        $getHourOnly = substr($avgValue, 0, strpos($avgValue, ':', 0));

        $movedReq = $user->movedRequestsTo(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));

        if ($movedReq != 0) {

            $diff = 0;
            if ($getHourOnly < $movedReq) {
                $diff = $getHourOnly / $movedReq;
            }
            else {
                $diff = $movedReq / $getHourOnly;
            }

            $data_update['updateReq_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);
        }
        else {
            $data_update['updateReq_present'] = '';
        }

        ////////////////////////////////////////////////////////

        $avreageTask = $this->averageTask($user->id, carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $completedTask = $user->completedTaskTo(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $getHourOnly = substr($avreageTask, 0, strpos($avreageTask, ':', 0));

        $noTask = $user->taskTo(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));

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

        if ($noTask != 0) {

            $diff = 0;

            $diff = $completedTask / $noTask;

            $data_update['completeTask_present'] = (number_format((float) $diff, 2, '.', '') * 100);
        }
        else {
            $data_update['completeTask_present'] = '';
        }

        ////////////////////////////////////////////////////////

        $movedFrom = $user->movedRequestsFrom(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));
        $movedTo = $user->movedRequestsTo(carbon::now()->submonths(3)->format('Y-m-d'), carbon::now()->format('Y-m-d'));

        $diff = 0;

        if ($movedFrom < $movedTo) {
            if ($movedTo != 0) {
                $diff = $movedFrom / $movedTo;
            }
            else {
                $diff = 0;
            }
        }

        else {
            if ($movedFrom != 0 && $movedTo != 0) {
                $diff = $movedTo / $movedFrom;
            }
            else {
                if ($movedFrom != 0 && $movedTo == 0) {
                    $diff = 1;
                }
                else {
                    $diff = 0;
                }
            }
        }

        $data_update['move_present'] = 100 - (number_format((float) $diff, 2, '.', '') * 100);

        ////////////////////////////////////////////////////////

        /*
           $diff = $movedTo / $this->allMovedRequestsTo(carbon::now()->submonths(3)->format('Y-m-d'),  carbon::now()->format('Y-m-d'));

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

        return $data_update;
    }

    function servayQuestion1Result($userID, $startDate, $endDate)
    {
        $result = '';

        $allReqs = DB::table('quality_reqs')->where('servays.user_id', $userID)->join('servays', 'servays.req_id', 'quality_reqs.id')->join('serv_ques', 'serv_ques.serv_id', 'servays.id')->where('ques_id', 1);

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

        $allReqs = DB::table('quality_reqs')->where('servays.user_id', $userID)->join('servays', 'servays.req_id', 'quality_reqs.id')->join('serv_ques', 'serv_ques.serv_id', 'servays.id')->where('ques_id', 2);

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

        $allReqs = DB::table('quality_reqs')->where('servays.user_id', $userID)->join('servays', 'servays.req_id', 'quality_reqs.id')->join('serv_ques', 'serv_ques.serv_id', 'servays.id')->where('ques_id', 3);

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

        $allReqs = DB::table('quality_reqs')->where('servays.user_id', $userID)->join('servays', 'servays.req_id', 'quality_reqs.id')->join('serv_ques', 'serv_ques.serv_id', 'servays.id')->where('ques_id', 4);

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

    function averageTask($userID, $startDate, $endDate)
    {
        $allReqs = DB::table('tasks')->where('tasks.recive_id', $userID)->join('task_contents', 'task_contents.task_id', 'tasks.id');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '<=', $endDate);
        }

        $allReqs = $allReqs->select(\DB::raw("(TIME_TO_SEC(TIMEDIFF(date_of_note, date_of_content))) AS day_diff"))->get()->avg('day_diff');

        //dd( $allReqs);

        $avg = gmdate("H:i:s", $allReqs);

        //dd($avg);
        //$avg=round($allReqs);

        return $avg;
    }

    public function myreqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->select('requests.*', 'customers.name as cust_name', 'customers.salary',
            'customers.salary_id', 'customers.mobile', 'customers.birth_date', 'customers.work', 'prepayments.payStatus');

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
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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
        return Datatables::of($requests)->addColumn('action', function ($row) {

            if(env('NEW_THEME') != '1')
            {

                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

                $data = $data.'</div>';
                return $data;
            }else{

                $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                  <path
                    id="menu"
                    d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                    transform="translate(-14 -39)"
                    fill="#6c757d"
                  ></path>
                </svg>
              </button>
              <ul class="dropdown-menu">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                }
                else {
                    $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                }
                $data = $data.'<li class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';

                $data = $data.'</ul>';
                return $data;
            }
        })->addColumn('card_class', function ($row) {
           $class = 'success';
           if(in_array($row->class_id_agent, [1, 9]))
           {
                $class = 'info';
           }elseif(in_array($row->class_id_agent, [13,14,16]))
           {
                $class = 'danger';
           }elseif(in_array($row->class_id_agent, [17,19]))
           {
                $class = 'info';
           }
           return $class;
        })->addColumn('action_grid', function ($row) {


            $data = [];
            if ($row->type == 'رهن-شراء') {
                $data[] = [
                    'title' => 'فتح الطلب',
                    'url' => route('agent.morPurRequest', $row->id),
                    'icon'  => 'fas fa-eye'
                ];
            }
            else {
                $data[] = [
                    'title' => 'فتح الطلب',
                    'url' => route('agent.fundingRequest', $row->id),
                    'icon'  => 'fas fa-eye'
                ];
            }
            $data[] = [
                    'title' => 'التذاكر',
                    'url' => route('all.taskReq', $row->id),
                    'icon'  => 'fas fa-comments'
                ];
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {

                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
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
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function customersFn($getBy = 'empty')
    {
        $s = customer::where('user_id', auth()->user()->id)->pluck("name", "id");

        $customers = customer::where('user_id', auth()->user()->id)->pluck("name", "id");
        $customersCount = $customers->count();

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[$customersCount]);
    }

    /*
    public function canceledReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)
            ->where(function ($query) {
                $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 4); //rejected request
            })
            ->where('is_canceled', 1)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('id', 'DESC')
            ->get();

        $notifys = $this->fetchNotify();

        //dd( $notifys);

        return view('Agent.Request.canceledReqs', compact('requests', 'notifys'));
    }


    public function canceledReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)
            ->whereIn('statusReq', [0, 1, 4]) // (0:new , 1:open , 4:rejected )
            ->where('is_canceled', 1)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('id', 'DESC')
            ->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->user()->id, 'Open') . '">
                    <a href="' . route('agent.morPurRequest', $row->id) . '"><i class="fas fa-eye"></i></a></span>';
            } else {
                $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->user()->id, 'Open') . '">
                    <a href="' . route('agent.fundingRequest', $row->id) . '"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data . '<span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->user()->id, 'Restore') . '">
                                <a href="' . route('agent.restoreRequest', $row->id) . '"> <i class="fas fa-time-restore-setting"></i></a>
                          </span>';
            $data = $data . '</div>';
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

    */

    public function recivedReqs(Request $request)
    {
        Session::forget('missedFileds');
        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name',
            'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        //dd( $notifys);
        if(env('NEW_THEME') == '1')
        {
            $title = 'الطلبات المستلمة';
            return view('themes.theme1.Agent.Request.recivedReqs',
                compact('title', 'requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));

        }else{
            return view('Agent.Request.recivedReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));

        }
    }

    public function recivedReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)
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
            ->select('requests.*', 'customers.name as cust_name', 'customers.id as cust_id', 'prepayments.payStatus');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', $request->get('req_date_to'));
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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
        /* if ($request->has('search')) {
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

        $checkRecive = $this->checkReciveReqOpenAndWithoutCommentAndClass(auth()->user()->id);
        $countCheckRecive = count($checkRecive);

        return Datatables::of($requests)->addColumn('action', function ($row) use ($checkRecive, $countCheckRecive) {
            if(env('NEW_THEME') != '1')
            {

                $data = '<div class="tableAdminOption">';
                if (($countCheckRecive > 0) && (!in_array($row->id, $checkRecive) && $row->statusReq == 0)) {
                    $data = $data.'<span class="item pointer" id="openReq" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'You have open request without comment and class').'">
                    <i class="fas fa-eye"></i></span>';
                }
                else {
                    if ($row->type == 'رهن-شراء') {
                        $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                    }
                    else {
                        $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                    }
                }

                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

                $data = $data.'</div>';
            }else{

                $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                  <path
                    id="menu"
                    d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                    transform="translate(-14 -39)"
                    fill="#6c757d"
                  ></path>
                </svg>
              </button>
              <ul class="dropdown-menu">';
              if (($countCheckRecive > 0) && (!in_array($row->id, $checkRecive) && $row->statusReq == 0)) {

                    $data = $data.'<li class="item pointer" id="openReq" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'You have open request without comment and class').'">
                    <a class="dropdown-item" href="#"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'You have open request without comment and class').'</span></a></li>';
                }
                else {
                    if ($row->type == 'رهن-شراء') {
                        $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                    }
                    else {
                        $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                    }
                }
                $data = $data.'<li class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';

                $data = $data.'</ul>';
                return $data;
            }
            return $data;

        })->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                 $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                 $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                 $class = 'info';
            }
            return $class;
         })->addColumn('action_grid', function ($row) {


             $data = [];
             if ($row->type == 'رهن-شراء') {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.morPurRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             else {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.fundingRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             $data[] = [
                     'title' => 'التذاكر',
                     'url' => route('all.taskReq', $row->id),
                     'icon'  => 'fas fa-comments'
                 ];
             return $data;
         })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
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
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function followReqs(Request $request)
    {

        Session::forget('missedFileds');
        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('is_canceled', 0)->where('is_followed', 1)->where('is_stared', 0)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name',
            'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        if(env('NEW_THEME') == '1'){
            return view('themes.theme1.Agent.Request.followReqs',
                 compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }else{
            return view('Agent.Request.followReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }

    }

    public function followReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('is_canceled', 0)->where('is_followed', 1)->where('is_stared', 0)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'customers.id as cust_id',
            'prepayments.payStatus')->orderBy('req_date', 'DESC');

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
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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
        return Datatables::of($requests)->addColumn('action', function ($row) {
            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                }

                $data = $data.'<span class="item pointer" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                        <a href="'.route('agent.restoreRequest', $row->id).'"> <i class="fas fa-reply-all"></i></a></span>';



                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                              <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

                $data = $data.'</div>';
            }else{
                $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                  <path
                    id="menu"
                    d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                    transform="translate(-14 -39)"
                    fill="#6c757d"
                  ></path>
                </svg>
              </button>
              <ul class="dropdown-menu">';

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                }
                else {
                    $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
                }


                $data = $data.'<li class="item pointer"  data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                <a class="dropdown-item" href="'.route('agent.restoreRequest', $row->id).'"><i class="fas fa-reply-all"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'</span></a></li>';

                $data = $data.'<li class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';


                $data = $data.'</ul>';

            }
            return $data;
        })->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                 $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                 $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                 $class = 'info';
            }
            return $class;
         })->addColumn('action_grid', function ($row) {


             $data = [];
             if ($row->type == 'رهن-شراء') {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.morPurRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             else {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.fundingRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             $data[] = [
                     'title' => 'استرجاع',
                     'url' => route('agent.restoreRequest', $row->id),
                     'icon'  => 'fas fa-reply-all'
                 ];

            $data[] = [
                    'title' => 'التذاكر',
                    'url' => route('all.taskReq', $row->id),
                    'icon'  => 'fas fa-comments'
                ];
             return $data;
         })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
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
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function starReqs(Request $request)
    {

        Session::forget('missedFileds');
        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name',
            'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Stared Requests');

            return view('themes.theme1.Agent.Request.staredReqs',
                compact('title','requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }else{
            return view('Agent.Request.staredReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }
    }

    public function starReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'customers.id as cust_id',
            'prepayments.payStatus')->orderBy('req_date', 'DESC');

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
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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
        /* if ($request->has('search')) {
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

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'<span type="item pointer" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                                <a href="'.route('agent.restoreRequest', $row->id).'"> <i class="fas fa-reply-all"></i></a>
                          </span>';
            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                          <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
            }else{}
            // $data = '<div class="tableAdminOption">';
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                <path
                    id="menu"
                    d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                    transform="translate(-14 -39)"
                    fill="#6c757d"
                ></path>
                </svg>
                </button>
                <ul class="dropdown-menu">';
            if ($row->type == 'رهن-شراء') {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';

            }
            else {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';

            }
            // $data = $data.'<span type="item pointer" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
            //                     <a href="'.route('agent.restoreRequest', $row->id).'"> <i class="fas fa-reply-all"></i></a>
            //               </span>';
            $data = $data.'<li class="item pointer"  data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                <a class="dropdown-item" href="'.route('agent.restoreRequest', $row->id).'"><i class="fas fa-reply-all"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'</span></a></li>';

            // $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            //               <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'<li class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';


            // $data = $data.'</div>';
            $data = $data.'</ul>';

            return $data;
        }) ->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                 $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                 $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                 $class = 'info';
            }
            return $class;
         })->addColumn('action_grid', function ($row) {
             $data = [];
             if ($row->type == 'رهن-شراء') {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.morPurRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             else {
                 $data[] = [
                     'title' => 'فتح الطلب',
                     'url' => route('agent.fundingRequest', $row->id),
                     'icon'  => 'fas fa-eye'
                 ];
             }
             $data[] = [
                     'title' => 'استرجاع',
                     'url' => route('agent.restoreRequest', $row->id),
                     'icon'  => 'fas fa-reply-all'
                 ];

            $data[] = [
                    'title' => 'التذاكر',
                    'url' => route('all.taskReq', $row->id),
                    'icon'  => 'fas fa-comments'
                ];
             return $data;
         })
        ->editColumn('created_at', function ($row) {
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
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
            }
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function additionalReqs()
    {
        $pending_requests = PendingRequest::all();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Additional Requests');
            return view('themes.theme1.Agent.Request.pendingRequests', compact('title','pending_requests', 'worke_sources', 'request_sources'));
        }else{
            return view('Agent.Request.pendingRequests', compact('pending_requests', 'worke_sources', 'request_sources'));
        }
    }

    /*  public function sendReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)
            ->where('statusReq', 3) //wating for sales manager approval
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->get();

        $notifys = $this->fetchNotify(); //get notificationes
        return view('Agent.Request.sendReqs', compact('requests', 'notifys'));
    }
    */

    public function additionalReqs_datatable(Request $request)
    {

        $requests = DB::table('pending_requests')->join('customers', 'customers.id', '=', 'pending_requests.customer_id')->join('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')->select('customers.*', 'real_estats.has_property', 'real_estats.owning_property',
            'pending_requests.created_at', 'pending_requests.req_date','pending_requests.id as pending_id','pending_requests.statusReq as pending_request_status');

        if ($request->get('customer_salary_from')) {
            $requests = $requests->where('customers.salary', '>=', $request->get('customer_salary_from'));
        }

        if ($request->get('customer_salary_to')) {
            $requests = $requests->where('customers.salary', '<=', $request->get('customer_salary_to'));
        }

        if ($request->get('work_source')) {
            $requests = $requests->whereIn('customers.work', $request->get('work_source'));
        }

        if ($request->get('is_supported')) {
            $requests = $requests->where('customers.is_supported', $request->get('is_supported'));
        }
        if ($request->get('has_property')) {
            $requests = $requests->where('has_property', $request->get('has_property'));
        }
        if ($request->get('has_joint')) {
            $requests = $requests->where('customers.has_joint', $request->get('has_joint'));
        }
        if ($request->get('has_obligations')) {
            $requests = $requests->where('customers.has_obligations', $request->get('has_obligations'));
        }
        if ($request->get('has_financial_distress')) {
            $requests = $requests->where('customers.has_financial_distress', $request->get('has_financial_distress'));
        }
        if ($request->get('has_owning_property')) {
            $requests = $requests->where('owning_property', $request->get('has_owning_property'));
        }

        return DataTables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('action', function ($row) {
            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';
                $data = $data.'<span class="item moveButtons pointer" id="move" data-id="'.$row->pending_id.'" data-toggle="tooltip" data-placement="top" title="سحب الطلب">
                                        <i class="fas fa-random"></i>
                                    </span> ';

                $data = $data.'</div>';
                return $data;
            }else{}
            // $data = '<div class="tableAdminOption">';
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
            <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#6c757d"
            ></path>
            </svg>
            </button>
            <ul class="dropdown-menu">';

            // $data = $data.'<span class="item moveButtons pointer" id="move" data-id="'.$row->pending_id.'" data-toggle="tooltip" data-placement="top" title="سحب الطلب">
            //                         <i class="fas fa-random"></i>
            //                     </span> ';

            $data = $data.'<li class="item moveButtons pointer" id="move" data-id="'.$row->pending_id.'" data-toggle="tooltip" data-placement="top" title="سحب الطلب">
            <a class="dropdown-item"><i class="fas fa-random"></i><span class="font-medium">'.'سحب الطلب'.'</span></a></li>';

            // $data = $data.'</div>';

            $data = $data.'</ul>';
            return $data;
        })
        ->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->pending_request_status, [1, 9]))
            {
                    $class = 'info';
            }elseif(in_array($row->pending_request_status, [13,14,16]))
            {
                    $class = 'danger';
            }elseif(in_array($row->pending_request_status, [17,19]))
            {
                    $class = 'info';
            }
            return $class;
        })->addColumn('action_grid', function ($row) {
                $data = [];
                $data[] = [
                        'title' => 'سحب الطلب',
                        'url' => '#',
                        'icon'  => 'fas fa-random'
                    ];

                return $data;
        })->addColumn('is_supported', function ($row) {
            if ($row->is_supported == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->is_supported == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->is_supported;
                }
            }
            return $data;
        })->addColumn('has_property', function ($row) {
            if ($row->has_property == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->has_property == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->has_property;
                }
            }
            return $data;
        })->addColumn('has_joint', function ($row) {
            if ($row->has_joint == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->has_joint == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->has_joint;
                }
            }
            return $data;
        })->addColumn('has_obligations', function ($row) {
            if ($row->has_obligations == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->has_obligations == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->has_obligations;
                }
            }
            return $data;
        })->addColumn('has_financial_distress', function ($row) {
            if ($row->has_financial_distress == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->has_financial_distress == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->has_financial_distress;
                }
            }
            return $data;
        })->addColumn('owning_property', function ($row) {
            if ($row->owning_property == 'yes') {
                $data = 'نعم';
            }
            else {
                if ($row->owning_property == 'no') {
                    $data = 'لا';
                }
                else {
                    $data = $row->owning_property;
                }
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->make(true);
    }

    public function moveAdditionalReqs(Request $request)
    {
        if (MyHelpers::checkRecivedReqsCount() != 0) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'The recived basket not empty'), 'status' => 4]);
        }
        $isActive = DB::table('settings')->where('option_name', 'movePendingByAgent_active')->first();
        if ($isActive->option_value == 'false') {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'ask request not allowed now'), 'status' => 3]);
        }
        if (!MyHelpers::checkAgentRecive()) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'ask request not allowed now'), 'status' => 3]);
        }
        if (!MyHelpers::checkNoofMovePendingRequest()) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you exceed allowed number'), 'status' => 3]);
        }

        $content = MyHelpers::guest_trans('PendingRequestsBySalesAgent');
        $agent_id = auth()->user()->id;
        $move_pending = MyHelpers::movePendingRequestByAgent($request->pending_id, $agent_id, $content);

        if ($move_pending == -1) {
            return response()->json(['status' => $move_pending, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'The request is not available')]);
        }
        else {
            if ($move_pending == false) {
                return response()->json(['status' => $move_pending, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'unexpected_error')]);
            }
            else {
                MyHelpers::addNewMoveRequestRecord(1);
            }
        }

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = auth()->user()->id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request->pending_id,'pendings');
        // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->pending_id,'pendings');
        //***********END - UPDATE DAILY PREFROMENCE */

        return response()->json(['status' => $move_pending, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]);
    }

    public function completedReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        //dd( $notifys);
        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') ;
            return view('themes.theme1.Agent.Request.completedReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));

        }else{
            return view('Agent.Request.completedReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }

    }

    public function completedReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
                $query->where('statusReq', '!=', 19);
                $query->where('requests.type', 'رهن-شراء');
                $query->where('requests.isSentSalesAgent', 1);
            });

            $query->orWhere(function ($query) {
                $query->whereNotIn('prepayments.payStatus', [4, 3]);
                $query->whereIn('statusReq', [6, 13]);
                $query->where('prepayments.isSentSalesAgent', 1);
                $query->where('requests.type', 'شراء-دفعة');
            });
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'customers.id as cust_id', 'prepayments.payStatus')->orderBy('req_date', 'DESC');

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
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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
        return Datatables::of($requests)->addColumn('action', function ($row) {

            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

                $data = $data.'</div>';
                return $data;
            }else{}
            // $data = '<div class="tableAdminOption">';
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
            <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#6c757d"
            ></path>
            </svg>
            </button>
            <ul class="dropdown-menu">';





            if ($row->type == 'رهن-شراء') {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                    $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
            }
            else {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
            }
            // $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            // <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';

            // $data = $data.'</div>';
            $data = $data.'</ul>';
            return $data;
        })->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                    $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                    $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                    $class = 'info';
            }
            return $class;
        })
        ->addColumn('action_grid', function ($row) {
                $data = [];
                if ($row->type == 'رهن-شراء') {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.morPurRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
                else {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.fundingRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
                $data[] = [
                    'title' => 'التذاكر',
                    'url' => route('all.taskReq', $row->id),
                    'icon'  => 'fas fa-comments'
                ];
                return $data;
        })
        ->editColumn('created_at', function ($row) {
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
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function manageReq($id, $action)
    {

        $userID = (auth()->user()->id);

        $reqInfo = DB::table('requests')->where('id', $id)->first();

        $checkFollow = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->first();

        //   dd($reqInfo );

        if ($reqInfo->class_id_agent != null) {
            if ($reqInfo->comment != null) {
                if (!empty($checkFollow)) {

                    if ($reqInfo->statusReq == 2) {

                        //***********UPDATE DAILY PREFROMENCE */
                        $agent_id = $userID;
                        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                        }
                        MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'archived_basket');
                        //***********END - UPDATE DAILY PREFROMENCE */

                        $restRequest = DB::table('requests')->where('id', $id)->where('user_id', $userID)->update(['is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'statusReq' => 1, 'remove_from_archive' => Carbon::now('Asia/Riyadh'), 'updated_at' => now('Asia/Riyadh')]); //set request
                    }
                    else {

                        if ($reqInfo->is_stared == 1) {
                            //***********UPDATE DAILY PREFROMENCE */
                            $agent_id = $userID;
                            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                            }
                            MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'star_basket');
                            //***********END - UPDATE DAILY PREFROMENCE */

                        }
                        else {
                            if ($reqInfo->is_followed == 1) {
                                //***********UPDATE DAILY PREFROMENCE */
                                $agent_id = $userID;
                                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                                }
                                MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'followed_basket');
                                //***********END - UPDATE DAILY PREFROMENCE */

                            }
                        }
                        $restRequest = DB::table('requests')->where('id', $id)->where('user_id', $userID)->update([
                            'is_canceled'     => 0,
                            'is_stared'       => 0,
                            'is_followed'     => 0,
                            'add_to_archive'  => null,
                            'add_to_stared'   => null,
                            'add_to_followed' => null,
                            'updated_at'      => now('Asia/Riyadh'),
                        ]);
                    }

                    if (!MyHelpers::checkClassType($reqInfo->class_id_agent)) {

                        $restRequest = DB::table('requests')->where('id', $id)->where('user_id', $userID)->update(['is_'.$action => 1, 'add_to_'.$action => Carbon::now('Asia/Riyadh'), 'updated_at' => now('Asia/Riyadh')]);
                        if ($action == 'stared') {
                            //***********UPDATE DAILY PREFROMENCE */
                            $agent_id = $userID;
                            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                            }
                            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'star_basket',$id);
                            //***********END - UPDATE DAILY PREFROMENCE */

                        }
                        else {
                            if ($action == 'followed') {
                                //***********UPDATE DAILY PREFROMENCE */
                                $agent_id = $userID;
                                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                                }
                                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'followed_basket',$id);
                                //***********END - UPDATE DAILY PREFROMENCE */

                            }
                        }
                    }
                    else {
                        return redirect()->back()->with('message2', 'لايُسمح بنقل الطلب لأن تصنيفه سلبي');
                    }

                    if ($restRequest == 1) {

                        return redirect()->route('agent.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
                    }
                    else {
                        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
                    }
                }
                else {
                    return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->user()->id, 'The request reminder is required'));
                }
            }
            else {
                return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->user()->id, 'The request comment is required'));
            }
        }
        else {
            return redirect()->back()->with('message4', MyHelpers::admin_trans(auth()->user()->id, 'The request class is required'));
        }
    }

    public function archReqs(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where('statusReq', 2) //archived in sales agent
        ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date', 'DESC')->count();

        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $coll_users = DB::table('customers') // to get all customers that related to me and my collobretor
        ->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.user_id', auth()->user()->id)->pluck('customers.id');

        $customers = customer::whereIn('id', $coll_users)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Archived Requests');
            return view('themes.theme1.Agent.Request.archReqs',
                compact('title','requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }else{
            return view('Agent.Request.archReqs',
                compact('requests', 'notifys', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'worke_sources', 'request_sources'));
        }

    }

    public function archReqs_datatable(Request $request)
    {

        $userID = (auth()->user()->id);
        $requests = DB::table('requests')->where('requests.user_id', $userID)->where('statusReq', 2) //archived in sales agent
        ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'customers.id as cust_id')->orderBy('req_date', 'DESC');

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
            $requests = $requests->join('fundings', 'fundings.id', 'requests.fun_id')->whereIn('fundings.funding_source', $request->get('founding_sources'));
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

        return Datatables::of($requests)->addColumn('action', function ($row) {

            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            $data = $data.'<span class="item pointer" id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                    <a href="'.route('agent.restoreRequest', $row->id).'"><i class=" archReqs_datatable fas fa-reply-all"></i></a></span>';
            $data = $data.'<span  class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                    <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'</div>';
            return $data;
            }else{}

            // $data = '<div class="tableAdminOption">';
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
            <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#6c757d"
            ></path>
            </svg>
            </button>
            <ul class="dropdown-menu">';

            if ($row->type == 'رهن-شراء') {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                // <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';


            }
            else {
                // $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                // <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';

            }
            // $data = $data.'<span class="item pointer" id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
            //         <a href="'.route('agent.restoreRequest', $row->id).'"><i class=" archReqs_datatable fas fa-reply-all"></i></a></span>';

            $data = $data.'<li class="item pointer" id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
            <a class="dropdown-item" href="'.route('agent.restoreRequest', $row->id).'"><i class=" archReqs_datatable fas fa-reply-all"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'</span></a></li>';

            // $data = $data.'<span  class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            //         <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i></a></span>';

            $data = $data.'<li class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                    <a class="dropdown-item" href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comments"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'</span></a></li>';


            // $data = $data.'</div>';
            $data = $data.'</ul>';
            return $data;
        })
        ->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                    $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                    $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                    $class = 'info';
            }
            return $class;
        })
        ->addColumn('action_grid', function ($row) {
                $data = [];
                if ($row->type == 'رهن-شراء') {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.morPurRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
                else {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.fundingRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
                $data[] = [
                        'title' => 'استرجاع',
                        'url' => route('agent.restoreRequest', $row->id),
                        'icon'  => 'fas fa-reply-all'
                    ];

                $data[] = [
                    'title' => 'التذاكر',
                    'url' => route('all.taskReq', $row->id),
                    'icon'  => 'fas fa-comments'
                ];
                return $data;
        })
        ->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
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
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
        })->make(true);
    }

    public function morPurReqs(Request $request)
    {

        $userID = (auth()->user()->id);

        $requests[] = DB::table('requests')->where('requests.user_id', $userID)->where('type', 'رهن-شراء')
            //  ->where('statusReq', 19) //WATING FOR SALES AGENT
            ->where('isSentSalesAgent', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('requests.req_date', 'DESC')->get();

        $notifys = $this->fetchNotify(); //get notificationes

        if (!empty($requests[0])) {
            $check = 0; // check if this user is belong for at lest one user (sales agent)
            if(env('NEW_THEME') == '1'){
                $title = MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests');
                return view('themes.theme1.Agent.Request.morPurReqs', compact('requests', 'check', 'notifys'));
            }else{
                return view('Agent.Request.morPurReqs', compact('requests', 'check', 'notifys'));
            }
        }

        $check = 1; // sales manager not belong with any user (sales agent)

        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests');
            return view('themes.theme1.Agent.Request.morPurReqs', compact('requests', 'check', 'notifys'));
        }else{
            return view('Agent.Request.morPurReqs', compact('requests', 'check', 'notifys'));
        }
    }

    public function morPurReqs_data_Table(Request $request)
    {

        $userID = (auth()->user()->id);

        $requests= DB::table('requests')->where('requests.user_id', $userID)->where('type', 'رهن-شراء')
            //  ->where('statusReq', 19) //WATING FOR SALES AGENT
            ->where('isSentSalesAgent', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name as cust_name')->orderBy('requests.req_date', 'DESC');

        $notifys = $this->fetchNotify(); //get notificationes

        return Datatables::of($requests)->addColumn('action', function ($row) {
            if(env('NEW_THEME') != '1')
            {
                $data = '<div class="tableAdminOption">';

                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'</div>';
                return $data;
            }else{}
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
              <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#6c757d"
              ></path>
            </svg>
          </button>
          <ul class="dropdown-menu">';

            $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';

            $data = $data.'</ul>';
            return $data;
        })->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                 $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                 $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                 $class = 'info';
            }
            return $class;
         })->addColumn('action_grid', function ($row) {
            $data = [];
            $data[] = [
                'title' => 'فتح الطلب',
                'url' => route('agent.morPurRequest', $row->id),
                'icon'  => 'fas fa-eye'
            ];
             return $data;
         })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })/*->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })*/->editColumn('class_id_agent', function ($row) {

            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

                if ($classifcations_sa != null) {
                    return $classifcations_sa->value;
                }
                else {
                    return $row->class_id_agent;
                }
            }
            else {
                return '';
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

                if ($collInfo->name != null) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('comment', function ($row) {
            //SHOW COMMENTS OR NOT (NEGAIVE Class)
            $get_agent_and_status_of_show = [];
            $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
            $hide_negative_comment = $get_agent_and_status_of_show[1];
            ////////////////////////////////////////////////////////

            if (!$hide_negative_comment) {
                return $row->comment;
            }

            return null;
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

    public function morPurpage($id)
    {

        $userID = auth()->user()->id;

        $morPur = DB::table('requests')->where('id', '=', $id)->where('user_id', $userID)->first();

        // dd(  $morPur);

        if (!empty($morPur)) {

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            $reqStatus = $morPur->statusReq;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

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

            $districts = District::all();

            $prefix = 'agent';
            $rejections = RejectionsReason::all();
            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = auth()->user()->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'opened_request',$id);
            //***********END - UPDATE DAILY PREFROMENCE */

            return view('Agent.morPurReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id', //Request ID
                'histories', 'documents', 'reqStatus', 'purchaseTsa', 'morPur', 'followdate', 'notifys', 'collaborator', 'cities', 'ranks', 'followtime', 'realTypes', 'rejections', 'worke_sources', 'request_sources'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function restReq($id)
    {

        $userID = (auth()->user()->id);
        $fundingReq = DB::table('requests')->where('id', $id)->first();

        if (!MyHelpers::checkClassType($fundingReq->class_id_agent)) {
            $restRequest = DB::table('requests')->where('id', $id)->where('user_id', $userID)->where(function ($query) {
                $query->where('statusReq', 2); //archive request in sales agent
                // ->orWhere('is_canceled', 1)
            })->update(['statusReq' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'remove_from_archive' => Carbon::now('Asia/Riyadh'), 'updated_at' => now('Asia/Riyadh')]); //open request
        }
        else {
            return redirect()->back()->with('message2', 'لابد من تغيير التصنيف إلى إيجابي حتى يتم استرجاعه');
        }

        $restRequest2 = DB::table('requests')->where('id', $id)->where('user_id', $userID)->where(function ($query) {
            $query->where('is_stared', 1);
        })->update(['statusReq' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'updated_at' => now('Asia/Riyadh')]); //open request

        $restRequest3 = DB::table('requests')->where('id', $id)->where('user_id', $userID)->where(function ($query) {
            $query->where('is_followed', 1);
        })->update(['statusReq' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'updated_at' => now('Asia/Riyadh')]); //open request

        if ($restRequest == 1) {

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $userID;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'archived_basket');
            //***********END - UPDATE DAILY PREFROMENCE */

            return redirect()->route('agent.archRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
        }
        else {
            if ($restRequest2 == 1) {
                //***********UPDATE DAILY PREFROMENCE */
                $agent_id = $userID;
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'star_basket');
                //***********END - UPDATE DAILY PREFROMENCE */
                return redirect()->route('agent.staredRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
            }
            else {
                if ($restRequest3 == 1) {
                    //***********UPDATE DAILY PREFROMENCE */
                    $agent_id = $userID;
                    if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                        $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                    }
                    MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'followed_basket');
                    //***********END - UPDATE DAILY PREFROMENCE */
                    return redirect()->route('agent.followedRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
                }
                else {
                    return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
                }
            }
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

        $request = DB::table('requests')->where('id', '=', $document->req_id)->first();

        if (($request->user_id == auth()->user()->id) || (auth()->user()->id == $userID)) {

            try {
                $filename = $document->location;
                return response()->file(storage_path('app/public/'.$filename));
            }
            catch (\Exception $e) {
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
        }
        catch (\Exception $e) {
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

        }
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

    public function prepaymentReqs()
    {

        $userID = (auth()->user()->id);

        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

            $query->where(function ($query) {
                $query->where('prepayments.isSentSalesAgent', 1);
                $query->where('requests.type', 'شراء-دفعة');
            });
        })->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        //dd( $requests);
        $notifys = $this->fetchNotify(); //get notificationes

        if(env('NEW_THEME') == '1'){
            $title = MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil Requests');
            return view('themes.theme1.Agent.Request.prepayment', compact('title','requests', 'notifys'));
        }else{
            return view('Agent.Request.prepayment', compact('requests', 'notifys'));
        }
    }

    public function prepaymentReqs_datatable()
    {

        $userID = (auth()->user()->id);

        $requests = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

            $query->where(function ($query) {
                $query->where('prepayments.isSentSalesAgent', 1);
                $query->where('requests.type', 'شراء-دفعة');
            });
        })->join('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {

            if(env('NEW_THEME') != '1')
            {
                if ($row->type == 'رهن-شراء') {
                    $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';
                }
                else {
                    $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';
                }
                return $data;
            }else{}
            $data = '<button class="btn btn-light" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
            <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#6c757d"
            ></path>
            </svg>
            </button>
            <ul class="dropdown-menu">';

            if ($row->type == 'رهن-شراء') {
                // $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.morPurRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
            }
            else {
                // $data = '<div class="tableAdminOption"><span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                //     <a href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span></div>';

                $data = $data.'<li class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a class="dropdown-item" href="'.route('agent.fundingRequest', $row->id).'"><i class="fas fa-eye"></i><span class="font-medium">'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'</span></a></li>';
            }
            $data = $data.'</ul>';
            return $data;
        })->addColumn('card_class', function ($row) {
            $class = 'success';
            if(in_array($row->class_id_agent, [1, 9]))
            {
                    $class = 'info';
            }elseif(in_array($row->class_id_agent, [13,14,16]))
            {
                    $class = 'danger';
            }elseif(in_array($row->class_id_agent, [17,19]))
            {
                    $class = 'info';
            }
            return $class;
        })->addColumn('action_grid', function ($row) {
                $data = [];
                if ($row->type == 'رهن-شراء') {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.morPurRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
                else {
                    $data[] = [
                        'title' => 'فتح الطلب',
                        'url' => route('agent.fundingRequest', $row->id),
                        'icon'  => 'fas fa-eye'
                    ];
                }
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

        $userID = (auth()->user()->id);
        $request = DB::table('requests')->where('requests.id', '=', $id)->where('user_id', $userID)->first();

        //dd( $request);
        $payment = DB::table('prepayments')->where('req_id', '=', $id)->where('isSentSalesAgent', 1)->first();

        $notifys = $this->fetchNotify(); //get notificationes

        // dd($payment);
        if (!empty($request)) {
            if (!empty($payment)) {

                $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

                return view('Agent.prepayement.updatePage', compact('notifys', 'id', 'request', 'payment', 'purchaseReal'));
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
        $realDisposition = $request->Real_estate_disposition_value_tsaheel;
        $purchaseTaxTsaheel = $request->purchase_tax_value_tsaheel;
        //

        //

        $this->records($reqID, 'realCost', $request->real);
        $this->records($reqID, 'incValue', $request->incr);
        $this->records($reqID, 'preValue', $request->preval);
        $this->records($reqID, 'prePresent', $request->prepre);
        $this->records($reqID, 'preCost', $request->precos);
        $this->records($reqID, 'netCust', $request->net);
        $this->records($reqID, 'deficitCust', $request->deficit);

        if ($realDisposition != 0) {
            $this->records($reqID, 'realDisposition', $realDisposition);
        }
        if ($purchaseTaxTsaheel != 0) {
            $this->records($reqID, 'purchaseTax', $purchaseTaxTsaheel);
        }

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

        if ($request1->type == 'رهن-شراء') {

            $request1 = DB::table('requests')->where('requests.req_id', '=', $request->reqID)->first(); // to get mor-ppur info

            $payupdate = DB::table('prepayments')->where('req_id', $request1->req_id)->update([
                'realCost'                      => $real,
                'incValue'                      => $incr,
                'prepaymentVal'                 => $preval,
                'prepaymentPre'                 => $prepre,
                'prepaymentCos'                 => $precos,
                'visa'                          => $visa,
                'carLo'                         => $carlo,
                'personalLo'                    => $perlo,
                'realLo'                        => $realo,
                'credit'                        => $credban,
                'netCustomer'                   => $net,
                'other'                         => $other,
                'debt'                          => $debt,
                'mortPre'                       => $morpre,
                'mortCost'                      => $morcos,
                'proftPre'                      => $propre,
                'deficitCustomer'               => $deficit,
                'profCost'                      => $procos,
                'addedVal'                      => $valadd,
                'adminFee'                      => $admfe,
                'req_id'                        => $reqID,
                'Real_estate_disposition_value' => $realDisposition,
                'purchase_tax_value'            => $purchaseTaxTsaheel,
            ]);
        }
        else {
            $payupdate = DB::table('prepayments')->where('req_id', $reqID)->update([
                'realCost'                      => $real,
                'incValue'                      => $incr,
                'prepaymentVal'                 => $preval,
                'prepaymentPre'                 => $prepre,
                'prepaymentCos'                 => $precos,
                'visa'                          => $visa,
                'carLo'                         => $carlo,
                'personalLo'                    => $perlo,
                'realLo'                        => $realo,
                'credit'                        => $credban,
                'netCustomer'                   => $net,
                'other'                         => $other,
                'debt'                          => $debt,
                'mortPre'                       => $morpre,
                'mortCost'                      => $morcos,
                'proftPre'                      => $propre,
                'deficitCustomer'               => $deficit,
                'profCost'                      => $procos,
                'addedVal'                      => $valadd,
                'adminFee'                      => $admfe,
                'req_id'                        => $reqID,
                'Real_estate_disposition_value' => $realDisposition,
                'purchase_tax_value'            => $purchaseTaxTsaheel,
            ]);
        }

        //

        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $payupdate, 'id' => $reqID]);
    }

    public function sendPre(Request $request)
    {

        $userID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->where('user_id', $userID)->first();

        if (!empty($restRequest)) {

            $sendPay = DB::table('prepayments')->where('req_id', $request->id)->whereIn('payStatus', [4, 3]) // wating sales agent, rejected from sales maanger
            ->where('isSentSalesAgent', 1)->update(['payStatus' => 1]); //wating for sales manager approval

            if ($sendPay == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendPay, 'id' => $request->id]);
            }

            else {

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), (auth()->user()->manager_id), $request->comment);

                DB::table('notifications')->insert([ // add notification to send general manager user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => (auth()->user()->manager_id),
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

    public function sendMorPur(Request $request)
    {

        //  return response($request);
        $userID = (auth()->user()->id);

        $restRequest = DB::table('requests')->where('id', $request->id)->where('user_id', $userID)->first();

        /*
        $userInfo = DB::table('users')->where('id', $userID) // check if user belong to manager
            ->first();
        */

        if (!empty($restRequest)) {

            $sendRequest = DB::table('requests')->where('id', $request->id)->where('statusReq', 19) //wating for sales agent

            ->update(['statusReq' => 18, 'class_id_agent' => 57, 'updated_at' => now('Asia/Riyadh')]); //wating for sales manager approval

            DB::table('req_records')->insert([
                'colum'          => 'class_agent',
                'user_id'        => null,
                //'value'          => 'مرفوع',
                'value'          => 57,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $request->id,
                'user_switch_id' => null,
                'comment'        => 'تلقائي - عن طريق النظام',
            ]);

            if ($sendRequest == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => $sendRequest, 'id' => $request->id]);
            }

            else {

                if ($request->comment == null) {
                    $request->comment = "لايوجد";
                }

                $salesManager = MyHelpers::getSalesManagerRequest($request->id);

                $this->history($request->id, MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), $salesManager, $request->comment);

                /* DB::table('request_histories')->insert([ // add to request history
                     'title' =>  MyHelpers::admin_trans(auth()->user()->id, 'Prepayment Sent'), 'user_id' => (auth()->user()->id), 'recive_id' => $userInfo->manager_id,
                     'history_date' => (Carbon::now('Asia/Riyadh')), 'content' => $request->comment,
                     'req_id' => $restRequest->req_id,
                 ]);
                 */

                DB::table('notifications')->insert([ // add notification to send general manager user
                                                     'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                     'recived_id' => $salesManager,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 0,
                                                     'req_id'     => $request->id,
                ]);

                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $sendRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function updateNewReq(Request $request)
    {

        /// remove notifi cationes that related to new requests event the user has not open the notifies
        /*
        $updateResult = DB::table('notifications')
            ->where('value', MyHelpers::guest_trans('New Request Added'))
            ->where('recived_id', auth()->user()->id)
            ->where('type', 0)
            ->where('req_id', $request->id)
            ->update([
                'status' => 1
            ]);
        ////////
        */

        $updateResult = DB::table('requests')->where([
            ['id', '=', $request->id],
            ['statusReq', '=', 0],
        ])->update([
            'statusReq'  => 1, //open
            'updated_at' => now('Asia/Riyadh'),
        ]);

        if ($updateResult) {
            $reqinfo = DB::table('requests')->where('id', $request->id)->first();

            if (MyHelpers::checkQualityReq($request->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($request->id, 1, $reqinfo->user_id, $reqinfo->class_id_agent);
            }
        }

        return response($updateResult); // if 1: update succesfally
    }

    public function archReqArr(Request $request)
    {

        $reqWithComm = DB::table('requests')->whereIn('id', $request->array)->where('comment', null)->get();

        if ($reqWithComm->count() > 0) {
            return response(-1);
        }

        $reqWithClass = DB::table('requests')->whereIn('id', $request->array)->where('class_id_agent', null)->get();

        if ($reqWithClass->count() > 0) {
            return response(-2);
        }

        foreach ($request->array as $req) {

            $reqinfo = DB::table('requests')->where('id', $req)->first();

            if (MyHelpers::checkQualityReq($reqinfo->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($reqinfo->id, 2, $reqinfo->user_id, $reqinfo->class_id_agent);
            }
        }

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = auth()->user()->id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumnWithCount($agent_id,'archived_basket',$request->array);
        //***********END - UPDATE DAILY PREFROMENCE */

        $result = DB::table('requests')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)->whereIn('statusReq', [0, 1, 4, 31])->update([
            'statusReq'       => 2, //archived in sales agent
            'is_stared'       => 0,
            'is_canceled'     => 0,
            'is_followed'     => 0,
            'add_to_archive'  => Carbon::now('Asia/Riyadh'),
            'add_to_stared'   => null,
            'add_to_followed' => null,
            'updated_at'      => now('Asia/Riyadh'),

        ]);
        return response($result); // if 1: update succesfally

    }

    public function starReqArr(Request $request)
    {

        $reqWithComm = DB::table('requests')->whereIn('id', $request->array)->where('comment', null)->get();

        if ($reqWithComm->count() > 0) {
            return response(-1);
        }

        $reqWithClass = DB::table('requests')->whereIn('id', $request->array)->where('class_id_agent', null)->get();

        if ($reqWithClass->count() > 0) {
            return response(-2);
        }

        $reqWithReminder = DB::table('notifications')->whereIn('req_id', $request->array)->where('recived_id', '=', (auth()->user()->id))->where('type', 1)->get();

        if ($reqWithReminder->count() < count($request->array)) {
            return response(-3);
        }

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = auth()->user()->id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumnWithCount($agent_id, 'star_basket',$request->array);
        //***********END - UPDATE DAILY PREFROMENCE */

        $result = DB::table('requests')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)
            // ->whereIn('statusReq', [0, 1, 4])
            ->update([
                'is_stared'       => 1,
                'is_canceled'     => 0,
                'is_followed'     => 0,
                'add_to_archive'  => null,
                'add_to_stared'   => Carbon::now('Asia/Riyadh'),
                'add_to_followed' => null,
                'updated_at'      => now('Asia/Riyadh'),
            ]);
        return response($result); // if 1: update succesfally

    }

    public function followReqArr(Request $request)
    {

        $reqWithComm = DB::table('requests')->whereIn('id', $request->array)->where('comment', null)->get();

        if ($reqWithComm->count() > 0) {
            return response(-1);
        }

        $reqWithClass = DB::table('requests')->whereIn('id', $request->array)->where('class_id_agent', null)->get();

        if ($reqWithClass->count() > 0) {
            return response(-2);
        }

        $reqWithReminder = DB::table('notifications')->whereIn('req_id', $request->array)->where('recived_id', '=', (auth()->user()->id))->where('type', 1)->get();

        if ($reqWithReminder->count() < count($request->array)) {
            return response(-3);
        }

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = auth()->user()->id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumnWithCount($agent_id, 'followed_basket',$request->array);
        //***********END - UPDATE DAILY PREFROMENCE */

        $result = DB::table('requests')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)
            // ->whereIn('statusReq', [0, 1, 4])
            ->update([
                'is_stared'       => 0,
                'is_canceled'     => 0,
                'is_followed'     => 1,
                'add_to_archive'  => null,
                'add_to_stared'   => null,
                'add_to_followed' => Carbon::now('Asia/Riyadh'),
                'updated_at'      => now('Asia/Riyadh'),
            ]);

        return response($result); // if 1: update succesfally

    }

    public function restoreReqArr(Request $request)
    {

        $result = DB::table('requests')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)->update([
            'is_stared'       => 0,
            'is_canceled'     => 0,
            'is_followed'     => 0,
            'add_to_archive'  => null,
            'add_to_stared'   => null,
            'add_to_followed' => null,
            'updated_at'      => now('Asia/Riyadh'),
        ]);
        return response($result); // if 1: update succesfally

    }

    public function restReqArr(Request $request)
    {

        foreach ($request->array as $req) {

            $reqinfo = DB::table('requests')->where('id', $req)->first();

            if (MyHelpers::checkQualityReq($reqinfo->id)) {
                $checkAdding = MyHelpers::checkBeforeQualityReq($reqinfo->id, 1, $reqinfo->user_id, $reqinfo->class_id_agent);
            }

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $reqinfo->user_id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::updateDecrementDailyPrefromenceRecord($agent_id, Carbon::today('Asia/Riyadh')->format('Y-m-d'), 'archived_basket');
            //***********END - UPDATE DAILY PREFROMENCE */

        }

        $result = DB::table('requests')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)->where('statusReq', 2) //archived in sales agent
        ->update([
            'statusReq'           => 1,
            'is_stared'           => 0,
            'is_canceled'         => 0,
            'is_followed'         => 0,
            'remove_from_archive' => Carbon::now('Asia/Riyadh'),
            'add_to_stared'       => null,
            'add_to_followed'     => null,
            'updated_at'          => now('Asia/Riyadh'),
        ]);
        return response($result); // if 1: update succesfally

    }

    public function archCustArr(Request $request)
    {

        $result = DB::table('customers')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)->update([
            'status' => 1, //archived
        ]);
        return response($result); // if 1: update succesfally

    }

    public function restCustArr(Request $request)
    {

        $result = DB::table('customers')->whereIn('id', $request->array)->where('user_id', auth()->user()->id)->update([
            'status' => 0, //active
        ]);
        return response($result); // if 1: update succesfally

    }

    public function update_task(Request $request, $id)
    {

        $rules = [
            'user_note' => 'required',
        ];

        $customMessages = [
            'user_note.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        DB::table('tasks')->where('id', $request->id)->update([
            'user_note' => $request->user_note,
            'status'    => 2,
        ]);

        $tasks = DB::table('tasks')->where('id', $request->id)->first();

        return redirect()->route('all.show_q_task', $request->id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function show_q_task($id)
    {
        $tasks = DB::table('tasks')->where('tasks.id', $id)->join('users', 'users.id', 'tasks.recive_id')->first();

        $reqInfo = DB::table('quality_reqs')->where('id', $tasks->req_id)->first();
        $reqID = $reqInfo->req_id;
        //$task = $this->fetchTask();
        // dd($tasks);
        if ($tasks->status == 0) {

            $task = task::find($id);
            $task->status = 1;
            $task->save();
        }

        return view('QualityManager.showtask', compact('id', 'tasks', 'reqInfo', 'reqID'));
    }

    public function alltask()
    {
        $tasks = DB::table('tasks')->where('recive_id', auth()->user()->id)->whereIn('tasks.status', [0, 1, 2])->get();

        $customers = DB::table('customers')->where('user_id', auth()->user()->id)->get();

        //$customers = customer::whereIn('id',  $result)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();
        // dd($requests);

        $check = 0; // check if this user is belong for at lest one user

        return view('Agent.Task.mytask', compact('tasks', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources'));
    }

    public function task_datatable()
    {
        $tasks = DB::table('tasks')->where('recive_id', auth()->user()->id)->whereIn('tasks.status', [0, 1, 2])->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'tasks.user_id')->join('customers',
            'customers.id', 'requests.customer_id')->select('tasks.*', 'requests.comment', 'requests.id as reqID', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary', 'requests.collaborator_id', 'requests.source', 'requests.type', 'requests.quacomment',
            'quality_reqs.status as qustatus');

        // dd($all);
        return Datatables::of($tasks)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'The Request').'">
            <a href="'.route('agent.fundingRequest', $row->reqID).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-more"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->user_note;
        })->make(true);
    }

    public function statusTask($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'new task'),
            1 => MyHelpers::admin_trans(auth()->user()->id, 'open task'),
            2 => MyHelpers::admin_trans(auth()->user()->id, 'Under Processing'),
            3 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'not completed'),
            5 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[4]);
    }

    public function completedtask()
    {
        $tasks = DB::table('tasks')->where('recive_id', auth()->user()->id)->whereNotIn('tasks.status', [0, 1, 2])->get();

        $customers = DB::table('customers')->where('user_id', auth()->user()->id)->get();

        //$customers = customer::whereIn('id',  $result)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();
        // dd($requests);

        $check = 0; // check if this user is belong for at lest one user

        return view('Agent.Task.completedtask', compact('tasks', 'customers', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources'));
    }

    public function completedtask_datatable()
    {
        $tasks = DB::table('tasks')->where('recive_id', auth()->user()->id)->whereNotIn('tasks.status', [0, 1, 2])->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'tasks.user_id')->join('customers',
            'customers.id', 'requests.customer_id')->select('tasks.*', 'requests.comment', 'requests.id as reqID', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary', 'requests.collaborator_id', 'requests.source', 'requests.type', 'requests.quacomment',
            'quality_reqs.status as qustatus');

        // dd($all);
        return Datatables::of($tasks)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'The Request').'">
        <a href="'.route('agent.fundingRequest', $row->reqID).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
        <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-more"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->user_note;
        })->make(true);
    }

    public function askRequest()
    {
        $isActive = DB::table('settings')->where('option_name', 'askRequest_active')->first();

        if ($isActive->option_value == 'false') {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'ask request not allowed now'), 'status' => 3]);
        }
        if (!MyHelpers::checkAgentRecive()) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'No requests allowed to be moved'), 'status' => 3]);
        }
        //return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you are not allowed to recive new customers'), 'status' => 3]);
        if (!MyHelpers::checkNoOfAskRequest() && !MyHelpers::checkNoOfAskRequestTransBasket()) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you exceed allowed number'), 'status' => 3]);
        }

        $hours = DB::table('settings')->where('option_name', 'askRequest_hours')->first();
        $requests = DB::table('requests')->where('statusReq', 0)/*->where('class_id_agent', null)->where('comment', null)*/->where('user_id', '!=', 61) // ahmed qassem user ID
        ->get();

        //***********************************************************************
        // Task-46 Ask For more Start
        //***********************************************************************
        $requestIds = DB::table('request_need_actions')
            ->where('request_need_actions.status', 0)
            //->join('customers', 'customers.id', '=', 'request_need_actions.customer_id')
            //->join('request_histories', 'request_need_actions.req_id', '=', 'request_histories.req_id')
            ->join('users', 'users.id', '=', 'request_need_actions.agent_id')
            ->where('request_need_actions.agent_id', '<>', auth()->user()->id)
            //->where(function ($query) {
            //    $query->where('request_histories.user_id', '<>', auth()->user()->id)
            //        ->orWhere('request_histories.user_id', null);
            //})
            ->pluck('request_need_actions.req_id')->toArray();

        $requestsFromNeedActions = DB::table('requests')->whereIn('id', $requestIds)->get()->first();
        if ($requests->count() == 0) {
            if ($requestsFromNeedActions == null) {
                $getAdmin = DB::table('users')->where('role', 7)->first();
                DB::table('notifications')->insert([
                    'value'      => MyHelpers::guest_trans('agent tried to ask customer').' '.auth()->user()->name,
                    'recived_id' => $getAdmin->id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 6,
                    'req_id'     => null,
                ]);
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'No requests allowed to be moved'), 'status' => 3]);
            }
            else {
                //***********************************************************************
                // Task-46 Ask For more End
                //***********************************************************************
                $requestActions = \App\Models\Request::whereIn('id', $requestIds)->get();
                $count = setting('trans_basket_request_count');
                foreach ($requestActions as $requestAction) {
                    if ($count < 1) {
                        break;
                    }
                    self::moveRequestToUser($requestAction, RequestHistory::CONTENT_ASK_REQUEST_TRANSFER_BASKET);
                    MyHelpers::addNewMoveRequestRecord();
                    $count--;
                }

                //return response([
                //    'message' => MyHelpers::admin_trans(auth()->user()->id, 'No requests allowed to be moved'),
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'), 'status' => 1]);
            }

        }

        $counter = 0;
        foreach ($requests as $request) {
            if ($hours->option_value <= carbon::parse($request->agent_date)->diffInHours()) {
                $counter++;
            }
        }

        if ($counter == 0) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'No requests allowed to be moved'), 'status' => 3]);
        }

        $noRequest = DB::table('settings')->where('option_name', 'askRequest_noRequest')->first();
        $count = $noRequest->option_value;
        $check = false;
        foreach ($requests as $request) {
            if ($count != 0) {
                if ($hours->option_value <= carbon::parse($request->agent_date)->diffInHours()) {
                    self::moveRequestToUser($request, MyHelpers::admin_trans(auth()->user()->id, 'Ask Request'));
                    MyHelpers::addNewMoveRequestRecord();
                    $count--;
                }
            }
        }

        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'), 'status' => 1]);
    }

    public static function moveRequestToUser($request, $content): void
    {
        if ($request->source != 2) {
            $updateCustomer = DB::table('customers')->where('id', $request->customer_id)->update(['user_id' => auth()->user()->id]);
        }

        $requests = DB::table('requests')->where('id', $request->id)
            ->update([
                'user_id'                 => auth()->user()->id,
                'agent_date'              => carbon::now(),
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
                'is_canceled'             => 0,
                'is_followed'             => 0,
                'is_stared'               => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                'updated_at'              => now('Asia/Riyadh'),
            ]);

        DB::table('request_histories')->insert([
            'title'          => RequestHistory::TITLE_MOVE_REQUEST,
            'user_id'        => $request->user_id,
            'recive_id'      => (auth()->user()->id),
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $request->id,
            'class_id_agent' => $request->class_id_agent,
            //'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Ask Request'),
            'content'        => $content,
        ]);

        DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                            'recived_id' => $request->user_id,
                                            'req_id'     => $request->id,
        ])->delete();

        $check = true;

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = $request->user_id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$request->id);
        //***********END - UPDATE DAILY PREFROMENCE */

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = auth()->user()->id;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$request->id);
        // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id);
        //***********END - UPDATE DAILY PREFROMENCE */

        #move customer's messages to new agent
        MyHelpers::movemessage($request->customer_id, auth()->user()->id, $request->user_id);

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($request->id) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($request->id);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($request->id);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($request->id);

    }

    public function updatereadyrecive()
    {
        $auth_id = auth()->user()->id;
        $check_update = false;

        if (auth()->user()->ready_receive == 0) {
            $check_return = MyHelpers::set_ready_recive($auth_id);
            if ($check_return) {
                $check_update = true;
            }
        }
        else {
            $is_update = DB::table('users')->where('id', $auth_id)->update(['ready_receive' => 0]);
            if ($is_update) {
                $check_update = true;
            }
        }

        if ($check_update) {
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
    }

    //****************************************************************************

    public function check_on_agent_ready_recive()
    {
        User::where([
            'allow_recived' => 0,
            'ready_receive' => 1,
        ])->update(['ready_receive' => 0]);

        //return;
        //$list_users = DB::table('users')->where('ready_receive', 1)->get();
        //foreach ($list_users as $user) {
        //    $check_return = MyHelpers::set_ready_recive($user->id);
        //    if (!$check_return) {
        //        $is_update = DB::table('users')->where('id', $user->id)->update(['ready_receive' => 0]);
        //    }
        //}
    }

    public function getAllRequests()
    {
        $user = auth()->id();
        $ready = MyHelpers::set_ready_recive($user);
        $requests = RequestWaitingList::where('request_waiting_lists.status', 0)->join('requests', 'requests.id', 'request_waiting_lists.req_id')->join('customers', 'customers.id', 'request_waiting_lists.customer_id')->join('users', 'users.id', 'request_waiting_lists.agent_id')->where('agent_id',
            '<>', $user)->select('customers.name', 'customers.salary', 'customers.birth_date_higri', 'customers.has_obligations', 'customers.has_financial_distress', 'customers.work', 'customers.military_rank')->orderBy('request_waiting_lists.created_at', 'DESC')->count();

        $salesAgents2 = DB::table('request_waiting_lists')->where('request_waiting_lists.status', 0)->join('users', 'users.id', '=', 'request_waiting_lists.agent_id')->distinct('users.id')->select('users.name', 'users.id')->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        return view('Agent.Request.waitingReqsNew', compact('requests', 'salesAgents', 'salesAgents2', 'ready', 'classifcations_sa'));
    }

    public function waitingReqs_datatableNew(Request $request)
    {
        $user = auth()->id();
        $this->userId = $user;
        $requests = RequestWaitingList::whereHas('request')->whereHas('customer')->whereHas('user')->with('messages', 'customer')->where('request_waiting_lists.status', 0)->where('agent_id', '<>', $user)->orderBy('request_waiting_lists.created_at', 'DESC')->get();

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('customer_name', function ($row) {
            return $row->customer->name;
        })->addColumn('message_value', function ($row) {
            return $row->messages->last()->message_value;
        })->addColumn('actions', function ($row) {
            $ready = MyHelpers::set_ready_recive($this->userId);
            if ($ready == false) {
                return '<a class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-times pr-2"></i>لا يمكنك سحب الطلب  </a>';

            }
            return '<a onclick="deleteData('.$row->req_id.')" class="btn btn-xs btn-success btn-sm text-white"><i class="fa fa-file pr-2"></i>سحب الطلب </a>';

        })->rawColumns(['actions'])->make(true);
    }

    public function moveRequest($requestId)
    {
        try {
            $userId = auth()->id();
            $request = \App\request::find($requestId);

            if (!$request) {
                return response()->json("رقم الطلب غير مسجل لدينا");
            }
            MyHelpers::checkIfThereIsNeedActionReq($requestId);
            $prev_user = $request->user_id;

            MyHelpers::moveRequestTasks($request->id, $request->user_id);

            $customerID = $request->customer_id;
            $this->updateRequestInfo($requestId);

            if ($request->collaborator_id == null) {
                customer::find($customerID)->update([
                    'user_id' => $userId,
                ]);
            }

            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                 'recived_id' => $request->salesAgent,
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $request->id,
            ]);
            DB::table('request_histories')->insert([
                'title'          => 'نقل الطلب',
                'user_id'        => $prev_user,
                'recive_id'      => $userId,
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $requestId,
                'class_id_agent' => $request->class_id_agent,
                'content'        => null,
            ]);

            DB::table('notifications')->where([
                //remove previous notificationes that related to previous agent's request
                'recived_id' => $prev_user,
                'req_id'     => $request->id,
            ])->delete();

            MyHelpers::movemessage($customerID, $userId, $prev_user);
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$request->id);

            $agent_id = $userId;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request->id);
            // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id);
            if (MyHelpers::checkQualityReqExistedByReqID($requestId) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($requestId);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($requestId);
                }
            }

            MyHelpers::removeNeedActionReqByReqID($requestId);
            MyHelpers::updateWaitingReq($requestId);
            MyHelpers::removeWaitingReq($requestId);

            return response()->json([
                'success' => true,
                'message' => 'تم سحب الطلب بنجاح',
            ]);
        }
        catch (Exception $e) {
            return redirect()->back();
        }
    }

    private function updateRequestInfo($requestId): void
    {
        $userId = auth()->id();
        \App\request::find($requestId)->update([
            'user_id'                 => $userId,
            'statusReq'               => 0,
            'agent_date'              => carbon::now(),
            'is_stared'               => 0,
            'is_followed'             => 0,
            'add_to_stared'           => null,
            'add_to_followed'         => null,
            'isUnderProcFund'         => 0,
            'isUnderProcMor'          => 0,
            'recived_date_report'     => null,
            'recived_date_report_mor' => null,
        ]);
    }

    public function readyReceive()
    {
        $userId = auth()->id();
        $ready = MyHelpers::set_ready_recive($userId);
        $status = !$ready;

        if ($status == false) {
            User::where('id', $userId)->update(['ready_receive' => 0]);
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الإستلام بنجاح (لا يمكنك استلام طلبات بالوقت الحالي)',
            ]);
        }

        if ($ready == false) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح لك بإستلام طلبات (سلة المستلمة بها طلبات)',
            ]);
        }
        return response()->json([
            'success' => true,

            'message' => 'تم تحديث حالة الإستلام بنجاح ( يمكنك استلام طلبات بالوقت الحالي)',
        ]);

    }

}
