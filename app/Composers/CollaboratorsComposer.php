<?php

namespace App\Composers;

use DB;
use App\User ;
use App\user_collaborator as Collaborator ;

class CollaboratorsComposer
{

    public function compose($view)
    {
         $coll = Collaborator::with('user')->pluck('collaborato_id'); // to prevent dublicate

//        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
//            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
//            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = User::whereIn('id', $coll)->get();

        //Add your variables
        $view->with([
            'collaborators' => $collaborators
        ]);
    }
}
