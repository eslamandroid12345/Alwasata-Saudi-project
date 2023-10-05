<?php

/**
 * Copyright MyTh
 * Website: https://4MyTh.com
 * Email: mythpe@gmail.com
 * Copyright © 2006-2020 MyTh All rights reserved.
 */

namespace App;

use App\Helpers\MyHelpers;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\realType;
use Illuminate\Http\Request;
use Myth\Api\Exceptions\ManagerRequestValidatorException;
use Myth\Api\Transformer\ManagerTransformer;

/**
 * Class TamweelkRequestTransformer
 * @package App
 */
class TamweelkRequestTransformer extends ManagerTransformer
{

    /**
     * array will fill the model when manager sync new model into client
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Http\Request $request
     * @return array
     * @uses @function setRawAttributes
     */
    public static function fillable($model, Request $request): array
    {
        $data = collect($request->get('request_data', []));
        return $data->only($model->getFillable())->toArray();
    }

    /**
     * validate manager request
     * this function must return value of request validation
     * @param \Illuminate\Http\Request $request
     * @param $model as request of json
     * @return bool|string error message
     */
    public static function validate(Request $request, $model)
    {

        // dd( $model->findNextAgent(77));

        $rules = [
            "mobile" => ["required", "mobile"],

            "request_data"          => ["required", "array"],
            "request_data.req_date" => ["required", "date_format:Y-m-d"],
            "request_data.type"     => ["nullable", "string"],

            "joint_data"                  => ["required", "array"],
            "joint_data.name"             => ["nullable", "string"],
            "joint_data.mobile"           => ["nullable", "mobile"],
            "joint_data.birth_date"       => ["nullable"],
            "joint_data.birth_date_higri" => ["nullable"],
            "joint_data.work"             => ["nullable", "string"],
            "joint_data.job_title"        => ["nullable", "string"],
            "joint_data.salary"           => ["nullable", "numeric"],

            // "a" => "عسكري"

            "customer_data"                  => ["required", "array"],
            "customer_data.name"             => ["nullable", "string"],
            "customer_data.mobile"           => ["nullable", "mobile", "same:mobile"],
            /** to check that mobile recived in the outside director is same with this mobile */
            "customer_data.birth_date"       => ["nullable"],
            "customer_data.birth_date_higri" => ["nullable"],
            "customer_data.has_joint"       => ["nullable"],
            "customer_data.has_obligations" => ["nullable"],
            "customer_data.obligations_value"  => ["nullable", "numeric"],
            "customer_data.has_financial_distress" => ["nullable"],
            "customer_data.financial_distress_value"  => ["nullable", "numeric"],
            "customer_data.work"             => ["nullable", "string"],
            "customer_data.job_title"        => ["nullable", "string"],
            "customer_data.is_supported"     => ["nullable", "string"],
            "customer_data.salary"           => ["nullable", "numeric"],

            "real_data"          => ["required", "array"],
            "real_data.name"     => ["nullable", "string"],
            "real_data.mobile"   => ["nullable", "mobile"],
            "real_data.age"      => ["nullable", "numeric"],
            "real_data.cost"     => ["nullable", "numeric"],
            "real_data.pursuit"  => ["nullable", "numeric"],
            "real_data.status"   => ["nullable", "string"],
            "real_data.type"     => ["nullable", "string"],
            "real_data.mortgage" => ["nullable", "string"],
            "real_data.city"     => ["nullable", "string"],
            "real_data.region"   => ["nullable", "string"],

            "funding_data"                  => ["required", "array"],
            "funding_data.funding_duration" => ["nullable", "numeric"],
            "funding_data.personalFun_pre"  => ["nullable", "numeric"],
            "funding_data.realFun_pre"      => ["nullable", "numeric"],
            "funding_data.realFun_cost"     => ["nullable", "numeric"],
            "funding_data.ded_pre"          => ["nullable", "numeric"],
            "funding_data.personalFun_cost" => ["nullable", "numeric"],
            "funding_data.monthly_in"       => ["nullable", "numeric"],

        ];
        // dd($request->validate($rules));
        $request->validate($rules);


        // get the user has this mobile or create a new object of this customer with this mobile number
        $customer = customer::getOrNew($request->get("mobile"));
        //dd($customer);
        if (
            $customer->exists
        ) {
            throw new ManagerRequestValidatorException(__("messages.customer_exists"));
        }
        return true;
    }

    /**
     * Event model will saving in client database
     * @param $model
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function saving($model, Request $request): void
    {
        //
        // $customer = customer::getOrNew($request->get("mobile"));
        // // dd($customer);
        //
        // // save customer
        // $customer->save();
        // // add customer id to $model that indicate to request
        // $model->customer_id = $customer->id;
    }

    /**
     * Event model was saved in client database
     * @param $model
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function saved($model, Request $request): void
    {

        $customer = customer::getOrNew($request->get("mobile"));
        $joint = new joint;
        $funding = new funding;
        $real = new real_estat;

        try {

            // dd($customer);

            // save customer
            $customer->save();
            // add customer id to $model that indicate to request
            $model->customer_id = $customer->id;

   
            $model->source = 'متعاون';
            $model->collaborator_id = 77;
            $model->statusReq = 0;

            $customer = $model->customer;

            // dd($model);

            $jointData = collect($request->get("joint_data"));
            $fundingData = collect($request->get("funding_data"));
            $realData = collect($request->get("real_data"));
            $customerData = $request->get("customer_data");
            $customerData = collect($customerData)->only($customer->getFillable())->toArray(); //array of customer data
            $customer->fill($customerData);

            //dd($customerData);
            //tamweelk ID
            // $customer->user = 77; /** tamweelk ID */

