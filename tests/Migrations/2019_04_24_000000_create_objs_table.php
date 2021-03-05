<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('post_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('model_type')->nullable();
            $table->string('tag')->nullable();
            $table->string('tags')->nullable();
            $table->string('tags_cast')->nullable();
            $table->text('file')->nullable();
            $table->text('file_cast')->nullable();
            $table->text('files')->nullable();
            $table->text('files_cast')->nullable();
            $table->text('image')->nullable();
            $table->text('image_cast')->nullable();
            $table->text('images')->nullable();
            $table->text('place')->nullable();
            $table->text('place_cast')->nullable();
            $table->text('places')->nullable();
            $table->text('places_cast')->nullable();
            $table->text('multiform')->nullable();

            //for DateRange
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->datetime('start_datetime')->nullable();
            $table->datetime('end_datetime')->nullable();

            //for filters
            $table->string('title')->nullable(); //empty fitler
            $table->string('equal')->nullable();
            $table->string('greater')->nullable(); //should work for >= too
            $table->integer('lower')->nullable();   //should work for <= too
            $table->string('like')->nullable();
            $table->string('startswith')->nullable();
            $table->string('endswith')->nullable();
            $table->datetime('between')->nullable();
            $table->string('in')->nullable();

            $table->timestamps();
        });

        Schema::create('file_obj', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->unsigned()->nullable();
            $table->string('model_type')->nullable();
            $table->integer('obj_id')->unsigned()->index()->nullable();
            $table->integer('file_id')->unsigned()->index()->nullable();
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
