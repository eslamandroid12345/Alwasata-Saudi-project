<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BankDelegateMiddleware
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
        if (!auth()->check() || auth()->user()->role != 13) {
            die(__("messages.noPermissions"));
        }
        return $next($request);
    }
}
