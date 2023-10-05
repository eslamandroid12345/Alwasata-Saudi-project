<?php

namespace App\Http\Controllers\Calculator;

use App\BankPercentage;
use App\FundingYear;
use App\Http\Controllers\Controller;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use View;

class SuggestionsController extends Controller
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

    public function index()
    {

        DB::table('notifications')
            ->where('type', 20)
            ->where('recived_id', auth()->user()->id)
            ->update(['status' => 1]);

        return view('Admin.Calculator.Suggestions.index', [
            'banks'         => BankPercentage::where('status', 0)->count(),
            'years'         => FundingYear::where('status', 0)->count(),
            'archiveBanks'  => BankPercentage::where('status', 2)->count(),
            'archiveYears'  => FundingYear::where('status', 2)->count(),
            'approvedBanks' => BankPercentage::where('status', 1)->count(),
            'approvedYears' => FundingYear::where('status', 1)->count(),
        ]);
    }

    public function years($type)
    {
        $status = self::checkStatusFromType($type);
        $extraFundingYears = FundingYear::where('status', $status)
            ->count();
        return view('Admin.Calculator.Suggestions.'.$type.'.years', compact('extraFundingYears'));
    }

    public function checkStatusFromType($type)
    {
        if ($type == 'new') {
            $status = 0;
        }
        elseif ($type == 'archives') {
            $status = 2;
        }
        else {
            $status = 1;
        }
        return $status;
    }

    public function percentages($type)
    {

        $status = self::checkStatusFromType($type);
        $profitPercentages = BankPercentage::where('status', $status)
            ->count();
        return view('Admin.Calculator.Suggestions.'.$type.'.percentages', compact('profitPercentages'));
    }

    public function yearsDataTables($status)
    {
        $extraFundingYears = FundingYear::where('status', $status)
            ->get();

        return Datatables::of($extraFundingYears)->setRowId(function ($extraFundingYear) {
            return $extraFundingYear->id;
        })->addColumn('user', function ($profitPercentages) {
            return $profitPercentages->user->name;
        })
            ->addColumn('yes', function ($extraFundingYear) {
                return $extraFundingYear->suggests
                    ->where('vote', 'yes')
                    ->where('suggestable_type', FundingYear::class)
                    ->where('suggestable_id', $extraFundingYear->id)
                    ->count();
            })->addColumn('created_at', function ($profitPercentages) {
                return date("d-m-Y", strtotime($profitPercentages->created_at));
            })->addColumn('updated_at', function ($profitPercentages) {
                return date("d-m-Y", strtotime($profitPercentages->updated_at));
            })->addColumn('no', function ($extraFundingYear) {
                return $extraFundingYear->suggests
                    ->where('vote', 'no')
                    ->where('suggestable_type', FundingYear::class)
                    ->where('suggestable_id', $extraFundingYear->id)
                    ->count();
            })->addColumn('action', function ($row) {
                $row->status == 2 ? $title = "نقل للإقتراحات الجديدة" : $title = "رفض المقترح";
                $row->status == 2 ? $icon = "fa fa-redo" : $icon = "fa fa-thumbs-down";
                $data = '<div class="tableAdminOption">';
                $archive = '';
                $approve = '';
                if ($row->status != 1) {
                    $archive = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.$title.'">
                         <a onclick="ArchiveForm('.$row->id.')" class="btn btn-danger btn-sm text-white"> <i class="fa '.$icon.'"></i></a>
                     </span>';
                }
                if ($row->status == 0) {
                    $approve = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="الموافقة على هذا المقترح">
                        <a onclick="ApproveForm('.$row->id.')" class="btn btn-success btn-sm text-white"> <i class="fas fa-thumbs-up"></i> </a>
                     </span>';
                }
                $data = $data.$approve.$archive.'
                     <span class="item pointer" data-toggle="tooltip" data-placement="top"  title="تفاصيل المقترح">
                         <a href="'.route('admin.suggestions.details', ['years', $row->id]).'" class="btn btn-primary btn-sm text-white">  <i class="fas fa-list"></i></a>
                     </span>';
                return $data;
            })->make(true);
    }

    public function percentagesDataTables($status)
    {

        $profitPercentages = BankPercentage::where('status', $status)
            ->get();
        return Datatables::of($profitPercentages)->setRowId(function ($profitPercentage) {
            return $profitPercentage->id;
        })->addColumn('user', function ($profitPercentages) {
            return $profitPercentages->user->name;
        })->addColumn('yes', function ($profitPercentages) {
            return $profitPercentages->suggests
                ->where('vote', 'yes')
                ->where('suggestable_type', BankPercentage::class)
                ->where('suggestable_id', $profitPercentages->id)
                ->count();
        })->addColumn('created_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->created_at));
        })->addColumn('updated_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->updated_at));
        })->addColumn('no', function ($profitPercentages) {
            return $profitPercentages->suggests
                ->where('vote', 'no')
                ->where('suggestable_type', BankPercentage::class)
                ->where('suggestable_id', $profitPercentages->id)
                ->count();
        })->addColumn('action', function ($row) {
            $row->status == 2 ? $title = "نقل للإقتراحات الجديدة" : $title = "رفض المقترح ";
            $row->status == 2 ? $icon = "fa fa-redo" : $icon = "fa fa-thumbs-down";
            $data = '<div class="tableAdminOption">';
            $archive = '';
            $approve = '';
            if ($row->status != 1) {
                $archive = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.$title.'">
                         <a onclick="ArchiveForm('.$row->id.')" class="btn btn-danger btn-sm text-white"> <i class="fa '.$icon.'"></i></a>
                     </span>';
            }
            if ($row->status == 0) {
                $approve = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="الموافقة على هذا المقترح">
                        <a onclick="ApproveForm('.$row->id.')" class="btn btn-success btn-sm text-white"> <i class="fas fa-thumbs-up"></i> </a>
                     </span>';
            }

            $data = $data.$approve
                .$archive.'
                     <span class="item pointer" data-toggle="tooltip" data-placement="top"  title="تفاصيل المقترح">
                         <a href="'.route('admin.suggestions.details', ['percentages', $row->id]).'" class="btn btn-primary btn-sm text-white">  <i class="fas fa-list"></i></a>
                     </span>';
            return $data;
        })->make(true);
    }

    public function approve(Request $request)
    {
        if ($request->suggestable_type == 'BankPercentage') {
            $data = BankPercentage::find($request->id);
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$data->apiId;
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'             => $data->bank_id,
                    'from_year'           => $data->from_year,
                    'to_year'             => $data->to_year,
                    'percentage'          => $data->percentage,
                    'residential_support' => $data->residential_support,
                    'guarantees'          => $data->guarantees,
                    'personal'            => $data->personal,
                ],
            ]);
            if (!$response) {
                return abort(404);
            }
        }
        else {
            $data = FundingYear::find($request->id);
            $guarantees = '';
            $residential_support = '';
            if (($data->residential_support != 1) && ($data->guarantees != 1)) {
                $guarantees = false;
                $residential_support = false;
            }
            elseif (($data->residential_support != 1) && ($data->guarantees === "1")) {
                $guarantees = false;
                $residential_support = false;
            }
            elseif (($data->residential_support === "1") && ($data->guarantees != 1)) {
                $guarantees = false;
                $residential_support = true;
            }
            else {
                $guarantees = true;
                $residential_support = true;
            }

            $client = new Client();
            $extraFundingYearUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$data->apiId;
            $response = $client->put($extraFundingYearUrl, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'             => $data->bank_id,
                    'job_position_id'     => $data->job_position_id,
                    'years'               => $data->years,
                    'residential_support' => $residential_support,
                    'guarantees'          => $guarantees,
                ],
            ]);

            if (!$response) {
                return abort(404);
            }
        }
        $data->update([
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم  الموافقة بنجاح',
        ]);
    }

    public function archive(Request $request)
    {
        if ($request->suggestable_type == 'BankPercentage') {
            $data = BankPercentage::find($request->id);
        }
        else {
            $data = FundingYear::find($request->id);
        }
        $data->update([
            'status' => 2,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم  النقل للأرشيف بنجاح',
        ]);
    }

    public function restore(Request $request)
    {
        if ($request->suggestable_type == 'BankPercentage') {
            $data = BankPercentage::find($request->id);
        }
        else {
            $data = FundingYear::find($request->id);
        }
        $data->update([
            'status' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم  النقل للأرشيف بنجاح',
        ]);
    }

    public function details($type, $id)
    {
        if ($type == 'percentages') {
            $data = BankPercentage::find($id);

            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$data->apiId;
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $main = json_decode($response->getBody(), true);
        }
        else {
            $data = FundingYear::find($id);
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$data->apiId;
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $main = json_decode($response->getBody(), true);
        }

        return view('Admin.Calculator.Suggestions.'.$type, [
            'main' => $main['data'],
            'data' => $data,
        ]);
    }
}
