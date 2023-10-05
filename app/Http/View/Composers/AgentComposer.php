<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\View\View;

class AgentComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return [
            'V2.Admin.report2',
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
        $AgentsSelect = User::where([
            'role'   => 0,
            'status' => 1,
        ])->get()->toArray();
        return $view->with([
            'AgentsSelect' => $AgentsSelect,
        ]);
    }
}
