<?php

namespace App\Http\Controllers;

use Input;
use Config;
use Setting;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * A controller for getting settings regarding the system.
 *
 * @author garrettshevach@gmail.com
 */
class SettingsController extends Controller
{

    const RESILIENT_MODE = 'surehouse.resilient_mode';

    const ADDRESS = 'surehouse.address';

    /**
     * Set the status of the house (sustainable/resilient) given a boolean.
     * (true = resilient, false = sustainable)
     *
     * @return Response
     */
    public function setResilientMode($boolean)
    {
        if ($boolean) {
            Setting::set(self::RESILIENT_MODE, 1);
            $message = 'The system is now in resilient mode!';
        } else {
            Setting::set(self::RESILIENT_MODE, 0);
            $message = 'The system has now been restored to sustainable mode.';
        }
        return self::ok($message);
    }

    /**
     * Set the address of the house via POST.
     *
     * @return Response
     */
    public function setAddress()
    {
        // TODO check length of address given
        if (Input::has('address')) {
            Setting::set(self::ADDRESS, Input::get('address'));
            return self::ok('Successfully saved your address.');
        }
        return self::bad_request('Failed to save your address.', ['address' => ['This field is a required field.']]);
    }

    /**
     * Return the settings regarding the system.
     *
     * @return Response
     */
    public function getSettings()
    {
        return self::ok(null, [
            'address' => Setting::get(self::ADDRESS, Config::get(self::ADDRESS)),
            'resilient_mode' => Setting::get(self::RESILIENT_MODE, Config::get(self::RESILIENT_MODE))
        ]);
    }
}
