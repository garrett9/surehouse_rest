<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing a sensor log.
 *
 * @author garrettshevach@gmail.com
 *
 */
class SensorLog extends Ardent {
	
    /**
     * The validation rules for this model.
     * 
     * @var array
     */
	public static $rules = [
			'id' => 'required|integer|exists:sensors,id',
			'timestamp' => 'required|date',
			'value' => 'required|integer'
	];
	
}