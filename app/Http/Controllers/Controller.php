<?php

namespace App\Http\Controllers;

class Controller extends BaseController
{
    public static function successResponse($statusCode, $status, $message, $payloadData)
    {
        return response()->json([
            'code'    => $statusCode,
            'status'  => $status,
            'message' => $message,
            'payload' => $payloadData,
        ], $statusCode);
    }

    public static function errorResponse($statusCode, $status, $message, $payloadData)
    {
        return response()->json([
            'code'    => $statusCode,
            'status'  => $status,
            'message' => $message,
            'payload' => $payloadData,
        ], $statusCode);
    }

    public function responseWithPagination($paginator, $items, $page)
    {
        if ($paginator->url($page)) {
            $data = [
                'code'    => 200,
                'status'  => true,
                'message' => null,
                'payload' => $items,
            ];
            return response()->json($data, 200);
        }

    }

}
