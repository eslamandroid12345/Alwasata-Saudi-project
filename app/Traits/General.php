<?php

namespace App\Traits;

trait General
{
    public static function getFlexibleSettings(): array
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://appproduct.alwsata.com.sa/api/flexibleSettings';
        $response = $client->get($url, [
            'headers' => [
                'Accept'    => "application/json",
                'x-api-key' => "WFKvB4Vjr8v5BVmxY7CDQ6ZMAU8DSHSBFKCMVpkfd5hhZyKTvnV3uh5XwxSwhnfeUbptZ7Z4XFJCR4vKXeHWGHTZ6djsYXjdAatf",
            ],
        ]);
        $getFlexibleSettings = json_decode($response->getBody(), true);
        return $getFlexibleSettings['payload'];
    }

    public static function getPersonalSettings(): array
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://appproduct.alwsata.com.sa/api/PersonalSettings';
        $response = $client->get($url, [
            'headers' => [
                'Accept'    => "application/json",
                'x-api-key' => "WFKvB4Vjr8v5BVmxY7CDQ6ZMAU8DSHSBFKCMVpkfd5hhZyKTvnV3uh5XwxSwhnfeUbptZ7Z4XFJCR4vKXeHWGHTZ6djsYXjdAatf",
            ],
        ]);
        $getPersonalSettings = json_decode($response->getBody(), true);
        return $getPersonalSettings['payload'];
    }

    public static function getPropertySettings(): array
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://appproduct.alwsata.com.sa/api/PropertySettings';
        $response = $client->get($url, [
            'headers' => [
                'Accept'    => "application/json",
                'x-api-key' => "WFKvB4Vjr8v5BVmxY7CDQ6ZMAU8DSHSBFKCMVpkfd5hhZyKTvnV3uh5XwxSwhnfeUbptZ7Z4XFJCR4vKXeHWGHTZ6djsYXjdAatf",
            ],
        ]);
        $getPropertySettings = json_decode($response->getBody(), true);
        return $getPropertySettings['payload'];
    }

    public static function getExtendedSettings(): array
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://appproduct.alwsata.com.sa/api/ExtendedSettings';
        $response = $client->get($url, [
            'headers' => [
                'Accept'    => "application/json",
                'x-api-key' => "WFKvB4Vjr8v5BVmxY7CDQ6ZMAU8DSHSBFKCMVpkfd5hhZyKTvnV3uh5XwxSwhnfeUbptZ7Z4XFJCR4vKXeHWGHTZ6djsYXjdAatf",
            ],
        ]);
        $getExtendedSettings = json_decode($response->getBody(), true);
        return $getExtendedSettings['payload'];
    }

    public function fcm_send($tokens, $title, $body)
    {
        return sendPushTokenNotification($tokens,$title,$body);
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAA8F3ereU:APA91bESPMz7qdvt5xoMX0cucM_-2iBAoEP0I9SXFr0rKJrQeGcM0Zv9VWKf-eZt5lG7ntx3vkj34bF_oROPCj6b39OS-bPA6a5E6hofWBRpJSfkQEpCD8pojrHMBxVpnc5TocMXZawM';
        //$getToken = $token;
        $tokens = (array) $tokens;
        if (empty($tokens)) {
            return null;
        }
        $data = [
            "registration_ids" => $tokens,
            "notification"     => [
                "title" => $title,
                "body"  => $body,
                "sound" => "default",
            ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key='.$api_key,
            'Content-Type: Application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $result = curl_exec($ch);
        if ($result === false) {
            die('CURL Failed: '.curl_error($ch));
        }
        else {
            curl_close($ch);
            return $result;
            ob_flush();
        }
    }
}
