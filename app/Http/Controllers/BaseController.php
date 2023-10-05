<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class BaseController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param  string  $message
     * @param  null  $urlOrCode
     * @param  int  $code
     *
     * @return JsonResponse|RedirectResponse
     */
    function controller_response(string $message = '', $urlOrCode = null, $code = 200)
    {
        $url = $urlOrCode;
        if (is_numeric($url) || is_bool($url)) {
            $code = $url;
            $url = null;
        }
        if (is_bool($code)) {
            $code = $code ? 200 : 422;
        }
        $success = $code === 200 || $code === true;
        if (request()->expectsJson()) {
            $json = [
                "message" => $message,
            ];
            if (!is_array($url) && $url) {
                $json["url"] = $url;
            }
            elseif (is_array($url)) {
                $json = array_merge($url, $json);
            }
            return response()->json($json, $code);
        }
        //flash($message, $success ? "success" : "danger");
        $redirect = redirect();

        return $success ? (!is_array($url) && !is_null($url) ? $redirect->to($url) : $redirect->back()) : $redirect->back()->withInput();
    }
}
