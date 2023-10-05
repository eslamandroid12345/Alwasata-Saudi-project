<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use View;

class SettingsController extends Controller
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

    public function getCalculatorSettingsValue()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorSetting?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $setting = json_decode($response->getBody(), true);
        return view('Admin.Calculator.Settings.show_settings', compact('setting'));
    }

    public function updateCalculatorSetting(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $productType = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorSetting';
        $response = $client->post($productType, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => $request->all(),
        ]);
        $body = [];
        try {
            $body = json_decode($response->getBody(), true);
        }
        catch (Exception $e) {
            $body = [];
        }
        $status = $response->getStatusCode();
        if ($status == 422) {
            $errors = [];
            $errors = $body['errors'] ?? [];
            foreach ($errors as $error) {
                foreach ($error as $key => $value) {
                    return redirect()->back()->with(['errors_api' => $value]);
                }
            }
        }
        elseif ($status != 200) {
            return redirect()->back()->with(['errors_api' => 'حدث خطأ , يرجي المحاولة لاحقاً']);
        }
        else {
            return redirect()->route('admin.getCalculatorSettings')
                ->with(['success_api' => 'تم التحديث بنجاح']);
        }
    }

}
