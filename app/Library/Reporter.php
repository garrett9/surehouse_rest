<?php namespace App\Library;

use DB;
use App\Models\Sensor;
use App\Library\ReporterException;

/**
 * A custom library used for handling database interactions and intelligence when querying sensor log information.
 *
 * @author garrettshevach@gmail.com
 *
 */
class Reporter {
	
	const SENSOR_LOGS = 'sensor_logs';
	
	/**
	 * The QueryParams associated to this Reporter instance.
	 * 
	 * @var QueryParams
	 */
	private $params;
	
	/**
	 * An associative array of sensor names to their IDs in the database.
	 * 
	 * @var array
	 */
	private $sensorMap;
	
	/**
	 * The class's current instance of a QueryBuilder.
	 * 
	 * @var QueryBuilder
	 */
	private $qry;
	
	/**
	 * To initialize an instance of the Reporter, then you must pass it a set of QueryParams;
	 * 
	 * @param QueryParams $params
	 */
	public function __construct(QueryParams $params) {
		$this->errors = [];
		$this->params = $params;
		
		$this->sensorMap = Sensor::whereIn('name', $params->getSensors())->lists('name', 'id');
		foreach($params->getSensors() as $sensor) {
			if(!in_array($sensor, $this->sensorMap))
				throw new ReporterException('Invalid sensor name of "' . $sensor . '"!');
		}

		$base = $this->buildBaseTable();
		$this->qry = DB::table(DB::raw('(' . $base->toSql() . ') Main'));
		if($this->params->getAggregate()) {
			$this->qry = $this->qry->select('Main.Time')->orderBy('Main.Time', $params->getOrderBy());
			if($params->getRows() > 0)
				$this->qry = $this->qry->take($this->params->getRows());
		}
		else
			$this->qry = $this->qry->take(1);
		$this->qry->mergeBindings($base);
		
		foreach($this->sensorMap as $id=>$name) {
			$tempQry = $this->buildSensorTable($id);
			$this->qry->mergeBindings($tempQry);
			$this->qry = $this->qry->leftJoin(DB::raw('(' . $tempQry->toSql() . ') ' . $name),
					($params->getAggregate()) ? $name . '.Time' : DB::raw(1),
					'=',
					($params->getAggregate()) ? 'Main.Time' : DB::raw(1));
			$this->qry->addSelect(DB::raw($name . '.value as `' . $name . '`'));
		}
	}
	
	/**
	 * Retrieve the results from the reporter.
	 */
	public function get() {
		return $this->qry->get();
	}
	
	/**
	 * Construct the base table that all other tables will build off of.
	 */
	private function buildBaseTable() {
		$base = DB::table(self::SENSOR_LOGS);
		//If we are computing an aggregate, then we add the following.
		if($this->params->getAggregate())
			$base = $base->select($this->buildTimestampColumn())->groupBy('Time');
	
		/* 
		 * So that we don't query every timestamp from the database, we need to get the minimum and maximum timestamps for the given data points.
		 * We then set these timestamps as constraints to the base table.
		 */
		if($timestamps = $this->getTimestamps()) {
			if($timestamps->max) 
				$base->where('timestamp', '<=', $timestamps->max);
			if($timestamps->min) 
				$base->where('timestamp', '>=', $timestamps->min);
		}
		
		/*
		 * Check to see if a toTime or fromTime was added as query parameters. 
		 */
		if($this->params->getFromTime() != null)
			$base->where('timestamp', '>=', $this->params->getFromTime());
		if($this->params->getToTime() != null)
			$base->where('timestamp', '<=', $this->params->getToTime());
		
		return $base;
	}
	
	/**
	 * Construct the table of a single sensor query.
	 */
	private function buildSensorTable($sensor) {
		$absolute = (($this->params->getAbsolute()) ? 'ABS(ROUND(AVG(value), 2))' : 'ROUND(AVG(value), 2)') . ' as value';
		$tempQry = DB::table(self::SENSOR_LOGS)->select(DB::raw($absolute))->where('id', '=', $sensor);
		
		if($this->params->getAggregate())
			$tempQry = $tempQry->addSelect($this->buildTimestampColumn())->groupBy('Time');
		return $tempQry;
	}
	
	/**
	 * Retrieve the minimum and maximum timestamps in the content of all of the provided sensors.
	 *
	 * @return stdClass
	 */
	private function getTimestamps() {
		return DB::table(self::SENSOR_LOGS)->whereIn('id', array_keys($this->sensorMap))
									   ->select(DB::raw('MAX(timestamp) as max, MIN(timestamp) as min'))
									   ->first();
	}
	
	/**
	 * Returns the SQL syntax for constructing a column of timestamps that increments by the specified number of minutes for the aggregate value.
	 * 
	 * @return string
	 */
	private function buildTimestampColumn() {
		return DB::raw('from_unixtime(FLOOR(unix_timestamp(timestamp) / (60*' . $this->params->getAggregate() . ')) * 60 * ' . $this->params->getAggregate() . ') as Time');
	}
}