<?php

namespace App\Http\Controllers;

use App\customer;
use App\funding;
use App\Helpers\MyHelpers;
use App\joint;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\real_estat;
use App\realType;
use App\request as req;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

//to take date

class OtaredController extends Controller
{

    private $repateCounter = 0;
    private $recordCounter = 0;

    public function oldApi()
    {

        $this->recordCounter = 0;
        $this->repateCounter = 0;

        // *** otared ID = 17 *****
        $otaredID = 17;

        $client = new Client();
        $todayDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        //$todayDate= '2020-03-15';

        $otaredAPI = 'http://otared.sa/api/customers-service/get-DemandClassification';

        // Create a request with auth credentials
        //$request = $client->get('http://otared.sa/api/customers-service/get-DemandClassification',['auth'=>['username','password']]);

        $request = $client->get($otaredAPI.'?result_date='.$todayDate);

        // Get the actual response without headers
        $response = $request->getBody();
        $get_result_arr[] = json_decode($response, true); // convert to array

        $noPage = $get_result_arr[0]['last_page'];
        $total_result = $get_result_arr[0]['total']; // number of data pages

        if ($total_result != 0) {

            if ($this->findNextAgent($otaredID) != null) { // no sales agent that avalible to recive otared requests

                for ($i = 1; $i <= $noPage; $i++) {

                    $get_result_arr = null;

                    $request = $client->get($otaredAPI.'?result_date='.$todayDate.'&page='.$i);
                    // Get the actual response without headers
                    $response = $request->getBody();
                    $get_result_arr[] = json_decode($response, true); // convert to array

                    $arrayData = $get_result_arr[0]['data'];

                    //dd($get_result_arr);

                    $this->addRecord($arrayData, $otaredID);
                }

                ///////////////////////

            }
            else {
                //echo ('-- no sales agent avalible to recive it --');
            }

            echo('-- first :'.$this->recordCounter.'------ second:'.$this->repateCounter);
        }
        else {
            //echo ('-- no request --');
        }
    }

