<?php namespace App\Library;

/**
 * A representation of Query Parameters when a user makes a request for reporting on sensor logs.
 *
 * @author garrettshevach@gmail.com
 *
 */
class QueryParams {
	
	/**
	 * The array of sensor names to query from.
	 * 
	 * @var array
	 */
	private $sensors;
	
	/**
	 * The number of minutes to aggregate when returning rows.
	 * 
	 * @var number
	 */
	private $aggregate;
	
	/**
	 * The time to query from.
	 * 
	 * @var string
	 */
	private $fromTime;
	
	
	/**
	 * The time to query to.
	 * 
	 * @var string
	 */
	private $toTime;
	
	/**
	 * Whether or not to return absolute values.
	 * 
	 * @var boolean
	 */
	private $absolute;
	
	/**
	 * The number of rows to query.
	 * 
	 * @var number
	 */
	private $rows;
	
	/**
	 * What the results of the query will be ordered by.
	 * 
	 * @var string
	 */
	private $orderBy;
	
	
	/**
	 * The class's constructor requires an array of sensor names to query from.
	 * 
	 * @param array $sensors
	 */
	public function __construct(array $params=null) {
		$this->sensors = [];
		$this->errors = [];
		$this->absolute = false;
		$this->rows = -1;
		$this->setOrderBy();
		
		$this->load($params);
	}
	
	/**
	 * Given an associative array, this function will extract all query parameters that should be loaded into the class.
	 * 
	 * @param array $params
	 */
	public function load(array $params=null) {
		if($params) {
			foreach($params as $key=>$value) {
				switch($key) {
					case 'sensors':
						$this->sensors = $value;
						break;
						
					case 'aggregate':
						$this->setAggregate($value);
						break;
			
					case 'absolute':
						$this->setAbsolute($value);
						break;
			
					case 'fromDate':
						$time = (isset($params['fromTime'])) ? $params['fromTime'] : null;
						$this->setFromTime($value, $time);
						break;
			
					case 'toDate':
						$time = (isset($params['toTime'])) ? $params['toTime'] : null;
						$this->setToTime($value, $time);
						break;
			
					case 'rows':
						$this->setRows($value);
						break;
						
					case 'order_by':
						$this->setOrderBy($value);
						break;
				}
			}
		}
	}
	
	/**
	 * Set the class's sensors.
	 * 
	 * @param array $sensors
	 */
	public function setSensors(array $sensors) {
		$this->sensors = $sensors;
	}
	
	/**
	 * Get the class's sensors.
	 * 
	 * @return array
	 */
	public function getSensors() {
		return $this->sensors;
	}
	
	/**
	 * Return the aggregate value.
	 * 
	 * @return number
	 */
	public function getAggregate() {
		return $this->aggregate;
	}
	
	/**
	 * Set the aggregate value. If an invalid integer is given, or an integer less than 1, then the aggregate will default to 0.
	 * 
	 * @param number $aggregate
	 */
	public function setAggregate($aggregate) {
		$this->aggregate = (is_numeric($aggregate) && $aggregate > 0) ? $aggregate : 0;
	}
	
	/**
	 * Set how the results should be ordered. (ASC = ascending, DESC = descending)
	 * 
	 * @param string $orderBy
	 */
	public function setOrderBy($orderBy='asc') {
		$orderBy = strtolower($orderBy);
		if(!in_array($orderBy, ['desc', 'asc']))
			$this->orderBy = 'asc';
		else
			$this->orderBy = $orderBy;
	}
	
	/**
	 * Get how the results should be ordered.
	 * 
	 * @return string
	 */
	public function getOrderBy() {
		return $this->orderBy;
	}
	
	/**
	 * Get whether or not to return absolute values.
	 * 
	 * @return boolean
	 */
	public function getAbsolute() {
		return $this->absolute;
	}
	
	/**
	 * Set whether or not to return absolute values.
	 * 
	 * @param boolean $bool
	 */
	public function setAbsolute($bool) {
		if($bool)
			$this->absolute = true;
		else
			$this->absolue = false;
	}
	
	/**
	 * Set the number of rows to return.
	 * 
	 * @param number $rows
	 */
	public function setRows($rows) {
		$this->rows = intval($rows);
	}
	
	/**
	 * Get the number of rows that will be returned.
	 * 
	 * @return number
	 */
	public function getRows() {
		return $this->rows;
	}
	
	/**
	 * Return the query from time.
	 * 
	 * @var string
	 */
	public function getFromTime() {
		return $this->fromTime;
	}
	
	/**
	 * Set the query from time. An optional time is allowed with the date parameter to create a more precise timestamp.
	 * 
	 * @param string $fromDate
	 * @param string $fromTime
	 */
	public function setFromTime($fromDate, $fromTime=null) {
		$this->fromTime = $this->calcDate($fromDate, $fromTime);
	}
	
	/**
	 * Get the time to query to.
	 * 
	 * @return string
	 */
	public function getToTime() {
		return $this->toTime;
	}
	
	/**
	 * Set the time to query to. An optional time is allowed with the date parameter to create a more precise timestamp.
	 * 
	 * @param string $toDate
	 * @param string $toTime
	 */
	public function setToTime($toDate, $toTime=null) {
		$this->toTime = $this->calcDate($toDate, $toTime);
	}
	
	/**
	 * Given a from date and time, this function will attempt to combine the two together, and then return a single timestamp in the format of Y-m-d H:i:s (24 hour clock).
	 * If no time is given, then the returned timestamp will be always at 00:00AM (or 12AM).
	 * 
	 * @param unknown $fromDate
	 * @param string $fromTime
	 * @return string|NULL
	 */
	private function calcDate($date, $time=null) {
		if(is_string($date)) {
			if(is_string($time))
				$date .= ' ' . $time;
			if($timestamp = strtotime($date))
				return date('Y-m-d H:i:s', $timestamp);
		}
		return null;
	}
	
	/**
	 * Return a JSON representation of the class.
	 */
	public function __toString() {
		return json_encode([
			'aggregate' => $this->aggregate,
			'fromTime' => $this->fromTime,
			'toTime' => $this->toTime, 
			'absolute' => $this->absolute,
			'sensors' => $this->sensors
		]);
	}
}

