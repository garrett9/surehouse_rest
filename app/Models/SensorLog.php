<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing a sensor log.
 *
 * @author garrettshevach@gmail.com
 *
 */
class SensorLog extends Ardent {
	
	public $rules = [
			'id' => 'required|integer|exists:sensors,id',
			'timestamp' => 'required|date',
			'value' => 'required|integer'
	];
	
}