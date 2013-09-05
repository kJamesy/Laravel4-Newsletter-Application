<?php

use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	public function up()
	{
		Schema::create('groups', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('permissions')->nullable();
			$table->timestamps();

			$table->engine = 'InnoDB';
			$table->unique('name');
		});
	}

	public function down()
	{
		Schema::drop('groups');
	}

}