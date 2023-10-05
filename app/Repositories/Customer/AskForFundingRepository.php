<?php

namespace App\Repositories\Customer;

use App\customer as Customer;
use App\CustomersPhone;
use App\funding as Funding;
use App\funding_source;
use App\GuestCustomer;
use App\HelperFunctions\Helper;
use App\Http\Requests\Customer\AskForFundingWeb;
use App\Http\Requests\Customer\NewFundingCustomerWebRequest;
use App\Interfaces\Customer\AskForFundingInterface;
use App\joint as Joint;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\RequestJob;
use App\real_estat as RealEstats;
use App\request as Request;
use App\requestHistory;
use App\salary_source as SalarySource;
use App\Setting;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MyHelpers;

class AskForFundingRepository implements AskForFundingInterface
{
    use ResponseAPI;

    //********************************************************************************
    // Hasbah Site APIS
    //********************************************************************************
    public function getSalarySources()
    {
        try {
            $getSalarySources = SalarySource::select('id', 'value')->get();
            return $this->success(" ", $getSalarySources);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }

    }

    public function newFundingCustomerWeb(NewFundingCustomerWebRequest $request)
    {
        try {
            //DB::beginTransaction();
            $countMobiles = Customer::where(['mobile' => $request->mobile])->count();
            $countMobiles += CustomersPhone::where(['mobile' => $request->mobile])->count();

            $guestCustomer = GuestCustomer::query()->updateOrCreate(['mobile' => $request->mobile], [
                'name'          => $request->name,
                'has_request'   => $countMobiles,
                'mobile'        => $request->mobile,
                'birth_date'    => $request->birth_date,
                'email'         => $request->email,
                'work'          => $request->work,
                'salary'        => $request->salary,
                'military_rank' => $request->military_rank ?? null,
            ]);


            try {
                if (!$guestCustomer->wasRecentlyCreated) {
                    $guestCustomer->increment('count');
                    $guestCustomer->created_at = now();
                    $guestCustomer->status = 0;
                    $guestCustomer->save();
                }
            }
            catch (\Exception $exception) {
            }
           // DB::commit();
            return $this->success('تم الاضافة بنجاح', 0);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function askFundingWeb(AskForFundingWeb $request)
    {

        $checkRequest = null;
        $customerRequest = null;
        $checkPostRequestsIsAchieveCondition = null;
        $data = $this->getMatchedBank($request->funding_source);

        if ($data != null) {
            foreach (funding_source::all() as $item) {
                if ($item->value == $data['name']) {
                    $request->merge(['funding_source' => $item->id]);
                }
            }
        }
        $request->merge([
            'source'          => \App\Models\Request::HASBAH_SOURCE,
            'collaborator_id' => null,
            'request_source'  => null,
        ]);
        try {
            $check = $this->checkMobileForHasbah($request->mobile, $request->source);

            $user_id = getLastAgentOfDistribution();
            if ($check == 1) {
                $passText = Str::random(8);
                $requestPost = Helper::checkPostRequest($request);
                $checkPostRequestsIsAchieveCondition = MyHelpers::check_is_request_acheive_condition($requestPost);

                $customer = $this->createCustomerAccount($requestPost, $passText, $user_id);
                $addNewJoint = Joint::create(['created_at' => (Carbon::now('Asia/Riyadh'))]);
                if ($request->owning_property == null) {
                    $owning_property = 'no';
                }
                else {
                    $owning_property = $request->owning_property;
                }
                $realEstate = RealEstats::create(['owning_property' => $owning_property, 'created_at' => (Carbon::now('Asia/Riyadh'))]);
                $newFunding = $this->createFunding($request);
                $requestSearching = RequestSearching::create()->id;
                if ($checkPostRequestsIsAchieveCondition) {
                    $customerRequest = $this->createNewCustomerRequestForHasbah($customer, $user_id, $request, $addNewJoint, $realEstate, $requestSearching, $newFunding);
                    setLastAgentOfDistribution($user_id);
                    $this->addRecordsRelatedToFunding($customerRequest, $request, $customer);
                    $this->addRequestHistory($customerRequest->id, $user_id);
                }
                else {
                    $customerRequest = $this->saveNewPendingRequestForHasbah($request, $customer, $user_id, $addNewJoint, $realEstate, $requestSearching, $newFunding);
                    setLastAgentOfDistribution($user_id, !0);
                    $this->addRequestHistory($customerRequest->id, $user_id);
                }
            }
            else {
                if (
                    !($customer = Customer::where('mobile', $request->mobile)->first())
                    && ($phone = CustomersPhone::where('mobile', $request->mobile)->first())) {
                    $customer = Customer::find($phone->customer_id)->first();
                }

                if (!($checkRequest = Request::where('customer_id', $customer->id)->first())) {
                    $checkRequest = PendingRequest::where('customer_id', $customer->id)->first();
                }

                if ($checkRequest) {
                    if ($checkRequest->is_freeze && method_exists($checkRequest, 'createJob')) {
                        $checkRequest->createJob(RequestJob::BACK_FROM_FROZEN_BY_REGISTER_AUTO, ['source_back' => RequestJob::SOURCE_HASBAH]);
                    }
                    else {
                        method_exists($checkRequest, 'createJob') && $checkRequest->createJob(RequestJob::CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE, ['source_back' => RequestJob::SOURCE_HASBAH]);
                        if ($checkRequest->class_id_agent != 16 && $checkRequest->class_id_agent != 13) {
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
                }
            }
            try {
                GuestCustomer::where('mobile', $request->mobile)->delete();
            }
            catch (\Exception $exception) {
            }
            return $this->success($check, $user_id);
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }



    public function getMatchedBank($bank_id)
    {
        $client = new \GuzzleHttp\Client();
        // $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank';
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $bank = json_decode($response->getBody(), true);
        foreach ($bank['data'] as $datum) {
            if ($datum['id'] == $bank_id) {
                return $datum;
            }
        }
        return null;
    }

    public function checkMobileForHasbah($mobile, $source)
    {
        $status = 1;
        $customer = Customer::where('mobile', $mobile)->first();
        $phones = CustomersPhone::where('mobile', $mobile)->first();
        if ($phones) {
            $customer = Customer::find($phones->customer_id)->first();
            $status = 2;
        }
        if ($customer) {
            $status = 2;
            $checkRequest = Request::where('customer_id', $customer->id)->first();
            if ($checkRequest) {
                if ($checkRequest->source == $source) {
                    $status = 3;
                }else{
                    $status = 2;
                }
            }
            else {
                $checkPending = PendingRequest::where('customer_id', $customer->id)->first();
                if ($checkPending) {
                    if ($checkPending->source == $source) {
                        $status = 3;
                    }else{
                        $status = 2;
                    }
                }
            }
        }
        return $status;
    }

    public function createCustomerAccount($requestPost, $passText, $user_id)
    {
        return Customer::create([
            'name'                   => $requestPost->name,
            'username'               => 'customer_'.rand(10000000, 99999999),
            'password'               => Hash::make($passText),
            'pass_text'              => $passText,
            'birth_date_higri'       => $requestPost->birth_hijri,
            'mobile'                 => $requestPost->mobile,
            'email'                  => $requestPost->email,
            'work'                   => $requestPost->work,
            'salary'                 => $requestPost->salary,
            'salary_id'              => $requestPost->salary_id,
            'is_supported'           => $requestPost->is_supported,
            'has_obligations'        => $requestPost->has_obligations,
            'has_financial_distress' => $requestPost->has_financial_distress,
            'user_id'                => $user_id,
            'region_ip'              => null,
            'welcome_message'              => 2,
            'created_at'             => (Carbon::now('Asia/Riyadh')),
        ]);
    }

    public function createFunding($request)
    {
        $newFunding = Funding::create([
            'flexiableFun_cost'                 => $request->net_loan_total, // صافي مبلغ التمويل
            'monthly_in'                        => $request->installment, // القسط الشهري
            'funding_duration'                  => $request->funding_years, // مدة التمويل بالسنوات
            'personal_salary_deduction'         => $request->personal_salary_deduction, // نسبة استقطاع صافي الراتب شخصي
            'monthly_installment_after_support' => $request->installment_after_support, // القسط الشهري بعد الدعم
            'personal_monthly_installment'      => $request->personal_installment, // القسط الشهري شخصي
            'realFun_pre'                       => $request->profit, // نسبة المرابحة
            'personalFun_pre'                   => $request->personal_profit,  // نسبة المرابحة شخصي
            'personalFun_cost'                  => $request->personal_net_loan_total, // صافي مبلغ التمويل الشخصي
            'funding_source'                    => $request->funding_source,
            'realFun_cost'                      => $request->flexible_loan_total, // التمويل العقاري مرن
            'ded_pre'                           => $request->salary_deduction, // نسبة استقطاع صافي الراتب
            'funding_months'                    => $request->funding_months, // مدة التمويل بالأشهر
            'personal_funding_months'           => $request->personal_funding_months, // مدة التمويل بالأشهر شخصي
            'product_code'                      => $request->product_type_code,
            'created_at'                        => (Carbon::now('Asia/Riyadh')),
        ]);
        return $newFunding;
    }

    public function createNewCustomerRequestForHasbah($customer, $user_id, $request, $addNewJoint, $realEstate, $requestSearching, $newFunding)
    {
        return Request::create([
            'statusReq'    => 0,
            'customer_id'  => $customer->id,
            'user_id'      => $user_id,
            'source'       => $request->source,
            'req_date'     => today('Asia/Riyadh')->format('Y-m-d'),
            'created_at'   => now('Asia/Riyadh'),
            'agent_date'   => now('Asia/Riyadh'),
            'joint_id'     => $addNewJoint->id,
            'real_id'      => $realEstate->id,
            'searching_id' => $requestSearching,
            //'collaborator_id' => 269,
            'fun_id'       => $newFunding->id,
        ]);
    }

    public function addRecordsRelatedToFunding($saveNewCustomerRequest, $request, $customer)
    {
        $net_loan_total = $this->records($saveNewCustomerRequest->id, 'fundFlex', $request->net_loan_total, $customer->id);
        $installment = $this->records($saveNewCustomerRequest->id, 'fundMonth', $request->installment, $customer->id);
        $funding_years = $this->records($saveNewCustomerRequest->id, 'fundDur', $request->funding_years, $customer->id);
        $personal_salary_deduction = $this->records($saveNewCustomerRequest->id, 'personal_salary_deduction', $request->personal_salary_deduction, $customer->id);
        $installment_after_support = $this->records($saveNewCustomerRequest->id, 'installment_after_support', $request->installment_after_support, $customer->id);
        $personal_installment = $this->records($saveNewCustomerRequest->id, 'personal_installment', $request->personal_installment, $customer->id);
        $profit = $this->records($saveNewCustomerRequest->id, 'fundRealPre', $request->profit, $customer->id);
        $personal_profit = $this->records($saveNewCustomerRequest->id, 'fundPersPre', $request->personal_profit, $customer->id);
        $personal_net_loan_total = $this->records($saveNewCustomerRequest->id, 'fundPers', $request->personal_net_loan_total, $customer->id);
        $funding_source = $this->records($saveNewCustomerRequest->id, 'funding_source', $request->funding_source, $customer->id);
        $flexible_loan_total = $this->records($saveNewCustomerRequest->id, 'fundReal', $request->flexible_loan_total, $customer->id);
        $salary_deduction = $this->records($saveNewCustomerRequest->id, 'fundDed', $request->salary_deduction, $customer->id);
        $funding_months = $this->records($saveNewCustomerRequest->id, 'funding_months', $request->funding_months, $customer->id);
        $personal_funding_months = $this->records($saveNewCustomerRequest->id, 'personal_funding_months', $request->personal_funding_months, $customer->id);
        $product_type_code = $this->records($saveNewCustomerRequest->id, 'product_type', $request->product_type_code, $customer->id);
        return true;
    }
    //*******************************************************************
    // Task-33
    //*******************************************************************

    public function records($reqID, $coloum, $value, $customerId)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')->where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment
        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //
        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => $customerId,
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => null,
                'comment'        => 'حاسبة التمويل',
            ]);
        }
        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => $customerId,
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => null,
                    'comment'        => 'حاسبة التمويل',
                ]);
            }
        }
        //  dd($rowOfLastUpdate);
    }

    public function addRequestHistory($reqID, $userID)
    {
        return requestHistory::create([ // add to request history
                                        'title'        => 'إضافة الطلب من موقع الحاسبة',
                                        'user_id'      => null,
                                        'recive_id'    => $userID,
                                        'history_date' => (Carbon::now('Asia/Riyadh')),
                                        'req_id'       => $reqID,
        ]);
    }

    public function saveNewPendingRequestForHasbah($request, $customer, $user_id, $addNewJoint, $realEstate, $requestSearching, $newFunding)
    {
        return PendingRequest::create([
            'statusReq'    => 0,
            'customer_id'  => $customer->id,
            'user_id'      => $user_id,
            'source'       => $request->source,
            'req_date'     => today('Asia/Riyadh')->format('Y-m-d'),
            'created_at'   => now('Asia/Riyadh'),
            'joint_id'     => $addNewJoint->id,
            'real_id'      => $realEstate->id,
            'searching_id' => $requestSearching,
            'fun_id'       => $newFunding->id,
            //'collaborator_id' => 269,
        ]);
    }

    public function getFieldsHasbahSetting()
    {
        try {
            $getAskForFundingSettings = Setting::where('option_name', 'LIKE', 'askforfunding_'.'%')->where('option_value', 'show')->get();
            return $this->success(" ", $getAskForFundingSettings);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }
}
