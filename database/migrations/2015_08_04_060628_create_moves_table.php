<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* 
         * actions:
         * 1 - placement (source tile is origin, move (length) spaces in direction of target)
         * 2 - hit
         */
        Schema::create('moves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('game_id')->unsigned();
            $table->enum('action', ['put', 'hit']);
            $table->string('source_tile', 3)->nullable();
            $table->integer('ship_type')->nullable();
            $table->string('target_tile', 3)->nullable();
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
        Schema::drop('moves');
    }
}
