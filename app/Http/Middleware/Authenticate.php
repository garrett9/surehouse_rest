<?php namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

/**
 * Middleware for authenticating a JSON web token.
 *
 * @author garrettshevach@gmail.com
 *
 */
class Authenticate {
	
	/**
	 * Grab the permissions from the request
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return Array
	 */
	private function getPermissions($request)
	{
		$actions = $request->route()->getAction();
	
		return (isset($actions['permissions'])) ? $actions['permissions'] : null;
	}
	
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		try {
			$jwt = JWTAuth::parseToken();
			$user = $jwt->authenticate();
		} catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return Controller::authentication_timeout('Expired authentication token!');
		} catch(\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return Controller::unauthorized('Invalid authentication token!');
		}
		
		if(!$user)
			return Controller::unauthorized('Invalid authentication token!');
		
		if($permission = $this->getPermissions($request)) {
			if($user->permission !== 'super admin' && $user->permission !== $permission)
				return Controller::forbidden();
		}

		$request->merge(['user' => $user]);
		$response = $next($request);
		
		//$newToken = $jwt->refresh();
		//$response->header('X-Token', $newToken);
		//$response->header('Access-Control-Expose-Headers', 'X-Token');
		
		return $response;
	}

}
