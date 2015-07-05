<?php namespace App\Models;

use App\Models\Ardent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * A model representing a User.
 *
 * @author garrettshevach@gmail.com
 *
 */
class User extends Ardent implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
	
	/**
	 * List of validation rules.
	 *
	 * @var array
	 */
	public static $rules = array(
			'name' => 'required|alpha_num|min:1|max:25|unique:users,name',
			'password' => 'required|min:7',
			'email' => 'required|email|unique:users',
			'permission' => 'required|in:admin,viewer,owner,super admin',
			'active' => 'required|boolean'
	);
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'active', 'created_at', 'updated_at'];

	/**
	 * Generates a random string using the libc pseudo-random number generator.
	 * 
	 * @return String The randomly generated password.
	 */
	public static function randomPassword() {
		return dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	}
	
	/**
	 * Returns the subscriptions associated to this user.
	 * 
	 * @return Collection
	 */
	public function alertSubscriptions() {
		return $this->hasMany('AlertSubscription', 'user');
	}
}
