<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Create table
		Schema::create('alerts', function($table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->integer('sensor')->unsigned();
			$table->enum('operation', ['<', '>', '>=', '<=']);
			$table->integer('value');
			$table->tinyInteger('timespan')->unsigned(5);
			$table->boolean('resilient_trigger')->default(0);
			$table->integer('user')->unsigned();
			$table->boolean('active')->default(1);
			$table->boolean('activated')->default(0);
			$table->timestamps();
			
			$table->foreign('sensor')->references('id')->on('sensors');
			$table->foreign('user')->references('id')->on('users');
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
		Schema::drop('alerts');
	}

}
