<?php

namespace App\Console\Commands\Factory;

use App\Models\ExternalCustomer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeExternalCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factory:external-customer {bankDelegate? : user ID }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Fake external customer';

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
    public function handle(): void
    {
        if (!($user = $this->argument('bankDelegate'))) {
            $user = User::where('role', 13)->first();
        }
        if (!$user) {
            $this->error("No Bank Delegate found");
            return;
        }
        //$user->update([
        //    'bank_id'   => 1,
        //    'username'  => 'bd',
        //    'subdomain' => "ffr",
        //]);
        //dd($user);

        ExternalCustomer::create([
            'user_id' => $user->id,
            'name'    => Str::random("6"),
            'mobile'  => "5".Str::random(8),
        ]);
        $this->line("Created: for user {$user->username}");
    }
}
