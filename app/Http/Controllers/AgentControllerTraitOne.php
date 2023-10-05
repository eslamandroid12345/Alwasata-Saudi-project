<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers;

use App\AskAnswer;
use App\Helpers\MyHelpers;
use App\Http\Requests\Agent\AgentRequest;
use App\Models\Classification;
use App\Models\ClassificationAlertSchedule;
use App\Models\RequestRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

trait AgentControllerTraitOne
{
    public function updatefunding(AgentRequest $request)
    {
        // dd($request->all());
        // $request = $request->merge(['req_class' => $request->reqclass]);
        Session::forget('missedFileds');
        $requestModel = \App\request::find($request->reqID);
        $deleteClassificationAlertSchedule33 = $requestModel->class_id_agent == 33 && $request->req_class != $requestModel->class_id_agent;
        // dd($request->req_class);
        if ($request->req_class == 61 && $requestModel->class_id_agent) {

            $classification = Classification::findOrFail(61);

            $record = RequestRecord::query()->where([
                'colum'   => 'class_agent',
                'user_id' => $requestModel->user_id,
                'req_id'  => $requestModel->id,
                //'value'   => $classification->value,
                'value'   => $classification->id,
            ])->exists();
            if ($record) {
                return redirect()->back()->with('message2', __("messages.updateRequestClassification61"));
            }
        }
        if ($request->req_class == 62 && $requestModel->class_id_agent) {
            $classification = Classification::findOrFail(62);
            $record = RequestRecord::query()->where([
                'colum'   => 'class_agent',
                'user_id' => $requestModel->user_id,
                'req_id'  => $requestModel->id,
                'value'   => $classification->id,
            ])->exists();
            if ($record) {
                return redirect()->back()->with('message2', __("messages.updateRequestClassification61"));
            }
        }

        if ($request->reqclass != $requestModel->class_id_agent) {

            if ($request->reqclass != 13) {
                try {
                    AskAnswer::where(['request_id' => $requestModel->id, 'user_id' => $requestModel->user_id])->delete();
                }
                catch (\Exception $exception) {
                }
            }
        }

        $reqID = $request->reqID;
        $fundingReq = DB::table('requests')->where('id', $reqID)->where('user_id', auth()->id())->where(function ($query) {
            $query->where('statusReq', 0)->orWhere('statusReq', 1)->orWhere('statusReq', 2)->orWhere('statusReq', 4)->orWhere('statusReq', 19)->orWhere('statusReq', 31);
        })->first();

        if (!empty($fundingReq)) {
            $jointId = $fundingReq->joint_id;
            $customerId = $fundingReq->customer_id;
            //$customerInfo = DB::table('customers')->where('id', '=', $customerId)->first();
            $fundingId = $fundingReq->fun_id;
            $realId = $fundingReq->real_id;
            //$classId = $fundingReq->class_id_agent;
            if ($request->name == null) {
                $request->name = 'بدون اسم';
            }
            $this->records($reqID, 'customerName', $request->name);
            $this->records($reqID, 'agent_identity_number', $request->agent_identity_number);
            //$this->records($reqID, 'mobile', $request->mobile);
            $this->records($reqID, 'sex', $request->sex);
            $this->records($reqID, 'birth_date', $request->birth);
            $this->records($reqID, 'birth_hijri', $request->birth_hijri);
            $this->records($reqID, 'hiring_date', $request->hiring_date);
            $this->records($reqID, 'salary', $request->salary);
            $this->records($reqID, 'age_years', $request->age_years);
            $this->records($reqID, 'add_support_installment_to_salary', $request->add_support_installment_to_salary == 0 ? 'لا' : 'نعم');
            $this->records($reqID, 'without_transfer_salary', $request->without_transfer_salary == 0 ? 'لا' : 'نعم');
            $this->records($reqID, 'guarantees', $request->guarantees == 0 ? 'لا' : 'نعم');
            $this->records($reqID, 'basic_salary', $request->basic_salary);
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
            $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
            if (!empty($getworkValue)) {
                $this->records($reqID, 'work', $getworkValue->value);
            }

            if (($request->jointname != null) || ($request->jointmobile != null)) {
                $has_joint = 'yes';
            }
            else {
                $has_joint = 'no';
            }
            DB::table('customers')->where([
                ['id', '=', $customerId],
            ])->update([
                'name'                              => $request->name,
                //'mobile' => $request->mobile,
                'sex'                               => $request->sex,
                'birth_date'                        => $request->birth,
                'birth_date_higri'                  => $request->birth_hijri,
                'age'                               => $request->age,
                'work'                              => $request->work,
                'madany_id'                         => $request->madany_work,
                'job_title'                         => $request->job_title,
                'askary_id'                         => $request->askary_work,
                'military_rank'                     => $request->rank,
                'salary_id'                         => $request->salary_source,
                'salary'                            => $request->salary,
                'is_supported'                      => $request->is_support,
                'has_obligations'                   => $request->has_obligations,
                'obligations_value'                 => $request->obligations_value,
                'has_financial_distress'            => $request->has_financial_distress,
                'financial_distress_value'          => $request->financial_distress_value,
                'has_joint'                         => $has_joint,
                'age_years'                         => $request->age_years,
                'without_transfer_salary'           => $request->without_transfer_salary,
                'add_support_installment_to_salary' => $request->add_support_installment_to_salary,
                'basic_salary'                      => $request->basic_salary,
                'guarantees'                        => $request->guarantees,
                'hiring_date'                       => $request->hiring_date,
            ]);
            $name = $request->jointname;
            $mobile = $request->jointmobile;
            $birth = $request->jointbirth;
            $birth_higri = $request->jointbirth_hijri;
            $age = $request->jointage;
            $joint_hiring_date = $request->joint_hiring_date;
            $work = $request->jointwork;
            $salary = $request->jointsalary;
            $salary_source = $request->jointsalary_source;
            $rank = $request->jointrank;
            $madany = $request->jointmadany_work;
            $job_title = $request->jointjob_title;
            $askary_work = $request->jointaskary_work;
            $jointfunding_source = $request->jointfunding_source;
            $jointis_support = $request->joint_is_support;
            $joint_add_support = $request->joint_add_support_installment_to_salary;
            $joint_obligation = $request->joint_has_obligations;
            $joint_obligationvalue = $request->jointobligations_value;
            $this->records($reqID, 'jointName', $request->jointname);
            $this->records($reqID, 'jointMobile', $request->jointmobile);
            $this->records($reqID, 'jointSalary', $request->jointsalary);
            $this->records($reqID, 'jointBirth', $request->jointbirth);
            $this->records($reqID, 'jointBirth_higri', $request->jointbirth_hijri);
            $this->records($reqID, 'joint_hiring_date', $joint_hiring_date);
            $this->records($reqID, 'jointJobTitle', $job_title);
            $this->records($reqID, 'jointobligations_value', $joint_obligationvalue);
            $this->records($reqID, 'joint_add_support_installment_to_salary', $request->joint_add_support_installment_to_salary == 0 ? 'لا' : 'نعم');
            $this->records($reqID, 'jointSupport', $jointis_support == 'yes' ? 'نعم' : 'لا');
            $this->records($reqID, 'jointObligations', $joint_obligation == 'yes' ? 'نعم' : 'لا');
            $getjointfundingValue = DB::table('funding_sources')->where('id', $request->jointfunding_source)->first();
            if (!empty($getjointfundingValue)) {
                $this->records($reqID, 'jointfunding_source', $getjointfundingValue->value);
            }
            $getjointsalaryValue = DB::table('salary_sources')->where('id', $request->jointsalary_source)->first();
            if (!empty($getjointsalaryValue)) {
                $this->records($reqID, 'jointsalary_source', $getjointsalaryValue->value);
            }
            $getjointrankValue = DB::table('military_ranks')->where('id', $request->jointrank)->first();
            if (!empty($getjointrankValue)) {
                $this->records($reqID, 'jointRank', $getjointrankValue->value);
            }
            $getjointaskaryValue = DB::table('askary_works')->where('id', $request->jointaskary_work)->first();
            if (!empty($getjointaskaryValue)) {
                $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
            }
            $getjointmadanyValue = DB::table('madany_works')->where('id', $request->jointmadany_work)->first();
            if (!empty($getjointmadanyValue)) {
                $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
            }
            $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
            if (!empty($getworkValue)) {
                $this->records($reqID, 'jointWork', $getworkValue->value);
            }

            DB::table('joints')->where('id', $jointId)->update([
                'name'                              => $name,
                'mobile'                            => $mobile,
                'salary'                            => $salary,
                'birth_date'                        => $birth,
                'birth_date_higri'                  => $birth_higri,
                'age'                               => $age,
                'work'                              => $work,
                'salary_id'                         => $salary_source,
                'military_rank'                     => $rank,
                'madany_id'                         => $madany,
                'job_title'                         => $job_title,
                'funding_id'                        => $jointfunding_source,
                'askary_id'                         => $askary_work,
                'is_supported'                      => $jointis_support,
                'add_support_installment_to_salary' => $joint_add_support,
                'has_obligations'                   => $joint_obligation,
                'obligations_value'                 => $joint_obligationvalue,
                'hiring_date'                       => $joint_hiring_date,
            ]);
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
            $this->records($reqID, 'residence_type', $request->residence_type);

            // $request->validate([
            //     'financing_or_tsaheel' => 'required_if:evaluated,نعم',
            //     'evaluation_amount' => 'required_if:evaluated,نعم'
            // ]);
            if($request->realeva == 'نعم'){
                $financing_or_tsaheel=$request->financing_or_tsaheel;
                $evaluation_amount=$request->evaluation_amount;
            }else{
                $financing_or_tsaheel=null;
                $evaluation_amount=null;
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
                'residence_type'  => $request->residence_type,

                'financing_or_tsaheel' =>$financing_or_tsaheel,
                'evaluation_amount' => $evaluation_amount,
            ]);
            //              // this condition check if request has tsaheel info, and create one if not exists
            if ((($request->reqtyp == "رهن") || ($request->reqtyp == "تساهيل")) && ($fundingReq->payment_id === null)) {
                $paypreID = DB::table('prepayments')->insertGetId([
                    'pay_date' => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                    'req_id'   => $reqID,
                ]);
                DB::table('requests')->where('id', $reqID)->update([
                    'payment_id' => $paypreID,
                ]);
            }
            /********update fundingreq after update request */
            $fundingReq = DB::table('requests')->where('id', $reqID)->where('user_id', auth()->id())->where(function ($query) {
                $query->where('statusReq', 0) //new request
                ->orWhere('statusReq', 1) //open request
                ->orWhere('statusReq', 2) //open request
                ->orWhere('statusReq', 4) //rejected from sales maanager
                ->orWhere('statusReq', 19) //mor-pur in sales agent
                ->orWhere('statusReq', 31); //rejected from mortgage maanger
            })->first();
            /******** end update fundingreq after update request */
            if ((($request->reqtyp == "رهن") || ($request->reqtyp == "تساهيل")) && ($fundingReq->payment_id != null)) {
                $payId = $fundingReq->payment_id;
                $real = $request->real;
                $incr = $request->incr;
                $preval = $request->preval;
                $prepre = $request->prepre;
                $precos = $request->precos;
                $net = $request->net;
                $deficit = $request->deficit;
                $visa = $request->visa;
                if ($visa == null) {
                    $visa = 0;
                }
                $carlo = $request->carlo;
                if ($carlo == null) {
                    $carlo = 0;
                }
                $perlo = $request->perlo;
                if ($perlo == null) {
                    $perlo = 0;
                }
                $realo = $request->realo;
                if ($realo == null) {
                    $realo = 0;
                }
                $credban = $request->credban;
                if ($credban == null) {
                    $credban = 0;
                }
                $other1 = $request->other1;
                if ($other1 == null) {
                    $other1 = 0;
                }
                $debt = $request->debt;
                $morpre = $request->morpre;
                $morcos = $request->morcos;
                $propre = $request->propre;
                $procos = $request->procos;
                $valadd = $request->valadd;
                $admfe = $request->admfe;
                $realDisposition = $request->Real_estate_disposition_value_tsaheel;
                $purchaseTaxTsaheel = $request->purchase_tax_value_tsaheel;
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
                if ($request->other1 != 0) {
                    $this->records($reqID, 'otherLo', $request->other1);
                }
                $this->records($reqID, 'morPresnt', $request->morpre);
                $this->records($reqID, 'mortCost', $request->mortCost);
                $this->records($reqID, 'pursitPresnt', $request->propre);
                $this->records($reqID, 'profCost', $request->procos);
                $this->records($reqID, 'addedValue', $request->valadd);
                $this->records($reqID, 'adminFees', $request->admfe);
                $up = DB::table('prepayments')->where('id', $payId)->update([
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
                    'other'                         => $other1,
                    'debt'                          => $debt,
                    'mortPre'                       => $morpre,
                    'mortCost'                      => $morcos,
                    'proftPre'                      => $propre,
                    'deficitCustomer'               => $deficit,
                    'profCost'                      => $procos,
                    'addedVal'                      => $valadd,
                    'adminFee'                      => $admfe,
                    'req_id'                        => $reqID,
                    'pay_date'                      => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                    'Real_estate_disposition_value' => $realDisposition,
                    'purchase_tax_value'            => $purchaseTaxTsaheel,
                ]);
            }
            if ($request->reqtyp == "رهن-شراء") {
                $payId = $fundingReq->payment_id;
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
                $this->records($reqID, 'realCost', $request->real);
                $this->records($reqID, 'incValue', $request->incr);
                $this->records($reqID, 'preValue', $request->preval);
                $this->records($reqID, 'prePresent', $request->prepre);
                $this->records($reqID, 'preCost', $request->precos);
                $this->records($reqID, 'netCust', $request->net);
                $this->records($reqID, 'deficitCust', $request->deficit);
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
                if ($request->other1 != 0) {
                    $this->records($reqID, 'otherLo', $request->other1);
                }
                $this->records($reqID, 'morPresnt', $request->morpre);
                $this->records($reqID, 'mortCost', $request->mortCost);
                $this->records($reqID, 'pursitPresnt', $request->propre);
                $this->records($reqID, 'profCost', $request->procos);
                $this->records($reqID, 'addedValue', $request->valadd);
                $this->records($reqID, 'adminFees', $request->admfe);
                DB::table('prepayments')->where('id', $payId)->update([
                    'realCost'        => $real,
                    'incValue'        => $incr,
                    'prepaymentVal'   => $preval,
                    'prepaymentPre'   => $prepre,
                    'prepaymentCos'   => $precos,
                    'visa'            => $visa,
                    'carLo'           => $carlo,
                    'personalLo'      => $perlo,
                    'realLo'          => $realo,
                    'credit'          => $credban,
                    'netCustomer'     => $net,
                    'other'           => $other,
                    'debt'            => $debt,
                    'mortPre'         => $morpre,
                    'mortCost'        => $morcos,
                    'proftPre'        => $propre,
                    'deficitCustomer' => $deficit,
                    'profCost'        => $procos,
                    'addedVal'        => $valadd,
                    'adminFee'        => $admfe,
                ]);
            }
            ////********************REMINDERS BODY************************* */

            //only one reminder to each request
            $checkFollow = DB::table('notifications')->where('req_id', '=', $reqID)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)->where('status', '=', 2)->first(); // check dublicate
            if ($request->follow != null) {
                $date = $request->follow;
                $time = $request->follow1;
                if ($time == null) {
                    $time = "00:00";
                }
                $newValue = $date."T".$time;
                if (empty($checkFollow)) { //first reminder
                    // add following notification
                    DB::table('notifications')->insert([
                        'value'         => MyHelpers::admin_trans(auth()->id(), 'The request need following'),
                        'recived_id'    => (auth()->id()),
                        'status'        => 2,
                        'type'          => 1,
                        'reminder_date' => $newValue,
                        'req_id'        => $reqID,
                        'created_at'    => (Carbon::now('Asia/Riyadh')),
                    ]);
                }
                else {
                    DB::table('notifications')->where('id', $checkFollow->id)->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy
                }
            }
            else {
                #if empty reminder, so the reminder ll remove if it's existed.
                if (!empty($checkFollow)) {
                    DB::table('notifications')->where('id', $checkFollow->id)->delete();
                }
            }

