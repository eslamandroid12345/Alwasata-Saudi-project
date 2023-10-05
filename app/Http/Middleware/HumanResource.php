<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HumanResource
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$data =null)
    {
        if ($data == 'admin'){
            if (auth()->user()->role != '12' && auth()->user()->role != '7' ) {
                die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
            }
        }else{
            if (auth()->user()->role != '12') {
                die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
            }
        }

        return $next($request);
    }
}
