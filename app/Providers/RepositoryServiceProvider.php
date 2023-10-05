<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register Interface and Repository in Here
        // You Must place Interface in first place
        // if you dont, the Repository will not get read .
                 // === Start Register GuestCustomer === //
        $this->app->bind(
            'App\Interfaces\Customer\GuestCustomerInterface',
            'App\Repositories\Customer\GuestCustomerRepository'
        );
                // === End Register AuthCustomer === //
        // ========================================================= //
              // === Start Register Calculator === //
        $this->app->bind(
            'App\Interfaces\Customer\CalculatorInterface',
            'App\Repositories\Customer\CalculatorRepository'
        );
        // ========================================================= //
                // === Start Register Ask For Funding === //
        $this->app->bind(
            'App\Interfaces\Customer\AskForFundingInterface',
            'App\Repositories\Customer\AskForFundingRepository'
        );



    }
}
