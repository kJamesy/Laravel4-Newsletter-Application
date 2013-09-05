<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersGroupsTable extends Migration {

	public function up()
	{
		Schema::create('users_groups', function($table)
		{
			$table->integer('user_id')->unsigned();
			$table->integer('group_id')->unsigned();

			$table->engine = 'InnoDB';
			$table->primary(array('user_id', 'group_id'));
		});
	}

	public function down()
	{
		Schema::drop('users_groups');
	}

}