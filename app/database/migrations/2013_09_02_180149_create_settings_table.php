<?php

use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('sitename', 255);
			$table->timestamps();				
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}

}