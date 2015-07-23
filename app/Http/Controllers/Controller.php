<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * A controller used for returning specific HTTP responses in a JSON format.
 *
 * @author garrettshevach@gmail.com
 *
 */
abstract class Controller extends BaseController {

	/*
	 |--------------------------------------------------------------------------
	 | Success Codes (200s)
	 |--------------------------------------------------------------------------
	 |
	 | All of the following functions indicates the action requested by the client
	 | was received, understood, accepted, and processed successfully.
	 |
	 */
	/**
	 * Return a 200 Response.
	 * @param unknown $payload The optional payload to send back with the response.
	 * @param string $payload
	 */
	public static function ok($msg=null, $payload=null) {
		$msg = (is_string($msg) ? $msg : 'OK!');
		return self::response(true, 200, $msg, $payload);
	}
	
	/**
	 * Return a 202 Response.
	 * 
	 * @param string $msg
	 * @param string $payload
	 */
	public static function created($msg=null, $payload=null) {
		$msg = (is_string($msg)) ? $msg : 'Created!';
		return self::response(true, 202, $msg, $payload);
	}
	
	/**
	 * Return a 204 Response.
	 * 
	 * @param string $msg
	 */
	public static function no_content($msg=null) {
		return self::response(true, 204, 'No Content!');
	}
	
	/*
	 |--------------------------------------------------------------------------
	 | Client Errors (400s)
	 |--------------------------------------------------------------------------
	 |
	 | All of the following functions indicates the action requested by the client
	 | encountered an error due to a client error.
	 |
	 */
	/**
	 * Return a 400 JSON response.
	 * 
	 * @param string $msg
	 * @param string $payload
	 */
	public static function bad_request($msg=null, $payload=null) {
		$msg = (is_string($msg)) ? $msg : 'Bad Request!';
		return self::response(false, 400, $msg, $payload);
	}
	
	/**
	 * Returns a 401 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function unauthorized($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Unauthorized!';
		$headers = ['WWW-Authenticate' => route('users.login')];
		return self::response(false, 401, $msg, null, $headers);
	}
	
	/**
	 * Return a 403 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function forbidden($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Forbidden!';
		return self::response(false, 403, $msg);
	}
	
	/**
	 * Return a 404 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function not_found($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Not Found!';
		return self::response(false, 404, $msg);
	}
	
	/**
	 * Return a 405 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function method_not_allowed($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Method not allowed!';
		return self::response(false, 405, $msg);
	}
	
	/**
	 * Return a 409 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function conflict($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Conflict!';
		return self::response(false, 409, $msg);
	}
	
	/**
	 * Return a 419 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function authentication_timeout($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Authentication Timeout';
		return self::response(false, 419, $msg);
	}
	
	/*
	 |--------------------------------------------------------------------------
	 | Server Errors (500s)
	 |--------------------------------------------------------------------------
	 |
	 | All of the following functions indicates the action requested by the client
	 | encountered an error due to a server error.
	 |
	 */
	/**
	 * Return a 500 JSON response.
	 * 
	 * @param string $msg
	 */
	public static function internal_server_error($msg=null) {
		$msg = (is_string($msg)) ? $msg : 'Internal Server Error!';
		return self::response(false, 500, $msg);
	}
	
	
	/*
	 |--------------------------------------------------------------------------
	 | Custom Response builder.
	 |--------------------------------------------------------------------------
	 */
	/**
	 * Return a JSON response given a result, status code, and optional response payload.
	 * @param mixed $result The result of the request (true = success; false = failure)
	 * @param mixed $status The HTTP response code of the request.
	 * @param string $message A message to send with the response.
	 * @param mixed $payload The optional data to send with the response.
	 * @param array $headers The array of headers to add to the response.
	 */
	public static function response($result, $status, $message=null, $payload=null, array $headers=null) {
		$result = ($result) ? 'success' : 'error';
		$result = ['result' => $result, 'status' => $status];
		if(is_string($message))
			$result['message'] = $message;
		if($payload || is_array($payload))
			$result['payload'] = $payload;
		
		$response = response()->json($result, $status);
		if($headers !== null) {
			foreach($headers as $name=>$value) {
				if(is_string($name) && is_string($value))
					$response->header($name, $value);
			}
		}
		return $response;
	}
}
