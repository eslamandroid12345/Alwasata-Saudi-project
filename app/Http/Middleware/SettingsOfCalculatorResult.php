<?php

namespace App\Http\Middleware;

use App\EditCalculationFormulaUser;
use Closure;
use Illuminate\Http\Request;

class SettingsOfCalculatorResult
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((auth()->user()->role != '7') && (EditCalculationFormulaUser::where(['user_id' => auth()->user()->id, 'type' => 1])->count() == 0)) {
            die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
        }
        return $next($request);
    }
}
