<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use View;

class ChatMiddleware
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
        if (auth()->guard('customer')->check()) {
            View::composers([
                //attaches HomeComposer to pages
                'App\Composers\HomeComposer' => ['layouts.content', 'layouts.customer_app', 'Customer.customerIndexPage', 'Chatting.chat', 'Chatting.new-chat'],
                //                'App\Composers\ActivityComposer'  => ['layouts.content'],
            ]);
        }
        else {
            View::composers([
                //attaches HomeComposer to pages
                'App\Composers\HomeComposer'     => ['layouts.content', 'Chatting.chat', 'Chatting.new-chat'],
                'App\Composers\ActivityComposer' => ['layouts.content'],
            ]);
        }
        return $next($request);
    }
}
