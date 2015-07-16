<?php namespace App\Models;

use App\Models\Ardent;

/**
 * A model representing an alert subscription.
 *
 * @author garrettshevach@gmail.com
 *
 */
class AlertSubscription extends Ardent {
	/**
	 * List of validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
			'alert' => 'required|exists:Alerts,id',
			'user' => 'required|exists:Users,id',
			'email' => 'required|boolean',
			'text' => 'required|boolean'
	);
	
	/**
	 * Override of the static find method.
	 */
	public static function getSubscription($alert, $user) {
		return self::where('alert', '=', $alert)->where('user', '=', $user)->first();
	}
}