<?php

namespace App\Http\Controllers\API;

use App\customer;
use App\helpDesk;
use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordApi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Location;

class AuthController extends Controller
{
    // use ApiResponseTrait;
    public static function getOptionValue($option_name)
    {
        $setting = DB::table('settings')->where('option_name', $option_name)->get();
        return $setting[0]->option_value;
    }

    public static function getValidationValue($option_name)
    {
        $setting = DB::table('request_condition_settings')->where($option_name, '!=', null)->first();

        return $setting;
    }

    //public function addNewCustomer(Request $request)
    //{
    //    $mobileNumber = $request->mobile;
    //    //        return self::errorResponse(422,false,null,$this->sendSMSotp($mobileNumber));
    //    $checkMobileExist = DB::table('customers')->where('mobile', $request->mobile)->first();
    //    if ($checkMobileExist) {
    //        // Check Customer Exist in table Requests
    //        $checkCustomerRequests = DB::table('requests')->where('customer_id', $checkMobileExist->id)->first();
    //        if ($checkCustomerRequests && ($checkMobileExist->isVerified === 1)) {
    //            return self::errorResponse(422, false, "عزيزي, حسابك موجود بالفعل ويمكنك تسجيل الدخول", null);
    //        }
    //        elseif ($checkCustomerRequests && ($checkMobileExist->isVerified === 0)) {
    //            $send = $this->sendSMSotp($mobileNumber);
    //            if ($send === "Send") {
    //                return self::successResponse(200, true, "عزيزي حسابك موجود بالفعل لكنه غير مفعل , تم ارسال رمز تحقق لجوالك لتفعيله", null);
    //            }
    //            else {
    //                return self::errorResponse(422, false, "فشل ارسال رمز التحقق بسبب تجاوزك الحد المسموح من استخدام ارسال الرمز من خلال الجوال يرجي التواصل معنا ", null);
    //                //                    return self::errorResponse(422,false,"فشل ارسال رمز التحقق بسبب تجاوزك الحد المسموح من استخدام ارسال الرمز 1 من خلال الجوال يرجي التواصل معنا",$this->getOtpRequestCount());
    //
    //            }
    //
    //        }
    //    }
    //    else {
    //        $rules = [
    //            'name'       => 'required',
    //            'mobile'     => 'required|numeric',
    //            'email'      => 'email|unique:customers|nullable',
    //            'birth_date' => 'date|nullable',
    //            'salary_id'  => 'numeric|nullable',
    //        ];
    //        if ($this->getOptionValue('askforconsultant_salary') == 'show' && ($this->getValidationValue('request_validation_from_salary') != null && $this->getValidationValue('request_validation_from_salary') != '') || ($this->getValidationValue('request_validation_to_salary') != null && $this->getValidationValue('request_validation_to_salary') != '')) {
    //            $rules['salary'] = 'required|numeric|nullable';
    //        }
    //        else {
    //            $rules['salary'] = 'nullable|numeric';
    //        }
    //        if ($this->getOptionValue('askforconsultant_work') == 'show' && $this->getValidationValue('request_validation_to_work') != null && $this->getValidationValue('request_validation_to_work') != '') {
    //            $rules['work'] = 'required';
    //        }
    //        else {
    //            $rules['work'] = 'nullable';
    //        }
    //        if ($this->getOptionValue('askforconsultant_isSupported') == 'show' && $this->getValidationValue('request_validation_to_support') != null && $this->getValidationValue('request_validation_to_support') != '') {
    //            $rules['is_supported'] = 'required';
    //        }
    //        else {
    //            $rules['is_supported'] = 'nullable';
    //        }
    //        if ($this->getOptionValue('askforconsultant_has_obligations') == 'show' && $this->getValidationValue('request_validation_to_has_obligations') != null && $this->getValidationValue('request_validation_to_has_obligations') != '') {
    //            $rules['has_obligations'] = 'required';
    //        }
    //        else {
    //            $rules['has_obligations'] = 'nullable';
    //        }
    //        if ($this->getOptionValue('askforconsultant_has_financial_distress') == 'show' && $this->getValidationValue('request_validation_to_has_financial_distress') != null && $this->getValidationValue('request_validation_to_has_financial_distress') != '') {
    //            $rules['has_financial_distress'] = 'required';
    //        }
    //        else {
    //            $rules['has_financial_distress'] = 'nullable';
    //        }
    //        if ($this->getOptionValue('askforconsultant_owning_property') == 'show' && $this->getValidationValue('request_validation_to_owningProperty') != null && $this->getValidationValue('request_validation_to_owningProperty') != '') {
    //            $rules['owning_property'] = 'required';
    //        }
    //        else {
    //            $rules['owning_property'] = 'nullable';
    //        }
    //        $customMessages = [
    //            'salary.required'                 => MyHelpers::guest_trans('Salary filed is required'),
    //            'work.required'                   => MyHelpers::guest_trans('Work filed is required'),
    //            'is_supported.required'           => MyHelpers::guest_trans('Support filed is required'),
    //            'has_obligations.required'        => MyHelpers::guest_trans('Obligation filed is required'),
    //            'has_financial_distress.required' => MyHelpers::guest_trans('Distress filed is required'),
    //            'owning_property.required'        => MyHelpers::guest_trans('The filed is required'),
    //            'name.required'                   => MyHelpers::guest_trans('Name filed is required'),
    //            'mobile.required'                 => MyHelpers::guest_trans('Mobile filed is required'),
    //            'mobile.unique'                   => MyHelpers::guest_trans('This customer already existed'),
    //            'mobile.regex'                    => MyHelpers::guest_trans('Should start with 5'),
    //            'mobile.numeric'                  => MyHelpers::guest_trans('Should start with 5'),
    //            'email.email'                     => MyHelpers::guest_trans('Email is invalid'),
    //            'email.unique'                    => MyHelpers::guest_trans('Email Already Existed'),
    //        ];
    //        if ($request->salary != null) {
    //            $request->merge([
    //                'salary' => str_replace(',', '', $request->salary),
    //            ]);
    //        }
    //        $validator = Validator::make($request->all(), $rules, $customMessages);
    //        if ($validator->fails()) {
    //            $failedRules = $validator->failed();
    //            if (isset($failedRules['mobile']['Unique'])) {
    //                $checkpending = true;
    //                $status = 3; //for unpending request
    //                $customer = Customer::where('mobile', $request->mobile)->first();
    //                $request = DB::table('requests')->where('customer_id', $customer->id)->first();
    //                if (empty($request)) {
    //                    $checkpending = false;
    //                    $status = 2; //for pending request only
    //                    $request = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
    //                }
    //                $gms = MyHelpers::getAllActiveGM();
    //                foreach ($gms as $gm) {
    //                    DB::table('notifications')->insert([ // add notification to send user
    //                                                         'value'      => MyHelpers::guest_trans('The customer tried to submit a new request'),
    //                                                         'recived_id' => $gm->id,
    //                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
    //                                                         'type'       => 5,
    //                                                         'req_id'     => $request->id,
    //                    ]);
    //                    MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
    //                }
    //                return response()->json([
    //                    'code'    => 200,
    //                    'status'  => true,
    //                    'message' => 'عميل مكرر يمكنك متابعة طلبك من خلال رقم الطلب التالي ',
    //                    'payload' => [
    //                        'searching_id' => $request->searching_id,
    //                    ],
    //                ], 200);
    //            }
    //            else {
    //                return self::errorResponse(422, false, $validator->errors()->first(), null);
    //            }
    //        }
    //        $request = MyHelpers::checkPostRequest($request);
    //        $is_approved = MyHelpers::check_is_request_acheive_condition($request);
    //        if ($is_approved) {
    //            $user_id = $this->getNextAgentForRequest();
    //        }
    //        else {
    //            $user_id = $this->getNextAgentForPending();
    //        }
    //        $pass = str_random(8);
    //        $customer = Customer::create([
    //            'name'                   => $request->name,
    //            'username'               => $is_approved ? 'customer_'.rand(10000000, 99999999) : null,
    //            'password'               => $is_approved ? Hash::make($pass) : null,
    //            'pass_text'              => $is_approved ? $pass : null,
    //            'birth_date'             => $request->birth_date,
    //            'birth_date_higri'       => $request->birth_hijri,
    //            'mobile'                 => $request->mobile,
    //            'email'                  => $request->email,
    //            'work'                   => $request->work,
    //            'salary'                 => $request->salary,
    //            'salary_id'              => $request->salary_id,
    //            'is_supported'           => $request->is_supported,
    //            'has_obligations'        => $request->has_obligations,
    //            'has_financial_distress' => $request->has_financial_distress,
    //            'user_id'                => $user_id,
    //            'region_ip'              => $this->getRegion(),
    //            'created_at'             => (Carbon::now('Asia/Riyadh')),
    //        ]);
    //        $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
    //        if ($customer) {
    //            //insertGetId : insertGetId method to insert a record and then retrieve the ID
    //            //add it once use insertGetId
    //            $joinID = DB::table('joints')->insertGetId([
    //                'created_at' => (Carbon::now('Asia/Riyadh')),
    //            ]);
    //            if ($request->owning_property == null) {
    //                $owning_property = 'no';
    //            }
    //            else {
    //                $owning_property = $request->owning_property;
    //            }
    //            $realID = DB::table('real_estats')->insertGetId([
    //                'created_at'      => (Carbon::now('Asia/Riyadh')),
    //                'owning_property' => $owning_property,
    //            ]);
    //            $funID = DB::table('fundings')->insertGetId([
    //                'created_at' => (Carbon::now('Asia/Riyadh')),
    //            ]);
    //            $searching_id = RequestSearching::create()->id;
    //            if ($is_approved) {
    //                $request = DB::table('requests')->insertGetId([
    //                    'statusReq'    => 0,
    //                    'customer_id'  => $customer->id,
    //                    'user_id'      => $user_id,
    //                    'source'       => $request->source,
    //                    'req_date'     => $reqdate,
    //                    'created_at'   => (Carbon::now('Asia/Riyadh')),
    //                    'agent_date'   => (Carbon::now('Asia/Riyadh')),
    //                    'joint_id'     => $joinID,
    //                    'real_id'      => $realID,
    //                    'searching_id' => $searching_id,
    //                    'fun_id'       => $funID,
    //                ]);
    //                $agenInfo = DB::table('users')->where('id', $user_id)->first();
    //                $notify = MyHelpers::addNewNotify($request, $user_id); // to add notification
    //                $record = MyHelpers::addNewReordWebsite($request, $user_id); // to add new history record
    //                $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification
    //                //$pwaPush = MyHelpers::pushPWA($user_id, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);
    //            }
    //            else {
    //                $request_prnding = PendingRequest::create([
    //                    'statusReq'    => 0,
    //                    'customer_id'  => $customer->id,
    //                    'user_id'      => $user_id,
    //                    'source'       => $request->source,
    //                    'req_date'     => $reqdate,
    //                    'created_at'   => (Carbon::now('Asia/Riyadh')),
    //                    'joint_id'     => $joinID,
    //                    'real_id'      => $realID,
    //                    'searching_id' => $searching_id,
    //                    'fun_id'       => $funID,
    //                ]);
    //                $request = $request_prnding->id;
    //                return response()->json([
    //                    'code'    => 200,
    //                    'status'  => true,
    //                    'message' => 'thank you',
    //                    'payload' => [
    //                        'searching_id' => $searching_id,
    //                    ],
    //                ], 200);
    //            }
    //            setLastAgentOfDistribution($user_id, !$is_approved);
    //            if ($request) {
    //                $send = $this->sendSMSotp($mobileNumber);
    //                //return response()->json([
    //                //    'code'   => 200,
    //                //    'status' => true,
    //                //    'send'   => $send,
    //                //
    //                //], 200);
    //                if ($send === "Send") {
    //                    if ($customer) {
    //                        $createToken = $customer->createToken('Personal Access Token');
    //                        $token = $createToken->accessToken;
    //                        return response()->json([
    //                            'code'    => 200,
    //                            'status'  => true,
    //                            'message' => 'تم تسجيل حسابك بنجاح , يرجي التحقق من جوالك ثم نسخ رمز التحقق ومتابعة تفعيل جوالك',
    //                            'payload' => [
    //                                'access_token' => $token,
    //                            ],
    //                        ], 200);
    //                    }
    //                }
    //                else {
    //                    return self::errorResponse(422, false, "فشل ارسال رمز التحقق بسبب تجاوزك الحد المسموح من استخدام ارسال الرمز  من خلال الجوال يرجي التواصل معنا", null);
    //                }
    //            }
    //        }
    //        else {
    //            return self::errorResponse(422, false, "حدثت مشكلة الرجاء المحاولة لاحقاً", null);
    //        }
    //    }
    //}

