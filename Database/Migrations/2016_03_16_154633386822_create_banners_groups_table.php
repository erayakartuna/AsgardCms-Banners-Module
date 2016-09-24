<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersGroupsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banners__groups', function(Blueprint $table) {
			$table->engine = 'InnomyDB';
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
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
		Schema::drop('banners__groups');
	}
}
