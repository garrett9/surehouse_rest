<?php namespace App\Http\Controllers;

use Hash;
use Input;
use App\Models\Gateway;

/**
 * A controller for adding, editing, getting, and deleting gateways.
 *
 * @author garrettshevach@gmail.com
 *
 */
class GatewaysController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /Gateways
	 *
	 * @return Response
	 */
	public function all()
	{
		$gateways = Gateway::all();
		if(!$gateways->isEmpty())
			return self::ok(null, $gateways);
		return self::no_content();
	}
	
	/**
	 * Display an existing resource.
	 * GET /Gateways/{id}
	 * 
	 * @return Response
	 */
	public function show($id) {
		if($gateway = Gateway::find($id))
			return self::ok(null, $gateway);
		return self::not_found();
	}
	
	/**
	 * Ping a gateway's device based on a provided IP and Port number
	 * POST /Gateways/Ping
	 * 
	 * @return Response
	 */
	public function ping() {
		if(Input::has('IP') && Input::has('port')) {
			$worked = false;
			if($fp = fsockopen(Input::get('IP'), Input::get('port'), $errCode, $errStr, 2))
				$worked = true;
				
			fclose($fp);
			if($worked)
				return self::ok('Connection to the provided host has been established.');
		}
		return self::bad_request('The provided host could not be resolved!');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /Gateways/Create
	 *
	 * @return Response
	 */
	public function store()
	{
		$gateway = new Gateway();
		$gateway->name = Input::get('name');
		$gateway->IP = Input::get('IP');
		$gateway->port = Input::get('port');
		$gateway->type = Input::get('type');
		
		if(!$gateway->save())
			return self::bad_request('Failed to create the Gateway!', $gateway->errors());
		else
			return self::created('Successfully created the gateway.', $gateway);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /Gateways/Edit/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($gateway = Gateway::find($id)) {
			$gateway->name = Input::get('name');
			$gateway->IP = Input::get('IP');
			$gateway->port = Input::get('port');
			$gateway->type = Input::get('type');
			
			if(!$gateway->updateUniques())
				return self::bad_request('Failed to save your changes!', $gateway->errors());
			else
				return self::ok('Successfully saved your changes.');
		}
		else
			return self::not_found();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /Gateways/Delete/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($gateway = Gateway::find($id)) {
			try {
				$gateway->delete();
			} catch(\Exception $e) {
				//This error is a foreign key integrity constraint
				if($e->errorInfo[0] == '23000')
					return self::conflict('Could not delete this gateway because there is other data that relies on it! You must delete this data before deleting the gateway.');
				else //Don't know the error at this point, so just return a generic error message.
					return self::internal_server_error();
			}
			return self::no_content('Successfully deleted the gateway.');
		}
		else
			return self::not_found();
	}
}