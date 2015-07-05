<?php namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Sensor;

class SeedSensorLogs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'surehouse:seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Given a sensor ID, this command will generate random log data to associate with this sensor.';

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
		//First, get the sensor given.
		if($sensor = Sensor::find($this->argument('id'))) {
			$this->info("Retrieved the following Data Point:\n\n" .
						"Name:\t\t\t" . $sensor->name . "\n" .
						"Display Name:\t\t" . $sensor->display_name . "\n" .
						"Unit Type:\t\t" . $sensor->units . "\n" .
						"Recorder:\t\t" . $sensor->recorder);
			$rows = ($this->option('rows') && is_numeric($this->option('rows'))) ? $this->option('rows') : 100;
			$min = ($this->option('min') && is_numeric($this->option('min'))) ? $this->option('min') : 0;
			$max = ($this->option('max') && is_numeric($this->option('max'))) ? $this->option('max') : 100;
			$time = ($this->option('start') && ($start = strtotime($this->option('start')))) ? $start : date('Y-m-d H:i:s');
			if($this->confirm('Do you wish to generate ' . (string)$rows . ' random records for this sensor? [yes|no]')) {
				$logs = array();
				for($i = 0; $i < $rows; $i++) {
					$rand = rand($min - 1, $max - 1);
					array_push($logs,
						[
							'id' => $this->argument('id'),
							'timestamp' => date('Y-m-d H:i:s', $time),
							'value' => $rand
						]
					);
					$time = strtotime('-1 minutes', $time);
				}
				
				DB::table('sensor_logs')->insert($logs);
				$this->info('Successfully generated ' . $rows . ' rows.');
			}
			else
				$this->info('Command Terminated.');
		}
		else {
			$this->error('There exists no sensor with an ID of ' . $this->argument('id'));
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::REQUIRED, 'The Data Point ID to create random logs for.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array
			(
				array('rows', null, InputOption::VALUE_OPTIONAL, 'The number of rows to generate for the sensor (default is 100).', null),
				array('min', null, InputOption::VALUE_OPTIONAL, 'The minimum value the generated value can be (default 0).', 0),
				array('max', null, InputOption::VALUE_OPTIONAL, 'The maximum value the generated value can be (default 100).', 100),
				array('start', null, InputOption::VALUE_OPTIONAL, 'Seeded data will start at this timestamp, and then minues one minute for the number of specified rows to seed.', date('Y-m-d H:i:s'))
			);
	}

}
