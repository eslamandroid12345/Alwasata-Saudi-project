<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\ClassificationAlertSettingDataTable as DataTable;
use App\Http\Controllers\AppController;
use App\Models\ClassificationAlertSetting as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassificationAlertSettingController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
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
