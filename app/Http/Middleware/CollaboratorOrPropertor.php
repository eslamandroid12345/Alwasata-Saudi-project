<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CollaboratorOrPropertor
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
        if (!in_array(auth()->user()->role, ['6', '7', '10'])) {
            die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
        }
        return $next($request);
    }
}
