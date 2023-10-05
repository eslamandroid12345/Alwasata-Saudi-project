<?php

use Illuminate\Support\Facades\Route;

Route::group([ 'prefix' => 'HumanResource', 'as' => 'HumanResource.', 'middleware' => ['auth', 'hr:admin', 'logout'] ],function(){
    Route::group(['namespace' => 'HumanResource'] ,function (){
        Route::get('/dashboard','DashboardController@dashboard')->name('dashboard');
        Route::get('/users', 'UserController@allUsers')->name('users.index');
        Route::post('/profile/personal', 'UserController@personalUpdate')->name('profile.personal.post');

        Route::get('/user/profile/{user}/{pdf?}', 'UserController@profile')->name('user.profile');
        Route::get('/users-datatable', 'UserController@allUsers_datatable')->name('users.datatable');
        Route::post('fetch-subsection', 'UserController@subsections')->name('subsections.fetch');
        Route::get('/{type}-file/{id}', 'UserController@openDownloadFile')->name('openDownloadFile');
        Route::post('/file-upload', 'UserController@FileUpload');
        Route::delete('/file-delete/{id}', 'UserController@FileDelete');
        Route::get('/file-restore/{id}', 'UserController@FileRestore');

        Route::resource('phones', 'AddPhonesController');
        Route::get('/addUserPage', 'UserController@addUserPage')->name('addUserPage');
        Route::post('/addUserPost', 'UserController@addUser')->name('addUser');

        Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');
        Route::post('/phones-check', 'AddPhonesController@checkMobileEmployee')->name('checkMobile');

        // ===================================== job applications ====================================================
        Route::resource('job_applications','JobApplicationController');
        Route::get('job_applications_datatable','JobApplicationController@datatable')->name('job_applications_datatable');

        // ===================================== agent daily performance ==============================================
        Route::get('dailyprefromence', 'ChartController@dailyPrefromence')->name('hr_dailyPrefromence');
        Route::get('ajax-chart-line', ['uses'=>'ChartController@ajaxdailyPrefromence']);
        Route::get('daily-performances', 'ChartController@report')->name('daily.report');
        Route::get('daily-performances-sum', 'ChartController@reportSum')->name('daily.report.sum');
        Route::get('daily-statistics-pdf/{id}/{srt}/{end}', 'ChartController@pdfStatistics')->name('daily.statistics.pdf');


    });
});

