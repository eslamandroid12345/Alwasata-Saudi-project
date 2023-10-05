<?php

namespace App\Helpers;

use App\Calculater;
use App\classCondition;
use App\customer;
use App\CustomersPhone;
use App\DailyLogs;
use App\DailyPerformances;
use App\Email;
use App\EmailUser;
use App\Mail\WastaMailNotification;
use App\Model\PendingRequest;
use App\Models\RequestHistory;
use App\Models\SmsLog;
use App\movedRequest;
use App\notification as notify;
use App\Notifications\SendNotification;
use App\quality_req;
use App\request;
use App\requestConditions;
use App\requestConditionSettings;
use App\RequestNeedAction;
use App\Scenario;
use App\ScenariosUsers;
use App\statusCondition;
use App\task;
use App\task_content;
use App\User;
use App\userCondition;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Mail;

//to take date

class MyHelpers
{

    public static function getControlTypeName($type)
    {
        try {
            $types = [
                'nationality'   => ['الجنسيات', 'جنسية'],
                'guaranty'      => ['الكفالة', 'كفالة'],
                'guaranty_name' => ['إسم الكفيل', 'إسم الكفيل'],
                'subsection'    => ['الأقسام الفرعية', 'قسم فرعى'],
                'section'       => ['الأقسام', 'قسم'],
                'company'       => ['الشركات', 'شركة'],
                'insurances'    => ['التأمينات', 'تأمين'],
                'medical'       => ['التأمين الطبى', 'تأمين طبى'],
                'work'          => ['طرق العمل', ' طريقة عمل'],
                'identity'      => ['أنواع الهوية', ' نوع هوية '],
                'custody'      => ['عهدة', 'عهدة'],
            ];
            return $types[$type];
        }
        catch (Exception $e) {
            return null;
        }

    }

    public static function sendEmail($email, $msg, $subject)
    {
        Mail::to($email)->send(new WastaMailNotification($subject, $msg));
    }

    public static function sendSmsOtp($mobile, $otpCode)
    {
        $msg = 'عزيزي عميل شركة الوساطة ، رمز التحقق الخاص بك : '.$otpCode;
        return self::sendSMS($mobile, $msg);
    }

    public static function sendSMS($mobile, $msg)
    {
        $customerMobile = $mobile;
        try {
            $slackMessage = config('app.url').": {$msg}.";
            sendSlackNotification($slackMessage, (string) $mobile);
        }
        catch (Exception $exception) {
            //dd($exception);
        }

        if (!config('sms.enabled')) {
            return null;
        }
        $username = "alwsatasa";
        $password = "Wsat_a@1670";
        $sender = "ALWSATA";
        $url = "https://www.vip1sms.com/smartsms/api/sendsms.php";
        //$username = Config::get('sms.username');
        //$password = Config::get('sms.password');
        //$sender = Config::get('sms.sender');
        //$url = Config::get('sms.url');



        $originalMobile = $mobile;
        $originalMessage = $msg;
        $mobile = "966.{$originalMobile}";
        $msg = urlencode($originalMessage);
        if (!$msg) {
            return null;
        }

        $sendsms = $url.'?username='.$username.'&password='.$password.'&message='.$msg.'&numbers='.$mobile.'&sender='.$sender.'&unicode=u&return=json';

        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $sendsms);

        // grab URL and pass it to the browser
        $data = curl_exec($ch); //$data will contain the API call response
        // close cURL resource, and free up system resources
        curl_close($ch);
        try {
            if ($data) {
                $json = json_decode($data, !0) ?: [];
                $sent = ($json['Code'] ?? null) == 100;
                SmsLog::log($originalMessage, $originalMobile, $json, $sent);
                if($sent == 1)
                {
                    Customer::where('mobile',$customerMobile)->increment('sms_count');
                }
            }
        }
        catch (\Exception $exception) {

        }

        try {
            $slackMessage = "Sms Result: {$data}.";
            sendSlackNotification($slackMessage, (string) $mobile);
        }
        catch (Exception $exception) {
        }

