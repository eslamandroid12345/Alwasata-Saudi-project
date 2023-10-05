<?php

namespace App\Console\Commands\Setup;

use App\Models\Classification;
use App\Models\ClassificationAlertSetting as Model;
use Illuminate\Console\Command;

class ClassificationAlertSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:classification-alert-setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set init data of classification alert setting';

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
        // Class: تعذر الاتصال
        $unableToConnect = Classification::find(33);
        $alertSettings = [
            [
                'step'          => 1,
                'hours_to_send' => 24,
                'type'          => Model::TYPES['push_token'],
            ],
            [
                'step'          => 2,
                'hours_to_send' => 24,
                'type'          => Model::TYPES['email'],
            ],
            [
                'step'          => 3,
                'hours_to_send' => 24,
                'type'          => Model::TYPES['sms'],
            ],
        ];

        foreach ($alertSettings as $value) {
            $insert = $unableToConnect->classificationAlertSettings()->create($value);
            $this->line("Inserted unable To Connect {$insert->id}");
        }
    }
}
