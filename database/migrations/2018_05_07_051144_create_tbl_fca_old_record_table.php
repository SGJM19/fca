<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblFcaOldRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fca_old_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fca_id')->unsigned()->nullable();
            $table->foreign('fca_id')->references('id')->on('tbl_fca');
            $table->string('audit_by_name',455);
            $table->string('store_id');
            $table->float('percentage',3,2);
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
        Schema::dropIfExists('tbl_fca_old_record');
    }
}
