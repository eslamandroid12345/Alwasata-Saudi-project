<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V2\ExternalCustomerController;
use App\Http\Controllers\API\Customer\CalculatorController;
use App\Http\Controllers\API\Customer\AsKForFundingController;


Route::group(['middleware' => ['cors', 'json.response', 'checkVisitAPIS']], function () {
    Route::post('customer/GuestCustomer', [AsKForFundingController::class, 'newFundingCustomerWeb']);
    Route::post('customer/AskFundingWeb', [AsKForFundingController::class, 'askFundingWeb']);
    Route::get('customer/AskForFundingHasbah/Fields', [AsKForFundingController::class, 'getFieldsHasbahSetting']);
    Route::post('customer/FundingHasbahCalculator', [CalculatorController::class, 'FundingHasbahCalculator']);
    Route::get('customer/ProductTypes', [CalculatorController::class, 'getAllProductTypes']);
    Route::get('customer/SalarySources', [AsKForFundingController::class, 'getSalarySources']);

    Route::get('customer/WorkSources', [CalculatorController::class, 'getAllWorkSources']);
    Route::get('customer/MilitaryRanks', [CalculatorController::class, 'getAllMilitaryRanks']);
    Route::get('/users-collabarator/{id}', [CalculatorController::class, 'getUsersCollabarators']);

    // Financing Model & check bank employee id
    Route::post('customer/BankRequest', [ExternalCustomerController::class, 'storeBankRequest']);
    Route::get('customer/getUser', [ExternalCustomerController::class, 'getUser']);
    Route::get('customer/AskaryWorks', [CalculatorController::class, 'getAllAskaryWorks']);
    Route::get('customer/MadanyWorks', [CalculatorController::class, 'getAllMadanyWorks']);


    
});