<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\Models\Bank;
use Illuminate\View\View;

class BankComposer implements AppComposerInterface
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
            'Admin.Users.myUsers',
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
        $BanksSelect = Bank::all()->toArray();
        return $view->with([
            'BanksSelect' => $BanksSelect,
        ]);
    }
}
