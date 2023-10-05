<?php

namespace App\Composers;

use App\editCoulmnsSettings;

class EditColumnsSettingComposer
{

    public function compose($view)
    {

        $editCoulmns = editCoulmnsSettings::where('tableName','underReqsTable')
            ->where('user_id',auth()->user()->id)->get();
        //Add your variables
        $view->with([
            'editCoulmns' => $editCoulmns
        ]);
    }
}
