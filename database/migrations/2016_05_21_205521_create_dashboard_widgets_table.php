<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardWidgetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dashboard_widgets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dashboard_id');
			$table->string('service');
			$table->text('configuration')->nullable();
			$table->integer('order')->nullable();
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
		Schema::drop('dashboard_widgets');
	}

}
