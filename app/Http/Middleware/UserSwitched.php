<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserSwitched
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
        if (auth()->user()->role == '7' || $request->session()->get('user_is_switched')) {
            return $next($request);
        }

        die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
    }
}
