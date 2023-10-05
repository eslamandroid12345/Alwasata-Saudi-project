<?php

namespace App\Http\Controllers;

use App\Notifications\PushGrgateHello;
use App\Notifications\SendNotification;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PushController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store the PushSubscription.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        if (!session('existing_user_id')) {
            $this->validate($request, [
                'endpoint'    => 'required',
                'keys.auth'   => 'required',
                'keys.p256dh' => 'required',
            ]);
            $endpoint = $request->endpoint;
            $token = $request->keys['auth'];
            $key = $request->keys['p256dh'];
            $user = Auth::user();
            $user->updatePushSubscription($endpoint, $key, $token);

            return response()->json(['success' => true], 200);
        }
    }

    public function push()
    {
        Notification::send(User::all(), new SendNotification('Hello Afnan', 'im programmer', 'transaction_page', 'https://alwsata.com.sa/agent/fundingreqpage/23180'));
        return redirect()->back();
    }

    public function offline()
    {
        return view('vendor/laravelpwa/offline');
    }

}
