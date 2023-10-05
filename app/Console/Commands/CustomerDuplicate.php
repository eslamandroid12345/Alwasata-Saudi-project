<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerDuplicate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for checking in duplicate customer issue to track the action that cause this kind of issue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $customers = DB::table('customers')
        ->select('id',DB::raw('COUNT(*) as NUM'),'mobile')
        ->groupBy('mobile')
        ->havingRaw('NUM > 1')
        ->get();
        

        foreach($customers as $customer){
            $check_add_track = False;
            # get last customer id
            $customer_obj = DB::table('customers')
            ->where('mobile', $customer->mobile)
            ->latest('id');
            $customer_info = $customer_obj->first();

            # get request id 
            $request_info = DB::table('requests')
            ->where('customer_id', $customer_info->id);
            $request_obj = $request_info->first();

            if (!empty($request_obj)){

                # add track record::
                DB::table('track_duplicate_customers')->insert([
                    'request_id'            =>$request_obj->id,
                    'request_source'        => $request_obj->source,
                    'request_date'          =>$request_obj->created_at,
                    'mobile'                => $customer_info->mobile,
                    'created_at'            => Carbon::now('Asia/Riyadh'),
                ]);
                $check_add_track = True;
                # get real estate
                if ($request_obj->real_id != null){
                    $real_info = DB::table('real_estats')
                    ->where('id', $request_obj->real_id);
                    $real_obj = $real_info->first();
                    if (!empty($real_obj)){
                        $real_info->delete();
                    }
                }
                

                # get funding
                if ($request_obj->fun_id != null){
                    $funding_info = DB::table('fundings')
                    ->where('id', $request_obj->fun_id);
                    $funding_obj = $funding_info->first();
                    if (!empty($funding_obj)){
                        $funding_info->delete();
                    }
                }
                

                # get joint
                if ($request_obj->joint_id != null){
                    $joint_info = DB::table('joints')
                    ->where('id', $request_obj->joint_id);
                    $joint_obj = $joint_info->first();
                    if (!empty($joint_obj)){
                        $joint_info->delete();
                    }
                }
                

                 # get payment
                 if ($request_obj->payment_id != null){
                    $payment_info = DB::table('prepayments')
                    ->where('id', $request_obj->payment_id);
                    $payment_obj = $payment_info->first();
                    if (!empty($payment_obj)){
                        $payment_info->delete();
                    }
                }
                 

                 # get notification
                 $notification_info = DB::table('notifications')
                 ->where('req_id', $request_obj->id)
                 ->where('recived_id', $request_obj->user_id);
                 $notification_obj = $notification_info->first();
                 if (!empty($notification_obj)){
                    $notification_info->delete();
                }

                # delete request
                $request_info->delete();
            }

            if (!$check_add_track){
                 # add track record::
                 DB::table('track_duplicate_customers')->insert([
                    'mobile'                => $customer_info->mobile,
                    'created_at'            => Carbon::now('Asia/Riyadh'),
                ]);
            }
            #delete customer 
            $customer_obj->delete();
        }
    }
}
