<?php

namespace App\HelperFunctions;

use App\customer;
use App\military_ranks as MilitaryRank;
use App\Notification;
use App\OtpRequest;
use App\requestConditionSettings as RequestConditionSetting;
use App\requestHistory;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

//use Stevebauman\Location\Facades\Location;

class Helper
{
    public static function moveFileToDestination($file)
    {
        $time = microtime('.') * 10000;
        $fileName = $time.'.'.strtolower($file->getClientOriginalExtension());
        $destination = 'storage/chat/';
        $move = $file->move($destination, $fileName);
        if ($move) {
            return $fileName;
        }
        else {
            return false;
        }
    }

    public static function getFileType($file)
    {
        $extension = $file->getClientOriginalExtension();
        if ($extension == "jpg" || $extension == "png" || $extension == "jpeg" || $extension == "gif") {
            $fileType = "image";
        }
        elseif ($extension == "pdf" || $extension == "docx") {
            $fileType = "file";
        }
        elseif ($extension == "mp4") {
            $fileType = "video";
        }
        else {
            $fileType = ' ';
        }
        return $fileType;
    }

    public static function getAllActiveAdmin()
    {
        return User::where('role', 7)->where('status', 1)->get();
    }

    public static function sendEmailNotifiaction($emailName, $userId, $subject, $content)
    {
        /*
        $email = Email::where('email_name', $emailName)->first();
        if ($email)
            if (EmailUser::where(['user_id' => $userId, 'email_id' => $email->id])->count() > 0) {
                Mail::to(User::find($userId)->email)->send(new \App\Mail\WastaMailNotification($subject, $content));
            }
            */
    }

    public static function calculateHijriAge($birthDate)
    {
        $now = \GeniusTS\HijriDate\Date::now();
        $birthdate = new \DateTime($birthDate);
        $today = new \Datetime($now);
        $diff = $today->diff($birthdate);
        return $diff->y;
    }

    public static function getMatchWork($work_name)
    {
        $matchWork = null;
        $client = new \GuzzleHttp\Client();
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

    public static function getMilitaryRankInfo($military_rank)
    {
        $rankInfo = MilitaryRank::where('id', $military_rank)->first();

        if (!empty($rankInfo)) {
            return $rankInfo;
        }
        return false;
    }

    public static function getMatchJobPosition($military_rank_name)
    {
        $matchRank = null;
        $client = new \GuzzleHttp\Client();
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
            similar_text($military_rank_name, $rank['name'], $percent);
            if ($percent >= 95) {
                $matchRank = $rank;
                break;
            }
        }
        if ($matchRank != null) {
            return $matchRank['code'];
        }
        else {
            return null;
        }
    }

