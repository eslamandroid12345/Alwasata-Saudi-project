<?php

namespace App\Http\Controllers\Proper;

use App\Http\Controllers\Controller;
use View;

class PropertyAgentController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }

    public function homePage()
    {
        return view('Proper.PropertyAgent.home.home');
    }

}
