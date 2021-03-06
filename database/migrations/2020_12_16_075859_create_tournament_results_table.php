<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_results', function (Blueprint $table) {
            $table->bigInteger('id_team')->unsigned();
            $table->bigInteger('id_tournament')->unsigned();
            $table->integer('points');

            $table->foreign('id_team')->on('teams')
                ->references('id')->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->foreign('id_tournament')
                ->on('tournaments')->references('id')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->primary(['id_team', 'id_tournament']);

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
        Schema::dropIfExists('tournament_results');
    }
}
