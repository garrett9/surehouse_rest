<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing an alert.
 *
 * @author garrettshevach@gmail.com
 *
 */
class Alert extends Ardent {
	
	/**
	 * The mappings from the operation pulled from the database to what the user reads.
	 *
	 * @var array
	 */
	public static $operations = array(
			'<' => 'less than',
			'>' => 'greater than',
			'<=' => 'less than or equal to',
			'>=' => 'greater than or equal to'
	);
	
	/**
	 * List of validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
			'name' => 'required|alpha_num|min:1|max:25|unique:alerts,name',
			'sensor' => 'required|exists:sensors,id',
			'operation' => 'required|in:>,<,<=,>=',
			'value' => 'required|integer',
			'timespan' => 'required|integer',
			'active' => 'required|boolean'
	);
	
	/**
	 * Returns the subscriptions related to the alert.
	 * 
	 * @return Collection
	 */
	public function alertSubscriptions() {
		return $this->hasMany('AlertSubscription', 'alert')
		->join('Users', 'Users.id', '=', 'AlertSubscriptions.user')
		->get();
	}
	
	/**
	 * Convert the value to an integer when retrieving it.
	 *
	 * @param mixed $value
	 * @return number
	 */
	public function getValueAttribute($value) {
		return (int)$value;
	}
	
	/**
	 * Convert the timespan to an integer when retrieving it.
	 *
	 * @param mixed $value
	 * @return number
	 */
	public function getTimespanAttribute($value) {
		return (int)$value;
	}
}