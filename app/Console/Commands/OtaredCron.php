<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OtaredCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otared:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'retrive data from otared api';

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
        \Log::info("Cron is working fine!");
     
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
      
        $this->info('Demo:Cron Cummand Run successfully!');
    }
}
