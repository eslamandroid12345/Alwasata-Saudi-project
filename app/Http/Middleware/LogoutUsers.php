<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class LogoutUsers
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
        $user = Auth::user();

        if (Auth::user()) {
            if (!session('existing_user_id')) {
                if ($user->logout == 1) {

                    Auth::logout();

                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
