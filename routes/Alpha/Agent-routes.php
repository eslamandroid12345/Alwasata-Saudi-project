<?php
/**
 * @Author: Ahmed Fayez
 **/

use App\Http\Controllers\V2\Admin\AgentController;

Route::group(['prefix' => 'Agent', 'as' => 'Agent.', 'middleware' => ['auth', 'salesagent', 'logout']], function () {
    Route::get('my-chat', [AgentController::class, 'myChat'])->name('myChat');
});