    //public function sendSMSotp($mobileNumber)
    //{
    //    $ipAddress = $this->getIpAddress();
    //    $checkOtpList = $this->getOtpRequestCount($mobileNumber, $ipAddress);
    //    $checkOtpIpList = $this->getOtpIpCount($ipAddress);
    //    //if ($checkOtpList <= 3)
    //    if ($checkOtpList <= 500) {
    //        //if ($checkOtpIpList <= 3) {
    //        if ($checkOtpIpList <= 500) {
    //            $otpsms = rand(1000, 9999);
    //            Helper::insertOtpRequest($mobileNumber, $ipAddress);
    //            $sendsms = $this->sendSmsCode($mobileNumber, $otpsms);
    //            if ($sendsms) {
    //                return "Send";
    //            }
    //        }
    //    }
    //    return "not";
    //}

    //public function getIpAddress()
    //{
    //    $ipaddress = '';
    //    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    //        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    //    }
    //    else {
    //        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //        }
    //        else {
    //            if (isset($_SERVER['HTTP_X_FORWARDED'])) {
    //                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    //            }
    //            else {
    //                if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    //                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    //                }
    //                else {
    //                    if (isset($_SERVER['HTTP_FORWARDED'])) {
    //                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    //                    }
    //                    else {
    //                        if (isset($_SERVER['REMOTE_ADDR'])) {
    //                            $ipaddress = $_SERVER['REMOTE_ADDR'];
    //                        }
    //                        else {
    //                            $ipaddress = 'UNKNOWN';
    //                        }
    //                    }
    //                }
    //            }
    //        }
    //    }
    //
    //    return $ipaddress;
    //}

