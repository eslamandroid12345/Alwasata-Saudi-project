<?php


namespace App\Interfaces\Customer;


use App\Http\Requests\Customer\FundingCalculatorRequest;

interface CalculatorInterface
{
    public function getAllAskaryWorks();
    public function getAllMadanyWorks();
    public function getAllWorkSources();
    public function getAllMilitaryRanks();
    public function getAllProductTypes();
    public function getUsersCollabarators($collabaratorId);

    public function FundingCalculator(FundingCalculatorRequest $request);
    public function FundingHasbahCalculator(FundingCalculatorRequest $request);


}
