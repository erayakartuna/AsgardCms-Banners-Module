<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners__banners', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners__banners', function(Blueprint $table)
        {
            $table->dropColumn('sort_order');
        });
    }

}
