<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use Illuminate\View\View;

class LanguageComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return [
            'Admin.Users.addUserPage',
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
        $LanguagesSelect = [
            [
                'id'   => 'ar',
                'name' => __("language.Arabic"),
            ],
            [
                'id'   => 'en',
                'name' => __("language.English"),
            ],
        ];
        return $view->with([
            'LanguagesSelect' => $LanguagesSelect,
        ]);
    }
}
