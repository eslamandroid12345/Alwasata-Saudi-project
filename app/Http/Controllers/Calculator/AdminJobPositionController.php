<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class AdminJobPositionController extends Controller
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

    public function jobPositionIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPositions = json_decode($response->getBody(), true);
        return view('Admin.Calculator.jobPositions.index', compact('jobPositions'));
    }

    public function jobPositionIndexDataTables()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPositions = json_decode($response->getBody(), true);
        return Datatables::of($jobPositions['data'])->setRowId(function ($jobPosition) {
            return $jobPosition['id'];
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editBank" href="'.url('admin/job-position-edit/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addNewJobPosition()
    {
        return view('Admin.Calculator.jobPositions.add_job_position');
    }

    public function saveNewJobPosition(Request $request)
    {
        if ($request->retirement == '') {
            $retirement = 0;
        }
        else {
            $retirement = 1;
        }
        if ($request->active == '') {
            $active = 0;
        }
        else {
            $active = 1;
        }
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'name_ar'                => $request->name_ar,
                'name_en'                => $request->name_en,
                'code'                   => $request->code,
                'sort_order'             => $request->sort_order,
                'salary_deduction'       => $request->salary_deduction,
                'retirement_age'         => $request->retirement_age,
                'retirement_calc_number' => $request->retirement_calc_number,
                'retirement'             => $retirement,
                'active'                 => $active,
            ],
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
            return redirect()->route('admin.jobPositionIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function editJobPositionItem($id)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPosition = json_decode($response->getBody(), true);
        return view('Admin.Calculator.jobPositions.edit', compact('jobPosition'));
    }

    public function updateJobPosition(Request $request)
    {
        if ($request->retirement == '') {
            $retirement = 0;
        }
        else {
            $retirement = 1;
        }
        if ($request->active == '') {
            $active = 0;
        }
        else {
            $active = 1;
        }
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'name_ar'                => $request->name_ar,
                'name_en'                => $request->name_en,
                'code'                   => $request->code,
                'sort_order'             => $request->sort_order,
                'salary_deduction'       => $request->salary_deduction,
                'retirement_age'         => $request->retirement_age,
                'retirement_calc_number' => $request->retirement_calc_number,
                'retirement'             => $retirement,
                'active'                 => $active,
            ],
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
            return redirect()->route('admin.jobPositionIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function removeJobPosition(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$request->id;
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

}
