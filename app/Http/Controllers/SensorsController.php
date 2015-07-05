<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Models\Sensor;

/**
 * A controller for adding, editing, getting, and deleting sensors.
 *
 * @author garrettshevach@gmail.com
 *
 */
class SensorsController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 * GET /Sensors
	 *
	 * @return Response
	 */
	public function all()
	{
		$sensors = DB::table('gateways')->join('sensors', 'sensors.gateway', '=', 'gateways.id')->get();
		if($sensors)
			return self::ok(null, $sensors);
		return self::no_content();
	}
	

	/**
	 * Query logs from a set of Sensors.
	 * GET /Sensors/Query?{params}
	 * 
	 * @return Response
	 */
	public function query() {
		return Input::all();
	}
	
	/**
	 * Display an existing resource.
	 * GET /Sensors/{id}
	 * 
	 * @return Response
	 */
	public function show($id) {
		if($sensor = Sensor::find($id))
			return self::ok(null, $sensor);
		return self::not_found();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /Sensors/Create
	 *
	 * @return Response
	 */
	public function store()
	{
		/* Validation for checking if the Sensor name already exists is done in Sensor model validation rules. */
		//Store all incoming values to create the new Sensor
		$sensor = new Sensor();
		$sensor->name = Input::get('name');
		$sensor->display_name = Input::get('display_name');
		$sensor->units = Input::get('units');
		$sensor->gateway = Input::get('gateway');

		//Attempt to save the user. If failed, pass the validation errors to the view.
		if(!$sensor->save())
			return self::bad_request('Failed to create the sensor!', $sensor->errors());
		//Saving the user was successful, so move on.
		else
			return self::ok('Successfully created the Sensor.', $sensor);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /Sensors/Edit/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//Make sure the Sensor exists before moving forward
		if($sensor = sensor::find($id)) {
			/* Validation for checking if the Sensor name already exists is done in Sensor model validation rules. */
			//Store all incoming values to create the new Sensor
			$sensor->name = Input::get('name');
			$sensor->display_name = Input::get('display_name');
			$sensor->units = Input::get('units');
			$sensor->gateway = Input::get('gateway');
			
			//Attempt to save the user. If failed, pass the validation errors to the view.
			if(!$sensor->updateUniques())
				return self::bad_request(null, $sensor->errors());
			//Saving the user was successful, so move on.
			else
				return self::ok('Successfully saved your changes.');
		}
		else
			return self::not_found();
	}

	/**
	 * Remove the specified resource from storage.
	 * POST /Sensors/Delete/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($sensor = Sensor::find($id)) {
			try {
				$sensor->delete();
			} catch(\Exception $e) {
				//This error is a foreign key integrity constraint
				if($e->errorInfo[0] == '23000')
					return self::conflict('Could not delete this sensor because there is other data that relies on it! You must delete this data before deleting the sensor.');
				else //Don't know the error at this point, so just return a generic error message.
					return self::internal_server_error();
			}
			return self::ok('Successfully deleted the Sensor.');
		}
		else
			return self::not_found();
	}
}