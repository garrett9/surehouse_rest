<?php

namespace App\Http\Controllers;

use DB;
use Input;
use Illuminate\Http\Request;
use App\Models\Alert;

/**
 * A controller for adding, editing, getting, and deleting alert subscriptions.
 *
 * @author garrettshevach@gmail.com
 *
 */
class AlertSubscriptionsController extends Controller {
	
	/**
	 * Return all alert subscriptions for the current user with a left join of all of the alerts in the system.
	 * GET /Alerts/Subscription
	 *
	 * @param Request $request        	
	 * @return Response
	 */
	public function all(Request $request) {
		$user = $request->input ( 'user' );
		$subscriptions = DB::table ( 'alerts' )->leftJoin ( 'alert_subscriptions', function ($join) use($user) {
			$join->on ( 'alert_subscriptions.id', '=', 'alerts.id' );
			$join->on ( 'alert_subscriptions.user', '=', DB::raw ( $user->id ) );
		} )->where ( 'alerts.active', '=', '1' )->select ( 'alerts.*', DB::raw ( 'IF(alert_subscriptions.id is null, false, true) as subscribed' ) )->get ();
		if ($subscriptions)
			return self::ok ( null, $subscriptions );
		return self::no_content ();
	}
	
	/**
	 * Allows for subscribing to multiple alerts.
	 * POST /Alerts/Subscriptions
	 *
	 * @return Response
	 */
	public function subscribeMultiple(Request $request) {
		$user = $request->input ( 'user' );
		if (Input::has ( 'checked[]' ) && is_array ( Input::get ( 'checked[]' ) )) {
			DB::transaction ( function () use($user) {
				DB::table ( 'alert_subscriptions' )->where ( 'user', '=', $user->id )->delete ();
				
				$today = date ( 'Y-m-d H:i:s' );
				$subs = [ ];
				foreach ( Input::get ( 'checked[]' ) as $alert )
					array_push ( $subs, [ 
							'id' => $alert,
							'user' => $user->id,
							'email' => true,
							'alerted' => false,
							'created_at' => $today,
							'updated_at' => $today 
					] );
				
				DB::table ( 'alert_subscriptions' )->insert ( $subs );
			} );
		}
		return self::ok ( 'Successfully saved your changes.' );
	}
	
	/**
	 * Allows a user to subscribe to a given alert.
	 * POST /Alerts/Subscriptions/Subscribe/{id}
	 *
	 * @return Response
	 */
	public function subscribe($id) {
		// If the subscription alert exists
		if ($sub = AlertSubscription::getSubscription ( $id, Auth::user ()->id )) {
			$sub->email = boolval ( Input::get ( 'email' ) );
			$sub->text = boolval ( Input::get ( 'text' ) );
			if ($sub->save ())
				return self::ok ( 'Successfully modified subscription.' );
			else
				return self::internal_server_error ( 'Failed to modify your subscription.' );
		} 		// The subscription does not exist
		else {
			$sub = new AlertSubscription ();
			$sub->alert = $id;
			$sub->user = Auth::user ()->id;
			$sub->email = boolval ( Input::get ( 'email' ) );
			$sub->text = boolval ( Input::get ( 'text' ) );
			if ($sub->save ())
				return self::ok ( 'Successfully subscribed for to the given alert.' );
			else
				return self::internal_server_error ( 'Failed to subscribe to the given alert.' );
		}
	}
	
	/**
	 * Allows a user to de-subscribe from a given alert.
	 * POST /Alerts/Subscriptions/Unsubscribe/{id}
	 *
	 * @return Response
	 */
	public function unsubscribe($id) {
		// If only the subscription was found for the current user
		if ($sub = AlertSubscription::getSubscription ( $id, Auth::user ()->id ))
			$sub->delete ();
		return self::ok ( 'Successfully unsubscribed from the selected alert.' );
	}
}