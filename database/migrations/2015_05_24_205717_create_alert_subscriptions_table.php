<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Create table
		Schema::create('alert_subscriptions', function($table) {
			$table->integer('id')->unsigned();
			$table->integer('user')->unsigned();
			$table->boolean('email')->default(0);
			$table->boolean('alerted')->default(0);
			$table->timestamps();
			
			$table->primary(['id', 'user']);
			$table->foreign('id')->references('id')->on('alerts');
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
		Schema::drop('alert_subscriptions');
	}

}
