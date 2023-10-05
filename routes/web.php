<?php

use App\User;
use App\Employee;
use App\DailyLogs;
use Carbon\Carbon;
use App\Helpers\MyHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V2\Admin\RequestController;

//to take date
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/** Alwsata Alpha web routes */
require_once __DIR__."/alpha.php";


/* Frontend Routes for guest*/

//Route::get('/tests', function () {
    //return view('emails.postponed_communication_email');
    //$employee = Employee::first();
    //$customer = \App\Models\Customer::find(4);
    //dd($customer->getPushTokens());
//});
Route::get('/', function () {
    return view('Frontend.index');
});
Route::get('/set-0-performance', function () {
    $start = Carbon::parse(date('Y-m-d', strtotime('2022-04-02')));
    $end = Carbon::parse(date('Y-m-d', strtotime(now())));
    $dates = [];
    for ($d = $start; $d->lte($end); $d->addDay()) {
        $dates[] = $d->format('Y-m-d');
    }
    $users = User::where(["role" => 0])->get();
    foreach ($users as $user) {
        foreach ($dates as $date) {
            \App\DailyPerformances::where([
                "today_date" => $date,
                "user_id"   => $user->id
            ])->update(["completed_request" => 0]);
        }
    }

    return  "Set 0 Successfully ;)";
});
Route::get('/update-daily-performance', function () {
    $start = Carbon::parse(date('Y-m-d', strtotime('2022-04-02')));
    $end = Carbon::parse(date('Y-m-d', strtotime(now())));
    $dates = [];
    for ($d = $start; $d->lte($end); $d->addDay()) {
        $dates[] = $d->format('Y-m-d');
    }
    //  $users = User::where(["role" => 0])->get();
    $requestsData = \App\Models\Request::whereDate("updated_at",">","2022-04-01")
        ->where("user_id","<>",null)
        ->select("id","user_id")
        ->toBase()->get();

    foreach ($requestsData as $key=>$req) {
        foreach ($dates as $date) {
            $count = DB::table('req_records')->where([
                'value'          => 58,
                'colum'          => 'class_agent',
            ])
                ->where("req_id",$req->id)
                ->whereDate("updateValue_at", $date)->count();
            // Increament Data
            if($req->user_id != null){
                $dailyperformance = \App\DailyPerformances::firstOrCreate([
                    "today_date" => $date,
                    "user_id"   => $req->user_id
                ]);
                if($count > 0){
                    $dailyperformance->increment("completed_request");

                    $records =DB::table('req_records')->where([
                        'value'          => 58,
                        'colum'          => 'class_agent',
                    ])
                        ->where("req_id",$req->id)
                        ->whereDate("updateValue_at", $date)->get();
                    foreach ($records as $rec) {
                        if($rec->req_id != null){
                            $request = \App\request::find($rec->req_id);
                        }

                        DailyLogs::create([
                            'user_id'=> $request->user_id ?? null,
                            'request_id'=> $request->id ?? null,
                            'event'=> "completed_request",
                            'today_date' => $date,
                            'request_type'  => 'request',
                        ]);
                    }
                }
            }
        }
    }
    return "success , daily performance updated successfully";


});

Route::get('chart/ajax-chart-line', ['uses'=>'ChartController@ajaxdailyPrefromenceChartR']);
Route::get('chart/ajax-chart-line-quilty', ['uses'=>'ChartController@ajaxdailyPrefromenceQuiltyChartR']);
Route::post('chart/quilty-repoert-chart', 'ChartController@quiltyRepoertChart')->name('quiltyRepoertChart');

Route::get('/customer/set-date/{request_id}', [\App\Http\Controllers\V2\Request\RequestClassificationController::class,'index']);
Route::get('/customer/postponed-status/{request_id}', [\App\Http\Controllers\V2\Request\RequestClassificationController::class,'postponedStatus']);
Route::get('/customer/thank-you/', [\App\Http\Controllers\V2\Request\RequestClassificationController::class,'thankPage'])->name('thank-you');
Route::post('/customer/postponed-communication/', [\App\Http\Controllers\V2\Request\RequestClassificationController::class,'store'])->name('postponed_new_date');


//----------------------------------------------------------------
Route::group(['prefix' => 'ar', 'namespace' => 'Frontend', 'middleware' => 'PropertyShowToGuestCustomer'], function () {
    Route::get('properties', 'PageController@properties')->name('home');
    Route::get('details-guest/{id}', 'PageController@property')->name('propertyDetails.guest');
    //Route::post('request-property', 'HomeController@requestProperty')->name('requestProperty');
});
//----------------------------------------------------------------

//----------------Frontend Pages--------------------//
Route::get('/realestates', 'Frontend\PageController@newPage')->name('newPage');

Route::get('/404', 'Frontend\PageController@get404Page')->name('missing');
//Route::get('ar/{page}', 'Frontend\PageController@getIndex')->name('frontend.index');
//Route::get('/duplicate_customer/{id?}', 'Frontend\PageController@duplicateCustomer')->name('duplicateCustomer');
//Route::get('/thankyou/{id?}', 'Frontend\PageController@thankyou')->name('thankyou');
Route::get('/', 'Frontend\PageController@homePageOfWebsite');
Route::get('ar/about-us', 'Frontend\PageController@aboutUs');
Route::get('ar/contact-us', 'Frontend\PageController@contactUs');
Route::get('ar/my-requests', 'Frontend\PageController@myOrders');
Route::get('ar/app', 'Frontend\PageController@fundingcalculator');

Route::get('ar/otpverify', 'Frontend\PageController@otpverify')->name('mobileOTP');
Route::get('ar/otpverifypage', 'Frontend\PageController@otpverifypage')->name('mobileOTPPage');

Route::get('ar/sendsmsotp', 'Frontend\PageController@sendSmsOtp')->name('sendSMSotp');
Route::post('ar/checkotpcode', 'Frontend\PageController@checkotpCode')->name('checkotpCode');

Route::get('ar/setNewPasswordPage', 'Frontend\PageController@setNewPasswordPage')->name('setNewPasswordPage');
Route::post('ar/setNewPassword', 'Frontend\PageController@setNewPassword')->name('setNewPassword');

Route::get('ar/modifyMobilePage', 'Frontend\PageController@modifyMobilePage')->name('modifyMobilePage');
Route::post('page/modifyMobilePost', 'Frontend\PageController@modifyMobilePost')->name('modifyMobilePost');
//Route::get('ar/askforconsultant', 'Frontend\PageController@askForConsltantPage');
Route::get('ar/setsessionmobile', 'Frontend\PageController@setSessionMobileNumber')->name('setSessionMobileNumber');

Route::get('ar/request_service', 'Frontend\PageController@askForFundingPage');
Route::get('ar/col/{url}', 'Frontend\PageController@askForFundingPage');

Route::get('ar/help_desk', 'Frontend\PageController@helpDeskPage')->name('frontend.page.helpDesk');
Route::get('ar/privacy_policy', 'Frontend\PageController@privacyPage');
Route::get('/thankyou', 'Frontend\PageController@thankyou')->name('thankyou');
Route::get('/thanks', 'Frontend\PageController@thanksForHelpDesk')->name('thanksForHelpDesk');
Route::get('/duplicate_customer', 'Frontend\PageController@duplicateCustomer')->name('duplicateCustomer');
Route::get('page/order/get_status', 'Frontend\PageController@postCheckOrderStatus')->name('frontend.page.check_order_status');
Route::post('page/consultancy_request', 'Frontend\PageController@postConsultancyRequest')->name('frontend.page.consultancy_request');
Route::post('page/funding_request', 'Frontend\PageController@postFundingRequest')->name('frontend.page.funding_request');
Route::post('page/save_loan_request', 'Frontend\PageController@postSaveLoanRequest')->name('frontend.page.save_loan_request');
Route::post('page/ask_help_desk', 'Frontend\PageController@postHelpDesk')->name('postHelpDesk');
Route::get('/update_soical_clicktimes', 'Frontend\PageController@update_soical_clicktimes')->name('update_soical_clicktimes');

Route::post('/request-property', 'Frontend\HomeController@requestProperty')->name('requestProperty');
Auth::routes();


//====================start jobs routes==============================
//----front---------
Route::get('careers', 'Frontend\JobController@NewPage')->name('careers');//route after change page design
Route::get('ar/job-submitted', 'Frontend\JobController@JobThankYou')->name('job-submitted');
Route::get('ar/job_application_apply', 'Frontend\JobController@index')->name('job_application_apply');//old route
Route::post('ar/apply_for_job', 'Frontend\JobController@apply_for_job')->name('apply_job');

//-----dashboard----------
Route::group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => ['auth','admin']], function () {
    Route::resource('job_titles','JobController');
    Route::get('job_titles_datatable','JobController@datatable')->name('job_titles_datatable');

    Route::resource('nationality','NationalityController');
    Route::get('nationality_datatable','NationalityController@datatable')->name('nationality_datatable');

    Route::resource('university','UniversityController');
    Route::get('university_datatable','UniversityController@datatable')->name('university_datatable');

    Route::resource('job_applications','JobApplicationController');
    Route::get('job_applications_datatable','JobApplicationController@datatable')->name('job_applications_datatable');

    Route::resource('job_applications_types','JobApplicationTypesController');
    Route::get('job_applications_types_datatable','JobApplicationTypesController@datatable')->name('job_applications_types_datatable');
});
//====================end jobs routes==============================


Route::POST('/search_user_account', 'Admin\SearchController@search_user_account')->name('search_user_account');


// Route::get('/login/customer', 'Auth\LoginController@showCustomerLoginForm')->name('customer.login');
// Route::post('/login/customer', 'Auth\LoginController@customerLogin')->name('customer.loginPost');

//-----------------------------------------------------------------
// #Task-03# Cancele req in customer Route
//-----------------------------------------------------------------
Route::group(['namespace' => 'Reports'], function () {

    Route::get('daily-performances', 'DailyPerformancesService@report')->name('daily.report');
    Route::get('daily-performances-quality', 'DailyPerformancesService@reportQuality')->name('daily.report.quality');
    Route::get('daily-performances-sum', 'DailyPerformancesService@reportSum')->name('daily.report.sum');
    Route::get('daily-statistics-pdf/{id}/{srt}/{end}', 'DailyPerformancesService@pdfStatistics')->name('daily.statistics.pdf');

    Route::get('requests-report-status', 'RequestsService@status')->name('requests.report.status');
    Route::get('requests-report-status-sum', 'RequestsService@statusSum')->name('requests.report.status.sum');
    Route::get('requests-report-basket', 'RequestsService@basket')->name('requests.report.basket');
    Route::get('requests-report-classification', 'RequestsService@classification')->name('requests.report.classification');
    Route::get('requests-report-classification-sum', 'RequestsService@classificationSum')->name('requests.report.classification.sum');

    Route::get('report-sources-sum', 'SourcesService@sourcesSum')->name('report.sources.sum');
    Route::get('report-sources-wsata-sum', 'SourcesService@sourcesWsataSum')->name('report.sources.wsata.sum');


    Route::get('sources-report', 'SourcesService@sources')->name('report.sources');
    Route::get('sources-wsata-report', 'SourcesService@sourcesWsata')->name('report.sources.wsata');
    Route::get('sources-pendings-report', 'SourcesService@sourcesPending')->name('report.sources.pending');
    Route::get('sources-pdf/{id}/{srt}/{end}', 'SourcesService@pdf')->name('sources.pdf');

    Route::get('requests-pdf/{id}/{srt}/{end}', 'RequestsService@pdf')->name('requests.statistics.pdf');
    Route::get('requests-all-pdf/{id}/{srt?}/{end?}', 'RequestsService@pdfAll')->name('requests.statistics.all.pdf');
    Route::get('requests-status-pdf/{id}/{srt?}/{end?}', 'RequestsService@pdfStatus')->name('requests.statistics.status.pdf');
    Route::get('requests-classification-pdf/{id}/{srt?}/{end?}', 'RequestsService@pdfClassification')->name('requests.statistics.classification.pdf');
    Route::get('requests-basket-pdf/{id}/{srt?}/{end?}', 'RequestsService@pdfBasket')->name('requests.statistics.basket.pdf');
});
Route::group(['namespace' => 'Admin'], function () {
    Route::get('asks', 'AsksService@asks')->name('canclereq.asks');
    Route::get('files/{employeeId}/{role}', 'EmployeeFilesService@files')->name('employee.files');
    Route::get('files-archives/{employeeId}/{role}', 'EmployeeFilesService@archives')->name('employee.files.archives');
    Route::get('rejections', 'RejectionsService@rejections')->name('requests.rejections');
    Route::get('answers', 'ServeriesCancelingService@answers')->name('canclereq.answers');

    // Task-05#
    //----------------------------------------------------------------
    Route::get('scenarios', 'ScenariosService@scenarios')->name('scenarios.index');
    Route::get('guests/users', 'HasbahRequestsService@guests')->name('api.guests.index');
});
//-----------------------------------------------------------------

Route::group(['as' => 'customer.', 'middleware' => ['auth:customer']], function () {
    //---------------Customer Routes---------------------
    Route::get('/customer', 'CustomerController@index')->name('account');

    //Customer Route Surveys
    Route::get('/customer', 'CustomerController@index')->name('account');
    Route::get('/survey/{requestId}', 'AsksAnswersController@index')->name('survey.index');
    Route::get('/reopen/{requestId}', 'AsksAnswersController@reopen')->name('request.reopen');
    Route::post('/survey-store', 'AsksAnswersController@store')->name('survey.store');
    //----------------------------------------------------------------------

    //Reminders CRUD START
    Route::resource('/customer-reminders', 'RemindersController');
    Route::get('customer-notifications', 'RemindersController@IndexNotification')->name('notifications.index');
    Route::get('customer-notification-delete/{markId}', 'RemindersController@destroyNotification')->name('notifications.delete');
    Route::get('customer-notification/{id}/{type?}', 'RemindersController@markNotification')->name('notifications.read');
    //------------------------------------------------------------------------------------------------

    Route::get('/customer/profile', 'CustomerController@customerProfile')->name('profile');
    Route::get('/customer/editprofile', 'CustomerController@editCustomerProfile')->name('editprofile');
    Route::get('/customer/help_desk', 'CustomerController@helpDeskPage')->name('helpDesk');
    Route::post('/customer/updateProfile', 'ProfileController@customerUpdateProfile')->name('updateProfile');
    Route::get('/customer-properties-requests', 'CustomerController@my_reqs')->name('propertyRequests');
    Route::get('/customer/fundingreqpage/{id}', 'CustomerController@fundingreqpage')->name('fundingRequestCustomer');
    Route::post('/uploadfile', 'CustomerController@uploadFile')->name('uploadFile');
    Route::get('/updatefoundproperty', 'CustomerController@updateFoundProperty')->name('updateFoundProperty');
    Route::get('/updatecustomerwanttoreject', 'CustomerController@updateCustomerWantToReject')->name('updateCustomerWantToReject');
    Route::get('/updateresolveproblem', 'CustomerController@updateCustomerResolveProblem')->name('updateCustomerResolveProblem');
    Route::get('/needtoeditreqinfo', 'CustomerController@needToEditReqInfo')->name('needToEditReqInfo');

    Route::get('/customer/downloadfile/{id}', 'CustomerController@downloadFile')->name('downFile');
    Route::get('/customer/openfile/{id}', 'CustomerController@openFile')->name('openFile');

    //COMPLAIN
    Route::get('/complain', 'SuggestionsController@index')->name('complain.page');
    Route::post('/complain', 'SuggestionsController@store')->name('complain.store');
    Route::get('/complain-chat/{complainId}', 'SuggestionsController@chat')->name('complain.chat');
    Route::post('/complain-chat', 'SuggestionsController@chatStore')->name('complain.chatStore');
});

Route::group(['prefix' => 'customer/password'], function () {
    Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('customer.password.request');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('customer.password.email');
    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('customer.password.reset');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('customer.password.update');

    //------------------------------------------------------------------------
    //          SMS-Reset Password
    //------------------------------------------------------------------------
    Route::get('/sms', 'Auth\ForgotPasswordController@showSMSRequestForm')->name('customer.sms.get');
    Route::post('/sms', 'Auth\ForgotPasswordController@sendResetLinkSMS')->name('customer.password.sms');
    Route::get('/sms-verify', 'Auth\ForgotPasswordController@showSMSVerifyRequestForm')->name('customer.sms.verify');
    Route::post('/sms-verify', 'Auth\ForgotPasswordController@CheckSMS')->name('customer.sms.check');
});

Route::group(['middleware' => ['auth:customer,web']], function () {
    //---------------Chating Between Users---------------------
    Route::post('sendMessage', 'MessageController@sendMessage')->name('sendMessage');
    Route::post('sendMessageFirebase', 'MessageController@sendMessageFirebase')->name('sendMessageFirebase');
    Route::get('/delete-message/{id}', 'MessageController@delete')->name('messageDelete');
    Route::get('/downloadfile/{id}', 'MessageController@downloadFile')->name('downFile');
    Route::get('/downloadfilefirebase/{id}', 'MessageController@downloadFileFirebase')->name('downloadFileFirebase');
    Route::get('/openfile/{id}', 'MessageController@openFile')->name('openFile');
    Route::get('/openfilefirebase/{id}', 'MessageController@openFileFirebase')->name('openFileFirebase');
    Route::get('/addfile', 'MessageController@addFile')->name('addFile');
    Route::get('/addfilefirebase', 'MessageController@addFileFirebase')->name('addFileFirebase');
});

Route::group(['middleware' => ['auth:customer']], function () {
    //---------------Chating Between Users---------------------
    Route::post('chat/cust/inbox', 'MessageController@newMessageCustomer')->name('CustomernewChat');
    Route::get('chat/getCustomerMsg/{agent}', 'MessageController@getMessageCustomer')->name('getMessageCustomer');
});

Route::group(['middleware' => ['auth:web']], function () {
    //---------------Chating Between Users---------------------
    Route::get('/chat', 'MessageController@index')->name('chat');
    Route::get('/chat-ajax', 'MessageController@ajax')->name('chat.ajax');
    Route::get('/chat-receivers-ajax-firebase', 'MessageController@ajaxReceiversFirebase')->name('chat.receivers.ajax.firebase');
    Route::get('/chat-receivers-ajax', 'MessageController@ajax_receivers')->name('chat.receivers.ajax');
    Route::post('chat/inbox', 'MessageController@newMessage')->name('newChat');
    Route::get('chat/getUserMsg/{user}/{receiver_model}', 'MessageController@getMessageUser')->name('getMessageUser');
    Route::get('/chat-clients', 'MessageController@indexWithClients')->name('chatWithClients');
    Route::get('/chat-ajax-clients', 'MessageController@ajaxClientchat')->name('chat.client.ajax');
    Route::post('/chat-client-inbox', 'MessageController@getAllMessagesWithSalesAgentAndCustomer')->name('chatClientInbox');
    Route::get('/get-all-customers', 'MessageController@getAllCustomersRelatedToSalesAgent')->name('get.all.customers');
});

Route::group(['prefix' => 'image', 'as' => 'image.', 'middleware' => ['auth:web,customer']], function () {
    //---------------Chating Between Users---------------------
    Route::get('/list/{type}/{id}', 'ImageController@list')->name('list');
    Route::post('/update', 'ImageController@update')->name('update');
    Route::get('/delete/{id}', 'ImageController@delete')->name('delete');
});

route::group(['middleware' => ['auth', 'logout']], function () {

    //---------------User Profile---------------------
    //get & update user profile on ProfileController
    Route::get('/profile', 'ProfileController@profile')->name('profile');
    Route::post('/updateProfile', 'ProfileController@updateProfile')->name('updateProfile');

    //---------------Reminder Calendar ---------------------
    //show user requests reminders
    Route::get('/reminders/{filter?}/{id?}', 'HomeController@calendar')->name('reminders');
    Route::post('/create-reminder', 'HomeController@createReminder')->name('createReminder');
    Route::get('/get-reminder/{id}', 'HomeController@getReminder')->name('getReminder');
    Route::get('/get-req-Type/{id}', 'HomeController@getReqType')->name('getReqType');
    Route::post('/update-reminder', 'HomeController@updateReminder')->name('updateReminder');
    Route::get('/delete-reminder/{id}', 'HomeController@deleteReminder')->name('deleteReminder');
    Route::get('/check-customer-mobile/{mobile}', 'HomeController@checkCustomerMobile')->name('checkCustomerMobile');

    Route::get('/jsonRequests', 'HomeController@jsonRequests')->name('jsonRequests');
    Route::get('/jsonUsers', 'HomeController@jsonUsers')->name('jsonUsers');
    Route::get('/deleteUndefindCustomerRequests', 'HomeController@deleteUndefindCustomerRequests')->name('deleteUndefindCustomerRequests');   /// this used to remove requests or notifications where customer return error or not found

    //ANNOUNCMENT FILE
    Route::get('/openannouncefile/{id}', 'HomeController@openAnnounceFile')->name('openAnnounceFile');
    Route::get('/seenannounce', 'HomeController@seenAnnounce')->name('seenAnnounce');

    // techniqal support
    Route::post('/techniqal-support', 'UsersController@SendTechnicalSupport')->name('techniqal-support');
});

////////////////////////////

route::group(['prefix' => 'calculater', 'as' => 'calculater.',], function () {

    Route::get('/getlastcalculaterdata', 'CalculaterController@getLastcalCulaterData')->name('getLastcalCulaterData');

    Route::post('/calculaterApi', 'CalculaterController@calculaterApi')->name('calculaterApi');
    Route::get('/flexibleSetting', 'CalculaterController@flexibleSetting')->name('flexibleSetting');

    Route::post('/selectcalculaterresult', 'CalculaterController@selectCalculaterResult')->name('selectCalculaterResult');
    Route::get('/noResultOfCalculater', 'CalculaterController@noResultOfCalculater')->name('noResultOfCalculater');

    Route::get('/calculaterHistory/{id}', 'CalculaterController@calculaterHistory')->name('calculaterHistory');

    Route::get('/calculaterGetProductTypes', 'CalculaterController@getProductType')->name('calculaterGetProductTypes');

    Route::get('/getCalculaterResultSettings', 'CalculaterController@getCalculaterResultSettings')->name('getCalculaterResultSettings');
    Route::get('/getAgentCalculaterResultSettings', 'CalculaterController@getAgentCalculaterResultSettings')->name('getAgentCalculaterResultSettings');
});

///////////////////////////

route::group(['prefix' => 'training', 'as' => 'training.', 'middleware' => ['auth', 'training', 'logout']], function () {

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //REQUEST
    Route::get('/myreqs', 'TrainingController@myReqs')->name('myRequests');
    Route::get('/myreqs-datatable', 'TrainingController@myreqs_datatable');

    Route::get('/recivedreqs', 'TrainingController@recivedReqs')->name('recivedRequests');
    Route::get('/recivedreqs-datatable', 'TrainingController@recivedReqs_datatable');

    Route::get('/followreqs', 'TrainingController@followReqs')->name('followedRequests');
    Route::get('/followreqs-datatable', 'TrainingController@followReqs_datatable');

    Route::get('/staredreqs', 'TrainingController@starReqs')->name('staredRequests');
    Route::get('/staredreqs-datatable', 'TrainingController@starReqs_datatable');

    Route::get('/completedreqs', 'TrainingController@completedReqs')->name('completedRequests');
    Route::get('/completedreqs-datatable', 'TrainingController@completedreqs_datatable');

    Route::get('/archreqs', 'TrainingController@archReqs')->name('archRequests');
    Route::get('/archreqs-datatable', 'TrainingController@archReqs_datatable');

    Route::get('chart/requestChartRForTraining', 'ChartController@requestChartRForTraining')->name('requestChartRForTraining');

    Route::get('chart/request-basket-v2', 'ChartController@requestChartTrainingBasketV2')->name('charts.requests.basket');
    Route::get('chart/request-status-v2', 'ChartController@requestChartTrainingStatusV2')->name('charts.requests.status');
    Route::get('chart/request-classification-v2', 'ChartController@requestChartTrainingClassificationV2')->name('charts.requests.classification');

    Route::get('chart/request-training-api', 'ChartController@requestChartTrainingApi')->name('requestChartTrainingApi');

    Route::get('/fundingreqpage/{id}', 'TrainingController@fundingreqpage')->name('fundingRequest');
    Route::get('/morpurpage/{id}', 'TrainingController@morPurpage')->name('morPurRequest');
});

route::group(['middleware' => ['auth', 'adminAndGeneralManager', 'logout']], function () {

    Route::get('/charts-details/{userId}', 'ChartController@user')->name('admin.histories.details');
    Route::get('/request-tree/{requestId}', 'ChartController@request_single')->name('admin.request.tree');

    //---------------Charts---------------------
    Route::get('/charts', 'ChartController@index')->name('charts');

    Route::post('extractCharts', 'ChartController@extractCharts')->name('extractCharts');

    Route::get('chart/dailyprefromence', 'ChartController@dailyPrefromenceChartR')->name('dailyPrefromenceChartR');
    Route::get('chart/dailyprefromence-quality', 'ChartController@dailyPrefromenceChartForQuailityR')->name('dailyPrefromenceChartQuality');

    Route::get('chart/requestsources', 'ChartController@requestSourcesChartR')->name('requestSourcesChartR');
    Route::get('chart/requestsources-2', 'ChartController@requestSourcesWsata')->name('requestSourcesWsata');
    Route::get('chart/requestsources-3', 'ChartController@requestSourcesChartRequests')->name('requestSourcesReq');

    Route::get('chart/report5', [App\Http\Controllers\V2\Admin\AdminController::class, 'report5'])->name('V2.Admin.report5');

    Route::get('chart/request', 'ChartController@requestChartR')->name('requestChartR');

    Route::get('chart/request-basket-v2', 'ChartController@requestChartBasketV2')->name('charts.requests.basket');
    Route::get('chart/request-status-v2', 'ChartController@requestChartStatusV2')->name('charts.requests.status');
    Route::get('chart/request-classification-v2', 'ChartController@requestChartClassificationV2')->name('charts.requests.classification');
    // Measurement tools
    Route::get('chart/measurement-tools', 'ChartController@measurement_tools')->name('measurement_tools');
    Route::get('measurement_tools-dt', 'Reports\SourcesService@measurement_tools')->name('measurement_tools.datatable');


    Route::get('chart/request-sources', 'ChartController@sources')->name('charts.sources');
    Route::get('chart/request-sources-wsata', 'ChartController@sources_for_wsata')->name('charts.sources.wsata');
    Route::get('chart/request-sources-requests', 'ChartController@sources_for_requests')->name('charts.sources.requests');

    Route::get('chart/request/api', 'ChartController@requestChartRApi')->name('requestChartRApi');
    Route::get('chart/users/api', 'ChartController@requestUsersApi')->name('requestUsersApi');
    Route::get('chart/roles/api', 'ChartController@requestRoleApi')->name('requestRoleApi');
    Route::get('chart/request/api-quality', 'ChartController@requestChartRApiQuality')->name('requestChartRApiQuality');
    Route::get('chart/getUsersByStatus', 'ChartController@getUsersByStatus')->name('getUsersByStatus');

    Route::get('chart/movedRequest', 'ChartController@movedRequestChartR')->name('movedRequestChartR');

    Route::get('chart/movedRequestWtihPostiveClass', 'ChartController@movedRequestWtihPostiveClassChart')->name('movedRequestWtihPostiveClass');

    Route::get('chart/qualityTask', 'ChartController@qualityTaskChartR')->name('qualityTaskChartR');

    Route::get('chart/qualityServay', 'ChartController@qualityServayChartR')->name('qualityServayChartR');

    Route::get('chart/updateRequest', 'ChartController@updateRequestChartR')->name('updateRequestChartR');

    Route::get('chart/finalResultChartR', 'ChartController@finalResultChartR')->name('finalResultChartR');

    Route::get('chart/websiteChartR', 'ChartController@websiteChartR')->name('websiteChartR');//الموقع الإلكتروني

    Route::get('chart/otaredUpdateChartR', 'ChartController@otaredUpdateChartR')->name('otaredUpdateChartR');//تحديث عطارد

    Route::get('chart/', 'ChartController@chart'); //For test Library charts
    //-----------------------------------------

    //---------------Filter Method---------------------
    Route::get('/filterEngine', 'FilterEngineController@filterEnginePage')->name('filterEngine');

    Route::get('/filterEngine/madanywork', 'FilterEngineController@getMadanyValue')->name('getMadanyValue');
    Route::get('/filterEngine/miliratyrank', 'FilterEngineController@getMiliratyValue')->name('getMiliratyValue');
    Route::get('/filterEngine/salarysource', 'FilterEngineController@getSalaryValue')->name('getSalaryValue');
    Route::get('/filterEngine/city', 'FilterEngineController@getCityValue')->name('getCityValue');
    Route::get('/filterEngine/agentclass', 'FilterEngineController@getAgentClassValue')->name('getAgentClassValue');
    Route::get('/filterEngine/region', 'FilterEngineController@getRegionValues')->name('getRegionValues');
    Route::get('/filterEngine/agent', 'FilterEngineController@getAgentsValues')->name('getAgentsValues');
    Route::get('/filterEngine/status', 'FilterEngineController@getStatusReqValues')->name('getStatusReqValues');

    Route::get('/filterEngine/test', 'FilterEngineController@testFilter')->name('testFilter');

    Route::POST('/getrequest-datatable', 'FilterEngineController@requests_datatable')->name('getrequest-datatable');
});

