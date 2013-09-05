<?php

use Illuminate\Database\Migrations\Migration;

class CreateListsTable extends Migration {

	public function up()
	{
		Schema::create('lists', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('name', 128);
			$table->boolean('active')->default(1);
			$table->timestamps();
		});
	}


	public function down()
	{
		Schema::drop('lists');
	}

}