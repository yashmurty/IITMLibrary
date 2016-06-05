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

    // Login with ADS
    // There is an ADSController which has been added to .gitignore for
    // Security reasons.
    Route::post('/loginwithads', [
	    'as' => 'login-with-ads', 'uses' => 'ADSController@postLoginwithADS'
	]);

	Route::post('/loginwithstaffads', [
	    'as' => 'login-with-staff-ads', 'uses' => 'ADSController@postLoginwithStaffADS'
	]);

    // Staff ADS Login. Factulty Login is located at Native Login
    Route::get('/stafflogin', [
	    'as' => 'staff-login', 'uses' => 'ADSController@getStaffLogin'
	]);

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

	Route::post('bookrequisitionformisbn', [
	    'as' => 'bookrequisitionformisbn-post', 'uses' => 'HomeController@postBookRequisitionFormISBN'
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
	// Admin Export to Excel
	Route::get('admin/requeststatus/exporttoexcel', [
	    'as' => 'adminrequeststatus-export-excel', 'uses' => 'AdminController@getAdminRequestStatusExportExcel'
	]);

	// Admin LAC Memebers
	Route::get('admin/lacmembers', [
	    'as' => 'admin-lacmembers', 'uses' => 'AdminController@getAdminLACMembers'
	]);
    // Admin LAC Memebers Edit
	Route::get('admin/lacmembers/{iitm_dept_code}/edit', [
	    'as' => 'admin-lacmembers-edit', 'uses' => 'AdminController@getAdminLACMembersEdit'
	]);

	// Admin Staff Memebers
	Route::get('admin/staffmembers', [
	    'as' => 'admin-staffmembers', 'uses' => 'AdminController@getAdminStaffMembers'
	]);

});
