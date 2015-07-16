<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Create table
		Schema::create('sensors', function($table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('display_name');
			$table->string('units');
			$table->integer('gateway')->unsigned();
			$table->timestamps();
			
			$table->foreign('gateway')->references('id')->on('gateways');
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
		Schema::drop('sensors');
	}

}
