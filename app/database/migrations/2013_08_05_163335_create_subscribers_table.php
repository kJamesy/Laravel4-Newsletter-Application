<?php

use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable {

	public function up()
	{
		Schema::create('subscribers', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('first_name', 128);
			$table->string('last_name', 128);
			$table->string('email')->unique();
			$table->boolean('active');
			$table->timestamps();						
		});
	}


	public function down()
	{
		Schema::drop('subscribers');
	}

}