<?php namespace App\Http\Controllers;

use Input;
use App\Models\Sensor;
use App\Library\QueryParams;
use App\Library\Reporter;
use App\Library\ReporterException;

/**
 * A controller reporting on different sensor logs.
 *
 * @author garrettshevach@gmail.com
 *
 */
class ReportingController extends Controller {

	/**
	 * Query the most recent number of rows for 
	 */
	public function mostRecent() {
		if(!Input::has('sensors') || !is_array(Input::get('sensors')))
			return self::bad_request('The "sensors" field is a required array of sensors to query!');
		
		$params = new QueryParams(Input::only('sensors', 'aggregate', 'absolute', 'order_by', 'rows'));

		$skip = 0;
		if(Input::has('skip') && is_numeric(Input::get('skip')) && Input::get('skip') > 0) {
			$skip = Input::get('skip');
			$time = date('Y-m-d H:i:s', strtotime('- ' . intval(Input::get('skip')) . ' minutes'));
			$params->setToTime($time);		
		}
		
		if(Input::has('minutes') && is_numeric(Input::get('minutes')) && Input::get('minutes') > 0) {
			$minutes = Input::get('minutes');
			$minutes = $skip + $minutes;
			$time = date('Y-m-d H:i:s', strtotime('- ' . intval($minutes) . ' minutes'));	
			$params->setFromTime($time);
		}

		return $this->query($params);
	}
	
	/**
	 * Create a custom query for retrieving sensor logs from the database.
	 * GET /Reporting/Custom
	 * 
	 * @return Response
	 */
	public function custom() {
	    if(!Input::has('sensors') || !is_array(Input::get('sensors')))
	        return self::bad_request('The "sensors" field is a required array of sensors to query!');
	    
		return $this->query(new QueryParams(Input::all()));
	}
	
	/**
	 * Query a set of sensors for the log information.
	 * GET /Reporting/Query
	 * 
	 * @return Response
	 */
	public function query(QueryParams $params) {
		try {
			$reporter = new Reporter($params);
			return self::ok(null, $reporter->get());
		} catch(ReporterException $e) {
			return self::bad_request($e->getMessage());
		}
	}
}