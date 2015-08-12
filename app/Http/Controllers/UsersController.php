<?php
namespace App\Http\Controllers;

use Hash;
use Input;
use JWTAuth;
use Mail;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;

/**
 * A controller for adding, editing, getting, and deleting users.
 *
 * @author garrettshevach@gmail.com
 *        
 */
class UsersController extends Controller
{
    
    use ResetsPasswords;

    /**
     * Initialize a UsersController
     *
     * @param Guard $auth            
     * @param PasswordBroker $passwords            
     */
    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;
    }
    
    /**
     * Refresh a user's JWT so long as it's within the refresh limit.
     *
     * @return Response
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            /*
             * This exception means that the provided token has expired.
             * Therefore, we return an authentication timeout response.
             */
            return self::authenticate_timeout('The provided authentication token has expired!');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            /*
             * This exception means either no authentication token was provided, or one
             * was provided and it was invalid. Therefore, we return an unauthorized response.
             */
            return self::unauthorized('Invalid authentication token!');
        }
        
        return self::ok('Successfully refreshed your token.', [
            'auth_token' => $token
        ]);
    }

    /**
     * If the user is authenticated, it will return back with a token for later authentication.
     *
     * @return Response
     */
    public function login(Request $request)
    {
        // Make sure an email and password was provided
        if (Input::has('name') && Input::has('password')) {
            $creds = [
                'name' => Input::get('name'),
                'password' => Input::get('password'),
                'active' => true
            ];
            if ($token = JWTAuth::attempt($creds))
                return self::ok('Successfully authenticated!', [
                    'auth_token' => $token
                ]);
        }
        return self::unauthorized('Invalid email/password combination!');
    }

    /**
     * Handle a request to issue a password reset.
     * POST /Users/SendPasswordReset
     *
     * @return Response
     */
    public function sendPasswordReset(Request $request)
    {
        $response = $this->passwords->sendResetLink($request->only('email'), function ($message) {
            $message->subject('Password Reminder');
        });
        
        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return self::ok('An email has been successfully sent to the provided email address for resetting your password.');
            
            case PasswordBroker::INVALID_USER:
                return self::bad_request('There was no user associated to the provided email address!');
        }
    }

    /**
     * Reset a user's password.
     * POST /Users/ResetPassword
     *
     * @return Response
     */
    public function resetPassword($token)
    {
        $validator = Validator::make(Input::all(), [
            'email' => 'required|email',
            'password' => 'required|min:7',
            'password_confirmation' => 'required|min:7'
        ]);
        if ($validator->fails())
            return self::bad_request('Could not change your password!', $validator->messages());
            
            // return array_merge(Input::only('email', 'password', 'password_confirmation'), ['token' => $token]);
        $response = $this->passwords->reset(array_merge(Input::only('email', 'password', 'password_confirmation'), [
            'token' => $token
        ]), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->updateUniques();
        });
        switch ($response) {
            case PasswordBroker::INVALID_PASSWORD:
                return self::bad_request('Your confirmation password does not match your new password!');
            
            case PasswordBroker::INVALID_TOKEN:
                return self::bad_request('Either the email address associated to this reset request is incorrect, or the request is no longer valid!');
            
            case PasswordBroker::PASSWORD_RESET:
                return self::ok('Successfully saved your password.');
        }
    }

    /**
     * Return all users in the system.
     *
     * @return Response
     */
    public function all()
    {
        return self::ok(null, $users = User::where('permission', '!=', 'super admin')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // Create the new user model
        $user = new User();
        $user->name = Input::get('name');
        $user->email = Input::get('email');
        $user->permission = Input::get('permission');
        $user->active = true;
        $password = User::randomPassword();
        $user->password = Hash::make($password);
        
        if (! $user->save())
            return self::bad_request('Failed to create the new user!', $user->errors());
            
            // If a welcome email needs to be sent out.
        if (Input::get('welcome_email')) {
            Mail::send('emails.users.welcome', [
                'username' => $user->name,
                'password' => $password
            ], function ($message) use($user) {
                $message->to($user->email)->subject('Welcome!');
            });
            
            if (count(Mail::failures()) > 0)
                return self::created('Successfully created the user, but failed to send the welcome email!', $user);
        }
        
        return self::created('Successfully created the new user.', $user);
    }

    /**
     * Return the current user.
     * The function gets the current user from the Authenticate middleware.
     *
     * @param unknown $user            
     */
    public function self(Request $request)
    {
        return self::ok(null, $request->input('user'));
    }

    /**
     * Edit the current user's profile settings.
     *
     * @param Request $request            
     */
    public function manage(Request $request)
    {
        $user = $request->input('user');
        $user->email = Input::get('email');
        if ($user->updateUniques())
            return self::ok(null, $user);
        return self::bad_request('Could not save your information!', $user->errors());
    }

    /**
     * Change the current user's password.
     *
     * @param Request $request            
     */
    public function changePassword(Request $request)
    {
        $user = $request->input('user');
        $validator = Validator::make(Input::all(), [
            'old_password' => 'required|min:7',
            'new_password' => 'required|min:7',
            'confirm_password' => 'required|min:7'
        ]);
        if ($validator->fails())
            return self::bad_request('Could not change your password!', $validator->messages());
        
        if (Input::get('new_password') !== Input::get('confirm_password'))
            return self::bad_request('Could not change your password!', [
                'confirm_password' => [
                    'Does not match your new password!'
                ]
            ]);
        
        if (! Hash::check(Input::get('old_password'), $user->password))
            return self::bad_request('Could not change your password!', [
                'old_password' => [
                    'Does not match your current password!'
                ]
            ]);
        
        $user->password = Hash::make(Input::get('new_password'));
        if ($user->forceSave())
            return self::ok('Successfully changed your password.');
        return self::internal_server_error('Failed to save your new password!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id            
     * @return Response
     */
    public function show($id)
    {
        // Get the user
        $user = User::find($id);
        // Only if the retrieved user is not a super admin, then return an OK response.
        if ($user && $user->permission !== 'super admin')
            return self::ok(null, $user);
        return self::not_found('The user was not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id            
     * @return Response
     */
    public function update($id)
    {
        // Get the user
        $user = User::find($id);
        // Only if the retrieved user is not a super admin, then edit the user.
        if ($user && $user->permission !== 'super admin') {
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->permission = Input::get('permission');
            $user->active = (Input::get('active')) ? true : false;
            // Attempt to save the model's data.
            if (! $user->updateUniques())
                return self::bad_request('Failed to save your changes!', $user->errors());
            return self::ok('Successfully saved your changes.');
        }
        return self::not_found('The user was not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id            
     * @return Response
     */
    public function destroy($id)
    {
        // Get the user
        $user = User::find($id);
        // Only if the retrieved user is not a super admin, then edit the user.
        if ($user->permission !== 'super admin') {
            try {
                $user->delete();
            } catch (Illuminate\Database\QueryException $e) {
                // If the error is a foreign key integrity constraint
                if ($e->errorInfo[0] == '23000')
                    return self::conflict('Could not delete this user because there is other data that relies on it! You must delete this data before deleting the user!');
                    // Internal Server error has occured otherwise
                return self::internal_server_error();
            }
            
            return self::ok('Successfully deleted the user.');
        }
        
        return self::not_found('The user was not found!');
    }
}
