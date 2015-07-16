<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing an sensor.
 *
 * @author garrettshevach@gmail.com
 *
 */
class Sensor extends Ardent {
	
	/**
	 * List of validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
			'name' => 'required|unique:sensors,name',
			'display_name' => 'required|unique:sensors,display_name',
			'units' => 'required',
			'gateway' => 'required|exists:gateways,id',
			'type' => 'alphaNum'
	);
	
	protected $fillable = array('name', 'units', 'recorder', 'type');
	
	/**
	 * Returns the Gateway associated to the Data point.
	 */
	public function gateway() {
		return $this->belongsTo('Gateway', 'gateway', 'id');
	}
	
	/**
	 * A single Datapoint can have many datapoint Logs.
	 */
	public function logs() {
		return $this->hasMany('DatapointLog', 'id');
	}
	
	/**
	 * A single Datapoint can have many Resilient Triggers attached to it.
	 */
	public function alerts() {
		return $this->hasMany('Alert', 'datapoint');
	}
}