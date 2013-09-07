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
			$table->string('IP_address', 32)->nullable();
			$table->string('browser', 64)->nullable();
			$table->string('platform', 64)->nullable();
			$table->boolean('bounced')->default(0);
			$table->boolean('unsubscribed')->default(0);
			$table->boolean('read')->default(0);
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
