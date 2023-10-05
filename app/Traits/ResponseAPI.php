<?php


namespace App\Traits;


trait ResponseAPI
{
    /*
    * core of response
    *
    * @param int                 $statusCode
    * @param boolean             $isSuccess
    * @param string              $message
    * @param array|object        $data
    */
    public function coreResponse($message, $statusCode, $data = null, $isSuccess = true)
    {
        // Check the params
        if(!$message)
        {
            return response()->json(['message' => 'Message is required'],500);
        }
        // Send the response
        if($isSuccess)
        {
            return response()->json([
                'code'      => $statusCode,
                'error'     => false,
                'message'   => $message,
                'payload'   => $data
            ], $statusCode);
        }else{
            return response()->json([
                'code'      => $statusCode,
                'error'     => true,
                'message'   => $message,
                'payload'   => $data
            ], $statusCode);
        }
    }

    /*
    * Send any success response
    *
    * @param int                 $statusCode
    * @param boolean             $isSuccess
    * @param string              $message
    * @param array|object        $data
    */
    public function success($message,$data)
    {
        return $this->coreResponse($message,200,$data,true);
    }

    /*
   * Send any error response
   *
   * @param int                 $statusCode
   * @param boolean             $isSuccess
   * @param string              $message
   * @param array|object        $data
   */
    public function error($message, $statusCode = 422)
    {
        return $this->coreResponse($message,$statusCode,null,false);
    }
    /*
       * Send any response has pagination
       *
       * @param array | object        $paginator
       * @param int                   $page
       * @param array|object          $item
       */
    public function responseWithPagination($paginator, $items,$page)
    {
        if($paginator->url($page))
        {
            $data = [
                'code'             => 200,
                'error'            => false,
                'message'          => " ",
                'payload'          => $items
            ];
            return response()->json($data,200);
        }
    }

}
