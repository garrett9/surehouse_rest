<?php namespace App\Http\Controllers;

use JWTAuth;
use DB;
use Input;
use App\Models\Alert;

/**
 * A controller for adding, editing, getting, and deleting alerts.
 * 
 * @author garrettshevach@gmail.com
 *
 */
class AlertsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /Alerts
	 *
	 * @return Response
	 */
	public function all()
	{
		//Get all users and pass them to the view
		$alerts = DB::table('alerts')->join('sensors', 'sensors.id', '=', 'alerts.sensor')
									 ->select('alerts.name', 'sensors.name as sensor', 'alerts.id', 'alerts.operation', 'alerts.value', 'alerts.timespan', 'alerts.active')
									 ->get();
		return self::ok(null, $alerts);
	}
	
	/**
	 * Displays all activated alerts.
	 * GET /Alerts
	 * 
	 * @return Response
	 */
	public function activated() {
	    $alerts = DB::table('alerts')->join('sensors', 'sensors.id', '=', 'alerts.sensor')
	                                 ->select('alerts.name', 'sensors.display_name as sensor_name', 'sensors.units as sensor_units', 'alerts.operation', 'alerts.value', 'alerts.timespan', 'alerts.resilient_trigger', 'alerts.updated_at')
	                                 ->where('alerts.activated', '=', true)
	                                 ->where('alerts.active', '=', true)
	                                 ->get();
	    return self::ok(null, $alerts);
	}
	
	/**
	 * Display an existing resource.
	 * GET /Alerts/{id}
	 * 
	 * @return Response
	 */
	public function show($id) {
		if($alert = Alert::find($id))
			return self::ok(null, $alert);
		return self::not_found();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /Alerts/Create
	 *
	 * @return Response
	 */
	public function store()
	{
		/* Validation for checking if the alert name already exists is done in the Alert model validation rules. */
		//Store all incoming values to create the new Alert
		$alert = new Alert();
		$alert->name = Input::get('name');
		$alert->sensor = Input::get('sensor');
		$alert->operation = Input::get('operation');
		$alert->value = Input::get('value');
		$alert->timespan = Input::get('timespan');
		$alert->resilient_trigger = (Input::get('resilient_trigger')) ? true : false;
		$alert->active = true;
		$alert->activated = false;
		$alert->user = JWTAuth::parseToken()->authenticate()->id;
		
		//Attempt to save the alert. If failed, pass the validation errors to the view.
		if(!$alert->save())
			return self::bad_request('Failed to create the alert!', $alert->errors());
		//Saving the alert was successful, so move on.
		else
			return self::ok('Successfully created the alert.', $alert);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /Alerts/Edit/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//The first thing to do is see if the alert exists.
		if($alert = Alert::find($id)) {
			$alert->name = Input::get('name');
			$alert->sensor = Input::get('sensor');
			$alert->operation = Input::get('operation');
			$alert->value = Input::get('value');
			$alert->timespan = Input::get('timespan');
			$alert->resilient_trigger = (Input::get('resilient_trigger')) ? true : false;
			//Attempt to save the model's data.
			if(!$alert->updateUniques())
				return self::bad_request(null, $alert->errors());
			else
				return self::ok('Successfully saved your changes.');
		}
		//The alert is not allowed to do anything otherwise, so they are unauthorized
		else
			self::not_found();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /Alerts/Delete/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($alert = Alert::find($id)) {
			$alert->delete();
			return self::no_content('Successfully deleted the alert.');
		}
		else
			self::not_found();
	}
}