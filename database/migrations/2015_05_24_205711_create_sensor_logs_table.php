<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Create table
		Schema::create('sensor_logs', function($table) {
			$table->integer('id')->unsigned();
			$table->datetime('timestamp');
			$table->integer('value');
			
			$table->primary(['id', 'timestamp']);
			$table->foreign('id')->references('id')->on('sensors');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Drop table
		Schema::drop('sensor_logs');
	}

}
