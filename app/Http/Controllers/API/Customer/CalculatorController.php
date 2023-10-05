<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\FundingCalculatorRequest;
use App\Interfaces\Customer\CalculatorInterface;

class CalculatorController extends Controller
{
    protected $calculatorInterface;

    public function __construct(CalculatorInterface $calculatorInterface)
    {
        $this->calculatorInterface = $calculatorInterface;
    }

    public function getAllWorkSources()
    {
        return $this->calculatorInterface->getAllWorkSources();
    }

    public function getAllMilitaryRanks()
    {
        return $this->calculatorInterface->getAllMilitaryRanks();
    }

    public function getUsersCollabarators($collabaratorId)
    {
        return $this->calculatorInterface->getUsersCollabarators($collabaratorId);
    }

    public function FundingCalculator(FundingCalculatorRequest $request)
    {
        return $this->calculatorInterface->FundingCalculator($request);
    }

    public function getAllProductTypes()
    {
        return $this->calculatorInterface->getAllProductTypes();
    }

    public function getAllAskaryWorks()
    {
        return $this->calculatorInterface->getAllAskaryWorks();
    }

    public function getAllMadanyWorks()
    {
        return $this->calculatorInterface->getAllMadanyWorks();
    }

    //**********************************************************************
    // Hasbah Code Hegazy
    //**********************************************************************
    public function FundingHasbahCalculator(FundingCalculatorRequest $request)
    {
        return $this->calculatorInterface->FundingHasbahCalculator($request);
    }
}
