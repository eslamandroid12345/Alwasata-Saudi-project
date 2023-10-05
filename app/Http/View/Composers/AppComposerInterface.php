<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use Illuminate\View\View;

interface AppComposerInterface
{
    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): View;
}
