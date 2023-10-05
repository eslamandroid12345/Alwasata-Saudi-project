<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;

class PropertyShowToGuestCustomer
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
        if (Setting::getByIndex('property_showToGuestCustomer') == 'false') {
            return redirect()->back();
        }
        return $next($request);
    }
}
