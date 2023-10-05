<?php


//---------------Property Agent---------------------
route::group(['prefix' => 'propertyAgent', 'as' => 'property.agent.', 'middleware' => ['auth', 'propertyagent']], function () {
    //Home
    Route::get('/home', 'PropertyAgentController@homePage')->name('home');
});

//---------------Propertor---------------------
route::group(['prefix' => 'propertor', 'as' => 'propertor.', 'middleware' => ['auth', 'propertor']], function () {
    //Home
    Route::get('/home', 'PropertorController@homePage')->name('home');
});


//---------------Properties---------------------
route::group(['prefix' => 'property', 'as' => 'property.', 'middleware' => ['auth','CollaboratorOrPropertor']], function () {

    // Properties
    Route::get('/list', 'PropertyController@index')->name('list');
    Route::get('/create', 'PropertyController@create')->name('create');
    Route::post('/store', 'PropertyController@store')->name('store');
    Route::get('/show/{id}', 'PropertyController@show')->name('show');
    Route::get('/edit/{id}', 'PropertyController@edit')->name('edit');
    Route::post('/update/{id}', 'PropertyController@update')->name('update');
    Route::delete('/destroy/{id}', 'PropertyController@destroy')->name('destroy');
    Route::post('/update-status', 'PropertyController@status')->name('status');
    Route::delete('/archpropertyarr', 'PropertyController@archPropertyArr')->name('archPropertyArray');

    Route::get('/get-cities', 'PropertyController@cities');
    Route::get('/get-district', 'PropertyController@districts');

});
//---------------Properties Requests---------------------
Route::group(['prefix' => 'proper', 'as' => 'proper.', 'middleware' => ['auth','CollaboratorOrPropertor']], function () {

    Route::resource('phones', 'PhonesController');
    Route::post('/phones-updates', 'PhonesController@MobilesUpdate');
    Route::post('/phones-check', 'PhonesController@checkMobileEmployee')->name('checkMobile');
});
Route::group(['prefix' => 'requests', 'as' => 'proper.', 'middleware' => ['auth','CollaboratorOrPropertor']], function () {

    Route::get('/all', 'RequestsController@requests')->name('requests');
    Route::get('/property-request/{id}', 'RequestsController@Propertyrequest')->name('property.request');
    Route::get('/archives', 'RequestsController@archives')->name('archives');
    Route::get('/actives', 'RequestsController@actives')->name('actives');
    Route::get('/collaborator/datatable/{status?}', 'RequestsController@datatable')->name('requests.datatable');

    Route::get('/add-customer-with-request', 'RequestsController@addCustomer')->name('customer.create');
    Route::post('/add-customer-with-request', 'RequestsController@storeCustomer')->name('customer.store');

    Route::get('/funding-request/{id}', 'RequestsController@show')->name('request.show');

    Route::post('/upload-file', 'RequestsController@uploadFile')->name('request.uploadFile');
    Route::get('/open-file/{id}', 'RequestsController@openFile')->name('open.file');
    Route::get('/download-file/{id}', 'RequestsController@downloadFile')->name('download.file');
    Route::post('/delete-file', 'RequestsController@deleteFile')->name('delete.file');
    Route::post('/update-funding', 'RequestsController@updatefunding')->name('request.update');

});


//---------------Properties Requests---------------------
Route::group(['prefix' => 'properties-requests', 'as' => 'propertiesRequests.', 'middleware' => ['auth','HasPropertyRequests']], function () {

    // Properties Requests
    //Route::get('/list', 'PropertyRequestController@index')->name('list');
    //Route::post('/update-property-request', 'PropertyRequestController@update')->name('update');
    //Route::post('/convert-propertyRequest-to-tamweelRequest', 'PropertyRequestController@convertPropertyRequestToTamweelRequest')->name('convertRequest');
});

Route::post('fetch-cities', 'PropertyController@cities')->name('cities.fetch');
Route::post('fetch-districts', 'PropertyController@districts')->name('districts.fetch');
Route::post('get-properties', 'IndexController@getProperities')->name('get.properties');

Route::group([ 'prefix' => 'properties','middleware' => ['auth:customer']], function () {
    Route::get('/', 'IndexController@properties')->name('allProperties');
    Route::post('/filter', 'IndexController@filterProperties')->name('filterProperties');
    Route::get('/details/{id}', 'IndexController@property')->name('propertyDetails');
    Route::post('/request', 'IndexController@requestProperty')->name('requestProperty');
    Route::get('/suggestions/{city?}/{region?}', 'IndexController@suggestions')->name('propertySuggestions');


    Route::get('update-customers-data/{start_id}/{end_id}', 'IndexController@update_customers_date')->name('update.customers.data');


});



