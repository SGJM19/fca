<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get('/user','UserController@auth');
Route::post('/test','UserController@test');
Route::get('/get_stores', 'FcaController@get_stores');


//fca
Route::post('/addYear', 'FcaController@addYear');
Route::post('/get_fca_table', 'FcaController@get_fca_table');
Route::post('/uploadFcaFile','FcaController@uploadFcaFile');
Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);
Route::post('/removeFile','FcaController@removeFile');
Route::post('/exportFca', 'FcaController@exportFca');

/*FOR UPADTING*/
Route::post('/saveOtherDetails','FcaController@saveOtherDetails');



/*settings*/
Route::post('/getarls','FcaSettings@getArls');
Route::post('/set_arl_settings', 'FcaSettings@set_arl_settings');
Route::post('/getCurrentMonths', 'FcaSettings@getCurrentMonths');
Route::post('/saveExpSettings', 'FcaSettings@saveExpSettings');
Route::post('/get_exp_date', 'FcaSettings@getCurrentExpSettings');
Route::post('/get_users', 'FcaSettings@get_users');
Route::post('/store_email_to', 'FcaSettings@store_email_to');
Route::post('/saveAccessSettings', 'FcaSettings@saveAndUpdateAccessSettings');


Route::post('/store_threshold_percent','FcaSettings@store_threshold_percent');
Route::post('/get_store_threshold_percent','FcaSettings@get_store_threshold_percent');

// FIles
Route::post('/get_files_uploaded','FcaController@get_files_uploaded');
Route::get('/downloadFile/{fname}/{rfname}', 'FcaController@downloadFile');
Route::post('/readComments', 'FcaDashboardController@readComments');


/*FCA DASHBOARD*/
Route::post('/getarl_dashboard', 'FcaDashboardController@getarl_dashboard');
Route::post('/get_arls', 'FcaDashboardController@get_arls');
Route::post('/getReportDetails', 'FcaDashboardController@getReportDetails');
Route::post('/submit_pending', 'FcaDashboardController@submit_pending');
Route::post('/send_feedback','FcaDashboardController@send_feedback');
Route::post('/getFeedBacks','FcaDashboardController@getFeedBacks');
Route::post('/replyMessage','FcaDashboardController@submitReply');
Route::post('/exportFcaDashboard','FcaDashboardController@exportFcaDashboard');
Route::post('/approveOrNot', 'FcaDashboardController@approveOrNot');


Route::post('/getPreviousScore', 'FcaController@getPreviousScore');
Route::post('/exempt_store', 'ExemptStoreController@getTable');
Route::post('/insert_update', 'ExemptStoreController@insert_update');
Route::post('/getExemptStore','FcaController@getExempted');