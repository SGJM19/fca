<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblFcaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fca_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fca_id')->unsigned()->nullable();
            $table->foreign('fca_id')->references('id')->on('tbl_fca');
            $table->string('file_name',355);
            $table->text('orig_file_name');
            $table->integer('file_size');
            $table->text('file_path');
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
        Schema::dropIfExists('tbl_fca_files');
    }
}
