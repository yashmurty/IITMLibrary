<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    
    // Route::get('/', 'HomeController@getRoot');
    Route::get('/', [
	    'as' => 'root', 'uses' => 'HomeController@getHome'
	]);

    Route::get('home', [
	    'as' => 'home', 'uses' => 'HomeController@getHome'
	]);
    
    // Book Requisition Form 
    Route::get('bookrequisitionform', [
	    'as' => 'bookrequisitionform', 'uses' => 'HomeController@getBookRequisitionForm'
	]);
	Route::post('bookrequisitionform', [
	    'as' => 'bookrequisitionform-post', 'uses' => 'HomeController@postBookRequisitionForm'
	]);

	// Request Status
	Route::get('requeststatus', [
	    'as' => 'requeststatus', 'uses' => 'HomeController@getRequestStatus'
	]);

	/*
	// LAC Module
	*/
	Route::get('lac', [
	    'as' => 'lachome', 'uses' => 'LacController@getHome'
	]);
	// LAC Request Status
	Route::get('lac/requeststatus', [
	    'as' => 'lacrequeststatus', 'uses' => 'LacController@getLacRequestStatus'
	]);
	// LAC Request Status View BRF
	Route::get('lac/requeststatus/brf/{brf_id}', [
	    'as' => 'lacrequeststatus-view-brf', 'uses' => 'LacController@getLacRequestStatusViewBRF'
	]);
	// LAC Request Status Approve BRF
	Route::post('lac/requeststatus/brf', [
	    'as' => 'lacrequeststatus-approve-brf-post', 'uses' => 'LacController@postLacRequestStatusApproveBRF'
	]);

	/*
	// Admin Module //
	*/
	Route::get('admin', [
	    'as' => 'adminhome', 'uses' => 'AdminController@getHome'
	]);
	// Admin Request Status
	Route::get('admin/requeststatus', [
	    'as' => 'adminrequeststatus', 'uses' => 'AdminController@getAdminRequestStatus'
	]);
	// Admin Request Status View BRF
	Route::get('admin/requeststatus/brf/{brf_id}', [
	    'as' => 'adminrequeststatus-view-brf', 'uses' => 'AdminController@getAdminRequestStatusViewBRF'
	]);
	// Admin Request Status Approve BRF
	Route::post('admin/requeststatus/brf', [
	    'as' => 'adminrequeststatus-approve-brf-post', 'uses' => 'AdminController@postAdminRequestStatusApproveBRF'
	]);

});
