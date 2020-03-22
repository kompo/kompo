<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('model_type')->nullable();
            $table->string('title')->nullable();
            $table->string('tag')->nullable();
            $table->string('tags')->nullable();
            $table->string('tags_cast')->nullable();
            $table->text('file')->nullable();
            $table->text('file_cast')->nullable();
            $table->text('files')->nullable();
            $table->text('files_cast')->nullable();
            $table->text('image')->nullable();
            $table->text('place')->nullable();
            $table->text('place_cast')->nullable();
            $table->text('places')->nullable();
            $table->text('places_cast')->nullable();
            $table->text('multiform')->nullable();
            $table->timestamps();
        });

        Schema::create('file_obj', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('model_type')->nullable();
            $table->integer('obj_id')->unsigned()->index();
            $table->integer('file_id')->unsigned()->index();
            $table->unsignedInteger('order')->nullable();
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
        Schema::dropIfExists('file_obj');
        Schema::dropIfExists('objs');
    }
}
