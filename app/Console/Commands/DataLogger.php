<?php namespace App\Console\Commands;

use App\Models\Sensor;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DataLogger extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'data:log';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Collects data from all active sensors.';

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
		/*
		 * We start out by querying for all "active" sensors while also joining them
		 * with the gateway they're attached to.
		 */
		$sensors = Sensor::where('active', '=', true)
						  ->join('gateways', 'sensors.gateway', '=', 'gateways.id')
						  ->select('sensors.name', 'gateways.type', 'gateways.IP', 'gateways.port')
						  ->get();
		
		/*
		 * If the results are empty, this means that there were no
		 * active sensors in the database. Therefore, we exit here.
		 */
		if($sensors->isEmpty()) {
			$this->info('There are no active sensors to log data for!');
			return;
		}
		
		/*
		 * Now, we will loop through each sensor to query the 
		 * information associated to it. 
		 */
		foreach($sensors as $sensor) {
			
		}
		
		$this->info(json_encode($sensors));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
