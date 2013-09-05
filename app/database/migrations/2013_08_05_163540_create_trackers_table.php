<?php

use Illuminate\Database\Migrations\Migration;

class CreateTrackersTable extends Migration {

	public function up()
	{
		Schema::create('trackers', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('subscriber_id')->unsigned();
			$table->integer('email_id')->unsigned();
			$table->string('IP_address', 32);
			$table->string('browser', 64);
			$table->string('platform', 64);
			$table->boolean('bounced');
			$table->boolean('unsubscribed');
			$table->boolean('read');
			$table->timestamp('read_at');
			$table->timestamps();

			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
			$table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('trackers');
	}

}