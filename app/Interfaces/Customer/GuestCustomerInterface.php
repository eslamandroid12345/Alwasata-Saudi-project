<?php


namespace App\Interfaces\Customer;
//use App\Http\Requests\Customer\CheckResetPasswordCodeRequest;
use App\Http\Requests\Customer\GuestCustomerRequest;
//use App\Http\Requests\Customer\GuestHelpDeskRequest;
//use App\Http\Requests\Customer\ResetPasswordRequest;
//use App\Http\Requests\Customer\SendResetPasswordCodeRequest;

interface GuestCustomerInterface
{
    public function requestCustomerLogin(GuestCustomerRequest $request);
    public function customerLogout();
    //public function sendResetPasswordCode(SendResetPasswordCodeRequest $request);
    //public function checkResetPasswordCode(CheckResetPasswordCodeRequest $request);
    //public function resetPassword(ResetPasswordRequest $request);
    //public function guestHelpDesk(GuestHelpDeskRequest $request);
}
