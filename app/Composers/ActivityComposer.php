<?php

namespace App\Composers;

use DB;
use File;
use App\Message;
use App\userActivity;
use App\customerActivity;
use App\User;
use Carbon\Carbon;
use Auth;
use MyHelpers;

class ActivityComposer
{

    public function compose($view)
    {


        $users=null;
        $usersArr=[];
        
        if (!Auth::guard('customer')->check()) {
        if (!session('existing_user_id')){

        $userActivity = userActivity::where('user_id',auth()->user()->id)->first();

        if ($userActivity){

            userActivity::where('user_id',auth()->user()->id)->update([
                'last_activity' => time(),
            ]);
        }
        else{

            userActivity::create([
                'user_id' => auth()->user()->id,
                'last_activity' => time(),
                'sesstionID' => session()->getId(),
            ]);
        }



    }}

      


    $onlineUsers=userActivity::select('last_activity','user_id')->get();

    foreach($onlineUsers as $onlineUser){
        if ((($onlineUser->last_activity + (\Config::get('session.lifetime') * 60) - time()) / 60) > 0){
            $usersArr[]=$onlineUser->user_id;
        }
    }


    $users=User::whereIn('id',$usersArr)->get();
 

        //Add your variables
        $view->with([
            'onlineUsers' =>  $users,
            'arrUsers' =>  $usersArr,
        ]);
    }
}
