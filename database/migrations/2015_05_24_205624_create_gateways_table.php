<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatewaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Create table
		Schema::create('gateways', function($table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('IP', 15);
			$table->smallInteger('port');
			$table->enum('type', array('eGuage', 'WEL'));
			$table->timestamps();
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
		Schema::drop('gateways');
	}

}
