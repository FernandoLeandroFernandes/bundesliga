<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			
			$table->integer('id');
			$table->integer('league_id')->unsigned();
			$table->integer('team1_id')->unsigned();
			$table->integer('team2_id')->unsigned();
			$table->integer('scoreTeam1');
			$table->integer('scoreTeam2');
			$table->integer('winner_team_id')->unsigned()->nullable();
			$table->dateTime('timeUTC');
			$table->boolean('finished');
			$table->timestamps();

			$table->foreign('team1_id')->references('id')->on('teams');
			$table->foreign('team2_id')->references('id')->on('teams');
			$table->foreign('winner_team_id')->references('id')->on('teams');
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
