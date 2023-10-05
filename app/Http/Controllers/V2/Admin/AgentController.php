<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\ClassificationAlertSettingDataTable as DataTable;
use App\Http\Controllers\AppController;
use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AgentController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
    }

    /**
     * Show agent chat from app
     */
    public function myChat()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        return view('V2.Agent.userMessages', ['redirectUrl' => route('agent.myRequests')]);
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