            ////********************REMINDERS BODY************************* */
            $funding_source = $request->funding_source;
            $fundingdur = $request->fundingdur;
            $fundingpersonal = $request->fundingpersonal;
            $fundingpersonalp = $request->fundingpersonalp;
            $fundingreal = $request->fundingreal;
            $fundingrealp = $request->fundingrealp;
            $dedp = $request->dedp;
            $monthIn = $request->monthIn;
            $flexFund = $request->flexiableFun_cost;
            $extenFund = $request->extendFund_cost;
            $personalDed = $request->personal_salary_deduction;
            $personalMonthIn = $request->personal_monthly_installment;
            $product_code = $request->product_code;
            $monthInAfterSupport = $request->monthly_installment_after_support;
            $this->records($reqID, 'fundDur', $fundingdur);
            $this->records($reqID, 'fundPers', $fundingpersonal);
            $this->records($reqID, 'fundPersPre', $fundingpersonalp);
            $this->records($reqID, 'fundReal', $fundingreal);
            $this->records($reqID, 'fundRealPre', $fundingrealp);
            $this->records($reqID, 'fundDed', $dedp);
            $this->records($reqID, 'fundMonth', $monthIn);
            $this->records($reqID, 'fundFlex', $flexFund);
            $this->records($reqID, 'fundExten', $extenFund);
            $this->records($reqID, 'personal_salary_deduction', $personalDed);
            $this->records($reqID, 'installment_after_support', $monthInAfterSupport);
            $getfundingValue = DB::table('funding_sources')->where('id', $request->funding_source)->first();
            if (!empty($getfundingValue)) {
                $this->records($reqID, 'funding_source', $getfundingValue->value);
            }
            if ($product_code != null) {
                $matchCode = MyHelpers::getSpasficProductType($product_code);
                if ($matchCode != null) {
                    $this->records($reqID, 'product_type', $matchCode['name_ar']);
                }
            }
            DB::table('fundings')->where('id', $fundingId)->update([
                'funding_source'                    => $funding_source,
                'funding_duration'                  => $fundingdur,
                'personalFun_cost'                  => $fundingpersonal,
                'personalFun_pre'                   => $fundingpersonalp,
                'realFun_cost'                      => $fundingreal,
                'realFun_pre'                       => $fundingrealp,
                'ded_pre'                           => $dedp,
                'monthly_in'                        => $monthIn,
                'flexiableFun_cost'                 => $flexFund,
                'personal_salary_deduction'         => $personalDed,
                'personal_monthly_installment'      => $personalMonthIn,
                'monthly_installment_after_support' => $monthInAfterSupport,
                'extendFund_cost'                   => $extenFund,
                'product_code'                      => $product_code,
            ]);
            $reqtype = $request->reqtyp;
            $reqsour = $request->reqsour;
            $reqclass = $request->reqclass;
            $reqcomm = $request->reqcomm;
            $webcomm = $request->webcomm;
            $update = Carbon::now('Asia/Riyadh');
            /**CHECK IF THERE IS CALCULATER HISTORY */
            if (MyHelpers::checkIfRequiredInCalculater($reqclass)) {
                if (!MyHelpers::checkIfThereCalculaterRecord($reqID)) {
                    return redirect()->back()->with('message6', MyHelpers::admin_trans(auth()->id(), 'Calculater record is required'));
                }
            }
            /*END *CHECK IF THERE IS CALCULATER HISTORY */
            $this->records($reqID, 'reqtyp', $reqtype);
            $this->records($reqID, 'comment', $reqcomm ?? $fundingReq->comment);
            $this->records($reqID, 'commentWeb', $webcomm);