//---------------Sales Agent---------------------
route::group(['prefix' => 'agent', 'as' => 'agent.', 'middleware' => ['auth', 'salesagent', 'logout']], function () { // اى لينك هنا هيكون فقط لمندوب المبيعات agent

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //Home
    Route::get('/home', 'AgentController@homePage')->name('home');

    //Update new req
    Route::post('/updatenewreq', 'AgentController@updateNewReq')->name('updateNewReq');

    //Request Records
    Route::get('/reqrecord', 'AgentController@reqRecords')->name('reqRecords');

    //************************************************************************************
    Route::get('/reqrecord', 'AgentController@reqRecords')->name('reqRecords');
    Route::get('/waiting-requests', 'AgentController@getAllRequests')->name('waiting.requests');
    Route::get('/ready-receive', 'AgentController@ReadyReceive')->name('ready.receive');
    Route::get('/waiting-reqs-datatable-new', 'AgentController@waitingReqs_datatableNew');
    Route::get('/waiting-requests-move/{reqId}', 'AgentController@moveRequest');

    Route::post('/checkmobile', 'AgentController@checkMobile')->name('checkMobile');

    Route::post('/archreqarr', 'AgentController@archReqArr')->name('archReqArray');
    Route::post('/restreqarr', 'AgentController@restReqArr')->name('restReqArray');
    Route::post('/starreqarr', 'AgentController@starReqArr')->name('starReqArray');
    Route::post('/followreqarr', 'AgentController@followReqArr')->name('followReqArray');
    Route::post('/restorereqarr', 'AgentController@restoreReqArr')->name('restoreReqArray');

    Route::post('/archcustarr', 'AgentController@archCustArr')->name('archCustArray');
    Route::post('/restcustarr', 'AgentController@restCustArr')->name('restCustArray');

    //CUSTOMERS
    Route::post('/add', 'AgentController@addCustomer')->name('addCustomer');
    Route::post('/add2', 'AgentController@addCustomer2')->name('addCustomer2');

    Route::get('/addcustomerwithreq', 'AgentController@addCustomerWithReq')->name('addCustomerWithReq');
    Route::post('/addcustomerwithreqpost', 'AgentController@addCustomerWithReqPost')->name('addCustomerWithReqPost');

    Route::post('/moveRequestWithAvalibleConditionToMe', 'AgentController@moveRequestWithAvalibleConditionToMe')->name('moveRequestWithAvalibleConditionToMe');

    Route::get('/addcustomer', 'AgentController@addCustomer_page')->name('addPage');

    Route::get('/mycustomer', 'AgentController@mycustomer')->name('myCustomers');
    //This Route to show dataTabel in view(Agent.Customer.mycustomer)
    Route::get('/mycustomer-datatable', 'AgentController@mycustomer_datatable');

    Route::get('/updatecustomer', 'AgentController@updatecustomer')->name('updateCustomer');
    Route::get('/customerprofile/{id}', 'AgentController@customerProfile')->name('profileCustomer');

    //This Route to show dataTabel in view(Agent.Customer.CustomerProfile)
    Route::get('/customerprofile-purRequests-datatable/{id}', 'AgentController@customerProfile_purRequests_datatable');
    Route::get('/customerprofile-morRequests-datatable/{id}', 'AgentController@customerProfile_morRequests_datatable');
    Route::get('/customerprofile-morPurRequests-datatable/{id}', 'AgentController@customerProfile_morPurRequests_datatable');
    Route::get('/customerprofile-payRequests-datatable/{id}', 'AgentController@customerProfile_payRequests_datatable');

    Route::post('/editcustomer', 'AgentController@editCustomer')->name('editCustomer');
    Route::get('/restorecustomer/{id}', 'AgentController@restoreCustomer')->name('restoreCustomer');
    Route::get('/archcustomerpage', 'AgentController@archCustomerPage')->name('archCustomerPage');
    //This Route to show dataTabel in view(Agent.Customer.archCustomers)
    Route::get('/archcustomerpage-datatable', 'AgentController@archCustomerPage_datatable');
    Route::Post('/archivecustomer', 'AgentController@archiveCustomer')->name('archCustomer');

    //FUNDING
    Route::get('/addpurchase/{id}/{title}', 'AgentController@getPurchase')->name('purchasePage');
    // Route::get('/addpurchase', 'AgentController@getPurchase')->name('purchasePage2');

    Route::post('/addfunding', 'AgentController@addfunding')->name('addFund');
    Route::get('/fundingreqpage/{id}', 'AgentController@fundingreqpage')->name('fundingRequest');
    Route::get('/fundingreqpageFromMsg/{id}', 'AgentController@fundingreqpageFromMsg')->name('fundingRequestFromMsg');
    Route::get('/getcustomerinfo', 'AgentController@getCustomerInfo')->name('getCustomerInfo');
    Route::post('/updatefunding', 'AgentController@updatefunding')->name('updateFunding');
    Route::Post('/sendfunding', 'AgentController@sendFunding')->name('sendFunding');
    Route::post('/checksendfunding', 'AgentController@checkSendFunding')->name('checkSendFunding');
    Route::get('/reqarchive/{id}', 'AgentController@reqArchive')->name('archFunding');
    Route::post('/uploadfile', 'AgentController@uploadFile')->name('uploadFile');

    //TASK:::::::::::::::::::::::

    Route::get('/alltask', 'AgentController@alltask')->name('alltask');
    Route::get('/task_datatable', 'AgentController@task_datatable');

    Route::get('/completedtask', 'AgentController@completedtask')->name('completedtask');
    Route::get('/completedtask_datatable', 'AgentController@completedtask_datatable');

    //

    Route::get('/openfile/{id}', 'AgentController@openFile')->name('openFile');
    Route::get('/openfileAgent/{file}', 'AgentController@openFileAgent')->name('openFileAgent');

    Route::get('/downloadfile/{id}', 'AgentController@downloadFile')->name('downFile');
    Route::Post('/deletefile', 'AgentController@deleteFile')->name('deleFile');

    //REQUEST
    Route::get('/myreqs', 'AgentController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(Agent.Customer.mycustomer)
    Route::get('/myreqs-datatable', 'AgentController@myreqs_datatable');

    Route::get('/recivedreqs', 'AgentController@recivedReqs')->name('recivedRequests');
    //This Route to show dataTabel in view(Agent.Customer.recivedReqs)
    Route::get('/recivedreqs-datatable', 'AgentController@recivedReqs_datatable');

    Route::get('/followreqs', 'AgentController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(Agent.Customer.followReqs)
    Route::get('/followreqs-datatable', 'AgentController@followReqs_datatable');

    Route::get('/staredreqs', 'AgentController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(Agent.Customer.staredReqs)
    Route::get('/staredreqs-datatable', 'AgentController@starReqs_datatable');

    //Route::get('/canceledreqs', 'AgentController@canceledReqs')->name('cancelRequests');
    //This Route to show dataTabel in view(Agent.Request.canceledReqs)
    //Route::get('/canceledreqs-datatable', 'AgentController@canceledReqs_datatable');

    Route::get('/completedreqs', 'AgentController@completedReqs')->name('completedRequests');
    //This Route to show dataTabel in view(Agent.Request.completedReqs)
    Route::get('/completedreqs-datatable', 'AgentController@completedreqs_datatable');

    Route::get('/managereq/{id}/{action}', 'AgentController@manageReq')->name('manageRequest');

    Route::get('/archreqs', 'AgentController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(Agent.Request.archReqs)
    Route::get('/archreqs-datatable', 'AgentController@archReqs_datatable');

    Route::get('/restreqs/{id}', 'AgentController@restReq')->name('restoreRequest');
    Route::get('/sendreqs', 'AgentController@sendReqs')->name('sendRequests');

    Route::get('/additional/request', 'AgentController@additionalReqs')->name('additionalRequests');
    Route::get('/additional/request/datatable', 'AgentController@additionalReqs_datatable');
    Route::get('/move/additional/request', 'AgentController@moveAdditionalReqs')->name('moveAdditionalReqs');

    Route::get('/morpurreqs', 'AgentController@morPurReqs')->name('morPurRequests');
    Route::get('/morpurreqs-data-table', 'AgentController@morPurReqs_data_Table')->name('morPurReqs_data_Table');
    Route::get('/morpurpage/{id}', 'AgentController@morPurpage')->name('morPurRequest');
    Route::Post('/sendmorpur', 'AgentController@sendMorPur')->name('sendMorPur');

    //CHART
    //Route::get('chart/finalResultChartForAgent', 'ChartController@finalResultChartForAgent')->name('finalResultChartForAgent');
    Route::get('chart/requestChartRForAgent', 'ChartController@requestChartRForAgent')->name('requestChartRForAgent');

    Route::get('chart/request-basket-v2', 'ChartController@requestAgentChartBasketV2')->name('charts.requests.basket');
    Route::get('chart/request-status-v2', 'ChartController@requestAgentChartStatusV2')->name('charts.requests.status');
    Route::get('chart/request-classification-v2', 'ChartController@requestAgentChartClassificationV2')->name('charts.requests.classification');

    //ASK REQUEST
    Route::get('/asknewrequest', 'AgentController@askRequest')->name('askRequest');

    //Prepayment
    Route::get('/prepaymentreqs', 'AgentController@prepaymentReqs')->name('PrepaymentReq');
    //This Route to show dataTabel in view(Agent.Request.prepayment)
    Route::get('/prepaymentreqs-datatable', 'AgentController@prepaymentReqs_datatable');

    Route::Get('/updateprepage/{id}', 'AgentController@updatePage')->name('updatePrepaymentPage');
    Route::Post('/updatepre', 'AgentController@updatePre')->name('updatePrepayment');
    Route::get('/sendpre', 'AgentController@sendPre')->name('sendPrepayment');

    #update ready recive account
    Route::get('/updatereadyrecive', 'AgentController@updatereadyrecive')->name('updatereadyrecive');
    Route::get('/testupdatereadyrecive', 'AgentController@check_on_agent_ready_recive')->name('testupdatereadyrecive');


    Route::get('/rates', 'Agent2Controller@index')->name('rates');
    Route::get('/rates-datatable', 'Agent2Controller@datatable')->name("rates.datatable");
    Route::get('/update-isProcessed-rate', 'Agent2Controller@updateIsProcessedRate')->name("updateIsProcessedRate");
   // Route::get('/fundingreqpage/{id}', 'AgentController@fundingreqpage')->name('fundingRequest');



});

//---------------Sales Manager---------------------

route::group(['prefix' => 'salesManager', 'as' => 'sales.manager.', 'middleware' => ['auth', 'salesmanager', 'logout']], function () {
    Route::get('staff', 'V2\SalesManager\StaffController@index')->name('staff_index');
    Route::get('staff-index', 'V2\SalesManager\StaffController@indexDataTable');
    Route::get('/user/profile/{user}/{pdf?}', 'V2\SalesManager\StaffController@profile')->name('user.profile');
    Route::get('/getAllMailsNotifications', 'V2\SalesManager\StaffController@getAllMailsNotifications')->name('getAllMailsNotifications');
    Route::post('/saveAllMailsNotifications', 'V2\SalesManager\StaffController@saveAllMailsNotifications')->name('saveAllMailsNotifications');

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //Home
    Route::get('/home', 'SalesManagerController@homePage')->name('home');

    //CHART
    Route::get('chart/finalResultChartForSalesManager', 'ChartController@finalResultChartForSalesManager')->name('finalResultChartForSalesManager');
    Route::get('chart/requestChartRForSalesManager', 'ChartController@requestChartRForSalesManager')->name('requestChartRForSalesManager');
    Route::get('chart/movedRequestChartRForSalesManager', 'ChartController@movedRequestChartRForSalesManager')->name('movedRequestChartRForSalesManager');
    Route::get('chart/qualityTaskChartRForSalesManager', 'ChartController@qualityTaskChartRForSalesManager')->name('qualityTaskChartRForSalesManager');
    Route::get('chart/qualityServayChartRForSalesManager', 'ChartController@qualityServayChartRForSalesManager')->name('qualityServayChartRForSalesManager');
    Route::get('chart/updateRequestChartRForSalesManager', 'ChartController@updateRequestChartRForSalesManager')->name('updateRequestChartRForSalesManager');
    /*********************************************************************/
    // Task-47
    /*********************************************************************/
    Route::get('chart/dailyprefromenceForSalesManager', 'ChartController@dailyPrefromenceChartRForSalesManager')->name('dailyPrefromenceChartRForSalesManager');
    Route::get('chart/request/api', 'ChartController@requestChartRApiForManager')->name('requestChartRApiForManager');
    //CUSTOMER
    Route::get('chart/request-sales-basket-v2', 'ChartController@requestChartBasketForSalesAgentV2')->name('charts.sales.requests.basket');
    Route::get('chart/request-sales-status-v2', 'ChartController@requestChartStatusForSalesAgentV2')->name('charts.sales.requests.status');
    Route::get('chart/request-sales-classification-v2', 'ChartController@requestChartClassificationForSalesAgentV2')->name('charts.sales.requests.classification');

    Route::get('/agentmanager', 'SalesManagerController@agentmanager')->name('agentManager');

    Route::get('/agentcustomer/{id}', 'SalesManagerController@agentcustomer')->name('agentCustomer');
    //This Route to show dataTabel in view(SalesManagerController.CUSTOMER.agentCustomer)
    Route::get('/agentcustomer-datatable', 'SalesManagerController@agentcustomer_datatable');

    Route::post('/archreqarr', 'SalesManagerController@archReqArr')->name('archReqArray');
    Route::post('/restreqarr', 'SalesManagerController@restReqArr')->name('restReqArray');

    //REQUEST
    Route::get('/myreqs', 'SalesManagerController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.myReqs)
    Route::get('/myreqs-datatable', 'SalesManagerController@myReqs_datatable');

    Route::get('/purreqs', 'SalesManagerController@purReqs')->name('purReqs');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.purReqs)
    Route::get('/purReqs-datatable', 'SalesManagerController@purReqs_datatable');

    Route::get('/morreqs', 'SalesManagerController@morReqs')->name('morReqs');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.morReqs)
    Route::get('/morReqs-datatable', 'SalesManagerController@morReqs_datatable');

    Route::get('/archreqs', 'SalesManagerController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.archReqs)
    Route::get('/archreqs-datatable', 'SalesManagerController@archReqs_datatable');

    Route::get('/rejreqs', 'SalesManagerController@rejReqs')->name('rejRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.rejReqs)
    Route::get('/rejreqs-datatable', 'SalesManagerController@rejReqs_datatable');

    Route::get('/restreqs/{id}', 'SalesManagerController@restReq')->name('restoreRequest');
    Route::post('/rejectreqs', 'SalesManagerController@rejectReq')->name('rejectRequest');

    Route::get('/recivedreqs', 'SalesManagerController@recivedReqs')->name('recivedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.recivedReqs)
    Route::get('/recivedreqs-datatable', 'SalesManagerController@recivedReqs_datatable');

    ///---------------------Agnet Requests ------------------------------------------------

    Route::get('/followreqs', 'SalesManagerController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.followReqs)
    Route::get('/followreqs-datatable', 'SalesManagerController@followReqs_datatable');

    Route::get('/staredreqs', 'SalesManagerController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.starReqs)
    Route::get('/staredreqs-datatable', 'SalesManagerController@starReqs_datatable');

    Route::get('/dailyreq', 'SalesManagerController@dailyreq')->name('dailyReq');
    //This Route to show dataTabel in view(SalesManagerController.CUSTOMER.agentCustomer)
    Route::get('/dailyreq-datatable', 'SalesManagerController@dailyreq_datatable');

    Route::get('/agentcompletedreqs', 'SalesManagerController@agentCompletedReqs')->name('agentCompletedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.completedReqs)
    Route::get('/agentcompletedreqs-datatable', 'SalesManagerController@agentCompletedReqs_datatable');

    Route::get('/agentrecivedreqs', 'SalesManagerController@agentRecivedReqs')->name('agentRecivedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.completedReqs)
    Route::get('/agentrecivedreqs-datatable', 'SalesManagerController@agentRecivedReqs_datatable');

    Route::get('/agentarchreqs', 'SalesManagerController@agentArchReqs')->name('agentArchRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.completedReqs)
    Route::get('/agentarchreqs-datatable', 'SalesManagerController@agentArchReqs_datatable');

    ///----------------------------------------------------------------------------------

    Route::get('/canceledreqs', 'SalesManagerController@canceledReqs')->name('cancelRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.canceledReqs)
    Route::get('/canceledreqs-datatable', 'SalesManagerController@canceledReqs_datatable');

    Route::get('/completedreqs', 'SalesManagerController@completedReqs')->name('completedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.completedReqs)
    Route::get('/completedreqs-datatable', 'SalesManagerController@completedReqs_datatable');

    Route::get('/managereq/{id}/{action}', 'SalesManagerController@manageReq')->name('manageRequest');

    Route::get('/prepaymentreqs', 'SalesManagerController@prepaymentReqs')->name('PrepaymentReq');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.prepaymentReqs)
    Route::get('/prepaymentreqs-datatable', 'SalesManagerController@prepaymentReqs_datatable');

    Route::get('/morpurreqs', 'SalesManagerController@morPurReqs')->name('morPurRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.morPurReqs)
    Route::get('/morpurreqs-datatable', 'SalesManagerController@morPurReqs_datatable');

    Route::get('/morpurpage/{id}', 'SalesManagerController@morPurpage')->name('morPurRequest');
    Route::post('/rejectmorpur', 'SalesManagerController@rejectMorPur')->name('rejectMorPur');
    Route::Post('/sendmorpur', 'SalesManagerController@sendMorPur')->name('sendMorPur');

    //PRAYMENT
    Route::Get('/updateprepage/{id}', 'SalesManagerController@updatePage')->name('updatePrepaymentPage');
    Route::Get('/rejectprepay', 'SalesManagerController@rejectPrepay')->name('rejectPrepayment');
    Route::Post('/updatepre', 'SalesManagerController@updatePre')->name('updatePrepayment');
    Route::Post('/sendpre', 'SalesManagerController@sendPre')->name('sendPrepayment');

    //FUNDING
    Route::get('/fundingreqpage/{id}', 'SalesManagerController@fundingreqpage')->name('fundingRequest');
    Route::post('/updatefunding', 'SalesManagerController@updatefunding')->name('updateFunding');
    Route::get('/reqarchive/{id}', 'SalesManagerController@reqArchive')->name('archFunding');
    Route::Post('/sendfunding', 'SalesManagerController@sendFunding')->name('sendFunding');

    //AproveTsaheel
    Route::get('/approvetsaheel', 'SalesManagerController@aprroveTsaheel')->name('aprroveTsaheel');
    Route::get('/undoapprovetsaheel', 'SalesManagerController@undoaprroveTsaheel')->name('undoaprroveTsaheel');
    //AproveAqar
    Route::get('/approveaqar', 'SalesManagerController@aprroveAqar')->name('aprroveAqar');
    Route::get('/undoapproveaqar', 'SalesManagerController@undoaprroveAqar')->name('undoAqarApprove');

    Route::post('/uploadfile', 'SalesManagerController@uploadFile')->name('uploadFile');

    Route::get('/openfile/{id}', 'SalesManagerController@openFile')->name('openFile');

    Route::get('/downloadfile/{id}', 'SalesManagerController@downloadFile')->name('downFile');

    Route::Post('/deletefile', 'SalesManagerController@deleteFile')->name('deleFile');
});

/////////////////////////////////////////////////////////

//---------------Funding Manager---------------------

route::group(['prefix' => 'fundingManager', 'as' => 'funding.manager.', 'middleware' => ['auth', 'fundingmanager', 'logout']], function () { // اى لينك هنا هيكون فقط لمندوب المبيعات agent

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //Home
    Route::get('/home', 'FundingManagerController@homePage')->name('home');

    //REQUEST
    Route::get('/myreqs', 'FundingManagerController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.myreqs)
    Route::get('/myreqs-datatable', 'FundingManagerController@myReqs_datatable');

    Route::get('/archreqs', 'FundingManagerController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.recivedReqs)
    Route::get('/archreqs-datatable', 'FundingManagerController@archReqs_datatable');

    Route::get('/prepaymentreqs', 'FundingManagerController@prepaymentReqs')->name('PrepaymentReq');
    //This Route to show dataTabel in view(FundingManager.REQUEST.prepaymentReqs)
    Route::get('/prepaymentreqs-datatable', 'FundingManagerController@prepaymentReqs_datatable');

    Route::get('/restreqs/{id}', 'FundingManagerController@restReq')->name('restoreRequest');
    Route::post('/rejectreqs', 'FundingManagerController@rejectReq')->name('rejectRequest');

    Route::get('/rejreqs', 'FundingManagerController@rejReqs')->name('rejRequests');
    //This Route to show dataTabel in view(FundingManagerController.REQUEST.rejReqs)
    Route::get('/rejreqs-datatable', 'FundingManagerController@rejReqs_datatable');

    Route::get('underpage', 'FundingManagerController@underPage')->name('UnderProcessPage');
    Route::post('/underpage-datatable', 'FundingManagerController@underpage_datatable');
    Route::get('/addunder/{id}', 'FundingManagerController@addUnder')->name('addUnderProcess');
    Route::get('/remunder/{id}', 'FundingManagerController@removeUnder')->name('removeUnderProcess');

    Route::post('/archreqarr', 'FundingManagerController@archReqArr')->name('archReqArray');
    Route::post('/restreqarr', 'FundingManagerController@restReqArr')->name('restReqArray');

    Route::get('/recivedreqs', 'FundingManagerController@recivedReqs')->name('recivedRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.recivedReqs)
    Route::get('/recivedreqs-datatable', 'FundingManagerController@recivedReqs_datatable');

    Route::get('/followreqs', 'FundingManagerController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.canceledReqs)
    Route::get('/followreqs-datatable', 'FundingManagerController@followReqs_datatable');

    Route::get('/staredreqs', 'FundingManagerController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(Agent.Customer.staredReqs)
    Route::get('/staredreqs-datatable', 'FundingManagerController@starReqs_datatable');

    Route::get('/canceledreqs', 'FundingManagerController@canceledReqs')->name('cancelRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.canceledReqs)
    Route::get('/canceledreqs-datatable', 'FundingManagerController@canceledReqs_datatable');

    Route::get('/completedreqs', 'FundingManagerController@completedReqs')->name('completedRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.completedReqs)
    Route::get('/completedreqs-datatable', 'FundingManagerController@completedReqs_datatable');

    Route::get('/managereq/{id}/{action}', 'FundingManagerController@manageReq')->name('manageRequest');

    //MOR-PUR
    Route::get('/morpurreqs', 'FundingManagerController@morPurReqs')->name('morPurRequests');
    //This Route to show dataTabel in view(FundingManager.REQUEST.morPurReqs)
    Route::get('/morpurreqs-datatable', 'FundingManagerController@morPurReqs_datatable');

    Route::get('/morpurpage/{id}', 'FundingManagerController@morPurpage')->name('morPurRequest');
    Route::post('/rejectmorpur', 'FundingManagerController@rejectMorPur')->name('rejectMorPur');
    Route::Post('/appmorpur', 'FundingManagerController@appMorPur')->name('appMorPur');

    //UPDATE BANK INFO
    Route::post('/updatebank', 'FundingManagerController@updateBank')->name('updateBank');
    Route::post('/updatecomm', 'FundingManagerController@updateComm')->name('updateComm');
    Route::post('/updatecost', 'FundingManagerController@updateCost')->name('updateCost');
    Route::post('/updatereal', 'FundingManagerController@updateReal')->name('updateReal');
    Route::post('/updateclass', 'FundingManagerController@updateClass')->name('updateClass');
    Route::post('/updatefunsour', 'FundingManagerController@updatefunsour')->name('updatefunsour');
    Route::post('/updaterealcity', 'FundingManagerController@updaterealcity')->name('updaterealcity');
    Route::post('/updaterealType', 'FundingManagerController@updaterealType')->name('updaterealType');

    //AproveAqar
    Route::get('/approveaqar', 'FundingManagerController@aprroveAqar')->name('aprroveAqar');
    Route::get('/undoapproveaqar', 'FundingManagerController@undoaprroveAqar')->name('undoAqarApprove');

    //FUNDING
    Route::get('/fundingreqpage/{id}', 'FundingManagerController@fundingreqpage')->name('fundingRequest');
    Route::get('/reqarchive/{id}', 'FundingManagerController@reqArchive')->name('archFunding');
    Route::Post('/sendfunding', 'FundingManagerController@sendFunding')->name('sendFunding');
    Route::post('/updatefunding', 'FundingManagerController@updatefunding')->name('updateFunding');
    Route::post('/uploadfile', 'FundingManagerController@uploadFile')->name('uploadFile');
    // send to bank user
    Route::get('/send-bank/{user_id}/{req_id}/{type}', 'FundingManagerController@sendBank')->name('sendBank');

    Route::get('/openfile/{id}', 'FundingManagerController@openFile')->name('openFile');

    Route::get('/downloadfile/{id}', 'FundingManagerController@downloadFile')->name('downFile');

    Route::Post('/deletefile', 'FundingManagerController@deleteFile')->name('deleFile');

    //PAYMENT
    Route::get('/prepayment/{id}', 'FundingManagerController@prepage')->name('prepayementPage');
    Route::Post('/createPrepay', 'FundingManagerController@createPre')->name('createPrepayment');
    Route::Get('/updateprepage/{id}', 'FundingManagerController@updatePage')->name('updatePrepaymentPage');
    Route::Get('/rejectprepay', 'FundingManagerController@rejectPrepay')->name('rejectPrepayment');
    Route::Post('/updatepre', 'FundingManagerController@updatePre')->name('updatePrepayment');
    Route::get('/cancelpre/{id}', 'FundingManagerController@cancelPre')->name('cancelPrepayment');
    Route::get('/restorepre/{id}', 'FundingManagerController@restorePre')->name('restPrepayment');
    Route::get('/apppre', 'FundingManagerController@appPre')->name('appPrepayment');
    Route::get('/sendpre', 'FundingManagerController@sendPre')->name('sendPrepayment');

    //EditCoumns
    Route::post('/editcolumn', 'SettingsController@editCoulmn')->name('editCoulmn');
});
/////////////////////////////////////////////////////

//---------------Mortgage Manager---------------------

route::group(['prefix' => 'mortgageManager', 'as' => 'mortgage.manager.', 'middleware' => ['auth', 'mortgagemanager', 'logout']], function () {

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //Home
    Route::get('/home', 'MortgageManagerController@homePage')->name('home');

    //REQUEST
    Route::get('/myreqs', 'MortgageManagerController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.myReqs)
    Route::get('/myreqs-datatable', 'MortgageManagerController@myReqs_datatable');

    Route::get('/archreqs', 'MortgageManagerController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.recivedReqs)
    Route::get('/archreqs-datatable', 'MortgageManagerController@archReqs_datatable');

    Route::get('/rejreqs', 'MortgageManagerController@rejReqs')->name('rejRequests');
    //This Route to show dataTabel in view(MortgageManagerController.REQUEST.rejReqs)
    Route::get('/rejreqs-datatable', 'MortgageManagerController@rejReqs_datatable');

    Route::get('/restreqs/{id}', 'MortgageManagerController@restReq')->name('restoreRequest');
    Route::get('/addunder/{id}', 'MortgageManagerController@addUnder')->name('addUnderProcess');
    Route::get('underpage', 'MortgageManagerController@underPage')->name('UnderProcessPage');
    Route::post('/underpage-datatable', 'MortgageManagerController@underpage_datatable');
    Route::get('/remunder/{id}', 'MortgageManagerController@removeUnder')->name('removeUnderProcess');

    Route::get('/morpurreqs', 'MortgageManagerController@morPurReqs')->name('morPurRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.morPurReqs)
    Route::get('/morpurreqs-datatable', 'MortgageManagerController@morPurReqs_datatable');

    Route::get('/recivedreqs', 'MortgageManagerController@recivedReqs')->name('recivedRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.recivedReqs)
    Route::get('/recivedreqs-datatable', 'MortgageManagerController@recivedReqs_datatable');

    Route::get('/followreqs', 'MortgageManagerController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.followReqs)
    Route::get('/followreqs-datatable', 'MortgageManagerController@followReqs_datatable');

    Route::get('/staredreqs', 'MortgageManagerController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.starReqs)
    Route::get('/staredreqs-datatable', 'MortgageManagerController@starReqs_datatable');

    Route::get('/canceledreqs', 'MortgageManagerController@canceledReqs')->name('cancelRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.cancelRequests)
    Route::get('/canceledreqs-datatable', 'MortgageManagerController@canceledReqs_datatable');

    Route::get('/completedreqs', 'MortgageManagerController@completedReqs')->name('completedRequests');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.completedReqs)
    Route::get('/completedreqs-datatable', 'MortgageManagerController@completedReqs_datatable');

    Route::get('/managereq/{id}/{action}', 'MortgageManagerController@manageReq')->name('manageRequest');

    Route::post('/archreqarr', 'MortgageManagerController@archReqArr')->name('archReqArray');
    Route::post('/restreqarr', 'MortgageManagerController@restReqArr')->name('restReqArray');

    //AproveTsaheel
    Route::get('/approvetsaheel', 'MortgageManagerController@aprroveTsaheel')->name('aprroveTsaheel');
    Route::get('/undoapprovetsaheel', 'MortgageManagerController@undoaprroveTsaheel')->name('undoaprroveTsaheel');

    //UPDATE BANK INFO
    Route::post('/updatebank', 'MortgageManagerController@updateBank')->name('updateBank');
    Route::post('/updatecomm', 'MortgageManagerController@updateComm')->name('updateComm');
    Route::post('/updatecost', 'MortgageManagerController@updateCost')->name('updateCost');
    Route::post('/updatereal', 'MortgageManagerController@updateReal')->name('updateReal');
    Route::post('/updatecheck', 'MortgageManagerController@updateCheck')->name('updateCheck');
    Route::post('/updateclass', 'MortgageManagerController@updateClass')->name('updateClass');
    Route::post('/updaterealcity', 'MortgageManagerController@updaterealcity')->name('updaterealcity');
    Route::post('/updaterealType', 'MortgageManagerController@updaterealType')->name('updaterealType');
    Route::post('/updatefunsour', 'MortgageManagerController@updatefunsour')->name('updatefunsour');

    //FUNDING
    Route::get('/fundingreqpage/{id}', 'MortgageManagerController@fundingreqpage')->name('fundingRequest');
    Route::get('/fundingreqpage-noedit/{id}', 'MortgageManagerController@fundingreqpageWithoutEdit')->name('fundingRequestWithoutEdit');

    Route::Post('/sendfunding', 'MortgageManagerController@sendFunding')->name('sendFunding');
    Route::get('/reqarchive/{id}', 'MortgageManagerController@reqArchive')->name('archFunding');
    Route::post('/rejectreqs', 'MortgageManagerController@rejectReq')->name('rejectRequest');
    Route::post('/updatefunding', 'MortgageManagerController@updatefunding')->name('updateFunding');
    Route::post('/uploadfile', 'MortgageManagerController@uploadFile')->name('uploadFile');

    Route::get('/openfile/{id}', 'MortgageManagerController@openFile')->name('openFile');

    Route::get('/downloadfile/{id}', 'MortgageManagerController@downloadFile')->name('downFile');

    Route::Post('/deletefile', 'MortgageManagerController@deleteFile')->name('deleFile');

    //Agent MORTGAGE REQUEST
    Route::get('/agentmortgagereqs', 'MortgageManagerController@allMortgageReqs')->name('allMortgageReqs');
    Route::get('/agentmortgagereqs-datatable', 'MortgageManagerController@allMortgageReqs_datatable');

    Route::get('/createmorpur/{id}', 'MortgageManagerController@createMorPur')->name('createMorPur');
    Route::get('/createmorpurafter/{id}', 'MortgageManagerController@createMorPur_after')->name('createMorPur_after');

    Route::get('/morpurpage/{id}', 'MortgageManagerController@morPurpage')->name('morPurRequest');
    Route::get('/morpurpage-noedit/{id}', 'MortgageManagerController@morPurpageWithoutEdit')->name('morPurRequestWithoutEdit');

    Route::Post('/sendmorpur', 'MortgageManagerController@sendMorPur')->name('sendMorPur');
    Route::get('/cancelmorpur/{id}', 'MortgageManagerController@cancelMorPur')->name('cancelMorPur');
    Route::get('/restmorpur/{id}', 'MortgageManagerController@restMorPur')->name('restMorPur');

    Route::get('/completemorpur', 'MortgageManagerController@completeMorPur')->name('completeMorPur');
    Route::get('/undocompletemorpur', 'MortgageManagerController@undocompleteMorPur')->name('undocompleteMorPur');

    //PREPAYMENT
    Route::get('/prepaymentreqs', 'MortgageManagerController@prepaymentReqs')->name('PrepaymentReq');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.prepaymentReqs)
    Route::get('/prepaymentreqs-datatable', 'MortgageManagerController@prepaymentReqs_datatable');

    Route::Get('/updateprepage/{id}', 'MortgageManagerController@updatePage')->name('updatePrepaymentPage');
    Route::Post('/updatepre', 'MortgageManagerController@updatePre')->name('updatePrepayment');
    Route::get('/cancelpre/{id}', 'MortgageManagerController@cancelPre')->name('cancelPrepayment');
    Route::get('/restorepre/{id}', 'MortgageManagerController@restorePre')->name('restPrepayment');
    Route::Get('/rejectprepay', 'MortgageManagerController@rejectPrepay')->name('rejectPrepayment');
    Route::get('/apppre', 'MortgageManagerController@appPre')->name('appPrepayment');
    Route::get('/getpre', 'MortgageManagerController@getPre')->name('getPrepayment');

    //EditCoumns
    Route::post('/editcolumn', 'SettingsController@editCoulmn')->name('editCoulmn');
});
/////////////////////////////////////////////////////

//---------------General Manager---------------------
Route::get('/RequestInfo', [RequestController::class, 'getRequestInfo'])->name('getRequestInfo');

route::group(['prefix' => 'generalmanager', 'as' => 'general.manager.', 'middleware' => ['auth', 'generalmanager', 'logout']], function () {

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    //Home
    Route::get('/home', 'GeneralManagerController@homePage')->name('home');

    //REQUEST
    Route::get('/myreqs', 'GeneralManagerController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.myReqs)
    Route::get('/myreqs-datatable', 'GeneralManagerController@myReqs_datatable');

    Route::get('/archreqs', 'GeneralManagerController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.archReqs)
    Route::get('/archreqs-datatable', 'GeneralManagerController@archReqs_datatable');

    Route::get('/restreqs/{id}', 'GeneralManagerController@restReq')->name('restoreRequest');

    Route::get('/recivedreqs', 'GeneralManagerController@recivedReqs')->name('recivedRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.archReqs)
    Route::get('/recivedreqs-datatable', 'GeneralManagerController@recivedReqs_datatable');

    Route::get('/followreqs', 'GeneralManagerController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.followReqs)
    Route::get('/followreqs-datatable', 'GeneralManagerController@followReqs_datatable');

    Route::get('/staredreqs', 'GeneralManagerController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.starReqs)
    Route::get('/staredreqs-datatable', 'GeneralManagerController@starReqs_datatable');

    Route::get('/canceledreqs', 'GeneralManagerController@canceledReqs')->name('cancelRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.canceledreqs)
    Route::get('/canceledreqs-datatable', 'GeneralManagerController@canceledReqs_datatable');

    Route::get('/completedreqs', 'GeneralManagerController@completedReqs')->name('completedRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.completedReq)
    Route::get('/completedreqs-datatable', 'GeneralManagerController@completedReqs_datatable');

    Route::get('/managereq/{id}/{action}', 'GeneralManagerController@manageReq')->name('manageRequest');

    Route::post('/archreqarr', 'GeneralManagerController@archReqArr')->name('archReqArray');
    Route::post('/restreqarr', 'GeneralManagerController@restReqArr')->name('restReqArray');

    //MOR-PUR

    Route::get('/morpurreqs', 'GeneralManagerController@morPurReqs')->name('morPurRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.morPurReqs)
    Route::get('/morpurreqs-datatable', 'GeneralManagerController@morPurReqs_datatable');

    Route::get('/morpurpage/{id}', 'GeneralManagerController@morPurpage')->name('morPurRequest');
    Route::Post('/appmorpur', 'GeneralManagerController@appMorPur')->name('appMorPur');
    Route::post('/rejectmorpur', 'GeneralManagerController@rejectMorPur')->name('rejectMorPur');
    Route::get('/cancelmorpur/{id}', 'GeneralManagerController@cancelMorPur')->name('cancelMorPur');
    Route::get('/restmorpur/{id}', 'GeneralManagerController@restMorPur')->name('restMorPur');

    Route::get('/prepaymentreqs', 'GeneralManagerController@prepaymentReqs')->name('PrepaymentReq');
    //This Route to show dataTabel in view(MortgageManager.REQUEST.prepaymentReqs)
    Route::get('/prepaymentreqs-datatable', 'GeneralManagerController@prepaymentReqs_datatable');

    //AproveTsaheel
    Route::get('/approvetsaheel', 'GeneralManagerController@aprroveTsaheel')->name('aprroveTsaheel');
    Route::get('/undoapprovetsaheel', 'GeneralManagerController@undoaprroveTsaheel')->name('undoaprroveTsaheel');

    //AproveAqar
    Route::get('/approveaqar', 'GeneralManagerController@aprroveAqar')->name('aprroveAqar');
    Route::get('/undoapproveaqar', 'GeneralManagerController@undoaprroveAqar')->name('undoAqarApprove');

    //FUNDING
    Route::get('/fundingreqpage/{id}', 'GeneralManagerController@fundingreqpage')->name('fundingRequest');
    Route::post('/updatefunding', 'GeneralManagerController@updatefunding')->name('updateFunding');
    Route::get('/reqarchive/{id}', 'GeneralManagerController@reqArchive')->name('archFunding');
    Route::Post('/approvefunding', 'GeneralManagerController@approveFunding')->name('approveFunding');
    Route::Post('/cancelfunding', 'GeneralManagerController@cancelFunding')->name('cancelFunding');
    Route::post('/rejectreqs', 'GeneralManagerController@rejectReq')->name('rejectRequest');
    Route::get('/recancelfund/{id}', 'GeneralManagerController@reCancelFunding')->name('reCancelFunding');

    Route::post('/uploadfile', 'GeneralManagerController@uploadFile')->name('uploadFile');

    Route::get('/openfile/{id}', 'GeneralManagerController@openFile')->name('openFile');

    Route::get('/downloadfile/{id}', 'GeneralManagerController@downloadFile')->name('downFile');

    Route::Post('/deletefile', 'GeneralManagerController@deleteFile')->name('deleFile');

    //PAYMENT

    Route::get('/movereq', 'AdminController@moveReqToAnother')->name('moveReqToAnother');



});
/////////////////////////////////////////////////////////////////////////////
Route::group(['prefix' => 'switch', 'as' => 'switch.', 'middleware' => ['userswitched', 'auth', 'logout']], function () {

    //SWITCH USERS ::
    Route::post('switchuser', 'AdminController@switchUser')->name('userSwitch');
    Route::get('restoreuser', 'AdminController@restorUser')->name('userRestore');
    //

});

// Start Program Settings Fields
Route::get('/program-setting', 'Calculator\ResultProgramCustomizeController@flexibleProgramCustomize')->name('ResultProgramsCustomize')->middleware(['SettingsOfCalculatorResult']);
Route::get('/changeFlexible', 'Calculator\ResultProgramCustomizeController@changeFlexible')->name('changeFlexible')->middleware(['SettingsOfCalculatorResult']);

//------------------------------------------------------------admin--------------------------------------------------------//
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin', 'auth', 'logout']], function () {

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');

    Route::get('Request/{Request}/moveToFreeze', [RequestController::class, 'moveToFreeze'])->name('Request.moveToFreeze');
    Route::post('Request/moveToFreeze', [RequestController::class, 'moveRequestToFreeze'])->name('Request.moveRequestToFreeze');

    //Admin Route Surveys
    Route::group(['namespace' => 'Admin'], function () {
        // fire events start

        Route::get('fire-events','FireEventController@index')->name('fire-events');
        Route::get('fire-events-property/{id}','FireEventController@show')->name('fire-events-property');
        Route::get('fire-events_datatable','FireEventController@datatable')->name('fire-events_datatable');
        // fire events end
        Route::get('/rates', 'CustomerController@index')->name('rates');
        Route::get('/rates-datatable', 'CustomerController@datatable')->name("rates.datatable");
        Route::get('/update-isProcessed-rate', 'CustomerController@updateIsProcessedRate')->name("updateIsProcessedRate");

        Route::group(['namespace' => 'Employee', 'prefix' => 'hr'], function () {

            Route::resource('vacancies', 'VacanciesController');
            Route::get('vacancies-datatable', 'VacanciesController@datatable')->name('vacancies.datatable');
            Route::get('vacancies-count', 'VacanciesController@count')->name('vacancies.count');
            Route::post('vacancies-count', 'VacanciesController@countPost')->name('vacancies.count.post');
            Route::get('vacancies-activate/{id}', 'VacanciesController@activate')->name('vacancies.activate');

            Route::resource('controls', 'ControlsController')->except(['index']);
            Route::get('control/{name}', 'ControlsController@index')->name('controls.index');
            Route::get('controls-activate/{id}', 'ControlsController@activate')->name('controls.activate');
            Route::get('controls-datatable/{type}', 'ControlsController@datatable')->name('controls.datatable');
        });

        Route::resource('asks', 'AsksController');
        Route::get('asks-activate/{id}', 'AsksController@activate')->name('asks.activate');
        Route::get('answers', 'AsksController@answers')->name('asks.answers');
        Route::get('/survey-answers/{id}', 'AsksController@answer')->name('survey.answer');

        Route::resource('rejections', 'RejectionsController');

        Route::get('guests-users', 'GuestsController@index')->name('guests.index');
        Route::get('guests-users-delete/{id}', 'GuestsController@destroy')->name('guests.destroy');
        Route::post('guests-users', 'GuestsController@filter')->name('guests.filter');

        Route::get('/moveguestusers', 'GuestsController@moveGuestUsers')->name('moveguestusers');

        Route::get('/updatemovmenthours', 'GuestsController@updatemovmenthours')->name('updatemovmenthours');

        Route::resource('scenarios', 'ScenariosController');
        Route::post('reorder-scenarios', 'ScenariosController@reorder')->name('reorder.scenarios');
        Route::get('scenarios-users/{scenarioId}', 'ScenariosController@users')->name('scenarios.users');
        Route::post('scenarios-users', 'ScenariosController@agents')->name('scenarios.agents');
        Route::post('scenarios-check', 'ScenariosController@check')->name('scenarios.check');

        Route::post('/importExealrequest', 'AdminController@importExealrequest')->name('importExealrequest');
        Route::get('/repeated/{id?}', 'CollaboratorsController@indexRepeated')->name('collaborator.requests.repeated');

        Route::get('/col-reqs/{id}', 'CollaboratorsController@colReqs')->name('collaborator.requests.active');

        Route::get('/col-pending/{id}', 'CollaboratorsController@index')->name('collaborator.requests.pending');
        Route::get('/down-request/{id}', 'CollaboratorsController@down')->name('collaborator.requests.down');
        Route::get('/reverse-request/{id}', 'CollaboratorsController@reverse')->name('collaborator.requests.reverse');
        Route::get('/col-pending-datatable/{id}', 'CollaboratorsController@datatable')->name("colPen_datatable");

        Route::get('/col-reqs-datatable/{id?}/{type?}', 'CollaboratorsController@colReqs_datatable')->name("colReqs_datatable");

    });

    // import Excel sheet request

    //Customer with req
    Route::get('/agentcolloberators', 'AdminController@getAgentCollberators')->name('getAgentCollberators');
    Route::get('/addcustomerwithreq', 'AdminController@addCustomerWithReq')->name('addCustomerWithReq');
    Route::post('/addcustomerwithreqpost', 'AdminController@addCustomerWithReqPost')->name('addCustomerWithReqPost');

    //**************************************************************************************
    // Task-41 IpAddress Repeated
    //**************************************************************************************
    Route::get('/ip-addresses', 'AdminController@ipAddresss')->name('ips');
    Route::post('/ip-addresse', 'AdminController@ipAddresssMerge')->name('ips.merge');
    Route::get('/ip-addresses-datatable', 'AdminController@ipAddresss_dataTable')->name('ips.datatable');
    Route::get('/ip-addresses/{ip}', 'AdminController@ipAddresssDetails')->name('ips.single');
    //**************************************************************************************

    //EMAIL:::
    Route::get('/emails', 'AdminController@allEmails')->name('emails');
    Route::get('/emails-datatable', 'AdminController@allEmails_datatable');
    Route::get('/addEmailPage', 'AdminController@addEmailPage')->name('addEmailPage');
    Route::get('/getAllMailsNotifications', 'AdminController@getAllMailsNotifications')->name('getAllMailsNotifications');
    Route::post('/saveAllMailsNotifications', 'AdminController@saveAllMailsNotifications')->name('saveAllMailsNotifications');
    Route::post('/addEmail', 'AdminController@addEmail')->name('addEmail');
    Route::get('/deleteEmail', 'AdminController@deleteEmail')->name('deleteEmail');

    //--------------------------------------------
    //#Suggestions OF CALCULATER
    //--------------------------------------------
    Route::get('/suggestions', 'Calculator\SuggestionsController@index')->name('suggestions.index');
    Route::get('/suggestions-years/{type}', 'Calculator\SuggestionsController@years')->name('suggestions.years.index');
    Route::get('/suggestions-years-datatable/{status}', 'Calculator\SuggestionsController@yearsDataTables')->name('suggestions.years.datatable');

    Route::get('/suggestions-percentages/{type}', 'Calculator\SuggestionsController@percentages')->name('suggestions.percentages.index');
    Route::get('/suggestions-percentages-datatable/{status}', 'Calculator\SuggestionsController@percentagesDataTables')->name('suggestions.percentages.datatable');

    Route::get('/suggestions-{type}-details/{suggestId}', 'Calculator\SuggestionsController@details')->name('suggestions.details');

    Route::post('/suggestion-year-approve', 'Calculator\SuggestionsController@approve')->name('suggestions.approve');
    Route::post('/suggestion-year-archive', 'Calculator\SuggestionsController@archive')->name('suggestions.archive');
    Route::post('/suggestion-year-restore', 'Calculator\SuggestionsController@restore')->name('suggestions.restore');

    Route::get('/profit-percentage-compare/{id}', 'Calculator\AdminProfitPercentageController@percentages')->name('compare.percentages');
    Route::get('/profit-percentage-compare-datatable/{id}', 'Calculator\AdminProfitPercentageController@percentagesDataTables')->name('compare.percentages.datatable');
    Route::get('/extra-funding-year-compare/{id}', 'Calculator\AdminExtraFundingYearController@years')->name('compare.years');
    Route::get('/extra-funding-year-compare-datatable/{id}', 'Calculator\AdminExtraFundingYearController@yearsDataTables')->name('compare.years.datatable');

    //--------------------------------------------

    // Resources Calculater
    // Error Controller
    //Route::get('/resources', 'Calculator\AdminCalculatorController@getResources')->name('resources');
    // Banks
    Route::get('/banks', 'Calculator\AdminBankController@getAllBanks')->name('banks');
    Route::get('/addNewBankPage', 'Calculator\AdminBankController@addNewBankPage')->name('addNewBankPage');
    Route::post('/addNewBank', 'Calculator\AdminBankController@addNewBankRequest')->name('addNewBank');
    Route::DELETE('/bank-remove', 'Calculator\AdminBankController@removeBank');
    Route::get('/getbank', 'Calculator\AdminBankController@getBankInfo')->name('getBank');
    Route::put('/bank/update/{id}', 'Calculator\AdminBankController@updateBankInfo');
    Route::get('/changeBankStatus', 'Calculator\AdminBankController@changeBankStatus')->name('changeBankStatus');
    Route::get('/changePropertyCompleted', 'Calculator\AdminBankController@changePropertyCompleted')->name('changePropertyCompleted');
    Route::get('/changePropertyUnCompleted', 'Calculator\AdminBankController@changePropertyUnCompleted')->name('changePropertyUnCompleted');
    Route::get('/changeJoint', 'Calculator\AdminBankController@changeJoint')->name('changeJoint');
    Route::get('/changeGuarantees', 'Calculator\AdminBankController@changeGuarantees')->name('changeGuarantees');
    Route::get('/changeQuestCheck', 'Calculator\AdminBankController@changeQuestCheck')->name('changeQuestCheck');
    Route::get('/changeBearTax', 'Calculator\AdminBankController@changeBearTax')->name('changeBearTax');
    Route::get('/changeShl', 'Calculator\AdminBankController@changeShl')->name('changeShl');

    // Start Calculator Settings Job Position Module
    Route::get('/job-position-index', 'Calculator\AdminJobPositionController@jobPositionIndex')->name('jobPositionIndex');
    Route::get('/job-positions-datatable', 'Calculator\AdminJobPositionController@jobPositionIndexDataTables');
    Route::get('/job-position/add', 'Calculator\AdminJobPositionController@addNewJobPosition')->name('addNewJobPosition');
    Route::post('/job-position/save', 'Calculator\AdminJobPositionController@saveNewJobPosition')->name('saveNewJobPosition');
    Route::get('/job-position-edit/{id}', 'Calculator\AdminJobPositionController@editJobPositionItem');

    Route::post('/job-position/update', 'Calculator\AdminJobPositionController@updateJobPosition')->name('updateJobPosition');
    Route::post('/job-position/remove/', 'Calculator\AdminJobPositionController@removeJobPosition');

    // End Calculator Settings Job Position Module

    // Start Calculator Settings ExtraFundingYear Module
    Route::get('/extra-funding-year-index', 'Calculator\AdminExtraFundingYearController@extraFundingYearIndex')->name('extraFundingYearIndex');
    Route::get('/extra-funding-year-datatable', 'Calculator\AdminExtraFundingYearController@extraFundingYearDataTables');
    Route::get('/extra-funding-year-add', 'Calculator\AdminExtraFundingYearController@addNewExtraFundingPage')->name('addNewExtraFundingPage');
    Route::post('/extra-funding-year-save', 'Calculator\AdminExtraFundingYearController@saveNewExtraFunding')->name('saveNewExtraFunding');
    Route::get('/extra-funding-year-edit/{id}', 'Calculator\AdminExtraFundingYearController@editExtraFundingPage');
    Route::post('/extra-funding-year/update', 'Calculator\AdminExtraFundingYearController@updateExtraFunding')->name('updateExtraFunding');
    Route::post('/extra-funding-year/remove', 'Calculator\AdminExtraFundingYearController@removeExtraFundingYear');
    // End Calculator Settings ExtraFundingYear Module

    // Start Calculator Settings ExtraFundingYear Module
    Route::get('/profit-percentage-index', 'Calculator\AdminProfitPercentageController@profitPercentageIndex')->name('profitPercentageIndex');
    Route::get('/profit-percentage-datatable', 'Calculator\AdminProfitPercentageController@profitPercentageDataTables');
    Route::get('/profit-percentage-index/add', 'Calculator\AdminProfitPercentageController@getAddNewProfitPercentagePage')->name('getAddNewProfitPercentagePage');
    Route::post('/profit-percentage-index/save', 'Calculator\AdminProfitPercentageController@saveNewProfitPercentage')->name('saveNewProfitPercentage');
    Route::get('/profit-percentage-index/edit/{id}', 'Calculator\AdminProfitPercentageController@getProfitPercentageEditPage');
    Route::post('/profit-percentage-index/update', 'Calculator\AdminProfitPercentageController@updateProfitPercentage')->name('updateProfitPercentage');
    Route::post('/profit-percentage-index/remove', 'Calculator\AdminProfitPercentageController@removeProfitPercentage');

    // start Calculator Setting  ( Product Type / First Batch)
    Route::get('/first-batch-index', 'Calculator\ProductTypeController@getAllFirstBatchIndex')->name('firstBatchIndex');
    Route::get('/first-batch-datatable', 'Calculator\ProductTypeController@firstBatchIndexDataTables');
    Route::get('/first-batch/add', 'Calculator\ProductTypeController@addNewFirstBatchItem')->name('addNewFirstBatchItem');
    Route::post('/first-batch-save', 'Calculator\ProductTypeController@saveNewFirstBatch')->name('saveNewFirstBatch');
    Route::get('/first-batch-show/{id}', 'Calculator\ProductTypeController@showFirstBatch')->name('showFirstBatch');
    Route::post('/first-batch-update', 'Calculator\ProductTypeController@updateFirstBatchItem')->name('updateFirstBatchItem');
    Route::post('/first-batch-remove', 'Calculator\ProductTypeController@removeFirstBatchItem');
    // start Calculator Setting  ( Product Type / Product Types)
    Route::get('/product-type-index', 'Calculator\ProductTypeController@getAllProductTypesIndex')->name('productTypeIndex');
    Route::get('/product-type-datatable', 'Calculator\ProductTypeController@kindsOfProductIndexDataTables');
    Route::get('/product-type-add', 'Calculator\ProductTypeController@addNewProductTypeItem')->name('addNewProductTypeItem');
    Route::post('/product-type-save', 'Calculator\ProductTypeController@saveNewProductTypeItem')->name('saveNewProductTypeItem');
    Route::get('/product-type-show/{id}', 'Calculator\ProductTypeController@showProductTypeItem');
    Route::post('/product-type-update', 'Calculator\ProductTypeController@updateProductTypeItem')->name('updateProductTypeItem');
    Route::post('/product-type-remove', 'Calculator\ProductTypeController@deleteProductTypeItem');
    // End Calculator Setting (product Type / Product Types)
    // ------------------------ //
    // start Calculator Setting (product Type Check Total)
    Route::get('/product-type-check-total-index', 'Calculator\ProductTypeController@productTypeCheckTotalIndex')->name('productTypeCheckTotalIndex');
    Route::get('/product-type-check-total-datatable', 'Calculator\ProductTypeController@productTypeCheckTotalIndexDataTable');
    Route::get('/product-type-check-total-add', 'Calculator\ProductTypeController@addNewProductTypeCheckTotalItem')->name('addNewProductTypeCheckTotalItem');
    Route::post('/product-type-check-total-save', 'Calculator\ProductTypeController@saveNewProductTypeCheckTotalItem')->name('saveNewProductTypeCheckTotalItem');
    Route::get('/product-type-check-total-show/{id}', 'Calculator\ProductTypeController@showProductTypeCheckTotalItem');
    Route::post('/product-type-check-total-update', 'Calculator\ProductTypeController@updateProductTypeCheckTotalItem')->name('updateProductTypeCheckTotalItem');
    Route::post('/product-type-check-total-remove', 'Calculator\ProductTypeController@deleteProductTypeCheckTotalItem');
    // End Calculator Setting (product Type Check Total)
    // ---------------------------- //
    // Start Calculator Setting (property status rule)
    Route::get('/property-status-rule-index', 'Calculator\PropertyStatusRuleController@propertyStatusRuleIndex')->name('propertyStatusRuleIndex');
    Route::get('/property-status-rule-datatable', 'Calculator\PropertyStatusRuleController@propertyStatusRuleIndexDataTable');
    Route::get('/property-status-rule-add', 'Calculator\PropertyStatusRuleController@addNewPropertyStatusRuleItem')->name('addNewPropertyStatusRuleItem');
    Route::post('/property-status-rule-save', 'Calculator\PropertyStatusRuleController@saveNewPropertyStatusRuleItem')->name('saveNewPropertyStatusRuleItem');
    Route::get('/property-status-rule-show/{id}', 'Calculator\PropertyStatusRuleController@showPropertyStatusRuleItem');
    Route::post('/property-status-rule-update', 'Calculator\PropertyStatusRuleController@updatePropertyStatusRuleItem')->name('updatePropertyStatusRuleItem');
    Route::post('/property-status-rule-remove', 'Calculator\PropertyStatusRuleController@deletePropertyStatusRuleItem');
    // End Calculator Setting ( property Status Rule )
    // ---------------------------------------//
    // Start Calculator Setting (CalculatorRule)
    Route::get('/calculator-rules-index', 'Calculator\CalculatorRuleController@getCalculatorRuleIndex')->name('calculatorRuleIndex');
    Route::get('/calculator-rules-datatable', 'Calculator\CalculatorRuleController@calculatorRuleDataTable');
    Route::get('/calculator-rules-add', 'Calculator\CalculatorRuleController@addNewCalculatorRuleItem')->name('addNewCalculatorRuleItem');
    Route::post('/calculator-rules-save', 'Calculator\CalculatorRuleController@saveNewCalculatorRuleItem')->name('saveNewCalculatorRuleItem');
    Route::get('/calculator-rules-show/{id}', 'Calculator\CalculatorRuleController@showCalculatorRuleItem');
    Route::post('/calculator-rules-update', 'Calculator\CalculatorRuleController@updateCalculatorRuleItem')->name('updateCalculatorRuleItem');
    Route::post('/calculator-rules-remove', 'Calculator\CalculatorRuleController@deleteCalculatorRuleItem');
    // End Calculator Setting (CalculatorRule)
    // ---------------------------------------//
    // Start Calculator Setting (Rules Without Transfer)
    Route::get('without-transfer-index', 'Calculator\RulesController@rulesWithoutTransferIndex')->name('rulesWithoutTransferIndex');
    Route::get('without-transfer-datatable', 'Calculator\RulesController@rulesWithoutTransferDataTable');
    Route::get('/without-transfer-add', 'Calculator\RulesController@addNewWithoutTransferRuleItem')->name('addNewWithoutTransferRuleItem');
    Route::post('/without-transfer-save', 'Calculator\RulesController@saveNewWithoutTransferRule')->name('saveNewWithoutTransferRule');
    Route::get('/without-transfer-show/{id}', 'Calculator\RulesController@showWithoutTransferRuleItem');
    Route::post('/without-transfer-update', 'Calculator\RulesController@updateWithoutTransferRuleItem')->name('updateWithoutTransferRuleItem');
    Route::post('/without-transfer-remove', 'Calculator\RulesController@deleteWithoutTransferRuleItem');

    // End Calculator Setting (Rules Without Transfer)
    // --------------------------------- //
    // Start Calculator Setting (calculator Settings)
    Route::get('/calculator-setting', 'Calculator\SettingsController@getCalculatorSettingsValue')->name('getCalculatorSettings');
    Route::post('/calculator-setting', 'Calculator\SettingsController@updateCalculatorSetting')->name('updateCalculatorSetting');
    // End Calculator Setting (calculator Settings)
    // --------------------------------- //
    // Start Calculator Setting (support Installment)
    Route::get('/support-installment-index', 'Calculator\SupportInstallmentController@getSupportInstallmentIndex')->name('supportInstallment');
    Route::get('/support-installment-datatable', 'Calculator\SupportInstallmentController@getSupportInstallmentDataTable');
    Route::get('/support-installment-add', 'Calculator\SupportInstallmentController@addNewSupportInstallmentItem')->name('addNewSupportInstallmentItem');
    Route::post('/support-installment-save', 'Calculator\SupportInstallmentController@saveNewSupportInstallmentItem')->name('saveNewSupportInstallmentItem');
    Route::get('/support-installment/{id}', 'Calculator\SupportInstallmentController@getSupportInstallmentItem');
    Route::post('/support-installment', 'Calculator\SupportInstallmentController@updateSupportInstallmentItem')->name('updateSupportInstallmentItem');
    Route::post('/support-installment-remove', 'Calculator\SupportInstallmentController@removeSupportInstallmentItem');
    // End Calculator Setting (AvailableExtended)
    Route::get('setting/welcome-messages', 'WelcomeMessageSettingController@index')->name('welcomeMessage');
    Route::get('setting/welcome-messages-datatable', 'WelcomeMessageSettingController@indexDataTable');
    Route::get('setting/welcome-messages/add', 'WelcomeMessageSettingController@addPage')->name('addWelcomeMessagePage');
    Route::post('setting/welcome-messages/save', 'WelcomeMessageSettingController@store')->name('storeNewWelcomeMessage');
    Route::get('setting/welcome-messages/{id}', 'WelcomeMessageSettingController@edit');
    Route::post('setting/welcome-messages/update', 'WelcomeMessageSettingController@update')->name('updateWelcomeMessage');
    Route::post('setting/welcome-messages/remove', 'WelcomeMessageSettingController@destroy');

    // --------------------------------- //
    // Start Calculator Setting (support Installment)
    Route::get('/available-extended-index', 'Calculator\SupportInstallmentController@getAvailableExtendedIndex')->name('availableExtended');
    Route::get('/available-extended-datatable', 'Calculator\SupportInstallmentController@getAvailableExtendedDataTable');
    Route::get('/available-extended-add', 'Calculator\SupportInstallmentController@addNewAvailableExtendedItem')->name('addNewAvailableExtendedItem');
    Route::post('/available-extended-save', 'Calculator\SupportInstallmentController@saveNewAvailableExtendedItem')->name('saveNewAvailableExtendedItem');
    Route::get('/available-extended/{id}', 'Calculator\SupportInstallmentController@getAvailableExtendedItem');
    Route::post('/available-extended', 'Calculator\SupportInstallmentController@updateAvailableExtendedItem')->name('updateAvailableExtendedItem');
    Route::post('/available-extended-remove', 'Calculator\SupportInstallmentController@removeAvailableExtendedItem');
    // End Calculator Setting (Available Extended)
    // --------------------------------- //
    // Start Calculator Setting (Salary Deduction)
    Route::get('/salary-deduction-index', 'Calculator\SupportInstallmentController@getSalaryDeductionIndex')->name('SalaryDeduction');
    Route::get('/salary-deduction-datatable', 'Calculator\SupportInstallmentController@getSalaryDeductionDataTable');
    Route::get('/salary-deduction-add', 'Calculator\SupportInstallmentController@addNewSalaryDeductionItem')->name('addNewSalaryDeductionItem');
    Route::post('/salary-deduction-save', 'Calculator\SupportInstallmentController@saveNewSalaryDeductionItem')->name('saveNewSalaryDeductionItem');
    Route::get('/salary-deduction/{id}', 'Calculator\SupportInstallmentController@getSalaryDeductionItem');
    Route::post('/salary-deduction', 'Calculator\SupportInstallmentController@updateSalaryDeductionItem')->name('updateSalaryDeductionItem');
    Route::post('/salary-deduction-remove', 'Calculator\SupportInstallmentController@removeSalaryDeductionItem');
    // End Calculator Setting (Salary Deduction)
    // --------------------------------- //
    // Start Calculator Setting (Salary Equation)
    Route::get('/salary-equation-index', 'Calculator\SupportInstallmentController@getSalaryEquationIndex')->name('SalaryEquation');
    Route::get('/salary-equation-datatable', 'Calculator\SupportInstallmentController@getSalaryEquationDataTable');
    Route::get('/salary-equation-add', 'Calculator\SupportInstallmentController@addNewSalaryEquationItem')->name('addNewSalaryEquationItem');
    Route::post('/salary-equation-save', 'Calculator\SupportInstallmentController@saveNewSalaryEquationItem')->name('saveNewSalaryEquationItem');
    Route::get('/salary-equation/{id}', 'Calculator\SupportInstallmentController@getSalaryEquationItem');
    Route::post('/salary-equation', 'Calculator\SupportInstallmentController@updateSalaryEquationItem')->name('updateSalaryEquationItem');
    Route::post('/salary-equation-remove', 'Calculator\SupportInstallmentController@removeSalaryEquationItem');
    // End Calculator Setting (Salary Equation)

    //Training Premations:::
    Route::get('/trainingpremtion', 'AdminController@trainingPremations')->name('trainingPremtions');
    Route::post('/addtraningpremtion', 'AdminController@addNewPremtion')->name('newPremtion');
    Route::get('/removepremtion', 'AdminController@removePremtion')->name('removePremtion');
    Route::post('/updatepremtion', 'AdminController@updatePremtion')->name('updatePremtion');

    //ANNONCMENT::
    Route::get('/announcements', 'AdminController@allAnnouncements')->name('announcements');
    Route::get('/announcements-datatable', 'AdminController@allAnnouncements_datatable');

    Route::get('/addannouncepage', 'AdminController@addAnnouncePage')->name('addAnnouncePage');
    Route::post('/addnewannounce', 'AdminController@addNewAnnounce')->name('addNewAnnounce');

    Route::post('/updateannouncetatus', 'AdminController@updateAnnounceStatus')->name('updateAnnounceStatus');
    Route::post('/deleteannounce', 'AdminController@deleteAnnounce')->name('deleteAnnounce');

    Route::get('/editannouncepage/{id}', 'AdminController@editAnnouncePage')->name('editAnnouncePage');
    Route::post('/editannounce', 'AdminController@editAnnounce')->name('editAnnounce');
    Route::get('/openannouncefile/{id}', 'AdminController@openAnnounceFile')->name('openAnnounceFile');
    Route::get('/deleteannouncefile/{id}', 'AdminController@deleteAnnounceFile')->name('deleteAnnounceFile');

    Route::get('/openannouncepage/{id}', 'AdminController@openAnnouncePage')->name('openAnnouncePage');

    # Real Estate
    Route::get('customer/real-estates', [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'customerRealEstatesPage'])->name('customer_real_estate');
    Route::get('customer/real-estates-index', [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'customerRealEstatesData']);
    Route::get('customer/real-estates-show/{id}', [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'customerShowRealEstatesData'])->name('customerShowRealEstatesData');
    Route::get('collaborator/real-estates', [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'collaboratorRealEstates'])->name('collaborator_real_estate');
    Route::get('collaborator/real-estates-index', [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'collaboratorRealEstatesData']);
    Route::get('collaborator/real-estates-show/{id}',  [\App\Http\Controllers\V2\Admin\RealEstate\RealEstateController::class, 'collaboratorRealEstatesDataShow'])->name('show');
    //USER::
    Route::get('/users', 'AdminController@allUsers')->name('users');
    //This Route to show dataTabel in view(Admin.Users.myUsers)
    Route::get('/myusers-datatable', 'AdminController@allUsers_datatable');

    Route::get('/colloberatorusers', 'AdminController@allcolloberatorUsers')->name('colloberatorusers');
    //This Route to show dataTabel in view(Admin.Users.myUsers)
    Route::get('/colloberatorusers-datatable', 'AdminController@colloberator_datatable');

    Route::get('/archusers', 'AdminController@archUsers')->name('archUsers');
    //This Route to show dataTabel in view(Admin.Users.archUsers)
    Route::get('/archusers-datatable', 'AdminController@archUsers_datatable');

    Route::get('/adduserpage', 'AdminController@addUserPage')->name('addUserPage');
    Route::post('/adduser', 'AdminController@addUser')->name('addUser');

    Route::post('/updateuserstatus', 'AdminController@updateUserStatus')->name('updateUserStatus');
    Route::post('/deleteuser', 'AdminController@deleteUser')->name('deleteUser');
    Route::get('/restoreuser/{id}', 'AdminController@restoreUser')->name('restoreUser');

    Route::post('/edituser', 'AdminController@editUser')->name('editUser');

    Route::get('/getUser', 'AdminController@getUser')->name('getUser');

    Route::post('/archuserarr', 'AdminController@archUserArr')->name('archUserArray');
    Route::post('/restuserarr', 'AdminController@restUserArr')->name('restUserArray');

    //

    //HELP DESK
    Route::get('/helpDeskPage', 'AdminController@helpDeskPage')->name('helpDeskPage');
    Route::get('/helpDesk-datatable', 'AdminController@helpDesk_datatable');

    Route::get('/openhelpDeskPage/{id}', 'AdminController@openHelpDeskPage')->name('openHelpDeskPage');
    Route::post('/postReplayHelpDesk', 'AdminController@postReplayHelpDesk')->name('postReplayHelpDesk');
    Route::get('/canceleHelpDesk/{id}', 'AdminController@canceleHelpDesk')->name('canceleHelpDesk');
    Route::get('/completeHelpDesk/{id}', 'AdminController@completeHelpDesk')->name('completeHelpDesk');

    //MOVE TO NEED ACTION BASKET
    Route::get('/addToNeedActionReq', 'AdminController@addToNeedActionReq')->name('addToNeedActionReq');
    Route::get('/addToNeedActionReqArray', 'AdminController@addToNeedActionReqArray')->name('addToNeedActionReqArray');

    //Customer
    Route::get('/allcustomer', 'AdminController@allCustomer')->name('allCustomers');
    Route::get('/salesAgents', 'AdminController@salesAgents')->name('salesAgents');
    Route::get('/multipleSalesAgents', 'AdminController@multipleSalesAgents')->name('multipleSalesAgents');
    Route::get('/allow-recived-sales-managers', 'AdminController@allowRecievedSalesManagers')->name('allowRecievedSalesManagers');
    Route::get('/allow-recived-sales-agents', 'AdminController@allowRecievedSalesAgents')->name('allowRecievedSalesAgents');
    //This Route to show dataTabel in view(Admin.Customer.allcustomer)
    Route::get('/allcustomer-datatable', 'AdminController@allCustomer_datatable');

    Route::get('/all-customers/reset-password/{id}', 'AdminController@customerResetPassword')->name('customerResetPassword');
    Route::post('/all-customers/reset-password/', 'AdminController@customerUpdatePassword')->name('customerUpdatePassword');

    /*
    Route::get('/archcustomerpage', 'AdminController@archCustomerPage')->name('archCustomerPage');
    //This Route to show dataTabel in view(Agent.Customer.archCustomers)
    Route::get('/archcustomerpage-datatable', 'AdminController@archCustomerPage_datatable');
    */

    Route::get('/updatecustomer', 'AdminController@updatecustomer')->name('updateCustomer');
    Route::get('/customerprofile/{id}', 'AdminController@customerProfile')->name('profileCustomer');

    Route::Post('/archivecustomer', 'AdminController@archiveCustomer')->name('archCustomer');
    Route::get('/restorecustomer/{id}', 'AdminController@restoreCustomer')->name('restoreCustomer');

    Route::post('/archcustarr', 'AdminController@archCustArr')->name('archCustArray');
    Route::post('/restcustarr', 'AdminController@restCustArr')->name('restCustArray');

    Route::post('/editcustomer', 'AdminController@editCustomer')->name('editCustomer');

    Route::get('/addcustomer', 'AdminController@addCustomer_page')->name('addPage');
    Route::post('/add', 'AdminController@addCustomer')->name('addCustomer');

    Route::get('/sendcustomerarray', 'AdminController@sendCustomerArray')->name('sendCustomerArray');
    Route::get('/customer-messages-history/{customerId}', 'AdminController@getCustomerHistory')->name('getCustomerHistory');
    Route::get('/customer-messages-history-datatable/{customerId}', 'AdminController@getCustomerHistory_datatable')->name('messages.histories');

    //REQUEST:
    Route::get('/myreqs', 'AdminController@myReqs')->name('myRequests');
    //This Route to show dataTabel in view(AdminController.Request.myReqs)
    Route::get('/myreqs-datatable', 'AdminController@myReqs_datatable');

    Route::get('/agentrecivedreqs', 'AdminController@agentRecivedReqs')->name('agentRecivedRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.completedReqs)
    Route::get('/agentrecivedreqs-datatable', 'AdminController@agentRecivedReqs_datatable');

    Route::get('/archreqs', 'AdminController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(GeneralManager.Request.archReqs)
    Route::get('/archreqs-datatable', 'AdminController@archReqs_datatable');

    Route::get('/staredreqs', 'AdminController@starReqs')->name('staredRequests');
    //This Route to show dataTabel in view(SalesManagerController.REQUEST.starReqs)
    Route::get('/staredreqs-datatable', 'AdminController@starReqs_datatable');

    Route::get('/needactionreqsnew', 'AdminController@needActionReqsNew')->name('needActionRequestsNew');
    Route::get('/needactionreqs-datatablenew', 'AdminController@needActionReqs_datatableNew');

    Route::get('/needactionreqsdone', 'AdminController@needActionReqsDone')->name('needActionRequestsDone');
    Route::get('/needactionreqs-datatabledone', 'AdminController@needActionReqs_datatableDone');

    Route::get('/followreqs', 'AdminController@followReqs')->name('followedRequests');
    //This Route to show dataTabel in view(Agent.Customer.followReqs)
    Route::get('/followreqs-datatable', 'AdminController@followReqs_datatable');

    Route::get('/waiting-reqs-new', 'AdminController@waitingReqsNew')->name('waitingReqsNew');
    Route::get('/waiting-reqs-datatable-new', 'AdminController@waitingReqs_datatableNew');

    Route::get('/waiting-reqs-done', 'AdminController@waitingReqsDone')->name('waitingReqsDone');
    Route::get('/waiting-reqs-datatable-done', 'AdminController@waitingReqs_datatableDone');

    Route::get('/update-waiting-req', 'AdminController@updateWaitingReq')->name('updateWaitingReq');
    Route::get('/update-waiting-req-array', 'AdminController@updateWaitingReqArray')->name('updateWaitingReqArray');

    Route::get('/addTo-waiting-Req', 'AdminController@addToWaitingReq')->name('addToWaitingReq');
    Route::get('/addTo-waiting-Req-Array', 'AdminController@addToWaitingReqArray')->name('addToWaitingReqArray');

    Route::get('/move-waiting-req-array-agent', 'AdminController@moveWaitingReqToAnotherArrayAgent')->name('moveWaitingReqToAnotherArrayAgent');


     # quality req need to be turned
     Route::get('/needToBeTurnedReqNew', 'AdminController@needToBeTurnedReqNew')->name('needToBeTurnedReqNew');
     Route::get('/needToBeTurnedReqNew-datatable', 'AdminController@needToBeTurnedReqNew_datatable');

     Route::get('/moveNeedToBeTurnedReq', 'AdminController@moveNeedToBeTurnedReq')->name('moveNeedToBeTurnedReq');
     Route::get('/moveMoveNeedToBeTurnedReqArray', 'AdminController@moveMoveNeedToBeTurnedReqArray')->name('moveMoveNeedToBeTurnedReqArray');

     Route::get('/rejectNeedToBeTurnedReq', 'AdminController@rejectNeedToBeTurnedReq')->name('rejectNeedToBeTurnedReq');

     Route::get('/needToBeTurnedReqDone', 'AdminController@needToBeTurnedReqDone')->name('needToBeTurnedReqDone');
     Route::get('/needToBeTurnedReqDone-datatable', 'AdminController@needToBeTurnedReqDone_datatable');

    //*****************************************************************************************************************

    Route::get('/restreqs/{id}', 'AdminController@restReq')->name('restoreRequest');

    Route::post('/archreq/{$id}', 'AdminController@archReq')->name('archRequset');
    Route::post('/archreqarr', 'AdminController@archReqArr')->name('archReqArray');

    //FundingRequest
    Route::get('/fundingreqpage/{id}', 'AdminController@fundingreqpage')->name('fundingRequest');
    Route::post('/updatefunding', 'AdminController@updatefunding')->name('updateFunding');

    Route::get('/openfile/{id}', 'AdminController@openFile')->name('openFile');
    Route::get('/downloadfile/{id}', 'AdminController@downloadFile')->name('downFile');
    Route::post('/uploadfile', 'AdminController@uploadFile')->name('uploadFile');

    //MorPurRequest
    Route::get('/morpurpage/{id}', 'AdminController@morPurpage')->name('morPurRequest');

    //MoveRequest
    Route::get('/movereq', 'AdminController@moveReqToAnother')->name('moveReqToAnother');
    Route::get('/movereqneedaction', 'AdminController@moveReqNeedActionToAnother')->name('moveReqNeedActionToAnother');
    Route::get('/movereqarray', 'AdminController@moveReqToAnotherArray')->name('moveReqToAnotherArray');
    Route::get('/movereqarrayagent', 'AdminController@moveReqToAnotherArrayAgent')->name('moveReqToAnotherArrayAgent');
    Route::get('/moveneedreqarrayagent', 'AdminController@moveNeedReqToAnotherArrayAgent')->name('moveNeedReqToAnotherArrayAgent');
    Route::get('/movependingreq', 'AdminController@movePendingReqToAnother')->name('movePendingReqToAnother');
    Route::get('/movependingreqarray', 'AdminController@movePendingReqToAnotherArray')->name('movePendingReqToAnotherArray');

    //add REQ to quality
    Route::get('/addreqtoquality', 'AdminController@addReqToQuality')->name('addReqToQuality');
    Route::get('/addreqtoqualityarray', 'AdminController@addReqToQualityArray')->name('addReqToQualityArray');
    Route::post('/postMoveNeedActionsToQuality', 'AdminController@postMoveNeedActionsToQuality')->name('postMoveNeedActionsToQuality');

    #update need to action req status
    Route::get('/updateneedactionreq', 'AdminController@updateNeedActionReq')->name('updateNeedActionReq');
    Route::get('/updateneedactionreqarray', 'AdminController@updateNeedActionReqArray')->name('updateNeedActionReqArray');

    Route::post('/moveRequestsToQuality', 'AdminController@moveRequestsToQuality')->name('moveRequestsToQuality');


    # remove sesstion filter
    Route::get('/remove_session_filter', 'AdminController@remove_session_filter')->name('remove_session_filter');
    //OTARED SYENC
    Route::get('/otaredsync', 'OtaredController@otaredSync')->name('otaredSync');

    Route::get('/pending/request', 'Frontend\PendingRequestController@index')->name('PendingRequests');
    Route::get('/pending/request/datatable', 'Frontend\PendingRequestController@datatable');

    Route::resource('/pending/request/condition', 'Frontend\RequestContiotionController')->only('store');

    Route::get('/pending/getacceptedcondition', 'Frontend\RequestContiotionController@getAcceptedCondition')->name('getAcceptedCondition');

    require_once __DIR__.'/settings-routes.php';
    Route::get('/settings/difference', 'SettingsController@difference');

    Route::get('/settings/days', 'SettingsController@days_of_resubmit')->name('settings.days_of_resubmit');

    //---------------Frontend Forms Settings---------------------
    Route::get('/settings/form/{prefix}', 'SettingsController@form');
    Route::post('/settings/form/update', 'SettingsController@formUpdate')->name('settings.form.update');

    Route::get('/settings/questions', 'SettingsController@questions');
    Route::get('/settings/addquestions', 'SettingsController@addquestions');
    Route::get('/settings/delquestions/{id}', 'SettingsController@delquestions');
    Route::get('/settings/statusquestions/{id}/{status}', 'SettingsController@statusquestions');
    Route::get('/settings/editquestions/{id}', 'SettingsController@editquestions');

    Route::post('/settings/form/updatequestions', 'SettingsController@updatequestions')->name('settings.form.updatequestions');

    Route::post('/settings/form/update', 'SettingsController@formUpdate')->name('settings.form.update');
    Route::post('/settings/form/savequestions', 'SettingsController@savequestions')->name('settings.form.savequestions');

    //waiting request conditions SETTINGS
    Route::get('/settings/waiting_requests_settings', 'SettingsController@waiting_requests_settings')->name('waiting_requests_settings');
    Route::post('/settings/add_waiting_requests_conditions', 'SettingsController@add_waiting_requests_conditions')->name('add_waiting_requests_conditions');
    Route::get('/settings/remove_waiting_requests_conditions', 'SettingsController@remove_waiting_requests_conditions')->name('remove_waiting_requests_conditions');
    Route::post('/settings/update_waiting_requests_conditions', 'SettingsController@update_waiting_requests_conditions')->name('update_waiting_requests_conditions');
    Route::post('/settings/update_waiting_requests_replaytime', 'SettingsController@update_waiting_requests_replaytime')->name('update_waiting_requests_replaytime');

    //quailty conditions SETTINGS
    Route::get('/settings/stutusRequest', 'SettingsController@stutusRequest')->name('stutusRequestPage');
    Route::post('/settings/statusUpdate', 'SettingsController@addNewConditions')->name('newCondition');
    Route::get('/settings/removecondition', 'SettingsController@removeCondition')->name('removeCondition');
    Route::post('/settings/updatecondition', 'SettingsController@updateCondition')->name('updateCondition');

    Route::get('/settings/requestconditionsettings', 'SettingsController@RequestConditions')->name('requestConditionSettings');
    Route::get('/settings/addnewrequestconditionspage', 'SettingsController@addNewRequestConditionsPage')->name('addNewRequestConditionsPage');
    Route::post('/settings/addnewrequestconditions', 'SettingsController@addNewRequestConditions')->name('addNewRequestConditions');
    Route::get('/settings/removerequestcondition', 'SettingsController@removeRequestCondition')->name('removeRequestCondition');
    Route::post('/settings/updaterequestcondition', 'SettingsController@updateRequestCondition')->name('updateRequestCondition');

    //PROPERTY SETTINGS
    Route::get('/settings/showToGuestCustomer', 'SettingsController@showToGuestCustomer')->name('showToGuestCustomer');
    Route::get('/settings/updateshowToGuestCustomer', 'SettingsController@updateshowToGuestCustomer')->name('updateshowToGuestCustomer');

    //REQUEST WITHOUT UPDATE
    Route::get('/settings/requestWithoutUpdate', 'SettingsController@requestWithoutUpdatePage')->name('requestWithoutUpdatePage');
    Route::post('/updateRequestWithoutUpdate', 'SettingsController@updateRequestWithoutUpdate')->name('updateRequestWithoutUpdate');

    //PREMATION OF EDITING CALCLATER FOURMAL
    Route::get('settingsformula', 'SettingsController@formula')->name('formula.page');
    Route::post('settingsformula-agents', 'SettingsController@formulaAgents')->name('formula.agents');

    //PREMATION OF EDITING RESULTS CALCLATER FOURMAL
    Route::get('settingsformula-results', 'SettingsController@formulaResults')->name('formula.results.page');
    Route::post('settingsformula-results-agents', 'SettingsController@formulaResultsAgents')->name('formula.results.agents');

    //Agent ASK A REQUEST SETTINGS
    Route::get('/settings/agentAskRequest', 'SettingsController@askRequestSettings')->name('askRequestSettings');
    Route::post('/settings/updateaskrequestcondition', 'SettingsController@updateAskRequestCondition')->name('updateAskRequestCondition');
    Route::get('/settings/updateaskrequestactive', 'SettingsController@updateAskRequestActive')->name('updateAskRequestActive');

    Route::post('/settings/updatemovependingrequestcondition', 'SettingsController@updateMovePendingRequestCondition')->name('updateMovePendingRequestCondition');
    Route::get('/settings/updatemovependingrequestactive', 'SettingsController@updateMovePendingRequestActive')->name('updateMovePendingRequestActive');

    //QUALITY REQUEST CONDITION SETTINGS
    Route::get('/settings/updatequalityrequestactive', 'SettingsController@updateQualityRequestActive')->name('updateQualityRequestActive');

    #hasbah.net movment
    Route::get('/settings/updatehasbah_net_movment', 'SettingsController@updatehasbah_net_movment')->name('updatehasbah_net_movment');

    //IMPORTING EXCEL SHEET
    Route::get('/settings/importExcelPage', 'SettingsController@importExcelPage')->name('importExcelPage');
    Route::post('/updateagentexcel', 'SettingsController@updateAgentExcel')->name('updateAgentExcel');

    //IMPORTING EXCEL SHEET for two coulmns
    Route::get('/settings/importExcelForTwoCloumnsPage', 'SettingsController@importExcelForTwoCloumnsPage')->name('importExcelForTwoCloumnsPage');

    // import Excel sheeeet for missed call
    Route::post('/importExeal', 'SettingsController@importExcel')->name('importExcel');
    // import Excel sheeeet for two coulmns
    Route::post('/importExealForTwoCloumns', 'SettingsController@importExealForTwoCloumns')->name('importExealForTwoCloumns');

    //CLASSIFICATIONES SETTINGS
    Route::get('/settings/classifications', 'SettingsController@classificationsSettings')->name('classificationsSettings');
    Route::get('/settingClass-datatable', 'SettingsController@settingClass_datatable');

    Route::get('/settings/addclasspage', 'SettingsController@addclassPage')->name('addclassificationPage');
    Route::Post('/settings/saveclass', 'SettingsController@saveclass')->name('saveclass');

    Route::get('/settings/getclass', 'SettingsController@getclass')->name('getclass');
    Route::Post('/settings/updateclass', 'SettingsController@updateclass')->name('updateclass');

    Route::post('/settings/deleteclass', 'SettingsController@deleteclass')->name('deleteClass');

    Route::get('/settings/changeclasstype', 'SettingsController@changeClassType')->name('changeClassType');


    Route::get('/settings/sources', 'SettingsController@sourcesSettings')->name('sources.settings');
    Route::get('/setting-sources-datatable', 'SettingsController@settingSources_datatable');
    Route::get('/settings/add-sources-page', 'SettingsController@addSourcePage')->name('add.source');
    Route::post('/settings/save-sources', 'SettingsController@store')->name('save.source');
    Route::get('/settings/get-sources', 'SettingsController@getSource')->name('get.source');
    Route::post('/settings/update-sources', 'SettingsController@updateSource')->name('update.source');
    Route::post('/settings/delete-sources', 'SettingsController@deleteSource')->name('delete.source');


    //CITY SETTINGS
    Route::get('/settings/city', 'SettingsController@citySettings')->name('citySettings');
    Route::get('/settingCity-datatable', 'SettingsController@settingCity_datatable');

    Route::get('/settings/addcitypage', 'SettingsController@addcityPage')->name('addcityPage');
    Route::Post('/settings/savecity', 'SettingsController@savecity')->name('savecity');

    Route::get('/settings/getcity', 'SettingsController@getcity')->name('getcity');
    Route::Post('/settings/updatecity', 'SettingsController@updatecity')->name('updatecity');

    Route::post('/settings/deletecity', 'SettingsController@deletecity')->name('deleteCity');

    //REAL TYPE SETTINGS
    Route::get('/settings/realtype', 'SettingsController@realtypeSettings')->name('realtypeSettings');
    Route::get('/settingRealtype-datatable', 'SettingsController@settingRealtype_datatable');

    Route::get('/settings/addrealtypepage', 'SettingsController@addrealtypePage')->name('addrealtypePage');
    Route::Post('/settings/saverealtype', 'SettingsController@saverealtype')->name('saverealtype');

    Route::get('/settings/getrealtype', 'SettingsController@getrealtype')->name('getrealtype');
    Route::Post('/settings/updaterealtype', 'SettingsController@updaterealtype')->name('updaterealtype');

    Route::post('/settings/deleterealtype', 'SettingsController@deleterealtype')->name('deleteRealtype');
    // Advisor rate statistics
    Route::get('/advisor/rate-statistics', 'AdvisorRateStatisticsController@index');
});

//---------------ACCOUNTANT USER---------------------

route::group(['prefix' => 'accountant', 'as' => 'accountant.', 'middleware' => ['auth', 'accountant', 'logout']], function () {

    //Home
    Route::get('/home', 'SalesManagerController@homePage')->name('home');

    //Approve Tsaheel
    Route::get('/approvetsaheelreq/{id}', 'AccountantController@approveTsaheelReq')->name('approveTsaheelReq');

    //Reapprove Tsaheel
    Route::get('/reapprovetsaheelreq/{id}', 'AccountantController@reApproveTsaheelReq')->name('reApproveTsaheelReq');

    //Approve Wsata
    Route::get('/approvewsatareq/{id}', 'AccountantController@approveWsataReq')->name('approveWsataReq');

    //Reapprove Wsata
    Route::get('/reapprovewsatareq/{id}', 'AccountantController@reApproveWsataReq')->name('reApproveWsataReq');

    Route::get('/fundingreqpage/{id}', 'AccountantController@fundingreqpage')->name('fundingRequest');
    Route::get('/morpurpage/{id}', 'AccountantController@morPurpage')->name('morPurRequest');
});

/////////////////////////////////////////////////////////

//----------Accountant & Admin & General mnager---------------------------------

route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['auth', 'logout', 'accountantAndAdminAndGeneralManager']], function () {

    //if we click of notificationes
    Route::get('tsaheelAccountingReportWithNotifiy', 'AccountantController@tsaheelAccountingReportWithNotifiy')->name('tsaheelAccountingReportWithNotifiy');
    Route::get('wsataAccountingReportWithNotifiy', 'AccountantController@wsataAccountingReportWithNotifiy')->name('wsataAccountingReportWithNotifiy');

    Route::get('tsaheelAccountingUnderReport', 'AccountantController@tsaheelAccountingUnderReport')->name('tsaheelAccountingUnderReport');
    Route::get('/tsaheelAccountingUnderReport-datatable', 'AccountantController@tsaheelAccountingUnderReport_datatable');

    //Accounting Report (wsata & tsahil)
    Route::get('wsataAccountingUnderReport', 'AccountantController@wsataAccountingUnderReport')->name('wsataAccountingUnderReport');
    Route::get('/wsataAccountingUnderReport_datatable', 'AccountantController@wsataAccountingUnderReport_datatable');

    Route::get('tsaheelAccountingReport', 'AccountantController@tsaheelAccountingReport')->name('tsaheelAccountingReport');
    Route::get('/tsaheelAccountingReport-datatable', 'AccountantController@tsaheelAccountingReport_datatable');

    //Accounting Report (wsata & tsahil)
    Route::get('wsataAccountingReport', 'AccountantController@wsataAccountingReport')->name('wsataAccountingReport');
    Route::get('/wsataAccountingReport_datatable', 'AccountantController@wsataAccountingReport_datatable');

    //UPDATE REPORT
    Route::post('/updateNet', 'AccountantController@updateNet')->name('updateNet');
    Route::post('/updatevalueAdded', 'AccountantController@updateValueAdded')->name('updatevalueAdded');
    Route::post('/updatepursuit', 'AccountantController@updatepursuit')->name('updatepursuit');
    Route::post('/updatecomm', 'AccountantController@updateComm')->name('updateComm');
    Route::post('/updateassmentFees', 'AccountantController@updateassmentFees')->name('updateassmentFees');
    Route::post('/updatecollobreatorCost', 'AccountantController@updatecollobreatorCost')->name('updatecollobreatorCost');
    Route::post('/updatecustName', 'AccountantController@updatecustName')->name('updatecustName');

    Route::post('/updaterequestProfit', 'AccountantController@updaterequestProfit')->name('updaterequestProfit');
    Route::post('/updateagreementCost', 'AccountantController@updateagreementCost')->name('updateagreementCost');
    Route::post('/updatemarktingCompany', 'AccountantController@updatemarktingCompany')->name('updatemarktingCompany');
    Route::post('/updateFunder', 'AccountantController@updateFunder')->name('updateFunder');
    Route::post('/updateMarkter', 'AccountantController@updateMarkter')->name('updateMarkter');
    Route::post('/updatestartdate', 'AccountantController@updateStartDate')->name('updateStartDate');
    Route::post('/updateenddate', 'AccountantController@updateEndDate')->name('updateEndDate');
    Route::post('/updateAccountStatus', 'AccountantController@updateAccountStatus')->name('updateAccountStatus');
    Route::post('/updateAccountProfitPresentage', 'AccountantController@updateAccountProfitPresentage')->name('updateAccountProfitPresentage');
    Route::post('/updatemortCost', 'AccountantController@updatemortCost')->name('updatemortCost');
    Route::post('/updateTsaheelMortgageValue', 'AccountantController@updateTsaheelMortgageValue')->name('updateTsaheelMortgageValue');
    Route::post('/updateMortgageValue', 'AccountantController@updateMortgageValue')->name('updateMortgageValue');
    Route::post('/updatenatureRequest', 'AccountantController@updatenatureRequest')->name('updatenatureRequest');
});

//--------------------------------------------------------------------

//------------------------Quality User -------------------------------------

route::group(['prefix' => 'qualityManager', 'as' => 'quality.manager.', 'middleware' => ['auth', 'qualitymanager', 'logout']], function () {

    Route::resource('phones', 'AddPhonesController');
    Route::post('/phones-updates', 'AddPhonesController@MobilesUpdate');
    Route::get('/dailyprefromence-quality', 'ChartController@dailyPrefromenceChartForQuailityR')->name('dailyPrefromenceChartQuality');
    // questions
    Route::get('/questions/{id}', 'QualityController@questions')->name('questions');
    Route::get('/quality-users', 'QualityController@qualityUsers')->name('qualityUsers');
    // TASK::
    Route::get('/mytask', 'QualityController@mytask')->name('mytask');
    Route::get('/mytask_datatable', 'QualityController@mytask_datatable');

    Route::get('/senttask', 'QualityController@sentTask')->name('sentTask');
    Route::get('/senttask_datatable', 'QualityController@sentTask_datatable');

    Route::get('/completetask', 'QualityController@completedtask')->name('completetask');
    Route::get('/completetask_datatable', 'QualityController@completetask_datatable');

    Route::get('/task/{id}', 'QualityController@task')->name('task');

    Route::get('/edittask/{id}', 'QualityController@edittask')->name('edittask');

    Route::get('/alltask/{id}', 'QualityController@alltask')->name('alltask');
    Route::get('/task_datatable', 'QualityController@task_datatable');

    Route::post('/questions_post/{servID}', 'QualityController@questions_post')->name('questions_post');

    //post tast ( create task by quality )
    Route::post('/task_post', 'QualityController@task_post')->name('task_post');

    //edit task
    Route::post('/edit_task_post', 'QualityController@edit_task_post')->name('edit_task_post');

    // show_q_task
    // Route::get('/show_q_task/{id}', 'QualityController@show_q_task')->name('show_q_task');

    Route::get('/completeTask/{id}', 'QualityController@completeTask')->name('completeTask');
    Route::get('/notcompleteTask/{id}', 'QualityController@notcompleteTask')->name('notcompleteTask');
    Route::get('/canceleTask/{id}', 'QualityController@canceleTask')->name('canceleTask');

    Route::post('/completeReq', 'QualityController@completeReq')->name('completeReq');
    Route::post('/notcompleteReq', 'QualityController@notcompleteReq')->name('notcompleteReq');
    //END TASK::

    //FUNDING
    Route::get('/fundingreqpage/{id}', 'QualityController@fundingreqpage')->name('fundingRequest');
    Route::post('/updatefunding', 'QualityController@updatefunding')->name('updateFunding');
    Route::get('/morpurpage/{id}', 'QualityController@morPurpage')->name('morPurRequest');

    Route::post('/updatecomm', 'QualityController@updateComm')->name('updateComm');
    Route::post('/updateclass', 'QualityController@updateClass')->name('updateClass');

    Route::post('/uploadfile', 'QualityController@uploadFile')->name('uploadFile');

    Route::get('/openfile/{id}', 'QualityController@openFile')->name('openFile');

    Route::get('/downloadfile/{id}', 'QualityController@downloadFile')->name('downFile');
    Route::Post('/deletefile', 'QualityController@deleteFile')->name('deleFile');

    //Update new req
    Route::post('/updatenewreq', 'QualityController@updateNewReq')->name('updateNewReq');

    //REQUEST
    Route::get('/myreqs', 'QualityController@myReqs')->name('myRequests');
    Route::get('/myreqs-datatable', 'QualityController@myReqs_datatable');

    Route::get('/recivedreq', 'QualityController@recivedReqs')->name('recivedRequests');
    Route::get('/recivedreq-datatable', 'QualityController@recivedReqs_datatable');

    Route::get('/completedreq', 'QualityController@completedReqs')->name('completedRequests');
    Route::get('/completedreq-datatable', 'QualityController@completedReqs_datatable');

    Route::get('/followreq', 'QualityController@followReqs')->name('followRequests');
    Route::get('/followreq-datatable', 'QualityController@followReqs_datatable');

    Route::get('/archreqs', 'QualityController@archReqs')->name('archRequests');
    //This Route to show dataTabel in view(Agent.Request.archReqs)
    Route::get('/archreqs-datatable', 'QualityController@archReqs_datatable');
    Route::get('/restreqs/{id}', 'QualityController@restReq')->name('restoreRequest');

    //MANAGE REQS
    Route::get('/managereq/{id}', 'QualityController@manageReq')->name('manageRequest');

    //RESTORE
    Route::get('/restorereq/{id}', 'QualityController@restoreReq')->name('restoreReq');

    //Archive REQ
    Route::get('/reqarchive/{id}', 'QualityController@reqArchive')->name('archFunding');

    //add REQ to quality
    Route::get('/addreqtoquality', 'QualityController@addReqToQuality')->name('addReqToQuality');

    //DELYED TASK
    Route::get('/delayedtask', 'QualityController@delayedTask')->name('delayedTask');

    //Customer
    Route::get('/allcustomer', 'QualityController@allCustomer')->name('allCustomers');
    Route::get('/serchCustomer', 'QualityController@searchCustomer')->name('searchCustomer');

    //MOVE TO NEED ACTION BASKET
    Route::post('/translate-to-basket/{id}', 'QualityController@translate');



    # quality req need to be turned
    Route::get('/needToBeTurnedReq', 'QualityController@needToBeTurnedReq')->name('needToBeTurnedRequests');
    Route::get('/needToBeTurnedReq-datatable', 'QualityController@needToBeTurnedReq_datatable');
    Route::get('/add_need_turned_req/{id}', 'QualityController@add_needToBeTurnedReq')->name('add_needToBeTurnedReq');
    Route::get('/remove_need_turned_req/{id}', 'QualityController@remove_needToBeTurnedReq')->name('remove_needToBeTurnedReq');
});

//-------------------------All Users--------------------------------------

route::group(['prefix' => 'all', 'as' => 'all.', 'middleware' => ['auth', 'logout']], function () {

    Route::get('/reqhistory/{id}', 'UsersController@reqHistory')->name('reqHistory');
    //********************************************************************
    // Task-45 Announcements Task Start
    //********************************************************************
    Route::get('/announcements-datatable', 'UsersController@allAnnouncements_datatable')->name('allAnnouncements_datatable');
    Route::get('/announcements', 'UsersController@announcements')->name('announcements');
    Route::get('/announcement/{id}', 'UsersController@singleAnnouncement')->name('announcement');
    Route::get('/announcement/downloadfile/{id}', 'UsersController@downloadFile')->name('announcement.downFile');
    Route::get('/announcement/openfile/{id}', 'UsersController@openFile')->name('announcement.openFile');

    //********************************************************************
    #NOTIFICATION IS DONE
    Route::get('/notifiys', 'UsersController@notificationes')->name('notification');
    Route::get('/notifiys-datatable', 'UsersController@notificationes_datatable');

    // help desks page
    Route::get('/openhelpDeskPage/{id}', 'UsersController@openHelpDeskPage')->name('openHelpDeskPage');
    Route::post('/postReplayHelpDesk', 'UsersController@postReplayHelpDesk')->name('postReplayHelpDesk');

    Route::get('/notifiys_done', 'UsersController@notificationes_Done')->name('notification_Done');
    Route::get('/notifiys-datatable_done', 'UsersController@notificationes_datatable_Done');

    #NOTIFICATION IS DONE
    Route::get('/notifydone', 'UsersController@updateNotificationToDone')->name('NotificationToDone');
    Route::get('/notifydonearray', 'UsersController@updateNotificationToDoneArray')->name('NotificationToDoneArray');

    Route::get('/opennot/{id}/{notify}', 'UsersController@openReq')->name('openNotify');

    Route::get('/reqrecord', 'UsersController@reqRecords')->name('reqRecords');

    //CheckMobile
    Route::post('/checkmobile', 'UsersController@checkMobile')->name('checkMobile');

    //PrintReport
    Route::get('/printreport/{id}', 'UsersController@printReport')->name('printReport');
    Route::get('/aqarReport/{id}', 'UsersController@aqarReport')->name('aqarReport');

    Route::post('/delnotify', 'UsersController@delNotify')->name('delNotify');
    Route::post('/delnotifys', 'UsersController@delNotifys')->name('delNotifys');

    //home
    Route::get('/home', 'UsersController@homePage')->name('home');

    //Task
    Route::post('/update_task_content', 'UsersController@update_task_content')->name('update_task_content');
    Route::post('/update_task_note', 'UsersController@update_task_note')->name('update_task_note');
    Route::get('/taskReq/{id}', 'TaskController@taskReq')->name('taskReq');
    Route::get('/taskreq_datatable', 'TaskController@taskReq_datatable')->name('task_datatable');

    Route::get('/addtaskpage/{id}', 'TaskController@addTaskPage')->name('addTaskPage');
    Route::post('/task_post', 'TaskController@task_post')->name('task_post');

    Route::get('/edittask/{id}', 'TaskController@edittask')->name('edittask');
    Route::post('/edit_task_post', 'TaskController@edit_task_post')->name('edit_task_post');

    Route::post('/update_users_task_note', 'TaskController@update_users_task_note')->name('update_users_task_note');
    Route::post('/update_users_task_content', 'TaskController@update_users_task_content')->name('update_users_task_content');

    Route::get('/completeTask/{id}', 'TaskController@completeTask')->name('completeTask');
    Route::get('/notcompleteTask/{id}', 'TaskController@notcompleteTask')->name('notcompleteTask');
    Route::get('/canceleTask/{id}', 'TaskController@canceleTask')->name('canceleTask');

    Route::get('/senttask', 'TaskController@sentTask')->name('sentTask');
    Route::get('/senttask_datatable', 'TaskController@sentTask_datatable');
    Route::get('/recivedtask', 'TaskController@recivedtask')->name('recivedtask');
    Route::get('/recivedtask_datatable', 'TaskController@recivedtask_datatable');
    // notify task
    Route::get('/notify-tasks', 'TaskController@notifyTasks')->name('notifytasks');
    Route::get('/notify-tasks_datatable', 'TaskController@notifyTasks_datatable');
    // notify task end
    // notify task
    Route::get('/notify-helpdesk', 'TaskController@notifyhelpDesk')->name('notifyhelpdesk');
    Route::get('/notify-helpdesk_datatable', 'TaskController@notifyhelpDesk_datatable');
    // notify task end
    Route::get('/completedtask', 'TaskController@completedtask')->name('completedtask');
    Route::get('/completedtask_datatable', 'TaskController@completedtask_datatable');

    // show_q_task
    Route::get('/show_q_task/{id}', 'UsersController@show_q_task')->name('show_q_task');
    Route::get('/show_users_task/{id}', 'TaskController@show_users_task')->name('show_users_task');
    ///////////////////

    //-------------------------------------------------------------------------------
    //#Suggestions Claculater
    //-------------------------------------------------------------------------------
    Route::get('/suggestion-funding-year-index', 'Suggestion\AdminExtraFundingYearController@extraFundingYearIndex')->name('suggestions.extraFundingYearIndex');
    Route::get('/suggestion-funding-year-datatable/{loggedIn}', 'Suggestion\AdminExtraFundingYearController@extraFundingYearDataTables')->name('suggestion.years.datatable');
    Route::get('/suggestion-funding-year-edit/{id}', 'Suggestion\AdminExtraFundingYearController@editExtraFundingPage');
    Route::post('/suggestion-funding-year/update', 'Suggestion\AdminExtraFundingYearController@updateExtraFunding')->name('suggestions.updateExtraFunding');
    //Route::get('/banks', 'Suggestion\AdminBankController@getAllBanks')->name('suggestions.banks');

    Route::get('/suggestion-percentage-index', 'Suggestion\AdminProfitPercentageController@profitPercentageIndex')->name('suggestions.profitPercentageIndex');
    Route::get('/suggestion-percentage-datatable/{loggedIn}', 'Suggestion\AdminProfitPercentageController@profitPercentageDataTables')->name('suggestion.percentage.datatable');
    Route::get('/suggestion-percentage-index/edit/{id}', 'Suggestion\AdminProfitPercentageController@getProfitPercentageEditPage');
    Route::post('/suggestion-percentage-index/update', 'Suggestion\AdminProfitPercentageController@updateProfitPercentage')->name('suggestions.updateProfitPercentage');

    Route::get('/suggestions', 'Suggestion\SuggestionsController@index')->name('suggestions.index');
    Route::get('/suggestions-years', 'Suggestion\SuggestionsController@years')->name('suggestions.years.index');
    Route::get('/suggestions-years-datatable', 'Suggestion\SuggestionsController@yearsDataTables')->name('suggestions.years.datatable');
    Route::get('/suggestions-percentages', 'Suggestion\SuggestionsController@percentages')->name('suggestions.percentages.index');
    Route::get('/suggestions-percentages-datatable', 'Suggestion\SuggestionsController@percentagesDataTables')->name('suggestions.percentages.datatable');

    Route::post('/suggestion-year-vote', 'Suggestion\SuggestionsController@Vote')->name('suggestions.vote');
    ///////////
    Route::get('/gets-cities', 'Proper\PropertyController@cities')->name("gets.cities");
    Route::get('/gets-district', 'Proper\PropertyController@districts')->name("gets.districts");
    Route::get('/gets-areas', 'Proper\PropertyController@areas')->name("gets.areas");

    //FUNDING CALACULATOR::
    Route::get('/calculater', 'CalculaterController@index')->name('calculaterPage');
    Route::post('/calculaterApi', 'CalculaterController@calculaterApi2')->name('calculaterApi2');

    //Hijri date
    Route::post('/convertToHijri', 'UsersController@convertToHijri');
    Route::post('/convertToGregorian', 'UsersController@convertToGregorian');

    //HIDE COMMENT OF NEGATIVE CLASS
    Route::get('/hidecommentwithnegativeclass', 'UsersController@getNegativeCommentWithAgent')->name('getNegativeCommentWithAgent');

    Route::get('/gets-cities', 'Proper\PropertyController@cities')->name("gets.cities");
    Route::get('/gets-district', 'Proper\PropertyController@districts')->name("gets.districts");
    //MORTGAGE CALCULATOR::
    Route::Post('/sendMortgageData', 'UsersController@sendMortgageData')->name('sendMortgageData');
    Route::Post('/updatePersonalFundingData', 'UsersController@updatePersonalFundingData')->name('updatePersonalFundingData');
});

// pwa offline  Nael Helles
Route::get('/offline', 'PushController@offline');
//to subscribe user to notifications
Route::post('/push', 'PushController@store');
//Route::get('/push','PushController@push')->name('push');

//Route::get('/addpurchase/{id}', 'HomeController@getPurchase');
//Route::get('/getcustomerinfo', 'HomeController@getCustomerInfo');
//Route::post('/addfunding', 'HomeController@addfunding');
//Route::get('/fundingreqpage/{id}', 'HomeController@fundingreqpage')->name('fundingRequest')->middleware(['auth', 'salesagent']);
//Route::post('/updatefunding', 'HomeController@updatefunding');
Route::get('/commenthistory', 'HomeController@commentHistory');
//Route::Post('/sendfunding', 'HomeController@sendFunding')->name('sendFunding');

//Route::post('/uploadfile', 'HomeController@uploadFile');

//Route::Post('/deletefile', 'HomeController@deleteFile');

//Route::get('/reqhistory/{id}', 'HomeController@reqHistory');
//Route::get('/reqarchive/{id}', 'HomeController@reqArchive');

//Route::get('/myreqs', 'HomeController@myReqs')->name('myRequests');
//Route::get('/archreqs', 'HomeController@archReqs')->name('archRequests');
//Route::get('/restreqs/{id}', 'HomeController@restReq')->name('restoreRequest');

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

////////////////////////////////////////////////////

//-----------------------Otared API--------------------------

Route::get('/json-api', 'OtaredController@api');




//====================start real_types==============================
Route::group(['prefix'=>'admin/','namespace' => 'Admin','middleware' => ['auth','admin']], function () {
    Route::resource('real_types','RealTypeController')/*->except(['show','destory'])*/;
    Route::get('real_types_datatable','RealTypeController@datatable')->name('real_types_datatable');

    Route::resource('app_details','AppDetailsController');
});
//=======================end real_types==============================

//-----------------------------------------------------------

/*



        Route::get('/updaterequuu', function () {

            $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                array( //add it once use insertGetId
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $realID = DB::table('real_estats')->insertGetId(
                array(
                'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );

            $funID = DB::table('fundings')->insertGetId(
                array(
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );

            DB::table('requests')->where('id', 12278)
            ->update([
                'joint_id' =>  $joinID, 'real_id' => $realID, 'fun_id' => $funID,
            ]);



        });


        Route::get('/updateSalesManager', function () {

            $document = DB::table('requests')
            ->where('statusReq','>',2)
            ->update(['isSentSalesManager' => 1]);

        dd($document);
        });


        Route::get('/updateFundingManager', function () {

            $document = DB::table('requests')
            ->where('statusReq','>',5)
            ->where('type','شراء')
            ->update(['isSentFundingManager' => 1]);

        dd($document);
        });

        Route::get('/updateMortgageManager', function () {

            $document = DB::table('requests')
            ->where('statusReq','>',5)
            ->where('type','رهن')
            ->update(['isSentMortgageManager' => 1]);

        dd($document);
        });

        Route::get('/updateGeneralManager', function () {

            $document = DB::table('requests')
            ->where('statusReq','>',11)
            ->update(['isSentGeneralManager' => 1]);

        dd($document);
        });

        Route::get('/updatepresalesmaanger', function () {

            $document = DB::table('requests')
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('prepayments.payStatus','>',0)
            ->update(['prepayments.isSentSalesManager' => 1]);

        dd($document);
        });

        Route::get('/updatepremortgagemaanger', function () {

            $document = DB::table('requests')
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('prepayments.payStatus','>',4)
            ->update(['prepayments.isSentMortgageManager' => 1]);

        dd($document);
        });





        Route::get('/updateSource', function () {

            $document = DB::table('requests')
            ->where('source','TMW01')
            ->update(['collaborator_id' => 77]);

            dd($document);
        });

        Route::get('/updateSourceOtared', function () {

            $document = DB::table('requests')
            ->where('source','Otd01')
            ->update(['collaborator_id' => 17]);

            dd($document);
        });

        Route::get('/updateSourceName', function () {

            $document = DB::table('requests')
            ->whereIn('source',['Otd01','TMW01'])
            ->update(['source' => 'متعاون']);

            dd($document);
        });






        Route::get('/updateCityAll', function () {


            $real=DB::table('real_estats')->get();


            foreach ($real as $fn){

                $city=DB::table('cities')->where('value',$fn->city)
                ->first();

                if ($city != null){

                    $updatereq=DB::table('real_estats')->where('id', $fn->id)
                    ->update([
                        'city' => $city->id,
                    ]);
                }

                else{
                    $newID = DB::table('cities')->insertGetId(
                        array(
                            'value' => $fn->city,
                        )
                    );

                    $updatereq=DB::table('real_estats')->where('id', $fn->id)
                    ->update([
                        'city' => $newID,
                    ]);

                }

                $city=null;

            }

        });


        Route::get('/updaterequuu', function () {


            $reqs=DB::table('requests')->whereBetween('req_date',['2020-03-15', '2020-03-17'])
            ->get();

        $count=0;
            foreach($reqs as $req){
                if($req->joint_id == null || $req->real_id == null || $req->fun_id == null){

                    $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        array( //add it once use insertGetId
                            'customer_id' => $req->customer_id,'created_at' => (Carbon::now('Asia/Riyadh')),
                        )
                    );

                    $realID = DB::table('real_estats')->insertGetId(
                        array(
                            'customer_id' => $req->customer_id,'created_at' => (Carbon::now('Asia/Riyadh')),
                        )

                    );

                    $funID = DB::table('fundings')->insertGetId(
                        array(
                            'customer_id' => $req->customer_id, 'created_at' => (Carbon::now('Asia/Riyadh')),
                        )

                    );

                    DB::table('requests')->where('id', $req->id)
                    ->update([
                        'joint_id' =>  $joinID, 'real_id' => $realID, 'fun_id' => $funID,
                    ]);

                    $count++;

                }
            }

            dd( $count);




        });

        */

        /*
        Route::get('/updateMiliratyCustomer', function () {


            $cust = DB::table('customers')->get();


            foreach ($cust as $fn) {


                if ($fn->military_rank != null) {
                    $mili = DB::table('military_ranks')->where('value', $fn->military_rank)
                        ->first();

                    if ($mili != null) {

                        $updatereq = DB::table('customers')->where('id', $fn->id)
                            ->update([
                                'military_rank' => $mili->id,
                            ]);
                    } else {
                        $newID = DB::table('military_ranks')->insertGetId(
                            array(
                                'value' => $fn->military_rank,
                            )
                        );

                        $updatereq = DB::table('customers')->where('id', $fn->id)
                            ->update([
                                'military_rank' => $newID,
                            ]);
                    }

                    $mili = null;
                }
            }
        });

        Route::get('/updateMiliratyJoint', function () {


            $cust = DB::table('joints')->get();


            foreach ($cust as $fn) {


                if ($fn->military_rank != null) {
                    $mili = DB::table('military_ranks')->where('value', $fn->military_rank)
                        ->first();

                    if ($mili != null) {

                        $updatereq = DB::table('joints')->where('id', $fn->id)
                            ->update([
                                'military_rank' => $mili->id,
                            ]);
                    } else {
                        $newID = DB::table('military_ranks')->insertGetId(
                            array(
                                'value' => $fn->military_rank,
                            )
                        );

                        $updatereq = DB::table('joints')->where('id', $fn->id)
                            ->update([
                                'military_rank' => $newID,
                            ]);
                    }

                    $mili = null;
                }
            }
        });



        Route::get('/moveMadanyWork', function () {


            $madanyPrev = DB::table('civilian_ministries')
                ->get();





            foreach($madanyPrev as $madanyPre){

                $madanyCurrent = DB::table('madany_works')->where('value',$madanyPre->name)
                ->first();

                if(empty($madanyCurrent))
                {
                    $newID = DB::table('madany_works')->insertGetId(
                        array(
                            'value' => $madanyPre->name,
                        )
                    );
                }

                $madanyCurrent=null;

            }
        });

        Route::get('/moveAskaryWork', function () {


            $madanyPrev = DB::table('ministries')
                ->get();




            foreach($madanyPrev as $madanyPre){

                $madanyCurrent = DB::table('askary_works')->where('value',$madanyPre->name)
                ->first();

                if(empty($madanyCurrent))
                {
                    $newID = DB::table('askary_works')->insertGetId(
                        array(
                            'value' => $madanyPre->name,
                        )
                    );
                }

                $madanyCurrent=null;

            }
        });

        */

        /*
        //--------------** START CUSTOMERS INFO **--------------------------//

        Route::get('/matchCustomerClients', function () {


            $clients = DB::table('clients')
                ->get();



                $counter =0;

            foreach($clients as $client){

                $check = DB::table('cust_clints')->where('clintes_id',$client->id)
                ->first();

                if(empty($check)){

                if ($client->mobile != null){
                $cusromer = DB::table('customers')->where('mobile',$client->mobile)
                ->first();

                if(!empty( $cusromer))
                {
                    $newIDs = DB::table('cust_clints')->insertGetId(
                        array(
                            'customer_id' => $cusromer->id, 'clintes_id' =>$client->id,
                        )
                    );
                    $counter++;
                }}}



            }

            echo($counter);
        });


        Route::get('/updateBirthDate', function () {


            $customers = DB::table('customers')
                ->get();



                $counter =0;

            foreach($customers as $customer){


                if ($customer->birth_date_higri == null){
                $client = DB::table('cust_clints')->where('customer_id',$customer->id)
                ->first();

                if(!empty( $client))
                {

                    $getClient = DB::table('clients')->where('id',$client->clintes_id)
                    ->first();

                    $updatereq = DB::table('customers')->where('id', $customer->id)
                            ->update([
                                'birth_date_higri' => $getClient->dob,
                            ]);

                    $counter++;
                }}



            }

            echo($counter);
        });

        Route::get('/updateSex', function () {


            $customers = DB::table('customers')
                ->get();



                $counter =0;

            foreach($customers as $customer){


                if ($customer->sex == null){
                $client = DB::table('cust_clints')->where('customer_id',$customer->id)
                ->first();

                if(!empty( $client))
                {

                    $getClient = DB::table('clients')->where('id',$client->clintes_id)
                    ->first();

                    if ($getClient->gender == 'Male' || $getClient->gender == 'male' || $getClient->gender == 'ذكر')
                    $sex='ذكر';

                    else if ($getClient->gender == 'Female' || $getClient->gender == 'female' || $getClient->gender == 'أنثى')
                    $sex='أنثى';

                    else
                    $sex=null;


                    $updatereq = DB::table('customers')->where('id', $customer->id)
                            ->update([
                                'sex' => $sex,
                            ]);

                    $counter++;
                }}



            }

            echo($counter);
        });


        Route::get('/matchCustomerLoans', function () {


            $loans = DB::table('loan_applications')
                ->get();



                $counter =0;

            foreach($loans as $loan){



                if ($loan->client_id != null){

                    $client = DB::table('cust_clints')->where('clintes_id',$loan->client_id)
                    ->first();

                    $check = DB::table('cust_loan')->where('loan_id',$loan->id)
                    ->first();

                if(empty($check) && !empty($client))
                {
                    $newIDs = DB::table('cust_loan')->insertGetId(
                        array(
                            'cust_id' => $client->customer_id, 'loan_id' =>$loan->id,
                        )
                    );
                    $counter++;
                }}



            }

            echo($counter);
        });

        Route::get('/updateWorkInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();

                if($req->employer_id != null)
                {


                    $customer = DB::table('customers')->where('id',$loan->cust_id)
                    ->first();

                    if($customer->work == null){


                        if ($req->employer_id == 1)
                        $work= 'مدني';
                        else if ($req->employer_id == 2)
                        $work= 'عسكري';
                        else if ($req->employer_id == 3)
                        $work= 'قطاع خاص';
                        else if ($req->employer_id == 4)
                        $work= 'متقاعد';
                        else
                        $work= null;


                        $updatereq = DB::table('customers')->where('id', $customer->id)
                        ->update([
                            'work' => $work,
                        ]);

                        $counter++;
                    }



                }


            }

            echo($counter);
        });


        Route::get('/updateMiliratyRankInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();

                if($req->military_rank_id != null && $req->employer_id == 2) // askary only that has miliraty ranks
                {

                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'military_rank' => $req->military_rank_id,
                        ]);

                        $counter++;




                }


            }

            echo($counter);
        });


        Route::get('/updateAskaryWorksInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();

                if($req->ministries_id != null && $req->employer_id == 2) // askary only that has askary works
                {

                    if ($req->ministries_id == 4)
                    $mini = 17;
                    else if ($req->ministries_id == 5)
                    $mini = 18;
                    else if ($req->ministries_id == 6)
                    $mini = 19;
                    else if ($req->ministries_id == 7)
                    $mini = 20;
                    else if ($req->ministries_id == 8)
                    $mini = 21;
                    else
                    $mini =$req->ministries_id;

                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'askary_id' => $mini,
                        ]);

                        $counter++;




                }


            }

            echo($counter);
        });


        Route::get('/updateMadanyWorksInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();

                if($req->civilian_ministries_id != null && $req->employer_id == 1) // madany only that has madany works
                {

                    $customer = DB::table('customers')->where('id', $loan->cust_id)
                    ->first();

                if ($customer->madany_id == null){

                $ciliven = DB::table('civilian_ministries')->where('id',$req->civilian_ministries_id)
                ->first();

                $madany= DB::table('madany_works')->where('value',$ciliven->name)
                ->first();

                if (empty( $madany)){

                    $newIDs = DB::table('madany_works')->insertGetId(
                        array(
                            'value' => $ciliven->name,
                        )
                    );

                    $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'madany_id' => $newIDs,
                        ]);
                }
                else{
                    $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                    ->update([
                        'madany_id' =>  $madany->id,
                    ]);
                }
                        $counter++;




                }

            }


            }

            echo($counter);
        });

        Route::get('/updateJobTitleInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $customer = DB::table('customers')->where('id', $loan->cust_id)
                    ->first();


                    if ($customer->job_title == null && $req->job_positions != null){

                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'job_title' => $req->job_positions,
                        ]);

                        $counter++;

                    }


            }

            echo($counter);
        });

        Route::get('/updateSalaryInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $customer = DB::table('customers')->where('id', $loan->cust_id)
                    ->first();


                    if ($customer->salary == null && $req->customer_salary != null){

                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'salary' => $req->customer_salary,
                        ]);

                        $counter++;

                    }


            }

            echo($counter);
        });

        Route::get('/updateSalaryBankInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $customer = DB::table('customers')->where('id', $loan->cust_id)
                    ->first();


                    if ($customer->salary_id == null && $req->salary_drop_bank_id != null){


                        if ($req->salary_drop_bank_id == 7)
                        $bankID=1;
                        else if ($req->salary_drop_bank_id == 8)
                        $bankID=12;
                        else if ($req->salary_drop_bank_id == 9)
                        $bankID=7;
                        else if ($req->salary_drop_bank_id == 10)
                        $bankID=14;
                        else if ($req->salary_drop_bank_id == 11)
                        $bankID=4;
                        else if ($req->salary_drop_bank_id == 15)
                        $bankID=10;
                        else if ($req->salary_drop_bank_id == 18)
                        $bankID=9;
                        else if ($req->salary_drop_bank_id == 18)
                        $bankID=9;
                        else
                        $bankID=null;


                        if ($bankID != null)
                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'salary_id' => $bankID,
                        ]);
                        else{

                            $banks= DB::table('banks')->where('id',$req->salary_drop_bank_id)
                            ->first();

                            $Salarybanks= DB::table('salary_sources')->where('value',$banks->name)
                            ->first();

                            if (empty( $Salarybanks)){

                            $newIDs = DB::table('salary_sources')->insertGetId(
                                array(
                                    'value' => $banks->name,
                                )
                            );

                            $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                            ->update([
                                'salary_id' => $newIDs,
                            ]);
                            }
                            else{
                                $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                            ->update([
                                'salary_id' => $Salarybanks->id,
                            ]);
                            }

                        }

                        $counter++;

                    }


            }

            echo($counter);
        });


        Route::get('/updateSupportedInCustomer', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $customer = DB::table('customers')->where('id', $loan->cust_id)
                    ->first();


                    if ($customer->is_supported == null && $req->test_field != null){


                        if ($req->test_field == 'نعم' || $req->test_field == 'kul' || $req->test_field == 'مدعوم' || $req->test_field == 'تعم' || $req->test_field == 'نعم 140' || $req->test_field == 'مدعومه')
                        $is_supported ='yes';
                        else if ($req->test_field == 'غير' || $req->test_field == 'غير مدعوم' || $req->test_field == 'لا' || $req->test_field == 'لا يوجد')
                        $is_supported ='no';
                        else
                        $is_supported =null;

                        $updatereq = DB::table('customers')->where('id', $loan->cust_id)
                        ->update([
                            'is_supported' =>  $is_supported,
                        ]);

                        $counter++;

                    }


            }

            echo($counter);
        });



        //--------------** END CUSTOMERS INFO **--------------------------//



        //--------------** START JOINTS INFO **--------------------------//



        Route::get('/updateNameInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->name == null && $req->solidarity_name != null){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'name' =>  $req->solidarity_name,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->name == null && $req->solidarity_name != null){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'name' =>  $req->solidarity_name,
                            ]);
                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateMobileInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->mobile == null && $req->solidarity_mobile != null){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'mobile' =>  $req->solidarity_mobile,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->mobile == null && $req->solidarity_mobile != null){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'mobile' =>  $req->solidarity_mobile,
                            ]);
                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateWorkInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->work == null && $req->solidarity_employer != null && $req->solidarity_employer != '12000' && $req->solidarity_employer != '8000'&& $req->solidarity_employer != '9000'){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'work' =>  $req->solidarity_employer,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->work == null && $req->solidarity_employer != null && $req->solidarity_employer != '12000' && $req->solidarity_employer != '8000'&& $req->solidarity_employer != '9000'){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'work' =>  $req->solidarity_employer,
                            ]);
                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateSalaryInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->salary == null && $req->test != null && $req->test != '834,282 + 140000'){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary' =>  $req->test,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->salary == null && $req->test != null && $req->test != '834,282 + 140000'){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary' =>  $req->test,
                            ]);
                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });

        Route::get('/updateBirthInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->birth_date_higri == null && $req->solidarity_dob != null){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'birth_date_higri' =>  $req->solidarity_dob,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->birth_date_higri == null && $req->solidarity_dob != null ){

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'birth_date_higri' =>  $req->solidarity_dob,
                            ]);
                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateSalaryBankInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->salary_id == null && $req->solidarity_salary_drop_bank_id != null){


                                if ($req->solidarity_salary_drop_bank_id == 7)
                                $bankID=1;
                                else if ($req->solidarity_salary_drop_bank_id == 8)
                                $bankID=12;
                                else if ($req->solidarity_salary_drop_bank_id == 9)
                                $bankID=7;
                                else if ($req->solidarity_salary_drop_bank_id == 10)
                                $bankID=14;
                                else if ($req->solidarity_salary_drop_bank_id == 11)
                                $bankID=4;
                                else if ($req->solidarity_salary_drop_bank_id == 15)
                                $bankID=10;
                                else if ($req->solidarity_salary_drop_bank_id == 18)
                                $bankID=9;
                                else if ($req->solidarity_salary_drop_bank_id == 18)
                                $bankID=9;
                                else
                                $bankID=null;


                                if ($bankID != null)
                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary_id' =>  $req->solidarity_salary_drop_bank_id,
                            ]);

                        else{

                            $banks= DB::table('banks')->where('id',$req->salary_drop_bank_id)
                            ->first();

                            if (!empty ($banks)){
                            $Salarybanks= DB::table('salary_sources')->where('value',$banks->name)
                            ->first();

                            if (empty( $Salarybanks)){

                            $newIDs = DB::table('salary_sources')->insertGetId(
                                array(
                                    'value' => $banks->name,
                                )
                            );

                            $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary_id' =>   $newIDs,
                            ]);
                            }
                            else{
                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                            ->update([
                                'salary_id' => $Salarybanks->id,
                            ]);
                            }
                        }

                        }

                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->salary_id == null && $req->solidarity_salary_drop_bank_id != null ){


                                if ($req->solidarity_salary_drop_bank_id == 7)
                                $bankID=1;
                                else if ($req->solidarity_salary_drop_bank_id == 8)
                                $bankID=12;
                                else if ($req->solidarity_salary_drop_bank_id == 9)
                                $bankID=7;
                                else if ($req->solidarity_salary_drop_bank_id == 10)
                                $bankID=14;
                                else if ($req->solidarity_salary_drop_bank_id == 11)
                                $bankID=4;
                                else if ($req->solidarity_salary_drop_bank_id == 15)
                                $bankID=10;
                                else if ($req->solidarity_salary_drop_bank_id == 18)
                                $bankID=9;
                                else if ($req->solidarity_salary_drop_bank_id == 18)
                                $bankID=9;
                                else
                                $bankID=null;


                                if ($bankID != null)
                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary_id' =>  $req->solidarity_salary_drop_bank_id,
                            ]);

                        else{

                            $banks= DB::table('banks')->where('id',$req->salary_drop_bank_id)
                            ->first();

                            if (!empty ($banks)){
                            $Salarybanks= DB::table('salary_sources')->where('value',$banks->name)
                            ->first();

                            if (empty( $Salarybanks)){

                            $newIDs = DB::table('salary_sources')->insertGetId(
                                array(
                                    'value' => $banks->name,
                                )
                            );

                            $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'salary_id' =>   $newIDs,
                            ]);
                            }
                            else{
                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                            ->update([
                                'salary_id' => $Salarybanks->id,
                            ]);
                            }

                        }

                            $counter++; }

                            }


                        }

                    }



            }

            echo($counter);
        });



        Route::get('/updateFundingBankInJoint', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->joint_id != null){

                            $jointInfo = DB::table('joints')->where('id', $reqInfo->joint_id)
                            ->first();

                            if ($jointInfo->funding_id == null && $req->solidarity_finance_authority_bank_id != null){



                            $banks= DB::table('banks')->where('id',$req->solidarity_finance_authority_bank_id)
                            ->first();

                            if (!empty ($banks)){
                            $Salarybanks= DB::table('funding_sources')->where('value',$banks->name)
                            ->first();

                            if (empty( $Salarybanks)){

                            $newIDs = DB::table('funding_sources')->insertGetId(
                                array(
                                    'value' => $banks->name,
                                )
                            );

                            $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                'funding_id' =>   $newIDs,
                            ]);
                            }
                            else{
                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                            ->update([
                                'funding_id' => $Salarybanks->id,
                            ]);
                            }
                        }



                            $counter++;
                            }


                        }

                        else{

                            $joinID = DB::table('joints')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'joint_id' => $joinID,
                        ]);

                        $jointInfo = DB::table('joints')->where('id', $joinID)
                            ->first();

                            if ($jointInfo->funding_id == null && $req->solidarity_finance_authority_bank_id != null ){


                                $banks= DB::table('banks')->where('id',$req->solidarity_finance_authority_bank_id)
                                ->first();

                                if (!empty ($banks)){
                                $Salarybanks= DB::table('funding_sources')->where('value',$banks->name)
                                ->first();

                                if (empty( $Salarybanks)){

                                $newIDs = DB::table('funding_sources')->insertGetId(
                                    array(
                                        'value' => $banks->name,
                                    )
                                );

                                $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                    ->update([
                                    'funding_id' =>   $newIDs,
                                ]);
                                }
                                else{
                                    $updateJoint = DB::table('joints')->where('id',  $jointInfo->id)
                                ->update([
                                    'funding_id' => $Salarybanks->id,
                                ]);
                                }
                            }

                            $counter++;

                            }


                        }

                    }



            }

            echo($counter);
        });

        //--------------** END JOINTS INFO **--------------------------//




        //--------------** START REAL ESTATE INFO **--------------------------//


        Route::get('/updateNameInReal', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->real_id != null){

                            $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                            ->first();

                            if ($RealInfo->name == null && $req->property_owner_name != null){

                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'name' =>  $req->property_owner_name,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $realID = DB::table('real_estats')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'real_id' =>  $realID,
                        ]);

                        $RealInfo = DB::table('real_estats')->where('id',  $realID)
                        ->first();

                        if ($RealInfo->name == null && $req->property_owner_name != null){

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'name' =>  $req->property_owner_name,
                            ]);
                            $counter++;
                        }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateMobileInReal', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->real_id != null){

                            $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                            ->first();

                            if ($RealInfo->mobile == null && $req->property_owner_mobile != null){

                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'mobile' =>  $req->property_owner_mobile,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $realID = DB::table('real_estats')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'real_id' =>  $realID,
                        ]);

                        $RealInfo = DB::table('real_estats')->where('id',  $realID)
                        ->first();

                        if ($RealInfo->mobile == null && $req->property_owner_mobile != null){

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'mobile' =>  $req->property_owner_mobile,
                            ]);
                            $counter++;
                        }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateStatusInReal', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->real_id != null){

                            $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                            ->first();

                            if ($RealInfo->status == null && $req->building_status != null){

                                if ($req->building_status == 1)
                                $status= 'مكتمل';
                                else if ($req->building_status == 2)
                                $status= 'عظم';
                                else
                                $status= null;

                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'status' =>  $status,
                            ]);
                            $counter++;
                            }


                        }

                        else{

                            $realID = DB::table('real_estats')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'real_id' =>  $realID,
                        ]);

                        $RealInfo = DB::table('real_estats')->where('id',  $realID)
                        ->first();

                        if ($RealInfo->status == null && $req->building_status != null){

                            if ($req->building_status == 1)
                            $status= 'مكتمل';
                            else if ($req->building_status == 2)
                            $status= 'عظم';
                            else
                            $status= null;

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'status' =>   $status,
                            ]);
                            $counter++;
                        }


                        }

                    }



            }

            echo($counter);
        });

        Route::get('/updateTypeInReal', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->real_id != null){

                            $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                            ->first();

                            if ($RealInfo->type == null && $req->property_type_id != null){

                            if ($req->property_type_id == 1){
                                $type='فيلا';
                                $other_value=null;
                            }
                            else if ($req->property_type_id == 3){
                                $type='مبنى';
                                $other_value=null;
                            }
                            else if ($req->property_type_id == 5){
                                $other_value=null;
                                $type='أرض';
                            }
                            else if ($req->property_type_id == 7){
                                $type='آخر';
                                $other_value=$req->other_property_type;
                            }
                            else {
                                $type='آخر';
                                if ($req->property_type_id == 6)
                                $other_value='استراحة';
                                else if ($req->property_type_id == 4)
                                $other_value='مسطحة';
                                else
                                $other_value=null;

                            }

                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'type' =>  $type, 'other_value' =>  $other_value,
                            ]);

                            $counter++;
                            }


                        }

                        else{

                            $realID = DB::table('real_estats')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'real_id' =>  $realID,
                        ]);

                        $RealInfo = DB::table('real_estats')->where('id',  $realID)
                        ->first();

                        if ($RealInfo->type == null && $req->property_type_id != null){


                            if ($req->property_type_id == 1){
                                $type='فيلا';
                                $other_value=null;
                            }
                            else if ($req->property_type_id == 3){
                                $type='مبنى';
                                $other_value=null;
                            }
                            else if ($req->property_type_id == 5){
                                $other_value=null;
                                $type='أرض';
                            }
                            else if ($req->property_type_id == 7){
                                $type='آخر';
                                $other_value=$req->other_property_type;
                            }
                            else {
                                $type='آخر';
                                if ($req->property_type_id == 6)
                                $other_value='استراحة';
                                else if ($req->property_type_id == 4)
                                $other_value='مسطحة';
                                else
                                $other_value=null;

                            }

                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'type' =>  $type, 'other_value' =>  $other_value,
                            ]);
                            $counter++;
                        }


                        }

                    }



            }

            echo($counter);
        });


        Route::get('/updateCityInReal', function () {


            $loans = DB::table('cust_loan2')
                ->get();



                $counter =0;

            foreach($loans as $loan){

                $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                    ->first();


                    $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                    ->first();

                    if (!empty($reqInfo )){

                        if ($reqInfo->real_id != null){

                            $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                            ->first();

                            if ($RealInfo->city == null && $req->property_city != null){

                                $city= DB::table('cities')->where('value', 'like', '%' . $req->property_city . '%')
                                ->first();

                                if (!empty($city)){
                                    $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                    ->update([
                                    'city' =>  $city->id,
                                ]);
                                }
                                else{
                                    $cityID = DB::table('cities')->insertGetId(
                                        array( //add it once use insertGetId
                                    'value' => $req->property_city,
                                        )
                                    );
                                    $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                    ->update([
                                    'city' =>  $cityID,
                                ]);
                                }

                            $counter++;
                            }


                        }

                        else{

                            $realID = DB::table('real_estats')->insertGetId(
                                array( //add it once use insertGetId
                            'customer_id' => $loan->cust_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                                )
                            );

                            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                            ->update([
                            'real_id' =>  $realID,
                        ]);

                        $RealInfo = DB::table('real_estats')->where('id',  $realID)
                        ->first();

                        if ($RealInfo->city == null && $req->property_city != null){

                            $city= DB::table('cities')->where('value', 'like', '%' . $req->property_city . '%')
                            ->first();

                            if (!empty($city)){
                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'city' =>  $city->id,
                            ]);
                            }
                            else{
                                $cityID = DB::table('cities')->insertGetId(
                                    array( //add it once use insertGetId
                                'value' => $req->property_city,
                                    )
                                );
                                $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                                ->update([
                                'city' =>  $cityID,
                            ]);
                            }
                            $counter++;
                        }


                        }

                    }

            }

            echo($counter);
        });

        Route::get('/updateCostInReal', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->real_id != null){

                        $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                        ->first();

                        if ($RealInfo->cost == null && $req->property_value != null){

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'cost' =>  $req->property_value,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $realID = DB::table('real_estats')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'real_id' =>  $realID,
                    ]);

                    $RealInfo = DB::table('real_estats')->where('id',  $realID)
                    ->first();

                    if ($RealInfo->cost == null && $req->property_value != null){

                        $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                        ->update([
                        'cost' =>  $req->property_value,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateAgeInReal', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->real_id != null){

                        $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                        ->first();

                        if ($RealInfo->age == null && $req->property_age != null){

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'age' =>  $req->property_age,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $realID = DB::table('real_estats')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'real_id' =>  $realID,
                    ]);

                    $RealInfo = DB::table('real_estats')->where('id',  $realID)
                    ->first();

                    if ($RealInfo->age == null && $req->property_age != null){

                        $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                        ->update([
                        'age' =>  $req->property_age,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateIsEvaluatedInReal', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->real_id != null){

                        $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                        ->first();

                        if ($RealInfo->evaluated == null && $req->is_property_evaluated != null){


                            if ($req->is_property_evaluated == 'yes')
                            $evaluated='نعم';
                            else if ($req->is_property_evaluated == 'no')
                            $evaluated='لا';
                            else
                            $evaluated=null;

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'evaluated' =>  $evaluated,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $realID = DB::table('real_estats')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'real_id' =>  $realID,
                    ]);

                    $RealInfo = DB::table('real_estats')->where('id',  $realID)
                    ->first();

                    if ($RealInfo->evaluated == null && $req->is_property_evaluated != null){

                        if ($req->is_property_evaluated == 'yes')
                        $evaluated='نعم';
                        else if ($req->is_property_evaluated == 'no')
                        $evaluated='لا';
                        else
                        $evaluated=null;

                        $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                        ->update([
                        'evaluated' =>  $evaluated,
                    ]);

                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateIsTenantInReal', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->real_id != null){

                        $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                        ->first();

                        if ($RealInfo->tenant == null && $req->is_property_tenants != null){


                            if ($req->is_property_tenants == 'yes')
                            $tenant='نعم';
                            else if ($req->is_property_tenants == 'no')
                            $tenant='لا';
                            else
                            $tenant=null;

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'tenant' =>  $tenant,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $realID = DB::table('real_estats')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'real_id' =>  $realID,
                    ]);

                    $RealInfo = DB::table('real_estats')->where('id',  $realID)
                    ->first();

                    if ($RealInfo->tenant == null && $req->is_property_tenants != null){

                        if ($req->is_property_tenants == 'yes')
                        $tenant='نعم';
                        else if ($req->is_property_tenants == 'no')
                        $tenant='لا';
                        else
                        $tenant=null;

                        $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                        ->update([
                        'tenant' =>  $tenant,
                    ]);

                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateIsMortgageInReal', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->real_id != null){

                        $RealInfo = DB::table('real_estats')->where('id', $reqInfo->real_id)
                        ->first();

                        if ($RealInfo->mortgage == null && $req->is_property_mortgaged != null){


                            if ($req->is_property_mortgaged == 'yes')
                            $mortgage='نعم';
                            else if ($req->is_property_mortgaged == 'no')
                            $mortgage='لا';
                            else
                            $mortgage=null;

                            $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                            ->update([
                            'mortgage' =>  $mortgage,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $realID = DB::table('real_estats')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'real_id' =>  $realID,
                    ]);

                    $RealInfo = DB::table('real_estats')->where('id',  $realID)
                    ->first();

                    if ($RealInfo->mortgage == null && $req->is_property_mortgaged != null){

                        if ($req->is_property_mortgaged == 'yes')
                        $mortgage='نعم';
                        else if ($req->is_property_mortgaged == 'no')
                        $mortgage='لا';
                        else
                        $mortgage=null;

                        $updateReal = DB::table('real_estats')->where('id',  $RealInfo->id)
                        ->update([
                        'mortgage' =>  $mortgage,
                    ]);

                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });
        //--------------** END REAL ESTATE INFO **--------------------------//




        //--------------** START Funding INFO **--------------------------//

        Route::get('/updateSourceInFunding', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->fun_id != null){

                        $FundInfo = DB::table('fundings')->where('id', $reqInfo->fun_id)
                        ->first();

                        if ($FundInfo->funding_source == null && $req->finance_authority_bank_id != null){

                            $banks= DB::table('banks')->where('id',$req->finance_authority_bank_id)
                            ->first();

                            if (!empty ($banks)){
                                $Salarybanks= DB::table('funding_sources')->where('value',$banks->name)
                                ->first();

                                if (empty( $Salarybanks)){

                                $newIDs = DB::table('funding_sources')->insertGetId(
                                    array(
                                        'value' => $banks->name,
                                    )
                                );

                                $updateJoint = DB::table('fundings')->where('id',  $FundInfo->id)
                                    ->update([
                                    'funding_source' =>   $newIDs,
                                ]);
                                }
                                else{
                                    $updateJoint = DB::table('fundings')->where('id',  $FundInfo->id)
                                ->update([
                                    'funding_source'=> $Salarybanks->id,
                                ]);
                                }
                                }


                        $counter++;
                        }


                    }

                    else{

                        $funID = DB::table('fundings')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'fun_id' => $funID,
                    ]);

                    $FundInfo = DB::table('fundings')->where('id', $funID)
                        ->first();

                        if ($FundInfo->funding_source == null && $req->finance_authority_bank_id != null){

                            $banks= DB::table('banks')->where('id',$req->finance_authority_bank_id)
                            ->first();

                            if (!empty ($banks)){
                                $Salarybanks= DB::table('funding_sources')->where('value',$banks->name)
                                ->first();

                                if (empty( $Salarybanks)){

                                $newIDs = DB::table('funding_sources')->insertGetId(
                                    array(
                                        'value' => $banks->name,
                                    )
                                );

                                $updateJoint = DB::table('fundings')->where('id',  $FundInfo->id)
                                    ->update([
                                    'funding_source' =>   $newIDs,
                                ]);
                                }
                                else{
                                    $updateJoint = DB::table('fundings')->where('id',  $FundInfo->id)
                                ->update([
                                    'funding_source'=> $Salarybanks->id,
                                ]);
                                }
                                }


                        $counter++;
                        }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateDurationInFunding', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->fun_id != null){

                        $FunInfo = DB::table('fundings')->where('id', $reqInfo->fun_id)
                        ->first();

                        if ($FunInfo->funding_duration == null && $req->property_funding_duration != null){

                            $updateReal = DB::table('fundings')->where('id',  $FunInfo->id)
                            ->update([
                            'funding_duration' =>  $req->property_funding_duration,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $FunID = DB::table('fundings')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'fun_id' => $FunID,
                    ]);

                    $FunInfo = DB::table('fundings')->where('id',  $FunID)
                    ->first();

                    if ($FunInfo->funding_duration == null && $req->property_funding_duration != null){

                        $updateFun = DB::table('fundings')->where('id',  $FunInfo->id)
                        ->update([
                        'funding_duration' =>  $req->property_funding_duration,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateMonthelyInstallInFunding', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->fun_id != null){

                        $FunInfo = DB::table('fundings')->where('id', $reqInfo->fun_id)
                        ->first();

                        if ($FunInfo->monthly_in == null && $req->property_monthly_installment != null){

                            $updateReal = DB::table('fundings')->where('id',  $FunInfo->id)
                            ->update([
                            'monthly_in' =>  $req->property_monthly_installment,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $FunID = DB::table('fundings')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'fun_id' => $FunID,
                    ]);

                    $FunInfo = DB::table('fundings')->where('id',  $FunID)
                    ->first();

                    if ($FunInfo->monthly_in == null && $req->property_monthly_installment != null){

                        $updateFun = DB::table('fundings')->where('id',  $FunInfo->id)
                        ->update([
                        'monthly_in' =>  $req->property_monthly_installment,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateDedcationPreInFunding', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->fun_id != null){

                        $FunInfo = DB::table('fundings')->where('id', $reqInfo->fun_id)
                        ->first();

                        if ($FunInfo->ded_pre == null && $req->property_interest_rate_deduction_from_salary != null){

                            $updateReal = DB::table('fundings')->where('id',  $FunInfo->id)
                            ->update([
                            'ded_pre' =>  $req->property_interest_rate_deduction_from_salary,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $FunID = DB::table('fundings')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'fun_id' => $FunID,
                    ]);

                    $FunInfo = DB::table('fundings')->where('id',  $FunID)
                    ->first();

                    if ($FunInfo->ded_pre == null && $req->property_interest_rate_deduction_from_salary != null){

                        $updateFun = DB::table('fundings')->where('id',  $FunInfo->id)
                        ->update([
                        'ded_pre' =>  $req->property_interest_rate_deduction_from_salary,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updatePersonalFunCostInFunding', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                    if ($reqInfo->fun_id != null){

                        $FunInfo = DB::table('fundings')->where('id', $reqInfo->fun_id)
                        ->first();

                        if ($FunInfo->personalFun_cost == null && $req->personal_finance != null){

                            $updateReal = DB::table('fundings')->where('id',  $FunInfo->id)
                            ->update([
                            'personalFun_cost' =>  $req->personal_finance,
                        ]);
                        $counter++;
                        }


                    }

                    else{

                        $FunID = DB::table('fundings')->insertGetId(
                            array( //add it once use insertGetId
                        'customer_id' => $loan->cust_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'fun_id' => $FunID,
                    ]);

                    $FunInfo = DB::table('fundings')->where('id',  $FunID)
                    ->first();

                    if ($FunInfo->personalFun_cost == null && $req->personal_finance != null){

                        $updateFun = DB::table('fundings')->where('id',  $FunInfo->id)
                        ->update([
                        'personalFun_cost' =>  $req->personal_finance,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });



        //--------------** END Funding INFO **--------------------------//



        //--------------** START PREPAYMENT INFO **--------------------------//


        Route::get('/updateVisaInTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->visa == null && $req->tasheil_visa_card != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'visa' =>  $req->tasheil_visa_card,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->visa == null && $req->tasheil_visa_card != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'visa' =>  $req->tasheil_visa_card,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateCarInTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->carLo == null && $req->tasheil_car_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'carLo' =>  $req->tasheil_car_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->carLo == null && $req->tasheil_car_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'carLo' =>  $req->tasheil_car_loan,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updatePersonalLoInTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->personalLo == null && $req->tasheil_personal_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'personalLo' =>  $req->tasheil_personal_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->personalLo == null && $req->tasheil_personal_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'personalLo' =>  $req->tasheil_personal_loan,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateRealLoInTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->realLo == null && $req->tasheil_mortgage_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realLo' =>  $req->tasheil_mortgage_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->realLo == null && $req->tasheil_mortgage_loan != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realLo' =>  $req->tasheil_mortgage_loan,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateCreditTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->credit == null && $req->tasheil_credit_bank != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->credit == null && $req->tasheil_credit_bank != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });



        Route::get('/updateCreditTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->credit == null && $req->tasheil_credit_bank != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->credit == null && $req->tasheil_credit_bank != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateOtherTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->other == null && $req->tasheil_other != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'other' =>  $req->tasheil_other,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->other == null && $req->tasheil_other != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'other' =>  $req->tasheil_other,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateDebtTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->debt == null && $req->tasheil_total_indebtedness != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'debt' =>  $req->tasheil_total_indebtedness,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->debt == null && $req->tasheil_total_indebtedness != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'debt' =>  $req->tasheil_total_indebtedness,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateProfitPreTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->proftPre == null && $req->tasheil_quest_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'proftPre' =>  $req->tasheil_quest_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->proftPre == null && $req->tasheil_quest_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'proftPre' =>  $req->tasheil_quest_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateProfitCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->profCost == null && $req->tasheil_quest_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->tasheil_quest_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->profCost == null && $req->tasheil_quest_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->tasheil_quest_fees,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateMortPreTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->mortPre == null && $req->tasheil_mortgage_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortPre' =>  $req->tasheil_mortgage_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->mortPre == null && $req->tasheil_mortgage_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortPre' =>  $req->tasheil_mortgage_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateMortCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->mortCost == null && $req->tasheil_mortgage_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortCost' =>  $req->tasheil_mortgage_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->mortCost == null && $req->tasheil_mortgage_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortCost' =>  $req->tasheil_mortgage_fees,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updatePayPreTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentPre == null && $req->tasheil_payment_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentPre' =>  $req->tasheil_payment_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentPre == null && $req->tasheil_payment_fees_ratio != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentPre' =>  $req->tasheil_payment_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updatePayCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentVal == null && $req->tasheil_payment_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentVal' =>  $req->tasheil_payment_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentVal == null && $req->tasheil_payment_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentVal' =>  $req->tasheil_payment_fees,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateAdminCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->adminFee == null && $req->tasheil_other_administrative_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'adminFee' =>  $req->tasheil_other_administrative_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->adminFee == null && $req->tasheil_other_administrative_fees != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'adminFee' =>  $req->tasheil_other_administrative_fees,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateActualRealCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->realCost == null && $req->payment_actual_property_value != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realCost' =>  $req->payment_actual_property_value,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->realCost == null && $req->payment_actual_property_value != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realCost' =>  $req->payment_actual_property_value,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updateIncPaymentValueTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->incValue == null && $req->payment_increase_property_value != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'incValue' =>  $req->payment_increase_property_value,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->incValue == null && $req->payment_increase_property_value != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'incValue' =>  $req->payment_increase_property_value,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });


        Route::get('/updatePaymentCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentCos == null && $req->payment_advance_payment_amount != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentCos' =>  $req->payment_advance_payment_amount,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentCos == null && $req->payment_advance_payment_amount != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentCos' =>  $req->payment_advance_payment_amount,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });



        Route::get('/updateNetClientTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->netCustomer == null && $req->payment_net_client != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'netCustomer' =>  $req->payment_net_client,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->netCustomer == null && $req->payment_net_client != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'netCustomer' =>  $req->payment_net_client,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });

        Route::get('/updateDeficitClientTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->deficitCustomer == null && $req->amount_of_incapability != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'deficitCustomer' =>  $req->amount_of_incapability,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->deficitCustomer == null && $req->amount_of_incapability != null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'deficitCustomer' =>  $req->amount_of_incapability,
                        ]);
                        $counter++;
                    }


                    }

                }



        }

        echo($counter);
        });





        //--------------** END PREPAYMENT INFO **--------------------------//




        //--------------** START REQUEST INFO **--------------------------//

        Route::get('/updateStatusReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

                if ( $req->application_status != null){

                    $isSentSalesManager=0;
                    $isSentFundingManager=0;
                    $isSentMortgageManager=0;
                    $isSentGeneralManager=0;

                    if ($req->application_status == 1)
                    $stsus=0;
                    else if ($req->application_status == 2 || $req->application_status == 3 )
                    $stsus=1;
                    else if ($req->application_status == 4 ){
                        $stsus=3;
                        $isSentSalesManager=1;
                    }
                    else if ($req->application_status == 5 ){
                        $stsus=4;
                        $isSentSalesManager=1;
                    }
                    else if ($req->application_status == 6 ){
                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;
                    }
                    else if ($req->application_status == 7 ){
                        $stsus=7;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;
                    }
                    else if ($req->application_status == 8 )
                    {
                        $stsus=9;
                        $isSentSalesManager=1;
                        $isSentMortgageManager=1;
                    }
                    else if ($req->application_status == 9 )
                    {
                        $stsus=10;
                        $isSentSalesManager=1;
                        $isSentMortgageManager=1;
                    }
                    else if ($req->application_status == 10 ){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

                        if ($reqInfo->payment_id != null){

                            $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                            ->first();

                            if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                                $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                                ->update([
                                    'req_id' =>  $reqInfo ->id,
                                    'payStatus' =>  4,
                                    'isSentSalesAgent'=>1,
                                    'isSentSalesManager'=>1,
                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                ]);

                            }
                        }

                        else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'payStatus' =>  4,
                        'isSentSalesAgent'=>1,
                        'isSentSalesManager'=>1,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,
                    ]);

                    }

                }
                else if ($req->application_status == 11 ){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                                'req_id' =>  $reqInfo ->id,
                                'payStatus' =>  1,
                                'isSentSalesManager'=>1,
                                'created_at' => (Carbon::now('Asia/Riyadh')),
                            ]);

                        }
                    }

                    else{

                    $PayID = DB::table('prepayments')->insertGetId(
                        array( //add it once use insertGetId
                            'req_id' =>  $reqInfo ->id,
                            'payStatus' =>  1,
                            'isSentSalesManager'=>1,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                        )
                    );

                    $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                    ->update([
                    'payment_id' => $PayID,
                ]);

                }

            }else if ($req->application_status == 12 ){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

                if ($reqInfo->payment_id != null){

                    $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                    ->first();

                    if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                        $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                        ->update([
                            'req_id' =>  $reqInfo ->id,
                            'payStatus' =>  5,
                            'isSentSalesManager'=>1,
                            'isSentMortgageManager'=>1,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                        ]);

                    }
                }

                else{

                $PayID = DB::table('prepayments')->insertGetId(
                    array( //add it once use insertGetId
                        'req_id' =>  $reqInfo ->id,
                        'payStatus' =>  5,
                        'isSentSalesManager'=>1,
                        'isSentMortgageManager'=>1,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    )
                );

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                ->update([
                'payment_id' => $PayID,
            ]);

            }

        }else if ($req->application_status == 13){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

            if ($reqInfo->payment_id != null){

                $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                ->first();

                if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                    $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                    ->update([
                        'req_id' =>  $reqInfo ->id,
                        'payStatus' =>  7,
                        'isSentSalesManager'=>1,
                        'isSentMortgageManager'=>1,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    ]);

                }
            }

            else{

            $PayID = DB::table('prepayments')->insertGetId(
                array( //add it once use insertGetId
                    'req_id' =>  $reqInfo ->id,
                    'payStatus' =>  7,
                    'isSentSalesManager'=>1,
                    'isSentMortgageManager'=>1,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'payment_id' => $PayID,
        ]);

        }

        }else if ($req->application_status == 14){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

            if ($reqInfo->payment_id != null){

                $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                ->first();

                if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                    $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                    ->update([
                        'req_id' =>  $reqInfo ->id,
                        'payStatus' =>  3,
                        'isSentSalesManager'=>1,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    ]);

                }
            }

            else{

            $PayID = DB::table('prepayments')->insertGetId(
                array( //add it once use insertGetId
                    'req_id' =>  $reqInfo ->id,
                    'payStatus' =>  3,
                    'isSentSalesManager'=>1,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'payment_id' => $PayID,
        ]);

        }

        }else if ($req->application_status == 15){

                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;

            if ($reqInfo->payment_id != null){

                $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                ->first();

                if ($PayInfo->payStatus == null || $PayInfo->payStatus == 0){

                    $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                    ->update([
                        'req_id' =>  $reqInfo ->id,
                        'payStatus' =>  6,
                        'isSentMortgageManager'=>1,
                        'isSentSalesManager'=>1,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    ]);

                }
            }

            else{

            $PayID = DB::table('prepayments')->insertGetId(
                array( //add it once use insertGetId
                    'req_id' =>  $reqInfo ->id,
                    'payStatus' =>  6,
                    'isSentMortgageManager'=>1,
                    'isSentSalesManager'=>1,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'payment_id' => $PayID,
        ]);

        }

        }else if ($req->application_status == 16){
            $stsus=12;
            $isSentSalesManager=1;
            $isSentGeneralManager=1;

            if($req->application_type == 1){
                $isSentFundingManager=1;
            }
            if($req->application_type == 2){
                $isSentMortgageManager=1;
            }

        }
            else if ($req->application_status == 17){
                $stsus=13;
                $isSentSalesManager=1;
                $isSentGeneralManager=1;

                if($req->application_type == 1){
                    $isSentFundingManager=1;
                }
                if($req->application_type == 2){
                    $isSentMortgageManager=1;
                }

            }
            else if ($req->application_status == 18){
                $stsus=16;
                $isSentSalesManager=1;
                $isSentGeneralManager=1;

                if($req->application_type == 1){
                    $isSentFundingManager=1;
                }
                if($req->application_type == 2){
                    $isSentMortgageManager=1;
                }

            }
            else if ($req->application_status == 19){
                $stsus=15;
                $isSentSalesManager=1;
                $isSentGeneralManager=1;

                if($req->application_type == 1){
                    $isSentFundingManager=1;
                }
                if($req->application_type == 2){
                    $isSentMortgageManager=1;
                }

            }
            else
            $stsus=null;



            $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'statusReq' =>$stsus,'isSentSalesManager' =>$isSentSalesManager,'isSentFundingManager' =>$isSentFundingManager,
            'isSentMortgageManager' =>$isSentMortgageManager,'isSentGeneralManager' =>$isSentGeneralManager,
        ]);

        $counter++;

                }

                }



        }

        echo($counter);
        });


        Route::get('/updateCommentWebInReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

            if( $req->comment_for_client != null && $reqInfo->noteWebsite == null){

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'noteWebsite' =>$req->comment_for_client,
        ]);
        $counter++;

            }



                }



        }

        echo($counter);
        });


        Route::get('/updatebankEmployeeInReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

            if( $req->bank_employee != null && $reqInfo->empBank == null){

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'empBank' =>$req->bank_employee,
        ]);
        $counter++;

            }



                }



        }

        echo($counter);
        });


        Route::get('/updatebankNumberInReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

            if( $req->bank_order_number != null && $reqInfo->reqNoBank == null){

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'reqNoBank' =>$req->bank_order_number,
        ]);
        $counter++;

            }



                }



        }

        echo($counter);
        });

        Route::get('/updatebankEmployeeInReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

            if( $req->bank_employee != null && $reqInfo->empBank == null){

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'empBank' =>$req->bank_employee,
        ]);
        $counter++;

            }



                }



        }

        echo($counter);
        });


        Route::get('/updateCommentInReqs', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo )){

            if( $req->property_notes != null && $reqInfo->comment == null){

                $updatereq = DB::table('requests')->where('id', $reqInfo->id)
            ->update([
            'comment' =>$req->property_notes,
        ]);
        $counter++;

            }



                }



        }

        echo($counter);
        });

        //--------------** END REQUEST INFO **--------------------------//


        //--------------** START DOCUMENT INFO **--------------------------//

        Route::get('/InsertDocuments', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();


                $documents=DB::table('loan_applications_documents')->where('application_id', $loan->loan_id)
                ->get();

                if (!empty($documents )){


                    foreach($documents as $document){

                        if ($document->other != null)
                        $filename=$document->other;
                        else
                        $filename=null;




            $retrive=DB::table('requests')->where('id',  $reqInfo ->id)
            ->first();


            $userID=  $retrive->user_id;



                    $docID = DB::table('documents')->insertGetId(
                        array( //add it once use insertGetId
                            'req_id' =>  $reqInfo ->id,
                            'filename' =>  $filename,
                            'user_id' =>  $userID,
                            'location' =>  'documents/'.$document->file_name,
                            'upload_date' => Carbon::parse($document->created_at)->format('Y-m-d'),

                        )
                    );
                    $counter++;

                }


                }



        }

        echo($counter);
        });



        Route::get('/UpdateUserIDDocuments', function () {


            $documents = DB::table('documents')
            ->get();



            $counter =0;

        foreach($documents as $document){


            $retrive=DB::table('requests')->where('id', $document->req_id)
            ->first();


            if (!empty($retrive)){

            $userID=  $retrive->user_id;
            $updatereq = DB::table('documents')->where('id', $document->id)
            ->update([
            'user_id' =>$userID,
        ]);
        $counter++;

            }




        }

        echo($counter);
        });



        //--------------** END DOCUMENT INFO **--------------------------//


        Route::get('/matchNUllCustomerLoans', function () {




                $counter =0;



            $NullCusts=DB::table('cust_clints')
            ->leftjoin('cust_loan','cust_loan.cust_id','cust_clints.customer_id')
            ->select('customer_id','cust_id')
            ->where('cust_id',null)
            ->get();

            dd($NullCusts);

            foreach ($NullCusts as $NullCust){

                $clientID=DB::table('cust_clints')
                ->where('customer_id',$NullCust->customer_id)->first();


                $loan=DB::table('loan_applications')
                ->where('client_id',$clientID->clintes_id)->first();


                if (!empty($loan)){
                $newIDs = DB::table('cust_loan2')->insertGetId(
                    array(
                        'cust_id' => $NullCust->customer_id, 'loan_id' =>$loan->id,
                    )
                );
                $counter++;}}







            echo($counter);
        });

        Route::get('/DeleteNoNeedLoan', function () {


        $counter =0;

        $NullCusts=DB::table('loan_applications')
        ->get();


        foreach ($NullCusts as $NullCust){

            $cust_loan =DB::table('cust_loan2')
            ->where('loan_id',$NullCust->id)->first();


            if (empty($cust_loan)){

                DB::table('loan_applications')
                ->where('id',$NullCust->id)
                ->delete();

                $counter++;
            }

            $cust_loan = null;

        }







        echo($counter);
        });



        //---------------------------------------------------




        Route::get('/addNotesLogs', function () {


        $counter =0;


        $notes=DB::table('loan_application_notes')
        ->get();

        dd($notes);

        foreach($notes as $note){

            $loan=DB::table('cust_loan6')
            ->where('loan_id',$note->loan_application_id)->first();

        //dd($note->loan_application_id);

            if (!empty($loan)){

                $reqInfo=DB::table('requests')
                ->where('customer_id', $loan->cust_id)->first();

                if (!empty($reqInfo)){

                    if ($note->user_id == 76)
                    $userID=23;
                    else if ($note->user_id == 75)
                    $userID=52;
                    else if ($note->user_id == 74)
                    $userID=31;
                    else if ($note->user_id == 73)
                    $userID=51;
                    else if ($note->user_id == 72)
                    $userID=50;
                    else if ($note->user_id == 71)
                    $userID=49;
                    else if ($note->user_id == 70)
                    $userID=48;
                    else if ($note->user_id == 69)
                    $userID=47;
                    else if ($note->user_id == 68)
                    $userID=30;
                    else if ($note->user_id == 66)
                    $userID=null;
                    else if ($note->user_id == 65)
                    $userID=46;
                    else if ($note->user_id == 63)
                    $userID=44;
                    else if ($note->user_id == 61)
                    $userID=42;
                    else if ($note->user_id == 58)
                    $userID=null;
                    else if ($note->user_id == 54)
                    $userID=40;
                    else if ($note->user_id == 53)
                    $userID=null;
                    else if ($note->user_id == 52)
                    $userID=null;
                    else if ($note->user_id == 50)
                    $userID=39;
                    else if ($note->user_id == 48)
                    $userID=37;
                    else if ($note->user_id == 47)
                    $userID=36;
                    else if ($note->user_id == 46)
                    $userID=35;
                    else if ($note->user_id == 45)
                    $userID=34;
                    else if ($note->user_id == 44)
                    $userID=null;
                    else if ($note->user_id == 43)
                    $userID=null;
                    else if ($note->user_id == 42)
                    $userID=29;
                    else if ($note->user_id == 41)
                    $userID=33;
                    else if ($note->user_id == 40)
                    $userID=null;
                    else if ($note->user_id == 39)
                    $userID=null;
                    else if ($note->user_id == 38)
                    $userID=null;
                    else if ($note->user_id == 36)
                    $userID=null;
                    else if ($note->user_id == 35)
                    $userID=null;
                    else if ($note->user_id == 34)
                    $userID=null;
                    else if ($note->user_id == 33)
                    $userID=null;
                    else if ($note->user_id == 11)
                    $userID=null;
                    else if ($note->user_id == 8)
                    $userID=null;
                    else if ($note->user_id == 7)
                    $userID=null;
                    else if ($note->user_id == 2)
                    $userID=null;
                    else
                    $userID=null;

                    $newIDs = DB::table('req_records')->insertGetId(
                        array(
                            'req_id' => $reqInfo->id, 'colum' =>'comment',
                            'value' =>$note->comments,'updateValue_at' =>$note->created_at,
                            'user_id' =>$userID,
                        )
                    );

                    $counter++;

                }



        }


        }


        echo($counter);
        });


        Route::get('/previewRecords', function () {



            $counter =0;


            $notes=DB::table('req_records')
        // ->where('req_records.updateValue_at','<','2020-03-15 00:00:00')
        // ->leftjoin('users','users.id','req_records.user_id')
        // ->where('req_records.user_id','!=',null)
        // ->where('req_records.colum','comment')
        // ->select('users.role','req_records.*')
            ->count();

        dd($notes);

            foreach($notes as $note){






                    DB::table('req_records')
                    ->where('id',$note->id)
                    ->delete();

                    $counter++;





            }





        echo($counter);
        });



        ///--------------------------------------------------------------------

        Route::get('/removeNotesInHistory', function () {




            $counter =0;


            $notes=DB::table('req_records')
            ->leftjoin('users','users.id','req_records.user_id')
            ->where('req_records.user_id','!=',null)
            ->where('req_records.colum','comment')
            ->select('users.role','req_records.*')
            ->get();

        //dd($notes);


            foreach($notes as $note){


                if ($note->user_id != null){
                $userNote=$note->user_id;
                $userRole=$note->role;

                if($userRole == 0){
                $getReq=DB::table('requests')
                ->where('id',$note->req_id)->first();

                if (!empty($getReq)){
                $userID= $getReq->user_id;

                if ( $userNote != $userID){


                    DB::table('req_records')
                    ->where('id',$note->id)
                    ->delete();

                    $counter++;
                }
            }
            }


                }
            }



        echo($counter);
        });



        //-----------------------PUR WITH PRE ---------------------


        Route::get('/purshasewithcompletedpre', function () {




            $counter =0;


            $notes=DB::table('requests')
            ->join('customers','customers.id','requests.customer_id')
            ->where('type','شراء')
            ->whereIn('customers.mobile',[544546767,557171788,501862293,501117217,504345260,558261006,
            558349209,569333566,550136002,546796803,503117776,590556919,583803699,558261006,504921968,
            502922624,506198888,507335717,534447812,504199139,557657897])
        // ->whereIn('name',['منال القيعاوي','شاكر صالح البلادي'])
        //->where('comment','تم الافراغ')
        //->where('comment','=',null)
        // ->select('requests.*','customers.name')
        ->whereNotIn('requests.id',[4125,12880])
            ->get();


            foreach($notes as $note){

                $newIDs = DB::table('precom')->insertGetId(
                    array(
                        'req_id' => $note->id,
                    )
                );

                $counter++;

            }





        echo($counter);
        });


        Route::get('/updateStatusComPre', function () {




            $counter =0;


            $notes=DB::table('precom')
            ->get();


            foreach($notes as $note){


                $reqInfo = DB::table('requests')->where('id', $note->req_id)
                ->first();

                // dd($reqInfo);

                if (!empty(  $reqInfo )){
                    $updatereq = DB::table('prepayments')->where('id',  $reqInfo->payment_id)
                    ->update([
                    'payStatus' => 9,'isSentSalesManager'=>1,'isSentSalesAgent'=>1,'isSentMortgageManager'=>1,
                ]);
                $counter++;
                }





            }





        echo($counter);
        });






        //--------------** START PREPAYMENT2 INFO **--------------------------//


        Route::get('/updateVisaInTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_visa_card != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->visa == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'visa' =>  $req->tasheil_visa_card,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                        'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->visa == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'visa' =>  $req->tasheil_visa_card,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateCarInTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ( $req->tasheil_car_loan != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->carLo == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'carLo' =>  $req->tasheil_car_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->carLo == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'carLo' =>  $req->tasheil_car_loan,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updatePersonalLoInTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_personal_loan != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->personalLo == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'personalLo' =>  $req->tasheil_personal_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->personalLo == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'personalLo' =>  $req->tasheil_personal_loan,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateRealLoInTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_mortgage_loan != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->realLo == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realLo' =>  $req->tasheil_mortgage_loan,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->realLo == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realLo' =>  $req->tasheil_mortgage_loan,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateCreditTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_credit_bank != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->credit == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->credit == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });



        Route::get('/updateCreditTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ( $req->tasheil_credit_bank != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->credit == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->credit == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'credit' =>  $req->tasheil_credit_bank,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });


        Route::get('/updateOtherTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_other != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->other == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'other' =>  $req->tasheil_other,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->other == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'other' =>  $req->tasheil_other,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateDebtTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ( $req->tasheil_total_indebtedness != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->debt == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'debt' =>  $req->tasheil_total_indebtedness,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->debt == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'debt' =>  $req->tasheil_total_indebtedness,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });

        Route::get('/updateProfitPreTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_quest_fees_ratio != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->proftPre == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'proftPre' =>  $req->tasheil_quest_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->proftPre == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'proftPre' =>  $req->tasheil_quest_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        //----------NEED TO REVIEWS---------------------------------

        Route::get('/updateProfitCostTsahile', function () {


            $loans = DB::table('cust_loan2')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 2){

                    if ( $req->tasheil_quest_fees != null || $req->property_pursuit  != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->profCost == null ){

                            if ($req->tasheil_quest_fees != null)
                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->tasheil_quest_fees,
                            ]);

                            if ($req->property_pursuit != null)
                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->property_pursuit,
                            ]);


                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->profCost == null ){

                            if ($req->tasheil_quest_fees != null)
                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->tasheil_quest_fees,
                            ]);

                            if ($req->property_pursuit != null)
                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'profCost' =>  $req->property_pursuit,
                            ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });

        //----------NEED TO REVIEWS---------------------------------

        Route::get('/updateMortPreTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_mortgage_fees_ratio != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->mortPre == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortPre' =>  $req->tasheil_mortgage_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->mortPre == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortPre' =>  $req->tasheil_mortgage_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateMortCostTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_mortgage_fees != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->mortCost == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortCost' =>  $req->tasheil_mortgage_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->mortCost == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'mortCost' =>  $req->tasheil_mortgage_fees,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });


        Route::get('/updatePayPreTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){


                    if ( $req->tasheil_payment_fees_ratio != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentPre == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentPre' =>  $req->tasheil_payment_fees_ratio,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentPre == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentPre' =>  $req->tasheil_payment_fees_ratio,
                        ]);
                        $counter++;
                    }


                    }

                }
                }



        }

        echo($counter);
        });

        Route::get('/updatePayCostTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_payment_fees != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentVal == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentVal' =>  $req->tasheil_payment_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentVal == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentVal' =>  $req->tasheil_payment_fees,
                        ]);
                        $counter++;
                    }


                    }
                }

                }



        }

        echo($counter);
        });


        Route::get('/updateAdminCostTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->tasheil_other_administrative_fees != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->adminFee == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'adminFee' =>  $req->tasheil_other_administrative_fees,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->adminFee == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'adminFee' =>  $req->tasheil_other_administrative_fees,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });


        Route::get('/updateActualRealCostTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->payment_actual_property_value != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->realCost == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realCost' =>  $req->payment_actual_property_value,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->realCost == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'realCost' =>  $req->payment_actual_property_value,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });


        Route::get('/updateIncPaymentValueTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->payment_increase_property_value != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->incValue == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'incValue' =>  $req->payment_increase_property_value,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->incValue == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'incValue' =>  $req->payment_increase_property_value,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });


        Route::get('/updatePaymentCostTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ($req->payment_advance_payment_amount != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->prepaymentCos == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentCos' =>  $req->payment_advance_payment_amount,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->prepaymentCos == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'prepaymentCos' =>  $req->payment_advance_payment_amount,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });



        Route::get('/updateNetClientTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){

                    if ( $req->payment_net_client != null){
                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->netCustomer == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'netCustomer' =>  $req->payment_net_client,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=>6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->netCustomer == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'netCustomer' =>  $req->payment_net_client,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });

        Route::get('/updateDeficitClientTsahile', function () {


            $loans = DB::table('cust_loan6')
            ->get();



            $counter =0;

        foreach($loans as $loan){

            $req = DB::table('loan_applications6')->where('id',$loan->loan_id)
                ->first();


                $reqInfo = DB::table('requests')->where('customer_id', $loan->cust_id)
                ->first();

                if (!empty($reqInfo ) && $req->application_type == 1){


                if ($req->amount_of_incapability != null){

                    if ($reqInfo->payment_id != null){

                        $PayInfo = DB::table('prepayments')->where('id', $reqInfo->payment_id)
                        ->first();

                        if ($PayInfo->deficitCustomer == null){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'deficitCustomer' =>  $req->amount_of_incapability,
                            ]);

                        $counter++;
                        }


                    }

                    else{

                        $PayID = DB::table('prepayments')->insertGetId(
                            array( //add it once use insertGetId
                            'payStatus' =>  1,
                            'isSentSalesManager' =>  1,
                        'req_id' =>  $reqInfo ->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                            )
                        );

                        $updatereq = DB::table('requests')->where('id', $reqInfo->id)
                        ->update([
                            'payment_id' => $PayID,'statusReq'=> 6,'type'=>'شراء-دفعة',
                    ]);

                    $PayInfo = DB::table('prepayments')->where('id', $PayID)
                        ->first();

                        if ($PayInfo->deficitCustomer == null ){

                            $updatePre = DB::table('prepayments')->where('id',  $PayInfo->id)
                            ->update([
                            'deficitCustomer' =>  $req->amount_of_incapability,
                        ]);
                        $counter++;
                    }


                    }

                }

                }



        }

        echo($counter);
        });





        //--------------** END PREPAYMENT2 INFO **--------------------------//





        //---------------DIVED LOAN APPLICATIONE--------------------------//


        Route::get('/matchNUllCustomerLoans3', function () {




            $counter =0;



        $NullCusts=DB::table('cust_clints')
        ->take(2500)
        ->get();

        //dd($NullCusts);

        foreach ($NullCusts as $NullCust){

            $clientID=DB::table('cust_loan2')
            ->where('cust_id',$NullCust->customer_id)->first();

        // dd($NullCust->clintes_id);

            if (empty($clientID)){

            $loan=DB::table('loan_applications2')
            ->where('client_id',$NullCust->clintes_id)->first();


        if (!empty($loan)){


            $newIDs = DB::table('cust_loan3')->insertGetId(
                array(
                    'cust_id' => $NullCust->customer_id, 'loan_id' =>$loan->id,
                )
            );

            $counter++;
        }

        }

        }

        echo($counter);
        });


        Route::get('/DeleteNoNeedLoan3', function () {


        $counter =0;

        $NullCusts=DB::table('loan_applications3')
        ->get();


        foreach ($NullCusts as $NullCust){

        $cust_loan =DB::table('cust_loan3')
        ->where('loan_id',$NullCust->id)->first();


        if (empty($cust_loan)){

            DB::table('loan_applications3')
            ->where('id',$NullCust->id)
            ->delete();

            $counter++;
        }

        $cust_loan = null;

        }







        echo($counter);
        });



        Route::get('/matchNUllCustomerLoans4', function () {




            $counter =0;



        $NullCusts=DB::table('cust_clints')
        ->take(5000)
        ->get();

        //dd($NullCusts);

        foreach ($NullCusts as $NullCust){

            $clientID=DB::table('cust_loan2')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID2=DB::table('cust_loan3')
            ->where('cust_id',$NullCust->customer_id)->first();

        // dd($NullCust->clintes_id);

            if (empty($clientID) && empty($clientID2)){

            $loan=DB::table('loan_applications2')
            ->where('client_id',$NullCust->clintes_id)->first();


        if (!empty($loan)){


            $newIDs = DB::table('cust_loan4')->insertGetId(
                array(
                    'cust_id' => $NullCust->customer_id, 'loan_id' =>$loan->id,
                )
            );

            $counter++;
        }

        }

        }

        echo($counter);
        });


        Route::get('/DeleteNoNeedLoan4', function () {


        $counter =0;

        $NullCusts=DB::table('loan_applications4')
        ->get();


        foreach ($NullCusts as $NullCust){

        $cust_loan =DB::table('cust_loan4')
        ->where('loan_id',$NullCust->id)->first();


        if (empty($cust_loan)){

            DB::table('loan_applications4')
            ->where('id',$NullCust->id)
            ->delete();

            $counter++;
        }

        $cust_loan = null;

        }







        echo($counter);
        });


        Route::get('/matchNUllCustomerLoans5', function () {




            $counter =0;



        $NullCusts=DB::table('cust_clints')
        ->take(8000)
        ->get();

        //dd($NullCusts);

        foreach ($NullCusts as $NullCust){

            $clientID=DB::table('cust_loan2')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID2=DB::table('cust_loan3')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID3=DB::table('cust_loan4')
            ->where('cust_id',$NullCust->customer_id)->first();

        // dd($NullCust->clintes_id);

            if (empty($clientID) && empty($clientID2) && empty($clientID3)){

            $loan=DB::table('loan_applications2')
            ->where('client_id',$NullCust->clintes_id)->first();


        if (!empty($loan)){


            $newIDs = DB::table('cust_loan5')->insertGetId(
                array(
                    'cust_id' => $NullCust->customer_id, 'loan_id' =>$loan->id,
                )
            );

            $counter++;
        }

        }

        }

        echo($counter);
        });


        Route::get('/DeleteNoNeedLoan5', function () {


        $counter =0;

        $NullCusts=DB::table('loan_applications5')
        ->get();


        foreach ($NullCusts as $NullCust){

        $cust_loan =DB::table('cust_loan5')
        ->where('loan_id',$NullCust->id)->first();


        if (empty($cust_loan)){

            DB::table('loan_applications5')
            ->where('id',$NullCust->id)
            ->delete();

            $counter++;
        }

        $cust_loan = null;

        }







        echo($counter);
        });


        Route::get('/matchNUllCustomerLoans6', function () {




            $counter =0;



        $NullCusts=DB::table('cust_clints')
        //->take(8000)
        ->get();

        //dd($NullCusts);

        foreach ($NullCusts as $NullCust){

            $clientID=DB::table('cust_loan2')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID2=DB::table('cust_loan3')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID3=DB::table('cust_loan4')
            ->where('cust_id',$NullCust->customer_id)->first();
            $clientID4=DB::table('cust_loan5')
            ->where('cust_id',$NullCust->customer_id)->first();

        // dd($NullCust->clintes_id);

            if (empty($clientID) && empty($clientID2) && empty($clientID3) && empty($clientID4)){

            $loan=DB::table('loan_applications2')
            ->where('client_id',$NullCust->clintes_id)->first();


        if (!empty($loan)){


            $newIDs = DB::table('cust_loan6')->insertGetId(
                array(
                    'cust_id' => $NullCust->customer_id, 'loan_id' =>$loan->id,
                )
            );

            $counter++;
        }

        }

        }

        echo($counter);
        });


        Route::get('/DeleteNoNeedLoan6', function () {


        $counter =0;

        $NullCusts=DB::table('loan_applications6')
        ->get();


        foreach ($NullCusts as $NullCust){

        $cust_loan =DB::table('cust_loan6')
        ->where('loan_id',$NullCust->id)->first();


        if (empty($cust_loan)){

            DB::table('loan_applications6')
            ->where('id',$NullCust->id)
            ->delete();

            $counter++;
        }

        $cust_loan = null;

        }







        echo($counter);
        });


        //----------------------------------------------------------


        //----------------------Return status ----------------------------

        Route::get('/returnnnn', function () {


            $counter =0;

            $requests=DB::table('requests')
            ->where('type','شراء-دفعة')
            ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->where('realCost',null)
            ->where('incValue',null)
            ->where('prepaymentVal',null)
            ->where('prepaymentPre',null)
            ->where('prepaymentCos',null)
            ->where('netCustomer',null)
            ->where('deficitCustomer',null)
            ->where('profCost','!=',null)
            ->select('requests.*','prepayments.req_id')
            ->count();


        dd( $requests);


            foreach ($requests as $request){

                $getLoanID = DB::table('cust_loan6')
                ->where('cust_id', $request->customer_id )->first();

                if (!empty($getLoanID)){

                $req=DB::table('loan_applications6')
                ->where('id', $getLoanID->loan_id )->first();

                if (!empty( $req)){

                if ( $req->application_status != null){

                    $isSentSalesManager=0;
                    $isSentFundingManager=0;
                    $isSentMortgageManager=0;
                    $isSentGeneralManager=0;

                    if ($req->application_status == 1)
                    $stsus=0;
                    else if ($req->application_status == 2 || $req->application_status == 3 )
                    $stsus=1;
                    else if ($req->application_status == 4 ){
                        $stsus=3;
                        $isSentSalesManager=1;
                    }
                    else if ($req->application_status == 5 ){
                        $stsus=4;
                        $isSentSalesManager=1;
                    }
                    else if ($req->application_status == 6 ){
                        $stsus=6;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;
                    }
                    else if ($req->application_status == 7 ){
                        $stsus=7;
                        $isSentSalesManager=1;
                        $isSentFundingManager=1;
                    }
                    else if ($req->application_status == 8 )
                    {
                        $stsus=9;
                        $isSentSalesManager=1;
                        $isSentMortgageManager=1;
                    }
                    else if ($req->application_status == 9 )
                    {
                        $stsus=10;
                        $isSentSalesManager=1;
                        $isSentMortgageManager=1;
                    }
                else if ($req->application_status == 16){
                    $stsus=12;
                    $isSentSalesManager=1;
                    $isSentGeneralManager=1;

                    if($req->application_type == 1){
                        $isSentFundingManager=1;
                    }
                    if($req->application_type == 2){
                        $isSentMortgageManager=1;
                    }

                }
                    else if ($req->application_status == 17){
                        $stsus=13;
                        $isSentSalesManager=1;
                        $isSentGeneralManager=1;

                        if($req->application_type == 1){
                            $isSentFundingManager=1;
                        }
                        if($req->application_type == 2){
                            $isSentMortgageManager=1;
                        }

                    }
                    else if ($req->application_status == 18){
                        $stsus=16;
                        $isSentSalesManager=1;
                        $isSentGeneralManager=1;

                        if($req->application_type == 1){
                            $isSentFundingManager=1;
                        }
                        if($req->application_type == 2){
                            $isSentMortgageManager=1;
                        }

                    }
                    else if ($req->application_status == 19){
                        $stsus=15;
                        $isSentSalesManager=1;
                        $isSentGeneralManager=1;

                        if($req->application_type == 1){
                            $isSentFundingManager=1;
                        }
                        if($req->application_type == 2){
                            $isSentMortgageManager=1;
                        }

                    }
                    else
                    $stsus=null;


                //  dd( $stsus);


                $payID= $request->payment_id;

                $updatePre = DB::table('prepayments')->where('id',  $payID)
                ->update([
                'req_id' =>  null,
                ]);


                $updatereq = DB::table('requests')->where('id', $request->id)
                ->update([
                    'payment_id' => null,'statusReq'=>  $stsus, 'type'=>'شراء',
                    'isSentSalesManager' =>$isSentSalesManager,'isSentFundingManager' =>$isSentFundingManager,
                    'isSentMortgageManager' =>$isSentMortgageManager,'isSentGeneralManager' =>$isSentGeneralManager,
                ]);


            DB::table('prepayments')
                ->where('id', $payID)
                ->delete();


                $counter++;


            }//if status

        }//req null

        }//loan null


        }//for







            //




            echo($counter);
            });




        //----------------Update Inputs histories---------------------




        Route::get('/moveDateToCreatedAt', function () {


            $counter = 0;

            $requests = DB::select("select * from requests  WHERE DATE(created_at) != req_date");
            // ->whereDate('created_at', '=', 'req_date')
            // ->where(Carbon::parse('created_at')->format('Y-m-d'),'!=','req_date')
            //->get();



            foreach ($requests as $request) {





                    $updatereq = DB::table('requests')->where('id', $request->id)
                    ->update([
                        'created_at' =>  $newValue,  ]);


                        $counter++;
            }







            echo ($counter);
        });

        ////////////////////////////////////////////

        Route::get('/updateStatusIfCommentIsWritten', function () {


            $counter = 0;

            $requests = DB::table('requests')
            ->where('statusReq',0)
            ->where('comment','!=',null)
            ->get();


            //dd($requests);



            foreach ($requests as $request) {

                if (strlen($request->comment) > 4){
                $updatereq = DB::table('requests')->where('id', $request->id)
                ->update([
                    'statusReq' =>  1,  ]);

                        $counter++;

                }
            }







            echo ($counter);
        });


        Route::get('/updateStatusIfCommentIsWrittenNew', function () {


            $counter = 0;

            $requests = DB::table('requests')
            ->where('statusReq',1)
            ->where('comment',null)
            ->get();


            //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('id', $request->id)
                ->update([
                    'statusReq' =>  0,  ]);

                        $counter++;


            }







            echo ($counter);
        });




        Route::get('/updateNullCreated', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('created_at', null)
                ->get();


            //  dd($requests);



            foreach ($requests as $request) {


                if ($request->req_date != null)
                    $newValue = $request->req_date . ' ' . '00:00:00';
                else
                    $newValue = Carbon::today()->format('Y-m-d') . ' ' . '00:00:00';


                $updatereq = DB::table('requests')->where('id', $request->id)
                    ->update([
                        'created_at' =>  $newValue,
                    ]);

                $counter++;
            }



            echo ($counter);
        });



        Route::get('/updateNullcity', function () {


            $counter = 0;

            $requests = DB::table('real_estats')
                ->where('city', 22)
                ->get();


            //  dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('real_estats')->where('id', $request->id)
                    ->update([
                        'city' => null,
                    ]);

                $counter++;
            }

            echo ($counter);
        });



        /*
        $myUsers = DB::table('agent_qualities')
        ->where('Quality_id',auth()->user()->id)
        ->pluck('Agent_id');


        $myReqs = DB::table('requests')
        ->joint('quality_reqs','quality_reqs.req_id','requests.id')
        ->whereIn('requests.user_id',$myUsers)
        ->get();




        Route::get('/movePurpretosalesAgent', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('statusReq', 6)
                ->where('type', 'شراء-دفعة')
                ->join('prepayments','prepayments.id','requests.payment_id')
                ->where('payStatus', 1)
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'statusReq' => 4, 'type' => 'شراء',
                    ]);

                    $updatepre = DB::table('prepayments')->where('id', $request->payment_id)
                    ->update([
                        'prepayments.payStatus' => 2,'prepayments.isSentSalesManager'=> 0, 'prepayments.isSentMortgageManager'=>0,
                        'prepayments.isSentSalesAgent'=> 0,
                    ]);

                $counter++;
            }

            echo ($counter);
        });



        Route::get('/moveTamweelk', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('requests.collaborator_id', 82)
                ->join('customers','customers.id','requests.customer_id')
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'collaborator_id' => 77,
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });



        Route::get('/moveisunderValue', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('requests.isUnderProcFund', 1)
                ->where('requests.type', 'رهن')
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'isUnderProcFund' => 0,'isUnderProcMor' => 1,'recived_date_report_mor' =>$request->recived_date_report,
                    'counter_report_mor' =>$request->counter_report,
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });



        Route::get('/UpdateSourceOfCalcater', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->whereIn('requests.source', ['الحاسبة العقارية','الحاسبة العقارية - ويب'])
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'source' => 'ويب - الحاسبة العقارية',
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });


        Route::get('/UpdateSourceOfFundingReq', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->whereIn('requests.source', ['طلب استشارة','طلب استشارة - ويب'])
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'source' => 'ويب - اطلب استشارة',
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });


        Route::get('/UpdateSourceOfConsoltReq', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->whereIn('requests.source', ['طلب تمويل - ويب','طلب تمويل'])
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'source' => 'ويب - اطلب تمويل',
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });


        Route::get('/UpdateSourceOfTel', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->whereIn('requests.source', ['التليفون الثابت'])
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('requests')->where('requests.id', $request->id)
                    ->update([
                    'source' => 'تلفون ثابت',
                    ]);


                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });



        Route::get('/UpdateRealType', function () {


            $counter = 0;

            $requests = DB::table('real_estats')
                ->whereNotIn('real_estats.type', ['فيلا','مبنى','أرض'])
                ->get();


        //dd($requests);



            foreach ($requests as $request) {


                $updatereq = DB::table('real_estats')->where('real_estats.id', $request->id)
                    ->update([
                    'other_value' => $request->type,
                    'type' => 'آخر',

                    ]);

                if ($updatereq ==1)
                $counter++;
            }

            echo ($counter);
        });

        */

        /*
        Route::get('/UpdateUserComments2', function () {


            $counter = 0;

            $records = DB::table('req_records')
                ->join('users', 'users.id', 'req_records.user_id')
                // ->join('requests', 'requests.id', 'req_records.req_id')
                ->where('colum', 'comment')
                ->where('users.role', '!=', 0)
                // ->where('requests.id', 16109)
                //->whereNotIn('requests.statusReq', [0, 1])
                ->orderBy('updateValue_at', 'ASC')
                ->select('req_records.user_id', 'req_records.value', 'req_records.req_id', 'updateValue_at', 'users.role')
                ->get();

            //dd($records);


            foreach ($records as  $record) {

                //dd($record);
                $reqRecord = DB::table('requests')
                    ->where('id', $record->req_id)
                    ->first();

                if ($record->role == 0)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'comment' => $record->value,
                        ]);

                else if ($record->role == 1)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'sm_comment' => $record->value,
                            'comment' => null,
                        ]);

                else if ($record->role == 2)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'fm_comment' => $record->value,
                            'comment' => null,
                        ]);

                else if ($record->role == 3)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'mm_comment' => $record->value,
                            'comment' => null,
                        ]);
                else if ($record->role == 4)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'gm_comment' => $record->value,
                            'comment' => null,
                        ]);
                else if ($record->role == 5)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'quacomment' => $record->value,
                            'comment' => null,
                        ]);

                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateAgentComments2', function () {


            $counter = 0;

            $records = DB::table('req_records')
                ->join('users', 'users.id', 'req_records.user_id')
                // ->join('requests', 'requests.id', 'req_records.req_id')
                ->where('colum', 'comment')
                ->where('users.role', 0)
                // ->where('requests.id', 16109)
                //->whereNotIn('requests.statusReq', [0, 1])
                ->orderBy('updateValue_at', 'ASC')
                ->select('req_records.user_id', 'req_records.value', 'req_records.req_id', 'updateValue_at', 'users.role')
                ->get();

            // dd($records);



            foreach ($records as  $record) {

                //dd($record);
                $reqRecord = DB::table('requests')
                    ->where('id', $record->req_id)
                    ->first();

                if ($record->role == 0)
                    $updatereq = DB::table('requests')->where('id', $reqRecord->id)
                        ->update([
                            'comment' => $record->value,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/insertoldtask', function () {


            $records = DB::table('tasks_old')
                ->get();

            $counter = 0;

            foreach ($records as $record) {

                $newTask =  task::create([
                    'req_id' => $record->req_id,
                    'recive_id' => $record->recive_id,
                    'user_id' => 59,
                    'status' => $record->status
                ]);

                $newContent =  task_content::create([
                    'content' => $record->content,
                    'date_of_content' => $record->created_at,
                    'task_id' =>  $newTask->id,
                    'user_note' =>  $record->user_note,
                    'date_of_note' => $record->updated_at,
                ]);

                $counter++;

            }
            echo ($counter);
        });



        Route::get('/testinsert', function () {


            $records = DB::table('test')
                ->get();

            $counter = 0;
            $info=null;
            foreach ($records as $record) {

                if ($record->mobile != null && $record->mobile != 'لايوجد رقم') {
                    $customerInfo = customer::where('mobile', $record->mobile)->first();
                    if ($customerInfo) {
                        $requestInfo = req::where('customer_id', $customerInfo->id)->first();
                        if ($requestInfo) {

                            $importDate = MyHelpers::getInfoFromExcel(
                                $requestInfo,
                                $record->agent_note,
                                $record->agent_class,
                                $record->quality_note,
                                $record->quality_class,
                                $record->q_1,
                                $record->q_2,
                                $record->q_3,
                                $record->q_4,
                                $record->need_action
                            );

                            if ($importDate)
                                $counter++;
                        }
                    }
                    else{
                        $info=$info . '---' .$record->mobile;
                    }
                }
            }
            echo ($counter.'%%% ( '.$info.' )');
        });

        */

        /*
        Route::get('/UpdateAgentClassToMarfoaa', function () {


            $counter = 0;

            $records = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4, 31]);
                        $query->whereIn('type', ['رهن', 'شراء', 'تساهيل']);
                    });


                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*')
                ->get();

        // dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('requests')->where('id', $record->id)
                        ->update([
                            'class_id_agent' => 57,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateAgentClassToCompleted', function () {


            $counter = 0;

            $records = DB::table('requests')
                ->whereIn('statusReq', [16,26])
                ->select('requests.*')
                ->get();

        // dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('requests')->where('id', $record->id)
                        ->update([
                            'class_id_agent' => 58,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateVilla', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('type', 'فيلا')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 1,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateLand', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('type', 'أرض')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 2,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateBuliding', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('type', 'مبنى')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 3,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateEstraha', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'استراحة')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 5,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateOmara', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'عمارة')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 6,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateDepartment', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->whereIn('other_value', ['شقه','شقة'])
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 4,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateDoor', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'دور')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 7,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateDeblux', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'ديبلوكس')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 8,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateTownHous', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'تاون هاوس')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 9,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateBenaaThati', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'بناء ذاتي')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 10,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });

        Route::get('/UpdateOther', function () {


            $counter = 0;

            $records = DB::table('real_estats')
                ->where('other_value', 'آخر')
                ->get();

        //dd($records);



            foreach ($records as  $record) {


                    $updatereq = DB::table('real_estats')
                    ->where('id', $record->id)
                        ->update([
                            'type' => 11,  'other_value' => null,
                        ]);


                if ($updatereq == 1)
                    $counter++;
            }




            echo ($counter);
        });


        Route::get('/UpdateReqDate', function () {


            $counter = 0;

            $records = DB::table('requests')
            ->where('agent_date',null)
            //->whereDate('created_at', '=', DB::raw('req_date'))
            //->where('req_date','=',Carbon::parse('requests.created_at')->format('Y-m-d'))
            //->whereColumn('created_at','=','req_date')
            ->take(3000)
            ->get();

        //dd($records);



            foreach ($records as  $record) {

                $updatereq =0;

                $dateValue = DB::table('request_histories')->where('req_id', $record->id)->get();
                if (($dateValue->count()>1)){
                    if ( Carbon::parse($record->created_at)->format('Y-m-d') >= Carbon::parse($dateValue->first()->history_date)->format('Y-m-d')){
                $data = $dateValue->first()->history_date;

                    $updatereq = DB::table('requests')
                    ->where('id', $record->id)
                        ->update([
                            'agent_date' =>  $record->created_at,
                            'created_at' =>  $data,
                            'req_date' =>   Carbon::parse($data)->format('Y-m-d'),
                        ]);
                }
            else{

                    $updatereq = DB::table('requests')
                    ->where('id', $record->id)
                        ->update([
                            'agent_date' =>  $record->created_at,
                            'req_date' =>  Carbon::parse($record->created_at)->format('Y-m-d'),
                        ]);

            }
        }

                else{
                $updatereq = DB::table('requests')
                ->where('id', $record->id)
                    ->update([
                        'agent_date' =>  $record->created_at,
                        'req_date' =>  Carbon::parse($record->created_at)->format('Y-m-d'),
                    ]);
                }


                if ($updatereq == 1)
                    $counter++;
                // dd('fn');
            }




            echo ($counter);
        });


        Route::get('/findcustomerWithoutReqs', function () {


            $counter = 0;

            $records = DB::table('customers')
            ->get();

        //dd($records);



            foreach ($records as  $record) {


                $cust_req = DB::table('requests')
                ->where('customer_id',$record->id)
                ->first();

                if (empty($cust_req)){

                $updatecus = DB::table('customers')
                ->where('id', $record->id)
                    ->update([
                        'mobile' =>  null,
                    ]);

                    if ($updatecus == 1)
                    $counter++;
                }
            }








            echo ($counter);
        });


        Route::get('/deltePendingRequest', function () {


            $counter = 0;

            $records = DB::table('pending_requests')
            ->where('collaborator_id',17)
            ->get();

        //dd($records);



            foreach ($records as  $record) {

                $cus=DB::table('customers')
                ->where('id',$record->customer_id)
                ->delete();

                $req=DB::table('pending_requests')
            ->where('id',$record->id)
            ->delete();


            if ( $cus && $req)
            $counter++;



            }


            echo ($counter);
        });



        Route::get('/updateCreatedPending', function () {


            $counter = 0;

            $requests = DB::table('pending_requests')
                ->get();


            //  dd($requests);



            foreach ($requests as $request) {


                if ($request->req_date != null)
                    $newValue = $request->req_date . ' ' . Carbon::now('Asia/Riyadh')->format('H:i:s');
                else
                    $newValue = $request->created_at;


                $updatereq = DB::table('pending_requests')->where('id', $request->id)
                    ->update([
                        'created_at' =>  $newValue,
                    ]);

                $counter++;
            }



            echo ($counter);
        });


        Route::get('/customerWithoutReqs', function () {


            $ids[]=null;
            $counter=0;
            $customers = DB::table('customers')
            ->where('id','>',18000)
            ->get();

            foreach( $customers as  $customer){

                $request=DB::table('requests')->where('customer_id',$customer->id)
                ->first();

                $request2=DB::table('pending_requests')->where('customer_id',$customer->id)
                ->first();

                if (empty($request) && empty($request2)){
                DB::table('customers')->where('id',$customer->id)
                ->delete();

                $counter++;
                }


            }

            dd($counter);

        });


        Route::get('/moveStarFromToanother', function () {


            $counter=0;

            $requests = DB::table('requests')
            ->where('user_id',66)
            ->where('is_stared',1)
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =0;

            if ($request->collaborator_id == null){

                DB::table('customers')->where('id', $request->customer_id)
                    ->update([
                        'user_id' =>  117,
                    ]);

            }

            $updatereq =  DB::table('requests')->where('id', $request->id)
            ->update([
                'user_id' =>  117,
            ]);

            if (  $updatereq )
            $counter++;




            }

            dd($counter);

        });


        Route::get('/test_mail', function () {

            $userId=99;

            $email = Email::where('email_name', 'test')->first();
            if (EmailUser::where(['user_id' => $userId, 'email_id' => $email->id])->count() > 0) {
                Mail::to(User::find($userId)->email)->send(new \App\Mail\WastaMailNotification('subject','HIIII'));
            }



        Route::get('/test_mail', function () {

            $content='';
        return view('emails.email', compact('content'));

        });

        });


        Route::get('/searchingID', function () {

            $counter=0;

            $requests = DB::table('requests')
            ->where('searching_id',null)
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =  DB::table('requests')->where('id', $request->id)
            ->update([
                'searching_id' =>  $request->id,
            ]);

            if (  $updatereq )
            $counter++;

            }

            dd($counter);

        });

        Route::get('/FindsearchingID', function () {

            $counter=0;
            $arra=[];

            $requests = DB::table('customers')
            ->whereDate('created_at','2020-07-25')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =  DB::table('requests')->where('customer_id', $request->id)
            ->first();

            if ( empty($updatereq )){
                $arra[]=$request->id;
                $counter++;
            }


            }

            foreach( $arra as  $arr){

                $getCustomer=DB::table('customers')->where('id',$arr)->first();
                $customerId =  $getCustomer->id;
                $user_id =   $getCustomer->user_id;

                $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                    array( //add it once use insertGetId
                        // 'customer_id' => $customerId,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    )
                );

                $realID = DB::table('real_estats')->insertGetId(
                    array(
                        //'customer_id' => $customerId,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    )

                );

                $funID = DB::table('fundings')->insertGetId(
                    array(
                        // 'customer_id' => $customerId,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                    )

                );


                $reqdate = '2020-07-27' . ' ' . Carbon::now('Asia/Riyadh')->format('H:i:s');
                $created_at = '2020-07-27' . ' ' . Carbon::now('Asia/Riyadh')->format('H:i:s');

                $searching_id = RequestSearching::create()->id;
                $reqID = DB::table('requests')->insertGetId(
                    array(
                        'source' => null, 'req_date' => $reqdate, 'created_at' =>  $created_at,'searching_id' => $searching_id,
                        'user_id' =>  $user_id, 'customer_id' => $customerId, 'collaborator_id' => null,
                        'joint_id' => $joinID, 'real_id' => $realID, 'fun_id' => $funID, 'statusReq' => 1, 'agent_date' => carbon::now(),
                    )

                );
            }


            dd($arra);

        });

        Route::get('/FindsearchingID', function () {

            $counter=0;
            $arra=[];

            $requests = DB::table('customers')
            ->whereDate('created_at','2020-07-28')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =  DB::table('requests')->where('customer_id', $request->id)
            ->first();

            $updatereq2 =  DB::table('pending_requests')->where('customer_id', $request->id)
            ->first();

            if ( !empty($updatereq ) && !empty($updatereq2 )){
                $arra[]=$request->id;
                $counter++;
            }


            }

            //dd($arra);

            foreach( $arra as  $arr){

                $req=DB::table('requests')->where ('customer_id',$arr)->delete();
            }


            dd($arra);

        });



        Route::get('/removeCustomerOfAhmed', function () {

            $counter = 0;
            $real=[];
            $fun=[];
            $pay=[];
            $doc=[];
            $req=[];
            $cust=[];

            $requests = DB::table('requests')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->where('customers.name', 'LIKE', '%تجربه%')
                ->where('requests.user_id', 61)
                ->select('requests.*', 'customers.id as custID')
                ->get();

            //dd($requests);

            foreach ($requests as  $request) {

                $real= DB::table('real_estats')->where ('id',$request->real_id)->delete();
                $fun= DB::table('fundings')->where ('id',$request->fun_id)->delete();
                $pay= DB::table('prepayments')->where ('id',$request->payment_id)->delete();
                $docu=DB::table('documents')->where ('req_id',$request->id)->delete();
                $cust = DB::table('customers')->where('id', $request->custID)->delete();
                $notifi=DB::table('notifications')->where ('req_id',$request->id)->delete();
                $records=DB::table('req_records')->where ('req_id',$request->id)->delete();
                $history=DB::table('request_histories')->where ('req_id',$request->id)->delete();
                $quality=DB::table('quality_reqs')->where ('req_id',$request->id)->delete();
                $req = DB::table('requests')->where('id', $request->id)->delete();



                // $real= DB::table('real_estats')->where ('id',$request->real_id)->delete();
                // $fun= DB::table('fundings')->where ('id',$request->fun_id)->delete();
                // $pay= DB::table('prepayments')->where ('id',$request->payment_id)->delete();
            // $doc= DB::table('documents')->where ('req_id',$request->id)->delete();
            // $req = DB::table('requests')->where('id', $request->id)->delete();
            //  $cust = DB::table('customers')->where('id', $request->custID)->delete();

            }

            dd('fn');
        });



        Route::get('/moveStarFromToanother', function () {


            $counter=0;

            $requests = DB::table('requests')
            ->where('user_id',68)
            ->where('is_stared',1)
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =0;

            if ($request->collaborator_id == null){

                DB::table('customers')->where('id', $request->customer_id)
                    ->update([
                        'user_id' =>  48,
                    ]);

            }

            $updatereq =  DB::table('requests')->where('id', $request->id)
            ->update([
                'user_id' =>  48,
            ]);

            if (  $updatereq )
            $counter++;




            }

            dd($counter);

        });


        Route::get('/moveStarFromToanother', function () {


            $counter=0;

            $requests = DB::table('requests')
            ->where('user_id',68)
            ->where('is_stared',1)
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

                $updatereq =0;

            if ($request->collaborator_id == null){

                DB::table('customers')->where('id', $request->customer_id)
                    ->update([
                        'user_id' =>  48,
                    ]);

            }

            $updatereq =  DB::table('requests')->where('id', $request->id)
            ->update([
                'user_id' =>  48,
            ]);

            if (  $updatereq )
            $counter++;




            }

            dd($counter);

        });



        Route::get('/removeQualityReqs', function () {

            $counter=0;


            $requests = DB::table('quality_reqs')
            ->join('requests','requests.id','quality_reqs.req_id')
            ->where('con_id',4)
            ->where('status',0)
            ->where('allow_recive',1)
            ->whereDate('quality_reqs.created_at','2020-09-20')
            ->select('quality_reqs.id','requests.created_at','quality_reqs.user_id')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){

            $date= Carbon::parse($request->created_at)->format("Y-m-d");

            if (  $date >= '2020-08-18'){

                DB::table('quality_reqs')->where('id', $request->id)->delete();
                DB::table('notifications')->where('req_id', $request->id)->where('type', 0)
                ->where('recived_id',$request->user_id)->delete();

                $counter++;
            }


            }

            dd($counter);

        });

        Route::get('/removeQualitynotification', function () {

            $counter=0;


            $requests = DB::table('notifications')
            ->join('quality_reqs','quality_reqs.id','notifications.req_id')
            ->where('notifications.type', 0)
            ->where('notifications.status',0)
            ->where('quality_reqs.allow_recive',0)
            ->whereIn('notifications.recived_id',[127,59])
            ->select('notifications.id','quality_reqs.user_id')
            ->get();

        //
            foreach( $requests as  $request){

                $deleteNot=0;

                $deleteNot=DB::table('notifications')->where('id', $request->id)->delete();

                if($deleteNot)
                $counter++;

            }

            dd($counter);

        });


        Route::get('/removeQualityReqs', function () {

            $counter=0;


            $requests = DB::table('quality_reqs')
            ->join('requests','requests.id','quality_reqs.req_id')
            ->where('con_id',4)
            ->where('status',0)
            ->where('allow_recive',1)
            ->whereDate('quality_reqs.created_at','2020-09-22')
            ->select('quality_reqs.*','requests.class_id_quality')
            ->get();


            foreach( $requests as  $request){

                if ( $request->class_id_quality == null){

                    $updatereq =  DB::table('quality_reqs')
                    ->where('id', $request->id)
                    ->update([
                        'created_at' =>  $request->updated_at,
                        'allow_recive' =>  0,
                        'status' =>  0,
                    ]);

                    $counter++;

                }

            }




            dd($counter);

        });


        Route::get('/removeQualitynotification', function () {

            $counter=0;


            $requests = DB::table('notifications')
            ->join('quality_reqs','quality_reqs.id','notifications.req_id')
            ->where('notifications.type', 0)
            ->where('notifications.status',0)
            ->whereIn('notifications.recived_id',[59])
            ->select('notifications.id','quality_reqs.user_id')
            ->get();

        //
            foreach( $requests as  $request){

                $deleteNot=0;

                if ( $request->user_id != 59)
                $deleteNot=DB::table('notifications')->where('id', $request->id)->delete();

                if($deleteNot)
                $counter++;

            }

            dd($counter);

        });



        //Route::get('/testnotification', 'AgentController@testPush');


        Route::get('/moveTaskAndReqFromToanother', function () {


            $counter=0;

            $requests = DB::table('tasks')
            ->join('quality_reqs','quality_reqs.id','tasks.req_id')
            ->where('tasks.recive_id',66)
            ->where('tasks.status',0)
            ->select('tasks.id as taskID','quality_reqs.req_id as reqID')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){


                DB::table('task_contents')->where('task_id', $request->taskID)
                    ->update([
                        'date_of_content' =>  Carbon::now(),'created_at'=>  Carbon::now()
                    ]);

                DB::table('tasks')->where('id', $request->taskID)
                    ->update([
                        'recive_id' =>  71 ,'created_at'=>  Carbon::now()
                    ]);

                $reqInfo=DB::table('requests')->where('id', $request->reqID)->first();

                if ($reqInfo->collaborator_id == null){

                DB::table('customers')->where('id', $reqInfo->customer_id)
                    ->update([
                    'user_id' =>  71,
                        ]);

                    }

                $updatereq= DB::table('requests')->where('id', $reqInfo->id)
                    ->update([
                        'user_id' =>  71 ,'statusReq'=> 0, 'agent_date'=>  Carbon::now()
                    ]);


            if ($updatereq)
            $counter++;


            }

            dd($counter);

        });



        Route::get('/moveTaskAndReqFromToanother', function () {


            $counter=0;

            $requests =DB::table('quality_reqs')
            ->whereIn('quality_reqs.id',[17180,783,406,6455,1082,17150,17152,17310,17155,17174,17171,17170,17165,17195,17185,32003,32008,32060])
            ->join('requests','requests.id','quality_reqs.req_id')
            ->select('requests.*')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){


                if ($request->collaborator_id == null){

                DB::table('customers')->where('id', $request->customer_id)
                    ->update([
                    'user_id' =>  71,
                        ]);

                    }

                $updatereq= DB::table('requests')->where('id', $request->id)
                    ->update([
                        'user_id' =>  71 ,'statusReq'=> 1, 'agent_date'=>  Carbon::now()
                    ]);


            if ($updatereq)
            $counter++;


            }

            dd($counter);

        });




        Route::get('/moveReqtoPending', function () {


            $counter=0;

            $requests = DB::table('requests')
            ->where('statusReq',0)
            ->where('agent_date','<=','22020-10-08 17:54:23')
            ->where('agent_date','>=','2020-10-08 17:50:14')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){


                $request_prnding =  PendingRequest::create([

                    'customer_id' => $request->customer_id,
                    'user_id' =>  $request->user_id,
                    'source' => $request->source,
                    'req_date' => $request->req_date,
                    'created_at' => $request->created_at,
                    'joint_id' =>  $request->joint_id,
                    'real_id' => $request->real_id,
                    'searching_id' => $request->searching_id,
                    'fun_id' =>  $request->fun_id,
                    'collaborator_id' => $request->collaborator_id,

                ]);




            if ($request_prnding){
                DB::table('requests')->where('id', $request->id)->delete();
                DB::table('notifications')->where('req_id', $request->id)->delete();
                $counter++;
            }


            }

            dd($counter);

        });


        Route::get('/moveTaskAndReqFromToanother', function () {


            $counter=0;

            $requests = DB::table('tasks')
            ->join('quality_reqs','quality_reqs.id','tasks.req_id')
            ->where('tasks.recive_id',66)
            ->where('tasks.status',0)
            ->select('tasks.id as taskID','quality_reqs.req_id as reqID')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){


                DB::table('task_contents')->where('task_id', $request->taskID)
                    ->update([
                        'date_of_content' =>  Carbon::now(),'created_at'=>  Carbon::now()
                    ]);

                DB::table('tasks')->where('id', $request->taskID)
                    ->update([
                        'recive_id' =>  71 ,'created_at'=>  Carbon::now()
                    ]);

                $reqInfo=DB::table('requests')->where('id', $request->reqID)->first();

                if ($reqInfo->collaborator_id == null){

                DB::table('customers')->where('id', $reqInfo->customer_id)
                    ->update([
                    'user_id' =>  71,
                        ]);

                    }

                $updatereq= DB::table('requests')->where('id', $reqInfo->id)
                    ->update([
                        'user_id' =>  71 ,'statusReq'=> 0, 'agent_date'=>  Carbon::now()
                    ]);


            if ($updatereq)
            $counter++;


            }

            dd($counter);

        });


        Route::get('/createAnotherRequest', function () {


            $counter=0;

            $requests = DB::table('requests')
            ->join('customers','customers.id','requests.customer_id')
            ->where('customers.mobile',555156373)
            ->select('customer_id')
            ->first();

            //dd($requests);

            $customerId= $requests->customer_id;
            $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                array( //add it once use insertGetId
                    // 'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $realID = DB::table('real_estats')->insertGetId(
                array(
                    //'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );

            $funID = DB::table('fundings')->insertGetId(
                array(
                    // 'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );


            $reqdate = (Carbon::now('Asia/Riyadh'));

            $searching_id = RequestSearching::create()->id;
            $reqID = DB::table('requests')->insertGetId(
                array(
                    'req_date' => $reqdate, 'created_at' => (Carbon::now('Asia/Riyadh')), 'searching_id' => $searching_id,
                    'user_id' => 88, 'customer_id' => $customerId, 'type' =>'تساهيل',
                    'joint_id' => $joinID, 'real_id' => $realID, 'fun_id' => $funID, 'statusReq' => 0, 'agent_date' => carbon::now(),
                )

            );

            //dd($reqID );






        });


        Route::get('/moveTaskAndReqFromToanother', function () {


            $counter=0;

            $requests = DB::table('tasks')
            ->join('quality_reqs','quality_reqs.id','tasks.req_id')
            ->where('tasks.recive_id',66)
            ->where('tasks.status',0)
            ->select('tasks.id as taskID','quality_reqs.req_id as reqID')
            ->get();

            //dd($requests);

            foreach( $requests as  $request){


                DB::table('task_contents')->where('task_id', $request->taskID)
                    ->update([
                        'date_of_content' =>  Carbon::now(),'created_at'=>  Carbon::now()
                    ]);

                DB::table('tasks')->where('id', $request->taskID)
                    ->update([
                        'recive_id' =>  151 ,'created_at'=>  Carbon::now()
                    ]);

                $reqInfo=DB::table('requests')->where('id', $request->reqID)->first();

                if ($reqInfo->collaborator_id == null){

                DB::table('customers')->where('id', $reqInfo->customer_id)
                    ->update([
                    'user_id' =>  151,
                        ]);

                    }

                $updatereq= DB::table('requests')->where('id', $reqInfo->id)
                    ->update([
                        'user_id' =>  151 ,'statusReq'=> 0, 'agent_date'=>  Carbon::now()
                    ]);


            if ($updatereq)
            $counter++;


            }

            dd($counter);

        });



        Route::get('/removeDublicateReqInPending', function () {


            $counter = 0;

            $requests = DB::table('pending_requests')
                ->select('customer_id', DB::raw('count(*) AS total'))
                ->groupBy('customer_id')
                ->havingRaw('total > 1')
                ->get();


        // dd($requests);

            foreach ($requests as  $request) {



                $total = $request->total;
                $pendingReqs = DB::table('pending_requests')
                    ->where('customer_id', $request->customer_id)
                    ->get();

                    foreach ($pendingReqs as   $pendingReq) {
                        if ($total == 1)
                    break;
                    DB::table('pending_requests')->where('id',$pendingReq->id)->delete();
                        $total--;
                    }
                    $counter++;
            }

            dd($counter);
        });


        Route::get('/changeSource', function () {


            $counter = 0;

            $requests = DB::table('requests')
            ->where('source','صديق')
            ->where('user_id',123)
            ->get();


        // dd($requests);

            foreach ($requests as  $request) {


                $updateReq = DB::table('requests')
                    ->where('id', $request->id)
                    ->update(['source'=>'Eskan']);

                    $counter++;
            }

            dd($counter);
        });



        Route::get('/MoveFllowingrequest', function () {


            $agents = array(166, 167, 183, 185, 186, 187, 189, 190, 193);

            $counter = 0;
            $i = 0;

            $requests = DB::table('requests')
                ->where('user_id', 38) //محمد الدهمي
                ->where('is_followed', 1)
                ->get();


            // dd($requests);

            foreach ($requests as  $request) {

                $reqInfo = DB::table('requests')->where('id', $request->id)->first();
                $prev_user = $reqInfo->user_id;

                $reqID = $reqInfo->id;


                #getNextAgent
                if($i == count($agents))
                $i=0;

                $nextAgent=$agents[$i];
                $i++;


                $getAllIdsInQualityReqs =
                    DB::table('quality_reqs')
                    ->where('quality_reqs.req_id', '=',  $reqID)
                    ->pluck('id')->toArray();


                #move all curent tasks to new agent
                $getAllTasksIds = DB::table('tasks')
                    ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                        $query->where(function ($query) use ($reqID, $prev_user) {
                            $query->where('tasks.req_id',  $reqID);
                            $query->where('tasks.recive_id',  $prev_user);
                        });

                        $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                            $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                            $query->where('tasks.recive_id', $prev_user);
                        });
                    })
                    ->whereIn('status', [0, 1, 2])
                    ->pluck('id')->toArray();


                if (count($getAllTasksIds) > 0) {

                    $updateTask = DB::table('tasks')
                        ->whereIn('id', $getAllTasksIds)
                        ->update([
                            'status' => 0,
                            'recive_id' =>  $nextAgent,
                            'created_at' => carbon::now(),
                        ]);
                }
                #

                $customerID = $reqInfo->customer_id;


                $updatereq = DB::table('requests')->where('id', $reqID)
                    ->update([
                        'user_id' =>  $nextAgent, 'statusReq' => 0,
                        'agent_date' => carbon::now(), 'is_stared' => 0, 'is_followed' => 0,
                        'add_to_stared' => null, 'add_to_followed' => null,
                        'isUnderProcFund' => 0, 'isUnderProcMor' => 0,
                        'recived_date_report' => null, 'recived_date_report_mor' => null,
                        // 'created_at' => carbon::now(),
                        // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
                    ]);


                if ($reqInfo->collaborator_id == null)
                    $updatecust = DB::table('customers')->where('id',  $customerID)->update([
                        'user_id' =>  $nextAgent, //active
                    ]);


                DB::table('notifications')->insert([ // add notification to send user
                    'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' =>  $nextAgent,
                    'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                    'req_id' =>  $reqID,
                ]);

                $agenInfo = DB::table('users')->where('id',  $nextAgent)->first();
                ////$pwaPush = MyHelpers::pushPWA( $nextAgent, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);


                DB::table('request_histories')->insert([ // add to request history
                    'title' => MyHelpers::admin_trans(auth()->user()->id, 'The request move'), 'user_id' => $prev_user,
                    'recive_id' =>  $nextAgent,
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id' =>  $reqID,
                    'content' => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
                ]);

                DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                    'recived_id' => $prev_user,
                    'req_id' =>  $reqID,
                ])->delete();


                #move customer's messages to new agent
                MyHelpers::movemessage($customerID,$nextAgent, $prev_user);


                if ($updatereq ==1)
                $counter++;

            }

            dd($counter);
        });

        Route::get('/moveNegativeClassToArchiveBasket', function () {


            $counter = 0;

            $requests = DB::table('requests') // get all requests with negative class and not in archive basket
            ->whereIn('statusReq', [0, 1, 4, 31])
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('is_stared', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('is_followed', 1);
                });

                $query->orWhere(function ($query) {
                    $query->where('is_followed', 0);
                    $query->where('is_stared', 0);
                });

            })
            ->join('classifcations','classifcations.id','requests.class_id_agent')
            ->where('classifcations.type',0)
            ->select('requests.*')
            ->get();

            foreach ($requests as  $request) {


                $updateReq = DB::table('requests')
                    ->where('id', $request->id)
                    ->update([
                    'statusReq' => 2,
                    'is_canceled' => 0,
                    'is_stared' => 0,
                    'is_followed' => 0,
                    'add_to_archive' => Carbon::now('Asia/Riyadh'),
                    'add_to_stared' => null,
                    'add_to_followed' => null
                    ]);

                    if ($updateReq == 1)
                    $counter++;
            }

            dd($counter);
        });


        Route::get('/moveReqToNeedActionBasket', function () {


            $counter = 0;

            $requests = DB::table('requests')
            ->join('tasks','tasks.req_id','requests.id')
            ->join('users','users.id','tasks.recive_id')
            ->where('users.status',0)
            ->whereIn('tasks.status', [0, 1, 2])
            ->select('requests.*')
            ->get();

            foreach ($requests as  $request) {

                $newReq =  RequestNeedAction::create([
                    'action' => 'مهمة لدى استشاري مؤرشف',
                    'agent_id' => $request->user_id,
                    'req_id' => $request->id,
                    'customer_id' => $request->customer_id,
                ]);

                if ($newReq)
                $counter++;

            }

            dd($counter);
        });

        Route::get('/moveReqToNeedActionBasket', function () {



            $counter = 0;

            $requests = DB::table('requests')
            ->join('tasks','tasks.req_id','requests.id')
            ->join('users','users.id','tasks.recive_id')
            ->join('users as quality','quality.id','tasks.user_id')
            ->where('users.status',0)
            ->where('quality.role','!=',5) // not quality req
            ->whereIn('tasks.status', [0, 1, 2])
            ->select('requests.*')
            ->get();

            foreach ($requests as  $request) {

                $newReq =  RequestNeedAction::create([
                    'action' => 'مهمة لدى استشاري مؤرشف',
                    'agent_id' => $request->user_id,
                    'req_id' => $request->id,
                    'customer_id' => $request->customer_id,
                ]);

                if ($newReq)
                $counter++;

            }

            $requests = DB::table('quality_reqs')
            ->join('tasks','tasks.req_id','quality_reqs.id')
            ->join('users','users.id','tasks.recive_id')
            ->join('users as quality','quality.id','tasks.user_id')
            ->where('users.status',0)
            ->where('quality.role',5) // for quality reqs
            ->whereIn('tasks.status', [0, 1, 2])
            ->select('quality_reqs.*')
            ->get();

            foreach ($requests as  $request) {

                $reqInfo=DB::table('requests')->where('id',$request->req_id)->first();
                $newReq =  RequestNeedAction::create([
                    'action' => 'مهمة لدى استشاري مؤرشف',
                    'agent_id' => $reqInfo->user_id,
                    'req_id' => $reqInfo->id,
                    'customer_id' => $reqInfo->customer_id,
                ]);

                if ($newReq)
                $counter++;

            }

            dd($counter);
        });

        Route::get('/removeNeedActionThatAgentNotArchived', function () {


            $counter = 0;

            $requests = DB::table('request_need_actions')
            ->join('users','users.id','request_need_actions.agent_id')
            ->where('request_need_actions.status',0)
            ->where('users.status',1)
            ->where('action','مهمة لدى استشاري مؤرشف')
            ->select('request_need_actions.*','users.name')
            ->get();


            foreach ($requests as  $request) {

                $deleteReq =  RequestNeedAction::where('id',$request->id)
                ->delete();

                if ( $deleteReq)
                $counter++;

            }

            dd($counter);
        });
        Route::get('/HindNotificationOfDublicateCustomer', function () {


            $counter = 0;

            $requests = DB::table('notifications')
            ->join('requests','requests.id','notifications.req_id')
            ->join('users','users.id','requests.user_id')
            ->where('notifications.value','العميل حاول تسجيل طلب مرة أخرى')
            ->whereDate('notifications.created_at','>=','2020-12-01')
            ->where('users.status',0)
            ->where('requests.class_id_agent','!=',16) //استبعاد المرفوض
            ->select('requests.*')
            ->distinct('notifications.req_id')
            ->get();


            foreach ($requests as  $request) {

        $checkIfExisted=RequestNeedAction::where('req_id',$request->id)->where('status',0)->first();

        if (!$checkIfExisted){

            $checkReqInfo=DB::table('requests')
            ->join('users','users.id','requests.user_id')
            ->where('requests.id',$request->id)
            ->where('users.status',0)
            ->first();

            if ($checkReqInfo){
                $newReq =  RequestNeedAction::create([
                    'action' => 'عميل مكرر لإستشاري مؤرشف',
                    'agent_id' => $request->user_id,
                    'req_id' => $request->id,
                    'customer_id' => $request->customer_id,
                ]);

                if ($newReq)
                $counter++;
            }

        }


            }

            dd($counter);
        });
        Route::get('/removeNeedActionReqsThatNotWithArchiveAgent', function () {


            $counter = 0;

            $requests = DB::table('request_need_actions')
            ->join('users','users.id','request_need_actions.agent_id')
            ->where('request_need_actions.action','عميل مكرر لإستشاري مؤرشف')
            ->where('users.status',1)
            ->where('request_need_actions.status',0)
            ->select('request_need_actions.*')
            ->get();



            foreach ($requests as  $request) {


                $delReq =  RequestNeedAction::where('id',$request->id)
                ->delete();

                if ($delReq)
                $counter++;



            }

            dd($counter);
        });


        Route::get('/moveStarReqFromHind', function () {


            $counter = 0;
            $i = 0;
            $salesAgents = array();

                    $salesAgents = DB::table('users')
                    ->where('role', 0)
                    ->where('status', 1)
                    ->where('manager_id', 30)// ohod's team
                    ->whereNotIn('id',[120,115,135,61])
                    ->pluck('id')
                    ->toArray();


        $requests_data=DB::table('requests')
            ->where('user_id',54)
            ->where('is_stared',1)
            ->get();

                foreach ($requests_data as $reqInfo) {

                    if (count($salesAgents) == $i)
                        $i = 0;

                    $updatereq = 0;

                    $prev_user = $reqInfo->user_id;
                    $reqID = $reqInfo->id;
                    $getAllIdsInQualityReqs =
                        DB::table('quality_reqs')
                        ->where('quality_reqs.req_id', '=',  $reqID)
                        ->pluck('id')->toArray();


                    #move all curent tasks to new agent
                    $getAllTasksIds = DB::table('tasks')
                        ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                            $query->where(function ($query) use ($reqID, $prev_user) {
                                $query->where('tasks.req_id',  $reqID);
                                $query->where('tasks.recive_id',  $prev_user);
                            });

                            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                                $query->where('tasks.recive_id', $prev_user);
                            });
                        })
                        ->whereIn('status', [0, 1, 2])
                        ->pluck('id')->toArray();


                    if (count($getAllTasksIds) > 0) {

                        $updateTask = DB::table('tasks')
                            ->whereIn('id', $getAllTasksIds)
                            ->update([
                                'status' => 0,
                                'recive_id' => $salesAgents[$i],
                                'created_at' => carbon::now(),
                            ]);
                    }
                    #

                    $customerID = $reqInfo->customer_id;
                    $updatereq = DB::table('requests')->where('id', $reqID)
                        ->update([
                            'user_id' => $salesAgents[$i], 'statusReq' => 0,
                            'agent_date' => carbon::now(), 'is_stared' => 0, 'is_followed' => 0,
                            'add_to_stared' => null, 'add_to_followed' => null,
                            'isUnderProcFund' => 0, 'isUnderProcMor' => 0,
                            'recived_date_report' => null, 'recived_date_report_mor' => null,
                            // 'created_at' => carbon::now(),
                            // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
                        ]);
                    if ($updatereq)
                        $counter++;


                    if ($reqInfo->collaborator_id == null)
                        $updatecust = DB::table('customers')->where('id',  $customerID)->update([
                            'user_id' => $salesAgents[$i], //active
                        ]);

                    DB::table('notifications')->insert([ // add notification to send user
                        'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' => $salesAgents[$i],
                        'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                        'req_id' => $reqID,
                    ]);

                    $agenInfo = DB::table('users')->where('id', $salesAgents[$i])->first();
                    ////$pwaPush = MyHelpers::pushPWA($salesAgents[$i], ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

                    DB::table('request_histories')->insert([ // add to request history
                        'title' => MyHelpers::admin_trans(auth()->user()->id, 'The request move'), 'user_id' => $prev_user,
                        'recive_id' => $salesAgents[$i],
                        'history_date' => (Carbon::now('Asia/Riyadh')),
                        'req_id' => $reqID,
                        'content' => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
                    ]);

                    DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                        'recived_id' => $prev_user,
                        'req_id' => $reqID,
                    ])->delete();


                    #move customer's messages to new agent
                    MyHelpers::movemessage($customerID, $salesAgents[$i], $prev_user);

                    $i++;
                }

            dd($counter);
        });
        Route::get('/getNumberOfCustomerToday', function () {


            $counter = 0;

            $requests = DB::table('requests')
            ->whereDate('created_at','2021-01-04')
            ->get()
            ->count();

            $pendingRequests = DB::table('pending_requests')
            ->whereDate('created_at','2021-01-04')
            ->get()
            ->count();

            $dublicateCustomer=DB::table('notifications')
            ->whereDate('created_at','2021-01-04')
            ->where('value','العميل حاول تسجيل طلب مرة أخرى')
            ->get()
            ->count();

        dd( $dublicateCustomer + $pendingRequests + $requests);

        });


        Route::get('/updateSalesManagerOfReq', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('isSentSalesManager', 1)
                ->get();

            foreach ($requests as  $request) {

                $updateSalesManagerRequest = MyHelpers::salesManagerRequestProcess($request->id, $request->user_id);
                if ($updateSalesManagerRequest == 1)
                    $counter++;
            }

            dd($counter);
        });

        Route::get('/updateFundingManagerOfReq', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('isSentFundingManager', 1)
                ->get();

            foreach ($requests as  $request) {

                $updateFundingManagerRequest = MyHelpers::fundingManagerRequestProcess($request->id);
                if ($updateFundingManagerRequest == 1)
                    $counter++;
            }

            dd($counter);
        });
        Route::get('/updateMortgageManagerOfReq', function () {


            $counter = 0;

            $requests = DB::table('requests')
                ->where('isSentMortgageManager', 1)
                ->get();

            foreach ($requests as  $request) {

                $updateMortgageManagerRequest = MyHelpers::mortgageManagerRequestProcess($request->id);
                if ($updateMortgageManagerRequest == 1)
                    $counter++;
            }

            dd($counter);
        });
        Route::get('/spreadRawanRequestsToFollowingUsers', function () {


            $counter = 0;
            $i = 0;

            $requests = DB::table('requests')
                ->whereIn('class_id_agent', [1, 9, 15, 33])
                ->where('user_id', 136)
                ->get()
                ->count();

                dd($requests);

            $agents = array(227, 235, 237, 238);


            foreach ($requests as  $request) {

                #getNextAgent
                if ($i == count($agents))
                    $i = 0;

                $nextAgent = $agents[$i];
                $i++;

                $newReq = null;
                $checkAddingStatus = false;

                if (MyHelpers::checkQualityReq($request->id))
                    $checkAddingStatus = true;
                else {
                    $quality_req_id = DB::table('quality_reqs')
                        ->where('quality_reqs.req_id', $request->id)
                        ->whereIn('quality_reqs.user_id',$agents)
                        ->first();
                    if (empty($quality_req_id)) {

                        //Update Current quality req in archived quality to complete before creating another one
                        MyHelpers::updateQualityReqToComplete($quality_req_id->id);

                        $checkAddingStatus = true;
                    }
                }

                if ($checkAddingStatus) {
                    #------remove need action req if existed(will nt allowed to recived same request with admin & quality)
                    $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($request->id);
                    if ($needReq != 'false') {
                        MyHelpers::removeNeedActionReq($needReq->id);
                    }
                    #----------------------------

                        $newReq =  quality_req::create([
                            'req_id' =>  $request->id,
                            'created_at' => (Carbon::now('Asia/Riyadh')),
                            'user_id' => $nextAgent,
                        ]);


                        DB::table('request_histories')->insert([ // add to request history
                            'title' => MyHelpers::admin_trans(auth()->user()->id, 'Move Req To Quality'),
                            'user_id' => null,
                            'recive_id' => $nextAgent,
                            'history_date' => (Carbon::now('Asia/Riyadh')),
                            'req_id' => $request->id,
                            'content' => null,
                        ]);

                        DB::table('notifications')->insert([ // add notification to send user
                            'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' => $newReq->user_id,
                            'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                            'req_id' => $newReq->id,
                        ]);

                }
            }
        });
        Route::get('/testSMSCODE', function () {
        $test=MyHelpers::sendSmsOtp(537268249, 5555);
        dd($test);
        });

        Route::get('/toTestMail', function () {
            $test=MyHelpers::testSendEmailNotifiaction('alwsatarealestateofficial@gmail.com', 'TEST From Google', 'ITS ONLY TEST');
            });


        Route::get('/qualityReqsCount', function () {

            $agents = array(
            // 235,
            238,
                237
            );

            $counter = 0;
            $i = 0;

            $quality_req_id = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->where('quality_reqs.user_id', 59)
                ->where('requests.class_id_quality', null)
                ->where('requests.quacomment', null)
                ->where('quality_reqs.status', 1)
                ->where('quality_reqs.is_followed', 0)
                ->select('quality_reqs.*')

                ->get()
                ->take(100);


            foreach ($quality_req_id as  $request) {

                #getNextAgent
                if ($i == count($agents))
                    $i = 0;

                $nextAgent = $agents[$i];
                $i++;


                $updateReq = DB::table('quality_reqs')
                    ->where('id', $request->id)
                    ->update(['user_id' => $nextAgent,'status' => 0]);

                $removeNotifications = DB::table('notifications')
                    ->where('recived_id', 59)
                    ->where('req_id', $request->id)
                    ->delete();

                $addNotifyToNewUser = DB::table('notifications')->insert([
                    'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' => $nextAgent,
                    'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                    'req_id' => $request->id,
                ]);


                $counter++;
            }

            dd( $counter);
        });




        Route::get('/toDetectFollowReqsWithoutFollowDates', function () {

            //dd(Carbon::today('Asia/Riyadh')->format("Y-m-d")); // "2021-05-25"
            //dd(Carbon::parse('2021-05-26')->format("Y-m-d"));//tommorw

            $dates = array(
            '2021-05-26',
            '2021-05-27',
            '2021-05-30'
            );
            $counter = 0;
            $i=0;

            $follow_reqs = DB::table('requests')
                ->where('statusReq', 1)
                ->where('is_followed', 1)
                ->where('user_id', 210)
                //->take(8)
                ->pluck('id');


                $follow_reqs_all = DB::table('requests')
                ->where('statusReq', 1)
                ->where('is_followed', 1)
                ->where('user_id', 210)
                ->get();
                //->take(8)
                //->pluck('id');
                //->take(50);
                //->count();
                //dd($follow_reqs);

                $missedFollow =
                notification::where('request_type', '=', 1)
                            ->whereIn('req_id', $follow_reqs )
                            ->where('recived_id', 210)
                            ->whereNotNull('reminder_date')
                            ->whereIn('status', [0])
                            ->pluck('req_id');

                $futureFollow =
                notification::where('request_type', '=', 1)
                            ->whereIn('req_id', $follow_reqs )
                            ->where('recived_id', 210)
                            ->whereNotNull('reminder_date')
                            ->whereIn('status', [2])
                            ->pluck('req_id');

                $notHasFollow =
                DB::table('requests')
                ->whereIn('id', $follow_reqs )
                ->whereNotIn('id', $missedFollow )
                ->whereNotIn('id', $futureFollow )
                ->get();



                //***********UPDATE MISSED REMINDER*****************************
                foreach ($follow_reqs_all as  $request) {

                    if ($i == count($dates))
                    $i = 0;

                $checkFollow =   notification::where('request_type', '=', 1)
                            ->where('req_id', $request->id )
                            ->where('recived_id', 210)
                            ->whereNotNull('reminder_date')
                            ->whereIn('status', [0])
                            ->first();

                if (!empty($checkFollow)) {

                    $date=Carbon::parse($dates[$i])->format("Y-m-d");
                    $i++;
                    $time = "00:00";
                    $newValue = $date . "T" . $time;
                    $overWriteReminder = DB::table('notifications')
                                ->where('id',  $checkFollow->id)
                                ->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]);

                                $counter++;

                }

            }
            //*****************************************************************

            //***********INSERT NEW REMINDER*****************************
            foreach ($notHasFollow as  $request) {

            if ($i == count($dates))
            $i = 0;

                    $date=Carbon::parse($dates[$i])->format("Y-m-d");
                    $i++;
                    $time = "00:00";
                    $newValue = $date . "T" . $time;

                    $insertNew=DB::table('notifications')->insert([
                        'value' => MyHelpers::admin_trans(auth()->user()->id, 'The request need following'), 'recived_id' =>$request->user_id,
                        'status' => 2, 'type' => 1, 'reminder_date' => $newValue,
                        'req_id' => $request->id, 'created_at' => (Carbon::now('Asia/Riyadh')),
                    ]);

                    if ($insertNew)
                    $counter++;

            }

            dd($counter);
        });


        Route::get('/toTestMail', function () {
            $test=MyHelpers::testSendEmailNotifiaction('afnan.wsata@gmail.com', 'TEST From Google', 'ITS ONLY TEST');
            });



            Route::get('/qualityReqsCount', function () {

                $agents = array(
                235,//khalid
                    278,//doaa
                    59 // shaymaa
                );

                $counter = 0;
                $i = 0;

                $requestsNotRecived = DB::table('quality_reqs')
                ->join('request_conditions', 'request_conditions.id', 'quality_reqs.con_id')
                ->where('quality_reqs.allow_recive', 0)
                ->where('quality_reqs.con_id', '!=', null)
                ->select('quality_reqs.id', 'quality_reqs.req_id', 'quality_reqs.user_id', 'quality_reqs.allow_recive', 'request_conditions.timeDays', 'quality_reqs.created_at')
                ->get()
                ->take(50);

                dd( $requestsNotRecived );

                foreach ($requestsNotRecived as  $request) {

                    if ($i == count($agents))
                    $i = 0;

                    $user_id = $agents[$i];

                        if (MyHelpers::checkConditionMatch($request->id)) {

                            $counter++;
                            $ifThereIsPreviousReq = MyHelpers::checkQualityUser($request->id, $user_id);

                            if ($ifThereIsPreviousReq == "true") {
                                DB::table('quality_reqs')->where('id', $request->id)
                                    ->update(['allow_recive' => 1, 'user_id' => $user_id, 'created_at' => Carbon::now('Asia/Riyadh')]);

                                DB::table('notifications')->insert([
                                    'value' => MyHelpers::guest_trans('New Request Added'), 'recived_id' => $user_id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                                    'req_id' => $request->id,
                                ]);

                                DB::table('request_histories')->insert([ // add to request history
                                    'title' => MyHelpers::guest_trans('Move Req To Quality'),
                                    'user_id' => null,
                                    'recive_id' => $user_id,
                                    'history_date' => (Carbon::now('Asia/Riyadh')),
                                    'req_id' => $request->req_id,
                                    'content' => null,
                                ]);
                                $i++;
                            } else {

                                $checkIfQualityUserArchived = User::where('id', $ifThereIsPreviousReq)
                                    ->where('status', 1)->where('allow_recived', 1)->first();

                                if (!empty($checkIfQualityUserArchived))
                                    $user_id = $checkIfQualityUserArchived->id;
                                else
                                    $i++;


                                DB::table('quality_reqs')->where('id', $request->id)
                                    ->update(['allow_recive' => 1, 'user_id' => $user_id, 'created_at' => Carbon::now('Asia/Riyadh')]);

                                DB::table('notifications')->insert([
                                    'value' => MyHelpers::guest_trans('New Request Added'), 'recived_id' => $user_id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                                    'req_id' => $request->id,
                                ]);

                                DB::table('request_histories')->insert([ // add to request history
                                    'title' => MyHelpers::guest_trans('Move Req To Quality'),
                                    'user_id' => null,
                                    'recive_id' => $user_id,
                                    'history_date' => (Carbon::now('Asia/Riyadh')),
                                    'req_id' => $request->req_id,
                                    'content' => null,
                                ]);
                            }
                        }
                }


                dd( $counter);
            });

            Route::get('/qualityReqsCount', function () {


                $counter=0;
                $requestsNotRecived = DB::table('quality_reqs')
                ->where('status',0)
                ->get();

                foreach ($requestsNotRecived as  $request) {

                $quality_count= DB::table('quality_reqs')
                ->where('id','!=', $request->id)
                ->where('req_id', $request->req_id)
                    ->where('status',0)
                    ->get();

                    foreach ($quality_count as $quality){

                        DB::table('notifications')
                        ->where('recived_id',$quality->user_id)
                        ->where('req_id',$quality->id)
                        ->where('type',0)
                        ->delete();

                        DB::table('quality_reqs')
                        ->where('id',$quality->id)
                        ->delete();

                        $counter++;
                    }

                }


                dd( $counter);
            });




            Route::get('/remove_quality', function () {


                $counter=0;
                $requestsNotRecived = DB::table('quality_reqs')
                ->join('users', 'users.id', 'quality_reqs.user_id')
                ->where('quality_reqs.status',0)
                ->whereDay('quality_reqs.created_at', '=', '17')
                ->whereMonth('quality_reqs.created_at', '=', '8')
                ->whereYear('quality_reqs.created_at', '=', '2021')
                ->select('quality_reqs.*','users.status as user_status')
                ->get();

                foreach ($requestsNotRecived as  $request) {

                $quality_count= DB::table('quality_reqs')
                ->join('users', 'users.id', 'quality_reqs.user_id')
                ->where('quality_reqs.id','!=', $request->id)
                ->where('quality_reqs.req_id', $request->req_id)
                ->where('quality_reqs.allow_recive', 1)
                ->select('quality_reqs.*','users.status as user_status')
                    ->get();

                    foreach ($quality_count as $quality){

                        if ($quality->user_id == $request->user_id){
                            if ($quality->status != 4 && $quality->status != 3){

                                DB::table('notifications')
                                ->where('recived_id',$request->user_id)
                                ->where('req_id',$request->id)
                                ->delete();

                                DB::table('quality_reqs')
                                ->where('id', $request->id)
                                ->delete();

                                $counter++;
                            }
                        }
                        else if ($quality->user_status == 0){

                            DB::table('quality_reqs')->where('id', $quality->id)
                                ->update(['status' => 3]);
                                $updateTask = DB::table('tasks')
                                ->where('req_id',$quality->id)
                                ->where('user_id',$quality->user_id)
                                ->update([
                                    'status' => 3 //completed
                                ]);

                            $counter++;
                        }
                        else if ($quality->user_id != $request->user_id){
                        if ($quality->status != 4 && $quality->status != 3){
                            DB::table('notifications')
                            ->where('recived_id',$request->user_id)
                            ->where('req_id',$request->id)
                            ->delete();

                            DB::table('quality_reqs')
                            ->where('id', $request->id)
                            ->delete();

                            $counter++;
                        }
                        else{
                            DB::table('notifications')
                            ->where('recived_id',$request->user_id)
                            ->where('req_id',$request->id)
                            ->update(['recived_id' =>$quality->user_id ]);

                            DB::table('quality_reqs')
                            ->where('id', $request->id)
                            ->update(['user_id' =>$quality->user_id ]);
                            $counter++;
                        }
                    }


                }
            }


            dd( $counter);
            });


            Route::get('/remove_quality', function () {


                $counter=0;
                $requestsNotRecived = DB::table('quality_reqs')
                ->join('users', 'users.id', 'quality_reqs.user_id')
                ->where('quality_reqs.status',0)
                ->whereDay('quality_reqs.created_at', '=', '17')
                ->whereMonth('quality_reqs.created_at', '=', '8')
                ->whereYear('quality_reqs.created_at', '=', '2021')
                ->select('quality_reqs.*','users.status as user_status')
                ->get();

                foreach ($requestsNotRecived as  $request) {


                    $check=MyHelpers::checkConditionMatch($request->id);

                    if ( $check == false){
                        DB::table('quality_reqs')->where('id', $request->id)
                        ->update(['status' => 3]);
                        $updateTask = DB::table('tasks')
                        ->where('req_id',$request->id)
                        ->where('user_id',$request->user_id)
                        ->update([
                            'status' => 3 //completed
                        ]);
                        $counter++;
                    }

                }


            dd( $counter);
            });


            Route::get('/restore_qulity_req', function () {


                $counter=0;
                $requestsNotRecived = DB::table('quality_reqs')
                ->whereIn('quality_reqs.user_id',[286,285])
                ->get();

                foreach ($requestsNotRecived as  $request) {


                    $updateTask  = DB::table('quality_reqs')
                    ->where('quality_reqs.id', '=', $request->id)
                    ->where('quality_reqs.status', 1)
                    ->update(['status' =>0, 'is_followed' => 0]);


                    if ( $updateTask==1)
                    $counter++;

                }


            dd( $counter);
            });


            Route::get('/pendingmovment', function () {

                $counter=0;
                $i = 0;
                $request_histories  = DB::table('request_histories')
                ->where('content', 'الطلبات المعلقة')
                ->whereDate('history_date','2021-08-22')
                ->join('requests', 'requests.id', 'request_histories.req_id')
                ->where('requests.statusReq',0)
                ->select('request_histories.*')
                ->get()
                ->take(28);

                $salesAgents = DB::table('users')->where('role', 0)->where('id','>', 183)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();

                foreach ($request_histories as  $request) {

                    if (count($salesAgents) == $i)
                    $i = 0;

                    $updatereq = DB::table('requests')->where('id', $request->req_id)
                    ->update([
                        'user_id' => $salesAgents[$i], 'statusReq' => 0,
                        'agent_date' => carbon::now(), 'is_stared' => 0, 'is_followed' => 0,
                        'add_to_stared' => null, 'add_to_followed' => null,
                        'isUnderProcFund' => 0, 'isUnderProcMor' => 0,
                        'recived_date_report' => null, 'recived_date_report_mor' => null,
                    ]);


                DB::table('notifications')->insert([ // add notification to send user
                    'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' => $salesAgents[$i],
                    'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                    'req_id' => $request->req_id,
                ]);

                DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                    'recived_id' =>$request->recive_id,
                    'req_id' => $request->req_id,
                ])->delete();

                DB::table('request_histories')->where('id',$request->id)
                ->update(['recive_id'=>$salesAgents[$i]]);

                $counter++;
                $i++;

                }




                dd( $counter);

            });


            Route::get('/pendingremovedublicate', function () {

                $counter=0;
                $requestsNotRecived = DB::table('pending_requests')
                ->get();

                foreach ($requestsNotRecived as  $request) {

                    $current= DB::table('pending_requests')
                    ->where('id','!=',$request->id)
                    ->where('customer_id',$request->customer_id)
                    ->get();

                    if ($current->count() > 0){

                        foreach ($current as  $delete_req) {

                            DB::table('pending_requests')
                            ->where('id',$delete_req->id)
                            ->delete();

                            $counter++;

                        }
                    }
                }

                dd( $counter);

            });


            Route::get('/requestremovedublicate', function () {

                $counter=0;
                $requestsNotRecived = DB::table('requests')
                ->where('statusReq',1)
                ->get();

                foreach ($requestsNotRecived as  $request) {

                    $current= DB::table('requests')
                    ->where('id','!=',$request->id)
                    ->where('statusReq',1)
                    ->whereDate('agent_date','<',Carbon::parse($request->agent_date))
                    ->where('customer_id',$request->customer_id)
                    ->get();

                    if ($current->count() > 0){

                        foreach ($current as  $delete_req) {

                            DB::table('requests')
                            ->where('id',$delete_req->id)
                            ->delete();

                            DB::table('notifications')->where([
                                'recived_id' => $delete_req->user_id,
                                'req_id' => $delete_req->id,
                            ])->delete();

                            $counter++;

                        }
                    }
                }

                dd( $counter);

            });

            Route::get('/agentrequestremovedublicate', function () {

                $counter=0;
                $requestsNotRecived = DB::table('requests')
                ->where('customer_id','>',18773)
                ->get();

                foreach ($requestsNotRecived as  $request) {

                    $current= DB::table('requests')
                    ->where('id','!=',$request->id)
                    ->where('customer_id',$request->customer_id)
                    ->get();

                    $length=$current->count();

                    if ($length > 0){

                        foreach ($current as  $delete_req) {

                            DB::table('quality_reqs')
                            ->where('req_id',$delete_req->id)
                            ->delete();

                            DB::table('requests')
                            ->where('id',$delete_req->id)
                            ->delete();

                            DB::table('notifications')->where([
                                'recived_id' => $delete_req->user_id,
                                'req_id' => $delete_req->id,
                            ])->delete();

                            $counter++;

                        }
                    }

                }

                dd( $counter);

            });


        Route::get('/restorerequestafterremove', function () {

            $customerId=21747;

            $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                array( //add it once use insertGetId
                    // 'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )
            );

            $realID = DB::table('real_estats')->insertGetId(
                array(
                    //'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );

            $funID = DB::table('fundings')->insertGetId(
                array(
                    // 'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                )

            );


            $reqdate = (Carbon::parse('2020-08-11'));
            $source='ويب - اطلب تمويل';
            $agentID=187;

            $searching_id = RequestSearching::create()->id;
            $reqID = DB::table('requests')->insertGetId(
                array(
                    'source' => $source, 'req_date' => $reqdate, 'created_at' => (Carbon::now('Asia/Riyadh')), 'searching_id' => $searching_id,
                    'user_id' =>  $agentID, 'customer_id' => $customerId, 'collaborator_id' => null,
                    'joint_id' => $joinID, 'real_id' => $realID, 'fun_id' => $funID, 'statusReq' => 1, 'agent_date' => carbon::now(),
                )

            );





        });



        Route::get('/movependingrequest', function () {

            #285 : arwa , 286 : ahlam.
            $i=0;
            $counter=0;
            $quality_users=[285,286];

            $requestsNotRecived = DB::table('quality_reqs')
            ->where('user_id',59)
            ->where('status',0)
            ->where('allow_recive',1)
            ->get()
            ->take(800);

            foreach ($requestsNotRecived as  $request) {

                if($i == count($quality_users))
                $i=0;

                $quality_user=$quality_users[$i];
                $i++;


                $updateTask  = DB::table('quality_reqs')
                ->where('id', $request->id)
                ->update(['user_id' => $quality_user,'status' => 0, 'created_at' => (Carbon::now('Asia/Riyadh'))]);

                DB::table('notifications')
                ->where('req_id', $request->id)
                ->where('recived_id', 59)
                ->update(['recived_id' => $quality_user,'status' => 0, 'created_at' => (Carbon::now('Asia/Riyadh'))]);


                if ( $updateTask==1)
                $counter++;

            }


        dd( $counter);
        });


        Route::get('/updatecustomerworkresources', function () {

            $customers = DB::table('customers')
            ->whereIn('work',['عسكري','مدني','متقاعد','شبه حكومي','قطاع خاص غير معتمد','قطاع خاص معتمد'])
            ->get();

            foreach ($customers as  $customer) {

                $work=null;

                if ($customer->work == 'عسكري')
                $work=1;
                else if ($customer->work == 'مدني')
                $work=2;
                else if ($customer->work == 'متقاعد')
                $work=3;
                else if ($customer->work == 'شبه حكومي')
                $work=4;
                else if ($customer->work == 'قطاع خاص غير معتمد')
                $work=5;
                else if ($customer->work == 'قطاع خاص معتمد')
                $work=6;


                DB::table('customers')
                ->where('id',$customer->id)
                ->update(['work' => $work]);

            }

        });

        Route::get('/updatejointworkresources', function () {

            $joints = DB::table('joints')
            ->whereIn('work',['عسكري','مدني','متقاعد','شبه حكومي','قطاع خاص غير معتمد','قطاع خاص معتمد'])
            ->get();

            foreach ($joints as  $joint) {

                $work=null;

                if ($joint->work == 'عسكري')
                $work=1;
                else if ($joint->work == 'مدني')
                $work=2;
                else if ($joint->work == 'متقاعد')
                $work=3;
                else if ($joint->work == 'شبه حكومي')
                $work=4;
                else if ($joint->work == 'قطاع خاص غير معتمد')
                $work=5;
                else if ($joint->work == 'قطاع خاص معتمد')
                $work=6;


                DB::table('joints')
                ->where('id',$joint->id)
                ->update(['work' => $work]);

            }

        });


        Route::get('/updaterequestssources', function () {

            $requests = DB::table('requests')
            ->whereIn('source',['مكالمة فائتة','متعاون','صديق','مدير النظام','تلفون ثابت','Eskan','ويب - اطلب تمويل','ويب - اطلب استشارة','ويب - الحاسبة العقارية','مكالمة لم تسجل','تطبيق - أطلب إستشارة','تطبيق - حاسبة التمويل'])
            ->get();

            foreach ($requests as  $request) {

                $source=null;

                if ($request->source == 'مكالمة فائتة')
                $source=1;
                else if ($request->source == 'متعاون')
                $source=2;
                else if ($request->source == 'صديق')
                $source=3;
                else if ($request->source == 'مدير النظام')
                $source=4;
                else if ($request->source == 'تلفون ثابت')
                $source=5;
                else if ($request->source == 'Eskan')
                $source=6;
                else if ($request->source == 'ويب - اطلب تمويل')
                $source=7;
                else if ($request->source == 'ويب - اطلب استشارة')
                $source=8;
                else if ($request->source == 'ويب - الحاسبة العقارية')
                $source=9;
                else if ($request->source == 'مكالمة لم تسجل')
                $source=10;
                else if ($request->source == 'تطبيق - أطلب إستشارة')
                $source=11;
                else if ($request->source == 'تطبيق - حاسبة التمويل')
                $source=12;


                DB::table('requests')
                ->where('id',$request->id)
                ->update(['source' => $source]);

            }

        });

        Route::get('/updatenullrequestssources', function () {
            $requests = DB::table('requests')
            ->where('source',null)
            ->where('collaborator_id','!=',null)
            ->get();

            foreach ($requests as  $request) {
                $source=2;
                DB::table('requests')
                ->where('id',$request->id)
                ->update(['source' => $source]);
            }
        });


        Route::get('/updatependingrequestssources', function () {

            $requests = DB::table('pending_requests')
            ->whereIn('source',['مكالمة فائتة','متعاون','صديق','مدير النظام','تلفون ثابت','Eskan','ويب - اطلب تمويل','ويب - اطلب استشارة','ويب - الحاسبة العقارية','مكالمة لم تسجل','تطبيق - أطلب إستشارة','تطبيق - حاسبة التمويل'])
            ->get();

            foreach ($requests as  $request) {

                $source=null;

                if ($request->source == 'مكالمة فائتة')
                $source=1;
                else if ($request->source == 'متعاون')
                $source=2;
                else if ($request->source == 'صديق')
                $source=3;
                else if ($request->source == 'مدير النظام')
                $source=4;
                else if ($request->source == 'تلفون ثابت')
                $source=5;
                else if ($request->source == 'Eskan')
                $source=6;
                else if ($request->source == 'ويب - اطلب تمويل')
                $source=7;
                else if ($request->source == 'ويب - اطلب استشارة')
                $source=8;
                else if ($request->source == 'ويب - الحاسبة العقارية')
                $source=9;
                else if ($request->source == 'مكالمة لم تسجل')
                $source=10;
                else if ($request->source == 'تطبيق - أطلب إستشارة')
                $source=11;
                else if ($request->source == 'تطبيق - حاسبة التمويل')
                $source=12;


                DB::table('pending_requests')
                ->where('id',$request->id)
                ->update(['source' => $source]);

            }

        });



        Route::get('/qualityReqsCount', function () {

            $agents = array(
            235,//khalid
                278,//doaa
                59, // shaymaa
                238, // balafife
                286 // ahlam
            );

            $counter = 0;
            $i = 0;

            $requestsNotRecived = DB::table('quality_reqs')
            ->join('request_conditions', 'request_conditions.id', 'quality_reqs.con_id')
            ->where('quality_reqs.allow_recive', 1)
            ->where('quality_reqs.user_id', 235)
            ->where('quality_reqs.status', 0)
            ->select('quality_reqs.id', 'quality_reqs.req_id', 'quality_reqs.user_id', 'quality_reqs.allow_recive', 'request_conditions.timeDays', 'quality_reqs.created_at')
            ->get()
            ->take(150);


            foreach ($requestsNotRecived as  $request) {

                $updateReq = DB::table('quality_reqs')
                ->where('id', $request->id)
                ->update(['user_id' => 286,'status' => 0]);

            $removeNotifications = DB::table('notifications')
                ->where('recived_id',$request->user_id)
                ->where('req_id', $request->id)
                ->delete();

            $addNotifyToNewUser = DB::table('notifications')->insert([
                'value' => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'), 'recived_id' => 286,
                'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 0,
                'req_id' => $request->id,
            ]);

            $counter++;

            }



    dd( $counter);
});


Route::get('/test_command', function () {
    $customers = DB::table('customers')
    ->select('id',DB::raw('COUNT(*) as NUM'),'mobile')
    ->groupBy('mobile')
    ->havingRaw('NUM > 1')
    ->get();

    foreach($customers as $customer){
        # get last customer id
        $customer_info = DB::table('customers')
        ->where('mobile', $customer->mobile)
        ->latest('id')->first();

        dd($customer_info);
    }

});

Route::get('/qualityReqsMovmentFromArchived', function () {

    $agents = [
        285,//Arwa
        237,//qc
        235, // khalid
        227, // raghad
        127 // ammera
    ];

    $counter = 0;

    $requestsNotRecived = DB::table('quality_reqs')->join('request_conditions', 'request_conditions.id', 'quality_reqs.con_id')->where('quality_reqs.allow_recive', 0)->whereIn('quality_reqs.user_id', $agents)->select('quality_reqs.id', 'quality_reqs.req_id', 'quality_reqs.user_id',
        'quality_reqs.allow_recive', 'request_conditions.timeDays', 'quality_reqs.created_at')->get();

    foreach ($requestsNotRecived as $request) {

        if ($counter <= 200) {
            #------remove need action req if existed(will nt allowed to recived same request with admin & quality)
            $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($request->req_id);
            if ($needReq != 'false') {
                MyHelpers::removeNeedActionReq($needReq->id);
            }
            #----------------------------

            $user_id = $request->user_id;

            $ifThereIsPreviousReq = MyHelpers::checkQualityUser($request->id, $user_id);

            if ($ifThereIsPreviousReq == "true") {

            }
            else {
                $checkIfQualityUserArchived = User::where('id', $ifThereIsPreviousReq)->where('status', 1)->first();

                if (empty($checkIfQualityUserArchived)) {
                    $user_id = 278;
                    DB::table('quality_reqs')->where('id', $request->id)->update(['status' => 0, 'is_followed' => 0, 'allow_recive' => 1, 'user_id' => $user_id, 'created_at' => (Carbon::now('Asia/Riyadh'))]);

                    $removeNotifications = DB::table('notifications')->where('recived_id', $request->user_id)->where('req_id', $request->id)->delete();

                    $addNotifyToNewUser = DB::table('notifications')->insert([
                        'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                        'recived_id' => $user_id,
                        'created_at' => (Carbon::now('Asia/Riyadh')),
                        'type'       => 0,
                        'req_id'     => $request->id,
                    ]);

                    $counter++;

                }

            }
        }
        else {
            break;
        }
    }
    dd($counter);
});

Route::get('/move_request_to_archive', function () {

    $req_id = 102199;
    $request = DB::table('requests')
    ->where('id',$req_id)
    ->update(['type' => 'رهن','statusReq' => 2]);


    DB::table('request_histories')->insert([
        'title'        => 'نقل الطلب إلى الأرشيف',
        'user_id'      => null,
        'recive_id'    => null,
        'history_date' => (Carbon::now('Asia/Riyadh')),
        'req_id'       => $req_id,
        'content'      => 'من قبل مدير النظام',
    ]);

});


Route::get('/move_request_to_another_sm', function () {

    $req_id_array = [118829,119066,117259];
    $request = DB::table('requests')
    ->whereIn('id',$req_id_array)->update(['sales_manager_id' => 153]);

foreach ($req_id_array as $req_id) {
    DB::table('request_histories')->insert([
        'title'        => 'نقل الطلب إلى مدير مبيعات آخر',
        'user_id'      => 165,
        'recive_id'    => 153,
        'history_date' => (Carbon::now('Asia/Riyadh')),
        'req_id'       => $req_id,
        'content'      => 'من قبل مدير النظام',
    ]);
}

});
*/