        return $data;
    }

    public static function checkPostRequest($request)
    {
        if (!$request->has('salary')) {
            $request->request->add(['salary' => null]);
        }
        if (!$request->has('birth_date')) {
            $request->request->add(['birth_date' => null]);
        }
        if (!$request->has('birth_hijri')) {
            $request->request->add(['birth_hijri' => null]);
        }
        if (!$request->has('work')) {
            $request->request->add(['work' => null]);
        }
        if (!$request->has('is_supported')) {
            $request->request->add(['is_supported' => null]);
        }
        if (!$request->has('has_property')) {
            $request->request->add(['has_property' => null]);
        }
        if (!$request->has('has_joint')) {
            $request->request->add(['has_joint' => null]);
        }
        if (!$request->has('has_obligations')) {
            $request->request->add(['has_obligations' => null]);
        }
        if (!$request->has('has_financial_distress')) {
            $request->request->add(['has_financial_distress' => null]);
        }
        if (!$request->has('owning_property')) {
            $request->request->add(['owning_property' => null]);
        }

        return $request;
    }

    public static function check_is_request_acheive_condition($request)
    {  // start helper function for check

        $is_achieve = false;

        $request_conditions = RequestConditionSettings::get();
        if (count($request_conditions) == 0){
            return true;
        }
        foreach ($request_conditions as $request_condition) {
            /// start check date of birth
            ///

            $count = RequestConditionSettings::where("id",$request_condition->id)
            ->when($request_condition->request_validation_from_birth_hijri,function ($q,$v) use ($request){
                $q->where("request_validation_from_birth_hijri",'<=',$request->birth_hijri);})
            ->when($request_condition->request_validation_to_birth_hijri != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_birth_hijri",'>=',$request->birth_hijri);})
            ->when($request_condition->request_validation_from_birth_date,function ($q,$v) use ($request){
                $q->where("request_validation_from_birth_date",'<=',$request->birth_date);})
            ->when($request_condition->request_validation_to_birth_date != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_birth_date",'>=',$request->birth_date);})
            ->when($request_condition->request_validation_from_salary != null,function ($q,$v) use ($request){
                $q->where("request_validation_from_salary","<=",(int)$request->salary);})
            ->when($request_condition->request_validation_to_salary != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_salary",">=",(int)$request->salary);})
            ->when($request_condition->request_validation_to_work != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_work",$request->work);})
            ->when($request_condition->request_validation_to_support != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_support",$request->is_supported);})
            ->when($request_condition->request_validation_to_hasProperty != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_hasProperty",$request->has_property);})
            ->when($request_condition->request_validation_to_hasJoint != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_hasJoint",$request->has_joint);})
            ->when($request_condition->request_validation_to_has_obligations != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_has_obligations",$request->has_obligations);})
            ->when($request_condition->request_validation_to_has_financial_distress != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_has_financial_distress",$request->has_financial_distress);})
            ->when($request_condition->request_validation_to_owningProperty != null,function ($q,$v) use ($request){
                $q->where("request_validation_to_owningProperty",$request->owning_property);})
            ->count();

           if ($count > 0){
               $is_achieve =true;
               break;
           }
        }
        return $is_achieve;
    }

    public static function check_data_is_between_rang($date, $from_date, $to_date)
    {
        $is_acheive = false;

        if (!empty($date) && (!empty($from_date) || !empty($to_date))) { // if has value
            $birth_date_carbon = Carbon::parse($date);
            $from_birth_date_carbon = Carbon::parse($from_date);
            $to_birth_date_carbon = Carbon::parse($to_date);
            $is_date2_greater_or_equal_date1 = $birth_date_carbon->gte($from_birth_date_carbon);
            $is_date2_less_or_equal_date1 = $birth_date_carbon->lte($to_birth_date_carbon);

            /// check from_birth_date is not  null
            if (!empty($from_date) && !empty($to_date)) { ///start if statement from_birth_date
                $is_acheive = $is_date2_greater_or_equal_date1 && $is_date2_less_or_equal_date1 && !$is_acheive;
            } /// end if statement from_birth_date

            else {
                if ($to_date) { ///start if statement from_birth_date
                    $is_acheive = $is_date2_less_or_equal_date1 && !$is_acheive;
                } /// end if statement from_birth_date
                else {
                    if ($from_date) { ///start if statement from_birth_date
                        $is_acheive = $is_date2_greater_or_equal_date1 && !$is_acheive;
                    }
                }
            } /// end if statement from_birth_date

        }
        return $is_acheive;
    }

    public static function check_salary_between_rang($salary, $from_salary, $to_salary)
    {
        $is_acheive = false;

        if (!empty($salary) && (!empty($from_salary) || !empty($to_salary))) {

            if ($from_salary && $to_salary) {
                $is_acheive = doubleval($salary) <= doubleval($to_salary) && doubleval($salary) >= doubleval($from_salary) && !$is_acheive;
            }
            else {
                if ($from_salary) {
                    $is_acheive = doubleval($salary) >= doubleval($from_salary) && !$is_acheive;
                }
                else {
                    $is_acheive = doubleval($salary) <= doubleval($to_salary) && !$is_acheive;
                }
            }
        }
        else {
            if (!empty($from_salary) || !empty($to_salary)) {
                $is_acheive = false;
            }
        }
        return $is_acheive;
    }

    public static function check_work($work, $work_setting)
    {
        $is_acheive = false;

        if (!empty($work) && (!empty($work_setting))) {
            if ($work == $work_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function check_support($support, $support_setting)
    {
        $is_acheive = false;

        if (!empty($support) && (!empty($support_setting))) {
            if ($support == $support_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function check_property($has_property, $property_setting)
    {
        $is_acheive = false;

        if (!empty($has_property) && (!empty($property_setting))) {
            if ($has_property == $property_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function check_joint($has_joint, $joint_setting)
    {
        $is_acheive = false;

        if (!empty($has_joint) && (!empty($joint_setting))) {
            if ($has_joint == $joint_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function check_obligations($has_obligations, $obligations_setting)
    {
        $is_acheive = false;

        if (!empty($has_obligations) && (!empty($obligations_setting))) {
            if ($has_obligations == $obligations_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function check_distress($has_distress, $distress_setting)
    {
        $is_acheive = false;

        if (!empty($has_distress) && (!empty($distress_setting))) {
            if ($has_distress == $distress_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    }

    public static function owning_property($owning_property, $owning_property_setting)
    {
        $is_acheive = false;

        if (!empty($owning_property) && (!empty($owning_property_setting))) {
            if ($owning_property == $owning_property_setting) {
                $is_acheive = true;
            }
        }

        return $is_acheive;
    } /// end helper check

    public static function check_is_hasbah_request_acheive_condition($request)
    {  // start helper function for check

        $is_acheive = true;

        $salary = $request['salary'];
        $birth_date = $request['birth_date'];
        $birth_hijri = $request['birth_hijri'];
        $work = $request['work'];

        $request_conditions = RequestConditionSettings::get();
        if (count($request_conditions) == 0){
            return true;
        }
        foreach ($request_conditions as $request_condition) {

            if ($request_condition->id != 23) {

                $is_acheive = true;

                $from_birth_date = $request_condition->request_validation_from_birth_date;
                $to_birth_date = $request_condition->request_validation_to_birth_date;
                $from_birth_hijri = $request_condition->request_validation_from_birth_hijri;
                $to_birth_hijri = $request_condition->request_validation_to_birth_hijri;
                $from_salary = $request_condition->request_validation_from_salary;
                $to_salary = $request_condition->request_validation_to_salary;
                $work_setting = $request_condition->request_validation_to_work;
                /// start check date of birth

                if ($birth_date && (!empty($from_birth_date) || !empty($to_birth_date))) {

                    $is_acheive = MyHelpers::check_data_is_between_rang($birth_date, $from_birth_date, $to_birth_date) && $is_acheive;
                }
                else {
                    if ($birth_hijri && (!empty($from_birth_hijri) || !empty($to_birth_hijri))) { // if has value
                        $is_acheive = MyHelpers::check_data_is_between_rang($birth_hijri, $from_birth_hijri, $to_birth_hijri) && $is_acheive;
                    }
                    else { // if birth hijri is null

                        $is_acheive = empty($from_birth_date) && empty($to_birth_date) && empty($from_birth_hijri) && empty($to_birth_hijri) && $is_acheive;
                    }
                } // end else

                if ($salary && (!empty($from_salary))) {
                    $is_acheive = doubleval($salary) >= doubleval($from_salary) && $is_acheive;
                }
                else {
                    if ($salary && (!empty($to_salary))) {
                        $is_acheive = doubleval($salary) <= doubleval($to_salary) && $is_acheive;
                    }
                    else {
                        $is_acheive = empty($from_salary) && empty($to_salary) && $is_acheive;
                    }
                }

                if ($work && (!empty($work_setting))) {
                    $is_acheive = MyHelpers::check_work($work, $work_setting) && $is_acheive;
                }
                else {
                    $is_acheive = empty($work_setting) && $is_acheive;
                }

                if ($is_acheive) {
                    return $is_acheive;
                }
            }

        }

        return $is_acheive;
    } /// end helper check

    public static function getInfoFromExcel($requestInfo, $agent_note, $agent_class, $quality_note, $quality_class, $q_1, $q_2, $q_3, $q_4, $need_action)
    {

        $agentID = $requestInfo->user_id;

        //Update Agent comment
        if ($requestInfo->comment == null) {
            if ($agent_note != null) {
                $updatereq = DB::table('requests')->where('id', $requestInfo->id)->update([
                    'comment' => $agent_note,
                ]);
                $updateID = DB::table('req_records')->insertGetId([
                    'colum'          => 'comment',
                    'user_id'        => $agentID,
                    'value'          => $agent_note,
                    'updateValue_at' => '2020-01-01 00:00:00',
                    'req_id'         => $requestInfo->id,
                    'user_switch_id' => null,
                ]);
            }
        }
        //End Update

        //Update Agent class
        if ($requestInfo->class_id_agent == null || $requestInfo->class_id_agent == 1) {
            if ($agent_class != null) {
                $updatereq = DB::table('requests')->where('id', $requestInfo->id)->update([
                    'class_id_agent' => 33,
                ]);
            }
        }
        //End Update

        //Update Quality comment
        if ($requestInfo->quacomment == null) {
            if ($quality_note != null) {
                $updatereq = DB::table('requests')->where('id', $requestInfo->id)->update([
                    'quacomment' => $quality_note,
                ]);

                $updateID = DB::table('req_records')->insertGetId([
                    'colum'          => 'comment',
                    'user_id'        => 59,
                    'value'          => $quality_note,
                    'updateValue_at' => '2020-01-01 00:00:00',
                    'req_id'         => $requestInfo->id,
                    'user_switch_id' => null,
                ]);
            }
        }
        //End Update

        //Update Quality class
        if ($requestInfo->class_id_quality == null) {
            if ($quality_class != null) {
                $updatereq = DB::table('requests')->where('id', $requestInfo->id)->update([
                    'class_id_quality' => 52,
                ]);
            }
        }
        //End Update

        if (MyHelpers::checkQualityReq($requestInfo->id)) {

            //Create Quality Reqs
            $newReq = quality_req::create([
                'req_id'     => $requestInfo->id,
                'created_at' => '2020-01-01 00:00:00',
                'user_id'    => 59,
                'status'     => 5,
            ]);
            //End create

            //dd( $newReq);

            //Create Servay
            if ($q_1 != null || $q_2 != null || $q_3 != null || $q_4 != null) {

                //CREATE
                $servayId = DB::table('servays')->insertGetId([
                    'user_id' => $agentID,
                    'req_id'  => $newReq->id,
                ]);

                //SAVE ANSWERS

                if ($q_1 != null) {
                    $resultId = DB::table('serv_ques')->insertGetId([
                        'ques_id' => 1,
                        'serv_id' => $servayId,
                        'answer'  => $q_1 == 'نعم' ? 2 : 1,
                    ]);
                }

                if ($q_2 != null) {
                    $resultId = DB::table('serv_ques')->insertGetId([
                        'ques_id' => 2,
                        'serv_id' => $servayId,
                        'answer'  => $q_2 == 'نعم' ? 2 : 1,
                    ]);
                }

                if ($q_3 != null) {
                    $resultId = DB::table('serv_ques')->insertGetId([
                        'ques_id' => 3,
                        'serv_id' => $servayId,
                        'answer'  => $q_3 == 'نعم' ? 2 : 1,
                    ]);
                }

                if ($q_4 != null) {
                    $resultId = DB::table('serv_ques')->insertGetId([
                        'ques_id' => 4,
                        'serv_id' => $servayId,
                        'answer'  => $q_4 == 'نعم' ? 2 : 1,
                    ]);
                }

                //END ANSWERS
            }
            //End Servay

            //CREATE TASK
            if ($need_action == 'نعم') {

                $newTask = task::create([
                    'req_id'    => $newReq->id,
                    'recive_id' => $agentID,
                    'user_id'   => 59,
                    'status'    => 3,
                ]);

                $newContent = task_content::create([
                    'content'         => $quality_note != null ? $quality_note : null,
                    'date_of_content' => '2020-01-01 00:00:00',
                    'task_id'         => $newTask->id,
                ]);
            }

            return true;
        }

        return false;
    }

    public static function checkQualityReq($req_id)
    {
        $request = DB::table('quality_reqs')->whereIn('status', [0, 1, 2, 5])
            ->where('req_id', $req_id)->first();
        //IF IT'S EXISTED BUT NOT RECEIVED YEST TO QUALITY
        if (!empty($request)) {
            if ($request->allow_recive == 0) {
                DB::table('quality_reqs')->where('id', $request->id)->delete();
                return true;
            }
        }
          //dd($request);
        if (empty($request)) {
            return true;
        }
        return false;
    }

    public static function activeQualities()
    {

        $all_users = DB::table('users')->where('role', 5)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        return $all_users;

    }

    public static function getAllActiveAdmin()
    {

        return User::where('role', 7)->where('status', 1)->get();
    }

    public static function getAllActiveGM()
    {

        return User::where('role', 4)->where('status', 1)->get();
    }
    public static function getAllActiveGMAndAdmins()
    {

        return User::whereIn('role', [4, 7])->where('status', 1)->get();
    }

    public static function getRequestByMobile($mobile)
    {
        $customer = DB::table('customers')->where('mobile', $mobile)->first();
        $mobiles = CustomersPhone::where('mobile', $mobile)->first();
        if ($customer == null) {
            $customer = DB::table('customers')->where('id', $mobiles->customer_id)->first();
        }

        if ($customer != null) {
            $request = DB::table('requests')->where('customer_id', $customer->id)->first();
            if ($request) {
                return $request;
            }
            else {
                $request = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
                if ($request) {
                    return $request;
                }
            }
        }

        return null;
    }

    public static function typeOfRequest($mobile)
    {
        $customer = DB::table('customers')->where('mobile', $mobile)->first();
        $mobiles = CustomersPhone::where('mobile', $mobile)->first();
        if ($customer == null) {
            $customer = DB::table('customers')->where('id', $mobiles->customer_id)->first();
        }

        if ($customer != null) {
            $request = DB::table('requests')->where('customer_id', $customer->id)->first();
            if ($request) {
                return 'request';
            }
            else {
                $request = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
                if ($request) {
                    return 'pending';
                }
            }
        }

        return null;
    }

    public static function getAgentInfo($agentID)
    {
        $agent = DB::table('users')->where('id', $agentID)->first();

        if (!empty($agent)) {
            return $agent;
        }

        return null;
    }

    public static function addNeedActionReq($action, $agent_id, $req_id)
    {
        $reqInfo = DB::table('requests')->where('id', $req_id)->first();
        //if (MyHelpers::checkClassType($reqInfo->class_id_agent)) // negativ classifications
        if ($reqInfo->class_id_agent != 16 && $reqInfo->class_id_agent != 13) { // we prevent to add the request with "Rejected req" to add it in Request need action table.
            $newReq = RequestNeedAction::create([
                'action'      => $action,
                'agent_id'    => $agent_id,
                'req_id'      => $req_id,
                'customer_id' => $reqInfo->customer_id,
            ]);
        }
    }

    public static function addNeedActionReqWithoutConditions($action, $agent_id, $req_id)
    {
        $reqInfo = \App\Models\Request::findOrFail($req_id);
        $data = [
            'action'      => $action,
            'agent_id'    => $agent_id,
            'req_id'      => $req_id,
            'customer_id' => $reqInfo->customer_id,
        ];
        RequestNeedAction::query()->firstOrCreate($data);
    }

    public static function checkDublicateOfNeedActionReq($action, $agent_id, $req_id)
    {
        $needReqs = RequestNeedAction::where('req_id', $req_id)->where('agent_id', $agent_id)->where('status', 0)->get();
        foreach ($needReqs as $needReq) {
            if (Carbon::parse($needReq->created_at)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                // preventing to create same action in the same day
                return false;
            }
        }
        return true;
    }

    public static function checkIfNeedActionReqExisted($req_id)
    {

        $needReqs = RequestNeedAction::where('req_id', $req_id)->where('status', 0)->first();

        if ($needReqs) {
            return true;
        }
        return false;
    }

    public static function getActiveRequestByMobile($mobile)
    {
        $request = null;
        $customer = DB::table('customers')->where('mobile', $mobile)->first();
        $mobiles = CustomersPhone::where('mobile', $mobile)->first();
        if ($customer == null && $mobiles) {
            $customer = DB::table('customers')->where('id', $mobiles->customer_id)->first();
        }
        if ($customer) {
            $request = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->where('customer_id', $customer->id)
                ->select('customers.*', 'real_estats.has_property', 'real_estats.owning_property', 'requests.*')
                ->first();
        }
        return $request;
    }

    public static function getPendingRequestByMobile($mobile)
    {
        $customer = DB::table('customers')->where('mobile', $mobile)->first();
        $mobiles = CustomersPhone::where('mobile', $mobile)->first();
        if ($customer == null) {
            $customer = DB::table('customers')->where('id', $mobiles->customer_id)->first();
        }
        if ($customer != null) {
            $request = DB::table('pending_requests')->join('customers', 'customers.id', '=', 'pending_requests.customer_id')->join('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')->where('pending_requests.customer_id', $customer->id)->select('customers.*',
                'real_estats.has_property', 'real_estats.owning_property', 'pending_requests.*')->first();

            if ($request) {
                return $request;
            }
        }
        return null;
    }

    public static function movePendingToRequestTable($request_data)
    {
        $reqID = DB::table('requests')->insertGetId([
            'source'          => $request_data->source,
            'req_date'        => $request_data->req_date,
            'created_at'      => $request_data->created_at,
            'user_id'         => auth()->user()->id,
            'customer_id'     => $request_data->customer_id,
            'collaborator_id' => $request_data->collaborator_id,
            'statusReq'       => $request_data->statusReq,
            'joint_id'        => $request_data->joint_id,
            'fun_id'          => $request_data->fun_id,
            'searching_id'    => $request_data->searching_id,
            'real_id'         => $request_data->real_id,
            'agent_date'      => carbon::now(),
        ]);

        $content = MyHelpers::guest_trans('PendingRequests');
        $record = MyHelpers::addNewReordPending($reqID, auth()->user()->id, $content);

        DB::table('pending_requests')->where('id', $request_data->id)->delete();

        return $reqID;
    }

    public static function guest_trans($template)
    {

        return __("language.{$template}");
        $output = '';

        $local = 'ar';
        $lan = '_language.';

        $output = trans($local.''.$lan.''.$template);

        return $output;
    }

    public static function addNewReordPending($reqID, $user_id, $content)
    {

        $reqInfo = DB::table('requests')->where('id', $reqID)->first();
        return DB::table('request_histories')->insert([
            'title'          => RequestHistory::TITLE_MOVE_REQUEST,
            'user_id'        => null,
            'recive_id'      => $user_id,
            'class_id_agent' => $reqInfo->class_id_agent,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $reqID,
            'content'        => $content,
        ]);
    }

    public static function moveRequestFromArchivedAgent($req, $content = null)
    {
        //dd($req,234);
        $reqID = $req->id;
        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

        /*
        #to get all tasks that related to this request
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs) {

                $query->where(function ($query) use ($reqID) {
                    $query->where('tasks.req_id',  $reqID);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                });
            })->pluck('id')->toArray();


        #set as uncompleted tasks
        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('status', [0, 1, 2])
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 4
                ]);

            if (count($getAllIdsInQualityReqs) > 0) {

                $completeReq = DB::table('quality_reqs')
                    ->whereIn('id', $getAllIdsInQualityReqs)
                    ->update([
                        'status' => 3
                    ]);

                if (MyHelpers::checkQualityReq($reqID)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($reqID, $req->statusReq, $req->user_id, $req->class_id_agent);
                }
            }
        }
        */

        $reqInfo = DB::table('requests')->where('id', $reqID)->first();
        $prev_user = $reqInfo->user_id;

        /*
        #move all curent tasks to new agent
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id',  $reqID);
                    $query->where('tasks.recive_id',  $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id',  $prev_user);
                });
            })
            ->whereIn('status', [0, 1, 2])
            ->pluck('id')->toArray();


        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 0,
                    'recive_id' => auth()->user()->id,
                    'created_at' => carbon::now(),
                ]);
        }
        #
        */

        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => auth()->user()->id,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK

        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);

            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

            foreach ($tasks as $task) {

                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => auth()->user()->id,
                        'user_id'   => $task->user_id,
                    ]);

                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }

        ///////////////////////////////////////////////////////

        $customerID = $req->customer_id;
        $isFreeze = $req->is_freeze;
        DB::table('requests')->where('id', $reqID)->update([
            'user_id'                 => auth()->user()->id,
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
            'is_freeze'               => 0,
        ]);

        if ($req->collaborator_id == null) {
            DB::table('customers')->where('id', $customerID)->update([
                'user_id' => auth()->user()->id,
            ]);
        }
        $title = $isFreeze ? RequestHistory::MOVE_FROM_FREEZE : RequestHistory::TITLE_MOVE_REQUEST;
        $content = $content ?: ($isFreeze ? RequestHistory::CONTENT_AGENT_TAKE_FROZEN_REQUEST : RequestHistory::CONTENT_ARCHIVED_AGENT);
        //dd($content);
        DB::table('request_histories')->insert([
            'title'          => $title,
            'user_id'        => $prev_user,
            'recive_id'      => auth()->user()->id,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $reqID,
            'class_id_agent' => $req->class_id_agent,
            'content'        => $content,
        ]);

        #move customer's messages to new agent
        MyHelpers::movemessage($customerID, auth()->user()->id, $prev_user);

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($reqID);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($reqID);
    }

    public static function movemessage($customerID, $newAgent, $old_agent)
    {

        $messages = DB::table('messages')->where('messages.from', $customerID)->where('messages.to', $old_agent)->get()->pluck('id');

        if (count($messages) > 0) {
            $updateMessage = DB::table('messages')->whereIn('id', $messages)->update(['messages.to' => $newAgent, 'is_read' => 0]);
        }
    }

    public static function checkQualityReqExistedByReqID($id)
    {
        return DB::table('quality_reqs')->where('req_id', $id)->get()->count();
    }

    public static function removeQualityReqByReqID($id)
    {
        \Schema::disableForeignKeyConstraints();
        return DB::table('quality_reqs')->where('req_id', $id)->where('status', 0)->delete();
    }

    public static function updateQualityReqToCompleteByReqID($id)
    {
        DB::table('quality_reqs')->where('req_id', $id)
            ->whereIn('status', [1, 2, 5])->update(['status' => 3]);
    }

    public static function removeNeedActionReqByReqID($id)
    {

        $removeReq = DB::table('request_need_actions')->where('req_id', $id)->where('status', 0)->delete();
    }

    public static function moveRequestWithRejectedClassOrCustomerNotWantIt($req)
    {

        $reqID = $req->id;

        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

        /*
        #to get all tasks that related to this request
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs) {

                $query->where(function ($query) use ($reqID) {
                    $query->where('tasks.req_id',  $reqID);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                });
            })->pluck('id')->toArray();


        #set as uncompleted tasks
        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('status', [0, 1, 2])
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 4
                ]);

            if (count($getAllIdsInQualityReqs) > 0) {

                $completeReq = DB::table('quality_reqs')
                    ->whereIn('id', $getAllIdsInQualityReqs)
                    ->update([
                        'status' => 3
                    ]);

                if (MyHelpers::checkQualityReq($reqID)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($reqID, $req->statusReq, $req->user_id, $req->class_id_agent);
                }
            }
        }

        */
        $reqInfo = DB::table('requests')->where('id', $reqID)->first();
        $prev_user = $reqInfo->user_id;

        /*
        #move all curent tasks to new agent
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id',  $reqID);
                    $query->where('tasks.recive_id',  $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id',  $prev_user);
                });
            })
            ->whereIn('status', [0, 1, 2])
            ->pluck('id')->toArray();


        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 0,
                    'recive_id' => auth()->user()->id,
                    'created_at' => carbon::now(),
                ]);
        }
        #
        */

        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => auth()->user()->id,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK

        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);

            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

            foreach ($tasks as $task) {

                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => auth()->user()->id,
                        'user_id'   => $task->user_id,
                    ]);

                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }

        ///////////////////////////////////////////////////////

        $customerID = $req->customer_id;

        //  return response()->json(['req'=>$customerID]);

        $updatereq = DB::table('requests')->where('id', $reqID)->update([
            'user_id'                 => auth()->user()->id,
            'statusReq'               => 0,
            'agent_date'              => carbon::now(),
            'class_id_agent'          => null,
            'is_stared'               => 0,
            'is_followed'             => 0,
            'add_to_stared'           => null,
            'add_to_followed'         => null,
            'isUnderProcFund'         => 0,
            'isUnderProcMor'          => 0,
            'recived_date_report'     => null,
            'recived_date_report_mor' => null,
        ]);

        if ($req->collaborator_id == null) {
            $updatecust = DB::table('customers')->where('id', $customerID)->update([
                'user_id' => auth()->user()->id,
            ]);
        }

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        DB::table('request_histories')->insert([
            'title'          => RequestHistory::TITLE_MOVE_REQUEST,
            'user_id'        => $prev_user,
            'recive_id'      => auth()->user()->id,
            'class_id_agent' => $req->class_id_agent,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $reqID,
            'user_switch_id' => $userSwitch,
            'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Rjected Request OR Customer Not Want'),
        ]);

        #move customer's messages to new agent
        MyHelpers::movemessage($customerID, auth()->user()->id, $prev_user);

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($reqID);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($reqID);
    }

    public static function admin_trans($id, $template)
    {

        return __("language.{$template}");
        $output = '';

        $user = User::find($id);
        $local = $user->locale;
        $lan = '_language.';

        $output = trans($local.''.$lan.''.$template);

        return $output;
    }

    public static function moveArchivedRequest($req)
    {

        $reqID = $req->id;

        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

        /*
        #to get all tasks that related to this request
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs) {

                $query->where(function ($query) use ($reqID) {
                    $query->where('tasks.req_id',  $reqID);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                });
            })->pluck('id')->toArray();


        #set as uncompleted tasks
        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('status', [0, 1, 2])
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 4
                ]);

            if (count($getAllIdsInQualityReqs) > 0) {

                $completeReq = DB::table('quality_reqs')
                    ->whereIn('id', $getAllIdsInQualityReqs)
                    ->update([
                        'status' => 3
                    ]);

                if (MyHelpers::checkQualityReq($reqID)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($reqID, $req->statusReq, $req->user_id, $req->class_id_agent);
                }
            }
        }

        */
        $reqInfo = DB::table('requests')->where('id', $reqID)->first();
        $prev_user = $reqInfo->user_id;

        /*
        #move all curent tasks to new agent
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id',  $reqID);
                    $query->where('tasks.recive_id',  $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id',  $prev_user);
                });
            })
            ->whereIn('status', [0, 1, 2])
            ->pluck('id')->toArray();


        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 0,
                    'recive_id' => auth()->user()->id,
                    'created_at' => carbon::now(),
                ]);
        }
        #
        */

        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => auth()->user()->id,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK

        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);

            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

            foreach ($tasks as $task) {

                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => auth()->user()->id,
                        'user_id'   => $task->user_id,
                    ]);

                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }

        ///////////////////////////////////////////////////////

        $customerID = $req->customer_id;

        //  return response()->json(['req'=>$customerID]);
        $isFreeze = $req->is_freeze;
        DB::table('requests')->where('id', $reqID)->update([
            'user_id'                 => auth()->user()->id,
            'statusReq'               => 0,
            'agent_date'              => carbon::now(),
            'class_id_agent'          => null,
            'is_stared'               => 0,
            'is_followed'             => 0,
            'add_to_stared'           => null,
            'add_to_followed'         => null,
            'add_to_archive'          => null,
            'remove_from_archive'     => null,
            'isUnderProcFund'         => 0,
            'isUnderProcMor'          => 0,
            'recived_date_report'     => null,
            'recived_date_report_mor' => null,
            'is_freeze'               => 0,
        ]);

        if ($req->collaborator_id == null) {
            $updatecust = DB::table('customers')->where('id', $customerID)->update([
                'user_id' => auth()->user()->id,
            ]);
        }

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }
        $content = $isFreeze ? RequestHistory::CONTENT_AGENT_TAKE_FROZEN_REQUEST : RequestHistory::CONTENT_ARCHIVED_BASKET;
        $title = $isFreeze ? RequestHistory::MOVE_FROM_FREEZE : RequestHistory::TITLE_MOVE_REQUEST;
        DB::table('request_histories')->insert([
            'title'          => $title,
            'user_id'        => $prev_user,
            'recive_id'      => auth()->user()->id,
            'class_id_agent' => $req->class_id_agent,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $reqID,
            'user_switch_id' => $userSwitch,
            'content'        => $content,
        ]);

        #move customer's messages to new agent
        MyHelpers::movemessage($customerID, auth()->user()->id, $prev_user);

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($reqID);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($reqID);
    }

    public static function moveRequestExistedInNeedAction($req)
    {
        $reqID = $req->id;
        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();
        /*
        #to get all tasks that related to this request
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs) {

                $query->where(function ($query) use ($reqID) {
                    $query->where('tasks.req_id',  $reqID);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                });
            })->pluck('id')->toArray();


        #set as uncompleted tasks
        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('status', [0, 1, 2])
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 4
                ]);

            if (count($getAllIdsInQualityReqs) > 0) {

                $completeReq = DB::table('quality_reqs')
                    ->whereIn('id', $getAllIdsInQualityReqs)
                    ->update([
                        'status' => 3
                    ]);

                if (MyHelpers::checkQualityReq($reqID)) {
                    $checkAdding = MyHelpers::checkBeforeQualityReq($reqID, $req->statusReq, $req->user_id, $req->class_id_agent);
                }
            }
        }

        */
        //$req = DB::table('requests')->where('id', $reqID)->first();
        $prev_user = $req->user_id;
        /*
        #move all curent tasks to new agent
        $getAllTasksIds = DB::table('tasks')
            ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id',  $reqID);
                    $query->where('tasks.recive_id',  $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id',  $prev_user);
                });
            })
            ->whereIn('status', [0, 1, 2])
            ->pluck('id')->toArray();


        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')
                ->whereIn('id', $getAllTasksIds)
                ->update([
                    'status' => 0,
                    'recive_id' => auth()->user()->id,
                    'created_at' => carbon::now(),
                ]);
        }
        #
        */

        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => auth()->user()->id,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK

        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);

            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

            foreach ($tasks as $task) {

                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => auth()->user()->id,
                        'user_id'   => $task->user_id,
                    ]);

                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }

        ///////////////////////////////////////////////////////

        $customerID = $req->customer_id;

        //  return response()->json(['req'=>$customerID]);
        $isFreeze = $req->is_freeze;
        DB::table('requests')->where('id', $reqID)->update([
            'user_id'                 => auth()->user()->id,
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
            'is_freeze'               => 0,
        ]);

        if ($req->collaborator_id == null) {
            DB::table('customers')->where('id', $customerID)->update([
                'user_id' => auth()->user()->id,
            ]);
        }
        $userSwitch = session('existing_user_id');

        $content = $isFreeze ? RequestHistory::CONTENT_AGENT_TAKE_FROZEN_REQUEST : RequestHistory::CONTENT_EXISTED_IN_NEED_ACTION;
        $title = $isFreeze ? RequestHistory::MOVE_FROM_FREEZE : RequestHistory::TITLE_MOVE_REQUEST;
        DB::table('request_histories')->insert([
            'title'          => $title,
            'user_id'        => $prev_user,
            'recive_id'      => auth()->user()->id,
            'class_id_agent' => $req->class_id_agent,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $reqID,
            'user_switch_id' => $userSwitch,
            'content'        => $content,
        ]);

        MyHelpers::updateNeedActionReq($reqID); // update need action status

        #move customer's messages to new agent
        MyHelpers::movemessage($customerID, auth()->user()->id, $prev_user);

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($reqID);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($reqID);
    }

    public static function updateNeedActionReq($req_id)
    {

        $getNeedReq = RequestNeedAction::where('req_id', $req_id)->where('status', 0)->get()->pluck('id');

        $updateReq = RequestNeedAction::whereIn('id', $getNeedReq)->update(['status' => 1]);
    }

    public static function movePendingRequestByAgent($pending_id, $agent_id, $content)
    {
        $request_data = PendingRequest::where('id', $pending_id)->first();

        if (empty($request_data)) {
            return -1;
        } // not existed

        if ($request_data->collaborator_id == null) { // because customer is related to collobrator if it is

            $customerID = $request_data->customer_id;
            customer::where('id', $customerID)->update(['user_id' => $agent_id]);
        }

        $reqID = DB::table('requests')->insertGetId([
            'source'          => $request_data->source,
            'req_date'        => $request_data->req_date,
            'created_at'      => $request_data->created_at,
            'user_id'         => $agent_id,
            'customer_id'     => $request_data->customer_id,
            'collaborator_id' => $request_data->collaborator_id,
            'statusReq'       => $request_data->statusReq,
            'joint_id'        => $request_data->joint_id,
            'fun_id'          => $request_data->fun_id,
            'searching_id'    => $request_data->searching_id,
            'real_id'         => $request_data->real_id,
            'agent_date'      => carbon::now(),
        ]);

        if (!empty($reqID)) {
            if ($request_data->source == 2) {
                if ($request_data->collaborator_id == 17) {
                    $record = MyHelpers::addNewReordOtared($reqID, $request_data->created_at);
                } // to add new history record
                else {
                    if ($request_data->collaborator_id == 77) {
                        $record = MyHelpers::addNewReordTamweelk($reqID, $request_data->created_at);
                    }
                }
            }
            else {
                $record = MyHelpers::addNewReordWebsite($reqID, $agent_id, $request_data->created_at);
            }

            $record = MyHelpers::addNewReordPending($reqID, $agent_id, $content);
            $deletereq = DB::table('pending_requests')->where('id', $pending_id)->delete();

            return ($reqID);
        }

        return false;
    }

    public static function addNewReordOtared($reqID, $userID = null, $date = null, $content = null)
    {

        if ($date == null) {
            $date = (Carbon::now('Asia/Riyadh'));
        }

        return DB::table('request_histories')->insert([ // add to request history
                                                        'title'        => MyHelpers::guest_trans('New Request Added From Otared'),
                                                        'user_id'      => $userID,
                                                        'history_date' => $date,
                                                        'content'      => $content,
                                                        'req_id'       => $reqID,
        ]);
    }

    public static function addNewReordTamweelk($reqID, $userID = null, $date = null, $content = null)
    {

        if ($date == null) {
            $date = (Carbon::now('Asia/Riyadh'));
        }

        return DB::table('request_histories')->insert([ // add to request history
                                                        'title'        => MyHelpers::guest_trans('New Request Added From Tamweelk'),
                                                        'user_id'      => $userID,
                                                        'history_date' => $date,
                                                        'content'      => $content,
                                                        'req_id'       => $reqID,
        ]);
    }

    public static function addNewReordWebsite($reqID, $userID, $date = null, $content = null)
    {
        // Request history
        if ($date == null) {
            $date = (Carbon::now('Asia/Riyadh'));
        }

        return DB::table('request_histories')->insert([ // add to request history
                                                        'title'        => MyHelpers::guest_trans('New Request Added From Web'),
                                                        'user_id'      => null,
                                                        'recive_id'    => $userID,
                                                        'history_date' => $date,
                                                        'content'      => $content,
                                                        'req_id'       => $reqID,
        ]);
    }

    public static function checkClassType($class_id)
    {

        $classInfo = DB::table('classifcations')->where('id', $class_id)->where('type', 0) // negative
        ->first();

        if (!empty($classInfo)) {
            return true;
        }
        return false;
    }

    public static function checkStatus($status)
    {

        $findValue = MyHelpers::witchStatus($status);

        $status = DB::table('settings')->where('option_name', 'LIKE', 'statusQualityReq_%')->where('option_value', 'true')->where('display_name', $findValue)->first();

        if (empty($status)) {
            return false;
        }
        return true;
    }

    public static function witchStatus($number)
    {
        $s = [
            0  => 'new req',
            1  => 'open req',
            2  => 'archive in sales agent req',
            3  => 'wating sales manager req',
            4  => 'rejected sales manager req',
            //5 =>   'archive in sales manager req' ,
            5  => 'wating sales manager req',
            6  => 'wating funding manager req',
            7  => 'rejected funding manager req',
            // 8 =>   'archive in funding manager req' ,
            8  => 'wating funding manager req',
            9  => 'wating mortgage manager req',
            10 => 'rejected mortgage manager req',
            // 11 =>   'archive in mortgage manager req' ,
            11 => 'wating mortgage manager req',
            12 => 'wating general manager req',
            13 => 'rejected general manager req',
            14 => 'wating general manager req',
            16 => 'Completed',

        ];

        return (($s[$number]));
    }

    public static function checkClass($class)
    {

        $class = DB::table('classifcations')->where('status', 'true')->where('id', $class)->first();

        if (empty($class)) {
            return false;
        }
        return true;
    }

    public static function checkRejectedAndArchived()
    {

        $status = DB::table('settings')->where('option_name', 'statusQualityReq_archiveAndRejected')->where('option_value', 'true')->first();

        if (empty($status)) {
            return false;
        }

        return true;
    }

    public static function checkQualityMatchWithAgent($user_id)
    {

        $qualityAgents = DB::table('agent_qualities')->where('Quality_id', auth()->user()->id)->where('Agent_id', $user_id) //archive in admin shall not appeared
        ->first();

        if (empty($qualityAgents)) {
            return false;
        }
        else {
            return true;
        }
    }

    public static function checkBeforeQualityReq($reqID, $status, $user_id, $class)
    {

        $request_conditions = DB::table('request_conditions')->get();
        if (count($request_conditions) == 0){
            return true;
        }
        if ($request_conditions) {

            foreach ($request_conditions as $request_condition) {

                $checkClass = MyHelpers::checkClassForQualityReq($request_condition->id, $class);
                $checkUsers = MyHelpers::checkUserForQualityReq($request_condition->id, $user_id);
                $checkStatus = MyHelpers::checkStatusForQualityReq($request_condition->id, $status);

                if (($checkClass == true) && ($checkUsers == true) && ($checkStatus == true)) {

                    if (($request_condition->timeDays == null) || ($request_condition->timeDays == 0)) {

                        $quality_id = MyHelpers::findNextQuality();
                        $newReq = quality_req::create([
                            'req_id'     => $reqID,
                            'con_id'     => $request_condition->id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                            'user_id'    => $quality_id,
                        ]);
                        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($quality_id)) {
                            MyHelpers::addDailyPerformanceRecord($quality_id);
                        }
                        MyHelpers::incrementDailyPerformanceColumn($quality_id, 'received_basket',$reqID);

                        DB::table('notifications')->insert([ // add notification to send user
                                                             'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                             'recived_id' => $newReq->user_id,
                                                             'created_at' => (Carbon::now('Asia/Riyadh')),
                                                             'type'       => 0,
                                                             'req_id'     => $newReq->id,
                        ]);

                        DB::table('request_histories')->insert([
                            'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                            'user_id'      => null,
                            'recive_id'    => $quality_id,
                            'history_date' => (Carbon::now('Asia/Riyadh')),
                            'req_id'       => $reqID,
                            'content'      => null,
                        ]);

                        $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $newReq->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification

                    }
                    else {
                        $newReq = quality_req::create([
                            'req_id'       => $reqID,
                            'con_id'       => $request_condition->id,
                            'created_at'   => (Carbon::now('Asia/Riyadh')),
                            'user_id'      => null,
                            'allow_recive' => 0,
                        ]);

                    }

                    #------remove need action req if existed(will nt allowed to recived same request with admin & quality)
                    $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($reqID);
                    if ($needReq != 'false') {
                        MyHelpers::removeNeedActionReq($needReq->id);
                    }

                    #----------------------------
                    return true;
                }
            }
            return false;
        }
        else {
            return false;
        }
    }

    public static function checkClassForQualityReq($req_con, $class)
    {

        $class_cons = DB::table('class_conditions')->where('cond_id', $req_con)->where('cond_type', 0)->get();

        if ($class_cons->count() > 0) {

            foreach ($class_cons as $class_con) {
                if ($class_con->class_id == $class) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    public static function checkUserForQualityReq($req_con, $user_id)
    {

        $user_cons = DB::table('user_conditions')->where('cond_id', $req_con)->where('cond_type', 0)->get();

        if ($user_cons->count() > 0) {

            foreach ($user_cons as $user_con) {
                if ($user_con->user_id == $user_id) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    public static function checkStatusForQualityReq($req_con, $status)
    {

        $status_cons = DB::table('status_conditions')->where('cond_id', $req_con)->where('cond_type', 0)->get();

        if ($status_cons->count() > 0) {

            foreach ($status_cons as $status_con) {
                if ($status_con->status == $status) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    public static function findNextQuality()
    {
        $user_id = null;

        $last_req = DB::table('quality_reqs')->where('allow_recive', 1)->orderBy('created_at', 'desc')->first(); // latest request_id

        if ($last_req) {

            $last_user_id = $last_req->user_id;

            if ($last_user_id) {
                $maxValue = DB::table('users')->where('role', 5)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
                $minValue = DB::table('users')->where('role', 5)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

                if ($last_user_id == $maxValue) {
                    $user_id = $minValue;
                }
                else {
                    // get next user id
                    $user_id = User::where('id', '>', $last_user_id)->where('role', 5)->where('allow_recived', 1)->where('status', 1)->min('id');
                    if (!$user_id) {
                        $user_id = User::where('role', 5)->where('allow_recived', 1)->where('status', 1)->min('id');
                    }
                }
            }
            else {
                // get next user id
                $user_id = User::where('role', 5)->where('allow_recived', 1)->where('status', 1)->min('id');
            }
        }
        else {
            // get next user id
            $user_id = User::where('role', 5)->where('allow_recived', 1)->where('status', 1)->min('id');
        }

        return $user_id;
    }

    public static function sendEmailNotifiaction($emailName, $userId, $subject, $content)
    {
        $checkEmail = User::find($userId)->email;
        if ($checkEmail != null) {
            $email = Email::where('email_name', $emailName)->first();
            if ($email) {
                if (EmailUser::where(['user_id' => $userId, 'email_id' => $email->id])->count() > 0) {
                    try {
                        Mail::to(User::find($userId)->email)->send(new WastaMailNotification($subject, $content));
                    }
                    catch (Exception $exception) {
                    }
                }
            }
        }
    }

    public static function checkDublicateOfNeedActionReqWithStatusOnly($req_id)
    {

        $needReq = RequestNeedAction::where('req_id', $req_id)->where('status', 0)->first();

        if ($needReq) {
            return $needReq;
        }

        return 'false';
    }

    public static function removeNeedActionReq($id)
    {

        $removeReq = DB::table('request_need_actions')->where('id', $id)->delete();
    }

    public static function checkRequestRecord($req_id)
    {
        //get last update of request
        $records = DB::table('req_records')->where('req_id', $req_id)->orderBy('updateValue_at', 'desc')->first();

        if (!empty($records)) {
            $timeToLeave = DB::table('settings')->where('option_name', 'time_requestWithoutUpdate')->first()->option_value;
            $created = new Carbon($records->updateValue_at);
            $now = Carbon::now();
            $difference = ($created->diff($now)->days > $timeToLeave);
            return $difference;
        }

        return false;
    }

    public static function checkClassOfRequestWithoutUpdate($req_id)
    {

        $checkClassOfRequestWitoutUpdate = DB::table('classification_for_request_without_update')->get()->pluck('class_id')->toArray();
        if (count($checkClassOfRequestWitoutUpdate) == 0) //no class condition
        {
            return true;
        }

        $reqClass = DB::table('requests')->where('id', $req_id)->first()->class_id_agent;

        if (in_array($reqClass, $checkClassOfRequestWitoutUpdate)) {
            return true;
        }

        return false;
    }

    public static function updateQualityReqToComplete($id)
    {
        DB::table('quality_reqs')->where('id', $id)->update(['status' => 3]);
    }

    public static function checkQualityReqWithArchivedUser($req_id)
    {
        $request = DB::table('quality_reqs')
            ->join('users', 'users.id', '=', 'quality_reqs.user_id')
            ->where('users.status', 0)->whereIn('quality_reqs.status', [0, 1, 2, 5])
            ->where('quality_reqs.req_id', $req_id)->select('quality_reqs.*')
            ->first();

        if (!empty($request)) {
            return $request->id;
        }

        return false;
    }

    public static function checkQualityUser($req_id, $qualityID)
    {

        $request = DB::table('quality_reqs')->where('req_id', $req_id)->first();

        if (!empty($request)) {
            if ($request->user_id == $qualityID) {
                return "true";
            }
            else {
                return $request->user_id;
            }
        }
        else {
            return "true";
        }
    }

    //***************************************************************************

    //***************************************************************************

    public static function checkConditionMatch($id)
    {
        //return true;
        $requestInfo = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where('quality_reqs.id', $id)->select('quality_reqs.con_id', 'requests.*')->first();

        $check = false;
        $con_id = null;
        $request_conditions = DB::table('request_conditions')->get();
        if (count($request_conditions) == 0){
            return true;
        }
        if (!empty($requestInfo)) {
            foreach ($request_conditions as $condition) {
                $now = Carbon::now();
                $date = Carbon::parse($requestInfo->agent_date);
                $checkValue = $date->diffInDays($now);

                $timedaysWithActiveCounter = $condition->timeDays + MyHelpers::getQualityCounter();
                //dd(MyHelpers::getQualityCounter(),$timedaysWithActiveCounter,$checkValue);
                $checkTime = $timedaysWithActiveCounter <= $checkValue;
                $checkClass = MyHelpers::checkClassForQualityReq($condition->id, $requestInfo->class_id_agent);
                $checkUsers = MyHelpers::checkUserForQualityReq($condition->id, $requestInfo->user_id);
                $checkStatus = MyHelpers::checkStatusForQualityReq($condition->id, $requestInfo->statusReq);

                if (($checkTime == true) && ($checkClass == true) && ($checkUsers == true) && ($checkStatus == true)) {
                    $check = true;
                    $con_id = $condition->id;
                    break;
                }

            }
        }
        if ($check) {
            return $condition->id;
        }
        return false;
    }

    public static function getQualityCounter()
    {
        $active = DB::table('settings')->where('option_name', 'qualityRequest_counterDay')->first();

        if (empty($active)) {
            return 0;
        }
        return $active->option_value;
    }
    public static function getQualityActivateEndDate()
    {
        $active = DB::table('settings')->where('option_name', 'qualityRequest_endDate')->first();

        if (empty($active)) {
            return null;
        }
        return $active->option_value;
    }

    public static function checkIfTimedayChanheOnly($coun_id, $salesAgents, $classifcations, $stutuses, $timeDays)
    {

        $check = true;

        $condition = requestConditions::where('id', $coun_id)->first();

        if ($condition->timeDays != $timeDays) {
            $check = false;
        }

        foreach ($salesAgents as $salesAgent) {
            $agent = userCondition::where('cond_id', $coun_id)->where('user_id', $salesAgent)->first();

            if (!$agent) {
                return true;
            }
        }

        foreach ($classifcations as $classifcation) {
            $class = classCondition::where('cond_id', $coun_id)->where('class_id', $classifcation)->first();

            if (!$class) {
                return true;
            }
        }

        foreach ($stutuses as $stutuse) {
            $status = statusCondition::where('cond_id', $coun_id)->where('status', $stutuse)->first();

            if (!$status) {
                return true;
            }
        }

        return $check;
    }

    public static function checkNoOfAskRequest()
    {
        $noAskRequest = DB::table('moved_requests')->where('user_id', auth()->user()->id)->where('isPending', 0)->whereDate('created_at', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->count();

        $eachDay = DB::table('settings')->where('option_name', 'askRequest_eachDay')->first();

        if ($noAskRequest >= $eachDay->option_value) {
            return false;
        }
        return true;
    }

    public static function checkNoOfAskRequestTransBasket()
    {
        $noAskRequest = DB::table('moved_requests')->where('user_id', auth()->user()->id)->where('isPending', 0)->whereDate('created_at', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->count();
        $eachDay = DB::table('settings')->where('option_name', 'askRequest_eachDay')->first();

        if ($noAskRequest >= (setting('trans_basket_per_day') + $eachDay->option_value)) {
            return false;
        }
        return true;
    }

    public static function checkNoofMovePendingRequest()
    {

        $noAskRequest = DB::table('moved_requests')->where('user_id', auth()->user()->id)->where('isPending', 1)->whereDate('created_at', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->count();

        $eachDay = DB::table('settings')->where('option_name', 'movePendingByAgent_dailyReq')->first();

        if ($noAskRequest >= $eachDay->option_value) {
            return false;
        }
        return true;
    }

    public static function addNewMoveRequestRecord($isPending = 0)
    {
        movedRequest::create([
            'created_at' => (Carbon::now('Asia/Riyadh')),
            'user_id'    => auth()->user()->id,
            'isPending'  => $isPending,
        ]);
    }

    public static function checkRecivedReqsCount()
    {
        return DB::table('requests')->where('requests.user_id', auth()->user()->id)->where(function ($query) {

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
                $query->where('prepayments.isSentSalesAgent', 1);
                $query->where('requests.type', 'شراء-دفعة');
            });
        })->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->count();

    }

    public static function checkAgentRecive()
    {

        $userInfo = DB::table('users')->where('id', auth()->user()->id)->first();

        if ($userInfo->allow_recived == 0) {
            return false;
        }
        return true;
    }

    public static function extractFunding($req_id)
    {

        $agent = DB::table('requests')->where('requests.id', '=', $req_id)->where('statusReq', '!=', 30) //archive in admin shall not appeared
        ->join('users', 'users.id', '=', 'requests.user_id')->first();

        $managerUser = DB::table('users')->where('id', '=', $agent->sales_manager_id)->first();

        if ($managerUser) {
            return $managerUser->funding_mnager_id;
        }
        else {
            return null;
        }
    }

    public static function extractUsersFunding($id)
    {

        $myusers = DB::table('users')->where('funding_mnager_id', $id)->get(); // get sales managers

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                $users[] = DB::table('users')->where('manager_id', $myuser->id)->get(); // get sales agents that belong to these sales managers
            }

            return $users;
        }

        return $myusers;
    }

    public static function extractMortgage($req_id)
    {

        $agent = DB::table('requests')->where('requests.id', '=', $req_id)->join('users', 'users.id', '=', 'requests.user_id')->first();

        if ($agent->type == "تساهيل") {
            return $agent->mortgage_mnager_id;
        }

        $managerUser = DB::table('users')->where('id', '=', $agent->sales_manager_id)->first();

        if (!empty($managerUser)) {
            return $managerUser->mortgage_mnager_id;
        }
        return null;
    }

    public static function extractUsersMortgage($id)
    {

        $myusers = DB::table('users')->where('mortgage_mnager_id', $id)->get(); // get sales managers

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                $users[] = DB::table('users')->where('manager_id', $myuser->id)
                    //->ORwhere('mortgage_mnager_id',   auth()->user()->id)
                    ->get(); // get sales agents that belong to these sales managers
            }

            array_push($users, DB::table('users')->where('mortgage_mnager_id', auth()->user()->id)->where('role', 0)->get());

            return $users;
        }

        return $myusers;
    }

    //stop time of reciving quality reqs

    public static function checkActiveQualityReqs()
    {
        $active = DB::table('settings')->where('option_name', 'qualityRequest_active')->where('option_value', 'true')->first();

        if (empty($active)) {
            return false;
        }
        return true;
    }

    public static function pushPWA($user_id, $subject, $body, $button, $userType, $reqType, $reqID)
    {
        Notification::send(User::all()->where('id', $user_id)->first(), new SendNotification($subject, $body, $button, 'https://alwsata.com.sa/'.$userType.'/'.$reqType.'/'.$reqID));
    }

    public static function testSendEmailNotifiaction($email, $subject, $content)
    {

        Mail::to($email)->send(new WastaMailNotification($subject, $content));
    }

    public static function sendEmailNotifiactionCustomer($customerId, $subject, $content)
    {

        $customer = customer::find($customerId);
        if ($customer->email != null) {
            try {
                Mail::to($customer->email)->send(new WastaMailNotification($subject, $content));
            }
            catch (Exception $exception) {
            }
        }
    }

    public static function sendEmailNotifiactionByEmailOnly($email, $subject, $content)
    {
        Mail::to($email)->send(new WastaMailNotification($subject, $content));
    }

    public static function sendNotify($receiver_id, $receiver_type = 'web', $notification, $req = null, $req_type = 1, $type = null, $task_id = null, $reminder_date = null)
    {
        return notify::create([
            'recived_id'    => $receiver_id,
            'receiver_type' => $receiver_type,  // web or customer
            'value'         => $notification,
            'req_id'        => $req,
            'request_type'  => $req_type, // 1:tamweel request , 2:property request
            'created_at'    => (Carbon::now('Asia/Riyadh')),
            'type'          => 1,
            'task_id'       => $task_id,
            'status'        => 2,
            'reminder_date' => $reminder_date,
        ]); // return true if succesful

    }

    public static function addNewNotify($reqID, $userID)
    {

        return DB::table('notifications')->insert([ // add notification to send user
                                                    'value'      => MyHelpers::guest_trans('New Request Added'),
                                                    'recived_id' => $userID,
                                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                                    'type'       => 0,
                                                    'req_id'     => $reqID,
        ]); // return true if succesful

    }

    public static function addNewReordExcel($reqID, $userID)
    {
        // Request Record history
        return DB::table('request_histories')->insert([ // add to request history
                                                        'title'        => MyHelpers::guest_trans('New Request Added by Admin'),
                                                        'user_id'      => $userID,
                                                        'history_date' => (Carbon::now('Asia/Riyadh')),
                                                        'req_id'       => $reqID,
        ]);
    }

    public static function getNextAgentForRequest()
    {
        return getLastAgentOfDistribution();
        // To get user_id for last request
        $last_req_id = DB::table('requests')->max('id');                       // latest request_id
        $last_req = DB::table('requests')->where('id', $last_req_id)->first(); // latest request object
        $last_user_id = $last_req ? $last_req->user_id : null;

        $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            if ($user_id == null) {
                $user_id = User::where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            }
        }

        return $user_id;
    }

    public static function reciveReqCountSalesAgent($userID)
    {

        return $received_reqs_count = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

            $query->where(function ($query) {
                $query->whereIn('statusReq', [0, 1, 4]);
            });

            $query->orWhere(function ($query) {
                $query->where('statusReq', 19);
                $query->where('type', 'رهن-شراء');
                $query->where('requests.isSentSalesAgent', 1);
            });

            $query->orWhere(function ($query) {
                $query->where('prepayments.payStatus', 4);
                $query->where('prepayments.isSentSalesAgent', 1);
            });
        })->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->count();
    }

    public static function compReqCountSalesAgent($userID)
    {

        return $com_reqs_count = DB::table('requests')->where('requests.user_id', $userID)->where(function ($query) {

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
    }

    public static function archReqCountSalesAgent($userID)
    {

        return $arch_reqs_count = DB::table('requests')->where('requests.user_id', $userID)->where('statusReq', 2) //archived in sales agent
        ->count();
    }

    public static function allReqCountSalesManager($userID)
    {

        //$myusers = DB::table('users')->where('manager_id',  $userID)->get();
        //  dd( $myusers);
        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })->where('requests.sales_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function dailyReqCountSalesManager($userID)
    {

        $todayDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $myusers = DB::table('users')->where('manager_id', $userID)->get();
        //  dd( $myusers);
        $requests = [];

        if (!empty($myusers[0])) {

            foreach ($myusers as $myuser) {
                $requests[] = DB::table('requests')->where('requests.user_id', $myuser->id)
                    //->whereIn('statusReq', [3,7,10]) //wating for sales manager
                    // ->where('requests.req_date',   $todayDate)
                    // ->where('requests.req_date','>','2019-12-31')
                    ->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name')->orderBy('req_date', 'DESC')->get();
            }

            $counter = 0;
            foreach ($requests as $request) {
                foreach ($request as $req) {
                    $counter++;
                }
            }

            return $counter;
        }

        return 0;
    }

    public static function rejReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->whereIn('statusReq', [7, 10]) //rejected from mortgage & fundinfg managers
            ->where('requests.sales_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function purReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })->where('type', 'شراء')->where('requests.sales_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function morReqCountSalesManager($userID)
    {
        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {
                $query->where('statusReq', 3) //wating for sales manager
                ->orWhere('isSentSalesManager', 1); //yes sent

            })->where('type', 'رهن')->where('requests.sales_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function morPurReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where('type', 'رهن-شراء')->where('isSentSalesManager', 1)->where('requests.sales_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function prepayReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.isSentSalesManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.sales_manager_id', $userID)->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function reciveReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
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
            })->where('requests.sales_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)
            //->where('isSentSalesManager', 1) //yes sent
            ->get()->count();

        return $requests;
    }

    public static function comReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)

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
            })->where('requests.sales_manager_id', $userID)->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date',
                'DESC')->get()->count();

        return $requests;
    }

    public static function archReqCountSalesManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $myuser->id)
            ->where('requests.sales_manager_id', $userID)->where('statusReq', 5) //archived in sales manager
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->get()->count();

        return $requests;
    }

    public static function allReqCountFundingManager($userID)
    {

        //$myusers = MyHelpers::extractUsersFunding($userID);

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            //->where('statusReq', 6) //wating for funding manager approval
            ->where('isSentFundingManager', 1) //yes sent
            //  ->where('isUnderProcFund', 0) //still not under funding process
            //  ->where('type', 'شراء') //funding will recive only purchase reqs
            ->where('requests.funding_manager_id', $userID)->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        return $requests;
    }

    public static function reciveReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->where('type', 'شراء');
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
            })->where('requests.funding_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('isUnderProcFund', 0) //still not under funding process
            ->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)
            //->where('isSentFundingManager', 1) //yes sent
            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->count();

        return $requests;
    }

    public static function rejReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
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
            })->where('requests.funding_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')

            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function underReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [6, 13]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->where('type', 'شراء');
                    $query->orwhere('requests.type', 'شراء-دفعة');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [21, 25, 17]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentFundingManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [0, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.funding_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('isUnderProcFund', 1) //still not under funding process
            //->where('isSentFundingManager', 1) //yes sent
            //->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function comReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
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
            })->where('requests.funding_manager_id', $userID)->where('isUnderProcFund', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            // ->where('type', 'شراء') //funding will recive only purchase reqs
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        return $requests;
    }

    public static function prepayReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.payStatus', '!=', null);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.funding_manager_id', $userID)->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function archReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('statusReq', 8) //archived in funding manager
            ->where('isSentFundingManager', 1)->where('requests.funding_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function allReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->whereIn('requests.user_id', $fn)
            ->whereIn('type', ['رهن', 'رهن-شراء', 'شراء-دفعة', 'تساهيل']) //mortgage will recive only mortgage reqs

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('prepayments.isSentMortgageManager', 1);
                });
            })->where('requests.mortgage_manager_id', $userID)->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->select('requests.*',
                'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->count();

        return $requests;
    }

    public static function underReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [9, 13, 30, 33]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('requests.type', ['رهن', 'تساهيل']);
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [23]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [5, 10]);
                    $query->where('prepayments.isSentMortgageManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.mortgage_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('requests.isUnderProcMor', 1) //still not under funding process
            ->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function morPurReqCountFundingManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('type', 'رهن-شراء')->where('requests.funding_manager_id', $userID)->where('isSentFundingManager', 1)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->count();

        return $requests;
    }

    public static function morPurReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('type', 'رهن-شراء')
            // ->where('isSentMortgageManager', 1)
            ->where('requests.mortgage_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function prepayReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('prepayments.isSentMortgageManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })
            //  ->where('requests.isUnderProcMor', 0)
            ->where('requests.mortgage_manager_id', $userID)->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->join('prepayments', 'prepayments.req_id', '=', 'requests.id')->select('requests.*', 'customers.name', 'prepayments.payStatus')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function reciveReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [9, 13, 30, 33]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'تساهيل']);
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('statusReq', [17, 20]);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', '!=', 2);
                    $query->whereIn('prepayments.payStatus', [5, 10]);
                    $query->where('prepayments.isSentMortgageManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.mortgage_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('requests.isUnderProcMor', 0) //still not under funding process
            ->where('requests.is_canceled', 0)->where('requests.is_followed', 0)->where('requests.is_stared', 0)
            // ->where('isSentMortgageManager', 1)
            //->where('type', 'رهن') //mortgage will recive only mortgage reqs
            ->count();

        return $requests;
    }

    public static function records($reqID, $coloum, $value)
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

    public static function comReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereNotIn('statusReq', [9, 13, 30, 33, 11]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'تساهيل']);
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('statusReq', [17, 20]);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereNotIn('prepayments.payStatus', [5, 10]);
                    $query->where('prepayments.isSentMortgageManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.mortgage_manager_id', $userID)->where('isUnderProcMor', 0) //still not under funding process
            ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function archReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)
            ->where('statusReq', 11) //archived in mortgage manager
            ->where('requests.mortgage_manager_id', $userID)->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function rejReqCountMortgageManager($userID)
    {

        $requests = DB::table('requests')
            //->where('requests.user_id',   $user->id)

            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [13, 33]); // (6:wating for mortgage manager ,13:rejected from general maanger );
                    $query->whereIn('type', ['رهن', 'تساهيل']);
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 20);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentMortgageManager', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('prepayments.payStatus', 10);
                    $query->where('prepayments.isSentMortgageManager', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->where('requests.mortgage_manager_id', $userID)->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('id', 'DESC')->count();

        return $requests;
    }

    public static function openReqWillOpenNotify($reqID)
    {
        $getAllNewNotifys = DB::table('notifications')->where('req_id', '=', $reqID)->where('recived_id', '=', (auth()->user()->id))->where('status', '=', 0)->pluck('notifications.id');

        if (count($getAllNewNotifys) > 0) {
            DB::table('notifications')->whereIn('id', $getAllNewNotifys)->update(['status' => 1]);
        } // set as open notify

    }

    public static function checkCompleteRequestFilds($request)
    {
        $missing = [];

        //********CustomerInfo*******
        $check = MyHelpers::checkNullRequestFild($request->name);
        if ($check) {
            $missing[] = 'name';
        }
        $check = MyHelpers::checkNullRequestFild($request->sex);
        if ($check) {
            $missing[] = 'sex';
        }
        $check = MyHelpers::checkNullRequestFild($request->mobile);
        if ($check) {
            $missing[] = 'mobile';
        }
        //
        $check = MyHelpers::checkNullRequestFild($request->age_years);
        if ($check) {
            $missing[] = 'age_years';
        }
        $check = MyHelpers::checkNullRequestTwoFild($request->birth, $request->birth_hijri);
        if ($check) {
            $missing[] = 'birth_hijri';
        }
        $check = MyHelpers::checkNullRequestFild($request->work);
        if ($check) {
            $missing[] = 'work';
        }
        $check = MyHelpers::checkNullRequestFild($request->salary);
        if ($check) {
            $missing[] = 'salary';
        }
        $check = MyHelpers::checkNullRequestFild($request->salary_source);
        if ($check) {
            $missing[] = 'salary_source';
        }
        $check = MyHelpers::checkNullRequestFild($request->is_support);
        if ($check) {
            $missing[] = 'is_support';
        }
        $check = MyHelpers::checkNullRequestFild($request->has_obligations);
        if ($check) {
            $missing[] = 'has_obligations';
        }
        $check = MyHelpers::checkValueRequestFild($request->has_obligations) && MyHelpers::checkNullRequestFild($request->obligations_value);
        if ($check) {
            $missing[] = 'obligations_value';
        }
        $check = MyHelpers::checkNullRequestFild($request->has_financial_distress);
        if ($check) {
            $missing[] = 'has_financial_distress';
        }
        $check = MyHelpers::checkValueRequestFild($request->has_financial_distress) && MyHelpers::checkNullRequestFild($request->financial_distress_value);
        if ($check) {
            $missing[] = 'financial_distress_value';
        }
        //

        //********RealInfo*******
        $checkRealType = null;
        $check = MyHelpers::checkNullRequestFild($request->realname);
        if ($check) {
            $missing[] = 'realname';
        }
        $check = MyHelpers::checkNullRequestFild($request->realmobile);
        if ($check) {
            $missing[] = 'realmobile';
        }
        $check = MyHelpers::checkNullRequestFild($request->realcity);
        if ($check) {
            $missing[] = 'realcity';
        }
        $check = MyHelpers::checkNullRequestFild($request->realpursuit);
        if ($check) {
            $missing[] = 'realpursuit';
        }
        $check = MyHelpers::checkNullRequestFild($request->realage);
        if ($check) {
            $missing[] = 'realage';
        }
        $check = MyHelpers::checkNullRequestFild($request->realcost);
        if ($check) {
            $missing[] = 'realcost';
        }
        // $check = MyHelpers::checkNullRequestFild($request->real);
        // if ($check) {
        //     $missing[] = 'real';
        // }
        $check = MyHelpers::checkNullRequestFild($request->realstatus);
        if ($check) {
            $missing[] = 'realstatus';
        }
        $check = MyHelpers::checkNullRequestFild($request->realtype);
        if ($check) {
            $checkRealType = $request->realtype;
            $missing[] = 'realtype';
        }

        if ($checkRealType != null && $checkRealType != 'أرض') {
            $check = MyHelpers::checkNullRequestFild($request->realstatus);
            if ($check) {
                $missing[] = 'realstatus';
            }
            $check = MyHelpers::checkNullRequestFild($request->realage);
            if ($check) {
                $missing[] = 'realage';
            }
        }
        $check = MyHelpers::checkNullRequestFild($request->owning_property);
        if ($check) {
            $missing[] = 'owning_property';
        }
        $check = MyHelpers::checkValueRequestType($request->checktype);
        if ($check) {
            $missing[] = 'realmor';
        }
        //

        //********FundingInfo*******
        $check = MyHelpers::checkNullRequestFild($request->funding_source);
        if ($check) {
            $missing[] = 'funding_source';
        }
        $check = MyHelpers::checkNullRequestFild($request->fundingdur);
        if ($check) {
            $missing[] = 'fundingdur';
        }
        $check = MyHelpers::checkNullRequestFild($request->fundingreal);
        if ($check) {
            $missing[] = 'fundingreal';
        }
        $check = MyHelpers::checkNullRequestFild($request->fundingrealp);
        if ($check) {
            $missing[] = 'fundingrealp';
        }
        $check = MyHelpers::checkNullRequestFild($request->dedp);
        if ($check) {
            $missing[] = 'dedp';
        }
        $check = MyHelpers::checkNullRequestFild($request->monthIn);
        if ($check) {
            $missing[] = 'monthIn';
        }
        //

        //********PrepaymentInfo*******
        $check = MyHelpers::checkValueRequestType($request->reqtyp);
        if ($check) {
            $check = MyHelpers::checkValueRequestFild($request->realmor) && MyHelpers::checkNullRequestFild($request->realo);
        }
        if ($check) {
            $missing[] = 'realo';
        }
        //
        // $check = MyHelpers::checkNullRequestFild($request->morpre);
        // if ($check) {
        //     $missing[] = 'morpre';
        // }
        // $check = MyHelpers::checkNullRequestFild($request->morcos);
        // if ($check) {
        //     $missing[] = 'morcos';
        // }
        //

        //********RequestInfo*******
        $check = MyHelpers::checkNullRequestFild($request->reqtyp);
        if ($check) {
            $missing[] = 'reqtyp';
        }
        $check = MyHelpers::checkNullRequestFild($request->reqclass);
        if ($check) {
            $missing[] = 'reqclass';
        }
        $check = MyHelpers::checkNullRequestFild($request->reqcomm);
        if ($check) {
            $missing[] = 'reqcomm';
        }
        $agent_identity_number = $request->get('agent_identity_number', '');
        $check = strlen($agent_identity_number) != 10;
        if ($check) {
            $missing[] = 'agent_identity_number';
        }
        //

        //********Document*******
        $check = MyHelpers::checkDocumentRequest($request->reqID);
        if ($check) {
            $missing[] = 'document';
        }
        //

        return $missing;
    }

    public static function checkNullRequestFild($value)
    {
        if ($value == null || $value == '' || $value == '0' || $value == 'بدون اسم') {
            return true;
        }
        return false;
    }

    public static function checkNullRequestTwoFild($value1, $value2)
    {
        if ($value1 == null && $value2 == null) {
            return true;
        }
        return false;
    }

    public static function checkValueRequestFild($value)
    {
        if ($value == 'yes' || $value == 'نعم') {
            return true;
        }
        return false;
    }

    public static function checkValueRequestType($value)
    {
        if ($value == 'رهن') {
            return true;
        }
        return false;
    }

    public static function checkDocumentRequest($value)
    {
        $countDocument = DB::table('documents')->where('req_id', $value)->get()->count();
        if ($countDocument == 0) {
            return true;
        }
        return false;
    }

    public static function getBankInfo($bank_id)
    {
        $bankInfo = DB::table('funding_sources')->where('id', $bank_id)->first();

        if (!empty($bankInfo)) {
            return $bankInfo;
        }

        return false;
    }

    public static function getSalaryBankInfo($bank_id)
    {
        $bankInfo = DB::table('salary_sources')->where('id', $bank_id)->first();

        if (!empty($bankInfo)) {
            return $bankInfo;
        }

        return false;
    }

    public static function getMiliratyRankInfo($rank_id)
    {
        $rankInfo = DB::table('military_ranks')->where('id', $rank_id)->first();

        if (!empty($rankInfo)) {
            return $rankInfo;
        }

        return false;
    }

    public static function canShowBankName($user_id)
    {
        $scenarios = Scenario::whereIn('sort_id', [5, 3, 2])->get();
        foreach ($scenarios as $scenario) {
            $agent_array = ScenariosUsers::where('scenario_id', $scenario->id)->pluck('user_id')->toArray();
            if (in_array($user_id, $agent_array) || (count($agent_array) == 0)) {
                return true;
            }
        }
        return false;
    }

    public static function checkIfThereCalculaterRecord($req_id)
    {
        $calculaters = Calculater::where('request_id', $req_id)->get();

        if ($calculaters->count() == 0) {
            return false;
        }
        return true;
    }

    public static function getProductType()
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $products = json_decode($response->getBody(), true);
        $products = $products['data'];

        return ($products);
    }

    public static function getSpasficProductType($code)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $products = json_decode($response->getBody(), true);
        $products = $products['data'];

        $matchProduct = null;
        foreach ($products as $product) {
            if ($code == $product['code']) {

                $matchProduct = $product;
                break;
            }
        }

        return ($matchProduct);
    }

    public static function checkIfRequiredInCalculater($class_id)
    {
        $class = DB::table('classifcations')->where('id', $class_id)->first();

        if (!empty($class)) {
            return $class->is_required_in_calculater;
        }

        return false; // if it's empty

    }

    public static function salesManagerRequestProcess($req_id, $agent_id)
    {
        $salesManager = MyHelpers::getSalesManagerRequest($req_id);
        if ($salesManager != null) {
            if (MyHelpers::checkIfManagerArchived($salesManager)) {
                $salesManager = MyHelpers::getCurrentSalesManagerAgent($agent_id);
            }
        }
        else {
            $salesManager = MyHelpers::getCurrentSalesManagerAgent($agent_id);
        }

        $updateSalaesManager = MyHelpers::updateSalesManagerRequest($req_id, $salesManager);

        return $updateSalaesManager;
    }

    /**MANAGERS OF REQUEST */
    public static function getSalesManagerRequest($id)
    {
        $request = DB::table('requests')->where('id', $id)->first();
        if (!empty($request)) {
            return $request->sales_manager_id;
        }

        return null;
    }

    public static function checkIfManagerArchived($user_id)
    {
        $manager = DB::table('users')->where('id', $user_id)->where('status', 1)->first(); //active manager
        if (!empty($manager)) {
            return false;
        }

        return true;
    }

    public static function getCurrentSalesManagerAgent($agent_id)
    {
        $agent = DB::table('users')->where('id', $agent_id)->first();
        if (!empty($agent)) {
            return $agent->manager_id;
        }

        return null;
    }

    public static function updateSalesManagerRequest($req_id, $user_id)
    {
        return DB::table('requests')->where('id', $req_id)->update(['sales_manager_id' => $user_id]);
    }

    public static function fundingManagerRequestProcess($req_id)
    {
        $fundingManager = MyHelpers::getFundingManagerRequest($req_id);
        if ($fundingManager != null) {
            if (MyHelpers::checkIfManagerArchived($fundingManager)) {
                $fundingManager = MyHelpers::getCurrentFundingManagerOfSalesManager(MyHelpers::getSalesManagerRequest($req_id));
            }
        }
        else {
            $fundingManager = MyHelpers::getCurrentFundingManagerOfSalesManager(MyHelpers::getSalesManagerRequest($req_id));
        }

        $updateFundingManager = MyHelpers::updateFundingManagerRequest($req_id, $fundingManager);

        return $updateFundingManager;
    }

    public static function getFundingManagerRequest($id)
    {
        $request = DB::table('requests')->where('id', $id)->first();
        if (!empty($request)) {
            return $request->funding_manager_id;
        }

        return null;
    }

    public static function getCurrentFundingManagerOfSalesManager($manager_id)
    {
        $manager = DB::table('users')->where('id', $manager_id)->first();
        if (!empty($manager)) {
            return $manager->funding_mnager_id;
        }

        return null;
    }

    public static function updateFundingManagerRequest($req_id, $user_id)
    {
        return DB::table('requests')->where('id', $req_id)->update(['funding_manager_id' => $user_id]);
    }

    public static function mortgageManagerRequestProcess($req_id)
    {
        $mortgageManager = MyHelpers::getMortgageManagerRequest($req_id);
        if ($mortgageManager != null) {
            if (MyHelpers::checkIfManagerArchived($mortgageManager)) {
                $mortgageManager = MyHelpers::getCurrentMortgageManagerOfSalesManager(MyHelpers::getSalesManagerRequest($req_id));
            }
        }
        else {
            $mortgageManager = MyHelpers::getCurrentMortgageManagerOfSalesManager(MyHelpers::getSalesManagerRequest($req_id));
        }

        $updateMortgageManager = MyHelpers::updateMortgageManagerRequest($req_id, $mortgageManager);

        return $updateMortgageManager;
    }

    public static function getMortgageManagerRequest($id)
    {
        $request = DB::table('requests')->where('id', $id)->first();
        if (!empty($request)) {
            return $request->mortgage_manager_id;
        }

        return null;
    }

    public static function getCurrentMortgageManagerOfSalesManager($manager_id)
    {
        $manager = DB::table('users')->where('id', $manager_id)->first();
        if (!empty($manager)) {
            return $manager->mortgage_mnager_id;
        }

        return null;
    }

    public static function updateMortgageManagerRequest($req_id, $user_id)
    {
        return DB::table('requests')->where('id', $req_id)->update(['mortgage_manager_id' => $user_id]);
    }

    /** END MANAGERS OF REQUEST */

    public static function resubmitCustomerReqTime($req_time)
    {
        $fields = DB::table('settings')->where('option_name', 'LIKE', 'request_resubmit_days')->first()->option_value;
        $reqDate = new Carbon($req_time);
        $now = Carbon::now();

        return ($reqDate->diff($now)->days > $fields);
    }

    public static function checkDublicateNotification($recived_id, $value, $req_id)
    {
        $notify = DB::table('notifications')->where('recived_id', $recived_id)->where('value', $value)->where('req_id', $req_id)->whereDate('created_at', Carbon::now()->toDateString())->first();

        if (empty($notify)) {
            return true;
        }
        return false;
    }

    public static function hideCommentOfNegativeClassification($req_id, $agent_comment)
    {

        //SHOW COMMENTS OR NOT (NEGAIVE NOTES)
        $history = DB::table('request_histories')->where([
            'req_id'    => $req_id,
            'recive_id' => auth()->user()->id,
            'title'     => 'نقل الطلب',
        ])->first();

        $history_negative_agent = null;
        $get_comments_of_negative_agent = '';
        $hide_negative_comment = false;
        if ($history != null) {
            $classifcations = DB::table('classifcations')->find($history->class_id_agent);
            if ($classifcations != null) {
                if ($classifcations->type == 0) {
                    // Negative
                    $get_comments_of_negative_agent = DB::table('req_records')->where('req_id', $req_id)->where('colum', 'comment')->where('user_id', $history->user_id)->orderBy('id', 'DESC')->first();
                    if ($get_comments_of_negative_agent != null) {
                        if (strcmp($get_comments_of_negative_agent->value, $agent_comment) == 0) {
                            $hide_negative_comment = true;
                        }
                        $history_negative_agent = $history->user_id;
                    }
                }
            }
        }
        ////////////////////////////////////////////////////////

        return [$history_negative_agent, $hide_negative_comment];
    }

    public static function hideCommentOfNegativeClassificationOfAddCustomer($req_id, $comment, $user_id, $class_id_agent)
    {

        $history_negative_agent = null;
        $get_comments_of_negative_agent = '';
        $hide_negative_comment = false;

        $classifcations = DB::table('classifcations')->find($class_id_agent);
        if ($classifcations != null) {
            if ($classifcations->type == 0) {
                // Negative
                $get_comments_of_negative_agent = DB::table('req_records')->where('req_id', $req_id)->where('colum', 'comment')->where('user_id', $user_id)->orderBy('id', 'DESC')->first();
                if ($get_comments_of_negative_agent != null) {
                    if (strcmp($get_comments_of_negative_agent->value, $comment) == 0) {
                        $hide_negative_comment = true;
                    }
                    $history_negative_agent = $user_id;
                }
            }
        }
        ////////////////////////////////////////////////////////

        return [$history_negative_agent, $hide_negative_comment];
    }

    public static function checkIfThereDailyPrefromenceRecord($user_id)
    {
        $daily_record = DailyPerformances::where('user_id', $user_id)->where('today_date', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->first();

        if (empty($daily_record)) {
            return false;
        }

        return true;
    }

    public static function addDailyPerformanceRecord($user_id)
    {
        return  DailyPerformances::create([
            'user_id' => $user_id,
            'today_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d')
        ]);
    }

    public static function incrementDailyPerformanceColumn($user_id, $column,$requestId,$type="request"):void
    {
        if (! self::checkIfThereDailyPrefromenceRecord($user_id)){
            self::addDailyPerformanceRecord($user_id);
        }

        $today_date = today('Asia/Riyadh')->format('Y-m-d');
        if($column == "received_task" || $column == "replayed_task"){
            $log = DailyLogs::create([
                'user_id'=> $user_id,
                'request_id'=> $requestId,
                'event'=> $column,
                'today_date' => $today_date,
                'request_type'  => $type,
            ]);
        }else{
            $log = DailyLogs::firstOrCreate([
                'user_id'=> $user_id,
                'request_id'=> $requestId,
                'event'=> $column,
                'today_date' => $today_date,
                'request_type'  => $type,
            ]);
        }

        if ($log->wasRecentlyCreated){
            DailyPerformances::where([
                'user_id'=> $user_id,
                'today_date' => $today_date
            ])->increment($column);
            if ($column == "move_request_to" || $column == "received_basket"){
                DailyPerformances::where([
                    'user_id'=> $user_id,
                    'today_date' => $today_date
                ])->increment('total_recived_request');
            }

        }

    }

    public static function incrementDailyPerformanceColumnWithCount($user_id, $column,$requests):void
    {
        $today_date =  Carbon::today('Asia/Riyadh')->format('Y-m-d');
        foreach ($requests as $request) {
            $log = DailyLogs::firstOrCreate([
                'user_id'=> $user_id,
                'request_id'=> $request,
                'event'=> $column,
                'today_date' => $today_date,
                'request_type'  => "request",
            ]);
            if ($log->wasRecentlyCreated){
                DailyPerformances::where([
                    'user_id'=> $user_id,
                    'today_date' => $today_date
                ])->increment($column);
                if ($column == "move_request_to" || $column == "received_basket") {
                    DailyPerformances::where([
                        'user_id'    => $user_id,
                        'today_date' => $today_date
                    ])->increment('total_recived_request');
                }
            }
        }
    }

    public static function updateDecrementDailyPrefromenceRecord($user_id, $today_date, $coulm)
    {
        //DailyPerformances::where('user_id', $user_id)->where('today_date', $today_date)->update([$coulm => DB::raw($coulm.' - 1')]);
    }

    public static function updateDecrementDailyPrefromenceRecordSpacficNumber($user_id, $today_date, $coulm, $number)
    {
        //DailyPerformances::where('user_id', $user_id)->where('today_date', $today_date)->update([$coulm => DB::raw($coulm - $number)]);
    }

    public static function numberFormatter($number)
    {
        if ($number < 1000) {
            $n_format = number_format($number);
        }
        else {
            if ($number < 1000000) {
                $n_format = number_format($number / 1000, 1).'K';
            }
            else {
                if ($number < 1000000000) {
                    $n_format = number_format($number / 1000000, 1).'M';
                }
                else {
                    $n_format = number_format($number / 1000000000, 1).'B';
                }
            }
        }

        //dd($n_format);
        return $n_format;
    }

    public static function EnglishDigits($value)
    {
        $hindi_numbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $arabic_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($hindi_numbers, $arabic_numbers, $value);
        //return $str = strtr("$value", $this->_arabicIndic);
    }

    public static function updateWaitingReq($requestId)
    {
        $updateReq = RequestWaitingList::where('req_id', $requestId)->update(['status' => 1]);
        return response($updateReq);
    }

    public static function moveRequestTasks($reqID, $prev_user)
    {
        $request = request::find($reqID);
        $userId = auth()->id();
        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();
        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => $userId,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {
            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {
            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);
            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();
            foreach ($tasks as $task) {
                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => $userId,
                        'user_id'   => $task->user_id,
                    ]);
                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }
    }

    public static function checkWaitingRequestConditionMatch($id)
    {
        $requestInfo = DB::table('requests')->where('id', $id)->first();

        $check = false;
        $con_id = null;

        $request_conditions = DB::table('waiting_requests_settings')->get();
        if (count($request_conditions) == 0){
            return true;
        }
        if (!empty($requestInfo)) {
            foreach ($request_conditions as $condition) {

                $checkClass = MyHelpers::checkClassForWaitingReq($condition->id, $requestInfo->class_id_agent);
                $checkUsers = MyHelpers::checkUserForWaitingReq($condition->id, $requestInfo->user_id);
                $checkStatus = MyHelpers::checkStatusForWaitingReq($condition->id, $requestInfo->statusReq);

                if (($checkClass == true) && ($checkUsers == true) && ($checkStatus == true)) {
                    $check = true;
                    $con_id = $condition->id;
                    break;
                }

            }
        }
        if ($check) {
            return $condition->id;
        }
        return false;
    }

    public static function checkClassForWaitingReq($req_con, $class)
    {

        $class_cons = DB::table('class_conditions')->where('cond_id', $req_con)->where('cond_type', 1)->get();

        if ($class_cons->count() > 0) {

            foreach ($class_cons as $class_con) {
                if ($class_con->class_id == $class) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    public static function checkUserForWaitingReq($req_con, $user_id)
    {

        $user_cons = DB::table('user_conditions')->where('cond_id', $req_con)->where('cond_type', 1)->get();

        if ($user_cons->count() > 0) {

            foreach ($user_cons as $user_con) {
                if ($user_con->user_id == $user_id) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    public static function checkStatusForWaitingReq($req_con, $status)
    {

        $status_cons = DB::table('status_conditions')->where('cond_id', $req_con)->where('cond_type', 1)->get();

        if ($status_cons->count() > 0) {

            foreach ($status_cons as $status_con) {
                if ($status_con->status == $status) {
                    return true;
                }
            }
            return false;
        }
        else {
            return true;
        }
    }

    //**************************************************************************

    public static function checkWaitingRequestConditionReplayTime($msg_time)
    {
        $now = Carbon::now();
        $date = Carbon::parse($msg_time);
        $checkValue = $date->diffInSeconds($now);

        $replay_time = MyHelpers::getWaitingRequestReplayTime();
        return ($replay_time <= $checkValue);

    }

    public static function getWaitingRequestReplayTime()
    {
        $active = DB::table('settings')->where('option_name', 'waitingRequest_replaytime')->first();

        if (empty($active)) {
            return 0;
        }
        return $active->option_value;
    }

    public static function checkDublicateOfWaitingReq($req_id)
    {
        $watingReqs = RequestWaitingList::where('req_id', $req_id)->where('status', 0)->first();
        if (empty($watingReqs)) {
            return true;
        }
        return false;
    }

    public static function addWaitingReq($action, $req_id)
    {
        $reqInfo = DB::table('requests')->where('id', $req_id)->first();
        // Add Condtions For Adding in the WatingList
        RequestWaitingList::create([
            'action'      => $action,
            'agent_id'    => $reqInfo->user_id,
            'req_id'      => $req_id,
            'customer_id' => $reqInfo->customer_id,
        ]);
    }

    public static function removeWaitingReq($id)
    {
        $removeReq = DB::table('request_waiting_lists')->where('id', $id)->delete();
    }

    public static function removeWaitingReqByReqID($id)
    {
        $removeReq = DB::table('request_waiting_lists')->where('req_id', $id)->where('status', 0)->delete();
    }

    public static function checkIfThereIsNeedActionReq($id)
    {
        $checkNeedReq = self::checkDublicateOfNeedActionReqWithStatusOnly($id);
        if ($checkNeedReq != 'false') {
            $updateNeedReq = self::updateNeedActionReqStatus($checkNeedReq->id);
        }
    }

    public static function updateNeedActionReqStatus($id)
    {

        $needReqs = RequestNeedAction::where('id', $id)->where('status', 0)->update(['status' => 1]);

        if ($needReqs) {
            return true;
        }
        return false;
    }

    public static function set_ready_recive($user_id)
    {
        if (MyHelpers::checkAgentRecive_byid($user_id)) {
            $received_reqs_count = DB::table('requests')->where('requests.user_id', $user_id)->where(function ($query) {
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
                    $query->where('prepayments.isSentSalesAgent', 1);
                    $query->where('requests.type', 'شراء-دفعة');
                });
            })->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->where('is_canceled', 0)->where('is_followed', 0)->where('is_stared', 0)->count();

            if (!$received_reqs_count) {
                DB::table('users')->where('id', $user_id)->update(['ready_receive' => 1]);
                return true;
            }
        }
        return false;

    }

    public static function checkAgentRecive_byid($user_id)
    {
        $userInfo = DB::table('users')->where('id', $user_id)->first();
        return (bool) $userInfo->allow_recived;
    }

    public static function QualityBasketsSelect()
    {
        return collect([
            [
                'id'   => 'received',
                'name' => 'المستلمة',
            ],
            [
                'id'   => 'follow',
                'name' => 'المتابعة',
            ],
            [
                'id'   => 'archived',
                'name' => 'المؤرشفة',
            ],
            [
                'id'   => 'finished',
                'name' => 'المنتهية',
            ],
        ]);
    }

    public static function QualityRequestStatusSelect()
    {
        return collect([
            [
                'id'   => 0,
                'name' => 'طلب جديد',
            ],
            [
                'id'   => 1,
                'name' => 'طلب تم فتحه',
            ],
            [
                'id'   => 2,
                'name' => 'تحت المعالجة',
            ],
            [
                'id'   => 3,
                'name' => 'مكتمل',
            ],
            [
                'id'   => 4,
                'name' => 'غير مكتمل',
            ],
            [
                'id'   => 5,
                'name' => 'الطلب مؤرشف لدى الجودة',
            ],
        ]);
    }

    public static function UpdatingRequest(\App\Models\Request $requestModel)
    {
        try{
            $ids = $requestModel->qualityRequests()->where('status', '0')->pluck('id')->toArray();
            DB::table('quality_reqs')->whereIn('id', $ids)->delete();

        }catch(\Exception $e){}

        $requestModel->qualityRequests()->whereNotIn('status', [3,5])->update(['status' => 3, 'is_followed' => 0]);
        foreach ($requestModel->qualityRequests as $item) {
            if ($item->user_id != null){
                MyHelpers::incrementDailyPerformanceColumn($item->user_id, 'completed_request',$requestModel->id);
            }
        }
    }
    public static function TechniqalMsgType($i = '')
    {
        $d = [
            '' => 'اختر نوع الرسالة',
            'login_issue' => "مشكلة في تسجيل الدخول",
            'programing_issue' => "مشكلة برمجية",
            'ui_issue' => "مشكله تفاعل مع الصفحات",
            'report_employee' => "ابلاغ من موظف",
            'report' => "شكوي",
            'recommendation' => "اقتراح",
            'other' => "اخري",
        ];
        if($i != '')
            return $d[$i];
        return $d;
    }
    // SEND NOTIFICATION TO USER
    public static function SendNotificationToUser($message, $user_id, $req_id)
    {
        DB::table('notifications')->insert([
            'value'         => $message,
            'recived_id'    => $user_id,
            'status'        => 0,
            'type'          => 1,
            'req_id'        => $req_id,
            'created_at'    => (Carbon::now('Asia/Riyadh')),
        ]);
    }
}
