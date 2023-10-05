<?php

namespace App\Http\Controllers;

use App\Calculater;
use App\Scenario;
use App\ScenariosUsers;
use App\Traits\General;
use App\WorkSource;
use Carbon\Carbon;
use Datetime;
use Exception;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class CalculaterController extends Controller
{
    use General;

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

    public function getLastcalCulaterData(Request $request)
    {

        $lastData = Calculater::where('request_id', $request->requestID)->orderBy('id', 'DESC')->first();

        //$getdate=$this->convertToGregorianWithoutRequest($lastData->birth_hijri);
        //$getdate=$this->calculateAge($getdate);

        return response($lastData);
    }

    public function getAgentCalculaterResultSettings(Request $request)
    {
        $agent_array = ScenariosUsers::where('scenario_id', $request->senario_id)->pluck('user_id')->toArray();
        return response($agent_array);
    }

    public function getCalculaterResultSettings()
    {
        $scenarios = Scenario::orderBy('sort_id', 'ASC')->get();
        return response($scenarios);
    }

    public function flexibleSetting()
    {
        $getSettings = DB::table('program_settings')->where('program_id', '=', 1)->select('id', 'value_en', 'option_value')->get();
        return response($getSettings);
    }

    public function convertToGregorianWithoutRequest($hijri)
    {
        //        return($request->hijri);
        $date = $hijri;
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $output = Hijri::convertToGregorian((int) $day, (int) $month, (int) $year);

        $year2 = substr($output, 0, 4);
        $month2 = substr($output, 5, 2);
        $day2 = substr($output, 8, 2);

        $fulldate = $year2.'-'.$month2.'-'.$day2;
        return $fulldate;
    }

    public function calculateAge($input)
    {
        $dateOfBirth = $input;
        $years = Carbon::parse($dateOfBirth)->age;

        return $years;
    }

    public function removeCommaFromNumber($number)
    {
        if ($number != null) {
            return str_replace(',', '', $number);
        }
        else {
            return null;
        }
    }

    public function calculaterApi(Request $request)
    {
        //        dd($request->job_tenure_caculater);

        $rules = [];
        $rules = [
            'birth_hijri_caculater'                     => ['required', 'regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'],
            'salary_caculater'                          => ['required', 'numeric'],
            'work_caculater'                            => 'required',
            'product_type_id_caculater'                 => 'required',
            'salary_deduction_caculater'                => 'numeric|nullable',
            'joint_salary_deduction_caculater'          => 'numeric|nullable',
            'personal_salary_deduction_caculater'       => 'numeric|nullable',
            'joint_personal_salary_deduction_caculater' => 'numeric|nullable',

            'basic_salary_caculater'       => 'numeric|nullable',
            'property_amount_caculater'    => 'numeric|nullable',
            'housing_allowance_caculater'  => 'numeric|nullable',
            'transfer_allowance_caculater' => 'numeric|nullable',
            'other_allowance_caculater'    => 'numeric|nullable',
            'retirement_income_caculater'  => 'numeric|nullable',
            'early_repayment_caculater'    => 'numeric|nullable',

            'joint_salary_caculater'             => 'numeric|nullable',
            'joint_early_repayment_caculater'    => 'numeric|nullable',
            'joint_housing_allowance_caculater'  => 'numeric|nullable',
            'joint_transfer_allowance_caculater' => 'numeric|nullable',
            'joint_other_allowance_caculater'    => 'numeric|nullable',
            'joint_retirement_income_caculater'  => 'numeric|nullable',
        ];

        // $rules['job_tenure_caculater'] = 'required';
        // $rules['basic_salary_caculater'] = 'required';

        if ($request->has('work_caculater')) {
            if ($request->work_caculater == 1) {
                $rules['military_rank_caculater'] = 'required';
            }
        }

        if ($request->has('joint_work_caculater')) {
            if ($request->joint_work_caculater == 1) {
                $rules['joint_military_rank_caculater'] = 'required';
            }
        }

        if ($request->has('guarantees_caculater')) {
            $rules['basic_salary_caculater'] = 'required';
        }

        if ($request->has('has_obligations_caculater')) {
            if (($request->obligations_installment_caculater == null) && ($request->remaining_obligations_months_caculater == null)) {
                $rules['early_repayment_caculater'] = 'required';
            }
            else {
                if ($request->obligations_installment_caculater != null) {
                    $rules['remaining_obligations_months_caculater'] = 'required';
                }

                if ($request->remaining_obligations_months_caculater != null) {
                    $rules['obligations_installment_caculater'] = 'required';
                }
            }
        }

        if ($request->has('provide_first_batch_caculater')) {
            $rules['first_batch_profit_caculater'] = 'required';
        }

        if ($request->has('have_joint_caculater')) {
            $rules['joint_birth_hijri_caculater'] = ['required', 'regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'];
            $rules['joint_salary_caculater'] = 'required';
            $rules['joint_work_caculater'] = 'required';
        }

        if ($request->has('joint_has_obligations_caculater')) {
            if (($request->joint_obligations_installment_caculater == null) && ($request->joint_remaining_obligations_months_caculater == null)) {
                $rules['joint_early_repayment_caculater'] = 'required';
            }
            else {
                if ($request->joint_obligations_installment_caculater != null) {
                    $rules['joint_remaining_obligations_months_caculater'] = 'required';
                }

                if ($request->joint_remaining_obligations_months_caculater != null) {
                    $rules['joint_obligations_installment_caculater'] = 'required';
                }
            }
        }

        $customMessages = [
            'required'                          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'numeric'                           => 'يجب أن يكون رقمًا صحيحا',
            'birth_hijri_caculater.regex'       => "أدخل صيغة صحيحة لتاريخ الميلاد",
            'joint_birth_hijri_caculater.regex' => "أدخل صيغة صحيحة لتاريخ الميلاد",
        ];

        $this->validate($request, $rules, $customMessages);

        //RESTRUCTRE OF REQUEST-------------------

        //1- AGE OF CUSTOMER & Joint::
        $customerAge = $this->calculateHijriAge($request->birth_hijri_caculater);
        //        $customerAge = $this->calculateHijriAgeWithMonth($request->birth_hijri_caculater);
        $request->merge(["customerAge" => $customerAge]);

        //        $ageByMonth = $customerAge * 12;
        $ageByMonth = $this->calculateHijriAgeWithMonth($request->birth_hijri_caculater);
        $request->merge(["customerAgeByMonth" => $ageByMonth]);

        if ($request->has('have_joint_caculater')) {
            $jointAge = $this->calculateHijriAge($request->joint_birth_hijri_caculater);
            $request->merge(["jointAge" => $jointAge]);

            $jointAgeByMonth = $jointAge * 12;
            $request->merge(["jointAgeByMonth" => $jointAgeByMonth]);
        }
        else {
            $request->merge(["jointAge" => null]);
            $request->merge(["jointAgeByMonth" => null]);
        }

        //2- Banks::
        if ($request->has('salary_bank_id_caculater')) {
            if ($request->salary_bank_id_caculater != '' && $request->salary_bank_id_caculater != null) {

                $bankInfo = MyHelpers::getSalaryBankInfo($request->salary_bank_id_caculater);
                if ($bankInfo != false) {
                    $matchBank = $this->getMatchBanks($bankInfo->value);
                }
                if ($matchBank != null) {
                    $request->merge(["salary_bank_code" => $matchBank]);
                }
            }
        }

        if (!$request->has('salary_bank_code')) {
            $request->merge(["salary_bank_code" => null]);
        }

        if ($request->has('joint_salary_bank_id_caculater')) {
            if ($request->joint_salary_bank_id_caculater != '' && $request->joint_salary_bank_id_caculater != null) {

                $bankInfo = MyHelpers::getSalaryBankInfo($request->joint_salary_bank_id_caculater);
                if ($bankInfo != false) {
                    $matchBank = $this->getMatchBanks($bankInfo->value);
                }
                if ($matchBank != null) {
                    $request->merge(["joint_salary_bank_code" => $matchBank]);
                }
            }
        }

        if (!$request->has('joint_salary_bank_code')) {
            $request->merge(["joint_salary_bank_code" => null]);
        }

        //3- WORK
        if ($request->has('military_rank_caculater')) {
            if ($request->military_rank_caculater != '' && $request->military_rank_caculater != null) {

                $rankInfo = MyHelpers::getMiliratyRankInfo($request->military_rank_caculater);
                if ($rankInfo != false) {
                    $matchRank = $this->getMatchRank($rankInfo->value);
                }
                if ($matchRank != null) {
                    $request->merge(["military_rank_code" => $matchRank]);
                }
            }
        }

        if (!$request->has('military_rank_code')) {
            $work = null;
            $getworkValue = DB::table('work_sources')->where('id', $request->work_caculater)->first();
            if (!empty($getworkValue)) {
                $work = $this->getMatchWork($getworkValue->value);
            }
            $request->merge(["military_rank_code" => $work]);
        }

        if ($request->has('joint_military_rank_caculater')) {
            if ($request->joint_military_rank_caculater != '' && $request->joint_military_rank_caculater != null) {

                $rankInfo = MyHelpers::getMiliratyRankInfo($request->joint_military_rank_caculater);
                if ($rankInfo != false) {
                    $matchRank = $this->getMatchRank($rankInfo->value);
                }
                if ($matchRank != null) {
                    $request->merge(["joint_military_rank_code" => $matchRank]);
                }
            }
        }

        if (!$request->has('joint_military_rank_code')) {
            $joint_work = null;
            $getworkValue = DB::table('work_sources')->where('id', $request->joint_work_caculater)->first();
            if (!empty($getworkValue)) {
                $joint_work = $this->getMatchWork($getworkValue->value);
            }
            $request->merge(["joint_military_rank_code" => $joint_work]);
        }

        //4- reset value of boolean
        if (!$request->has('residential_support_caculater')) {
            $request->merge(["residential_support_caculater" => 0]);
        }

        if (!$request->has('add_support_installment_to_salary_caculater')) {
            $request->merge(["add_support_installment_to_salary_caculater" => 0]);
        }

        if (!$request->has('guarantees_caculater')) {
            $request->merge(["guarantees_caculater" => 0]);
        }

        if (!$request->has('guarantees_caculater')) {
            $request->merge(["guarantees_caculater" => 0]);
        }

        if (!$request->has('without_transfer_salary_caculater')) {
            $request->merge(["without_transfer_salary_caculater" => 0]);
        }

        if (!$request->has('has_obligations_caculater')) {
            $request->merge(["has_obligations_caculater" => 0]);
        }

        if (!$request->has('provide_first_batch_caculater')) {
            $request->merge(["provide_first_batch_caculater" => 0]);
        }

        if (!$request->has('first_batch_mode_caculater')) {
            $request->merge(["first_batch_mode_caculater" => 0]);
        }

        if (!$request->has('have_joint_caculater')) {
            $request->merge(["have_joint_caculater" => 0]);
        }

        if (!$request->has('joint_residential_support_caculater')) {
            $request->merge(["joint_residential_support_caculater" => 0]);
        }

        if (!$request->has('joint_add_support_installment_to_salary_caculater')) {
            $request->merge(["joint_add_support_installment_to_salary_caculater" => 0]);
        }

        if (!$request->has('joint_has_obligations_caculater')) {
            $request->merge(["joint_has_obligations_caculater" => 0]);
        }

        #update funding month
        if ($request->personal_funding_months_caculater != null) {
            $request->personal_funding_months_caculater = $request->personal_funding_months_caculater * 12;
        }

        if ($request->joint_personal_funding_months_caculater != null) {
            $request->joint_personal_funding_months_caculater = $request->joint_personal_funding_months_caculater * 12;
        }

        if ($request->funding_months_caculater != null) {
            $request->funding_months_caculater = $request->funding_months_caculater * 12;
        }

        if ($request->joint_funding_months_caculater != null) {
            $request->joint_funding_months_caculater = $request->joint_funding_months_caculater * 12;
        }

        //SERVICE PERIOD
        if ($request->has('job_tenure_caculater')) {
            $job_tenure_caculater = $this->calculateHijriAgeWithMonth($request->job_tenure_caculater);
        } //years * 12 = to get months
        $request->merge(["job_tenure_caculater_result" => $job_tenure_caculater]);
        //            $request->job_tenure_caculater = $this->calculateHijriAge($request->job_tenure_caculater) * 12; //years * 12 = to get months

        if ($request->joint_job_tenure_caculater != null) {
            $request->joint_job_tenure_caculater = $this->calculateHijriAge($request->joint_job_tenure_caculater) * 12;
        } //years * 12 = to get months

        $calculaterResults = $this->connectWithCalculater($request);

        $array_net_loan_values = null;

        //dd($calculaterResults);
        if (auth()->user()->role == 13){
            foreach ($calculaterResults as $key=>$calculaterResult) {
                $bankInfo = $this->getMatchBanksId($calculaterResult['bank_name']);
                if ($bankInfo->id != auth()->user()->bank_id){
                    unset($calculaterResults[$key]);
                }

            }
        }
        if (count($calculaterResults) != 0) {
            //for banks images & customer age & joint age
            $i = 0;
            //for max funding
            $array_net_loan_values = [];

            foreach ($calculaterResults as $calculaterResult) {
                if ($this->checkIfNull('flexibleProgram', $calculaterResult['programs'])) {
                    $net_loan = $calculaterResult['programs']['flexibleProgram']['net_loan_total'];
                }
                elseif ($this->checkIfNull('propertyProgram', $calculaterResult['programs'])) {
                    $net_loan = $calculaterResult['programs']['propertyProgram']['net_loan_total'];
                }
                else {
                    $net_loan = 0;
                }

                $array_net_loan_values[$calculaterResult['bank_code']] = $net_loan;
            }
            foreach ($calculaterResults as $calculaterResult) {

                $bankInfo = $this->getMatchBanksId($calculaterResult['bank_name']);

                if ($bankInfo != null) {
                    $calculaterResults[$i]['bank_img'] = $bankInfo->img_location;
                }
                else {
                    $calculaterResults[$i]['bank_img'] = null;
                }

                $calculaterResults[$i]['customer_age'] = $request->customerAge;
                $calculaterResults[$i]['joint_age'] = $request->jointAge;

                $i++;
            }
            //



            $array_net_loan_values = array_keys($array_net_loan_values, max($array_net_loan_values));

            //If the salary_bank existed in request , i will add it to max funding , to check about it once senario of max_funding_with_customer_bank in result display
            if ($request->salary_bank_code != null) {
                $array_net_loan_values[] = $request->salary_bank_code;
            }
        }

        $getFlexibleSettings = general::getFlexibleSettings();
        $getPersonalSettings = general::getPersonalSettings();
        $getPropertySettings = general::getPropertySettings();
        $getExtendedSettings = general::getExtendedSettings();

        return response()->json([
            'calculaterResults' => $calculaterResults,
            'max_funding'       => $array_net_loan_values,
            'flexibleSettings'  => $getFlexibleSettings,
            'personalSettings'  => $getPersonalSettings,
            'propertySettings'  => $getPropertySettings,
            'extendedSettings'  => $getExtendedSettings,
        ]);
    }

    public function calculateHijriAge($input)
    {
        $now = Date::now();

        $birthdate = new DateTime($input);
        $today = new Datetime($now);
        $diff = $today->diff($birthdate);

        return $diff->y;
    }

    public function calculateHijriAgeWithMonth($input)
    {
        $now = Date::now();

        $birthdate = new DateTime($input);
        $today = new Datetime($now);
        $diff = $today->diff($birthdate);

        return (($diff->y * 12) + $diff->m);
    }

    public function getMatchBanks($bank_name)
    {

        $matchBank = null;

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($response->getBody(), true);
        $banks = $banks['data'];

        foreach ($banks as $bank) {
            similar_text($bank_name, $bank['name'], $percent);
            if ($percent >= 95) {

                $matchBank = $bank;
                break;
            }
        }
        if ($matchBank != null) {
            return $matchBank['code'];
        }

        return $matchBank;
    }

    public function getMatchRank($rank_name)
    {

        $matchRank = null;

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $ranks = json_decode($response->getBody(), true);
        $ranks = $ranks['data'];

        foreach ($ranks as $rank) {
            $rank['name'] = substr($rank['name'], 11);
            similar_text($rank_name, $rank['name'], $percent);
            if ($percent >= 95) {
                $matchRank = $rank;
                break;
            }
        }
        if ($matchRank != null) {
            return $matchRank['code'];
        }

        return $matchRank;
    }

    public function getMatchWork($work_name)
    {

        $matchWork = null;

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $works = json_decode($response->getBody(), true);
        $works = $works['data'];

        foreach ($works as $work) {
            similar_text($work_name, $work['name'], $percent);
            if ($percent >= 95) {
                $matchWork = $work;
                break;
            }
        }
        if ($matchWork != null) {
            return $matchWork['code'];
        }

        return $matchWork;
    }

    public function connectWithCalculater($request)
    {
        // d($request->all());
        $jobCalc = $this->calculateHijriAgeWithMonth($request->job_tenure_caculater);
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [

                'product_type_id' => $request->product_type_id_caculater,
                'bank_id'         => null,

                'age'                 => $request->customerAge,
                'age_by_months'       => $request->customerAgeByMonth,
                'salary'              => $request->salary_caculater,
                'basic_salary'        => $request->basic_salary_caculater,
                'job_position_id'     => $request->military_rank_code,
                'salary_bank_id'      => $request->salary_bank_code,
                'residential_support' => $request->residential_support_caculater,

                'add_support_installment_to_salary' => $request->add_support_installment_to_salary_caculater,
                'guarantees'                        => $request->guarantees_caculater,
                'without_transfer_salary'           => $request->without_transfer_salary_caculater,

                'housing_allowance'  => $request->housing_allowance_caculater,
                'transfer_allowance' => $request->transfer_allowance_caculater,
                'other_allowance'    => $request->other_allowance_caculater,
                'retirement_income'  => $request->retirement_income_caculater,

                'job_tenure_months' => $request->job_tenure_caculater_result,

                'personal_salary_deduction' => $request->personal_salary_deduction_caculater,
                'salary_deduction'          => $request->salary_deduction_caculater,
                'flexible_salary_deduction' => $request->salary_deduction_caculater,
                'personal_funding_months'   => $request->personal_funding_months_caculater,
                'funding_months'            => $request->funding_months_caculater,
                'personal_bank_profit'      => null,
                'bank_profit'               => null,

                'early_repayment'              => $request->early_repayment_caculater,
                'credit_installment'           => $request->credit_installment_caculater,
                'obligations_installment'      => $request->obligations_installment_caculater,
                'remaining_obligations_months' => $request->remaining_obligations_months_caculater,

                'property_amount'    => $request->property_amount_caculater,
                'property_completed' => $request->property_completed_caculater,
                'residence_type'     => $request->residence_type_caculater,

                'first_batch_mode'       => $request->first_batch_mode_caculater,
                'provide_first_batch'    => $request->provide_first_batch_caculater,
                'first_batch_percentage' => $request->first_batch_percentage_caculater,
                'first_batch_profit'     => $request->first_batch_profit_caculater,
                'fees'                   => $request->fees_caculater,
                'discount'               => $request->discount_caculater,

                'have_joint'                    => $request->have_joint_caculater,
                'extension_support_installment' => (bool) $request->extension_support_installment_caculater,

                'joint' => [
                    'age'                               => $request->jointAge,
                    'age_by_months'                     => $request->jointAgeByMonth,
                    'salary'                            => $request->joint_salary_caculater,
                    'basic_salary'                      => null,
                    'job_position_id'                   => $request->joint_military_rank_code,
                    'residential_support'               => $request->joint_residential_support_caculater,
                    'add_support_installment_to_salary' => $request->joint_add_support_installment_to_salary_caculater,
                    'salary_bank_id'                    => $request->joint_salary_bank_code,

                    'early_repayment'              => $request->joint_early_repayment_caculater,
                    'credit_installment'           => $request->joint_credit_installment_caculater,
                    'obligations_installment'      => $request->joint_obligations_installment_caculater,
                    'remaining_obligations_months' => $request->joint_remaining_obligations_months_caculater,

                    'housing_allowance'  => $request->joint_housing_allowance_caculater,
                    'transfer_allowance' => $request->joint_transfer_allowance_caculater,
                    'other_allowance'    => $request->joint_other_allowance_caculater,
                    'retirement_income'  => $request->joint_retirement_income_caculater,

                    'job_tenure_months' => $request->joint_job_tenure_caculater,

                    'personal_salary_deduction' => $request->joint_personal_salary_deduction_caculater,
                    'personal_funding_months'   => $request->joint_personal_funding_months_caculater,
                    'funding_months'            => $request->joint_funding_months_caculater,
                    'salary_deduction'          => $request->joint_salary_deduction_caculater,
                ],

            ],

        ]);

        $response = json_decode($response->getBody(), true);
        // d($response['responseData'][0]['programs']);
        return $response['responseData'];
    }

    public function getMatchBanksId($bank_name)
    {

        $matchBank = null;

        $banks = DB::table('banks')->get();

        foreach ($banks as $bank) {
            similar_text($bank_name, $bank->name, $percent);
            if ($percent >= 95) {

                $matchBank = $bank;
                break;
            }
        }
        return $matchBank;
    }

    public function checkIfNull($key, $array)
    {

        return is_array($array) && array_key_exists($key, $array);
    }

    public function selectCalculaterResult(Request $request)
    {
        $currentUser = auth()->user()->id;
        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        $bank_id = null;
        $bankInfo = $this->getMatchBanksByCode($request->resultOfClculater['bank_code']);
        //dd($bankInfo);
        if ($bankInfo != null) {
            $bankInfo = $this->getMatchBanksId($bankInfo['name_ar']);
            if ($bankInfo != null) {
                $bank_id = $bankInfo->id;
            }
        }
        $selectedProgram = null;

        if ($this->checkIfNull('extendedProgram', $request->resultOfClculater['programs'])) {
            if ($request->program_name == 'ممتد') {
                $selectedProgram = $request->resultOfClculater['programs']['extendedProgram'];
            }
        }else {
            $propertyFund_cost = null;
        }
        if ($this->checkIfNull('flexibleProgram', $request->resultOfClculater['programs'])) {
            if ($request->program_name == 'مرن 2×1') {
                $selectedProgram = $request->resultOfClculater['programs']['flexibleProgram'];
            }
        }else {
            $propertyFund_cost = null;
        }
        if ($this->checkIfNull('propertyProgram', $request->resultOfClculater['programs'])) {
            if ($request->program_name == 'عقاري فقط') {
                $selectedProgram = $request->resultOfClculater['programs']['propertyProgram'];
            }
        }else {
            $propertyFund_cost = null;
        }

        try {
            if ($this->checkIfNull('flexibleProgram', $request->resultOfClculater['programs'])) {
                $flexiableFund_cost = $request->resultOfClculater['programs']['flexibleProgram']['net_loan_total'] ?? null;
            }
            else {
                $flexiableFund_cost = null;
            }
        }
        catch (Exception $exception) {
            $flexiableFund_cost = null;
        }

        //dd($request->all(),$request->resultOfClculater['programs']);
        if ($request->program_name == 'عقاري فقط') {
            if ($this->checkIfNull('propertyProgram', $request->resultOfClculater['programs'])) {
                $propertyFund_cost = $request->resultOfClculater['programs']['propertyProgram']['net_loan_total'] ?? null;
            }
            else {
                $propertyFund_cost = null;
            }
        }
        elseif ($request->program_name == 'مرن 2×1') {
            if ($this->checkIfNull('flexibleProgram', $request->resultOfClculater['programs'])) {
                $propertyFund_cost = $request->resultOfClculater['programs']['flexibleProgram']['flexible_loan_total'] ?? null;
            }
            else {
                $propertyFund_cost = null;
            }
        }
        else {
            $propertyFund_cost = null;
        }
        if ($this->checkIfNull('personalProgram', $request->resultOfClculater['programs'])) {
            $personalFund_cost = $request->resultOfClculater['programs']['personalProgram']['personal_net_loan_total'] ?? null;
        }
        else {
            $personalFund_cost = null;
        }

        if ($this->checkIfNull('extendedProgram', $request->resultOfClculater['programs'])) {
            $extendedFund_cost = $request->resultOfClculater['programs']['extendedProgram']['personal_net_loan_total'] ?? null;
        }
        else {
            $extendedFund_cost = null;
        }

        if ($selectedProgram != null && $this->checkIfNull('personal_installment', $selectedProgram)) {
            $personal_installment = $selectedProgram['personal_installment'];
        }
        else {
            $personal_installment = null;
        }
        if ($selectedProgram != null) {
            $installment = $selectedProgram['installment'];
        }
        else {
            $installment = null;
        }

        if ($selectedProgram != null) {
            $installment_after_support = $selectedProgram['installment_after_support'];
        }
        else {
            $installment_after_support = null;
        }
        $hiring_date = null;
        $joint_hiring_date = null;
        $job_tenure_caculater = null;
        $joint_job_tenure_caculater = null;
        //dd();
        if ($request->dataOfInputs && is_array($request->dataOfInputs)) {
            if (($request->dataOfInputs['job_tenure_caculater'] ?? null) != null) {
                $hiring_date = $request->dataOfInputs['job_tenure_caculater'];
                $job_tenure_caculater = $this->calculateHijriAge($request->dataOfInputs['job_tenure_caculater']) * 12; //years * 12 = to get months
            }
            if (($request->dataOfInputs['joint_job_tenure_caculater'] ?? null) != null) {
                $joint_hiring_date = $request->dataOfInputs['joint_job_tenure_caculater'];
                $joint_job_tenure_caculater = $this->calculateHijriAge($request->dataOfInputs['joint_job_tenure_caculater']) * 12; //years * 12 = to get months
            }
        }
        $request->merge(["hiring_date" => $hiring_date]);
        $request->merge(["joint_hiring_date" => $joint_hiring_date]);
        $request->merge(["selectedProgram" => $selectedProgram]);

        $bank_id_code = $this->updateRequest($request);
        $arr=[
            'program_name' => $request->program_name,
            'product_code' => $request->dataOfInputs['product_type_id_caculater'],
            'request_id'   => $request->requestID,
            'user_id'      => $currentUser,
            'switch_id'    => $userSwitch,
            'bank_id'      => $bank_id,

            'work'                => $request->dataOfInputs['work_caculater'],
            'military_rank'       => $request->dataOfInputs['military_rank_caculater'],
            'residential_support' => $this->checkIfNull('residential_support_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['residential_support_caculater'] : null,
            'age'                 => $request->customer_age,

            'joint_birth_hijri' => $this->checkIfNull('joint_birth_hijri_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_birth_hijri_caculater'] : null,
            'birth_hijri'       => $this->checkIfNull('birth_hijri_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['birth_hijri_caculater'] : null,
            'salary'            => $this->checkIfNull('salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['salary_caculater'] : null,
            'basic_salary'      => $this->checkIfNull('basic_salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['basic_salary_caculater'] : null,

            'guarantees'                        => $this->checkIfNull('guarantees_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['guarantees_caculater'] : null,
            'salary_bank_id'                    => $this->checkIfNull('salary_bank_id_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['salary_bank_id_caculater'] : null,
            'add_support_installment_to_salary' => $this->checkIfNull('add_support_installment_to_salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['add_support_installment_to_salary_caculater'] : null,
            'without_transfer_salary'           => $this->checkIfNull('without_transfer_salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['without_transfer_salary_caculater'] : null,

            'housing_allowance'  => $this->checkIfNull('housing_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['housing_allowance_caculater'] : null,
            'transfer_allowance' => $this->checkIfNull('transfer_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['transfer_allowance_caculater'] : null,
            'other_allowance'    => $this->checkIfNull('other_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['other_allowance_caculater'] : null,
            'retirement_income'  => $this->checkIfNull('retirement_income_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['retirement_income_caculater'] : null,
            'job_tenure'         => $job_tenure_caculater,
            'hiring_date'        => $hiring_date,

            'personal_salary_deduction'         => $this->checkIfNull('personal_salary_deduction_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['personal_salary_deduction_caculater'] : null,
            'salary_deduction'                  => $this->checkIfNull('salary_deduction_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['salary_deduction_caculater'] : null,
            'funding_months'                    => $this->checkIfNull('funding_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['funding_months_caculater'] : null,
            'personal_funding_months'           => $this->checkIfNull('personal_funding_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['personal_funding_months_caculater'] : null,

            //            'personal_bank_profit'              => $this->checkIfNull('personal_profit', $selectedProgram['raw']) != false ? $selectedProgram['raw']['personal_profit']['data'] : null,
            'personal_bank_profit'              => $this->checkIfNull('personal_profit', $selectedProgram) != false ? $selectedProgram['personal_profit'] : null,
            //            'personal_bank_profit'              => 0,
            'bank_profit'                       => $this->checkIfNull('profit', $selectedProgram) != false ? $selectedProgram['profit'] : null,
            'flexiableFun_cost'                 => $flexiableFund_cost,
            'extendedFund_cost'                 => $extendedFund_cost,
            'realFun_cost'                      => $propertyFund_cost,
            'personalFun_cost'                  => $personalFund_cost,
            'personal_monthly_installment'      => $personal_installment,
            'monthly_installment'               => $installment,
            'monthly_installment_after_support' => $installment_after_support,

            'early_repayment'              => $this->checkIfNull('early_repayment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['early_repayment_caculater'] : null,
            'credit_installment'           => $this->checkIfNull('credit_installment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['credit_installment_caculater'] : null,
            'obligations_installment'      => $this->checkIfNull('obligations_installment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['obligations_installment_caculater'] : null,
            'remaining_obligations_months' => $this->checkIfNull('remaining_obligations_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['remaining_obligations_months_caculater'] : null,

            'property_amount' => $this->checkIfNull('property_amount_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['property_amount_caculater'] : null,

            'property_completed' => $this->checkIfNull('property_completed_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['property_completed_caculater'] : null,
            'residence_type'     => $this->checkIfNull('residence_type_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['residence_type_caculater'] : null,
            'have_joint'         => $this->checkIfNull('have_joint_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['have_joint_caculater'] : null,
            'joint_age'          => $request->joint_age,
            'joint_salary'       => $this->checkIfNull('joint_salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_salary_caculater'] : null,
            'joint_basic_salary' => null,

            'joint_hiring_date'                       => $joint_hiring_date,
            'joint_work'                              => $this->checkIfNull('joint_work_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_work_caculater'] : null,
            'joint_military_rank'                     => $this->checkIfNull('joint_military_rank_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_military_rank_caculater'] : null,
            'joint_residential_support'               => $this->checkIfNull('joint_residential_support_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_residential_support_caculater'] : null,
            'joint_add_support_installment_to_salary' => $this->checkIfNull('joint_add_support_installment_to_salary_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_add_support_installment_to_salary_caculater'] : null,

            'joint_personal_salary_deduction' => $this->checkIfNull('joint_personal_salary_deduction_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_personal_salary_deduction_caculater'] : null,
            'joint_salary_deduction'          => $this->checkIfNull('joint_salary_deduction_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_salary_deduction_caculater'] : null,
            'joint_funding_months'            => $this->checkIfNull('joint_funding_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_funding_months_caculater'] : null,
            'joint_personal_funding_months'   => $this->checkIfNull('joint_personal_funding_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_personal_funding_months_caculater'] : null,

            'joint_housing_allowance'  => $this->checkIfNull('joint_housing_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_housing_allowance_caculater'] : null,
            'joint_transfer_allowance' => $this->checkIfNull('joint_transfer_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_transfer_allowance_caculater'] : null,
            'joint_other_allowance'    => $this->checkIfNull('joint_other_allowance_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_other_allowance_caculater'] : null,
            'joint_retirement_income'  => $this->checkIfNull('joint_retirement_income_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_retirement_income_caculater'] : null,
            'joint_job_tenure'         => $joint_job_tenure_caculater,

            'joint_salary_bank_id'               => $this->checkIfNull('joint_salary_bank_id_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_salary_bank_id_caculater'] : null,
            'joint_early_repayment'              => $this->checkIfNull('joint_early_repayment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_early_repayment_caculater'] : null,
            'joint_credit_installment'           => $this->checkIfNull('joint_credit_installment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_credit_installment_caculater'] : null,
            'joint_obligations_installment'      => $this->checkIfNull('joint_obligations_installment_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_obligations_installment_caculater'] : null,
            'joint_remaining_obligations_months' => $this->checkIfNull('joint_remaining_obligations_months_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['joint_remaining_obligations_months_caculater'] : null,

            'first_batch_mode'       => $this->checkIfNull('first_batch_mode_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['first_batch_mode_caculater'] : null,
            'provide_first_batch'    => $this->checkIfNull('provide_first_batch_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['provide_first_batch_caculater'] : null,
            'first_batch_percentage' => $this->checkIfNull('first_batch_percentage_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['first_batch_percentage_caculater'] : null,

            'first_batch_profit' => $this->checkIfNull('first_batch_profit_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['first_batch_profit_caculater'] : null,
            'fees'               => $this->checkIfNull('fees_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['fees_caculater'] : null,
            'discount'           => $this->checkIfNull('discount_caculater', $request->dataOfInputs) != false ? $request->dataOfInputs['discount_caculater'] : null,

            'is_there_result' => 1,
            'created_at'      => Carbon::now('Asia/Riyadh'),
        ];

        $addNewCal = DB::table('calculaters')->insertGetId($arr);

        return response()->json([
            //'result'            => $result,
            'addNewCal'         => $addNewCal,
            'bank_id'           => $bank_id_code,
            'hiring_date'       => $request->hiring_date,
            'joint_hiring_date' => $request->joint_hiring_date,
        ]);
    }

    public function getMatchBanksByCode($code)
    {

        $matchBank = null;

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($response->getBody(), true);
        $banks = $banks['data'];

        foreach ($banks as $bank) {
            if ($code == $bank['code']) {

                $matchBank = $bank;
                break;
            }
        }

        return $matchBank;
    }

    public function updateRequest($request)
    {

        $reqID = $request->requestID;
        $fundingReq = DB::table('requests')->where('id', $reqID)->first();

        //JOINT---------------------------------------------------
        $jointId = $fundingReq->joint_id;

        if ($this->checkIfNull('joint_birth_hijri_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'jointBirth_higri', $request->dataOfInputs['joint_birth_hijri_caculater']);
            $joint_birth_hijri_caculater = $request->dataOfInputs['joint_birth_hijri_caculater'];
        }
        else {
            $joint_birth_hijri_caculater = null;
            $this->records($reqID, 'jointBirth_higri', null);
        }

        if ($this->checkIfNull('joint_work_caculater', $request->dataOfInputs)) {
            $getworkValue = DB::table('work_sources')->where('id', $request->dataOfInputs['joint_work_caculater'])->first();
            if (!empty($getworkValue)) {
                $this->records($reqID, 'jointWork', $getworkValue->value);
            }
            $joint_work_caculater = $request->dataOfInputs['joint_work_caculater'];
        }
        else {
            $joint_work_caculater = null;
            $this->records($reqID, 'jointWork', null);
        }

        $jointAge = $request->resultOfClculater['joint_age'] ?? 0;
        $this->records($reqID, 'jointage_years', $jointAge);

        $joint_hiring_date = null;
        if ($request->joint_hiring_date != null) {
            $joint_hiring_date = $request->joint_hiring_date;
            $this->records($reqID, 'joint_hiring_date', $joint_hiring_date);
        }

        if ($this->checkIfNull('joint_salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'jointSalary', $request->dataOfInputs['joint_salary_caculater']);
            $joint_salary_caculater = $request->dataOfInputs['joint_salary_caculater'];
        }
        else {
            $this->records($reqID, 'jointSalary', null);
            $joint_salary_caculater = null;
        }

        if ($this->checkIfNull('joint_military_rank_caculater', $request->dataOfInputs)) {
            $getjointrankValue = DB::table('military_ranks')->where('id', $request->dataOfInputs['joint_military_rank_caculater'])->first();
            if (!empty($getjointrankValue)) {
                $this->records($reqID, 'jointRank', $getjointrankValue->value);
            }
            $joint_military_rank_caculater = $request->dataOfInputs['joint_military_rank_caculater'];
        }
        else {
            $this->records($reqID, 'jointRank', null);
            $joint_military_rank_caculater = null;
        }

        if ($this->checkIfNull('joint_residential_support_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'jointSupport', 'نعم');
            $joint_residential_support_caculater = 'yes';
        }
        else {
            $this->records($reqID, 'jointSupport', 'لا');
            $joint_residential_support_caculater = 'no';
        }

        if ($this->checkIfNull('joint_add_support_installment_to_salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'joint_add_support_installment_to_salary', 'نعم');
            $joint_add_support_installment_to_salary = 1;
        }
        else {
            $this->records($reqID, 'joint_add_support_installment_to_salary', 'لا');
            $joint_add_support_installment_to_salary = 0;
        }

        if ($this->checkIfNull('joint_salary_bank_id_caculater', $request->dataOfInputs)) {
            $getsalaryValue = DB::table('salary_sources')->where('id', $request->dataOfInputs['joint_salary_bank_id_caculater'])->first();
            if (!empty($getsalaryValue)) {
                $this->records($reqID, 'jointsalary_source', $getsalaryValue->value);
            }

            $joint_salary_bank_id_caculater = $request->dataOfInputs['joint_salary_bank_id_caculater'];
        }
        else {
            $joint_salary_bank_id_caculater = null;
            $this->records($reqID, 'jointsalary_source', null);
        }

        if ($this->checkIfNull('joint_has_obligations_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'jointObligations', 'نعم');
            $joint_has_obligations_caculater = 'yes';
        }
        else {
            $this->records($reqID, 'joint_has_obligations_caculater', 'لا');
            $joint_has_obligations_caculater = 'no';
        }

        if ($this->checkIfNull('joint_early_repayment_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'jointobligations_value', $request->dataOfInputs['joint_early_repayment_caculater']);
            $joint_early_repayment_caculater = $request->dataOfInputs['joint_early_repayment_caculater'];
        }
        else {
            $this->records($reqID, 'jointobligations_value', null);
            $joint_early_repayment_caculater = null;
        }

        DB::table('joints')->where('id', $jointId)->update([
            'salary'                            => $joint_salary_caculater,
            'birth_date_higri'                  => $joint_birth_hijri_caculater,
            'age_years'                         => $jointAge,
            'work'                              => $joint_work_caculater,
            'hiring_date'                       => $joint_hiring_date,
            'salary_id'                         => $joint_salary_bank_id_caculater,
            'military_rank'                     => $joint_military_rank_caculater,
            'add_support_installment_to_salary' => $joint_add_support_installment_to_salary,
            'is_supported'                      => $joint_residential_support_caculater,
            'has_obligations'                   => $joint_has_obligations_caculater,
            'obligations_value'                 => $joint_early_repayment_caculater,
        ]);

        //END JOINT---------------------------------------------------

        //REAL ESTAT---------------------------------------------------

        $realId = $fundingReq->real_id;

        if ($this->checkIfNull('property_amount_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'realCost', $request->dataOfInputs['property_amount_caculater']);
            $property_amount_caculater = $request->dataOfInputs['property_amount_caculater'];
        }
        else {
            $property_amount_caculater = null;
            $this->records($reqID, 'realCost', null);
        }

        if ($this->checkIfNull('property_completed_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'realStatus', 'مكتمل');
            $property_completed_caculater = 'مكتمل';
        }
        else {
            $property_completed_caculater = 'عظم';
            $this->records($reqID, 'realCost', 'عظم');
        }

        if ($this->checkIfNull('residence_type_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'residence_type', $request->dataOfInputs['residence_type_caculater'] == 1 ? 'أول' : 'ثاني');
            $residence_type_caculater = $request->dataOfInputs['residence_type_caculater'];
        }
        else {
            $residence_type_caculater = null;
        }

        DB::table('real_estats')->where('id', $realId)->update([
            'status'         => $property_completed_caculater,
            'cost'           => $property_amount_caculater,
            'residence_type' => $residence_type_caculater,
        ]);

        //END REAL ESTAT---------------------------------------------------

        //FUNDING---------------------------------------------------

        $fundingId = $fundingReq->fun_id;
        $bank_code_id = null;

        $bankInfo = $this->getMatchBanksByCode($request->resultOfClculater['bank_code']);
        if ($bankInfo != null) {
            $bankInfo = $this->getMatchBanksId2($bankInfo['name_ar']);
        }
        if ($bankInfo != null) {
            $bank_code_id = $bankInfo->id;
        }

        if ($bank_code_id != null) {
            $getfundingValue = DB::table('funding_sources')->where('id', $bank_code_id)->first();
        }
        if (!empty($getfundingValue)) {
            $this->records($reqID, 'funding_source', $getfundingValue->value);
        }

        $personal_salary_deduction_caculater = null;
        if ($this->checkIfNull('personal_salary_deduction_caculater', $request->dataOfInputs) && $request->dataOfInputs['personal_salary_deduction_caculater'] != null && $request->dataOfInputs['personal_salary_deduction_caculater'] != '') {
            $this->records($reqID, 'personal_salary_deduction', $request->dataOfInputs['personal_salary_deduction_caculater']);
            $personal_salary_deduction_caculater = $request->dataOfInputs['personal_salary_deduction_caculater'];
        }
        elseif ($request->selectedProgram != null) {
            if ($this->checkIfNull('personal_salary_deduction', $request->selectedProgram)) {
                $this->records($reqID, 'personal_salary_deduction', $request->selectedProgram['personal_salary_deduction']);
                $personal_salary_deduction_caculater = $request->selectedProgram['personal_salary_deduction'];
            }
        }
        else {
            $personal_salary_deduction_caculater = null;
            $this->records($reqID, 'personal_salary_deduction', null);
        }

        if ($this->checkIfNull('personal_funding_months_caculater', $request->dataOfInputs) && $request->dataOfInputs['personal_funding_months_caculater'] != null && $request->dataOfInputs['personal_funding_months_caculater'] != '') {
            $this->records($reqID, 'personal_funding_months', $request->dataOfInputs['personal_funding_months_caculater']);
            $personal_funding_months_caculater = $request->dataOfInputs['personal_funding_months_caculater'];
        }
        else {
            $personal_funding_months_caculater = null;
            $this->records($reqID, 'personal_funding_months', null);
        }

        $salary_deduction_caculater = null;
        if ($this->checkIfNull('salary_deduction_caculater', $request->dataOfInputs) && $request->dataOfInputs['salary_deduction_caculater'] != null && $request->dataOfInputs['salary_deduction_caculater'] != '') {
            $this->records($reqID, 'fundDed', $request->dataOfInputs['salary_deduction_caculater']);
            $salary_deduction_caculater = $request->dataOfInputs['salary_deduction_caculater'];
        }
        elseif ($request->selectedProgram != null) {
            $this->records($reqID, 'fundDed', $request->selectedProgram['salary_deduction']);
            $salary_deduction_caculater = $request->selectedProgram['salary_deduction'];
        }
        else {
            $salary_deduction_caculater = null;
            $this->records($reqID, 'fundDed', null);
        }

        if ($this->checkIfNull('funding_months_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'funding_months', $request->dataOfInputs['funding_months_caculater']);
            $funding_months_caculater = $request->dataOfInputs['funding_months_caculater'];
        }
        else {
            $funding_months_caculater = null;
            $this->records($reqID, 'funding_months', null);
        }

        $personal_profit = null;
        if ($request->selectedProgram != null) {
            if ($this->checkIfNull('personal_profit', $request->selectedProgram)) {
                $this->records($reqID, 'fundPersPre', $request->selectedProgram['personal_profit']);
                $personal_profit = $request->selectedProgram['personal_profit'];
            }
        }
        else {
            $personal_profit = null;
            $this->records($reqID, 'fundPersPre', null);
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'fundRealPre', $request->selectedProgram['profit']);
            $profit = $request->selectedProgram['profit'];
        }
        else {
            $profit = null;
            $this->records($reqID, 'fundRealPre', null);
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'fundFlex', $request->selectedProgram['net_loan_total']);
            $flexiableFund_cost = $request->selectedProgram['net_loan_total'];
        }
        else {
            $flexiableFund_cost = null;
            $this->records($reqID, 'fundFlex', null);
        }

        if ($request->selectedProgram != null) {

            $property_funding = null;

            if ($request->program_name == 'عقاري فقط') {
                $property_funding = $request->resultOfClculater['programs']['propertyProgram']['net_loan_total'];
            }
            elseif ($request->program_name == 'مرن 2×1') {
                $property_funding = $request->resultOfClculater['programs']['flexibleProgram']['flexible_loan_total'];
            }
            else {
                $property_funding = $request->selectedProgram['net_loan_total'];
            }

            $this->records($reqID, 'fundReal', $property_funding);
            $propertyFund_cost = $property_funding;

        }
        else {
            $this->records($reqID, 'fundReal', null);
            $propertyFund_cost = null;
        }

        $personalFund_cost = null;
        if ($request->selectedProgram != null) {
            if ($this->checkIfNull('personal_net_loan_total', $request->selectedProgram)) {
                $this->records($reqID, 'fundPers', $request->selectedProgram['personal_net_loan_total']);
                $personalFund_cost = $request->selectedProgram['personal_net_loan_total'];
            }
        }
        else {
            $this->records($reqID, 'fundPers', null);
            $personalFund_cost = null;
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'fundExten', $request->selectedProgram['net_loan_total']);
            $extendFund_cost = $request->selectedProgram['net_loan_total'];
        }
        else {
            $this->records($reqID, 'fundExten', null);
            $extendFund_cost = null;
        }

        $personal_installment = null;
        if ($request->selectedProgram != null) {
            if ($this->checkIfNull('personal_installment', $request->selectedProgram)) {
                $this->records($reqID, 'personal_installment', $request->selectedProgram['personal_installment']);
                $personal_installment = $request->selectedProgram['personal_installment'];
            }
        }
        else {
            $this->records($reqID, 'personal_installment', null);
            $personal_installment = null;
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'fundMonth', $request->selectedProgram['installment']);
            $installment = $request->selectedProgram['installment'];
        }
        else {
            $this->records($reqID, 'fundMonth', null);
            $installment = null;
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'installment_after_support', $request->selectedProgram['installment_after_support']);
            $installment_after_support = $request->selectedProgram['installment_after_support'];
        }
        else {
            $installment_after_support = null;
            $this->records($reqID, 'installment_after_support', null);
        }

        if ($request->selectedProgram != null) {
            $this->records($reqID, 'fundDur', $request->selectedProgram['funding_years']);
            $funding_years = $request->selectedProgram['funding_years'];
        }
        else {
            $funding_years = null;
            $this->records($reqID, 'fundDur', null);
        }
        //dd($request->dataOfInputs);
        $product_code = $request->dataOfInputs['product_type_id_caculater'];
        $matchcode = $this->getSpasficProductType($product_code);
        if ($matchcode != null) {
            $this->records($reqID, 'product_type', $matchcode['name_ar']);
        }

        DB::table('fundings')->where('id', $fundingId)->update([
            'funding_source'                    => $bank_code_id,
            'funding_duration'                  => $funding_years,
            'personalFun_cost'                  => $personalFund_cost,
            'personalFun_pre'                   => $personal_profit,
            'realFun_cost'                      => $propertyFund_cost,
            'realFun_pre'                       => $profit,
            'ded_pre'                           => $salary_deduction_caculater,
            'monthly_in'                        => $installment,
            'funding_months'                    => $funding_months_caculater,
            'personal_funding_months'           => $personal_funding_months_caculater,
            'personal_salary_deduction'         => $personal_salary_deduction_caculater,
            'personal_monthly_installment'      => $personal_installment,
            'flexiableFun_cost'                 => $flexiableFund_cost,
            'monthly_installment_after_support' => $installment_after_support,
            'extendFund_cost'                   => $extendFund_cost,
            'product_code'                      => $product_code,
        ]);

        //END FUNDING---------------------------------------------------

        //CUSTOMER---------------------------------
        $customerId = $fundingReq->customer_id;

        if ($this->checkIfNull('birth_hijri_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'birth_hijri', $request->dataOfInputs['birth_hijri_caculater']);
            $birth_hijri = $request->dataOfInputs['birth_hijri_caculater'];
        }
        else {
            $birth_hijri = null;
            $this->records($reqID, 'birth_hijri', null);
        }

        if ($this->checkIfNull('work_caculater', $request->dataOfInputs)) {
            $getworkValue = DB::table('work_sources')->where('id', $request->dataOfInputs['work_caculater'])->first();
            if (!empty($getworkValue)) {
                $this->records($reqID, 'work', $getworkValue->value);
            }
            $work_caculater = $request->dataOfInputs['work_caculater'];
        }
        else {
            $work_caculater = null;
            $this->records($reqID, 'work', null);
        }

        $hiring_date = null;
        if ($request->hiring_date != null) {
            $hiring_date = $request->hiring_date;
            $this->records($reqID, 'hiring_date', $hiring_date);
        }

        if ($this->checkIfNull('salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'salary', $request->dataOfInputs['salary_caculater']);
            $salary_caculater = $request->dataOfInputs['salary_caculater'];
        }
        else {
            $this->records($reqID, 'salary', null);
            $salary_caculater = null;
        }

        if ($this->checkIfNull('residential_support_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'support', 'نعم');
            $residential_support_caculater = 'yes';
        }
        else {
            $this->records($reqID, 'support', 'لا');
            $residential_support_caculater = 'no';
        }

        if ($this->checkIfNull('has_obligations_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'obligations', 'نعم');
            $has_obligations_caculater = 'yes';
        }
        else {
            $this->records($reqID, 'obligations', 'لا');
            $has_obligations_caculater = 'no';
        }

        if ($this->checkIfNull('early_repayment_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'obligations_value', $request->dataOfInputs['early_repayment_caculater']);
            $early_repayment_caculater = $request->dataOfInputs['early_repayment_caculater'];
        }
        else {
            $this->records($reqID, 'obligations_value', null);
            $early_repayment_caculater = null;
        }

        if ($this->checkIfNull('salary_bank_id_caculater', $request->dataOfInputs)) {
            $getsalaryValue = DB::table('salary_sources')->where('id', $request->dataOfInputs['salary_bank_id_caculater'])->first();
            if (!empty($getsalaryValue)) {
                $this->records($reqID, 'salary_source', $getsalaryValue->value);
            }

            $salary_bank_id_caculater = $request->dataOfInputs['salary_bank_id_caculater'];
        }
        else {
            $salary_bank_id_caculater = null;
            $this->records($reqID, 'salary_source', null);
        }

        if ($this->checkIfNull('military_rank_caculater', $request->dataOfInputs)) {
            $getrankValue = DB::table('military_ranks')->where('id', $request->dataOfInputs['military_rank_caculater'])->first();
            if (!empty($getrankValue)) {
                $this->records($reqID, 'rank', $getrankValue->value);
            }
            $military_rank_caculater = $request->dataOfInputs['military_rank_caculater'];
        }
        else {
            $military_rank_caculater = null;
            $this->records($reqID, 'rank', null);
        }

        if ($this->checkIfNull('have_joint_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'have_joint', 'نعم');
            $have_joint_caculater = 'yes';
        }
        else {
            $this->records($reqID, 'have_joint', 'لا');
            $have_joint_caculater = 'no';
        }

        $age_years = $request->customer_age;
        $this->records($reqID, 'age_years', $age_years);

        if ($this->checkIfNull('without_transfer_salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'without_transfer_salary', 'نعم');
            $without_transfer_salary_caculater = 1;
        }
        else {
            $this->records($reqID, 'without_transfer_salary', 'لا');
            $without_transfer_salary_caculater = 0;
        }

        if ($this->checkIfNull('add_support_installment_to_salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'add_support_installment_to_salary', 'نعم');
            $add_support_installment_to_salary_caculater = 1;
        }
        else {
            $this->records($reqID, 'add_support_installment_to_salary', 'لا');
            $add_support_installment_to_salary_caculater = 0;
        }

        if ($this->checkIfNull('basic_salary_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'basic_salary', $request->dataOfInputs['basic_salary_caculater']);
            $basic_salary_caculater = $request->dataOfInputs['basic_salary_caculater'];
        }
        else {
            $this->records($reqID, 'basic_salary', null);
            $basic_salary_caculater = null;
        }

        if ($this->checkIfNull('guarantees_caculater', $request->dataOfInputs)) {
            $this->records($reqID, 'guarantees', 'نعم');
            $guarantees_caculater = 1;
        }
        else {
            $this->records($reqID, 'guarantees', 'لا');
            $guarantees_caculater = 0;
        }

        //UpdateCustomer
        $updateResult = DB::table('customers')->where([
            ['id', '=', $customerId],
        ])->update([
            'birth_date_higri'                  => $birth_hijri,
            'work'                              => $work_caculater,
            'salary'                            => $salary_caculater,
            'is_supported'                      => $residential_support_caculater,
            'obligations_value'                 => $early_repayment_caculater,
            'salary_id'                         => $salary_bank_id_caculater,
            'military_rank'                     => $military_rank_caculater,
            'has_obligations'                   => $has_obligations_caculater,
            'has_joint'                         => $have_joint_caculater,
            'age_years'                         => $age_years,
            'without_transfer_salary'           => $without_transfer_salary_caculater,
            'add_support_installment_to_salary' => $add_support_installment_to_salary_caculater,
            'basic_salary'                      => $basic_salary_caculater,
            'guarantees'                        => $guarantees_caculater,
            'hiring_date'                       => $hiring_date,
        ]);

        //END CUSTOMER---------------------------------
        //dd($updateResult);
        return $bank_code_id; // because i want to send it with response

    }

    public function records($reqID, $coloum, $value)
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
                'comment'        => 'حاسبة التمويل',
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
                    'comment'        => 'حاسبة التمويل',
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function getMatchBanksId2($bank_name)
    {

        $matchBank = null;

        $banks = DB::table('funding_sources')->get();

        foreach ($banks as $bank) {
            similar_text($bank_name, $bank->value, $percent);
            if ($percent >= 90) {

                $matchBank = $bank;
                break;
            }
        }
        return $matchBank;
    }

    public function getSpasficProductType($code)
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

    public function noResultOfCalculater(Request $request)
    {

        $currentUser = auth()->user()->id;

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        $addNewCal = DB::table('calculaters')->insertGetId([
            'request_id'      => $request->requestID,
            'user_id'         => $currentUser,
            'switch_id'       => $userSwitch,
            'is_there_result' => 0,
            'created_at'      => Carbon::now('Asia/Riyadh'),
        ]);

        return response($addNewCal);
    }

    public function getProductType()
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

        return response($products);
    }

    public function calculaterHistory($id)
    {
        $histories = Calculater::where('request_id', $id)->leftJoin('users', 'users.id', 'calculaters.user_id')->leftJoin('users as switch', 'switch.id', 'calculaters.switch_id')->select('calculaters.*', 'users.name as user_name', 'switch.name as switch_name')->orderBy('calculaters.created_at',
            'DESC')->get();
        return view('FundingCalculater.calculaterHistory', compact('histories'));
    }

    ///FUNDING CALCULATER
    public function index()
    {
        if (auth()->user()->role != 2 && auth()->user()->role != 1 && auth()->user()->role != 7) {
            return redirect()->back();
        }

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $ranks = DB::table('military_ranks')->select('id', 'value')->get();
        $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
        $worke_sources = WorkSource::all();

        return view('FundingCalculater.index', compact('salary_sources', 'ranks', 'funding_sources', 'worke_sources'));
    }

    public function calculaterApi2(Request $request)
    {
        $rules = [];
        $rules = [
            'birth_hijri_caculater'                     => ['required', 'regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'],
            'salary_caculater'                          => 'required',
            'work_caculater'                            => 'required',
            'product_type_id_caculater'                 => 'required',
            'salary_deduction_caculater'                => 'numeric|nullable',
            'joint_salary_deduction_caculater'          => 'numeric|nullable',
            'personal_salary_deduction_caculater'       => 'numeric|nullable',
            'joint_personal_salary_deduction_caculater' => 'numeric|nullable',
            //'basic_salary_caculater' => 'required|numeric',
            //'job_tenure_caculater' => 'required',
        ];
        //$rules['job_tenure_caculater'] = 'required';
        //$rules['basic_salary_caculater'] = 'required';
        if ($request->has('work_caculater')) {
            if ($request->work_caculater == 1) {
                $rules['military_rank_caculater'] = 'required';
            }
        }
        if ($request->has('joint_work_caculater')) {
            if ($request->joint_work_caculater == 1) {
                $rules['joint_military_rank_caculater'] = 'required';
            }
        }
        if ($request->has('guarantees_caculater')) {
            $rules['basic_salary_caculater'] = 'required';
        }
        if ($request->has('has_obligations_caculater')) {
            if (($request->obligations_installment_caculater == null) && ($request->remaining_obligations_months_caculater == null)) {
                $rules['early_repayment_caculater'] = 'required';
            }
            else {
                if ($request->obligations_installment_caculater != null) {
                    $rules['remaining_obligations_months_caculater'] = 'required';
                }
                if ($request->remaining_obligations_months_caculater != null) {
                    $rules['obligations_installment_caculater'] = 'required';
                }
            }
        }
        if ($request->has('provide_first_batch_caculater')) {
            $rules['first_batch_profit_caculater'] = 'required';
        }
        if ($request->has('have_joint_caculater')) {
            $rules['joint_birth_hijri_caculater'] = ['required', 'regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'];
            $rules['joint_salary_caculater'] = 'required';
            $rules['joint_work_caculater'] = 'required';
        }
        if ($request->has('joint_has_obligations_caculater')) {
            if (($request->joint_obligations_installment_caculater == null) && ($request->joint_remaining_obligations_months_caculater == null)) {
                $rules['joint_early_repayment_caculater'] = 'required';
            }
            else {
                if ($request->joint_obligations_installment_caculater != null) {
                    $rules['joint_remaining_obligations_months_caculater'] = 'required';
                }
                if ($request->joint_remaining_obligations_months_caculater != null) {
                    $rules['joint_obligations_installment_caculater'] = 'required';
                }
            }
        }
        /*
        if ($request->has('extended_caculater')) {
            $rules['job_tenure_caculater'] = 'required';
            $rules['basic_salary_caculater'] = 'required';
        }
        */
        $customMessages = [
            'required'                          => MyHelpers::guest_trans('The filed is required'),
            'birth_hijri_caculater.regex'       => "أدخل صيغة صحيحة لتاريخ الميلاد",
            'joint_birth_hijri_caculater.regex' => "أدخل صيغة صحيحة لتاريخ الميلاد",
        ];
        $this->validate($request, $rules, $customMessages);
        //RESTRUCTRE OF REQUEST-------------------
        //1- AGE OF CUSTOMER & Joint::
        $customerAge = $this->calculateHijriAge($request->birth_hijri_caculater);
        //        $customerAge = $this->calculateHijriAgeWithMonth($request->birth_hijri_caculater);
        $request->merge(["customerAge" => $customerAge]);

        //        $ageByMonth = $customerAge * 12;
        $ageByMonth = $this->calculateHijriAgeWithMonth($request->birth_hijri_caculater);
        $request->merge(["customerAgeByMonth" => $ageByMonth]);

        if ($request->has('have_joint_caculater')) {
            $jointAge = $this->calculateHijriAge($request->joint_birth_hijri_caculater);
            $request->merge(["jointAge" => $jointAge]);

            $jointAgeByMonth = $jointAge * 12;
            $request->merge(["jointAgeByMonth" => $jointAgeByMonth]);
        }
        else {
            $request->merge(["jointAge" => null]);
        }
        //2- Banks::
        if ($request->has('salary_bank_id_caculater')) {
            if ($request->salary_bank_id_caculater != '' && $request->salary_bank_id_caculater != null) {

                $bankInfo = MyHelpers::getSalaryBankInfo($request->salary_bank_id_caculater);
                if ($bankInfo != false) {
                    $matchBank = $this->getMatchBanks($bankInfo->value);
                }
                if ($matchBank != null) {
                    $request->merge(["salary_bank_code" => $matchBank]);
                }
            }
        }

        if (!$request->has('salary_bank_code')) {
            $request->merge(["salary_bank_code" => null]);
        }

        if ($request->has('joint_salary_bank_id_caculater')) {
            if ($request->joint_salary_bank_id_caculater != '' && $request->joint_salary_bank_id_caculater != null) {

                $bankInfo = MyHelpers::getSalaryBankInfo($request->joint_salary_bank_id_caculater);
                if ($bankInfo != false) {
                    $matchBank = $this->getMatchBanks($bankInfo->value);
                }
                if ($matchBank != null) {
                    $request->merge(["joint_salary_bank_code" => $matchBank]);
                }
            }
        }

        if (!$request->has('joint_salary_bank_code')) {
            $request->merge(["joint_salary_bank_code" => null]);
        }

        //3- WORK
        if ($request->has('military_rank_caculater')) {
            if ($request->military_rank_caculater != '' && $request->military_rank_caculater != null) {

                $rankInfo = MyHelpers::getMiliratyRankInfo($request->military_rank_caculater);
                if ($rankInfo != false) {
                    $matchRank = $this->getMatchRank($rankInfo->value);
                }
                if ($matchRank != null) {
                    $request->merge(["military_rank_code" => $matchRank]);
                }
            }
        }

        if (!$request->has('military_rank_code')) {
            $work = null;
            $getworkValue = DB::table('work_sources')->where('id', $request->work_caculater)->first();
            if (!empty($getworkValue)) {
                $work = $this->getMatchWork($getworkValue->value);
            }
            $request->merge(["military_rank_code" => $work]);
        }

        if ($request->has('joint_military_rank_caculater')) {
            if ($request->joint_military_rank_caculater != '' && $request->joint_military_rank_caculater != null) {

                $rankInfo = MyHelpers::getMiliratyRankInfo($request->joint_military_rank_caculater);
                if ($rankInfo != false) {
                    $matchRank = $this->getMatchRank($rankInfo->value);
                }
                if ($matchRank != null) {
                    $request->merge(["joint_military_rank_code" => $matchRank]);
                }
            }
        }

        if (!$request->has('joint_military_rank_code')) {
            $joint_work = null;
            $getworkValue = DB::table('work_sources')->where('id', $request->joint_work_caculater)->first();
            if (!empty($getworkValue)) {
                $joint_work = $this->getMatchWork($getworkValue->value);
            }
            $request->merge(["joint_military_rank_code" => $joint_work]);
        }

        //4- reset value of boolean
        if (!$request->has('residential_support_caculater')) {
            $request->merge(["residential_support_caculater" => 0]);
        }

        if (!$request->has('add_support_installment_to_salary_caculater')) {
            $request->merge(["add_support_installment_to_salary_caculater" => 0]);
        }

        if (!$request->has('guarantees_caculater')) {
            $request->merge(["guarantees_caculater" => 0]);
        }

        if (!$request->has('without_transfer_salary_caculater')) {
            $request->merge(["without_transfer_salary_caculater" => 0]);
        }

        if (!$request->has('has_obligations_caculater')) {
            $request->merge(["has_obligations_caculater" => 0]);
        }

        if (!$request->has('provide_first_batch_caculater')) {
            $request->merge(["provide_first_batch_caculater" => 0]);
        }

        if (!$request->has('first_batch_mode_caculater')) {
            $request->merge(["first_batch_mode_caculater" => 0]);
        }

        if (!$request->has('have_joint_caculater')) {
            $request->merge(["have_joint_caculater" => 0]);
        }

        if (!$request->has('joint_residential_support_caculater')) {
            $request->merge(["joint_residential_support_caculater" => 0]);
        }

        if (!$request->has('joint_add_support_installment_to_salary_caculater')) {
            $request->merge(["joint_add_support_installment_to_salary_caculater" => 0]);
        }

        if (!$request->has('joint_has_obligations_caculater')) {
            $request->merge(["joint_has_obligations_caculater" => 0]);
        }

        #update funding month
        if ($request->personal_funding_months_caculater != null) {
            $request->personal_funding_months_caculater = $request->personal_funding_months_caculater * 12;
        }

        if ($request->joint_personal_funding_months_caculater != null) {
            $request->joint_personal_funding_months_caculater = $request->joint_personal_funding_months_caculater * 12;
        }

        if ($request->funding_months_caculater != null) {
            $request->funding_months_caculater = $request->funding_months_caculater * 12;
        }

        if ($request->joint_funding_months_caculater != null) {
            $request->joint_funding_months_caculater = $request->joint_funding_months_caculater * 12;
        }

        //SERVICE PERIOD
        if ($request->job_tenure_caculater != null) {
            $request->job_tenure_caculater = $this->calculateHijriAgeWithMonth($request->job_tenure_caculater);
        } //years * 12 = to get months

        if ($request->joint_job_tenure_caculater != null) {
            $request->joint_job_tenure_caculater = $this->calculateHijriAgeWithMonth($request->joint_job_tenure_caculater);
        } //years * 12 = to get months

        $calculaterResults = $this->connectWithCalculater($request);
        $array_net_loan_values = null;
        if (auth()->user()->role == 13){
            foreach ($calculaterResults as $key=>$calculaterResult) {
                $bankInfo = $this->getMatchBanksId($calculaterResult['bank_name']);
                if ($bankInfo->id != auth()->user()->bank_id){
                    unset($calculaterResults[$key]);
                }

            }
        }

        if (count($calculaterResults) != 0) {
            //for banks images & customer age & joint age
            $i = 0;
            foreach ($calculaterResults as $calculaterResult) {
                $bankInfo = $this->getMatchBanksId($calculaterResult['bank_name']);

                if ($bankInfo != null) {
                    $calculaterResults[$i]['bank_img'] = $bankInfo->img_location;
                }
                else {
                    $calculaterResults[$i]['bank_img'] = null;
                }

                $calculaterResults[$i]['customer_age'] = $request->customerAge;
                $calculaterResults[$i]['joint_age'] = $request->jointAge;

                $i++;
            }
            //

        }
        $getFlexibleSettings = general::getFlexibleSettings();
        $getPersonalSettings = general::getPersonalSettings();
        $getPropertySettings = general::getPropertySettings();
        $getExtendedSettings = general::getExtendedSettings();
        return response()->json([
            'calcResult' => $calculaterResults,
            //           'flexibleSettings' => $getFlexibleSettings,
            //           'personalSettings' => $getPersonalSettings,
            //           'propertySettings' => $getPropertySettings,
            //           'extendedSettings' => $getExtendedSettings,
        ]);
    }
}
