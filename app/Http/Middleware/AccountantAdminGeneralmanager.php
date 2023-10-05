<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccountantAdminGeneralmanager
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
        if (auth()->user()->role != '8' && auth()->user()->role != '7' && auth()->user()->role != '4') {
            die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
        }
        return $next($request);
    }
}
