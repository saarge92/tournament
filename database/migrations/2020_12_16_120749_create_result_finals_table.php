<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultFinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_finals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_team')->unsigned()->nullable();
            $table->bigInteger('id_tournament')->unsigned()->nullable();
            $table->integer('place')->unsigned();

            $table->foreign('id_team')->on('teams')
                ->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->foreign('id_tournament')->on('tournaments')
                ->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');

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
        Schema::dropIfExists('result_finals');
    }
}
