<?php
/**
 * @Author: Ahmed Fayez
 **/

use App\Http\Controllers\V2\ExternalCustomerController;
use App\Http\Controllers\V2\WasataRequestesController;
use App\Http\Controllers\V2\WasataInternalRequestesController;
use App\Models\WasataRequestes;

Route::group(['middleware' => ['auth', 'logout']], function () {

    Route::group(['prefix' => 'ExternalCustomer', 'as' => 'ExternalCustomer.'], function () {
        Route::get('', [ExternalCustomerController::class, 'index'])->name('index')->middleware(['bank_delegate']);
        Route::get('Datatable', [ExternalCustomerController::class, 'indexDatatable'])->name('indexDatatable')->middleware(['bank_delegate']);

        Route::group(['prefix' => '{ExternalCustomer}'], function () {
            Route::get('Show', [ExternalCustomerController::class, 'show'])->name('show')->middleware(['bank_delegate']);
            Route::put('update', [ExternalCustomerController::class, 'update'])->name('update')->middleware(['bank_delegate']);
            Route::post('/updatefunding', [ExternalCustomerController::class, 'updatefunding'])->name('updateFunding');

        });

        Route::group(['prefix' => 'Archive', 'as' => 'Archive.'], function () {
            Route::get('', [ExternalCustomerController::class, 'archivedIndex'])->name('index')->middleware(['bank_delegate']);
            Route::get('Ids', [ExternalCustomerController::class, 'ids'])->name('ids')->middleware(['bank_delegate']);
            Route::get('restoreIds', [ExternalCustomerController::class, 'restoreIds'])->name('restoreIds')->middleware(['bank_delegate']);

            Route::group(['prefix' => '{ExternalCustomer}'], function () {
                Route::get('Set', [ExternalCustomerController::class, 'archive'])->name('archive')->middleware(['bank_delegate']);
                Route::get('Restore', [ExternalCustomerController::class, 'restore'])->name('restore')->middleware(['bank_delegate']);
            });
        });

        Route::get('wasata-requestes',[WasataRequestesController::class,'index'])->name('requestes-of-wasata');
        Route::get('wasata-requestes-datatable',[WasataRequestesController::class,'indexDatatable'])->name('requestes-of-wasata-indexDatatable');

        Route::get('wasata-internal-requestes',[WasataInternalRequestesController::class,'index'])->name('requestes-of-wasata-internal');
        Route::get('wasata-internal-requestes-datatable',[WasataInternalRequestesController::class,'indexDatatable'])->name('requestes-of-wasata-internal-indexDatatable');
        Route::post('/updatecomm', [WasataInternalRequestesController::class,'updateComm'])->name('updatecomm');
    });

    Route::group(['prefix' => 'BankDelegate', 'as' => 'BankDelegate.', 'middleware' => ['bank_delegate']], function () {
        Route::group(['prefix' => 'requests'], function () {

            Route::get('/all', 'V2\BankRequestsController@requests')->name('requests');
            Route::get('/sended/{type?}', 'V2\BankRequestsController@sended')->name('sended');
            Route::get('/property-request/{id}', 'V2\BankRequestsController@Propertyrequest')->name('property.request');
            Route::get('/archives', 'V2\BankRequestsController@archives')->name('archives');
            Route::get('/actives', 'V2\BankRequestsController@actives')->name('actives');
            Route::get('/datatable/{status?}', 'V2\BankRequestsController@datatable')->name('requests.datatable');

            Route::get('/add-customer-with-request', 'V2\BankRequestsController@addCustomer')->name('customer.create');
            Route::post('/add-customer-with-request', 'V2\BankRequestsController@storeCustomer')->name('customer.store');

            Route::get('/add-external-customer-with-request', 'V2\BankRequestsController@addExternalCustomer')->name('external.customer.create');
            Route::post('/add-external-customer-with-request', 'V2\BankRequestsController@storeExternalCustomer')->name('external.customer.store');

            Route::get('/funding-request/{id}', 'V2\BankRequestsController@show')->name('request.show');
            Route::get('/funding-request-not-archive-internal/{id}', 'V2\BankRequestsController@internalNotArchive')->name('request.internal-not-archive');
            Route::get('/funding-request-archive-internal/{id}', 'V2\BankRequestsController@internalArchive')->name('request.internal-archive');

            Route::post('/upload-file', 'V2\BankRequestsController@uploadFile')->name('request.uploadFile');
            Route::get('/open-file/{id}', 'V2\BankRequestsController@openFile')->name('open.file');
            Route::get('/download-file/{id}', 'V2\BankRequestsController@downloadFile')->name('download.file');
            Route::post('/delete-file', 'V2\BankRequestsController@deleteFile')->name('delete.file');
            Route::post('/update-funding', 'V2\BankRequestsController@updatefunding')->name('request.update');

        });
    });

});
