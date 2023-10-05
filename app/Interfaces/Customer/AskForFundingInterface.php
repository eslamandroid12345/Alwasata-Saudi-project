<?php


namespace App\Interfaces\Customer;


use App\Http\Requests\Customer\AskForFundingWeb;
use App\Http\Requests\Customer\NewFundingCustomerWebRequest;

interface AskForFundingInterface
{
    public function askFundingWeb(AskForFundingWeb $request);
    public function newFundingCustomerWeb(NewFundingCustomerWebRequest $request);
    public function getFieldsHasbahSetting();
    public function getSalarySources();
    public function getMatchedBank($id);
}