            //dd($request->get("customer_data")['salary']);

            // dd($customer);

            if ($customer->sex != null) {

                if ($customer->sex == 'انثى') $customer->sex = 'أنثى';
            }

            if ($customer->salary_id != null) {
                $salary_id = $customer->salaryBank($request->get("customer_data")['salary_id']);
                $customer->salary_id = $salary_id;
            }

            if ($customer->work == 'مدني') {
                $madany_id = $customer->madanyWork($request->get("customer_data")['madany_id']);
                $customer->madany_id = $madany_id;
            } else {
                $customer->madany_id = null;
            }

            if ($customer->work == 'عسكري') {
                $miliraty = $customer->askaryWork($request->get("customer_data")['military_rank']);
                $customer->military_rank = $miliraty;
            } else {
                $customer->military_rank = null;
            }

            $customer->setUserAttribute(77);

            $customer->save();

            //dd($customer);

            if ($jointData['salary_id'] != null) {
                $salary_id = $joint->salaryBank($request->get("joint_data")['salary_id']);
                $jointData['salary_id'] = $salary_id;
            }

            if ($jointData['work'] == 'مدني') {
                $madany_id = $joint->madanyWork($request->get("joint_data")['madany_id']);
                $jointData['madany_id'] = $madany_id;
            } else {
                $jointData['madany_id'] = null;
            }

            if ($jointData['work'] == 'عسكري') {
                $miliraty = $joint->askaryWork($request->get("joint_data")['military_rank']);
                $jointData['military_rank'] = $miliraty;
            } else {
                $jointData['military_rank'] = null;
            }

            $joint->fill($jointData->only($joint->getFillable())->toArray())->save();

            $joint->save();

            // $joint->refresh();
            $model->joint_id = $joint->id;


            if ($fundingData['funding_source'] != null) {
                $funding_source = $funding->fundBank($request->get("funding_data")['funding_source']);
                $fundingData['funding_source'] = $funding_source;
            }

            $funding->fill($fundingData->only($funding->getFillable())->toArray())->save();

            $funding->save();

            $model->fun_id = $funding->id;

            if ($realData['city'] != null) {
                $city = $real->findCity($request->get("real_data")['city']);
                $realData['city'] = $city;
            }

            $real->fill($realData->only($real->getFillable())->toArray())->save();

            $realType = $real->type;
            $realStatus = $real->status;

            if ($realStatus == 'غير مكتمل') {
                $real->status = 'عظم';
            }

            if ($realType == 'ارض') {
                $real->type = 'أرض';
            }
    
            $typeofreal=DB::table('real_types')->where('value',$real->type)->first();
    
            if (empty($typeofreal)) {
                $newRealType =  realType::create([
                    'value' => $real->type
                ]);
                $real->type =  $newRealType->id;
            }
            else
            $real->type =  $typeofreal->id;


            $real->save();

            $model->real_id = $real->id;

            #
            $customer = customer::find($model->customer_id);

            //dd( $customer);
            // check if request achieve condtion or not
            $is_approved = MyHelpers::check_is_request_acheive_condition(
                [
                    'salary'       => $customer->salary,
                    'birth_hijri'  => $customer->birth_date_higri,
                    'birth_date'   => $customer->birth_date,
                    'work'         => $customer->work,
                    'is_supported' => $customer->is_supported,
                    'has_property' => $real->has_property,
                    'has_joint'    => $customer->has_joint,
                    'has_obligations'    => $customer->has_obligations,
                    'has_financial_distress'    => $customer->has_financial_distress,
                ]
            );

            ///dd( $is_approved);
            $agent_id = $model->findNextAgent(77, $is_approved);
            $model->user_id = $agent_id;

            $searching_id = RequestSearching::create()->id;

            # Finally
            if ($is_approved) { // if request achieve request condition
                $model->searching_id = $searching_id;
                $model->save();
                $model->updateCreated_at($model->id);
                $notify = MyHelpers::addNewNotify($model->id, $model->user_id); // to add notification
                $record = MyHelpers::addNewReordTamweelk($model->id, $model->user_id); // to add new history record
                $emailNotify=MyHelpers::sendEmailNotifiaction('new_req', $model->user_id,'لديك طلب جديد','طلب جديد تم إضافته لسلتك');
                $agenInfo=  DB::table('users')->where('id', $model->user_id)->first();
                //$pwaPush=MyHelpers::pushPWA($model->user_id, ' يومك سعيد  '.$agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب','agent','fundingreqpage',$model->id);

            } else {

                PendingRequest::create(
                    [
                        'statusReq'       => 0,
                        'customer_id'     => $model->customer_id,
                        'collaborator_id' => $model->collaborator_id,
                        'user_id'         => $model->user_id,
                        'source'          => $model->source,
                        'req_date'        => $model->req_date,
                        'joint_id'        => $model->joint_id,
                        'real_id'         => $model->real_id,
                        'searching_id'    => $searching_id,
                        'fun_id'          => $model->fun_id,
                    ]
                );

                $model->deleteAfterPending($model->id);
            } /// if request not aproved
        } catch (\Exception $exception) {
            // dd($customer,$customer->exists());
            try {
                $joint->exists && $joint->delete();
                $funding->exists && $funding->delete();
                $real->exists && $real->delete();
                $model->exists && $model->delete();
                $customer->exists && $customer->delete();
            } catch (\Exception $e) {
                // dd($e);
            }
            // dd($exception);
            abort(422, $exception->getMessage());
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public static function toArray($model, Request $request): array
    {
        return $model->toArray();
    }
}