    public static function getMatchProductTypeCode($product_type_id)
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$product_type_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $productTypes = json_decode($response->getBody(), true);
        $productType = $productTypes['data']['code'];
        return $productType;
    }

    public static function getFundingCalculatorWithMilitaryRank($getAge, $salary, $getMatchJobPosition, $getProductTypeCode)
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        $response = $client->post($url, [
            'headers'     => ['Accept' => "application/json", 'Secret' => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata", 'S-Client' => "alwsata.com.sa",],
            'form_params' => ['age' => $getAge, 'salary' => $salary, 'job_position_id' => $getMatchJobPosition, 'product_type_id' => $getProductTypeCode,],
        ]);
        $response = json_decode($response->getBody(), true);
        if ($response['responseData'] != []) {
            foreach ($response['responseData'] as $calc) {
                if (isset($calc['programs']['flexibleProgram'])) {
                    $count = count($calc['programs']['flexibleProgram']);
                    for ($i = 0; $i < $count; $i++) {
                        $personal_salary_deduction = [];
                        $installment_after_support = [];
                        $personal_installment = [];
                        $profit = [];
                        $personal_profit = [];
                        $personal_net_loan_total = [];
                        $bank_code = [];
                        $bank_name_ar = [];
                        $flexible_loan_total = [];
                        $salary_deduction = [];
                        $personal_funding_months = [];

                        $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                        $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                        $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                        $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                        $personal_salary_deduction[] = $calc['programs']['flexibleProgram']['personal_salary_deduction'];
                        $installment_after_support[] = $calc['programs']['flexibleProgram']['installment_after_support'];
                        $personal_installment[] = $calc['programs']['flexibleProgram']['personal_installment'];
                        $profit[] = $calc['programs']['flexibleProgram']['profit'];
                        $personal_profit[] = $calc['programs']['flexibleProgram']['personal_profit'];
                        $personal_net_loan_total[] = $calc['programs']['flexibleProgram']['personal_net_loan_total'];
                        $bank_code[] = $calc['programs']['flexibleProgram']['bank_code'];
                        $bank_name_ar[] = $calc['programs']['flexibleProgram']['bank_name'];
                        $flexible_loan_total[] = $calc['programs']['flexibleProgram']['flexible_loan_total'];
                        $salary_deduction[] = $calc['programs']['flexibleProgram']['salary_deduction'];
                        $personal_funding_months[] = $calc['programs']['flexibleProgram']['personal_funding_months'];

                        $netLoanTotalMax = max($net_loan_total);
                        $getInstallmentMax = max($getInstallment);
                        $getFundingYearsMax = max($getFundingYears);
                        $getFundingMonthsMax = max($getFundingMonths);
                        $personal_salary_deduction = max($personal_salary_deduction);
                        $installment_after_support = max($installment_after_support);
                        $personal_installment = max($personal_installment);
                        $profit = max($profit);
                        $personal_profit = max($personal_profit);
                        $personal_net_loan_total = max($personal_net_loan_total);
                        $bank_code = max($bank_code);
                        $bank_name_ar = max($bank_name_ar);
                        $flexible_loan_total = max($flexible_loan_total);
                        $salary_deduction = max($salary_deduction);
                        $personal_funding_months = max($personal_funding_months);
                    }
                    $bank_code_id = null;
                    $bankInfo = Helper::getMatchBanksByCode($bank_code);
                    if ($bankInfo != null) {
                        $bankInfo = Helper::getMatchBanksId2($bank_name_ar);
                    }
                    if ($bankInfo != null) {
                        $bank_code_id = $bankInfo->id;
                    }
                    $product_name = Helper::getSpasficProductType($getProductTypeCode);
                    $arrayKeys = [
                        'net_loan_total',
                        'installment',
                        'funding_years',
                        'funding_months',
                        'personal_salary_deduction',
                        'installment_after_support',
                        'personal_installment',
                        'profit',
                        'personal_profit',
                        'personal_net_loan_total',
                        'funding_source',
                        'flexible_loan_total',
                        'salary_deduction',
                        'personal_funding_months',
                        'product_type_code',
                    ];
                    $arrayValues = [
                        $netLoanTotalMax,
                        $getInstallmentMax,
                        $getFundingYearsMax,
                        $getFundingMonthsMax,
                        $personal_salary_deduction,
                        $installment_after_support,
                        $personal_installment,
                        $profit,
                        $personal_profit,
                        $personal_net_loan_total,
                        $bank_code_id,
                        $flexible_loan_total,
                        $salary_deduction,
                        $personal_funding_months,
                        $product_name['name_ar'],
                    ];
                    $combine = array_combine($arrayKeys, $arrayValues);
                }
            }
            if ($combine == []) {
                return null;
            }
            else {
                return $combine;
            }
        }
        else {
            $combine = 0;
            return $combine;
        }

    }

    public static function getMatchBanksByCode($code)
    {
        $matchBank = null;
        $client = new \GuzzleHttp\Client();
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

    public static function getMatchBanksId2($bank_name)
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

    public static function getSpasficProductType($code)
    {
        $client = new \GuzzleHttp\Client();
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

    public static function getEnumValues($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type;
        preg_match('/^enum((.*))$/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = Arr::add($enum, $v, $v);
        }
        return $enum;
    }

    public static function getFundingCalculatorWithoutMilitaryRank($getAge, $salary, $getWork, $getProductTypeCode)
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'age'             => $getAge,
                'salary'          => $salary,
                'job_position_id' => $getWork,
                'product_type_id' => $getProductTypeCode,
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        if ($response['responseData'] != []) {
            foreach ($response['responseData'] as $calc) {
                if (isset($calc['programs']['flexibleProgram'])) {
                    for ($i = 0; $i < count($calc['programs']['flexibleProgram']); $i++) {
                        $personal_salary_deduction = [];
                        $installment_after_support = [];
                        $personal_installment = [];
                        $profit = [];
                        $personal_profit = [];
                        $personal_net_loan_total = [];
                        $bank_code = [];
                        $bank_name_ar = [];
                        $flexible_loan_total = [];
                        $salary_deduction = [];
                        $personal_funding_months = [];

                        $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                        $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                        $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                        $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                        $personal_salary_deduction[] = $calc['programs']['flexibleProgram']['personal_salary_deduction'];
                        $installment_after_support[] = $calc['programs']['flexibleProgram']['installment_after_support'];
                        $personal_installment[] = $calc['programs']['flexibleProgram']['personal_installment'];
                        $profit[] = $calc['programs']['flexibleProgram']['profit'];
                        $personal_profit[] = $calc['programs']['flexibleProgram']['personal_profit'];
                        $personal_net_loan_total[] = $calc['programs']['flexibleProgram']['personal_net_loan_total'];
                        $bank_code[] = $calc['programs']['flexibleProgram']['bank_code'];
                        $bank_name_ar[] = $calc['programs']['flexibleProgram']['bank_name'];
                        $flexible_loan_total[] = $calc['programs']['flexibleProgram']['flexible_loan_total'];
                        $salary_deduction[] = $calc['programs']['flexibleProgram']['salary_deduction'];
                        $personal_funding_months[] = $calc['programs']['flexibleProgram']['personal_funding_months'];

                        $netLoanTotalMax = max($net_loan_total);
                        $getInstallmentMax = max($getInstallment);
                        $getFundingYearsMax = max($getFundingYears);
                        $getFundingMonthsMax = max($getFundingMonths);
                        $personal_salary_deduction = max($personal_salary_deduction);
                        $installment_after_support = max($installment_after_support);
                        $personal_installment = max($personal_installment);
                        $profit = max($profit);
                        $personal_profit = max($personal_profit);
                        $personal_net_loan_total = max($personal_net_loan_total);
                        $bank_code = max($bank_code);
                        $bank_name_ar = max($bank_name_ar);
                        $flexible_loan_total = max($flexible_loan_total);
                        $salary_deduction = max($salary_deduction);
                        $personal_funding_months = max($personal_funding_months);
                    }
                    $bank_code_id = null;
                    $bankInfo = Helper::getMatchBanksByCode($bank_code);
                    if ($bankInfo != null) {
                        $bankInfo = Helper::getMatchBanksId2($bank_name_ar);
                    }
                    if ($bankInfo != null) {
                        $bank_code_id = $bankInfo->id;
                    }
                    $product_name = Helper::getSpasficProductType($getProductTypeCode);
                    $arrayKeys = [
                        'net_loan_total',
                        'installment',
                        'funding_years',
                        'funding_months',
                        'personal_salary_deduction',
                        'installment_after_support',
                        'personal_installment',
                        'profit',
                        'personal_profit',
                        'personal_net_loan_total',
                        'funding_source',
                        'flexible_loan_total',
                        'salary_deduction',
                        'personal_funding_months',
                        'product_type_code',
                    ];
                    $arrayValues = [
                        $netLoanTotalMax,
                        $getInstallmentMax,
                        $getFundingYearsMax,
                        $getFundingMonthsMax,
                        $personal_salary_deduction,
                        $installment_after_support,
                        $personal_installment,
                        $profit,
                        $personal_profit,
                        $personal_net_loan_total,
                        $bank_code_id,
                        $flexible_loan_total,
                        $salary_deduction,
                        $personal_funding_months,
                        $product_name['name_ar'],
                    ];
                    $combine = array_combine($arrayKeys, $arrayValues);
                }
            }
            if ($combine == []) {
                return null;
            }
            else {
                return $combine;
            }
        }
        else {
            $combine = 0;
            return $combine;
        }
    }

    public static function checkOptionValue($optionName)
    {
        $setting = Setting::where('option_name', $optionName)->get();
        return $setting[0]->option_value;
    }

    public static function checkValidationValue($optionName)
    {
        $setting = RequestConditionSetting::where($optionName, '!=', null)->where($optionName, '!=', '')->first();
        if ($setting) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function getIpAddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        }
        elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        }
        elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    public static function getOtpRequestCount($mobile, $ipAddress)
    {
        //return DB::table('otp_request')->where('ip', $ipAddress)->where('mobile', $mobile)->get()->count();
        return customer::where('mobile', $mobile)->select('otp_resend_count')->first();
    }

    public static function getOtpIpCount($ipAddress)
    {
        return DB::table('otp_request')->where('ip', $ipAddress)->get()->count();
    }

    public static function insertOtpRequest($mobile, $ipAddress): void
    {
        $attributes = [
            'ip'     => $ipAddress,
            'mobile' => $mobile,
            //'created_at' => now('Asia/Riyadh'),
        ];
        $model = \App\Models\OtpRequest::query()->firstOrCreate($attributes);
        if ($model->exists && !$model->wasRecentlyCreated) {
            customer::where('mobile', $mobile)->increment('otp_resend_count');
        }
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

    public static function checkIsRequestAchieveCondition($request)
    {
        $is_acheive = true;
        $salary = $request['salary'];
        $birth_date = $request['birth_date'];
        $birth_hijri = $request['birth_hijri'];
        $work = $request['work'];
        $is_supported = $request['is_supported'];
        $has_property = $request['has_property'];
        $has_joint = $request['has_joint'];
        $has_obligations = $request['has_obligations'];
        $has_distress = $request['has_financial_distress'];
        $owning_property = $request['owning_property'];
        $request_conditions = RequestConditionSetting::get();
        if (count($request_conditions) == 0) {
            return true;
        }
        foreach ($request_conditions as $request_condition) {
            $is_acheive = true;
            $from_birth_date = $request_condition->request_validation_from_birth_date;
            $to_birth_date = $request_condition->request_validation_to_birth_date;
            $from_birth_hijri = $request_condition->request_validation_from_birth_hijri;
            $to_birth_hijri = $request_condition->request_validation_to_birth_hijri;
            $from_salary = $request_condition->request_validation_from_salary;
            $to_salary = $request_condition->request_validation_to_salary;
            $work_setting = $request_condition->request_validation_to_work;
            $support_setting = $request_condition->request_validation_to_support;
            $property_setting = $request_condition->request_validation_to_hasProperty;
            $joint_setting = $request_condition->request_validation_to_hasJoint;
            $obligations_setting = $request_condition->request_validation_to_has_obligations;
            $distress_setting = $request_condition->request_validation_to_has_financial_distress;
            $owning_property_setting = $request_condition->request_validation_to_owningProperty;
            /// start check date of birth
            if ($birth_date && (!empty($from_birth_date) || !empty($to_birth_date))) {
                $is_acheive = Helper::check_data_is_between_rang($birth_date, $from_birth_date, $to_birth_date) && $is_acheive;
            }
            elseif ($birth_hijri && (!empty($from_birth_hijri) || !empty($to_birth_hijri))) { // if has value
                $is_acheive = Helper::check_data_is_between_rang($birth_hijri, $from_birth_hijri, $to_birth_hijri) && $is_acheive;
            }
            else { // if birth hijri is null
                $is_acheive = empty($from_birth_date) && empty($to_birth_date) && empty($from_birth_hijri) && empty($to_birth_hijri) && $is_acheive;
            } // end else
            if ($salary && (!empty($from_salary) || !empty($to_salary))) {
                $is_acheive = Helper::check_salary_between_rang($salary, $from_salary, $to_salary) && $is_acheive;
            }
            else {
                $is_acheive = empty($from_salary) && empty($to_salary) && $is_acheive;
            }
            if ($work && (!empty($work_setting))) {
                $is_acheive = Helper::check_work($work, $work_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($work_setting) && $is_acheive;
            }
            if ($is_supported && (!empty($support_setting))) {
                $is_acheive = Helper::check_support($is_supported, $support_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($support_setting) && $is_acheive;
            }
            if ($has_property && (!empty($property_setting))) {
                $is_acheive = Helper::check_property($has_property, $property_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($property_setting) && $is_acheive;
            }
            if ($has_joint && (!empty($joint_setting))) {
                $is_acheive = Helper::check_joint($has_joint, $joint_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($joint_setting) && $is_acheive;
            }
            if ($has_obligations && (!empty($obligations_setting))) {
                $is_acheive = Helper::check_obligations($has_obligations, $obligations_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($obligations_setting) && $is_acheive;
            }
            if ($has_distress && (!empty($distress_setting))) {
                $is_acheive = Helper::check_distress($has_distress, $distress_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($distress_setting) && $is_acheive;
            }
            if ($owning_property && (!empty($owning_property_setting))) {
                $is_acheive = Helper::owning_property($owning_property, $owning_property_setting) && $is_acheive;
            }
            else {
                $is_acheive = empty($owning_property_setting) && $is_acheive;
            }
            if ($is_acheive) {
                return $is_acheive;
            }
        }
        return $is_acheive;
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
            elseif ($to_date) { ///start if statement from_birth_date
                $is_acheive = $is_date2_less_or_equal_date1 && !$is_acheive;
            } /// end if statement from_birth_date
            elseif ($from_date) { ///start if statement from_birth_date
                $is_acheive = $is_date2_greater_or_equal_date1 && !$is_acheive;
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
            elseif ($from_salary) {
                $is_acheive = doubleval($salary) >= doubleval($from_salary) && !$is_acheive;
            }
            else {
                $is_acheive = doubleval($salary) <= doubleval($to_salary) && !$is_acheive;
            }
        }
        elseif (!empty($from_salary) || !empty($to_salary)) {
            $is_acheive = false;
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
    }

    public static function addNewNotify($reqID, $userID)
    {
        return Notification::create([
            'value'      => 'طلب جديد تم إضافته لسلتك',
            'recived_id' => $userID,
            'created_at' => (Carbon::now('Asia/Riyadh')),
            'type'       => 0,
            'req_id'     => $reqID,
        ]);
    }

    public static function addNewReordWebsite($reqID, $userID)
    {
        return requestHistory::create([ // add to request history
                                        'title'        => 'إضافة الطلب من الموقع الإلكتروني',
                                        'user_id'      => null,
                                        'recive_id'    => $userID,
                                        'history_date' => (Carbon::now('Asia/Riyadh')),
                                        'req_id'       => $reqID,
        ]);
    }

    //**********************************************************************
    // Hasbah Code Hegazy
    //**********************************************************************
    public static function getHasbahFundingCalculatorWithoutMilitaryRank($getAge, $salary, $getWork, $getProductTypeCode)
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'age'             => $getAge,
                'salary'          => $salary,
                'job_position_id' => $getWork,
                'product_type_id' => $getProductTypeCode,
            ],
        ]);
        $response = json_decode($response->getBody(), true);
        if ($response['responseData'] != []) {
            foreach ($response['responseData'] as $calc) {
                if (isset($calc['programs']['flexibleProgram'])) {
                    for ($i = 0; $i < count($calc['programs']['flexibleProgram']); $i++) {
                        $personal_salary_deduction = [];
                        $installment_after_support = [];
                        $personal_installment = [];
                        $profit = [];
                        $personal_profit = [];
                        $personal_net_loan_total = [];
                        $bank_code = [];
                        $bank_name_ar = [];
                        $flexible_loan_total = [];
                        $salary_deduction = [];
                        $personal_funding_months = [];
                        $first_batch = [];

                        $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                        $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                        $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                        $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                        $personal_salary_deduction[] = $calc['programs']['flexibleProgram']['personal_salary_deduction'];
                        $installment_after_support[] = $calc['programs']['flexibleProgram']['installment_after_support'];
                        $personal_installment[] = $calc['programs']['flexibleProgram']['personal_installment'];
                        $profit[] = $calc['programs']['flexibleProgram']['profit'];
                        $personal_profit[] = $calc['programs']['flexibleProgram']['personal_profit'];
                        $personal_net_loan_total[] = $calc['programs']['flexibleProgram']['personal_net_loan_total'];
                        $bank_code[] = $calc['programs']['flexibleProgram']['bank_code'];
                        $bank_name_ar[] = $calc['programs']['flexibleProgram']['bank_name'];
                        $flexible_loan_total[] = $calc['programs']['flexibleProgram']['flexible_loan_total'];
                        $salary_deduction[] = $calc['programs']['flexibleProgram']['salary_deduction'];
                        $personal_funding_months[] = $calc['programs']['flexibleProgram']['personal_funding_months'];
                        $first_batch[] = $calc['programs']['flexibleProgram']['first_batch'];

                        $getFirstBatchMax = max($first_batch);
                        $netLoanTotalMax = max($net_loan_total);
                        $getInstallmentMax = max($getInstallment);
                        $getFundingYearsMax = max($getFundingYears);
                        $getFundingMonthsMax = max($getFundingMonths);
                        $personal_salary_deduction = max($personal_salary_deduction);
                        $installment_after_support = max($installment_after_support);
                        $personal_installment = max($personal_installment);
                        $profit = max($profit);
                        $personal_profit = max($personal_profit);
                        $personal_net_loan_total = max($personal_net_loan_total);
                        $bank_code = max($bank_code);
                        $bank_name_ar = max($bank_name_ar);
                        $flexible_loan_total = max($flexible_loan_total);
                        $salary_deduction = max($salary_deduction);
                        $personal_funding_months = max($personal_funding_months);
                    }
                    $bank_code_id = null;
                    $bankInfo = Helper::getMatchBanksByCode($bank_code);
                    if ($bankInfo != null) {
                        $bankInfo = Helper::getMatchBanksId2($bank_name_ar);
                    }
                    if ($bankInfo != null) {
                        $bank_code_id = $bankInfo->id;
                    }
                    $product_name = Helper::getSpasficProductType($getProductTypeCode);
                    $arrayKeys = [
                        'net_loan_total',
                        'installment',
                        'first_batch',
                        'funding_years',
                        'funding_months',
                        'personal_salary_deduction',
                        'installment_after_support',
                        'personal_installment',
                        'profit',
                        'personal_profit',
                        'personal_net_loan_total',
                        'funding_source',
                        'flexible_loan_total',
                        'salary_deduction',
                        'personal_funding_months',
                        'product_type_code',
                    ];
                    $arrayValues = [
                        $netLoanTotalMax,
                        $getInstallmentMax,
                        $getFirstBatchMax,
                        $getFundingYearsMax,
                        $getFundingMonthsMax,
                        $personal_salary_deduction,
                        $installment_after_support,
                        $personal_installment,
                        $profit,
                        $personal_profit,
                        $personal_net_loan_total,
                        $bank_code_id,
                        $flexible_loan_total,
                        $salary_deduction,
                        $personal_funding_months,
                        $product_name['name_ar'],
                    ];
                    $combine = array_combine($arrayKeys, $arrayValues);
                }
            }
            if ($combine == []) {
                return null;
            }
            else {
                return $combine;
            }
        }
        else {
            $combine = 0;
            return $combine;
        }
    }

    public static function getHasbahFundingCalculatorWithMilitaryRank($getAge, $salary, $getMatchJobPosition, $getProductTypeCode)
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        $response = $client->post($url, [
            'headers'     => ['Accept' => "application/json", 'Secret' => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata", 'S-Client' => "alwsata.com.sa",],
            'form_params' => ['age' => $getAge, 'salary' => $salary, 'job_position_id' => $getMatchJobPosition, 'product_type_id' => $getProductTypeCode,],
        ]);
        $response = json_decode($response->getBody(), true);
        if ($response['responseData'] != []) {
            foreach ($response['responseData'] as $calc) {
                if (isset($calc['programs']['flexibleProgram'])) {
                    $count = count($calc['programs']['flexibleProgram']);
                    for ($i = 0; $i < $count; $i++) {
                        $personal_salary_deduction = [];
                        $installment_after_support = [];
                        $personal_installment = [];
                        $profit = [];
                        $personal_profit = [];
                        $personal_net_loan_total = [];
                        $bank_code = [];
                        $bank_name_ar = [];
                        $flexible_loan_total = [];
                        $salary_deduction = [];
                        $personal_funding_months = [];
                        $first_batch = [];

                        $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                        $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                        $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                        $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                        $personal_salary_deduction[] = $calc['programs']['flexibleProgram']['personal_salary_deduction'];
                        $installment_after_support[] = $calc['programs']['flexibleProgram']['installment_after_support'];
                        $personal_installment[] = $calc['programs']['flexibleProgram']['personal_installment'];
                        $profit[] = $calc['programs']['flexibleProgram']['profit'];
                        $personal_profit[] = $calc['programs']['flexibleProgram']['personal_profit'];
                        $personal_net_loan_total[] = $calc['programs']['flexibleProgram']['personal_net_loan_total'];
                        $bank_code[] = $calc['programs']['flexibleProgram']['bank_code'];
                        $bank_name_ar[] = $calc['programs']['flexibleProgram']['bank_name'];
                        $flexible_loan_total[] = $calc['programs']['flexibleProgram']['flexible_loan_total'];
                        $salary_deduction[] = $calc['programs']['flexibleProgram']['salary_deduction'];
                        $personal_funding_months[] = $calc['programs']['flexibleProgram']['personal_funding_months'];
                        $first_batch[] = $calc['programs']['flexibleProgram']['first_batch'];

                        $netLoanTotalMax = max($net_loan_total);
                        $getInstallmentMax = max($getInstallment);
                        $getFundingYearsMax = max($getFundingYears);
                        $getFirstBatchMax = max($first_batch);
                        $getFundingMonthsMax = max($getFundingMonths);
                        $personal_salary_deduction = max($personal_salary_deduction);
                        $installment_after_support = max($installment_after_support);
                        $personal_installment = max($personal_installment);
                        $profit = max($profit);
                        $personal_profit = max($personal_profit);
                        $personal_net_loan_total = max($personal_net_loan_total);
                        $bank_code = max($bank_code);
                        $bank_name_ar = max($bank_name_ar);
                        $flexible_loan_total = max($flexible_loan_total);
                        $salary_deduction = max($salary_deduction);
                        $personal_funding_months = max($personal_funding_months);
                    }
                    $bank_code_id = null;
                    $bankInfo = Helper::getMatchBanksByCode($bank_code);
                    if ($bankInfo != null) {
                        $bankInfo = Helper::getMatchBanksId2($bank_name_ar);
                    }
                    if ($bankInfo != null) {
                        $bank_code_id = $bankInfo->id;
                    }
                    $product_name = Helper::getSpasficProductType($getProductTypeCode);
                    $arrayKeys = [
                        'net_loan_total',
                        'installment',
                        'first_batch',
                        'funding_years',
                        'funding_months',
                        'personal_salary_deduction',
                        'installment_after_support',
                        'personal_installment',
                        'profit',
                        'personal_profit',
                        'personal_net_loan_total',
                        'funding_source',
                        'flexible_loan_total',
                        'salary_deduction',
                        'personal_funding_months',
                        'product_type_code',
                    ];
                    $arrayValues = [
                        $netLoanTotalMax,
                        $getInstallmentMax,
                        $getFirstBatchMax,
                        $getFundingYearsMax,
                        $getFundingMonthsMax,
                        $personal_salary_deduction,
                        $installment_after_support,
                        $personal_installment,
                        $profit,
                        $personal_profit,
                        $personal_net_loan_total,
                        $bank_code_id,
                        $flexible_loan_total,
                        $salary_deduction,
                        $personal_funding_months,
                        $product_name['name_ar'],
                    ];
                    $combine = array_combine($arrayKeys, $arrayValues);
                }
            }
            if ($combine == []) {
                return null;
            }
            else {
                return $combine;
            }
        }
        else {
            $combine = 0;
            return $combine;
        }

    }

    //  ========================= Start Agent Helpers Author (Mahmoud Ahmed) ============== //
    public static function checkDailyPerformance($agentId)
    {
        $checkRecordExist = DB::table('daily_performances')->where('user_id', $agentId)->where('today_date', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->first();
        if (!$checkRecordExist) {
            $addNewRecord = DB::table('daily_performances')->insert(['user_id' => $agentId, 'today_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d')]);
        }
        $updateIncrementRecord = DB::table('daily_performances')->where('user_id', $agentId)->where('today_date', Carbon::today('Asia/Riyadh')->format('Y-m-d'))->update(['received_basket' => DB::raw('received_basket + 1'), 'total_recived_request' => DB::raw('total_recived_request + 1')]);
        return true;
    }

    public static function createHistoryRecord($requestId, $agentId)
    {
        DB::table('request_histories')->insert([ // add to request history
                                                 'title'          => "تم إنشاء الطلب",
                                                 'user_id'        => $agentId,
                                                 'recive_id'      => null,
                                                 'history_date'   => (Carbon::now('Asia/Riyadh')),
                                                 'content'        => null,
                                                 'req_id'         => $requestId,
                                                 'user_switch_id' => null,
        ]);
        return true;
    }

    public static function resubmitCustomerReqTime($req_time)
    {
        $fields = DB::table('settings')->where('option_name', 'LIKE', 'request_resubmit_days')->first()->option_value;
        $reqDate = new Carbon($req_time);
        $now = Carbon::now();
        $difference = ($reqDate->diff($now)->days > $fields);
        return $difference;
    }

    public static function getAllActiveGM()
    {
        return User::where('role', 4)->where('status', 1)->get();
    }

    public static function checkDublicateNotification($recived_id, $value, $req_id)
    {
        $notify = Notification::where('recived_id', $recived_id)->where('value', $value)->where('req_id', $req_id)->whereDate('created_at', Carbon::now()->toDateString())->first();
        if (empty($notify)) {
            return true;
        }
        return false;
    }
    //  ========================= End Agent Helpers Author (Mahmoud Ahmed) ============== //
}

