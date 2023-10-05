<?php

namespace App\Console\Commands\Request;

use App\Models\RequestJob;
use Illuminate\Console\Command;

class RunJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Real all jobs from database of requests';

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
     * @throws \Exception
     */
    public function handle(): void
    {
        RequestJob::runJobs();
    }
}