    public function findNextAgent($colID)
    {

        // To get user_id for last request

        $last_req_id = DB::table('requests')->where('collaborator_id', $colID)->max('id'); // latest request_id

        //dd(( $last_req_id));

        if ($last_req_id != null) {
            $last_req = DB::table('requests')->where('id', $last_req_id)->first(); // latest request object
            $last_user_id = $last_req->user_id;

            //dd(( $last_user_id));

            $maxValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                ->join('users', 'users.id', '=', 'user_collaborators.user_id')->max('user_collaborators.user_id'); // last user id (Sale Agent User)

            $minValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                ->join('users', 'users.id', '=', 'user_collaborators.user_id')->min('user_collaborators.user_id'); // first user id (Sale Agent User)

            if ($last_user_id == $maxValue) {
                $user_id = $minValue;
            }
            else {

                // get next user id
                $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                    ->where('user_collaborators.user_id', '>', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                    ->min('user_collaborators.user_id');

                if ($user_id == null) {
                    $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                        ->where('user_collaborators.user_id', '<', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                        ->min('user_collaborators.user_id');
                }
            }
        }
        else {
            $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                ->where('user_collaborators.collaborato_id', $colID)
                ->min('user_collaborators.user_id');

            if ($user_id == null) {
                $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                    ->where('user_collaborators.user_id', '<', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                    ->min('user_collaborators.user_id');
            }
        }

        return $user_id;
    }

    public function addRecord($arrayData, $otaredID)
    {

        foreach ($arrayData as $fn) {

            $joinID = null;
            $customerId = null;
            $realID = null;
            $funID = null;

            // echo ('--'.$fn['client_profile']['name']);

            $mobile = $fn['client_profile']['phone'];

            if ($mobile != null) // user not filed this parmeter
            {
                $mobile = substr($mobile, 1);
            } // to remove '0' from begning of mobile number

            if ($this->checkmobile($mobile)) { // return true

                //------------Get Customer Info--------------------------

                $name = $fn['client_profile']['name'];
                $birthdate = $fn['client_profile']['birthdate'];
                $birthdate = Carbon::parse($birthdate)->format('Y-m-d');
                $birthdate_hijri = $fn['client_profile']['hajribirthdate'];
                $birthdate_hijri = Carbon::parse($birthdate_hijri)->format('Y-m-d');
                $sex = $fn['client_profile']['gender'];
                $salaryBank = $this->salaryBank($fn['mainincomes'][0]['bank']['name']);
                $work = $fn['mainincomes'][0]['work_category']['name'];
                $employer = null;
                $military_rank = null;

                if ($work == 'مدني') // to get which work that customer work in it
                {
                    $employer = $this->madanyWork($fn['mainincomes'][0]['employer']['name']);
                }

                elseif ($work == 'عسكري') // to get which work that customer work in it
                {
                    $military_rank = $this->askaryWork($fn['mainincomes'][0]['employer']['name']);
                } // because no sub work for Askary work !

                else {
                    $employer = $fn['mainincomes'][0]['employer']['name'];
                }

                //----start salary----

                $netSalary = $fn['mainincomes'][0]['netincome'];
                $houseAllowance = $fn['mainincomes'][0]['housingallowance'];
                $other = $fn['mainincomes'][0]['otherallowances'];
                $transAllowance = $fn['mainincomes'][0]['transportation_allowance'];

                $salary = $netSalary + $houseAllowance + $other + $transAllowance;

                //---end salary -------

                //----start joint-----

                if ($fn['is_have_joint'] == true) { // check if has joint

                    $jointname = $fn['joints'][0]['name'];

                    $jointmobile = $fn['joints'][0]['mobilenumber'];
                    if ($jointmobile != null) // user not filed this parmeter
                    {
                        $jointmobile = substr($jointmobile, 1);
                    } // to remove '0' from begning of mobile number

                    $jointbirthdate = $fn['joints'][0]['birthdate'];
                    $jointbirthdate = Carbon::parse($jointbirthdate)->format('Y-m-d');
                    $jointbirthdate_hijri = $fn['joints'][0]['Hajribirthdate'];
                    $jointbirthdate_hijri = Carbon::parse($jointbirthdate_hijri)->format('Y-m-d');

                    $jointBank = $this->salaryBank($fn['joints'][0]['bank']['name']);
                    $jointsalary = $fn['joints'][0]['netincome'];

                    $jointwork = $fn['joints'][0]['work_category']['name'];
                    $jointemployer = null;
                    $jointmilitary_rank = null;

                    if ($jointwork == 'مدني') // to get which work that customer work in it
                    {
                        $jointemployer = $this->madanyWork($fn['joints'][0]['employer']['name']);
                    }

                    elseif ($jointwork == 'عسكري') // to get which work that customer work in it
                    {
                        $jointmilitary_rank = $this->askaryWork($fn['joints'][0]['employer']['name']);
                    } // because no sub work for Askary work !

                    else {
                        $jointemployer = $fn['joints'][0]['employer']['name'];
                    }
                }

                //----end joint------

                //----start real estate----

                $realAge = $fn['property_informations'][0]['age'];
                $realCost = $fn['property_informations'][0]['value'];
                $realType = $fn['property_informations'][0]['property_type']['name'];

                //----end real estate----

                //----start funding info----

                $fundingBank = $this->fundBank($fn['bank']['name']);

                //----end funding info----

                //----start city info----

                $realCity = $this->cityReal($fn['property_informations'][0]['property_places'][0]['city']['name_arabic']);

                //----end city info----

                //----------------End Customer Info--------------------------

                //------------------Create Customer---------------------

                $customerId = DB::table('customers')->insertGetId(
                    [
                        'user_id'          => $otaredID,
                        'name'             => $name,
                        'mobile'           => $mobile,
                        'sex'              => $sex,
                        'birth_date'       => $birthdate,
                        'birth_date_higri' => $birthdate_hijri,
                        'work'             => $work,
                        'salary_id'        => $salaryBank,
                        'salary'           => $salary,
                        'military_rank'    => $military_rank,
                        'welcome_message'        => 2,
                        'created_at'       => (Carbon::now('Asia/Riyadh')),

                    ]
                );

                //------------------End Create Customer---------------------

                //------------------Create Joint---------------------

                if ($fn['is_have_joint'] == true && !empty($customerId)) {

                    $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        [ //add it once use insertGetId
                          'name'             => $jointname,
                          'mobile'           => $jointmobile,
                          'birth_date'       => $jointbirthdate,
                          'birth_date_higri' => $jointbirthdate_hijri,
                          'work'             => $jointwork,
                          'salary_id'        => $jointBank,
                          'military_rank'    => $jointmilitary_rank,
                          'created_at'       => (Carbon::now('Asia/Riyadh')),
                          'customer_id'      => $customerId,
                        ]
                    );
                }
                elseif (!empty($customerId)) {
                    $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        [ //add it once use insertGetId
                          'created_at'  => (Carbon::now('Asia/Riyadh')),
                          'customer_id' => $customerId,
                        ]
                    );
                }

                //------------------End Create Joint---------------------

                //------------------Create Real estate---------------------

                if (!empty($customerId)) {

                    $realID = DB::table('real_estats')->insertGetId(
                        [
                            'city'        => $realCity,
                            'age'         => $realAge,
                            'cost'        => $realCost,
                            'type'        => $realType,
                            'customer_id' => $customerId,
                            'created_at'  => (Carbon::now('Asia/Riyadh')),
                        ]

                    );
                }

                //------------------End Real estate---------------------

                //------------------Create Funding---------------------

                if (!empty($customerId)) {

                    $funID = DB::table('fundings')->insertGetId(
                        [
                            'funding_source' => $fundingBank,
                            'customer_id'    => $customerId,
                            'created_at'     => (Carbon::now('Asia/Riyadh')),
                        ]

                    );
                }

                //------------------End Funding---------------------

                //------------------Create Request---------------------

                $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
                $userID = $this->findNextAgent($otaredID); // sales agent

                if (!empty($customerId)) {

                    $reqID = DB::table('requests')->insertGetId(
                        [
                            'source'          => 2,
                            'req_date'        => $reqdate,
                            'created_at'      => (Carbon::now('Asia/Riyadh')),
                            'user_id'         => $userID,
                            'customer_id'     => $customerId,
                            'collaborator_id' => $otaredID,
                            'statusReq'       => 0,
                        ]

                    );
                }

                if (!empty($joinID)) {

                    DB::table('requests')->where('id', $reqID)
                        ->update([
                            'joint_id' => $joinID,
                        ]);
                }

                if (!empty($realID)) {

                    DB::table('requests')->where('id', $reqID)
                        ->update([
                            'real_id' => $realID,
                        ]);
                }

                if (!empty($funID)) {

                    DB::table('requests')->where('id', $reqID)
                        ->update([
                            'fun_id' => $funID,
                        ]);
                }

                //------------------End Request---------------------

                $this->recordCounter++;

                $notify = MyHelpers::addNewNotify($reqID, $userID); // to add notification
                $record = MyHelpers::addNewReordOtared($reqID, $userID); // to add new history record
                $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $userID, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');
                $agenInfo = DB::table('users')->where('id', $userID)->first();
                //$pwaPush=MyHelpers::pushPWA($userID, ' يومك سعيد  '.$agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب','agent','fundingreqpage',$reqID);

            }
            else { // return false

                //echo ('-- already excisted --');
                $this->repateCounter++;
            }
        }
    }

    public function checkmobile($mobile)
    {

        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();

        if (empty($checkmobile)) {
            return true;
        } // not existed
        return false; // exsted

    }

    public function salaryBank($bank)
    {

        if ($bank == 'مصرف الراجحي') {
            $bank = 'بنك الراجحي';
        }

        $checkBank = DB::table('salary_sources')->where('value', $bank)->first();

        if (!empty($checkBank)) {
            return $checkBank->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('salary_sources')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $bank,
                ]
            );

            return $resultId;
        }
    }

    public function madanyWork($work)
    {

        $checkWork = DB::table('madany_works')->where('value', $work)->first();

        if (!empty($checkWork)) {
            return $checkWork->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('madany_works')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $work,
                ]
            );

            return $resultId;
        }
    }

    public function askaryWork($work)
    {

        $checkWork = DB::table('military_ranks')->where('value', $work)->first();

        if (!empty($checkWork)) {
            return $checkWork->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('military_ranks')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $work,
                ]
            );

            return $resultId;
        }
    }

    public function fundBank($bank)
    {

        $checkBank = DB::table('funding_sources')->where('value', $bank)->first();

        if (!empty($checkBank)) {
            return $checkBank->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('funding_sources')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $bank,
                ]
            );

            return $resultId;
        }
    }

    public function cityReal($city)
    {

        $checkCity = DB::table('cities')->where('value', $city)->first();

        if (!empty($checkCity)) {
            return $checkCity->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('cities')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $city,
                ]
            );

            return $resultId;
        }
    }

    public function api()
    {

        $this->recordCounter = 0;
        $this->repateCounter = 0;

        // *** otared ID = 17 *****
        $otaredID = 17;

        $client = new Client();
        $todayDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $todayDate = '2020-07-13';
        try{
        // $otaredAPI = 'https://api.bestsmsc.com/api/customers-service/synchronize-with-wassata/';
        $otaredAPI = 'https://calculatorapi.alwsata.com.sa/api/customers-service/synchronize-with-wassata/';

        // Create a request with auth credentials
        //$request = $client->get('http://otared.sa/api/customers-service/get-DemandClassification',['auth'=>['username','password']]);

        $api = $client->get($otaredAPI.'?result_date='.$todayDate);

        // Get the actual response without headers
        $response = $api->getBody();
        $get_result_arr[] = json_decode($response, true); // convert to array

        $convertArr = collect($get_result_arr[0]);
        //dd($convertArr);

        foreach ($convertArr as $collection) {

            $request = collect($collection);

            //
            if ($request->get("mobile") != null && $request->get("mobile") != "") {

                $checkValidMobile = preg_match("/^(5)[0-9]{8}$/", $request->get("mobile"));
                if ($checkValidMobile == 0) {
                    continue;
                }

                $checkExsistedUser = $this->checkCustomerMobile($request->get("mobile"));
                if ($checkExsistedUser) {
                    $this->repateCounter++;
                    continue;
                }
            }
            else {
                continue;
            }
            //

            $arrayCustomer = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("customer_data"));
            $customerDate = collect($arrayCustomer);

            //
            $checkIfSame = $request->get("mobile") != $customerDate->get("mobile");
            if ($checkIfSame) {
                continue;
            }
            //

            //
            $customerRequest = new req;
            $arrayRequest = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get('request_data'));
            $requestData = collect($arrayRequest);
            $customerRequest->fill($requestData->only($customerRequest->getFillable())->toArray())->save();

            $customerRequest->source = 2;
            $customerRequest->collaborator_id = $otaredID;
            $customerRequest->statusReq = 0;
            $customer = $customerRequest->customer;
            //

            //
            $customer = customer::getOrNew($request->get("mobile"));
            $customer->fill($customerDate->only($customer->getFillable())->toArray());
            $customer = $this->fillCustomer($customer);
            $customer->save();
            //

            $customerRequest->customer_id = $customer->id;

            //
            $joint = new joint;
            $arrayJoint = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("joint_data"));
            $jointData = collect($arrayJoint);
            $joint->fill($jointData->only($joint->getFillable())->toArray());
            $joint = $this->fillJoint($joint);
            $joint->save();
            //

            $customerRequest->joint_id = $joint->id;

            //
            $funding = new funding;
            $arrayFunding = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("funding_data"));
            $fundingData = collect($arrayFunding);
            $funding->fill($fundingData->only($funding->getFillable())->toArray());
            if ($funding->funding_source != null) {
                $funding_source = $funding->fundBank($funding->funding_source);
                $funding->funding_source = $funding_source;
            }
            $funding->save();
            //

            $customerRequest->fun_id = $funding->id;

            //
            $real = new real_estat;
            $arrayReal = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("real_data"));
            $realData = collect($arrayReal);
            $real->fill($realData->only($real->getFillable())->toArray());
            $real = $this->fillReal($real);
            $real->has_property = $realData->get('has_property‏');
            $real->save();
            //

            $customerRequest->real_id = $real->id;

            //
            $is_approved = MyHelpers::check_is_request_acheive_condition(
                [
                    'salary'                 => $customer->salary,
                    'birth_hijri'            => $customer->birth_date_higri,
                    'birth_date'             => $customer->birth_date,
                    'work'                   => $customer->work,
                    'is_supported'           => $customer->is_supported,
                    'has_property'           => $real->has_property,
                    'has_joint'              => $customer->has_joint,
                    'has_obligations'        => $customer->has_obligations,
                    'has_financial_distress' => $customer->has_financial_distress,
                ]
            );

            $agent_id = $customerRequest->findNextAgent($otaredID, $is_approved);
            $customerRequest->user_id = $agent_id;

            $searching_id = RequestSearching::create()->id;

            $this->recordCounter++;

            # Finally
            if ($is_approved) { // if request achieve request condition
                $customerRequest->searching_id = $searching_id;
                $customerRequest->save();
                $customerRequest->updateCreated_at($customerRequest->id);
                $notify = MyHelpers::addNewNotify($customerRequest->id, $customerRequest->user_id); // to add notification
                $record = MyHelpers::addNewReordOtared($customerRequest->id, $customerRequest->user_id); // to add new history record
                $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $customerRequest->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');//email notification
                $agenInfo = DB::table('users')->where('id', $customerRequest->user_id)->first();
                //$pwaPush=MyHelpers::pushPWA($customerRequest->user_id, ' يومك سعيد  '.$agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب','agent','fundingreqpage',$customerRequest->id);

                #ADD CUSTOMER RECORDS OF REQUEST
                $this->addCustomerRecords($customer, $customerRequest->id);
                #ADD JOINT RECORDS OF REQUEST
                $this->addJointRecords($joint, $customerRequest->id);
                #ADD REAL RECORDS OF REQUEST
                $this->addRealRecords($real, $customerRequest->id);
                #ADD FUNDING RECORDS OF REQUEST
                $this->addFundingRecords($funding, $customerRequest->id);
            }
            else {

                PendingRequest::create(
                    [
                        'statusReq'       => 0,
                        'customer_id'     => $customerRequest->customer_id,
                        'collaborator_id' => $customerRequest->collaborator_id,
                        'user_id'         => $customerRequest->user_id,
                        'source'          => $customerRequest->source,
                        'req_date'        => $customerRequest->req_date,
                        'joint_id'        => $customerRequest->joint_id,
                        'real_id'         => $customerRequest->real_id,
                        'searching_id'    => $searching_id,
                        'fun_id'          => $customerRequest->fun_id,
                        'created_at'      => Carbon::parse($customerRequest->req_date),
                    ]
                );

                $customerRequest->deleteAfterPending($customerRequest->id);
            }
        }

        echo('-- first not  :'.$this->recordCounter.' ------ second repated:'.$this->repateCounter);
        }catch(\Exception $e){
            echo ('exception');
        }
    }

    public function checkCustomerMobile($mobile)
    {
        //$customer = customer::getOrNew($mobile);

        $customer = DB::table('customers')->where('mobile', $mobile)->first();
        if (!empty($customer)) {
            return true;
        }

        return false;
    }

    public function fillCustomer($customer)
    {

        // *** otared ID = 17 *****
        $otaredID = 17;
        //**** */

        if ($customer->birth_date_higri != null) {
            $customer->birth_date_higri = Carbon::parse($customer->birth_date_higri)->format('Y-m-d');
        }

        if ($customer->sex != null) {

            if ($customer->sex == 'انثى') {
                $customer->sex = 'أنثى';
            }
        }

        if ($customer->salary_id != null) {
            $salary_id = $customer->salaryBank($customer->salary_id);
            $customer->salary_id = $salary_id;
        }

        if ($customer->work == 'مدني') {
            $madany_id = $customer->madanyWork($customer->madany_id);
            $customer->madany_id = $madany_id;
        }
        else {
            $customer->madany_id = null;
        }

        if ($customer->work == 'عسكري') {
            $miliraty = $customer->askaryWork($customer->military_rank);
            $customer->military_rank = $miliraty;
        }
        else {
            $customer->military_rank = null;
        }

        $customer->setUserAttribute($otaredID);

        return $customer;
    }

    public function fillJoint($joint)
    {

        if ($joint->birth_date_higri != null) {
            $joint->birth_date_higri = Carbon::parse($joint->birth_date_higri)->format('Y-m-d');
        }

        if ($joint->salary_id != null) {
            $salary_id = $joint->salaryBank($joint->salary_id);
            $joint->salary_id = $salary_id;
        }

        if ($joint->work == 'مدني') {
            $madany_id = $joint->madanyWork($joint->madany_id);
            $joint->madany_id = $madany_id;
        }
        else {
            $joint->madany_id = null;
        }

        if ($joint->work == 'عسكري') {
            $miliraty = $joint->askaryWork($joint->military_rank);
            $joint->military_rank = $miliraty;
        }
        else {
            $joint->military_rank = null;
        }

        return $joint;
    }

    public function fillReal($real)
    {

        if ($real->city != null) {
            $city = $real->findCity($real->city);
            $real->city = $city;
        }

        $realType = $real->type;
        $realStatus = $real->status;

        if ($realStatus == 'غير مكتمل') {
            $real->status = 'عظم';
        }

        if ($realType == 'ارض') {
            $real->type = 'أرض';
        }

        $typeofreal = DB::table('real_types')->where('value', $real->type)->first();

        if (empty($typeofreal)) {
            $newRealType = realType::create([
                'value' => $real->type,
            ]);
            $real->type = $newRealType->id;
        }
        else {
            $real->type = $typeofreal->id;
        }

        return $real;
    }

    public function addCustomerRecords($customer, $reqID)
    {

        if ($customer->name != null) {
            $this->records($reqID, 'customerName', $customer->name);
        }
        if ($customer->birth_date_higri != null) {
            $this->records($reqID, 'birth_hijri', $customer->birth_date_higri);
        }
        if ($customer->work != null) {
            $this->records($reqID, 'work', $customer->work);
        }
        if ($customer->salary != 0 && $customer->salary != null) {
            $this->records($reqID, 'salary', $customer->salary);
        }

        if ($customer->is_supported != null) {
            if ($customer->is_supported == 'no') {
                $this->records($reqID, 'support', 'لا');
            }
            if ($customer->is_supported == 'yes') {
                $this->records($reqID, 'support', 'نعم');
            }
        }

        if ($customer->has_obligations != null) {
            if ($customer->has_obligations == 'no') {
                $this->records($reqID, 'obligations', 'لا');
            }
            if ($customer->has_obligations == 'yes') {
                $this->records($reqID, 'obligations', 'نعم');
            }
        }

        if ($customer->has_financial_distress != null) {
            if ($customer->has_financial_distress == 'no') {
                $this->records($reqID, 'distress', 'لا');
            }
            if ($customer->has_financial_distress == 'yes') {
                $this->records($reqID, 'distress', 'نعم');
            }
        }

        if ($customer->obligations_value != 0 && $customer->obligations_value != null) {
            $this->records($reqID, 'obligations_value', $customer->obligations_value);
        }
        if ($customer->financial_distress_value != 0 && $customer->financial_distress_value != null) {
            $this->records($reqID, 'financial_distress_value', $customer->financial_distress_value);
        }
        if ($customer->job_title != 0 && $customer->job_title != null) {
            $this->records($reqID, 'jobTitle', $customer->job_title);
        }

        $getsalaryValue = DB::table('salary_sources')->where('id', $customer->salary_id)->first();
        if (!empty($getsalaryValue)) {
            $this->records($reqID, 'salary_source', $getsalaryValue->value);
        }

        $getaskaryValue = DB::table('askary_works')->where('id', $customer->askary_id)->first();
        if (!empty($getaskaryValue)) {
            $this->records($reqID, 'askaryWork', $getaskaryValue->value);
        }

        $getmadanyValue = DB::table('madany_works')->where('id', $customer->madany_id)->first();
        if (!empty($getmadanyValue)) {
            $this->records($reqID, 'madanyWork', $getmadanyValue->value);
        }

        $getrankValue = DB::table('military_ranks')->where('id', $customer->military_rank)->first();
        if (!empty($getrankValue)) {
            $this->records($reqID, 'rank', $getrankValue->value);
        }
    }

    public function records($reqID, $coloum, $value)
    {

        DB::table('req_records')->insert([
            'colum'          => $coloum,
            'user_id'        => 17,
            'value'          => $value,
            'updateValue_at' => Carbon::now('Asia/Riyadh'),
            'req_id'         => $reqID,
            'user_switch_id' => null,
        ]);
    }

    public function addJointRecords($joint, $reqID)
    {

        if ($joint->name != 0 && $joint->name != null) {
            $this->records($reqID, 'jointName', $joint->name);
        }
        if ($joint->mobile != 0 && $joint->mobile != null) {
            $this->records($reqID, 'jointMobile', $joint->mobile);
        }
        if ($joint->salary != 0 && $joint->salary != null) {
            $this->records($reqID, 'jointSalary', $joint->salary);
        }
        if ($joint->birth_date_higri != 0 && $joint->birth_date_higri != null) {
            $this->records($reqID, 'jointBirth_higri', $joint->birth_date_higri);
        }
        if ($joint->work != 0 && $joint->work != null) {
            $this->records($reqID, 'jointWork', $joint->work);
        }
        if ($joint->job_title != 0 && $joint->job_title != null) {
            $this->records($reqID, 'jointJobTitle', $joint->job_title);
        }

        $getjointfundingValue = DB::table('funding_sources')->where('id', $joint->funding_id)->first();
        if (!empty($getjointfundingValue)) {
            $this->records($reqID, 'jointfunding_source', $getjointfundingValue->value);
        }

        $getjointsalaryValue = DB::table('salary_sources')->where('id', $joint->salary_id)->first();
        if (!empty($getjointsalaryValue)) {
            $this->records($reqID, 'jointsalary_source', $getjointsalaryValue->value);
        }

        $getjointrankValue = DB::table('military_ranks')->where('id', $joint->military_rank)->first();
        if (!empty($getjointrankValue)) {
            $this->records($reqID, 'jointRank', $getjointrankValue->value);
        }

        $getjointaskaryValue = DB::table('askary_works')->where('id', $joint->askary_id)->first();
        if (!empty($getjointaskaryValue)) {
            $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
        }

        $getjointmadanyValue = DB::table('madany_works')->where('id', $joint->madany_id)->first();
        if (!empty($getjointmadanyValue)) {
            $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
        }
    }

    public function addRealRecords($real, $reqID)
    {

        if ($real->name != 0 && $real->name != null) {
            $this->records($reqID, 'realName', $real->name);
        }
        if ($real->mobile != 0 && $real->mobile != null) {
            $this->records($reqID, 'realMobile', $real->mobile);
        }
        $getcityValue = DB::table('cities')->where('id', $real->city)->first();
        if (!empty($getcityValue)) {
            $this->records($reqID, 'realCity', $getcityValue->value);
        }
        if ($real->region != 0 && $real->region != null) {
            $this->records($reqID, 'realRegion', $real->region);
        }
        if ($real->pursuit != 0 && $real->pursuit != null) {
            $this->records($reqID, 'realPursuit', $real->pursuit);
        }
        if ($real->age != 0 && $real->age != null) {
            $this->records($reqID, 'realAge', $real->age);
        }
        if ($real->status != 0 && $real->status != null) {
            $this->records($reqID, 'realStatus', $real->status);
        }
        if ($real->cost != 0 && $real->cost != null) {
            $this->records($reqID, 'realCost', $real->cost);
        }
        $gettypeValue = DB::table('real_types')->where('id', $real->type)->first();
        if (!empty($gettypeValue)) {
            $this->records($reqID, 'realType', $gettypeValue->value);
        }
    }

    public function addFundingRecords($funding, $reqID)
    {

        if ($funding->funding_source != 0 && $funding->funding_source != null) {
            $getfundingValue = DB::table('funding_sources')->where('id', $funding->funding_source)->first();
            if (!empty($getfundingValue)) {
                $this->records($reqID, 'funding_source', $getfundingValue->value);
            }
        }

        if ($funding->funding_duration != 0 && $funding->funding_duration != null) {
            $this->records($reqID, 'fundDur', $funding->funding_duration);
        }

        if ($funding->personalFun_pre != 0 && $funding->personalFun_pre != null) {
            $this->records($reqID, 'fundPersPre', $funding->personalFun_pre);
        }

        if ($funding->personalFun_cost != 0 && $funding->personalFun_cost != null) {
            $this->records($reqID, 'fundPers', $funding->personalFun_cost);
        }

        if ($funding->realFun_pre != 0 && $funding->realFun_pre != null) {
            $this->records($reqID, 'fundRealPre', $funding->realFun_pre);
        }

        if ($funding->realFun_cost != 0 && $funding->realFun_cost != null) {
            $this->records($reqID, 'fundReal', $funding->realFun_cost);
        }

        if ($funding->ded_pre != 0 && $funding->ded_pre != null) {
            $this->records($reqID, 'fundDed', $funding->ded_pre);
        }

        if ($funding->monthly_in != 0 && $funding->monthly_in != null) {
            $this->records($reqID, 'fundMonth', $funding->monthly_in);
        }
    }

    public function otaredSync()
    {

        $this->recordCounter = 0;
        $this->total = 0;

        // *** otared ID = 17 *****
        $otaredID = 17;

        $client = new Client();
        $todayDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        // $todayDate = '2020-04-22';

        $otaredAPI = 'https://calculatorapi.alwsata.com.sa/api/customers-service/synchronize-with-wassata/';

        // Create a request with auth credentials
        //$request = $client->get('http://otared.sa/api/customers-service/get-DemandClassification',['auth'=>['username','password']]);

        $api = $client->get($otaredAPI);

        // Get the actual response without headers
        $response = $api->getBody();
        $get_result_arr[] = json_decode($response, true); // convert to array

        $convertArr = collect($get_result_arr[0]);
        // dd( $request);

        foreach ($convertArr as $collection) {

            $this->total++;

            $request = collect($collection);

            //
            if ($request->get("mobile") != null && $request->get("mobile") != "") {

                $checkValidMobile = preg_match("/^(5)[0-9]{8}$/", $request->get("mobile"));
                if ($checkValidMobile == 0) {
                    continue;
                }

                $checkExsistedUser = $this->checkCustomerMobile($request->get("mobile"));
                if ($checkExsistedUser) {
                    continue;
                }
            }
            else {
                continue;
            }
            //

            $arrayCustomer = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("customer_data"));
            $customerDate = collect($arrayCustomer);

            //
            $checkIfSame = $request->get("mobile") != $customerDate->get("mobile");
            if ($checkIfSame) {
                continue;
            }
            //

            //
            $customerRequest = new req;
            $arrayRequest = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get('request_data'));
            $requestData = collect($arrayRequest);
            $customerRequest->fill($requestData->only($customerRequest->getFillable())->toArray())->save();

            if ($customerRequest->created_at != null) {
                $customerRequest->created_at = Carbon::parse($customerRequest->req_date)->format('Y-m-d').' '.Carbon::parse($customerRequest->created_at)->format('H:i:s');
            }
            else {
                $customerRequest->created_at = Carbon::parse($customerRequest->req_date)->format('Y-m-d').' '.'00:00:00';
            }

            $customerRequest->source = 2;
            $customerRequest->collaborator_id = $otaredID;
            $customerRequest->statusReq = 0;
            $customer = $customerRequest->customer;
            //

            //
            $customer = customer::getOrNew($request->get("mobile"));
            $customer->fill($customerDate->only($customer->getFillable())->toArray());
            $customer = $this->fillCustomer($customer);
            $customer->save();

            //

            $customerRequest->customer_id = $customer->id;

            //
            $joint = new joint;
            $arrayJoint = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("joint_data"));
            $jointData = collect($arrayJoint);
            $joint->fill($jointData->only($joint->getFillable())->toArray());
            $joint = $this->fillJoint($joint);
            $joint->save();
            //

            $customerRequest->joint_id = $joint->id;

            //
            $funding = new funding;
            $arrayFunding = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("funding_data"));
            $fundingData = collect($arrayFunding);
            $funding->fill($fundingData->only($funding->getFillable())->toArray());
            if ($funding->funding_source != null) {
                $funding_source = $funding->fundBank($funding->funding_source);
                $funding->funding_source = $funding_source;
            }
            $funding->save();
            //

            $customerRequest->fun_id = $funding->id;

            //
            $real = new real_estat;
            $arrayReal = array_map(function ($value) {
                return $value === "" ? null : $value;
            }, $request->get("real_data"));
            $realData = collect($arrayReal);
            $real->fill($realData->only($real->getFillable())->toArray());
            $real = $this->fillReal($real);
            $real->has_property = $realData->get('has_property‏');
            $real->save();
            //

            $customerRequest->real_id = $real->id;

            //
            $is_approved = MyHelpers::check_is_request_acheive_condition(
                [
                    'salary'                 => $customer->salary,
                    'birth_hijri'            => $customer->birthdate_hijri,
                    'birth_date'             => $customer->birthdate,
                    'work'                   => $customer->work,
                    'is_supported'           => $customer->is_supported,
                    'has_property'           => $real->has_property,
                    'has_joint'              => $customer->has_joint,
                    'has_obligations'        => $customer->has_obligations,
                    'has_financial_distress' => $customer->has_financial_distress,
                ]
            );

            $agent_id = $customerRequest->findNextAgent($otaredID, $is_approved);
            $customerRequest->user_id = $agent_id;

            $searching_id = RequestSearching::create()->id;

            $this->recordCounter++;

            # Finally
            if ($is_approved) { // if request achieve request condition
                $customerRequest->searching_id = $searching_id;
                $customerRequest->save();
                $customerRequest->updateCreated_at($customerRequest->id);
                MyHelpers::addNewNotify($customerRequest->id, $customerRequest->user_id); // to add notification
                MyHelpers::addNewReordOtared($customerRequest->id, $customerRequest->user_id); // to add new history record
                MyHelpers::sendEmailNotifiaction('new_req', $customerRequest->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');//email notification
                $agenInfo = DB::table('users')->where('id', $customerRequest->user_id)->first();
                //$pwaPush=MyHelpers::pushPWA($customerRequest->user_id, ' يومك سعيد  '.$agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب','agent','fundingreqpage',$customerRequest->id);

                #ADD CUSTOMER RECORDS OF REQUEST
                $this->addCustomerRecords($customer, $customerRequest->id);
                #ADD JOINT RECORDS OF REQUEST
                $this->addJointRecords($joint, $customerRequest->id);
                #ADD REAL RECORDS OF REQUEST
                $this->addRealRecords($real, $customerRequest->id);
                #ADD FUNDING RECORDS OF REQUEST
                $this->addFundingRecords($funding, $customerRequest->id);

            }
            else {

                PendingRequest::create(
                    [
                        'statusReq'       => 0,
                        'customer_id'     => $customerRequest->customer_id,
                        'collaborator_id' => $customerRequest->collaborator_id,
                        'user_id'         => $customerRequest->user_id,
                        'source'          => $customerRequest->source,
                        'req_date'        => $customerRequest->req_date,
                        'joint_id'        => $customerRequest->joint_id,
                        'real_id'         => $customerRequest->real_id,
                        'searching_id'    => $searching_id,
                        'fun_id'          => $customerRequest->fun_id,
                        'created_at'      => Carbon::parse($customerRequest->req_date),
                    ]
                );

                $customerRequest->deleteAfterPending($customerRequest->id);
            }
        }

        return response()->json([
            'status' => true,
            'msg'    => trans('language.Otared Succesffuly', [
                'count'          => $this->total,
                'accepted_count' => $this->recordCounter,
            ]),
        ]);
    }
}
