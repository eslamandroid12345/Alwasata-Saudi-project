<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mobileRule();
    }

    /**
     * Mobile
     */
    protected function mobileRule()
    {
        $ruleName = 'mobile';
        Validator::extend($ruleName, function ($attribute, $value, $parameters, $validator) {
            $value = $value ?: '';
            $cases = [
                '966',
                '+966',
                '00966',
            ];
            foreach ($cases as $case) {
                if (Str::startsWith($value, $case)) {
                    $value = Str::after($value, $case);
                    break;
                }
            }
            // return strlen($value) == 9;
            $value = (int) $value;
            return Str::startsWith($value, "5") && strlen($value) === 9;
        });
    }
}
