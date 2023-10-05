<?php

namespace App\Composers;

use DB;
use File;
use App\Message;
use App\customerActivity;
use App\User;
use Carbon\Carbon;
use MyHelpers;

use Auth;

class ActivityCustomerComposer
{

    public function compose($view)
    {


        $customers=[];


    if (Auth::guard('customer')->check()) {
        $customerID=auth()->guard('customer')->user()->id;

    $customerActivity = customerActivity::where('customer_id',$customerID)->first();

    if ($customerActivity){
        customerActivity::where('customer_id',$customerID)->update([
            'last_activity' => time(),
        ]);
    }
    else{

        customerActivity::create([
            'customer_id' => $customerID,
            'last_activity' => time(),
            'sesstionID' => session()->getId(),
        ]);

    }


        
    }

    


    $onlineCustomers=customerActivity::select('last_activity','customer_id')->get();

    foreach($onlineCustomers as $onlineCustomer){
        if ((($onlineCustomer->last_activity + (\Config::get('session.lifetime') * 60) - time()) / 60) > 0){
            $customers[]=$onlineCustomer->customer_id;
        }
    }
    //dd($customers);

        //Add your variables
        $view->with([
            'onlineCustomers' =>  $customers,
  
        ]);
    }
}
