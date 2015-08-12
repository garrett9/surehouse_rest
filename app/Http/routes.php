<?php

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 */

/*
 * |--------------------------------------------------------------------------
 * | Users Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Users, including logging in and password resets.
 * |
 */
// Login to the application
Route::post('Users/Login', [
    'as' => 'users.login',
    'uses' => 'UsersController@login'
]);

// Refresh a user's JWT token
Route::get('Users/Refresh', 'UsersController@refresh');

// Get all of the user's in the system
Route::get('Users', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'UsersController@all'
]);

// Create a user
Route::post('Users/Create', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'UsersController@store'
]);

// Get a user
Route::get('Users/{id}', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'UsersController@show'
]);

// Get the information for a user to manage his/her account
Route::get('Users/Manage/Account', [
    'middleware' => 'auth',
    'uses' => 'UsersController@self'
]);

// Edit a user's account
Route::put('Users/Manage/Account', [
    'middleware' => 'auth',
    'uses' => 'UsersController@manage'
]);

// Change a user's password
Route::put('Users/Change/Password', [
    'middleware' => 'auth',
    'uses' => 'UsersController@changePassword'
]);

// Get a user record
Route::put('Users/{id}', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'UsersController@update'
]);

// Delete a user record
Route::delete('Users/Delete/{id}', [
    'middleware' => 'auth',
    'permission' => 'super admin',
    'uses' => 'UsersController@destroy'
]);

// Issue a password reset
Route::post('Users/SendPasswordReset', [
    'uses' => 'UsersController@sendPasswordReset'
]);

// Reset a user's password
Route::post('Users/ResetPassword/{token}', [
    'as' => 'users.password_reset',
    'uses' => 'UsersController@resetPassword'
]);
/*
 * |--------------------------------------------------------------------------
 * | Gateways Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Alerts.
 * |
 */
Route::get('Gateways', [
    'as' => 'gateways.all',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@all'
]);

Route::post('Gateways/Ping', [
    'as' => 'gateways.ping',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@ping'
]);

Route::post('Gateways/Create', [
    'as' => 'gateways.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@store'
]);

Route::get('Gateways/{id}', [
    'as' => 'gateways.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@show'
]);

Route::put('Gateways/Edit/{id}', [
    'as' => 'gateways.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@update'
]);

Route::delete('Gateways/Delete/{id}', [
    'as' => 'gateways.delete',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@destroy'
]);

/*
 * |--------------------------------------------------------------------------
 * | Sensors Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Alerts.
 * |
 */
Route::get('Sensors', [
    'as' => 'sensors.all', /*'middleware' => 'auth', */'uses' => 'SensorsController@all'
]);

Route::post('Sensors/Create', [
    'as' => 'sensors.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@store'
]);

Route::get('Sensors/{id}', [
    'as' => 'sensors.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@show'
]);

Route::put('Sensors/Edit/{id}', [
    'as' => 'sensors.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@update'
]);

Route::delete('Sensors/Delete/{id}', [
    'as' => 'sensors.delete',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@destroy'
]);

/*
 * |--------------------------------------------------------------------------
 * | Alerts Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Alerts.
 * |
 */
Route::get('Alerts', [
    'as' => 'alerts.all',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@all'
]);

Route::post('Alerts/Create', [
    'as' => 'alerts.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@store'
]);

Route::get('Alerts/{id}', [
    'as' => 'alerts.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@show'
]);

Route::put('Alerts/Edit/{id}', [
    'as' => 'alerts.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@update'
]);

Route::delete('Alerts/Delete/{id}', [
    'as' => 'alerts.delete',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@destroy'
]);

/*
 * |--------------------------------------------------------------------------
 * | Alert Subscription Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Alert Subscriptions.
 * |
 */
Route::get('Alerts/Subscriptions/All', [
    'as' => 'alerts.subscriptions',
    'middleware' => 'auth',
    'uses' => 'AlertSubscriptionsController@all'
]);

Route::post('Alerts/Subscriptions/Subscribe', [
    'as' => 'alerts.subscriptions.subscribe',
    'middleware' => 'auth',
    'uses' => 'AlertSubscriptionsController@subscribeMultiple'
]);

/*
 * |--------------------------------------------------------------------------
 * | Reporting Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for querying the database for sensor information.
 * |
 */
Route::any('Reporting/Custom', [
    'as' => 'reporting.custom', /*'middleware' => 'auth',*/ 'uses' => 'ReportingController@custom'
]);

Route::any('Reporting/Recent', [
    'as' => 'reporting.most_recent',
    'uses' => 'ReportingController@mostRecent'
]);

Route::get('Reporting/Query/{id}', [
    'as' => 'reporting.single',
    'uses' => 'ReportingController@single'
]);