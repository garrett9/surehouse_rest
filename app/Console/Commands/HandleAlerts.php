<?php namespace App\Console\Commands;

use Setting;
use App\Models\Alert;
use App\Models\SensorLog;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Http\Controllers\SettingsController;

/**
 * A console command for monitoring whether or not alerts have been met in the system. If so, then the alert is set to activated.
 * 
 * @author garrettshevach@gmail.com
 */
class HandleAlerts extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'surehouse:alerts';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Handles the monitoring of alerts, as well as putting the system in Resilient mode if one of the alerts has been met.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
	   // Start by querying all alerts in the system
	   $alerts = Alert::all();
	   // Then loop through each alert to see if it has been met
	   foreach($alerts as $alert) {
	       $minTime = date('Y-m-d H:i:s', strtotime('- ' . $alert->timespan . ' minutes'));
	       $value = SensorLog::where('id', '=', $alert->sensor)->where('timestamp', '>=', $minTime)->avg('value');
	       
	       $met = false;
	       // If the value is null, then the alert has not been met
	       if($value !== null) {
    	       // Now, we test if the value triggers the alert
    	       switch($alert->operation) {
    	           case '<':
    	               if($alert->value < $value)
    	                   $met = true;
    	               break;
    	           case '>':
    	               if($alert->value > $value)
    	                   $met = true;
    	               break;
    	           case '<=':
    	               if($alert->value <= $value) 
    	                   $met = true;
    	              break;
    	           case '>=':
    	               if($alert->value >= $value) 
    	                   $met = true;
    	               break;
    	       }
	       }

	       // Only if the alert has been met can we do something
	       if($met) {
	           // If the alert is a resilient trigger, turn on resilient mode in the settings
	           if($alert->resilient_trigger)
	               Setting::set(SettingsController::RESILIENT_MODE, true);
	           // Set the alert to activated
	           $alert->activated = true;
	           // And then save the model.
	           $alert->updateUniques();
	       }
	       else {
	           // The alert has not been met, so see if it was originally activated
	           if($alert->activated) {
	               // The alert was activated, so make it not activated
	               $alert->activated = false;
	               // Also, if it's a resilient trigger, then turn off resilient mode
	               if($alert->resilient_trigger)
	                   Setting::set(SettingsController::RESILIENT_MODE, false);
	               // And then save the model
	               $alert->updateUniques();
	           }
	       }
	   }
	}
}
