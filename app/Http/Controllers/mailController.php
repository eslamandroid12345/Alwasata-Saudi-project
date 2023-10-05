<?php

namespace App\Http\Controllers;

use Mail;

class mailController extends Controller
{
    public function send()
    {
        $to_name = 'afnan';
        $to_email = 'afnan.wsata@gmail.com';
        $data = ['name' => "fnn", "body" => "Test mail"]; // pass verible to blade file

        Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) { // emails.mail : view of mail body
            $message->to($to_email, $to_name)
                ->subject('Artisans Web Testing Mail'); // subject of mail
            $message->from('ahmed@alwsata.com.sa', 'Artisans Web'); //from(email from, name of sender)
        });
    }
}
