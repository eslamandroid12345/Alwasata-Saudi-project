<?php

namespace App\Http\Controllers\API;

use App\AskAnswer;
use App\ChatFiles;
use App\customerActivity;
use App\helpDesk;
use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Model\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct(){

        $this->middleware('auth:api');
    }

    public static function fileds($getBy)
    {
        $s = [

            "customerReq_customerName"                  => 'name',
            "customerReq_customerSex"                   => 'sex',
            "customerReq_customerMobile"                => 'mobile',
            "customerReq_customerDOB"                   => 'birth_date_higri',
            "customerReq_customerWork"                  => 'work',
            "customerReq_customerRegion"                => 'region_ip',
            "customerReq_customerWorkMadanySource"      => 'madany_id',
            "customerReq_customerWorkMadany"            => 'job_title',
            "customerReq_customerWorkAskarySource"      => 'askary_id',
            "customerReq_customerWorkAskaryRank"        => 'military_rank',
            "customerReq_customerSalary"                => 'salary',
            "customerReq_customerSalarySource"          => 'salary_id',
            "customerReq_customerSupport"               => 'is_supported',
            "customerReq_customerObligations"           => 'has_obligations',
            "customerReq_customerObligationsCost"       => 'obligations_value',
            "customerReq_customerFinancialDistress"     => 'has_financial_distress',
            "customerReq_customerFinancialDistressCost" => 'financial_distress_value',

            "customerReq_jointName"          => 'name',
            "customerReq_jointMobile"        => 'mobile',
            "customerReq_jointSalary"        => 'salary',
            "customerReq_jointSalarySource"  => 'salary_id',
            "customerReq_jointDOB"           => 'birth_date_higri',
            "customerReq_jointWork"          => 'work',
            "customerReq_jointFundingSource" => 'funding_id',

            "customerReq_realName"              => 'name',
            "customerReq_realMobile"            => 'mobile',
            "customerReq_realCity"              => 'city',
            "customerReq_realDistrict"          => 'region',
            "customerReq_realFundingProfit"     => 'pursuit',
            "customerReq_realStatus"            => 'status',
            "customerReq_realAge"               => 'age',
            "customerReq_realFundingMortgage"   => 'mortgage_value',
            "customerReq_realType"              => 'type',
            "customerReq_realCost"              => 'cost',
            "customerReq_realCustomerHasReal"   => 'owning_property',
            "customerReq_realCustomerFoundReal" => 'has_property',
            "customerReq_realAssment"           => 'evaluated',
            "customerReq_realTenants"           => 'tenant',
            "customerReq_realMortgaged"         => 'mortgage',

            "customerReq_fundingSource"             => 'funding_source',
            "customerReq_fundingDuration"           => 'funding_duration',
            "customerReq_fundingPersonalCost"       => 'personalFun_cost',
            "customerReq_fundingPersonalPresentage" => 'personalFun_pre',
            "customerReq_fundingRealCost"           => 'realFun_cost',
            "customerReq_fundingRealPresentage"     => 'realFun_pre',
            "customerReq_fundingDeductionRate"      => 'ded_pre',
            "customerReq_fundingMonthlyInstallment" => 'monthly_in',

            "customerReq_prepaymentRealCost"            => 'realCost',
            "customerReq_prepaymentRealIncreaseCost"    => 'incValue',
            "customerReq_prepaymentCost"                => 'prepaymentVal',
            "customerReq_prepaymentPresentage"          => 'prepaymentPre',
            "customerReq_prepaymentCostAfterPresentage" => 'prepaymentCos',
            "customerReq_prepaymentCustomerNet"         => 'netCustomer',
            "customerReq_prepaymentCustomerDeficit"     => 'deficitCustomer',
            "customerReq_prepaymentVisa"                => 'visa',
            "customerReq_prepaymentCar"                 => 'carLo',
            "customerReq_prepaymentPersonal"            => 'personalLo',
            "customerReq_prepaymentReal"                => 'realLo',
            "customerReq_prepaymentBank"                => 'credit',
            "customerReq_prepaymentOther"               => 'other',
            "customerReq_prepaymentTotalDebt"           => 'debt',
            "customerReq_prepaymentMortgagePresantage"  => 'mortPre',
            "customerReq_prepaymentMortgageCost"        => 'mortCost',
            "customerReq_prepaymentProfitPresantage"    => 'proftPre',
            "customerReq_prepaymentProfitCost"          => 'profCost',
            "customerReq_prepaymentValueAdded"          => 'addedVal',
            "customerReq_prepaymentAdminFees"           => 'adminFee',

            "customerReq_attachments" => '',

        ];

        return (isset($s[$getBy]) ? $s[$getBy] : '');
    }

    public function customerLogout()
    {
        $customerId = Auth::guard('api')->id();
        $accessToken = Auth::user()->token();
        customerActivity::where('customer_id', $customerId)->update([
            'last_activity' => 1494030000,
        ]);
        $updateLogout = Customer::where('id', $customerId)
            ->update(['logout' => true]);
        if ($updateLogout) {
            $updateRevoke = DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true,
                ]);
            $revokeUser = $accessToken->revoke();
            return self::successResponse(200, true, "تم تسجيل الخروج بنجاح", null);
        }
        else {
            return self::errorResponse(422, false, "حدثت مشكلة يرجي المحاولة لاحقاً", null);
        }
    }

    public function customerProfileData()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomerAuth = Customer::where('id', $customerId)->first();
        if ($checkCustomerAuth) {
            $getCustomerProfileData = Customer::join('requests', 'requests.customer_id', '=', 'customers.id')
                ->where('customers.id', $customerId)
                ->select('customers.id as customer_id',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    'customers.mobile as customer_mobile',
                    'customers.isVerified as customer_status',
                    'requests.statusReq as customer_request_status'
                )
                ->first();
            return self::successResponse(200, true, null, $getCustomerProfileData);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
        }
    }

    public function customerUpdateProfileData(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $validatedData = Validator::make($request->only(['name', 'email']), [
            'name'  => 'sometimes|nullable',
            'email' => 'sometimes|nullable',
        ], [
            'email.unique' => 'email is already exist',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            $checkCustomerAuth = Customer::where('id', $customerId)->first();
            if ($checkCustomerAuth) {
                if (count($request->all()) >= 2) {
                    $customer = DB::table('customers')->where('id', $customerId)->first();
                    $requestData = $request->only(['name', 'email']);
                    $update = DB::table('customers')
                        ->where('id', $customerId)
                        ->update($requestData);
                    return self::successResponse(200, true, "تم تحديث البيانات بنجاح", null);
                    /*  if ($request->email === $customer->email) {
                          return self::errorResponse(422, false, "هذا البريد مسجل لدينا بالفعل", null);
                      }
                      else {

                      }*/
                }
                else {
                    return self::errorResponse(422, false, "Request data Count Is Zero", null);
                }
            }
            else {
                return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
            }
        }
    }

    public function customerChangeCurrentPassword(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $validatedData = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ], [
            'old_password.required' => 'كلمة المرور القديمة مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min'      => 'كلمة المرور الجديدة يجب ان تكون على الأقل 6 أحرف أو أرقام',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            $customer = Customer::where('id', $customerId)->first();
            if ($customer) {
                if ((Hash::check($request->old_password, $customer->password)) == false) {
                    return self::errorResponse(422, false, "كلمة المرور القديمة غير صحيحة", null);
                }
                elseif ((Hash::check($request->new_password, $customer->password)) == true) {
                    return self::errorResponse(422, false, "من فضلك قم بإدخال كلمة مرور غير متطابقة مع كلمة المرور القديمة", null);
                }
                else {
                    DB::table('customers')->where('id', $customer->id)->update(['password' => Hash::make($request->new_password)]);
                    return self::successResponse(200, true, "تم تحديث كلمة المررو بنجاح", null);
                }
            }
            else {
                return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
            }
        }
    }

    public function getCustomerRequestTypes()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            // $fields = DB::table('settings')->where('option_name', 'LIKE', 'customerReq_customer'.'%')->where('option_value', 'show')->get()->count();
            // $fields = DB::table('settings')->where('option_name', 'LIKE', 'customerReq_customer%')->where('option_value', 'show')->get()->count();
            $checkCustomerRequestType = DB::table('requests')->where('customer_id', $customerId)->first();
            if ($checkCustomerRequestType->type === "شراء") {
                $array = [
                    "customer_data"     => "بيانات العميل",
                    "funding_data"      => "بيانات التمويل",
                    "real estate"       => "بيانات العقار",
                    "customer_document" => "مرفقات الطلب",
                ];
                return self::successResponse(200, true, null, $array);

            }
            elseif ($checkCustomerRequestType->type === null) {
                $array = [
                    "customer_data"     => "بيانات العميل",
                    "funding_data"      => "بيانات التمويل",
                    "real estate"       => "بيانات العقار",
                    "customer_document" => "مرفقات الطلب",
                ];
                return self::successResponse(200, true, null, $array);
            }

            else {
                $array = [
                    "customer_data"      => "بيانات العميل",
                    "funding_data"       => "بيانات التمويل",
                    "real_estate"        => "بيانات العقار",
                    "funding_prepayment" => "بيانات الدفعة",
                    "customer_document"  => "مرفقات الطلب",

                ];
                return self::successResponse(200, true, null, $array);

            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function customerFundingRequestPage()
    {
        // to
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $getCustomerInfoSettings = DB::table('settings')->where('option_name', 'LIKE', 'customerReq_customer'.'%')->where('option_value', 'show')->get();
            $getCustomerInfo = Customer::leftjoin('askary_works', 'askary_works.id', '=', 'customers.askary_id')
                ->leftJoin('madany_works', 'madany_works.id', '=', 'customers.madany_id')
                ->leftJoin('salary_sources', 'salary_sources.id', '=', 'customers.salary_id')
                ->where('customers.id', $customerId)
                ->select('customers.name as customer_name', 'customers.sex as customer_sex', 'customers.mobile as customer_mobile', 'customers.age',
                    'customers.birth_date as customer_birth_date', 'customers.work as customer_work', 'askary_works.value as askary_work_source', 'customers.military_rank',
                    'madany_works.value as madany_work_source', 'customers.salary', 'salary_sources.value as salary_source',
                    'customers.is_supported', 'customers.has_obligations', 'customers.obligations_value',
                    'customers.has_financial_distress', 'customers.financial_distress_value'
                )
                ->first();
            $getRealEstateSetting = DB::table('settings')->where('option_name', 'LIKE', 'customerReq_real'.'%')->where('option_value', 'show')->get();
            $getCustomerRealEstateInfo = DB::table('requests')
                ->leftJoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->leftJoin('cities', 'cities.id', '=', 'real_estats.city')
                ->leftJoin('real_types', 'real_types.id', '=', 'real_estats.type')
                ->where('requests.customer_id', $customerId)
                ->select('real_estats.*', 'cities.value as city_name', 'real_types.value as real_estate_type')
                ->first();
            $getCustomerFundingSetting = DB::table('settings')->where('option_name', 'LIKE', 'customerReq_funding'.'%')->where('option_value', 'show')->get();
            $getCustomerFundingInfo = DB::table('fundings')
                ->leftJoin('funding_sources', 'funding_sources.id', '=', 'fundings.funding_source')
                ->select('fundings.*', 'funding_sources.value as funding_source_value')
                ->first();
            $getRequestId = DB::table('requests')->where('customer_id', $customerId)->select('id')->first();
            $documents = DB::table('documents')->where('req_id', '=', $getRequestId->id)
                ->select('documents.*')
                ->first();
            return response()->json([
                'code'    => 200,
                'status'  => true,
                'message' => null,
                'payload' => [
                    'customer_info_settings' => $getCustomerInfoSettings,
                    'customers_info'         => $getCustomerInfo,
                    'customer_real_estate_settings' => $getRealEstateSetting,
                    'customer_real_estate_info'     => $getCustomerRealEstateInfo,
                    'customer_funding_settings' => $getCustomerFundingSetting,
                    'customer_funding_info'     => $getCustomerFundingInfo,
                    'documents' => $documents,
                ],
            ], 200);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function getAllNotifications(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $validatedData = Validator::make($request->only(['page']), [
                'page' => 'required|integer',
            ], [
                'page.required' => 'page id is required',
                'page.integer'  => 'page id must be an integer value',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            $day = now('Asia/Riyadh');
            $today = date("Y-m-d", strtotime($day));
            $current = date("H:i", strtotime($day));
            $now = date("Y-m-d H:i", strtotime($today.' '.$current));
            $notifications = DB::table('notifications')
                ->where('recived_id', $customerId)
                ->where('status', 0) // new
                ->where('reminder_date', '>=', $now)
                ->orWhere('reminder_date', '=', null)
                ->orderBy('id', 'DESC')
                ->select(
                    'id', 'value', 'status'
                )
                ->paginate(10);
            if ($notifications->count() == 0) {
                return self::errorResponse(422, false, "لا يوجد لديك تنبيهات بالوقت الحالي !", null);
            }
            else {
                return $this->responseWithPagination($notifications, $notifications->all(), $request->page);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function getNotificationsCount()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $day = now('Asia/Riyadh');
            $today = date("Y-m-d", strtotime($day));
            $current = date("H:i", strtotime($day));
            $now = date("Y-m-d H:i", strtotime($today.' '.$current));
            $notifications = DB::table('notifications')
                ->where('recived_id', $customerId)
                ->where('status', 0) // new
                ->where('reminder_date', '>=', $now)
                ->orWhere('reminder_date', '=', null)
                ->count();
            return self::successResponse(200, true, null, $notifications);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function markNotificationAsRead(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $validatedData = Validator::make($request->only(['notification_id']), [
                'notification_id' => 'required|integer',
            ], [
                'notification_id.required' => 'notification id is required',
                'notification_id.integer'  => 'notification id must be an integer value',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            $notification = DB::table('notifications')
                ->where('id', $request->notification_id)
                ->where('status', '=', 0)
                ->first();
            if ($notification) {
                DB::table('notifications')->where('id', $request->notification_id)->update(['status' => 1]);
                return self::successResponse(200, true, "تم تحديث حالة الإشعار كمقروء", null);
            }
            else {
                return self::errorResponse(422, false, "incorrect notification id", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function deleteNotification(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $validatedData = Validator::make($request->only(['notification_id']), [
                'notification_id' => 'required|integer',
            ], [
                'notification_id.required' => 'notification id is required',
                'notification_id.integer'  => 'notification id must be an integer value',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            $deleteNotification = DB::table('notifications')
                ->where('id', $request->notification_id)
                ->where('recived_id', $customerId)
                ->first();
            if ($deleteNotification) {
                $deleteNotification = DB::table('notifications')
                    ->where('id', $request->notification_id)
                    ->where('recived_id', $customerId)
                    ->delete();
                return self::successResponse(200, true, "تم حذف الإشعار بنجاح", null);
            }
            else {
                return self::errorResponse(422, false, "incorrect notification id, Or Not Authorize to delete this notification", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function helpDeskLoggedInCustomer(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
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
                'customer_id' => $customerId,
            ]);
            if ($helpDeskRequest) {
                $admins = MyHelpers::getAllActiveAdmin();
                foreach ($admins as $admin) {
                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::guest_trans('You have new help desk request'),
                                                         'recived_id' => $admin->id,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 8,
                                                         'req_id'     => $helpDeskRequest ? $helpDeskRequest->id : null,
                    ]);
                    $emailNotify = MyHelpers::sendEmailNotifiaction('new_help_desk', $admin->id,
                        'لديك طلب دعم فني جديد  ', 'طلب دعم فني ');
                }
                return self::successResponse(200, true, "تم تسجيل رسالتكم بنجاح", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function customerNeedToEditRequestInfo()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $requestId = DB::table('requests')->where('customer_id', $customerId)->first();
            $previousNotify = DB::table('notifications')
                ->where('req_id', $requestId->id)
                ->where('type', 6)
                ->where('status', 0)
                ->where('recived_id', $requestId->user_id)
                ->get();
            if ($previousNotify->count() == 0) {
                DB::table('notifications')->insert([
                    'value'      => 'عميلك يحتاج إلى تعديل بياناته',
                    'recived_id' => $requestId->user_id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 6,
                    'req_id'     => $requestId->id,
                ]);
                return self::successResponse(200, true, "تم إرسال طلبك بنجاح ، سيتم التواصل بك قريبا", null);
            }
            return self::successResponse(200, true, "لديك طلب تعديل بيانات سابقاً ، يرجى الإنتظار", null);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function postNewChatFile(Request $request)
    {
        $validatedData = Validator::make($request->only(['file']), [
            'file' => 'required',
        ], [
            'file.required' => 'الملف مطلوب',

        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $requestId = DB::table('requests')->where('customer_id', $customerId)->first();
            if ($request->hasFile('file')) {
                $asset = asset('/');
                $file = $request->file('file');
                $time = microtime('.') * 10000;
                $message = $time.'.'.strtolower($file->getClientOriginalExtension());
                $destination = 'storage/chat/';
                $file->move($destination, $message);

                $data = new ChatFiles();
                $data->file_name = $message;
                $data->message_id = '1';
                $data->user_id = $requestId->user_id;
                $data->customer_id = $customerId;
                $data->created_at = Carbon::now('Asia/Riyadh');
                if ($data->save()) {
                    $getFiles = ChatFiles::where('id', $data->id)
                        ->select(
                            DB::raw("CONCAT('$asset', 'storage/chat/' ,file_name) as file_url"),
                            'created_at'
                        )
                        ->first();
                }
                return self::successResponse(200, true, "تم حفظ الملف بنجاح", $getFiles);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function checkCustomerRequestConditions()
    {

        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $class_id_agent = null;
            $question = null;
            $answer = null;
            $checkClassIdAgent = DB::table('requests')->where('customer_id', $customerId)->first();
            if ($checkClassIdAgent->class_id_agent == 9) {
                $class_id_agent = 9;
                $question = "تريد إرفاقها ؟";
                $answer = "نعم لدي الأوراق المطلوبة";
            }
            elseif ($checkClassIdAgent->class_id_agent == 15) {
                if ($checkClassIdAgent->customer_found_property == 0) {
                    // call api update-found-property
                    $class_id_agent = 15;
                    $question = "هل وجدت عقار ؟";
                    $answer = "نعم وجدت عقار";
                }
                else {
                    $class_id_agent = 15;
                    $question = "سيتم التواصل معك قريباً";
                    $answer = "";
                }
            }
            elseif ($checkClassIdAgent->class_id_agent == 16) {
                if ($checkClassIdAgent->customer_resolve_problem === null) {
                    // call api update-customer-resolve-problem
                    $class_id_agent = 16;
                    $question = "هل تم حل المشكلة ؟";
                    $answer = "نعم ، حللت المشكلة";
                }
                else {
                    $class_id_agent = 16;
                    $question = "سيتم التواصل معك قريباً";
                    $answer = "";
                }
            }
            elseif ($checkClassIdAgent->class_id_agent == 13) {

                if (AskAnswer::where(['batch' => 0, 'request_id' => $checkClassIdAgent->id, 'customer_id' => $customerId])->count() == 0) {
                    if ($checkClassIdAgent->customer_want_to_reject_req === null) {
                        // go to survey
                        $class_id_agent = 13;
                        $question = "هل أنت راغب فعلًا من إلغاء الطلب؟ً";
                        $answer = "";
                    }
                    elseif ($checkClassIdAgent->customer_want_to_reject_req === 0) {
                        $class_id_agent = 13;
                        $question = " سيتم التواصل معك قريبا";
                        $answer = "";
                    }
                    elseif ($checkClassIdAgent->customer_want_to_reject_req == 1 && $checkClassIdAgent->customer_reason_for_rejected != null) {
                        $class_id_agent = 13;
                        $question = " شكراً لك";
                        $answer = "";
                    }
                    else {
                        $class_id_agent = 13;
                        $question = "";
                        $answer = "";
                    }

                }
                else {
                    $class_id_agent = 13;
                    $question = "سيتم التواصل معك قريباً";
                    $answer = "";
                }
            }
            else {
                $class_id_agent = null;
                $question = "";
                $answer = "";
            }
            return response()->json([
                'code'    => 200,
                'status'  => true,
                'message' => "",
                'payload' => [
                    'class_id' => $class_id_agent,
                    'question' => $question,
                    'answer'   => $answer,
                ],
            ], 200);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function updateFoundProperty(Request $request)
    {
        $validatedData = Validator::make($request->only(['customer_found_property']), [
            'customer_found_property' => 'required|in:1',
        ], [
            'customer_found_property.required' => 'الإجابة مطلوبة',
            'customer_found_property.in'       => 'الإجابة بنعم فقط',

        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $updateResult = DB::table('requests')->where([
                ['customer_id', '=', $customerId],
            ])->update([
                'customer_found_property' => $request->customer_found_property,
            ]);
            if ($updateResult) {
                $reqinfo = DB::table('requests')
                    ->where('customer_id', $customerId)
                    ->first();
                DB::table('notifications')->insert([
                    'value'      => 'عميلك وجد عقار',
                    'recived_id' => $reqinfo->user_id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 5,
                    'req_id'     => $reqinfo->id,
                ]);
            }
            return self::successResponse(200, true, "تم التحديث بنجاح ، سيتم التواصل بك قريبا", null);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function updateCustomerResolveProblem(Request $request)
    {
        $validatedData = Validator::make($request->only(['customer_resolve_problem']), [
            'customer_resolve_problem' => 'required|in:1',
        ], [
            'customer_resolve_problem.required' => 'الإجابة مطلوبة',
            'customer_resolve_problem.in'       => 'الإجابة بنعم فقط',

        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $updateResult = DB::table('requests')->where([
                ['customer_id', '=', $customerId],
            ])->update([
                'customer_resolve_problem' => 1,
            ]);
            if ($updateResult) {
                $reqinfo = DB::table('requests')
                    ->where('customer_id', $customerId)
                    ->first();
                DB::table('notifications')->insert([
                    'value'      => 'عميلك حل مشكلة التمويل',
                    'recived_id' => $reqinfo->user_id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 5,
                    'req_id'     => $reqinfo->id,
                ]);
            }
            return self::successResponse(200, true, "تم التحديث بنجاح ، سيتم التواصل بك قريبا", null);
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

}
