<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\User;
use Illuminate\Http\Request;

class AdvisorRateStatisticsController extends Controller
{
    public function index()
    {
        $title = "";
        return $query = User::whereHas('customers')
                            ->select('id', 'name')
                            ->withCount(["customers AS rate_0"=>function($q){
                                $q->whereIn('app_rate_starts',['', '0']);
                            }])
                            ->withCount(["customers AS rate_1"=>function($q){
                                $q->where('app_rate_starts','1');
                            }])
                            ->withCount(["customers AS rate_2"=>function($q){
                                $q->where('app_rate_starts','2');
                            }])
                            ->withCount(["customers AS rate_3"=>function($q){
                                $q->where('app_rate_starts','3');
                            }])
                            ->withCount(["customers AS rate_4"=>function($q){
                                $q->where('app_rate_starts','4');
                            }])
                            ->withCount(["customers AS rate_5"=>function($q){
                                $q->where('app_rate_starts','5');
                            }])
                            ->paginate(12);
        // return $query = \DB::table('customers')->select('user_id', 'app_rate_starts')->whereNotNull('user_id')->get()->groupBy(['user_id', 'app_rate_starts']);
    }
}
