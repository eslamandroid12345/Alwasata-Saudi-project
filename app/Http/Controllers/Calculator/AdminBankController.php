<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MyHelpers;
use View;

class AdminBankController extends Controller
{
    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        $this->middleware('auth');
    }

    public function getAllBanks()
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($response->getBody(), true);
        return view('Admin.Calculator.Banks.index', compact('banks'));
    }

    public function addNewBankPage()
    {
        return view('Admin.Calculator.Banks.add_new_bank');
    }

    public function addNewBankRequest(Request $request)
    {
        $rules = [
            'name_ar'    => 'required',
            'name_en'    => 'required',
            'code'       => 'required',
            'sort_order' => 'nullable|numeric',
        ];
        $customMessages = [
            'name_ar.required'   => 'الإسم بالعربي مطلوب',
            'name_en.required'   => 'الإسم بالإنجليزي مطلوب',
            'code.required'      => 'الكود مطلوب',
            'sort_order.numeric' => 'ترتيب العرض يقبل فقط أرقام',
        ];
        if ($request->property_completed == '') {
            $property_completed = 0;
        }
        else {
            $property_completed = 1;
        }
        if ($request->property_uncompleted == '') {
            $property_uncompleted = 0;
        }
        else {
            $property_uncompleted = 1;
        }
        if ($request->joint == '') {
            $joint = 0;
        }
        else {
            $joint = 1;
        }
        if ($request->quest_check == '') {
            $quest_check = 0;
        }
        else {
            $quest_check = 1;
        }
        if ($request->bear_tax == '') {
            $bear_tax = 0;
        }
        else {
            $bear_tax = 1;
        }
        if ($request->guarantees == '') {
            $guarantees = 0;
        }
        else {
            $guarantees = 1;
        }
        if ($request->shl == '') {
            $shl = 0;
        }
        else {
            $shl = 1;
        }
        if ($request->active == '') {
            $active = 0;
        }
        else {
            $active = 1;
        }
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'name_ar'              => $request->name_ar,
                'name_en'              => $request->name_en,
                'code'                 => $request->code,
                'sort_order'           => $request->sort_order,
                'property_completed'   => $property_completed,
                'property_uncompleted' => $property_uncompleted,
                'joint'                => $joint,
                'quest_check'          => $quest_check,
                'bear_tax'             => $bear_tax,
                'guarantees'           => $guarantees,
                'shl'                  => $shl,
                'active'               => $active,
            ],
        ]);
        if ($response) {
            return redirect()->route('admin.banks')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function removeBank(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->delete($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        if ($response) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }

    public function getBankInfo(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $bank = json_decode($response->getBody(), true);
        if (!empty($bank)) {
            return response()->json(['bank' => $bank['data'], 'status' => 1]);
        }
        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

    public function updateBankInfo(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_ar'    => 'required',
            'name_en'    => 'required',
            'code'       => 'required',
            'sort_order' => 'required|numeric',
        ], [
            'name_ar.required'    => 'الإسم بالعربي مطلوب',
            'name_en.required'    => 'الإسم بالإنجليزي مطلوب',
            'code.required'       => 'الكود مطلوب',
            'sort_order.required' => 'ترتيب العرض مطلوب',
            'sort_order.numeric'  => 'ترتيب العرض يقبل فقط أرقام',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        else {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$id;
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'name_ar'    => $request->name_ar,
                    'name_en'    => $request->name_en,
                    'code'       => $request->code,
                    'sort_order' => $request->sort_order,
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
            }
        }
    }

    public function changeBankStatus(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'active' => $request->active,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changePropertyCompleted(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'property_completed' => $request->property_completed,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changePropertyUnCompleted(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'property_uncompleted' => $request->property_uncompleted,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changeJoint(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'joint' => $request->joint,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changeGuarantees(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'guarantees' => $request->guarantees,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changeQuestCheck(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'quest_check' => $request->quest_check,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changeBearTax(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bear_tax' => $request->bear_tax,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function changeShl(Request $request)
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'shl' => $request->shl,
            ],
        ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

}
