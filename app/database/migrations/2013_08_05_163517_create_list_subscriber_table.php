<?php

use Illuminate\Database\Migrations\Migration;

class CreateListSubscriberTable extends Migration {

	public function up()
	{
		Schema::create('list_subscriber', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('list_id')->unsigned();
			$table->integer('subscriber_id')->unsigned();
			$table->timestamps();

			$table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('list_subscriber');
	}
}