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
    
    Route::get('/', 'HomeController@getRoot');

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


});
