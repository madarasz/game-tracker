<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameSessionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_session_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_session_id');
            $table->integer('user_id');
            $table->integer('score');
            $table->string('notes');
            $table->boolean('winner')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_session_user');
    }
}
