<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\UserDataTable as DataTable;
use App\Http\Controllers\AppController;
use App\Models\User as Model;
//use Illuminate\Http\Request;

class UserController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
    }

    public function homePage(){
        if(auth()->check()){
            switch (auth()->user()->role){
                case 13:
                    return redirect()->route('V2.ExternalCustomer.index');
            }
        }
        die(__("messages.noPermissions"));
    }
}
