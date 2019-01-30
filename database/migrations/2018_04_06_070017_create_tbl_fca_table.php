<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblFcaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fca', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('db_cua.stores');
            $table->string('store',45);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('year_filed');
            $table->integer('month_filed_num');
            $table->string('month_filed_string');    
            $table->float('percentage',3,2);
            $table->text('remarks');
            $table->datetime('expiration_date');
            $table->string('audit_by');
            $table->boolean('isPassed')->default(0);
            $table->boolean('isEmailed')->default(0);
            $table->boolean('isReeval')->default(0);
            $table->boolean('isRead')->default(0);
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
        Schema::dropIfExists('tbl_fca');
    }
}
