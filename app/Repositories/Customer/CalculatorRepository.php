<?php

namespace App\Repositories\Customer;

use App\askary_work;
use App\HelperFunctions\Helper;
use App\Http\Requests\Customer\FundingCalculatorRequest;
use App\Interfaces\Customer\CalculatorInterface;
use App\madany_work;
use App\military_ranks;
use App\Traits\ResponseAPI;
use App\WorkSource;
use DB;

class CalculatorRepository implements CalculatorInterface
{
    use ResponseAPI;

    public function getAllAskaryWorks()
    {
        try {
            $getAllAskaryWorks = askary_work::all();
            return $this->success(" ", $getAllAskaryWorks);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }

    public function getAllMadanyWorks()
    {
        try {
            $getAllMadanyWorks = madany_work::all();
            return $this->success(" ", $getAllMadanyWorks);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }

    public function getAllWorkSources()
    {
        try {
            $getAllWorkSources = WorkSource::all();
            return $this->success(" ", $getAllWorkSources);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }

    public function getAllMilitaryRanks()
    {
        try {
            $getAllMilitaryRanks = military_ranks::all();
            return $this->success(" ", $getAllMilitaryRanks);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }

    public function getUsersCollabarators($collabaratorId)
    {
        $getCollabaratorRanks = DB::table('user_collaborators')
            ->where('collaborato_id', $collabaratorId)
            ->pluck('user_id')
            ->toArray();
        if (count($getCollabaratorRanks) == 0) //no agents
        {
            $getCollabaratorRanks = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        if (count($getCollabaratorRanks) <= 0) {
            return $this->error(" ", "لا توجد اي نتائج");
        }
        else {
            return $this->success(" ", $getCollabaratorRanks);
        }
    }

    public function FundingCalculator(FundingCalculatorRequest $request)
    {
        try {
            $getAge = Helper::calculateHijriAge($request->birth_date_hijri);
            $getWork = Helper::getMatchWork($request->work_source);
            $getProductTypeCode = Helper::getMatchProductTypeCode($request->product_type_id);

            if ($request->work_source === 'عسكري') {
                $militaryRankInfo = Helper::getMilitaryRankInfo($request->military_rank);
                $getMatchJobPosition = Helper::getMatchJobPosition($militaryRankInfo->value);
                $getFundingCalculatorWithMilitaryRank = Helper::getFundingCalculatorWithMilitaryRank($getAge, $request->salary, $getMatchJobPosition, $getProductTypeCode);
                if ($getFundingCalculatorWithMilitaryRank == 0) {
                    return $this->error("لا توجد نتائج مطابقة للمدخلات!");
                }
                else {
                    return $this->success(" ", $getFundingCalculatorWithMilitaryRank);
                }
            }
            else {
                $getFundingCalculatorWithoutMilitaryRank = Helper::getFundingCalculatorWithoutMilitaryRank($getAge, $request->salary, $getWork, $getProductTypeCode);
                if ($getFundingCalculatorWithoutMilitaryRank == 0) {
                    return $this->error("لا توجد نتائج مطابقة للمدخلات!");
                }
                else {
                    return $this->success(" ", $getFundingCalculatorWithoutMilitaryRank);
                }
            }
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getAllProductTypes()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType?itemsPerPage=-1';
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $productType = json_decode($response->getBody(), true);
            $productTypes = $productType['data'];
            return $this->success(" ", $productTypes);
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!", 500);
        }
    }

    //**********************************************************************
    // Hasbah Code Hegazy
    //**********************************************************************
    public function FundingHasbahCalculator(FundingCalculatorRequest $request)
    {
        try {
            $getAge = Helper::calculateHijriAge($request->birth_date_hijri);
            $getWork = Helper::getMatchWork($request->work_source);
            $getProductTypeCode = Helper::getMatchProductTypeCode($request->product_type_id);

            if ($request->work_source == 'عسكري') {
                $militaryRankInfo = $request->military_rank;
                $getMatchJobPosition = Helper::getMatchJobPosition($request->military_rank);
                $getFundingCalculatorWithMilitaryRank = Helper::getHasbahFundingCalculatorWithMilitaryRank($getAge, $request->salary, $getMatchJobPosition, $getProductTypeCode);
                if ($getFundingCalculatorWithMilitaryRank == 0) {
                    return $this->error("لا توجد نتائج مطابقة للمدخلات!");
                }
                else {
                    return $this->success(" ", $getFundingCalculatorWithMilitaryRank);
                }
            }
            else {
                $getFundingCalculatorWithoutMilitaryRank = Helper::getHasbahFundingCalculatorWithoutMilitaryRank($getAge, $request->salary, $getWork, $getProductTypeCode);
                if ($getFundingCalculatorWithoutMilitaryRank == 0) {
                    return $this->error("لا توجد نتائج مطابقة للمدخلات!");
                }
                else {
                    return $this->success(" ", $getFundingCalculatorWithoutMilitaryRank);
                }
            }
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
