<?php
/**
 * All of the URL routes defined for the application.
 * 
 * @author garrettshevach@gmail.com
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
 * | Settings Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with saving settings for the application.
 * |
 */
// Set the status of the dashboard (sustainable/resileint) by providing a boolean (true = resileint, false = sustainable)
Route::get('Settings/ResilientMode/{boolean}', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SettingsController@setResilientMode'
]);

// Set the address of the home
Route::post('Settings/Address', [
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SettingsController@setAddress'
]);

// Get the settings of the application
Route::get('Settings', [
    'uses' => 'SettingsController@getSettings'
]);

/*
 * |--------------------------------------------------------------------------
 * | Gateways Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for dealing with Alerts.
 * |
 */
// Get all gateways in the system
Route::get('Gateways', [
    'as' => 'gateways.all',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@all'
]);

// Ping a gateway device to see if it is active
Route::post('Gateways/Ping', [
    'as' => 'gateways.ping',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@ping'
]);

// Create a gateway
Route::post('Gateways/Create', [
    'as' => 'gateways.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@store'
]);

// Get a gateway
Route::get('Gateways/{id}', [
    'as' => 'gateways.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@show'
]);

// Modify a gateway
Route::put('Gateways/Edit/{id}', [
    'as' => 'gateways.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'GatewaysController@update'
]);

// Delete a gateway
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
// Get all of the sensors
Route::get('Sensors', [
    'as' => 'sensors.all', /*'middleware' => 'auth', */'uses' => 'SensorsController@all'
]);

// Create a sensor
Route::post('Sensors/Create', [
    'as' => 'sensors.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@store'
]);

// Get a sensor
Route::get('Sensors/{id}', [
    'as' => 'sensors.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@show'
]);

// Modify a sensor
Route::put('Sensors/Edit/{id}', [
    'as' => 'sensors.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'SensorsController@update'
]);

// Delete a sensor
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
// Get all of the alerts in the system
Route::get('Alerts', [
    'as' => 'alerts.all',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@all'
]);

// Get all activated routes
Route::get('Alerts/Activated', [
    'as' => 'alerts.activated',
    'uses' => 'AlertsController@activated' 
]);

// Create an alert
Route::post('Alerts/Create', [
    'as' => 'alerts.create',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@store'
]);

// Get an alert
Route::get('Alerts/{id}', [
    'as' => 'alerts.get',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@show'
]);

// Modify an alert
Route::put('Alerts/Edit/{id}', [
    'as' => 'alerts.edit',
    'middleware' => 'auth',
    'permissions' => 'super admin',
    'uses' => 'AlertsController@update'
]);

// Delete an alert
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
 * | NOTE: Alert subscriptions were thrown out. With a lack of internet connection, they really weren't worth the time to implement all the way.
 * |       Additionally, since you don't need to login anymore to see data, they didn't really make sense anymore.
 * |       Instead, all active alerts will be displayed to everyone.
 * |
 */
/* // Get all alert subscriptions for the current user
Route::get('Alerts/Subscriptions/All', [
    'as' => 'alerts.subscriptions',
    'middleware' => 'auth',
    'uses' => 'AlertSubscriptionsController@all'
]);

// Subscrive to an alert for the current user
Route::post('Alerts/Subscriptions/Subscribe', [
    'as' => 'alerts.subscriptions.subscribe',
    'middleware' => 'auth',
    'uses' => 'AlertSubscriptionsController@subscribeMultiple'
]); */

/*
 * |--------------------------------------------------------------------------
 * | Reporting Routes
 * |--------------------------------------------------------------------------
 * |
 * | Routes for querying the database for sensor information.
 * |
 */
// Perform a custom query
Route::any('Reporting/Custom', [
    'as' => 'reporting.custom', /*'middleware' => 'auth',*/ 'uses' => 'ReportingController@custom'
]);

// Perform a most recent query
Route::any('Reporting/Recent', [
    'as' => 'reporting.most_recent',
    'uses' => 'ReportingController@mostRecent'
]);