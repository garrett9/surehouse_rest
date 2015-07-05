<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing a gateway.
 *
 * @author garrettshevach@gmail.com
 *
 */
class Gateway extends Ardent {
	
	/**
	 * List of validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
			'name' => 'required|unique:gateways,name',
			'IP' => 'required',
			'port' => 'required|integer|min:1|max:65535',
			'type' => 'required|in:eGuage,WEL'
	);
	
	/**
	 * Return the data point associated to this gateway.
	 */
	public function datapoints() {
		return $this->hasMany('Datapoint', 'gateway');
	}
	
	/**
	 * Convert the port to an integer when retrieving it.
	 * 
	 * @param mixed $value
	 * @return number
	 */
	public function getPortAttribute($value) {
		return (int)$value;
	}
}