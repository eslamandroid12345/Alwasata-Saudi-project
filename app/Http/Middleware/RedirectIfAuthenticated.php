<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//to take date

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            if (Auth::guard('customer')->check()) {
                return redirect('/customer');
            }

            if (auth()->user()->role == 0) {
                return redirect('agent/myreqs');
            }
            elseif (auth()->user()->role == 1) {
                return redirect('salesManager/myreqs');
            }
            elseif (auth()->user()->role == 2) {
                return redirect('fundingManager/myreqs');
            }
            elseif (auth()->user()->role == 3) { // type
                return redirect('mortgageManager/myreqs');
            }
            elseif (auth()->user()->role == 4) {
                return redirect('generalmanager/myreqs');
            }
            elseif (auth()->user()->role == 5) {
                return redirect()->route('quality.manager.myRequests');
            }
            elseif (auth()->user()->role == 9) {
                return redirect()->route('quality.manager.myRequests');
            }
            elseif (auth()->user()->role == 6) {
                return redirect()->route('proper.requests');
            }
            elseif (auth()->user()->role == 7) {
                return redirect()->route('admin.users');
            }
            elseif (auth()->user()->role == 12) {
                return redirect()->route('HumanResource.users.index');
            }
            else {
                //dd(2);
                return redirect()->route('homePage');
                //die('fn');
            }
            //return redirect('/home');
        }

        return $next($request);
    }
}
