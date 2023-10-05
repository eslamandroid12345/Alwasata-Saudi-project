<?php

namespace App\Http\Controllers;

use App\classCondition;
use App\customer;
use App\CustomersPhone;
use App\EditCalculationFormulaUser;
use App\editCoulmnsSettings;
use App\funding;
use App\Imports\excelTwoCloumns;
use App\Imports\testImport;
use App\joint;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\RequestHistory;
use App\real_estat;
use App\realType;
use App\request as req;
use App\requestConditions;
use App\requestConditionSettings;
use App\Setting;
use App\statusCondition;
use App\User;
use App\userCondition;
use App\waiting_requests_settings;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MyHelpers;
use View;

//to take date

class SettingsController extends AppController
{

    public function __construct()
    {
        parent::__construct();
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }

    public static function getOptionValue($option_name)
    {
        $setting = DB::table('settings')->where('option_name', $option_name)->get();
        return $setting[0]->option_value;
    }

    public static function getValidationValue($option_name)
    {
        $setting = DB::table('request_condition_settings')->where($option_name, '!=', null)->first();

        return $setting;
    }

    public function form($prefix)
    {
        if (auth()->user()->role == '7') {
            $fields = DB::table('settings')->where('option_name', 'LIKE', $prefix.'%')->get();
            //dd($prefix);
            return view('Settings.Forms.index', compact('fields', 'prefix'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function formUpdate(Request $request)
    {
        $fields = $request->except(['_token']);
        foreach ($fields as $key => $value) {
            DB::table('settings')->where('option_name', $key)->update(['option_value' => $value]);
        }
        return redirect()->back()->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function questions()
    {
        $questions = DB::table('questions')->get();
        if (auth()->user()->role == '7') {
            return view('Settings.Forms.question', compact('questions'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function addquestions()
    {
        $questions = DB::table('questions')->get();
        if (auth()->user()->role == '7') {
            return view('Settings.Forms.add_question', compact('questions'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function editquestions($id)
    {

        $questions = DB::table('questions')->where('id', $id)->first();
        if (auth()->user()->role == '7' && $questions) {
            return view('Settings.Forms.edit_question', compact('questions'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    // savequestions
    public function savequestions(Request $request)
    {

        $rules = [
            'question' => 'required',
        ];

        $customMessages = [
            'question.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        DB::table('questions')->insert(['question' => $request->question]);

        return redirect('admin/settings/questions')->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
    }

    public function statusquestions($id, $status)
    {
        //dd($id ,$status);
        DB::table('questions')->where('id', '=', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function updatequestions(Request $request)
    {

        $rules = [
            'question' => 'required',
        ];

        $customMessages = [
            'question.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        if (DB::table('questions')->find($request->id)) {
            DB::table('questions')->where('id', $request->id)->update([
                'question' => $request->question,
                'status'   => $request->status,
            ]);
            return redirect('admin/settings/questions')->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
        else {
            return redirect()->back();
        }
    }

    public function stutusRequest()
    {
        $classifcations = DB::table('classifcations')->get();
        $agents = DB::table('users')->where('role', 0)->where('status', 1)->get();
        $statuses = $this->status();

        $request_conditions = DB::table('request_conditions')->get();

        $condition_agent = DB::table('request_conditions')->join('user_conditions', 'user_conditions.cond_id', 'request_conditions.id')->join('users', 'users.id', 'user_conditions.user_id')->select('user_conditions.user_id', 'user_conditions.cond_id')->where('user_conditions.cond_type', 0)->get();

        //dd($condition_agent);

        $condition_class = DB::table('request_conditions')->join('class_conditions', 'class_conditions.cond_id', 'request_conditions.id')->join('classifcations', 'classifcations.id', 'class_conditions.class_id')->select('class_conditions.class_id',
            'class_conditions.cond_id')->where('class_conditions.cond_type', 0)->get();

        $condition_status = DB::table('request_conditions')->join('status_conditions', 'status_conditions.cond_id', 'request_conditions.id')->select('status_conditions.status', 'status_conditions.cond_id')->where('status_conditions.cond_type', 0)->get();

        $qualityCon = DB::table('settings')->where('option_name', 'qualityRequest_active')->first(); //if it active or not

        //dd( $qualityCon);

        if (auth()->user()->role == '7') {
            return view('Settings.Forms.stutus', compact('request_conditions', 'qualityCon', 'condition_agent', 'condition_class', 'condition_status', 'classifcations', 'statuses', 'agents'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function status($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->user()->id, 'new req'),
            1  => MyHelpers::admin_trans(auth()->user()->id, 'open req'),
            2  => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales agent req'),
            3  => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            4  => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            //5 => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales manager req'),
            // 5 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            6  => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            7  => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->user()->id, 'archive in funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            9  => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            // 11 => MyHelpers::admin_trans(auth()->user()->id, 'archive in mortgage manager req'),
            //  11 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            12 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            13 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            //14 => MyHelpers::admin_trans(auth()->user()->id, 'archive in general manager req'),
            //  14 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            15 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            16 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            17 => MyHelpers::admin_trans(auth()->user()->id, 'Rejected and archived'),

        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[14]);
    }

    public function addNewConditions(Request $request)
    {

        $salesAgents = $request->input('agents', []);
        $classifcations = $request->input('classifcations', []);
        $stutuses = $request->input('stutus', []);
        $timeDays = $request->timeday;

        // dd( $salesAgents);

        if (!$salesAgents && !$classifcations && !$stutuses && !$timeDays) {
            $rules = [
                'salesAgents' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        $request_condition = requestConditions::create([
            'timeDays'   => $timeDays,
            'created_at' => (Carbon::now('Asia/Riyadh')),
        ]);

        if ($salesAgents) {
            foreach ($salesAgents as $salesAgent) {
                userCondition::create([
                    'cond_id'   => $request_condition->id,
                    'user_id'   => $salesAgent,
                    'cond_type' => 0,
                ]);
            }
        }

        if ($classifcations) {
            foreach ($classifcations as $classifcation) {
                classCondition::create([
                    'cond_id'   => $request_condition->id,
                    'class_id'  => $classifcation,
                    'cond_type' => 0,
                ]);
            }
        }

        if ($stutuses) {
            foreach ($stutuses as $stutuse) {
                statusCondition::create([
                    'cond_id'   => $request_condition->id,
                    'status'    => $stutuse,
                    'cond_type' => 0,
                ]);
            }
        }

        return redirect()->route('admin.stutusRequestPage')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function removeCondition(Request $request)
    {

        $qualityReqs = DB::table('quality_reqs')
            // ->where('quality_reqs.allow_recive', 1)
            ->where('quality_reqs.con_id', $request->id)->get();

        foreach ($qualityReqs as $qualityReq) {
            DB::table('quality_reqs')->where('id', $qualityReq->id)->update(['con_id' => null]);
        }

        $removeCondition = requestConditions::where('id', $request->id)->delete();

        if ($removeCondition) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => $removeCondition]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $removeCondition]);
    }

    public function updateCondition(Request $request)
    {
        //dd($request->all());
        $salesAgents = $request->input('agents', []);
        $classifcations = $request->input('classifcations', []);
        $stutuses = $request->input('stutus', []);
        $timeDays = $request->timeday;
        $coun_id = $request->condID;

        // dd( $salesAgents);

        if (!$salesAgents && !$classifcations && !$stutuses && !$timeDays) {
            $rules = [
                'salesAgents' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        $checkIfTimedayChanheOnly = MyHelpers::checkIfTimedayChanheOnly($coun_id, $salesAgents, $classifcations, $stutuses, $timeDays);

        // get all requests that already added to quality tables and set conditio as null to be completed after that
        if ($checkIfTimedayChanheOnly) {

            $qualityReqs = DB::table('quality_reqs')->where('quality_reqs.allow_recive', 1)->where('quality_reqs.con_id', $coun_id)->update(['con_id' => null]);
        }

        requestConditions::where('id', $coun_id)->update(['timeDays' => $timeDays]);

        userCondition::where('cond_id', $coun_id)->where('cond_type', 0)->delete();

        foreach ($salesAgents as $salesAgent) {
            userCondition::create([
                'cond_id'   => $coun_id,
                'user_id'   => $salesAgent,
                'cond_type' => 0,

            ]);
        }

        classCondition::where('cond_id', $coun_id)->where('cond_type', 0)->delete();

        foreach ($classifcations as $classifcation) {
            classCondition::create([
                'cond_id'   => $coun_id,
                'class_id'  => $classifcation,
                'cond_type' => 0,
            ]);
        }

        statusCondition::where('cond_id', $coun_id)->where('cond_type', 0)->delete();

        foreach ($stutuses as $stutuse) {
            statusCondition::create([
                'cond_id'   => $coun_id,
                'status'    => $stutuse,
                'cond_type' => 0,
            ]);
        }

        return redirect()->route('admin.stutusRequestPage')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function waiting_requests_settings()
    {
        $classifcations = DB::table('classifcations')->get();
        $agents = DB::table('users')->where('role', 0)->where('status', 1)->get();
        $statuses = $this->status();

        $request_conditions = DB::table('waiting_requests_settings')->get();

        $condition_agent = DB::table('waiting_requests_settings')->join('user_conditions', 'user_conditions.cond_id', 'waiting_requests_settings.id')->join('users', 'users.id', 'user_conditions.user_id')->select('user_conditions.user_id',
            'user_conditions.cond_id')->where('user_conditions.cond_type', 1)->get();

        //dd($condition_agent);

        $condition_class = DB::table('waiting_requests_settings')->join('class_conditions', 'class_conditions.cond_id', 'waiting_requests_settings.id')->join('classifcations', 'classifcations.id', 'class_conditions.class_id')->select('class_conditions.class_id',
            'class_conditions.cond_id')->where('class_conditions.cond_type', 1)->get();

        $condition_status = DB::table('waiting_requests_settings')->join('status_conditions', 'status_conditions.cond_id', 'waiting_requests_settings.id')->select('status_conditions.status', 'status_conditions.cond_id')->where('status_conditions.cond_type', 1)->get();

        //dd( $qualityCon);

        $replay_time = DB::table('settings')->where('option_name', 'waitingRequest_replaytime')->first(); //if it active or not

        if (auth()->user()->role == '7') {
            return view('Settings.Forms.waiting_requests_settings', compact('replay_time', 'request_conditions', 'condition_agent', 'condition_class', 'condition_status', 'classifcations', 'statuses', 'agents'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function add_waiting_requests_conditions(Request $request)
    {

        $salesAgents = $request->input('agents', []);
        $classifcations = $request->input('classifcations', []);
        $stutuses = $request->input('stutus', []);
        $timeDays = $request->timeday;

        // dd( $salesAgents);

        if (!$salesAgents && !$classifcations && !$stutuses && !$timeDays) {
            $rules = [
                'salesAgents' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        $request_condition = waiting_requests_settings::create([
            'timeDays'   => $timeDays,
            'created_at' => (Carbon::now('Asia/Riyadh')),
        ]);

        if ($salesAgents) {
            foreach ($salesAgents as $salesAgent) {
                userCondition::create([
                    'cond_id'   => $request_condition->id,
                    'user_id'   => $salesAgent,
                    'cond_type' => 1,
                ]);
            }
        }

        if ($classifcations) {
            foreach ($classifcations as $classifcation) {
                classCondition::create([
                    'cond_id'   => $request_condition->id,
                    'class_id'  => $classifcation,
                    'cond_type' => 1,
                ]);
            }
        }

        if ($stutuses) {
            foreach ($stutuses as $stutuse) {
                statusCondition::create([
                    'cond_id'   => $request_condition->id,
                    'status'    => $stutuse,
                    'cond_type' => 1,
                ]);
            }
        }

        return redirect()->route('admin.waiting_requests_settings')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function remove_waiting_requests_conditions(Request $request)
    {

        $removeCondition = waiting_requests_settings::where('id', $request->id)->delete();

        if ($removeCondition) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => $removeCondition]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $removeCondition]);
    }

    public function update_waiting_requests_conditions(Request $request)
    {

        $salesAgents = $request->input('agents', []);
        $classifcations = $request->input('classifcations', []);
        $stutuses = $request->input('stutus', []);
        $timeDays = $request->timeday;
        $coun_id = $request->condID;

        if (!$salesAgents && !$classifcations && !$stutuses && !$timeDays) {
            $rules = [
                'salesAgents' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        waiting_requests_settings::where('id', $coun_id)->update(['timeDays' => $timeDays]);

        userCondition::where('cond_id', $coun_id)->where('cond_type', 1)->delete();

        foreach ($salesAgents as $salesAgent) {
            userCondition::create([
                'cond_id'   => $coun_id,
                'user_id'   => $salesAgent,
                'cond_type' => 1,

            ]);
        }

        classCondition::where('cond_id', $coun_id)->where('cond_type', 1)->delete();

        foreach ($classifcations as $classifcation) {
            classCondition::create([
                'cond_id'   => $coun_id,
                'class_id'  => $classifcation,
                'cond_type' => 1,
            ]);
        }

        statusCondition::where('cond_id', $coun_id)->where('cond_type', 1)->delete();

        foreach ($stutuses as $stutuse) {
            statusCondition::create([
                'cond_id'   => $coun_id,
                'status'    => $stutuse,
                'cond_type' => 1,
            ]);
        }

        return redirect()->route('admin.waiting_requests_settings')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function update_waiting_requests_replaytime(Request $request)
    {

        $rules = [
            'replay_time' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        DB::table('settings')->where('option_name', 'waitingRequest_replaytime')->update(['option_value' => $request->replay_time]); //if it active or not

        return redirect()->route('admin.waiting_requests_settings')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function updateQualityUser($reqID)
    {

        $user_id = MyHelpers::findNextQuality();
        $getQualityReq = DB::table('quality_reqs')->where('id', $reqID)->first();

        $ifThereIsPreviousReq = MyHelpers::checkQualityUser($reqID, $user_id);

        if ($ifThereIsPreviousReq == "true") {
            DB::table('quality_reqs')->where('id', $reqID)->update(['allow_recive' => 1, 'user_id' => $user_id, 'con_id' => null, 'created_at' => Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s")]);

            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                'recived_id' => $user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 0,
                'req_id'     => $reqID,
            ]);

            DB::table('request_histories')->insert([
                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                'user_id'      => null,
                'recive_id'    => $user_id,
                'history_date' => (Carbon::now('Asia/Riyadh')),
                'req_id'       => $getQualityReq->req_id,
                'content'      => null,
            ]);

        }
        else {

            $checkIfQualityUserArchived = User::where('id', $ifThereIsPreviousReq)->where('status', 1)->first();

            if ($checkIfQualityUserArchived) {
                $user_id = $checkIfQualityUserArchived;
            }

            DB::table('quality_reqs')->where('id', $reqID)->update(['allow_recive' => 1, 'user_id' => $user_id, 'con_id' => null, 'created_at' => Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s")]);

            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                'recived_id' => $user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 0,
                'req_id'     => $reqID,
            ]);

            DB::table('request_histories')->insert([
                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                'user_id'      => null,
                'recive_id'    => $user_id,
                'history_date' => (Carbon::now('Asia/Riyadh')),
                'req_id'       => $getQualityReq->req_id,
                'content'      => null,
            ]);
        }
    }

    public function addNewRequestConditionsPage()
    {

        if (auth()->user()->role == '7') {
            $worke_sources = WorkSource::all();
            return view('Settings.Forms.addRequestConditionSettings', compact('worke_sources'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function addNewRequestConditions(Request $request)
    {

        $from_birth_date = $request->request_validation_from_birth_date;
        $to_birth_date = $request->request_validation_to_birth_date;

        $from_birth_hijri = $request->request_validation_from_birth_hijri;
        $to_birth_hijri = $request->request_validation_to_birth_hijri;

        $from_salary = $request->request_validation_from_salary;
        $to_salary = $request->request_validation_to_salary;

        $to_work = $request->request_validation_to_work;

        $to_support = $request->request_validation_to_support;
        $to_hasProperty = $request->request_validation_to_hasProperty;
        $to_hasJoint = $request->request_validation_to_hasJoint;
        $to_has_obligations = $request->request_validation_to_has_obligations;
        $to_has_financial_distress = $request->request_validation_to_has_financial_distress;
        $owning_property = $request->request_validation_to_owningProperty;

        if (!$from_birth_date && !$to_birth_date && !$from_birth_hijri && !$to_birth_hijri && !$from_salary && !$to_salary && !$to_work && !$to_support && !$to_hasProperty && !$to_hasJoint && !$to_has_obligations && !$to_has_financial_distress && !$owning_property) {
            $rules = [
                'from_birth_date' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        $request_condition = RequestConditionSettings::create([
            'request_validation_from_birth_date' => $from_birth_date,
            'request_validation_to_birth_date'   => $to_birth_date,

            'request_validation_from_birth_hijri' => $from_birth_hijri,
            'request_validation_to_birth_hijri'   => $to_birth_hijri,

            'request_validation_from_salary' => $from_salary,
            'request_validation_to_salary'   => $to_salary,

            'request_validation_to_work' => $to_work,

            'request_validation_to_support'                => $to_support,
            'request_validation_to_hasProperty'            => $to_hasProperty,
            'request_validation_to_hasJoint'               => $to_hasJoint,
            'request_validation_to_has_obligations'        => $to_has_obligations,
            'request_validation_to_has_financial_distress' => $to_has_financial_distress,

            'request_validation_to_owningProperty' => $owning_property,
        ]);

        return redirect()->route('admin.requestConditionSettings')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
    }

    public function updateRequestCondition(Request $request)
    {
        $condID = $request->condID;
        $from_birth_date = $request->request_validation_from_birth_date;
        $to_birth_date = $request->request_validation_to_birth_date;

        $from_birth_hijri = $request->request_validation_from_birth_hijri;
        $to_birth_hijri = $request->request_validation_to_birth_hijri;

        $from_salary = $request->request_validation_from_salary;
        $to_salary = $request->request_validation_to_salary;

        $to_work = $request->request_validation_to_work;

        $to_support = $request->request_validation_to_support;
        $to_hasProperty = $request->request_validation_to_hasProperty;
        $to_hasJoint = $request->request_validation_to_hasJoint;
        $to_has_obligations = $request->request_validation_to_has_obligations;
        $to_has_financial_distress = $request->request_validation_to_has_financial_distress;

        $owning_property = $request->request_validation_to_owningProperty;

        // dd( $salesAgents);

        if (!$from_birth_date && !$to_birth_date && !$from_birth_hijri && !$to_birth_hijri && !$from_salary && !$to_salary && !$to_work && !$to_support && !$to_hasProperty && !$to_hasJoint && !$to_has_obligations && !$to_has_financial_distress && !$owning_property) {
            $rules = [
                'from_birth_date' => 'required',
            ];

            $customMessages = [
                'required' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),
            ];

            $this->validate($request, $rules, $customMessages);
        }

        RequestConditionSettings::where('id', $condID)->update([
            'request_validation_from_birth_date' => $from_birth_date,
            'request_validation_to_birth_date'   => $to_birth_date,

            'request_validation_from_birth_hijri' => $from_birth_hijri,
            'request_validation_to_birth_hijri'   => $to_birth_hijri,

            'request_validation_from_salary' => $from_salary,
            'request_validation_to_salary'   => $to_salary,

            'request_validation_to_work' => $to_work,

            'request_validation_to_support'                => $to_support,
            'request_validation_to_hasProperty'            => $to_hasProperty,
            'request_validation_to_hasJoint'               => $to_hasJoint,
            'request_validation_to_has_obligations'        => $to_has_obligations,
            'request_validation_to_has_financial_distress' => $to_has_financial_distress,

            'request_validation_to_owningProperty' => $owning_property,
        ]);
        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function removeRequestCondition(Request $request)
    {

        $removeCondition = RequestConditionSettings::where('id', $request->id)->delete();
        //delete not

        if ($removeCondition) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => $removeCondition]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $removeCondition]);
    }

    public function RequestConditions()
    {
        $request_conditions = RequestConditionSettings::all();

        $request_conditions_id = RequestConditionSettings::pluck('id')->toArray();
        $worke_sources = WorkSource::all();

        // $request_conditions_id= array_values($request_conditions_id);

        // dd($request_conditions_id);

        if (auth()->user()->role == '7') {
            return view('Settings.Forms.requestConditionSettings', compact('request_conditions', 'request_conditions_id', 'worke_sources'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function classificationsSettings()
    {

        $classes = DB::table('classifcations')->count(); // only active
        $user_roles = $this->userRole();

        return view('Settings.classification.allClass', compact('classes', 'user_roles'));
    }
    public function sourcesSettings()
    {

        $classes = DB::table('request_source')->count(); // only active
        $RoleSelected = $this->userRoles();
        return view('Settings.sources.index', compact('classes','RoleSelected'));
    }

    public function settingSources_datatable()
    {
        $classes = DB::table('request_source')->orderBy('id', 'DESC');

        return Datatables::of($classes)->setRowId(function ($classes) {
            return $classes->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="edit" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';

            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->addColumn('idn', function ($row) {
            static $var=1;
            return $var++;
        })->rawColumns(['idn', 'action'])->make(true);
    }
    public function addSourcePage()
    {
        $RoleSelected = $this->userRoles();
        return view('Settings.sources.create',compact("RoleSelected"));
    }
    public function getSource(Request $request)
    {

        $class = DB::table('request_source')->where('id', $request->id)->first();

        if (!empty($class)) {
            return response()->json(['class' => $class, 'status' => 1]);
        }

        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }
    public function updateSource(Request $request)
    {

        $class = $request->source;

        $rules = [
            'source' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateClass = DB::table('request_source')->where('id', $request->id)->update([
            'value'                     => $class,
            'role'                     => $request->role
        ]);

        $class = DB::table('request_source')->where('id', $request->id)->first();

        if ($updateClass == 1) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'class' => $class]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }
    public function deleteSource(Request $request)
    {

        $class = DB::table('request_source')->where('id', $request->id)->first();
        DB::table('requests')->where('source', $class->id)->update(['source' => null]);

        $deletedClass = DB::table('request_source')->where('id', $class->id)->delete();

        if ($deletedClass == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $deletedClass]);
        }
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => $deletedClass]);
    }
    //This new function to show dataTabel in view(Admin.Users.myUsers)
    public function store(Request $request)
    {

        $rules = [
            'source' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $newClass = DB::table('request_source')->insert(['value' => $request->source,'role'  => $request->role]);

        if ($newClass) {
            return redirect('admin/settings/sources')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
    public function userRole($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'Sales Agent'),
            1 => MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager'),
            2 => MyHelpers::admin_trans(auth()->user()->id, 'Funding Manager'),
            3 => MyHelpers::admin_trans(auth()->user()->id, 'Mortgage Manager'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'General Manager'),
            5 => MyHelpers::admin_trans(auth()->user()->id, 'Quality User'),
            9 => MyHelpers::admin_trans(auth()->user()->id, 'Quality Manager'),
            6 => MyHelpers::admin_trans(auth()->user()->id, 'Collaborator'),

        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[7]);
    }


    public function userRoles($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'Sales Agent'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'General Manager'),
            6 => MyHelpers::admin_trans(auth()->user()->id, 'Collaborator'),
            13 =>  __("global.bankDelegate"),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[7]);
    }



    public function addclassPage()
    {

        $user_roles = $this->userRole();

        return view('Settings.classification.add_class', compact('user_roles'));
    }

    public function settingClass_datatable(Request $request)
    {
        $classes = DB::table('classifcations')->orderBy('id', 'DESC');

        #check class_user
        $class_user = $request->get("class_user");

        if ($class_user != ""){
            $classes = $classes->where('user_role',$class_user);
        }

        #check class_type
        $class_type = $request->get("class_type");

        if ($class_type != ''){
            $classes = $classes->where('type',(int)$class_type);
        }

        #check name
        $class_name = $request->get("class_name");

        if ($class_name != ''){
            $classes = $classes->where('value',$class_name);
        }


       
        return Datatables::of($classes)->setRowId(function ($classes) {
            return $classes->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="edit" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';

            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->addColumn('user_role', function ($row) {
            return @$this->userRole()[$row->user_role] ?? $this->userRole()[7];
        })->editColumn('type', function ($row) {
            if ($row->type == 1) {
                $data = 'إيجابي';
            }
            else {
                $data = 'سلبي';
            }
            return $data;
        })->editColumn('is_required_in_calculater', function ($row) {
            if ($row->is_required_in_calculater == 1) {
                $data = '<span class="item" style="color:green" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Yes').'">
                <i class="fas fa-check"></i>
            </span>';
            }
            else {
                $data = '<span class="item" style="color:red"  data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'No').'">
                <i class="fas fa-times"></i>
            </span>';
            }
            return $data;
        })->rawColumns(['is_required_in_calculater', 'action'])->make(true);
    }
    public function getclass(Request $request)
    {

        $class = DB::table('classifcations')->where('id', $request->id)->first();

        if (!empty($class)) {
            return response()->json(['class' => $class, 'status' => 1]);
        }

        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

    public function changeClassType(Request $request)
    {

        $getClass = DB::table('classifcations')->where('id', $request->id)->first();

        if ($getClass->type == 1) {
            $updateClass = DB::table('classifcations')->where('id', $request->id)->update(['type' => 0]);
        }
        else {
            $updateClass = DB::table('classifcations')->where('id', $request->id)->update(['type' => 1]);
        }

        return response($updateClass);
    }

    public function updateclass(Request $request)
    {

        $class = $request->class;
        $role = $request->role;
        $type = $request->type;
        $required_cal = $request->is_required_in_calculater;

        $rules = [
            'class' => 'required',
            'role'  => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateClass = DB::table('classifcations')->where('id', $request->id)->update([
            'value'                     => $class,
            'user_role'                 => $role,
            'type'                      => $type,
            'is_required_in_calculater' => $required_cal,
        ]);

        $class = DB::table('classifcations')->where('id', $request->id)->first();

        if ($updateClass == 1) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'class' => $class]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }


    public function deleteclass(Request $request)
    {

        $class = DB::table('classifcations')->where('id', $request->id)->first();

        $requests = DB::table('requests')->where('class_id_agent', $class->id)->OrWhere('class_id_sm', $class->id)->OrWhere('class_id_fm', $class->id)->OrWhere('class_id_mm', $class->id)->OrWhere('class_id_gm', $class->id)->OrWhere('class_id_quality', $class->id)->get();

        foreach ($requests as $request) {

            DB::table('requests')->where('class_id_agent', $class->id)->update(['class_id_agent' => null]);

            DB::table('requests')->where('class_id_sm', $class->id)->update(['class_id_sm' => null]);

            DB::table('requests')->where('class_id_fm', $class->id)->update(['class_id_fm' => null]);

            DB::table('requests')->where('class_id_mm', $class->id)->update(['class_id_mm' => null]);

            DB::table('requests')->where('class_id_gm', $class->id)->update(['class_id_gm' => null]);

            DB::table('requests')->where('class_id_quality', $class->id)->update(['class_id_quality' => null]);
        }

        $deletedClass = DB::table('classifcations')->where('id', $class->id)->delete();

        if ($deletedClass == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $deletedClass]);
        }
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => $deletedClass]);
    }

    public function saveclass(Request $request)
    {

        $rules = [
            'class' => 'required',
            'role'  => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $newClass = DB::table('classifcations')->insert(['value' => $request->class, 'user_role' => $request->role, 'type' => $request->type, 'is_required_in_calculater' => $request->is_required_in_calculater]);

        if ($newClass) {
            return redirect('admin/settings/classifications')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function difference()
    {
        if (auth()->user()->role == '7') {
            $fields = DB::table('settings')->where('option_name', 'LIKE', 'accounting_numbers_difference')->first();
            $prefix = 'accounting_numbers_difference';
            return view('Settings.Difference.index', compact('fields', 'prefix'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function citySettings()
    {

        $cities = DB::table('cities')->count(); // only active

        return view('Settings.city.allCity', compact('cities'));
    }

    //This new function to show dataTabel in view(Admin.Users.myUsers)
    public function settingCity_datatable()
    {
        $cities = DB::table('cities')->orderBy('id', 'DESC');

        return Datatables::of($cities)->setRowId(function ($city) {
            return $city->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item" id="edit" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';

            $data = $data.'<span class="item" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addcityPage()
    {

        return view('Settings.city.add_city');
    }

    public function getcity(Request $request)
    {

        $city = DB::table('cities')->where('id', $request->id)->first();

        if (!empty($city)) {
            return response()->json(['city' => $city, 'status' => 1]);
        }

        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

    public function updatecity(Request $request)
    {

        $city = $request->city;

        $rules = [
            'city' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateCity = DB::table('cities')->where('id', $request->id)->update([
            'value' => $city,
        ]);

        $city = DB::table('cities')->where('id', $request->id)->first();

        if ($updateCity == 1) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'city' => $city]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function deletecity(Request $request)
    {

        $city = DB::table('cities')->where('id', $request->id)->first();

        $requests = DB::table('requests')->join('real_estats', 'real_estats.id', 'requests.real_id')->where('real_estats.city', $city->id)->select('requests.*')->get();

        foreach ($requests as $request) {

            $reqInfo = DB::table('requests')->where('requests.id', $request->id)->first();

            $fn = DB::table('real_estats')->where('id', $reqInfo->real_id)->update(['city' => null]);
        }

        $deleteCity = DB::table('cities')->where('id', $city->id)->delete();

        if ($deleteCity == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $deleteCity]);
        }
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => $deleteCity]);
    }

    public function savecity(Request $request)
    {

        $rules = [
            'city' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $newCity = DB::table('cities')->insert(['value' => $request->city]);

        if ($newCity) {
            return redirect('admin/settings/city')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function realtypeSettings()
    {

        $realTypes = DB::table('real_types')->count(); // only active

        return view('Settings.realtype.allRealtype', compact('realTypes'));
    }

    //This new function to show dataTabel in view(Admin.Users.myUsers)
    public function settingRealtype_datatable()
    {
        $realTypes = DB::table('real_types')->orderBy('id', 'DESC');

        return Datatables::of($realTypes)->setRowId(function ($realTypes) {
            return $realTypes->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="edit" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';

            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addrealtypePage()
    {

        return view('Settings.realtype.add_realtype');
    }

    public function getrealtype(Request $request)
    {

        $realTypeObj = realType::find($request->id);

        if (!empty($realTypeObj)) {
            return response()->json(['realtype' => $realTypeObj, 'status' => 1]);
        }

        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

    public function updaterealtype(Request $request)
    {

        $realtype = $request->realtype;

        $rules = [
            'realtype' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateRealType = DB::table('real_types')->where('id', $request->id)->update([
            'value' => $realtype,
        ]);

        $realTypeObj = realType::find($request->id);

        if ($updateRealType == 1) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'realtype' => $realTypeObj]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function deleterealtype(Request $request)
    {

        $realType = realType::find($request->id);

        $requests = DB::table('real_estats')->where('real_estats.type', $realType->id)->get();

        foreach ($requests as $request) {

            $fn = DB::table('real_estats')->where('id', $request->id)->update(['type' => null]);
        }

        $deleteRealtype = realType::where('id', $realType->id)->delete();

        if ($deleteRealtype == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $deleteRealtype]);
        }
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => $deleteRealtype]);
    }

    public function saverealtype(Request $request)
    {

        $rules = [
            'realtype' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $newRealType = realType::create([
            'value' => $request->realtype,
        ]);

        if ($newRealType) {
            return redirect('admin/settings/realtype')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function askRequestSettings()
    {
        $askConditione = DB::table('settings')->where('option_name', 'LIKE', 'askRequest'.'%')->get();
        $noRequest = $askConditione->where('option_name', 'askRequest_noRequest')->first();
        $hours = $askConditione->where('option_name', 'askRequest_hours')->first();
        $eachDay = $askConditione->where('option_name', 'askRequest_eachDay')->first();
        // dd($askConditione );

        $movePendingConditione = DB::table('settings')->where('option_name', 'LIKE', 'movePendingByAgent'.'%')->get();
        $movePending_noRequest = $movePendingConditione->where('option_name', 'movePendingByAgent_dailyReq')->first();

        $setting = Setting::allSetting();
        //dd($setting);
        return view('Settings.askRequest.askRequestCondition', compact('askConditione', 'noRequest', 'hours', 'eachDay', 'movePendingConditione', 'movePending_noRequest', 'setting'));
    }

    public function updateAskRequestCondition(Request $request)
    {

        $rules = [
            'noRequest' => 'required',
            'timehour'  => 'required',
            'eachDay'   => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $noRequest = DB::table('settings')->where('option_name', 'askRequest_noRequest')->update(['option_value' => $request->noRequest]);

        $hours = DB::table('settings')->where('option_name', 'askRequest_hours')->update(['option_value' => $request->timehour]);

        $eachDay = DB::table('settings')->where('option_name', 'askRequest_eachDay')->update(['option_value' => $request->eachDay]);

        return redirect('admin/settings/agentAskRequest')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function updateMovePendingRequestCondition(Request $request)
    {

        $rules = [
            'movePending_noReqs' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $noRequest = DB::table('settings')->where('option_name', 'movePendingByAgent_dailyReq')->update(['option_value' => $request->movePending_noReqs]);
        return redirect('admin/settings/agentAskRequest')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function updateAskRequestActive()
    {

        $active = DB::table('settings')->where('option_name', 'askRequest_active')->where('option_value', 'false')->update(['option_value' => 'true']);

        if ($active == 0) {
            $active = DB::table('settings')->where('option_name', 'askRequest_active')->where('option_value', 'true')->update(['option_value' => 'false']);
        }

        if ($active) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function updateMovePendingRequestActive()
    {

        $active = DB::table('settings')->where('option_name', 'movePendingByAgent_active')->where('option_value', 'false')->update(['option_value' => 'true']);

        if ($active == 0) {
            $active = DB::table('settings')->where('option_name', 'movePendingByAgent_active')->where('option_value', 'true')->update(['option_value' => 'false']);
        }

        if ($active) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function updateQualityRequestActive()
    {

        $active = DB::table('settings')->where('option_name', 'qualityRequest_active')->where('option_value', 'false')->update(['option_value' => 'true']);

        if ($active) {

            #get start date
            $getQualityReq = DB::table('settings')->where('option_name', 'qualityRequest_startDate')->first();

            if ($getQualityReq->option_value != null) {

                $date = Carbon::parse($getQualityReq->option_value);
                $now = Carbon::now();

                $diff = $date->diffInDays($now);

                #update end date
                DB::table('settings')->where('option_name', 'qualityRequest_endDate')->update(['option_value' => $now]);

                #get calculate counter
                $getCounter = DB::table('settings')->where('option_name', 'qualityRequest_counterDay')->first();

                if ($getCounter->option_value == null) {
                    $getCounter->option_value = 0;
                }

                #update calculate counter
                DB::table('settings')->where('option_name', 'qualityRequest_counterDay')->update(['option_value' => ((int) $getCounter->option_value) + $diff]);
            }
        }

        if ($active == 0) {

            $active = DB::table('settings')->where('option_name', 'qualityRequest_active')->where('option_value', 'true')->update(['option_value' => 'false']);

            $now = Carbon::now();

            #update start date
            DB::table('settings')->where('option_name', 'qualityRequest_startDate')->update(['option_value' => $now]);
        }

        if ($active) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function updatehasbah_net_movment()
    {

        $active = DB::table('settings')->where('option_name', 'hasbah_net_movment')->where('option_value', 0)->update(['option_value' => 1]);

        if ($active == 0) {
            $active = DB::table('settings')->where('option_name', 'hasbah_net_movment')->where('option_value', 1)->update(['option_value' => 0]);
        }

        if ($active) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function editCoulmn(Request $request)
    {
        $columns = $request->except(['_token', 'tableName', 'select_all']);
        $tableName = $request->input('tableName');

        //DELETE ALL SETTINGS
        editCoulmnsSettings::where('tableName', 'underReqsTable')->where('user_id', auth()->user()->id)->delete();
        //
        $visibleCoulmns = null;

        foreach ($columns as $key => $value) {

            $newEdit = editCoulmnsSettings::create([
                'tableName'    => 'underReqsTable',
                'coulmnName'   => $key,
                'coulmnNumber' => $value,
                'user_id'      => auth()->user()->id,
            ]);

            $visibleCoulmns[] = $value;
        }

        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Edit Succesffuly'), 'visibleCoulmns' => $visibleCoulmns]);
    }

    function excel()
    {
        // return Excel::download(new UsersExport, 'users.xlsx');

        $data = User::get()->toArray();
        return Excel::store('itsolutionstuff_example', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('PDF');
    }

    public function importExcelPage()
    {

        $excel_agents = DB::table('agent_and_excel_import');
        $excel_agents_count = DB::table('agent_and_excel_import')->get()->count();

        $agents = DB::table('users')->where('role', 0)->where('status', 1)->get();

        return view('Admin.Excel.importExcel', compact('excel_agents', 'agents', 'excel_agents_count'));
    }

    public function updateAgentExcel(Request $request)
    {

        $salesAgents = $request->input('agents', []);

        $deleteagent = DB::table('agent_and_excel_import')->delete();

        if ($salesAgents) {
            foreach ($salesAgents as $salesAgent) {
                $result = DB::table('agent_and_excel_import')->insert([
                    [
                        'user_id' => $salesAgent,
                    ],

                ]);
            }
        }

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    function importExcel(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:xls,xlsx,csv,txt',
        ];

        $customMessages = [
            'file' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);
        $data = Excel::toArray(new testImport, $request->file('file'));

        //--------------------------------------------------
        // Start Importing
        //--------------------------------------------------
        $success = 0;
        $error = 0;
        $excel_agents = DB::table('agent_and_excel_import')->pluck('user_id')->toArray();
        $excel_agents_count = DB::table('agent_and_excel_import')->get()->count();
        foreach ($data[0] as $key => $row) {
            $rules1 = [
                'mobile' => ['numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            ];
            $rules2 = [
                'mobile' => ['numeric', 'unique:customers_phones', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            ];
            $row['number'] = substr($row['number'], -9);
            $validator1 = Validator::make(['mobile' => $row['number']], $rules1);
            $validator2 = Validator::make(['mobile' => $row['number']], $rules2);
            //--------------------------------------------------
            // Check validation failure Of Either Two Validation
            //--------------------------------------------------
            $customer = customer::where('mobile', $row['number'])->count();
            $phones = CustomersPhone::where('mobile', $row['number'])->count();

            if ($validator1->fails() || $validator2->fails() || $customer > 0 || $phones > 0) {
                $error++;
                continue;
            }
            else {
                //----------------------------------
                // validation Success
                //----------------------------------
                if ($excel_agents_count == 0) {
                    $user_id = $this->findNextAgent();
                }
                else {
                    $user_id = $this->findNextAgentWithEntred($excel_agents);
                }
                if ($customer == 0 && $phones == 0) {

                    $costomer = customer::create([
                        'name'    => 'بدون اسم',
                        'mobile'  => $row['number'],
                        'user_id' => $user_id,
                    ]);
                    //***********************************************
                    // Task-29 Check If Customer Has Pending Request
                    // If Has Don't Create New One
                    //***********************************************
                    $binding = PendingRequest::where(['customer_id' => $costomer->id])->count();
                    if ($binding == 0) {
                        $joint = joint::create([]);

                        $real = real_estat::create([]);

                        $fun = funding::create([]);

                        $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
                        $searching_id = RequestSearching::create()->id;

                        $request = req::create([
                            'source'       => 1,
                            'req_date'     => $reqdate,
                            'user_id'      => $user_id,
                            'customer_id'  => $costomer->id,
                            'searching_id' => $searching_id,
                            'joint_id'     => $joint->id,
                            'real_id'      => $real->id,
                            'fun_id'       => $fun->id,
                            'statusReq'    => 0,
                            'agent_date'   => carbon::now(),
                        ]);
                        $success++;
                        $notify = MyHelpers::addNewNotify($request->id, $request->user_id); // to add notification
                        $record = MyHelpers::addNewReordExcel($request->id, $request->user_id); // to add new history record
                        //$emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $request->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification
                    }
                }
                else {
                    $error++;
                    continue;
                }
            }
        }

        return back()->with(['excelCount' => $success, 'countRow' => $success + $error]);
    }

    public function findNextAgent()
    {
        $last_req_id = DB::table('requests')->max('id'); // latest request_id
        $last_req = DB::table('requests')->where('id', $last_req_id)->get(); // latest request object
        $last_user_id = $last_req[0]->user_id;
        $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
        }

        return $user_id;
    }

    public function findNextAgentWithEntred($agentExcel)
    {
        $last_req_id = DB::table('requests')->whereIn('user_id', $agentExcel)->max('id');
        $last_req = DB::table('requests')->where('id', $last_req_id)->get();
        $last_user_id = $last_req[0]->user_id;
        $maxValue = DB::table('users')->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
        }

        return $user_id;
    }

    //**************************************************************************
    // Excel TestImport Ends
    //**************************************************************************

    public function requestWithoutUpdatePage()
    {

        $allwoedHourToLeaveToLeaveRequestWithoutUpdate = DB::table('settings')->where('option_name', 'time_requestWithoutUpdate')->first();
        $classRequestWithoutUpdate = DB::table('classification_for_request_without_update');
        $getAgentClass = DB::table('classifcations')->where('user_role', 0)->get();

        return view('Settings.requestWithoutUpdate.requestWithoutUpdateCondition', compact('allwoedHourToLeaveToLeaveRequestWithoutUpdate', 'classRequestWithoutUpdate', 'getAgentClass'));
    }

    public function updateRequestWithoutUpdate(Request $request)
    {
        $deleteClass = DB::table('classification_for_request_without_update')->delete();

        $classifcations = $request->input('classifcations', []);
        $timeToLeave = $request->hourToLeave;

        $updateTimeToLeave = DB::table('settings')->where('option_name', 'time_requestWithoutUpdate')->update(['option_value' => $timeToLeave]);

        if ($classifcations) {
            foreach ($classifcations as $classifcation) {
                $result = DB::table('classification_for_request_without_update')->insert([
                    [
                        'class_id' => $classifcation,
                    ],

                ]);
            }
        }

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function importExcelForTwoCloumnsPage()
    {
        return view('Admin.Excel.importExcelWithTwoCloumns');
    }

    function importExealForTwoCloumns(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:xls,xlsx,csv,txt',
        ];

        $customMessages = [
            'file' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $data = Excel::toArray(new excelTwoCloumns, $request->file('file'));
        $success = 0;
        $error = 0;

        foreach ($data[0] as $row) {

            $rules1 = [
                'mobile' => ['numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            ];
            $rules2 = [
                'mobile' => ['numeric', 'unique:customers_phones', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            ];

            $validator1 = Validator::make(['mobile' => $row['number']], $rules1);
            $validator2 = Validator::make(['mobile' => $row['number']], $rules2);

            $customer = customer::where('mobile', $row['number'])->count();
            $phones = CustomersPhone::where('mobile', $row['number'])->count();
            if ($validator1->fails() || $validator2->fails() || $customer > 0 || $phones > 0) {
                $error++;
                continue;
            }
            else {
                $row['number'] = substr($row['number'], -9);
                $userInfo = User::where('name_in_callCenter', $row['agent'])->first();

                if ($userInfo) {
                    $user_id = $userInfo->id;
                }

                else //stop the function if user not existed
                {
                    return back()->with(['error' => 'توقفت العملية ، لأن اسم : '.$row['agent'].'غير موجود .']);
                }

                if ($customer == 0 && $phones == 0) {
                    $costomer = customer::firstOrCreate([
                        'mobile' => $row['number'],
                    ], [
                        'name'    => 'بدون اسم',
                        'mobile'  => $row['number'],
                        'user_id' => $user_id,
                    ]);
                    //***********************************************
                    // Task-29 Check If Customer Has Pending Request
                    // If Has Don't Create New One
                    //***********************************************
                    $binding = PendingRequest::where(['customer_id' => $costomer->id])->count();
                    if ($binding == 0) {
                        $joint = joint::create([]);

                        $real = real_estat::create([]);

                        $fun = funding::create([]);

                        $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
                        $searching_id = RequestSearching::create()->id;

                        $request = req::firstOrCreate([
                            'customer_id' => $costomer->id,
                        ], [
                            'source'       => 10,
                            'req_date'     => $reqdate,
                            'user_id'      => $user_id,
                            'customer_id'  => $costomer->id,
                            'searching_id' => $searching_id,
                            'joint_id'     => $joint->id,
                            'real_id'      => $real->id,
                            'fun_id'       => $fun->id,
                            'statusReq'    => 0,
                            'agent_date'   => carbon::now(),
                        ]);

                        $success++;
                        $notify = MyHelpers::addNewNotify($request->id, $request->user_id); // to add notification
                        $record = MyHelpers::addNewReordExcel($request->id, $request->user_id); // to add new history record
                        //$emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $request->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');//email notification
                    }
                }
                else {
                    $error++;
                    continue;
                }
            }
            //******************************************************************
        }
        return back()->with(['excelCount' => $success, 'countRow' => $success + $error]);
    }

    public function formula()
    {
        $users = EditCalculationFormulaUser::where(['auth' => 0, 'type' => 0])->get();
        $auths = EditCalculationFormulaUser::where(['auth' => 1, 'type' => 0])->get();

        $agents_auth = DB::table('users')->where('status', 1)->whereNotIn('id', $users->pluck('user_id')->toArray())->where('role', '!=', 7)->get();

        $agents_unauth = DB::table('users')->where('status', 1)->whereNotIn('id', $auths->pluck('user_id')->toArray())->where('role', '!=', 7)->get();

        return view('Settings.formula.index', compact('users', 'agents_unauth', 'agents_auth', 'auths'));

    }

    public function formulaAgents(Request $request)
    {
        //dd($request->agents);
        if (count(array_merge($request->agents, $request->authorizes)) > 0) {
            $all = array_merge($request->agents, $request->authorizes);
            $array = EditCalculationFormulaUser::pluck('user_id')->where('type', 0)->toArray();
            $diff = array_diff($array, $all);

            if (count($diff) != 0) {
                EditCalculationFormulaUser::whereIn('user_id', $diff)->where('type', 0)->delete();
            }
        }

        if ($request->has('agents')) {
            foreach ($request->agents as $agent) {
                $data = [
                    'user_id' => $agent,
                    'type'    => 0,
                ];
                EditCalculationFormulaUser::firstOrCreate($data, [
                    'user_id' => $agent,
                    'type'    => 0,
                    'auth'    => 0,
                ]);
            }
        }
        if ($request->has('authorizes')) {
            foreach ($request->authorizes as $agent) {
                $data = [
                    'user_id' => $agent,
                    'type'    => 0,
                ];
                EditCalculationFormulaUser::firstOrCreate($data, [
                    'user_id' => $agent,
                    'type'    => 0,
                    'auth'    => 1,
                ]);
            }
        }
        else {
            $array = EditCalculationFormulaUser::orderBy('id')->where('type', 0)->delete();
        }
    }

    public function formulaResults()
    {
        $users = EditCalculationFormulaUser::where(['auth' => 0, 'type' => 1])->get();

        $agents_unauth = DB::table('users')->where('status', 1)->where('role', '!=', 7)->get();

        return view('Settings.formulaResults.index', compact('users', 'agents_unauth'));

    }

    public function formulaResultsAgents(Request $request)
    {
        $array = EditCalculationFormulaUser::where('type', 1)->delete();

        if ($request->has('agents')) {
            foreach ($request->agents as $agent) {
                $data = [
                    'user_id' => $agent,
                    'type'    => 1,
                ];
                EditCalculationFormulaUser::firstOrCreate($data, [
                    'user_id' => $agent,
                    'type'    => 1,
                    'auth'    => 0,
                ]);
            }
        }
    }

    public function showToGuestCustomer()
    {

        $showToGuestCustomerStatus = DB::table('settings')->where('option_name', 'property_showToGuestCustomer')->first(); //if it active or not

        if (auth()->user()->role == '7') {
            return view('Settings.Property.showToGuestCustomer', compact('showToGuestCustomerStatus'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function updateshowToGuestCustomer()
    {

        $active = DB::table('settings')->where('option_name', 'property_showToGuestCustomer')->where('option_value', 'false')->update(['option_value' => 'true']);

        if (!$active) {
            $active = DB::table('settings')->where('option_name', 'property_showToGuestCustomer')->where('option_value', 'true')->update(['option_value' => 'false']);
        }

        if ($active) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function days_of_resubmit()
    {
        if (auth()->user()->role == '7') {
            $fields = DB::table('settings')->where('option_name', 'LIKE', 'request_resubmit_days')->first();
            $prefix = 'request_resubmit_days';
            return view('Settings.ReSubmitDays.index', compact('fields', 'prefix'));
        }
        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }
}
