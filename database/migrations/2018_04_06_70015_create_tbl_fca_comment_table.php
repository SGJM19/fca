<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblFcaCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fca_comment', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comments');
            $table->integer('fca_id')->unsigned()->nullable();
            $table->foreign('fca_id')->references('id')->on('tbl_fca');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('db_cua.users');
            $table->boolean('isDeleted');
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
        Schema::dropIfExists('tbl_fca_comment');
    }
}
