<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use App\Models\Classification;
use App\Models\RequestSource;
use App\Models\WelcomeMessageSetting;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WelcomeMessageSettingController extends Controller
{
    public function index()
    {
        $welcomeMessages = WelcomeMessageSetting::all();
        return view('Admin.WelcomeMessages.index', compact('welcomeMessages'));
    }

    public function indexDataTable()
    {
        $welcomeMessages = WelcomeMessageSetting::with('requestSources', 'classifications');

        return Datatables::of($welcomeMessages)
            ->setRowId(fn($row) => $row->id)
            ->addColumn('action', function ($row) {
                $data = '<div class="tableAdminOption">';
                $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editWelcomeMessage" href="'.url('admin/setting/welcome-messages/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
                $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

                return $data.'</div>';
            })
            ->editColumn('request_source_id', fn($row) => $row->requestSources->pluck('value')->implode(','))
            ->editColumn('classification_id', fn($row) => $row->classifications->pluck('value')->implode(','))
            ->make(true);
    }

    public function addPage()
    {
        $requestSources = RequestSource::all();
        return view('Admin.WelcomeMessages.add', compact('requestSources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classification_id' => ['nullable', 'array', Rule::exists(Classification::getModelTable(), 'id')],
            'request_source_id' => ['nullable', 'array', Rule::exists(RequestSource::getModelTable(), 'id')],
            'welcome_message'   => ['required',],
            'time'              => ['required',],
        ]);
        /** @var WelcomeMessageSetting $setting */
        $setting = WelcomeMessageSetting::create([
            'welcome_message' => $request->welcome_message,
            'time'            => $request->time,
        ]);
        $setting->classifications()->sync($request->get('classification_id'));
        $setting->requestSources()->sync($request->get('request_source_id'));
        return redirect()->route('admin.welcomeMessage')->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
    }

    public function edit($id)
    {
        $welcomeMessage = WelcomeMessageSetting::findOrFail($id);
        $requestSources = RequestSource::all();
        return view('Admin.WelcomeMessages.edit', compact('welcomeMessage', 'requestSources'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'classification_id' => ['nullable', 'array', Rule::exists(Classification::getModelTable(), 'id')],
            'request_source_id' => ['nullable', 'array', Rule::exists(RequestSource::getModelTable(), 'id')],
            'welcome_message'   => ['required',],
            'time'              => ['required',],
        ]);
        /** @var WelcomeMessageSetting $model */
        $model = WelcomeMessageSetting::query()->findOrFail($request->get('id'));
        $model->update([
            'welcome_message' => $request->welcome_message,
            'time'            => $request->time,
        ]);
        $model->classifications()->sync($request->get('classification_id'));
        $model->requestSources()->sync($request->get('request_source_id'));
        return redirect()->route('admin.welcomeMessage')
            ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function destroy(Request $request)
    {
        WelcomeMessageSetting::where('id', $request->id)->delete();
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => 1]);
    }
}
