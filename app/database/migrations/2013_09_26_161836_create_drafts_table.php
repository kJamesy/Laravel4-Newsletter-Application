<?php

use Illuminate\Database\Migrations\Migration;

class CreateDraftsTable extends Migration {

	public function up()
	{
		Schema::create('drafts', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('subject', 255);
			$table->text('message');
			$table->timestamps();				
		});
	}

	public function down()
	{
		Schema::drop('drafts');

	}

}