<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersBannersTranslationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banners__banners_translations', function(Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('banners_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->string('url');
            $table->boolean('target');

            $table->unique(['banners_id', 'locale']);
            $table->foreign('banners_id')->references('id')->on('banners__banners')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('banners__banners_translations');
	}
}
