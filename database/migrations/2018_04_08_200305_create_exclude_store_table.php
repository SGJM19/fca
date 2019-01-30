<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcludeStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclude_store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('db_cua.users');
            $table->string('exluded_by');
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('db_cua.stores');
            $table->integer('month_tobe_exluded')->unsigned()->nullable();
            $table->boolean('isExluded')->default(0); //1 means exluded, 0 no
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
        Schema::dropIfExists('exclude_store');
    }
}
