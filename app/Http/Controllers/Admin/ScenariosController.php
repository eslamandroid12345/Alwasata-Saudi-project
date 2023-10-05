<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Scenario;
use App\ScenariosUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use View;

class ScenariosController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }

    public static function reorder()
    {

        if (count(request()->json()->all())) {
            $ids = request()->json()->all();

            foreach ($ids as $i => $key) {
                $id = $key['id'];
                $position = $key['position'];
                $mymodel = Scenario::find($id);
                $mymodel->sort_id = $position;
                $mymodel->save();
            }
            $response = 'send response records updated goes here';
            return response()->json($response);
        }
        else {
            $response = 'send nothing to sort response goes here';
            return response()->json($response);
        }
    }

    public function index()
    {
        return view('Admin.scenarios.index');
    }

    public function users($id)
    {
        $scenario = Scenario::find($id);
        $agents = DB::table('users')->where('role', 0)->where('status', 1)->get();

        return view('Admin.scenarios.users', compact('scenario', 'agents'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'إسم السيناريو  مطلوب *',
        ]);
        $input['sort_id'] = Scenario::count() + 1;
        if ($validator->passes()) {
            $input = $request->all();
            Scenario::create($input);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة السيناريو ',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);

    }

    public function edit($id)
    {
        return Scenario::findOrFail($id);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'إسم السيناريو  مطلوب *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            $scenario = Scenario::findOrFail($request->id);
            $scenario->update($input);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل السيناريو ',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $scenario = Scenario::find($id);
        $scenario->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم حذف السيناريو  ',
        ]);
    }

    public function check(Request $request)
    {
        $users = ScenariosUsers::whereIn('user_id', $request->agents)
            ->where('scenario_id', '<>', $request->scenario_id)
            ->get();
        $names = '';

        if ($users->count() != 0) {
            $status = 1;
            foreach ($users as $key => $user) {
                if ($key == ($users->count() - 1)) {
                    $names .= $user->user->name;
                }
                else {
                    $names .= $user->user->name.'-';
                }

            }
        }
        else {
            $status = 0;
        }

        return response()->json([
            'status' => $status,
            'names'  => $names,
        ]);
    }

    public function agents(Request $request)
    {
        $deleted = ScenariosUsers::whereIn('user_id', $request->agents)
            ->where('scenario_id', '<>', $request->scenario_id)
            ->delete();

        if ($request->has('agents')) {
            $array = ScenariosUsers::where('scenario_id', $request->scenario_id)->pluck('user_id')->toArray();
            $diff = array_diff($array, $request->agents);

            if (count($diff) != 0) {
                ScenariosUsers::whereIn('user_id', $diff)
                    ->where('scenario_id', $request->scenario_id)
                    ->delete();
            }
        }

        if ($request->has('agents')) {
            foreach ($request->agents as $agent) {
                $data = [
                    'user_id'     => $agent,
                    'scenario_id' => $request->scenario_id,
                ];

                ScenariosUsers::firstOrCreate($data, $data);
            }
        }
        else {
            if (ScenariosUsers::where('scenario_id', $request->scenario_id)->count() > 0) {
                ScenariosUsers::where('scenario_id', $request->scenario_id)->delete();
            }
        }
        session()->flash('success', 'تم بنجاح تعديل إعدادت الحسبة لهذا السيناريو ');
        return redirect()->back();
    }

}
