<?php

namespace App\Composers;

use App\classifcation;


class ClassificationsComposer
{

    public function compose($view)
    {
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();


        //Add your variables
        $view->with([
            'classifcations_sa' => $classifcations_sa,
            'classifcations_sm' => $classifcations_sm,
            'classifcations_fm' => $classifcations_fm,
            'classifcations_mm' => $classifcations_mm,
            'classifcations_gm' => $classifcations_gm,
        ]);
    }
}
