<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\Helpers\MyHelpers;
use App\Models\User;
use Illuminate\View\View;

class QualityComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return [
            'Admin.Request.needActionReqsNew',
            'V2.Reports.report5',
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): View
    {
        $QualitySelect = User::where([
            'role'   => 5,
            'status' => 1,
        ])->get()->map(function (User $user) {
            $name = $user->name;
            $name .= ($name ? ' - ' : '').($user->name_for_admin ?: '');
            $name = trim($name, '- ');
            $user->name = $name;
            return $user;
        })->toArray();
        $QualityBasketsSelect = MyHelpers::QualityBasketsSelect()->toArray();
        $QualityRequestStatusSelect = MyHelpers::QualityRequestStatusSelect()->toArray();
        return $view->with([
            'QualitySelect'              => $QualitySelect,
            'QualityBasketsSelect'       => $QualityBasketsSelect,
            'QualityRequestStatusSelect' => $QualityRequestStatusSelect,
        ]);
    }
}
