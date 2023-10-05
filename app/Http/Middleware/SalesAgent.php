<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SalesAgent
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

        // dd(auth()->user()->role);
        if (auth()->user()->role != '0') {
            die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
        }
        return $next($request);
    }
}