    //public function getOtpRequestCount($mobile, $ipAddress)
    //{
    //    //return DB::table('otp_request')->where('ip', $ipAddress)->where('mobile', $mobile)->count();
    //    return customer::where('mobile', $mobile)->select('otp_resend_count')->first();
    //}

    //public function getOtpIpCount($ipAddress)
    //{
    //    return DB::table('otp_request')->where('ip', $ipAddress)->count();
    //}

    //public function sendSmsCode($mobile, $otpCode)
    //{
    //    if ($otpCode) {
    //        DB::table('customers')->where('mobile', $mobile)->update(['otp_value' => $otpCode]);
    //    }
    //    return MyHelpers::sendSmsOtp($mobile, $otpCode);
    //}

    public function getOtpCode(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile' => 'required|numeric',
        ], [
            'mobile.required' => 'Mobile filed is required',
        ], []);
        if ($validatedData->fails()) {
            return response()->json([
                'code'    => 422,
                'status'  => false,
                'message' => $validatedData->errors()->first(),
                'payload' => null,
            ], 422);

        }
        else {
            $checkCustomer = Customer::where('mobile', $request->mobile)->first();
            if ($checkCustomer) {
                $get = Customer::where('id', $checkCustomer->id)->select('otp_value', 'reset_code')->first();
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => null,
                    'payload' => $get,
                ], 200);
            }
        }
    }

    public function getFieldsValue()
    {
        $getCustomerInfoSettings = DB::table('settings')->where('option_name', 'LIKE', 'askforconsultant_'.'%')->where('option_value', 'show')->get();
        if ($getCustomerInfoSettings->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getCustomerInfoSettings);
        }
    }

    public function getNextAgentForRequest()
    {
        return getLastAgentOfDistribution();
        // To get user_id for last request
        $last_req_id = DB::table('requests')->max('id'); // latest request_id
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
            $user_id = DB::table('users')->where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            if ($user_id == null) {
                $user_id = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            }
        }

        return $user_id;
    }

    public function getNextAgentForPending()
    {
        return getLastAgentOfDistribution(!0);
        // To get user_id for last request
        $last_req_id = DB::table('pending_requests')->max('id'); // latest request_id
        if ($last_req_id != null) {
            $last_req = DB::table('pending_requests')->where('id', $last_req_id)->first(); // latest request object
            $last_user_id = $last_req->user_id;
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
                $user_id = DB::table('users')->where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                if ($user_id == null) {
                    $user_id = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                }
            }
        }
        else {
            $last_req_id = DB::table('requests')->max('id'); // latest request_id
            $last_req = DB::table('requests')
                ->where('id', $last_req_id)->get(); // latest request object
            $last_user_id = $last_req[0]->user_id;
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
                $user_id = DB::table('users')->where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                if ($user_id == null) {
                    $user_id = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                }
            }
        }

        return $user_id;
    }

    public function getRegion()
    {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else {
                if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                }
                else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    }
                    else {
                        if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        }
                        else {
                            if (isset($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            }
                            else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }

        $location = Location::get($ipaddress);

        if ($location) {
            return $location->cityName;
        }
        return null;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|max:55',
            'email'    => 'email|required|unique:users',
            'password' => 'required',
        ]);
        $validatedData['password'] = bcrypt($request->password);
        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (!auth()->attempt($loginData)) {
            return response()->json(['error' => 'Invalid Credentials'], 404);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function getSalarySources()
    {
        $getSalarySources = DB::table('salary_sources')->select('id as salary_id', 'value')->get();
        if ($getSalarySources->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getSalarySources);
        }
    }

    public function otpVerification(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile'    => 'required|numeric',
            'otp_value' => 'required|min:4|max:4',
        ], [
            'mobile.required'    => 'رقم الجوال مطلوب',
            'mobile.numeric'     => 'يجب ان يكون رقم الجوال أرقام',
            'otp_value.required' => 'رمز التحقق مطلوب',
            'otp_value.min'      => 'رمز التحقق يجب ان يكون 4 أرقام',
            'otp_value.max'      => 'رمز التحقق يجب ان يكون 4 أرقام',
        ], []);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {

            $checkMobileExist = Customer::where('mobile', $request->mobile)->first();
            if (!$checkMobileExist) {
                return self::errorResponse(422, false, "رقم الجوال غير مسجل لدينا بالنظام يرجي التأكد من الرقم واعادة المحاولة", null);
            }
            elseif ($checkMobileExist->otp_value != $request->otp_value) {
                return self::errorResponse(422, false, "رمز التحقق غير صحيح يرجي التأكد من الرقم وأعادة المحاولة", null);
            }
            elseif ($checkMobileExist->isVerified === 1) {
                return self::errorResponse(422, false, "حسابك تم تفعيله بالفعل", null);
            }
            else {
                $updateStatus = Customer::where('mobile', $request->mobile)->where('otp_value', $request->otp_value)->where('isVerified', '0')->update(['isVerified' => "1", 'otp_value' => null]);
                return self::successResponse(200, true, "تم تفعيل الحساب بنجاح", null);
            }
        }
    }

    public function setNewPasswordForCustomer(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile'   => 'required|numeric',
            'password' => 'required|min:6',
        ], [
            'mobile.required'   => 'رقم الجوال مطلوب',
            'mobile.numeric'    => 'يجب ان يكون رقم الجوال ارقام فقط ولا يحتوي على حروف',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور لابد ان تكون 6 ارقام او حروف على الاقل',
        ], []);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            $checkMobileExist = Customer::where('mobile', $request->mobile)->first();
            if (!$checkMobileExist) {
                return self::errorResponse(422, false, "رقم الجوال غير مسجل لدينا بالنظام يرجي التأكد من الرقم واعادة المحاولة", null);
            }
            else {
                $setNewPassword = Customer::where('mobile', $request->mobile)->update([
                    'password' => Hash::make($request->password),
                ]);
                if ($setNewPassword) {
                    return self::successResponse(200, true, "تم إضافة كلمة المرور بنجاح يمكنك تسجيل الدخول", null);
                }
            }
        }
    }

    public function customerLogin(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile'   => 'required|numeric',
            'password' => 'required',
        ], [
            'mobile.required'   => 'رقم الجوال مطلوب',
            'mobile.numeric'    => 'يجب ان يكون رقم الجوال اراقم فقط ولا يحتوي على حروف',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customer = Customer::where('mobile', $request->mobile)->first();
        if ($customer) {
            if ((Hash::check($request->password, $customer->password)) && $customer->isVerified === 1) {
                $token = $customer->createToken('authToken')->accessToken;
                $updateCustomer = Customer::where('mobile', $request->mobile)->update(['logout' => false, 'login_time' => Carbon::now()]);
                $getCustomerInfo = DB::table('customers')->leftJoin('requests', 'requests.customer_id', '=', 'customers.id')->where('customers.id', $customer->id)->select('customers.id as customer_id', 'requests.noteWebsite', 'customers.name as customer_name', 'customers.email as customer_email',
                    'customers.mobile as customer_mobile', 'customers.isVerified as customer_status', 'requests.user_id as customer_sales_agent_id'//                        'requests.class_id_agent as classID'
                )->first();
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => "",
                    'payload' => [
                        'access_token'  => $token,
                        'customer_info' => $getCustomerInfo,
                    ],
                ], 200);

            }
            else {
                return self::errorResponse(422, false, "كلمة المرور غير صحيحة او حسابك لم يتم تفعيله", null);
            }
        }
        else {
            return self::errorResponse(422, false, "البيانات المدخلة لاتتطابق مع أي من البيانات المسجلة لدينا", null);
        }
    }

    public function sendResetPasswordCode(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email'  => 'nullable',
            'mobile' => 'nullable',
        ]);
        // $otpsms = Str::random(4);
        $otpsms = 1111;
        if (!empty($request->email) && empty($request->mobile)) {
            $checkEmail = DB::table('customers')->where('email', $request->email)->first();
            if ($checkEmail) {
                DB::table('password_resets')->where(['email' => $request->email, 'mobile' => null])->delete();
                $customer = DB::table('password_resets')->insert([
                    'channel_reset_type' => "email",
                    'code'               => $otpsms,
                    'email'              => $request->email,
                    'token'              => Str::random(60),
                    'created_at'         => Carbon::now(),
                ]);
                $send = Mail::to($request->email)->send(new ResetPasswordApi($otpsms, $checkEmail));
                return self::successResponse(200, true, "تم إرسال إيميل إعادة تعيين كلمة المرور إلى صندوق بريدك ", null);
            }
            else {
                return self::errorResponse(422, false, "البريد الالكتروني غير مسجل لدينا يرجي اعادة المحاولة باستخدام بريد الكتروني صحيح", null);
            }
        }
        elseif (empty($request->email) && !empty($request->mobile)) {
            $checkCustomer = Customer::where('mobile', $request->mobile)->first();
            if ($checkCustomer) {
                // Check Customer Is Exist in requests table or not
                $checkRequest = DB::table('requests')->where('customer_id', $checkCustomer->id)->first();
                // If No Requests
                if (!$checkRequest) {
                    return self::errorResponse(422, false, "رقم الجوال لايتطابق مع أي من البيانات المسجلة لديناً", null);
                }
                $checkPendingRequest = DB::table('pending_requests')->where('customer_id', $checkCustomer->id)->first();
                if ($checkPendingRequest) {
                    return self::errorResponse(422, false, "تم تعليق طلبك برجاء التواصل معنا للمساعدة", null);
                }
                $day = Carbon::now('Asia/Riyadh');
                $today = date("Y-m-d", strtotime($day));
                $phones = DB::table('password_resets')->where([
                    'mobile' => $request->mobile,
                ])->where('day', $today)->count();

                $ips = DB::table('password_resets')->where([
                    'ip' => $request->ip(),
                ])->where('day', $today)->count();

                $val = DB::table('password_resets')->where([
                    'mobile' => $request->mobile,
                ])->where('waiting_at', '>', $day)->orderBy('waiting_at', 'DESC')->first();
                if ($val != null) {
                    $first = new DateTime($val->waiting_at);
                    $second = new DateTime($day);
                    $diff = $first->diff($second);
                    if ($ips == 3 || $phones == 3) {
                        return self::errorResponse(422, false, "لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة مجددا فى اليوم التالى", null);
                    }
                    else {
                        return self::errorResponse(422, false, 'لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة مجددا بعد :  '.($diff->format(' %i دقيقة ')), null);
                    }
                    if ($checkRequest->class_id_agent == 16) {
                        return self::errorResponse(422, false, 'لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة من خلال البريد الإلكترونى ', null);
                    }
                }
                if ($checkRequest->class_id_agent == 16) {
                    $val = DB::table('password_resets')->where([
                        'mobile' => $request->mobile,
                    ])->count();
                    if ($val != 0) {
                        return self::errorResponse(422, false, "لقد استنذفت محاولتك الرجاء إستخدام البريد الإلكترونى", null);
                    }
                }
                $input['batch'] = 1;
                if (($ips == 3 || $phones == 3) && $val == null) {
                    return self::errorResponse(422, false, 'لقد استنفدت عدد المحاولات اليوم حاول فى اليوم التالى ..', null);
                }
                if ($ips == 2 || $phones == 2) {
                    $input['batch'] = 0;
                }
                $user = DB::table('password_resets')->insert([
                    'mobile'             => $request->mobile,
                    'channel_reset_type' => "mobile",
                    'code'               => $otpsms,
                    'created_at'         => $day,
                    'ip'                 => $request->ip(),
                    'waiting_at'         => $day->addHours(1),
                    'day'                => $today,
                    'batch'              => $input['batch'],
                ]);
                MyHelpers::sendSmsOtp($request->mobile, $otpsms);
                return self::successResponse(200, true, "تم ارسال رمز التحقق لجوالك يرجي التحقق", null);
            }
            else {
                return self::errorResponse(422, false, "رقم الجوال غير صحيح يرجي اعادة المحاولة باستخدام رقم جوال صحيح", null);
            }
        }
        else {
            return self::errorResponse(422, false, "please add only one filed", null);
        }
    }

    public function checkResetPasswordCode(Request $request)
    {
        $validatedData = Validator::make($request->only(['channel_reset_type']), [
            'channel_reset_type' => 'required|in:email,mobile',
        ], [
            'channel_reset_type.required' => 'نوع الإستعادة مطلوب إما عن طريق الايميل او الجوال',
            'channel_reset_type.in'       => 'نوع الإستعادة لابد ان يكون إيميل أو جوال',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            if ($request->channel_reset_type === "email") {
                $validatedEmailRequest = Validator::make($request->only(['email', 'reset_code', 'password']), [
                    'email'      => 'required',
                    'reset_code' => 'required|min:4|max:4',
                ], [
                    'email.required'      => 'البريد الالكتروني مطلوب',
                    'reset_code.required' => 'رمز التحقق مطلوب',
                    'reset_code.min'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'reset_code.max'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                ]);
                if ($validatedEmailRequest->fails()) {
                    return self::errorResponse(422, false, $validatedEmailRequest->errors()->first(), null);
                }
                else {
                    $checkCustomer = DB::table('password_resets')->where('email', $request->email)->first();
                    if ($checkCustomer) {
                        if ($checkCustomer->code === $request->reset_code && $checkCustomer->channel_reset_type === "email") {
                            return self::successResponse(200, true, "رمز التحقق صحيح", null);
                        }
                        else {
                            return self::errorResponse(422, false, "رمز التحقق غير صحيح , الرجاء ادخال رمز التحقق المرسل على بريدك ", null);
                        }
                    }
                    else {
                        return self::errorResponse(422, false, "البريد الإلكتروني غير مسجل لدينا بالنظام الرجاء إدخال بريد صالح", null);
                    }
                }
            }
            elseif ($request->channel_reset_type === "mobile") {
                $validatedEmailRequest = Validator::make($request->only(['mobile', 'reset_code', 'password']), [
                    'mobile'     => 'required',
                    'reset_code' => 'required|min:4|max:4',
                ], [
                    'mobile.required'     => 'رقم الجوال مطلوب',
                    'reset_code.required' => 'رمز التحقق مطلوب',
                    'reset_code.min'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'reset_code.max'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                ]);
                if ($validatedEmailRequest->fails()) {
                    return self::errorResponse(422, false, $validatedEmailRequest->errors()->first(), null);
                }
                else {
                    $day = Carbon::now('Asia/Riyadh');
                    $checkCustomer = DB::table('password_resets')->where('mobile', $request->mobile)->where('channel_reset_type', "mobile")->where('waiting_at', '>', $day)->first();
                    if ($checkCustomer) {
                        if ($checkCustomer->code === $request->reset_code && $checkCustomer->channel_reset_type === "mobile") {
                            return self::successResponse(200, true, "رمز التحقق صحيح", null);
                        }
                        else {
                            return self::errorResponse(422, false, " رمز التحقق غير صحيح او أن وقته أنتهي ,الرجاء ادخال رمز التحقق المرسل على جوالك", null);
                        }
                    }
                    else {
                        return self::errorResponse(422, false, "رقم الجوال غير مسجل لدينا بالنظام , الرجاء ادخال رقم جوال صالح", null);
                    }
                }
            }
            else {
                return self::errorResponse(422, false, "البيانات المدخلة غير صحيحة , الرجاء المحاولة مرة أخري", null);
            }
        }
    }

    public function resetPasswordAfterChcekCode(Request $request)
    {
        $validatedData = Validator::make($request->only(['channel_reset_type']), [
            'channel_reset_type' => 'required|in:email,mobile',
        ], [
            'channel_reset_type.required' => 'نوع الإستعادة مطلوب إما عن طريق الايميل او الجوال',
            'channel_reset_type.in'       => 'نوع الإستعادة لابد ان يكون إيميل أو جوال',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            if ($request->channel_reset_type === "email") {
                $validatedEmailRequest = Validator::make($request->only(['email', 'reset_code', 'password']), [
                    'email'      => 'required',
                    'reset_code' => 'required|min:4|max:4',
                    'password'   => 'required|min:6',
                ], [
                    'email.required'      => 'البريد الالكتروني مطلوب',
                    'reset_code.required' => 'رمز التحقق مطلوب',
                    'reset_code.min'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'reset_code.max'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'password.required'   => 'كلمة المرور الجديدة مطلوبة',
                    'password.min'        => 'كلمة المرور يجب ان لاتقل عن 6 أرقام أو حروف',

                ]);
                if ($validatedEmailRequest->fails()) {
                    return self::errorResponse(422, false, $validatedEmailRequest->errors()->first(), null);
                }
                else {
                    $checkCustomer = DB::table('password_resets')->where('email', $request->email)->where('channel_reset_type', "email")->first();
                    if ($checkCustomer) {
                        if ($checkCustomer->code === $request->reset_code && $checkCustomer->channel_reset_type === "email") {
                            $updatePassword = Customer::where('email', $checkCustomer->email)->update([
                                'password' => Hash::make($request->password),
                            ]);
                            $updatePasswordResetTable = DB::table('password_resets')->where('email', $checkCustomer->email)->update([
                                'code'               => null,
                                'channel_reset_type' => null,
                            ]);
                            return self::successResponse(200, true, "تم استعادة كلمة المرور بنجاح", null);
                        }
                        else {
                            return self::errorResponse(422, false, "رمز التحقق غير صحيح , الرجاء ادخال رمز التحقق المرسل على بريدك ", null);
                        }
                    }
                    else {
                        return self::errorResponse(422, false, "البريد الإلكتروني غير مسجل لدينا بالنظام الرجاء إدخال بريد صالح", null);
                    }
                }
            }
            elseif ($request->channel_reset_type === "mobile") {
                $validatedEmailRequest = Validator::make($request->only(['mobile', 'reset_code', 'password']), [
                    'mobile'     => 'required',
                    'reset_code' => 'required|min:4|max:4',
                    'password'   => 'required|min:6',
                ], [
                    'mobile.required'     => 'رقم الجوال مطلوب',
                    'reset_code.required' => 'رمز التحقق مطلوب',
                    'reset_code.min'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'reset_code.max'      => 'يجب ان يكون رمز التحقق مكون من أربعة أرقام أو حروف',
                    'password.required'   => 'كلمة المرور الجديدة مطلوبة',
                    'password.min'        => 'كلمة المرور يجب ان لاتقل عن 6 أرقام أو حروف',
                ]);
                if ($validatedEmailRequest->fails()) {
                    return self::errorResponse(422, false, $validatedEmailRequest->errors()->first(), null);
                }
                else {
                    $day = Carbon::now('Asia/Riyadh');
                    $checkCustomer = DB::table('password_resets')->where('mobile', $request->mobile)->where('channel_reset_type', "mobile")
                        //                    ->where('waiting_at','>',$day)
                        ->first();
                    $checkWiatingAt = DB::table('password_resets')->where('mobile', $request->mobile)->where('channel_reset_type', "mobile")->where('waiting_at', '>', $day)->count();
                    if ($checkCustomer) {
                        if ($checkCustomer->code === $request->reset_code && $checkCustomer->channel_reset_type === "mobile") {
                            if ($checkWiatingAt == 0) {
                                return self::errorResponse(422, false, 'لقد انتهت صلاحية الكود المرسل', null);
                            }
                            else {
                                $updatePassword = Customer::where('mobile', $checkCustomer->mobile)->update([
                                    'password' => Hash::make($request->password),
                                ]);
                                $updatePasswordResetTable = DB::table('password_resets')->where('mobile', $checkCustomer->mobile)->update([
                                    'code'               => null,
                                    'channel_reset_type' => null,
                                ]);
                                return self::successResponse(200, true, "تم استعادة كلمة المرور بنجاح", null);
                            }
                        }
                        else {
                            return self::errorResponse(422, false, "رمز التحقق غير صحيح , الرجاء ادخال رمز التحقق المرسل على جوالك", null);
                        }
                    }
                    else {
                        return self::errorResponse(422, false, "رقم الجوال غير مسجل لدينا بالنظام , الرجاء ادخال رقم جوال صالح", null);
                    }
                }
            }
            else {
                return self::errorResponse(422, false, "البيانات المدخلة غير صحيحة , الرجاء المحاولة مرة أخري", null);
            }
        }
    }

    public function helpDeskUnAuthCustomer(Request $request)
    {
        $validatedData = Validator::make($request->only(['name', 'email', 'mobile', 'description']), [
            'name'        => 'required',
            'email'       => 'required|email',
            'mobile'      => 'required|numeric',
            'description' => 'required',
        ], [
            'name.required'        => 'الإسم بالكامل إلزامي',
            'email.required'       => 'البريد الالكتروني إلزامي',
            'email.email'          => 'صيغة البريد غير صحيحة , يرجي ادخال بريد الكتروني صالح',
            'mobile.required'      => 'رقم الجوال إلزامي',
            'mobile.numeric'       => 'رقم الجوال غير صالح , يرجي أدخال رقم جوال صالح',
            'description.required' => 'الرسالة إلزامية *',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $helpDeskRequest = helpDesk::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'mobile'      => $request->mobile,
            'descrebtion' => $request->description,
            'customer_id' => null,
        ]);
        if ($helpDeskRequest) {
            $admins = MyHelpers::getAllActiveAdmin();
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'value'      => MyHelpers::guest_trans('You have new help desk request'),
                    'recived_id' => $admin->id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 8,
                    'req_id'     => $helpDeskRequest ? $helpDeskRequest->id : null,
                ]);
                $emailNotify = MyHelpers::sendEmailNotifiaction('new_help_desk', $admin->id, 'لديك طلب دعم فني جديد  ', 'طلب دعم فني ');
            }
            return self::successResponse(200, true, "تم تسجيل رسالتكم بنجاح", null);
        }
    }

}
