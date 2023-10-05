<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use Illuminate\View\View;

class RolesComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return [
            'Admin.Users.addUserPage',
            'Admin.Users.updateUser',
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
        $RolesSelect = [
            [
                'id'   => 0,
                'name' => __("language.Sales_Agent"),
            ],
            [
                'id'   => 1,
                'name' => __("language.Sales Manager"),
            ],
            [
                'id'   => 2,
                'name' => __("language.Funding Manager"),
            ],
            [
                'id'   => 3,
                'name' => __("language.Mortgage Manager"),
            ],
            [
                'id'   => 4,
                'name' => __("language.General Manager"),
            ],
            [
                'id'   => 5,
                'name' => __("language.Quality User"),
            ],
            [
                'id'   => 6,
                'name' => __("language.Collaborator"),
            ],
            [
                'id'   => 7,
                'name' => __("language.Admin"),
            ],
            [
                'id'   => 8,
                'name' => __("language.Accountant"),
            ],
            [
                'id'   => 9,
                'name' => __("language.Quality Manager"),
            ],
            [
                'id'   => 11,
                'name' => __("language.Training"),
            ],
            [
                'id'   => 12,
                'name' => __("language.Hr"),
            ],
            [
                'id'   => 13,
                'name' => __("global.bankDelegate"),
            ],
        ];
        return $view->with([
            'RolesSelect' => $RolesSelect,
        ]);
    }
}
