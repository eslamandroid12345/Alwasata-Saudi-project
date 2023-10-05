<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends AppController
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateSetting(Request $request)
    {
        $rules = [
            'schedule_unable_to_communicate' => ['required', 'bool'],
            'postponed_communication' => ['required', 'bool'],
            'schedule_not_answer_to_unable'  => ['required', 'bool'],
            'not_answer_to_unable_days'      => ['required', 'numeric'],
        ];
        setting($request->only(array_keys($rules)))->save();
        return $this->controller_response(__("messages.updated_success"));
    }

    public function updateTransBasketSetting(Request $request)
    {
        $request->merge([
            'active_trans_basket'        => (bool) $request->get('active_trans_basket', !1),
            'trans_basket_per_day'       => (int) $request->get(($k = 'trans_basket_per_day'), setting($k, 0)),
            'trans_basket_request_count' => (int) $request->get(($k = 'trans_basket_request_count'), setting($k, 0)),
        ]);
        $rules = [
            'active_trans_basket'        => ['required', 'bool'],
            'trans_basket_per_day'       => ['required', 'int'],
            'trans_basket_request_count' => ['required', 'int'],
        ];
        $data = $request->only(array_keys($rules));
        setting($data)->save();
        //dd(setting()->all());
        //dd($request->all(), $data);
        return $this->controller_response(__("messages.updated_success"));
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), []);
    }
}
