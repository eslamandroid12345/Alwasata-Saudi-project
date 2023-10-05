<?php
return [
    'enabled'  => (bool) env('ENABLE_SMS', false),
    'username' => (string) env('USERNAME_SMS'),
    'password' => (string) env('PASSWORD_SMS'),
    'sender'   => (string) env('SENDER_SMS'),
    'url'      => (string) env('URL_SMS'),
];
