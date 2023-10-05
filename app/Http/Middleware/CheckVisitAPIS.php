<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckVisitAPIS
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
        if ($request->header('x-api-key') != "dAF@E#iR") {
            return response()->json([
                'code'    => 401,
                'status'  => false,
                'message' => 'Unauthenticated To Visit This URL',
                'payload' => null,
            ], 401);
        }
        else {
            return $next($request);
        }
    }
}
