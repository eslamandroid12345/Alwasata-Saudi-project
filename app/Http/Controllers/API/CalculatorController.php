<?php

namespace App\Http\Controllers\API;

use App\askary_work;
use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculationRequest;
use App\madany_work;
use App\Models\AskaryWork;
use App\WorkSource;
use Datetime;
use GeniusTS\HijriDate\Date;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class CalculatorController extends Controller
{

    public function getAllAskaryWorks()
    {
        $getAllAskaryWorks = askary_work::all();
        if ($getAllAskaryWorks->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getAllAskaryWorks);
        }
    }

    public function getAllMadanyWorks()
    {
        $getAllMadanyWorks = madany_work::all();
        if ($getAllMadanyWorks->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getAllMadanyWorks);
        }
    }

    public function getAllWorkSources()
    {
        $getAllWorkSources = WorkSource::all();
        if ($getAllWorkSources->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getAllWorkSources);
        }
    }

    public function getMilitaryRanks()
    {
        $getMilitaryRanks = DB::table('military_ranks')->select('id', 'value')->get();
        if ($getMilitaryRanks->count() <= 0) {
            return self::errorResponse(422, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, true, null, $getMilitaryRanks);
        }
    }

    # GET ADDED AGENTS TO SPASFIC COLLOBERATOR
    public function getUsersCollabarator($collabaratorId)
    {
        $getCollabaratorRanks = DB::table('user_collaborators')
            ->where('collaborato_id', $collabaratorId)
            ->pluck('user_id')
            ->toArray();
        if (count($getCollabaratorRanks) <= 0) {
            return self::errorResponse(200, false, "لا توجد اي نتائج", null);
        }
        else {
            return self::successResponse(200, false, null, $getCollabaratorRanks);
        }
    }

    public function getProductTypes()
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
        $productTypes = json_decode($response->getBody(), true);
        $productType = $productTypes['data'];
        return self::successResponse(200, true, null, $productType);
    }

    public function checkIfNull($key, $array)
    {
        return array_key_exists($key, $array);
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

    public function calculation(CalculationRequest $request)
    {
        $getAge = $this->calculateHijriAge($request->birth_hijri);
        $getWork = $this->getMatchWork($request->work_caculater);
        $getProductTypeCode = $this->getMatchProductTypeCode($request->product_type_id);
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/calculation';
        if ($request->has('work_caculater')) {
            if ($request->work_caculater == 1) {
                if ($request->has('military_rank_caculater')) {
                    if ($request->military_rank_caculater != '' && $request->military_rank_caculater != null) {
                        $rankInfo = MyHelpers::getMiliratyRankInfo($request->military_rank_caculater);
                        if ($rankInfo != false) {
                            $matchRank = $this->getMatchRank($rankInfo->value);
                            $response = $client->post($url, [
                                'headers'     => [
                                    'Accept'   => "application/json",
                                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                                    'S-Client' => "alwsata.com.sa",
                                ],
                                'form_params' => [
                                    'age'             => $getAge,
                                    'salary'          => $request->salary,
                                    'job_position_id' => $matchRank,
                                    'product_type_id' => $getProductTypeCode,
                                ],
                            ]);
                            $response = json_decode($response->getBody(), true);
                            $calculaterResults = $response['responseData'];
                            $combine = [];
                            foreach ($calculaterResults as $calc) {
                                if (isset($calc['programs']['flexibleProgram'])) {
                                    for ($i = 0; $i < count($calc['programs']['flexibleProgram']); $i++) {
                                        $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                                        $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                                        $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                                        $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                                        $netLoanTotalMax = max($net_loan_total);
                                        $getInstallmentMax = max($getInstallment);
                                        $getFundingYearsMax = max($getFundingYears);
                                        $getFundingMonthsMax = max($getFundingMonths);
                                    }
                                    $arrayKeys = ['net_loan_total', 'installment', 'funding_years', 'funding_months'];
                                    $arrayValues = [$netLoanTotalMax, $getInstallmentMax, $getFundingYearsMax, $getFundingMonthsMax];
                                    $combine = array_combine($arrayKeys, $arrayValues);
                                }
                            }
                            if ($combine == []) {
                                return self::successResponse(200, true, "لا توجد بيانات مطابقة للمدخلات", null);
                            }
                            else {
                                return self::successResponse(200, true, null, $combine);
                            }

                        }
                    }
                    else {
                        return self::errorResponse(422, false, "رتبة العسكري غير موجودة لدينا, يرجي ادخال قيمة صالحة", null);
                    }
                }
            }
            else {
                $response = $client->post($url, [
                    'headers'     => [
                        'Accept'   => "application/json",
                        'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                        'S-Client' => "alwsata.com.sa",
                    ],
                    'form_params' => [
                        'age'             => $getAge,
                        'salary'          => $request->salary,
                        'job_position_id' => $getWork,
                        'product_type_id' => $getProductTypeCode,
                    ],
                ]);
                $response = json_decode($response->getBody(), true);
                $calculaterResults = $response['responseData'];
                foreach ($calculaterResults as $calc) {
                    if (isset($calc['programs']['flexibleProgram'])) {
                        for ($i = 0; $i < count($calc['programs']['flexibleProgram']); $i++) {
                            $net_loan_total[] = $calc['programs']['flexibleProgram']['net_loan_total'];
                            $getInstallment[] = $calc['programs']['flexibleProgram']['installment'];
                            $getFundingYears[] = $calc['programs']['flexibleProgram']['funding_years'];
                            $getFundingMonths[] = $calc['programs']['flexibleProgram']['funding_months'];
                            $netLoanTotalMax = max($net_loan_total);
                            $getInstallmentMax = max($getInstallment);
                            $getFundingYearsMax = max($getFundingYears);
                            $getFundingMonthsMax = max($getFundingMonths);
                        }
                        $arrayKeys = ['net_loan_total', 'installment', 'funding_years', 'funding_months'];
                        $arrayValues = [$netLoanTotalMax, $getInstallmentMax, $getFundingYearsMax, $getFundingMonthsMax];
                        $combine = array_combine($arrayKeys, $arrayValues);
                    }
                }
            }
            return self::successResponse(200, true, null, $combine);
        }
    }

    public function calculateHijriAge($input)
    {
        $now = Date::now();
        $birthdate = new DateTime($input);
        $today = new Datetime($now);
        $diff = $today->diff($birthdate);
        return $diff->y;
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

    public function getMatchProductTypeCode($productTypeId)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$productTypeId;
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
        else {
            return null;
        }
    }
}