            $getsourceValue = DB::table('request_source')->where('id', $reqsour)->first();
            if (!empty($getclassValue)) {
                $this->records($reqID, 'reqSource', $getsourceValue->value);
            }

            $getclassValue = DB::table('classifcations')->where('id', $request->reqclass)->first();
            if (!empty($getclassValue)) {
                $this->records($reqID, 'class_agent', $getclassValue->id);
            }
            $getRejectionValue = DB::table('rejections_reasons')->where('id', $request->rejection_id_agent)->first();
            if (!empty($getRejectionValue)) {
                $this->records($reqID, 'rejection_id_agent', $getRejectionValue->title);
            }

            if ($request->reqclass == 15 && $request->realcity == null) {
                return redirect()->back()->with('message5', MyHelpers::admin_trans(auth()->id(), 'The city field is required once you select seeking for property'));
            }

            if ($fundingReq->is_approved_by_tsaheel_acc == 1) {
                DB::table('request_histories')->insert([
                    'title'        => MyHelpers::admin_trans(auth()->id(), 'Cancele approve of Tsaheel Accountant'),
                    'user_id'      => (auth()->id()),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id'       => $reqID,
                ]);
            }
            if ($fundingReq->is_approved_by_wsata_acc == 1) {
                DB::table('request_histories')->insert([
                    'title'        => MyHelpers::admin_trans(auth()->id(), 'Cancele approve of Wsata Accountant'),
                    'user_id'      => (auth()->id()),
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id'       => $reqID,
                ]);
            }
            // check if class_id_agent changed and request has quality_req change its status to completed
            if($reqclass != $requestModel->class_id_agent)
            {
                MyHelpers::UpdatingRequest($requestModel);
            }
            $data = [
                'agent_identity_number'      => $request->agent_identity_number,
                'rejection_id_agent'         => $request->rejection_id_agent,
                'class_id_agent'             => $reqclass,
                'type'                       => $reqtype,
                'noteWebsite'                => $webcomm,
                'comment'                    => $reqcomm ?? $fundingReq->comment,
                'updated_at'                 => $update,
                'is_approved_by_tsaheel_acc' => 0,
                'is_approved_by_wsata_acc'   => 0,
                'funding_source' => $request->funding_source,
            ];
            if (!$fundingReq->collaborator_id) {
                $data += [
                    'collaborator_id' => $request->collaborator,
                ];
            }

