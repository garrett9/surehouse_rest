<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
 |--------------------------------------------------------------------------
 | Users Routes
 |--------------------------------------------------------------------------
 |
 | Routes for dealing with Users, including logging in and password resets.
 |
 */
Route::post('Users/Login', ['as' => 'users.login', 'uses' => 'UsersController@login']);
Route::get('Users/Refresh', ['as' => 'users.refresh', 'middleware' => 'auth', 'uses' => 'UsersController@refresh']);
Route::get('Users', ['as' => 'users.all', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'UsersController@all']);
Route::post('Users/Create', ['as' => 'users.create', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'UsersController@store']);
Route::get('Users/{id}', ['as' => 'users.get', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'UsersController@show']);
Route::get('Users/Manage/Account', ['as' => 'users.manage.get', 'middleware' => 'auth', 'uses' => 'UsersController@self']);
Route::put('Users/Manage/Account', ['as' => 'users.manage.post', 'middleware' => 'auth', 'uses' => 'UsersController@manage']);
Route::put('Users/Change/Password', ['as' => 'users.change.password', 'middleware' => 'auth', 'uses' => 'UsersController@changePassword']);
Route::put('Users/{id}', ['as' => 'users.edit', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'UsersController@update']);
Route::delete('Users/Delete/{id}', ['as' => 'users.delete', 'middleware' => 'auth', 'permission' => 'super admin', 'uses' => 'UsersController@destroy']);

Route::post('Users/SendPasswordReset', ['as' => 'users.send_password_reset', 'uses' => 'UsersController@sendPasswordReset']);
Route::post('Users/ResetPassword/{token}', ['as' => 'users.password_reset', 'uses' => 'UsersController@resetPassword']);
/*
 |--------------------------------------------------------------------------
 | Gateways Routes
 |--------------------------------------------------------------------------
 |
 | Routes for dealing with Alerts.
 |
 */
Route::get('Gateways', ['as' => 'gateways.all', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@all']);
Route::post('Gateways/Ping', ['as' => 'gateways.ping', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@ping']);
Route::post('Gateways/Create', ['as' => 'gateways.create', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@store']);
Route::get('Gateways/{id}', ['as' => 'gateways.get', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@show']);
Route::put('Gateways/Edit/{id}', ['as' => 'gateways.edit', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@update']);
Route::delete('Gateways/Delete/{id}', ['as' => 'gateways.delete', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'GatewaysController@destroy']);

/*
 |--------------------------------------------------------------------------
 | Sensors Routes
 |--------------------------------------------------------------------------
 |
 | Routes for dealing with Alerts.
 |
 */
Route::get('Sensors', ['as' => 'sensors.all', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'SensorsController@all']);
Route::post('Sensors/Create', ['as' => 'sensors.create', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'SensorsController@store']);
Route::get('Sensors/{id}', ['as' => 'sensors.get', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'SensorsController@show']);
Route::put('Sensors/Edit/{id}', ['as' => 'sensors.edit', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'SensorsController@update']);
Route::delete('Sensors/Delete/{id}', ['as' => 'sensors.delete', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'SensorsController@destroy']);

/*
 |--------------------------------------------------------------------------
 | Alerts Routes
 |--------------------------------------------------------------------------
 |
 | Routes for dealing with Alerts.
 |
 */
Route::get('Alerts', ['as' => 'alerts.all', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'AlertsController@all']);
Route::post('Alerts/Create', ['as' => 'alerts.create', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'AlertsController@store']);
Route::get('Alerts/{id}', ['as' => 'alerts.get', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'AlertsController@show']);
Route::put('Alerts/Edit/{id}', ['as' => 'alerts.edit', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'AlertsController@update']);
Route::delete('Alerts/Delete/{id}', ['as' => 'alerts.delete', 'middleware' => 'auth', 'permissions' => 'super admin', 'uses' => 'AlertsController@destroy']);

/*
 |--------------------------------------------------------------------------
 | Alert Subscription Routes
 |--------------------------------------------------------------------------
 |
 | Routes for dealing with Alert Subscriptions.
 |
 */
Route::get('Alerts/Subscriptions/All', ['as' => 'alerts.subscriptions', 'middleware' => 'auth', 'uses' => 'AlertSubscriptionsController@all']);
Route::post('Alerts/Subscriptions/Subscribe', ['as' => 'alerts.subscriptions.subscribe', 'middleware' => 'auth', 'uses' => 'AlertSubscriptionsController@subscribeMultiple']);

/*
 |--------------------------------------------------------------------------
 | Reporting Routes
 |--------------------------------------------------------------------------
 |
 | Routes for querying the database for sensor information.
 |
 */
Route::any('Reporting/Custom', ['as' => 'reporting.custom', 'middleware' => 'auth', 'uses' => 'ReportingController@custom']);
Route::any('Reporting/Recent', ['as' => 'reporting.most_recent', 'uses' => 'ReportingController@mostRecent']);
Route::get('Reporting/Query/{id}', ['as' => 'reporting.single', 'uses' => 'ReportingController@single']);