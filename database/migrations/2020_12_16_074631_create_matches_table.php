<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_division')->unsigned()->nullable();
            $table->bigInteger('id_team_home')->unsigned()->nullable();
            $table->bigInteger('id_team_guest')->unsigned()->nullable();
            $table->bigInteger('id_tournament')->unsigned()->nullable();
            $table->bigInteger('id_stage')->unsigned()->nullable();

            $table->integer('count_goal_team_home')->unsigned()->default(0);
            $table->integer('count_goal_team_guest')->unsigned()->default(0);

            $table->foreign('id_team_home')->on('teams')
                ->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->foreign('id_team_guest')->on('teams')
                ->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->foreign('id_tournament')->on('tournaments')
                ->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->foreign('id_stage')->on('stages')
                ->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