            /*$requestClass = $fundingReq->class_id_agent;
            if ($requestClass == Classification::AGENT_UNABLE_TO_COMMUNICATE && $reqclass != $requestClass) {
                // Create Questionnaire
                ClassificationQuestionnaire::updateOrCreate([
                    'classification_id' => Classification::AGENT_UNABLE_TO_COMMUNICATE,
                    'request_id'        => $reqID,
                ], [
                    'user_id' => auth()->id(),
                    'title'   => ClassificationQuestionnaire::CHANGE_TITLE,
                    'body'    => null,
                    'value'   => !0,
                ]);
            }
            */
            unset($data['funding_source']);
            // if bank note updated
            if(trim($requestModel->bank_notes) != trim($request->bank_notes)){
                $data += [
                    'bank_notes' => $request->bank_notes,
                ];
                $this->records($reqID, 'bank_notes', $request->bank_notes);
                foreach($requestModel->getBankAccountIds() as $u_id)
                {
                    $msg = auth()->user()->name .': ' . \App\Helpers\MyHelpers::admin_trans(auth()->id(), 'New Note');
                    \App\Helpers\MyHelpers::SendNotificationToUser($msg, $u_id, $requestModel->id);
                }
            }
            DB::table('requests')->where('id', $reqID)->update($data);

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = auth()->id();
            if(Classification::find($reqclass)) {
                if(Classification::find($reqclass)->type ==0) {
                    MyHelpers::incrementDailyPerformanceColumn($agent_id, 'archived_basket',$reqID);
                }
            }
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'updated_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            $collName = DB::table('users')->where('id', $request->collaborator)->first();
            if (!empty($collName)) {
                $this->records($reqID, 'collaborator_name', $collName->name);
            }
            $reqInfoAfterUpdate = DB::table('requests')->where('id', $reqID)->first();
            if ($reqclass == 15 && $fundingReq->class_id_agent != 15) { // seeking for proerty
                DB::table('requests')->where('id', $reqID)->update([
                    'customer_found_property' => 0, // have to reset this to false
                    'updated_at'              => now('Asia/Riyadh'),
                ]);
                MyHelpers::sendEmailNotifiactionCustomer($fundingReq->customer_id, ' عزيزي العميل ، تم تحديث طلبك إلى يبحث عن عقار  ', ' تم تحديث طلبك - شركة الوساطة العقارية');
            }
            if ($reqclass == 13 && $fundingReq->class_id_agent != 13) { // customer not want to complete order
                DB::table('requests')->where('id', $reqID)->update([
                    'customer_reason_for_rejected' => null,
                    'customer_want_to_reject_req'  => null,
                    'updated_at'                   => now('Asia/Riyadh'),
                ]);
                MyHelpers::sendEmailNotifiactionCustomer($fundingReq->customer_id, ' عزيزي العميل ، تم تحديث طلبك إلى لايرغب في إكمال الطلب  ', ' تم تحديث طلبك - شركة الوساطة العقارية');
            }
            if ($reqclass == 16 && $fundingReq->class_id_agent != 16) // customer has problem in the request
            {
                DB::table('requests')->where('id', $reqID)->update([
                    'customer_resolve_problem' => null,
                    'updated_at'               => now('Asia/Riyadh'),
                ]);
            }
            #AUTO MOVE TO ARCHIVE BASKET IF THE SELECTED CLASS WITH NEGATIVE TYPE
            if (MyHelpers::checkClassType($reqInfoAfterUpdate->class_id_agent)) {
                DB::table('requests')->where('id', $reqID)->where(function ($query) {
                    $query->where('statusReq', 0) //new request
                    ->orWhere('statusReq', 1) //open request
                    ->orWhere('statusReq', 4) //rejected from sales maanger
                    ->orWhere('statusReq', 31); //rejected from mortgage maanger
                })->update(['statusReq' => 2, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'add_to_archive' => Carbon::now('Asia/Riyadh'), 'add_to_stared' => null, 'add_to_followed' => null, 'updated_at' => now('Asia/Riyadh')]);
            }
            else {
                #MOVE TO REIVED BASKET IF REQUEST WAS IN ARCHIVE AND NOW CLASS IS NOT NEGATIVE
                DB::table('requests')->where('id', $reqID)->where('statusReq', 2) //Archive
                ->update(['statusReq' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'remove_from_archive' => Carbon::now('Asia/Riyadh'), 'updated_at' => now('Asia/Riyadh')]);
            }
            //for quality intent:::::::::::::::: disabled by abdelilah
            if (MyHelpers::checkQualityReq($reqID) && false) {
                MyHelpers::checkBeforeQualityReq($reqID, $fundingReq->statusReq, $fundingReq->user_id, $reqclass);
            }
            //end quality :::::::::::::::::::::::::::::::::::::::

            if ($deleteClassificationAlertSchedule33) {
                ClassificationAlertSchedule::query()->where(['request_id' => $requestModel->id])->delete();
            }
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));

        }

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
    }
}
