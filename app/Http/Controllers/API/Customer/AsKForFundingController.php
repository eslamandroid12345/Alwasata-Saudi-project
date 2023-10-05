<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AskForFundingWeb;
use App\Http\Requests\Customer\NewFundingCustomerWebRequest;
use App\Interfaces\Customer\AskForFundingInterface;

class AsKForFundingController extends Controller
{
    protected $askForFundingInterface;

    public function __construct(AskForFundingInterface $askForFundingInterface)
    {
        $this->askForFundingInterface = $askForFundingInterface;
    }

    public function newFundingCustomerWeb(NewFundingCustomerWebRequest $request)
    {
        return $this->askForFundingInterface->newFundingCustomerWeb($request);
    }

    public function askFundingWeb(AskForFundingWeb $request)
    {
        return $this->askForFundingInterface->askFundingWeb($request);
    }

    public function getSalarySources()
    {
        return $this->askForFundingInterface->getSalarySources();
    }

    public function getFieldsHasbahSetting()
    {
        return $this->askForFundingInterface->getFieldsHasbahSetting();

    }
    //*******************************************************************
    // Task-33
    //*******************************************************************
    public function getMatchedBank($id)
    {
        return $this->askForFundingInterface->getMatchedBank($id);
    }
}
