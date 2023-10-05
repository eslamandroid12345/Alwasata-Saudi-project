<?php

namespace App\Traits;
trait ApiResponseTrait {
public static function apiResponse($payload = null, $error = null, $code = 200)
{
  $array = [
    'payload' => $payload,
    'status' => $code == 200 ? true : false,
    'error' => $error
  ];
  return response($array,$code);
}
}
