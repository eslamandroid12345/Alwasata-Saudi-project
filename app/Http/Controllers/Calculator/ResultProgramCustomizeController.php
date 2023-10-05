<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

class ResultProgramCustomizeController extends Controller
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

    public function flexibleProgramCustomize()
    {
        $getFlexibleSettings = DB::table('program_settings')->where('program_id', '=', 1)->select('id', 'value_ar', 'option_value')->get();
        $getPersonalSettings = DB::table('program_settings')->where('program_id', '=', 2)->select('id', 'value_ar', 'option_value', 'program_id')->get();
        $getPropertySettings = DB::table('program_settings')->where('program_id', '=', 3)->select('id', 'value_ar', 'option_value', 'program_id')->get();
        $getExtendedSettings = DB::table('program_settings')->where('program_id', '=', 4)->select('id', 'value_ar', 'option_value', 'program_id')->get();
        return view('Admin.Calculator.ProgramSettings.index', compact('getFlexibleSettings', 'getPersonalSettings', 'getPropertySettings', 'getExtendedSettings'));
    }

    public function changeFlexible(Request $request)
    {
        $response = DB::table('program_settings')
            ->where('id', $request->id)
            ->update([
                'option_value' => $request->checkValue,
                'updated_at'   => (Carbon::now('Asia/Riyadh')),
            ]);
        if ($response) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);

    }
}
