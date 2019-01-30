<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExemptStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exempt_store', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('user_id')->unsigned();
            //$table->foreign('user_id')->references('id')->on('db_cua.users');
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('db_cua.stores');
            $table->string('store_name',45);
            $table->integer('month_num')->unsigned();
            $table->string('month_txt',45);
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
        Schema::dropIfExists('exempt_store');
    }
}
