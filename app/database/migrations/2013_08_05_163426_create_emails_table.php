<?php

use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration {

	public function up()
	{
		Schema::create('emails', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('from', 255);
			$table->string('subject', 255);
			$table->text('message');
			$table->boolean('deleted')->default(0);
			$table->timestamps();				
		});
	}

	public function down()
	{
		Schema::drop('emails');
	}

}