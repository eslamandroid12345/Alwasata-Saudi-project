<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Slack configuration
    |--------------------------------------------------------------------------
    |
    */
    'slack'       => [
        'url'                    => (string) env('SLACK_URL'),
        'username'               => (string) env('SLACK_APP_USERNAME'),
        'notifications_channel'  => (string) env('SLACK_NOTIFICATIONS_CHANNEL'),
        'classification_channel' => 'https://hooks.slack.com/services/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',
    ],

    /** App date formats */
    "date_format" => [
        'date'             => "Y-m-d",
        'datetime'         => "Y-m-d g:i a",
        'day'              => "l",
        'hijri_date'       => "Y/m/d",
        'time'             => "H:i",
        'time_string'      => "g:i a",
        'time_12'          => "g:i",
        'full'             => "Y-m-d H:i:s",
        'full_12'          => "Y-m-d g:i a",
        'human'            => "Y-m-d g:i a",
        'human_full'       => "l Y-m-d g:i a",
        'hijri_human'      => "Y/m/d g:i a",
        'hijri_human_full' => "l Y/m/d g:i a",
        'log'              => 'Y-m-d',
    ],
];
