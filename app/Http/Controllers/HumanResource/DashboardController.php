<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;

class DashboardController extends Controller
{
    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }
    public function dashboard(){
        return view('HumanResource.dashboard');
    }
}
