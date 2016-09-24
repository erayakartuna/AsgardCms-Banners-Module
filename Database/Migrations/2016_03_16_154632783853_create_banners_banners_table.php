<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersBannersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banners__banners', function(Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
			$table->string("title");
			$table->string("url");
			$table->boolean("target");
			$table->integer('group_id')->unsigned();

            // Your fields
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('banners__banners');
	}
}
