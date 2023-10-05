<?php

namespace App\Console\Commands;

use App\Models\OtpRequest;
use DB;
use Illuminate\Console\Command;

class RemoveDuplicatedOtpRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        //***********************************************************************************
        // Remove Duplicated Records
        //***********************************************************************************
        $duplicates = DB::table('otp_request')->select('mobile', 'ip', DB::raw('COUNT(*) as `count`'))->groupBy('mobile', 'ip')->havingRaw('COUNT(*) > 1')->get();

        foreach ($duplicates as $dup) {
            // Skip First one
            $dd = OtpRequest::where([
                'mobile' => $dup->mobile,
                'ip'     => $dup->ip,
            ])->first();
            // Remove Others
            OtpRequest::where('id', '<>', $dd->id)->where([
                'mobile' => $dup->mobile,
                'ip'     => $dup->ip,
            ])->delete();
        }

        //***********************************************************************************
        // Remove Records That dosnot belongs To Customers and Requests
        //***********************************************************************************
        $data = DB::table('otp_request')->join('customers', 'customers.mobile', '=', 'otp_request.mobile')->join('requests', 'requests.customer_id', '=', 'customers.id')->pluck('customers.mobile')->toArray();

        DB::table('otp_request')->whereNotIn('mobile', $data)->delete();

    }
}
