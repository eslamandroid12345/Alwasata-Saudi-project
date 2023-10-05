<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Console\Commands\Request;

use App\Helpers\MyHelpers;
use App\Models\Customer;
use App\Models\WelcomeMessageSetting;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class WelcomeMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:welcome-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome message to customer if a new request';

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
     * @return void
     */
    public function handle()
    {
        $settings = WelcomeMessageSetting::with(['classifications', 'requestSources'])->get();

        //$a = '2022-02-24 04:00:00';
        $a = now();
        $customers = Customer::query()
            ->whereRaw("DATE_ADD(created_at, INTERVAL 4 HOUR) >= '{$a}'")
            ->where('id', 22);
        //dd($customers->count());
        foreach ($settings as $setting) {
            $classifications = $setting->classifications->pluck('id')->toArray();
            $requestSources = $setting->requestSources->pluck('id')->toArray();
            if (empty($classifications) || empty($requestSources)) {
                continue;
            }
            $date = now()->format(config('config.date_format.full'));
            $customers = Customer::query()
                ->whereHas('request', function (Builder $q) use ($classifications, $requestSources) {
                    !empty($classifications) && $q->whereIn('class_id_agent', $classifications);
                    !empty($requestSources) && $q->whereIn('source', $requestSources);
                    return $q->where('statusReq', 0);
                })
                ->where('welcome_message', 2)
                ->whereRaw("DATE_ADD(created_at, INTERVAL {$setting->time} HOUR) <= '{$date}'")
                ->get();
            //dd($setting->time,$customers->count());
            foreach ($customers as $customer) {
                $message = __('replace.dear', ['name' => $customer->name]).PHP_EOL.$setting->welcome_message;
                MyHelpers::sendSMS($customer->mobile, $message);
                $customer->update(['welcome_message' => 1]);
            }
        }
    }
}
