<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
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
        $locale = config('app.locale');
        if (($user = auth()->user())) {
            $locale = $user->locale;
        }
        elseif (session()->has('locale')) {
            $locale = session('locale');
        }
        elseif (session()->has('language')) {
            $locale = session('language');
        }
        elseif (request()->header('locale')) {
            $locale = request()->header('locale');
        }
        elseif (request()->header('language')) {
            $locale = request()->header('language');
        }
        elseif (request()->has('locale')) {
            $locale = request()->get('locale', config('app.locale'));
        }

        if ($locale) {
            app()->setLocale($locale);
            request()->setLocale($locale);
        }

        //  dd($locale);
        return $next($request);
    }
}
