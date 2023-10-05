<?php
/**
 * @Author: Ahmed Fayez
 **/

use App\Http\Controllers\V2\Admin\AdminController;
use App\Http\Controllers\V2\Admin\ClassificationAlertSettingController;
use App\Http\Controllers\V2\Admin\FreezeRequestController;
use App\Http\Controllers\V2\Admin\SettingController;
use App\Http\Controllers\V2\Admin\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'Admin', 'as' => 'Admin.'], function () {
    Route::group(['middleware' => ['admin', 'auth', 'logout']], function () {
        Route::group(['prefix' => 'User/{UserId}'], function () {
            Route::get('App-Messages', [AdminController::class, 'userMessages'])->name('userMessages');
        });
    });
    Route::group(['middleware' => ['auth', 'adminAndGeneralManager', 'logout']], function () {
        Route::group(['namespace' => "\\"], function () {
            Route::resource('ClassificationAlertSetting', ClassificationAlertSettingController::class);
        });
        Route::get('report1', [AdminController::class, 'report1'])->name('report1');
        Route::get('report2', [AdminController::class, 'report2'])->name('report2');
        Route::get('requests-table', [AdminController::class, 'requests_table'])->name('requests_table');
        Route::get('report3', [AdminController::class, 'report3'])->name('report3');
        Route::get('report4', [AdminController::class, 'report4'])->name('report4');
        //updateBasketSetting
        Route::group(['prefix' => 'Setting', 'as' => 'Setting.'], function () {
            Route::put('', [SettingController::class, 'updateSetting'])->name('updateSetting');
            Route::post('updateTransBasketSetting', [SettingController::class, 'updateTransBasketSetting'])->name('updateTransBasketSetting');
        });

        Route::group(['prefix' => 'FreezeRequest', 'as' => 'FreezeRequest.'], function () {
            //Route::get('', [FreezeRequestController::class, 'index'])->name('index');
            Route::get('', [FreezeRequestController::class, 'listView'])->name('index');
            Route::get('datatable', [FreezeRequestController::class, 'listViewDatatable'])->name('datatable');
        });

        Route::group(['prefix' => 'Statistics', 'as' => 'Statistics.'], function () {
            Route::get('Classification', [StatisticsController::class, 'classifications'])->name('classifications');
        });
    });
});
